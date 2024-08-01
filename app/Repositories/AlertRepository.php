<?php

namespace App\Repositories;

use App\Jobs\NotificationNewInvite;
use App\Models\Alert;
use App\Models\AlertMessage;
use App\Models\Indicator;
use App\Models\LicenseIndicator;
use App\Models\Role;
use App\Models\Workspace;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class AlertRepository extends BaseRepository
{

    public function __construct()
    {
        parent::__construct(Alert::class);
    }

    public function getByWorkspace($filters, $workspaceId, $trashed = false)
    {
        $query = $this->model->newQuery();
        $columns = $filters['columns'] ?? ['*'];
        $take = $filters['take'] ?? $this->take;
        $search = $filters['search'] ?? false;
        $sortBy = $filters['sortBy'] ?? 'created_at';
        $sortDirection = $filters['sortDirection'] ?? 'DESC';
        $what = $filters['what'] ?? false;
        $gt = $filters['gt'] ?? false;
        $lt = $filters['lt'] ?? false;
        $where = $filters['where'] ?? false;
        $closed = $filters['closed'] ?? false;

        if ($search) {
            $query->where(function ($query) use ($search) {
                $searchFields = ($this->model->searchFields) ?? ['name'];
                foreach ($searchFields as $searchField) {
                    $query->orWhere($searchField, 'like', '%' . $search . '%');
                }
            });
        }
        if ($closed) {
            $query->where('closed_at', '!=', null);
        } else {
            $query->where('closed_at', null);
        }

        $currentUserId = auth()->user()->id;
        $currentRole = $this->getRoleName($workspaceId, $currentUserId);

        if (!in_array($currentRole, ['super_admin', 'admin'])) {
            $query->where('alert_manager_id', $currentUserId)
                ->orWhereJsonContains('observers_ids', $currentUserId);
        }

        if ($where) {
            $query->whereHas('indicators', function ($query) use ($where) {
                $query->where('filter_id', $where);
            });
        }
        if ($what) {
            $query->where('indicator', $what);
        }
        if ($gt && $lt) {
            $lt = Carbon::createFromTimestamp($lt) ?? null;
            $gt = Carbon::createFromTimestamp($gt) ?? null;
            if($lt && $gt){
                $query->whereBetween('alert_datetime', [$lt, $gt]);
            }
        }
        $workspace = Workspace::where('id', $workspaceId)->firstOrFail();
        $licenseIndicators = LicenseIndicator::where('license_id', $workspace->license_id)
            ->pluck('indicator_id')->toArray();
        $indicatorIds = Indicator::whereIn('id', $licenseIndicators)->pluck('id')->toArray();
        $query->whereIn('indicator', $indicatorIds);

        $query->withCount(['alertMessageRead as total_unread_messages']);
        $query->where('workspace_id', $workspaceId);
        $query->with('status', 'indicators', 'filter', 'satellite', 'alertImages');
        if ($trashed) {
            $query->onlyTrashed();
        }

        $query->orderBy($sortBy, $sortDirection);
        $alerts = $query->paginate($take, $columns);
        foreach ($alerts as $alert) {
            if ($alert->indicators && is_array($alert->indicators->description) && $alert->indicators->description) {
                $alert->indicators->description = $alert->indicators->description[auth()->user()->language] ?? $alert->indicators->description['en'];
            }
            if ($alert->satellite && is_array($alert->satellite->description) && $alert->satellite->description) {
                $alert->satellite->description = $alert->satellite->description[auth()->user()->language] ?? $alert->satellite->description['en'];
            }
        }
        return $alerts->toArray();
    }

    public function getTrashedByWorkspace($filters, $workspaceId)
    {
        return $this->getByWorkspace($filters, $workspaceId, true);
    }

    public function getClosedByWorkspace($filters, $workspaceId)
    {
        $filters['closed'] = true;
        return $this->getByWorkspace($filters, $workspaceId);
    }

    public function getAlert($alertUuid, $workspaceId)
    {
        $query = $this->model->newQuery();
        $query->where('uuid', $alertUuid);
        $currentUserId = auth()->user()->id;
        $currentRole = $this->getRoleName($workspaceId, $currentUserId);
        if (!in_array($currentRole, ['super_admin', 'admin'])) {
            $query->where('alert_manager_id', $currentUserId)
                ->orWhereJsonContains('observers_ids', $currentUserId);
        }
        $alert = $query->with('status', 'alertManager', 'indicators', 'alertImages')->firstOrFail()->toArray();
        $alert['observers'] = [];
        $observersIds = json_decode($alert['observers_ids'], true);
        if ($observersIds) {
            foreach ($observersIds as $observerId) {
                $observer = (new UserRepository)->getById($observerId);
                $alert['observers'][] = $observer;
            }
        }
        return $alert;
    }

    public function updateAlert($alertUuid, $dataValidated)
    {
        $alert = $this->model->where('uuid', $alertUuid)->firstOrFail();
        $alert->update($dataValidated);
        return $alert;
    }

    public function destroyAlert($alertUuid)
    {
        $alert = $this->model->where('uuid', $alertUuid)->firstOrFail();
        $alert->delete();
        return $alert;
    }

    public function createAlert($dataValidated)
    {
        $alert = $this->model->create($dataValidated);
        return $alert->toArray();
    }

    public function updateAlertManagerId($alertId, $userUuid)
    {
        $alert = $this->model->where('uuid', $alertId)->firstOrFail();
        $user = ($userUuid) ? (new UserRepository)->getByUuid($userUuid) : null;
        $alert->update(['alert_manager_id' => $user->id ?? null]);
        NotificationNewInvite::dispatch($alert->workspace->id, $alert->id, [$user->id]);
        $this->createAlertMessageChangedManager($alert, $alert->workspace->id, $user);
        return $alert;
    }

    public function updateObserversIds($alertUuid, $addObserversUuids, $removeObserversUuids)
    {
        $alert = $this->model->where('uuid', $alertUuid)->firstOrFail();
        $registerObserversIds = $alert->observers_ids;
        $observersIds = [];
        $addObserversIds = [];
        $removeObserversIds = [];

        if ($registerObserversIds) {
            $observersIds = json_decode($registerObserversIds, true) ?? [];
        }

        if ($addObserversUuids) {
            foreach ($addObserversUuids as $observerUuid) {
                $observerId = (new UserRepository)->getByUuid($observerUuid)->id;
                $addObserversIds[] = $observerId;
                if (!in_array($observerId, $observersIds)) {
                    $observersIds[] = $observerId;
                }
            }
        }
        if ($removeObserversUuids) {
            foreach ($removeObserversUuids as $observerUuid) {
                $observerId = (new UserRepository)->getByUuid($observerUuid)->id;
                $removeObserversIds[] = $observerId;
                if ($observersIds) {
                    $observersIds = array_values(array_diff($observersIds, [$observerId]));
                }
            }
        }
        if (!$observersIds) {
            $observersIds = null;
        }
        $alert->update(['observers_ids' => json_encode($observersIds)]);
        (new AlertMessageObserverLogRepository)->createLog($alert, $addObserversIds, $removeObserversIds);
        $this->createAlertMessageChangedObservers($alert, $alert->workspace->id, $addObserversIds, $removeObserversIds);
        return $alert;
    }

    public function updateStatus($alertUuid, $workspaceId, $type)
    {
        $alert = $this->model->where('uuid', $alertUuid)->firstOrFail();
        $statusBeforeId = $alert->status_id;
        $statusAfterId = (new StatusRepository)->getStatusType($type)->id ?? null;
        if ($type == 'closed') {
            $alert->update(['status_id' => $statusAfterId, 'closed_at' => now()]);
            return $alert;
        }
        if (!$statusAfterId) {
            return $alert;
        }
        $alert->update(['status_id' => $statusAfterId, 'closed_at' => null]);

        if ($statusBeforeId != $statusAfterId) {
            $this->createAlertMessageStatus($alert, $workspaceId, $statusBeforeId, $statusAfterId, $type);
        }
        return $alert;
    }

    private function createAlertMessageStatus($alert, $workspaceId, $statusBeforeId, $statusAfterId)
    {

        $alertMessage = new AlertMessage();
        $alertMessage->alert_id = $alert->id;
        $alertMessage->user_id = auth()->user()->id;
        $alertMessage->workspace_id = $workspaceId;
        $alertMessage->type = 'status_changed';
        $message = [
            'userName' => auth()->user()->name,
            'statusBeforeId' => $statusBeforeId,
            'statusAfterId' => $statusAfterId,
        ];
        $alertMessage->message = json_encode($message);
        $alertMessage->save();
    }

    private function createAlertMessageChangedManager($alert, $workspaceId, $user) {
        $alertMessage = new AlertMessage();
        $alertMessage->alert_id = $alert->id;
        $alertMessage->user_id = auth()->user()->id;
        $alertMessage->workspace_id = $workspaceId;
        $alertMessage->type = 'manager_changed';
        $message = [
            'userName' => auth()->user()->name,
            'managerName' => $user->name ?? '',
        ];
        $alertMessage->message = json_encode($message);
        $alertMessage->save();
    }

    private function createAlertMessageChangedObservers($alert, $workspaceId, $addObserversIds, $removeObserversIds)
    {
        if ($addObserversIds) {
            foreach ($addObserversIds as $addObserverId) {
                $addObserver = (new UserRepository)->getById($addObserverId);
                $alertMessageAdd = new AlertMessage();
                $alertMessageAdd->alert_id = $alert->id;
                $alertMessageAdd->user_id = auth()->user()->id;
                $alertMessageAdd->workspace_id = $workspaceId;
                $alertMessageAdd->type = 'add_observer';
                $message = [
                    'userName' => auth()->user()->name,
                    'addObserver' => $addObserver->name,
                ];
                $alertMessageAdd->message = json_encode($message);
                $alertMessageAdd->save();
            }
        }
        if ($removeObserversIds) {
            foreach ($removeObserversIds as $removeObserverId) {
                $removeObserver = (new UserRepository)->getById($removeObserverId);
                $alertMessageRemove = new AlertMessage();
                $alertMessageRemove->alert_id = $alert->id;
                $alertMessageRemove->user_id = auth()->user()->id;
                $alertMessageRemove->workspace_id = $workspaceId;
                $alertMessageRemove->type = 'remove_observer';
                $message = [
                    'userName' => auth()->user()->name,
                    'removeObserver' => $removeObserver->name,
                ];
                $alertMessageRemove->message = json_encode($message);
                $alertMessageRemove->save();
            }
        }
    }
}
