<?php

namespace App\Repositories;

use App\Jobs\NotificationNewMessage;
use App\Models\Alert;
use App\Models\AlertMessage;
use App\Models\UserAlertMessageRead;
use Illuminate\Pagination\LengthAwarePaginator;

class AlertMessageRepository extends BaseRepository
{

    public function __construct()
    {
        parent::__construct(AlertMessage::class);
    }

    public function alertMessageFilter($filters, $workspaceId, $alertUuid)
    {
        $alert = Alert::where('uuid', $alertUuid)->withTrashed()->firstOrFail();
        $dateAlertLog = (new AlertMessageObserverLogRepository)->getByAlert($alert->id, auth()->user()->id)->invited_at ?? '';
        $queryRead = $this->model->newQuery();
        $columns = $filters['columns'] ?? ['*'];
        $take = $filters['take'] ?? $this->take;
        $sortBy = $filters['sortBy'] ?? 'created_at';
        $sortDirection = $filters['sortDirection'] ?? 'DESC';
        $queryRead->with('attachments', 'user', 'statusBefore', 'statusAfter');

        $queryRead->where('workspace_id', $workspaceId);
        $queryRead->where('alert_id', $alert->id);
        if ($dateAlertLog) {
            $queryRead->where('created_at', '>', $dateAlertLog);
        }

        $queryRead->whereHas('userAlertMessageRead', function ($query) {
            $query->where('is_read', 1);
            $query->where('user_id', auth()->user()->id);
        });

        $queryRead->orderBy($sortBy, $sortDirection);
        $readMessages = $queryRead->paginate($take, $columns);
        $queryUnread = $this->model->newQuery();
        $queryUnread->with('attachments', 'user');
        $queryUnread->where('workspace_id', $workspaceId);
        $queryUnread->where('alert_id', $alert->id);
        if ($dateAlertLog) {
            $queryUnread->where('created_at', '>', $dateAlertLog);
        }
        $queryUnread->whereHas('userAlertMessageRead', function ($query) {
            $query->where('is_read', 0);
            $query->where('user_id', auth()->user()->id);
        });
        $unreadMessages = $queryUnread->get();

        $originalUnreadMessages = $unreadMessages->map(function ($unreadMessage) {
            return $unreadMessage->replicate();
        });

        UserAlertMessageRead::where('user_id', auth()->user()->id)
            ->whereIn('alert_message_id', $unreadMessages->pluck('id'))
            ->update(['is_read' => 1]);

        $modifiedMessages = $this->verifyTypeMessage($readMessages);

        return [
            'read' => $modifiedMessages,
            'unread' => ['data' => $this->verifyTypeMessage($originalUnreadMessages)->toArray()],
        ];
    }

    public function getAlertMessage($take = 0, $columns = ["*"])
    {
        if ($take === 0) {
            $take = $this->take;
        }

        $query = $this->model->newQuery();

        return $query->paginate($take, $columns);
    }

    public function createAlertMessage($dataValidated, $alertUuid)
    {
        $alert = Alert::where('uuid', $alertUuid)->firstOrFail();
        $lastNotificationSent = AlertMessage::where('workspace_id', $dataValidated['workspace_id'])
            ->where('alert_id', $alert->id)
            ->max('created_at');
        $dataMessage['alert_id'] = $alert->id;
        $dataMessage['user_id'] = auth()->user()->id;
        $dataMessage['workspace_id'] = $dataValidated['workspace_id'];
        $dataMessage['message'] = $dataValidated['message'];
        $alertMessage = $this->model->create($dataMessage);

        if (isset($dataValidated['attachments'])) {
            foreach ($dataValidated['attachments'] as $attachment) {
                $workspacePath = config('internal.folder-azulfy-bucket').'/'.$dataValidated['workspace_id'].'/attachments';
                $storedAttachment = $attachment->store($workspacePath, config('internal.azulfy-bucket'));
                $mimeType = $attachment->getMimeType();
                switch ($mimeType) {
                    case 'image/jpeg':
                    case 'image/png':
                    case 'image/jpg':
                    case 'image/gif':
                        $type = 'image';
                        break;
                    case 'video/mp4':
                    case 'video/avi':
                    case 'video/mov':
                        $type = 'video';
                        break;
                    case 'application/pdf':
                        $type = 'pdf';
                        break;
                }
                $alertMessage->attachments()->create([
                    'file_name' => $storedAttachment,
                    'file_type' => $type,
                    'alert_id' => $alert->id,
                    'workspace_id' => $dataValidated['workspace_id'],
                ]);
            }
        }
        NotificationNewMessage::dispatch($alert->workspace->id, $alert->id, $lastNotificationSent, 'alert');
        return $alertMessage->load('attachments', 'user')->toArray();
    }

    public function updateAlertMessage($uuid, $dataValidated)
    {
        $user = $this->model->where('uuid', $uuid)->firstOrFail();
        $user->update($dataValidated);
        return $user;
    }

    public function destroyAlertMessage($uuid)
    {
        $user = $this->model->where('uuid', $uuid)->firstOrFail();
        $user->delete();
        return $user;
    }

    public function showAlertMessage($uuid)
    {
        $user = $this->model->where('uuid', $uuid)->firstOrFail();
        return $user;
    }

    public function verifyTypeMessage($messages)
    {
        $messagesArray = $messages;
        if($messages instanceof LengthAwarePaginator){
            $messagesArray = $messages->items();
        }
            foreach ($messagesArray as $messageObj) {
                if ($messageObj->type == 'status_changed') {
                    $objMessage = json_decode($messageObj->message);
                    $statusAfterId = $objMessage->statusAfterId ?? null;
                    $statusBeforeId = $objMessage->statusBeforeId ?? null;
                    if($statusAfterId == null || $statusBeforeId == null){
                        $messageObj->message = null;
                        continue;
                    }
                    $statusAfterName = (new StatusRepository)->getStatusById($statusAfterId);
                    $statusBeforeName = (new StatusRepository)->getStatusById($statusBeforeId);
                    $messageObj->message = __('messages.status_changed', [
                        'userName' => $objMessage->userName ?? '',
                        'statusBeforeName' => $statusBeforeName,
                        'statusAfterName' => $statusAfterName,
                    ]);
                }
                if($messageObj->type == 'manager_changed'){
                    $objMessage = json_decode($messageObj->message);
                    $messageObj->message = __('messages.manager_changed', [
                        'userName' => $objMessage->userName ?? '',
                        'managerName' => $objMessage->managerName ?? '',
                    ]);
                }
                if($messageObj->type == 'add_observer'){
                    $objMessage = json_decode($messageObj->message);
                    $messageObj->message = __('messages.add_observer', [
                        'userName' => $objMessage->userName ?? '',
                        'observerName' => $objMessage->addObserver ?? '',
                    ]);
                }
                if($messageObj->type == 'remove_observer'){
                    $objMessage = json_decode($messageObj->message);
                    $messageObj->message = __('messages.remove_observer', [
                        'userName' => $objMessage->userName ?? '',
                        'observerName' => $objMessage->removeObserver ?? '',
                    ]);
                }
            }

        return $messages;
    }
}
