<?php

namespace App\Repositories;

use App\Models\WorkspaceAreaPoint;

class WorkspaceAreaPointRepository extends BaseRepository
{

    /**
     * UserRepository constructor.
     */
    public function __construct()
    {
        parent::__construct(WorkspaceAreaPoint::class);
    }

    public function getAllAreaPoints($filters)
    {
        $query = $this->model->newQuery();

        if (isset($filters['sortBy'])) {
            $sortDirection = $filters['sortDirection'] ?? 'ASC';
            $query->orderBy($filters['sortBy'], $sortDirection);
        }
        $query->where('workspace_id', $filters['workspace_id']);

        return $query->get();
    }

    public function getAreaPointByWorkspaceId($workspace_id)
    {
        $query = $this->model->newQuery();
        $query->where('workspace_id', $workspace_id);

        return $query->get();


    }
}
