<?php

namespace App\Repositories;

use App\Models\Alert;
use App\Services\IndicatorHistoryService\IndicatorHistoryService;
use Carbon\Carbon;

class IndicatorHistoryRepository extends BaseRepository
{

    public function __construct()
    {
        parent::__construct(Alert::class);
    }

    public function getReports($filters, $workspaceId)
    {
        $query = $this->model->newQuery();
        $search = $filters['search'] ?? false;
        $what = $filters['what'] ?? false;
        $gt = $filters['gt'] ?? false;
        $lt = $filters['lt'] ?? false;
        $where = $filters['where'] ?? false;
        $closed = $filters['closed'] ?? false;
        $seletedColumns = [
            'id', 'workspace_id', 'area', 'intensity', 'indicator', 'alert_datetime'
        ];
        $query->select($seletedColumns);
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
        //with indicators select only name and description fields
        $query->with(['indicators' => function ($query) {
            $query->select('id', 'name', 'description');
        }]);

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
            if ($lt && $gt) {
                $query->whereBetween('alert_datetime', [$lt, $gt]);
            }
        }
        $query->where('workspace_id', $workspaceId);
        $query->orderBy('alert_datetime', 'ASC');
        $alerts = $query->get();
        $indicatorService = new IndicatorHistoryService();
        $indicator = [];
        if (!$alerts->isEmpty()) {
            $indicator = $alerts->pluck('indicators')->unique()[0]->only(['name', 'description']) ?? [];
            $indicator['description'] = $indicator['description'][auth()->user()->language] ?? '';
        }
        return ['data' => $indicatorService->getSummedAlerts($alerts, $lt, $gt), 'lt' => $lt, 'gt' => $gt, 'indicator' => $indicator];
    }
}
