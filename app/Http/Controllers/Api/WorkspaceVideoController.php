<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\WorkspaceVideoRequest;
use App\Repositories\WorkspaceVideoRepository;
use Illuminate\Http\Request;

/**
 * @group Workspace Video
 * APIs for managing workspaces videos
 * @authenticated
 * @package App\Http\Controllers\Api
 */
class WorkspaceVideoController extends Controller
{
    /**
     * Get all workspace videos
     */
    public function index(Request $request,  WorkspaceVideoRepository $repository)
    {
        $this->checkPermission('view workspace video');
        $this->enableFilters();
        $filters = $this->getFilters($request);
        $data = $repository->getWorkspaceVideos($filters, $this->workspaceId);

        return $this->apiResponse->successResponse(__('workspace.video.list'), $data);
    }

    /**
     * Create a new workspace video
     */
    public function store(WorkspaceVideoRequest $request, WorkspaceVideoRepository $repository)
    {
        $this->checkPermission('create workspace video');
        $requestValidated = $request->validated();
        $requestValidated['workspace_id'] = $this->workspaceId;
        $workspaceVideo = $repository->create($requestValidated);

        $message = __('workspace.video.created');
        return $this->apiResponse->successResponse($message, $workspaceVideo);
    }

    /**
     * Get a workspace video by uuid
     */
    public function show(string $workspaceId, string $videoUuid, WorkspaceVideoRepository $repository)
    {
        $this->checkPermission('view workspace video');
        $workspaceVideo = $repository->getWorkspaceVideo($videoUuid);

        return $this->apiResponse->successResponse(__('workspace.video.show'), $workspaceVideo);
    }

    /**
     * Update a workspace video
     */
    public function update(WorkspaceVideoRequest $request, string $workspaceId, string $videoUuid, WorkspaceVideoRepository $repository)
    {
        $this->checkPermission('edit workspace video');
        $requestValidated = $request->validated();
        $requestValidated['workspace_id'] = $this->workspaceId;
        $workspaceVideo = $repository->updateWorkspaceVideo($videoUuid, $requestValidated);
        return $this->apiResponse->successResponse(__('workspace.video.updated'), $workspaceVideo);
    }

    /**
     * Delete a workspace video
     */
    public function destroy(string $workspaceId, string $videoUuid, WorkspaceVideoRepository $repository)
    {
        $this->checkPermission('delete workspace video');
        $workspaceVideo = $repository->deleteWorkspaceVideo($videoUuid);
        return $this->apiResponse->successResponse(__('workspace.video.deleted'), $workspaceVideo);
    }
}
