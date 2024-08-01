<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\WorkspaceAreaPointRequest;
use App\Repositories\WorkspaceAreaPointRepository;
use Illuminate\Http\Request;

/**
 * @group Workspace Area Points
 * APIs for managing workspace area points (councils accounts)
 * @authenticated
 * @package App\Http\Controllers\Api
 */
class WorkspaceAreaPointController extends Controller
{
    /**
     * List all workspace area points
     * @queryParam sortBy string Column to sort by. Example: created_at
     * @queryParam sortDirection string Sort direction. Example: desc
     */
    public function index(Request $request, WorkspaceAreaPointRepository $repository)
    {
        $this->enableFilters();
        $filters = $this->getFilters($request);
        $filters['workspace_id'] = $this->workspaceId;
        $workspaces = $repository->getAllAreaPoints($filters);

        $message = ($workspaces) ? __('workspace.no_workspaces') : __('workspace.workspaces_list');
        return $this->apiResponse->successResponse($message, $workspaces->toArray());
    }

    /**
     * Create a new workspace area point
     */
    public function store(WorkspaceAreaPointRequest $request, WorkspaceAreaPointRepository $repository)
    {
        $this->checkPermission('create workspace area point');
        $requestValidated = $request->validated();
        $requestValidated['workspace_id'] = $this->workspaceId;
        $workspace = $repository->create($requestValidated);

        $message = __('workspace.area_points.created');
        return $this->apiResponse->successResponse($message, $workspace->toArray());
    }

    /**
     * Get a workspace area point by id
     */
    public function show(string $workspaceUuid, string $areaPointUuid, WorkspaceAreaPointRepository $repository)
    {
        $this->checkPermission('super admin');
        $areaPoint = $repository->getByUuid($areaPointUuid);

        return $this->apiResponse->successResponse(__('workspace.area_points.show'), $areaPoint->toArray());
    }

    /**
     * Update a workspace area point
     */
    public function update(string $workspaceUuid, string $areaPointUuid, WorkspaceAreaPointRequest $request, WorkspaceAreaPointRepository $repository)
    {
        $this->checkPermission('edit workspace area point');
        $requestValidated = $request->validated();
        $requestValidated['workspace_id'] = $this->workspaceId;
        $workspace = $repository->updateByUuid($areaPointUuid, $requestValidated);

        $message = __('workspace.area_points.updated');
        return $this->apiResponse->successResponse($message, $workspace->toArray());
    }

    /**
     * Delete a workspace area point
     */
    public function destroy(string $workspaceUuid, string $areaPointUuid, WorkspaceAreaPointRepository $repository)
    {
        $this->checkPermission('super admin');
        $workspace = $repository->deleteByUuid($areaPointUuid);

        $message = __('workspace.area_points.deleted');
        return $this->apiResponse->successResponse($message, $workspace->toArray());
        
    }
}
