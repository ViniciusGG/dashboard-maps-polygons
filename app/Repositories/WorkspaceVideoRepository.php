<?php

namespace App\Repositories;

use App\Models\WorkspaceMember;
use App\Models\WorkspaceVideo;

class WorkspaceVideoRepository extends BaseRepository
{

    /**
     * UserRepository constructor.
     */
    public function __construct()
    {
        parent::__construct(WorkspaceVideo::class);
    }

    public function create($dataValidated)
    {
        $workspaceVideo =  $this->model->create($dataValidated);
        $workspaceVideo->region_map_area = json_decode($workspaceVideo->region_map_area);
        return $workspaceVideo->toArray();
    }

    public function getWorkspaceVideos($filters, $workspaceId)
    {
        $query = WorkspaceVideo::where('workspace_id', $workspaceId);

        if (isset($filters['search'])) {
            $query->where('name', 'like', '%' . $filters['search'] . '%');
        }

        if (isset($filters['sortBy'])) {
            $query->orderBy($filters['sortBy'], $filters['sortDirection']);
        }

        if (isset($filters['take'])) {
            return $query->paginate($filters['take']);
        }

        $workspaceVideo = $query->get()->toArray();
        foreach ($workspaceVideo as &$video) {
            $video['region_map_area'] = json_decode($video['region_map_area']);
        }
        return $workspaceVideo;
    }

    public function getWorkspaceVideo($workspaceVideoUuid)
    {
        $workspaceVideo = $this->model->where('uuid', $workspaceVideoUuid)->firstOrFail();
        $workspaceVideo->region_map_area = json_decode($workspaceVideo->region_map_area);
        return $workspaceVideo->toArray();
    }

    public function updateWorkspaceVideo($workspaceVideoUuid, $dataValidated)
    {
        $workspaceVideo = $this->model->where('uuid', $workspaceVideoUuid)->firstOrFail();
        $workspaceVideo->update($dataValidated);
        $workspaceVideo->region_map_area = json_decode($workspaceVideo->region_map_area);
        return $workspaceVideo->toArray();
    }

    public function deleteWorkspaceVideo($workspaceVideoUuid)
    {
        $workspaceVideo = $this->model->where('uuid', $workspaceVideoUuid)->firstOrFail();
        $workspaceVideo->delete();
        $workspaceVideo->region_map_area = json_decode($workspaceVideo->region_map_area);
        return $workspaceVideo->toArray();
    }
}
