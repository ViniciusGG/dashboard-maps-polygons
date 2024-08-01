<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\DangerousAlertsManagersRequest;
use App\Http\Requests\Api\WorkspaceManagerRequest;
use App\Http\Requests\Api\WorkspaceRequest;
use App\Http\Requests\Api\WorkspaceStatusRequest;
use App\Repositories\UserRepository;
use App\Repositories\WorkspaceRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * @group Workspace
 * APIs for managing workspaces (councils)
 * @authenticated
 * @package App\Http\Controllers\Api
 */

class WorkspaceController extends Controller
{

    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    /**
     * Get all workspaces
     * @queryParam search string Search term. Example: John
     * @queryParam sortBy string Column to sort by. Example: created_at
     * @queryParam sortDirection string Sort direction. Example: asc
     * @queryParam page int Number of items per page. Example: 1
     * @queryParam take int Number of items to take. Example: 10
     * @param WorkspaceRepository $repository
     * @return Response
     */
    public function index(Request $request, WorkspaceRepository $repository)
    {
        $this->checkPermission('view workspaces');
        $this->enableFilters();
        $filters = $this->getFilters($request);
        $workspaces = $repository->getAllWorkspace($filters);

        $message = empty($workspaces) ? __('workspace.no_workspaces') : __('workspace.workspaces_list');
        return $this->apiResponse->successResponse($message, $workspaces->toArray());
    }

    /**
     * Get a workspace by id
     * @param WorkspaceRepository $repository
     * @return JsonResponse
     */
    public function show()
    {
        // $this->checkPermission('view workspaces');
        $workspaceRepo = new WorkspaceRepository();
        $workspace = $workspaceRepo->getWorkspace($this->workspaceId);

        $message = empty($workspace) ? __('workspace.no_workspaces') : __('workspace.workspaces_list');
        $response = $this->apiResponse->successResponse($message, $workspace->load('license')->toArray());

        return $response;
    }

    /**
     * Create a new workspace
     *
     * @param UserRepository $repository
     * @return Response|Application|ResponseFactory
     * @return Application|ResponseFactory|Response
     * @urlParam name string required The workspace name. Example: My Workspace
     */
    public function store(WorkspaceRequest $request, WorkspaceRepository $repository)
    {
        $requestValidated = $request->validated();
        $workspace = $repository->create($requestValidated);
        $message = __('workspace.created');
        return $this->apiResponse->successResponse($message, $workspace->load('license')->toArray());
    }

    /**
     * Delete a Workspace
     * @param UserRepository $repository
     * @return array
     */
    public function destroy(WorkspaceRepository $repository)
    {
        $workspaceId = $this->workspaceId;
        $response = $repository->delete($workspaceId);

        if ($response) {
            $message = "Workspace deleted successfully";
            $response = $this->apiResponse->successResponse($message, []);
        } else {
            $message = "Workspace not found";
            $response = $this->apiResponse->errorResponse($message, []);
        }

        return $response;
    }

    /**
     * Update a workspace
     * @param WorkspaceRequest $request
     * @param WorkspaceRepository $repository
     * @return Response

     */
    public function update(WorkspaceRequest $request, WorkspaceRepository $repository)
    {
        if(!$this->checkPermissionUpdateWorkspace($this->workspaceId)){
            $message = __('workspace.workspace_not_approved');
            return $this->apiResponse->errorResponse($message, 160);
        }
        $workspaceId = $this->workspaceId;
        $data = $request->validated();
        $workspaceId = $repository->updateWorkspace($workspaceId, $data);

        $message = __('workspace.updated');
        return $this->apiResponse->successResponse($message, $workspaceId->load('license')->toArray());
    }

    public function updateInfoWorkspace(WorkspaceRequest $request, WorkspaceRepository $repository)
    {
        $workspaceId = $this->workspaceId;
        $data = $request->validated();
        $workspaceId = $repository->updateInfoWorkspace($workspaceId, $data);

        $message = __('workspace.updated');
        return $this->apiResponse->successResponse($message, $workspaceId->load('license')->toArray());
    }

    /**
     * Update a workspace status
     * @param WorkspaceRequest $request
     * @param WorkspaceRepository $repository
     * @return Response

     */
    public function status(WorkspaceStatusRequest $request, WorkspaceRepository $repository)
    {
        $this->checkPermission('super admin');
        $workspaceId = $this->workspaceId;
        $data = $request->validated();
        $workspaceId = $repository->updateStatusWorkspace($workspaceId, $data);

        $message = __('workspace.updated');
        return $this->apiResponse->successResponse($message, $workspaceId->load('license')->toArray());
    }

    /**
     * Get all managers admin by workspace
     * @param WorkspaceRepository $repository
     * @return JsonResponse
     */
    public function getManagersAdminByWorkspace(WorkspaceRepository $repository)
    {
        $workspaceId = $this->workspaceId;
        $managers = $repository->getManagersAdminByWorkspace($workspaceId);
        $message = __('workspace.managers_list');
        return $this->apiResponse->successResponse($message, $managers->toArray());
    }

    /**
     * Update managers admin by workspace
     * @bodyParam alerts_managers_ids array required The alerts managers uuids. Example: ["152c8589-d24a-4814-a0c3-9f15691083d4",
     * "152c8589-d24a-4814-a0c3-9f15691083d4"]
     * @param WorkspaceManagerRequest $request
     * @param WorkspaceRepository $repository
     * @return JsonResponse
     */
    public function updateManagersAdminByWorkspace(WorkspaceManagerRequest $request, WorkspaceRepository $repository)
    {
        $workspaceId = $this->workspaceId;
        $requestValidated = $request->validated();
        $alerts_managers_ids = $requestValidated['alerts_managers_ids'] ?? [];
        $managers = $repository->updateManagersAdminByWorkspace($workspaceId, $alerts_managers_ids);
        $message = __('workspace.managers_updated');
        return $this->apiResponse->successResponse($message, $managers->toArray());
    }


    /**
     * Update dangerous alerts managers ids
     * @bodyParam dangerous_alerts_managers_ids array required The dangerous alerts managers uuids. Example: ["152c8589-d24a-4814-a0c3-9f15691083d4",
     * "152c8589-d24a-4814-a0c3-9f15691083d4"]
     * @param WorkspaceManagerRequest $request
     * @param WorkspaceRepository $repository
     * @return JsonResponse
     */
    public function updateDangerousAlertsManagers(DangerousAlertsManagersRequest $request, WorkspaceRepository $repository)
    {
        $workspaceId = $this->workspaceId;
        $requestValidated = $request->validated();
        $alerts_managers_ids = $requestValidated['dangerous_alerts_managers_ids'] ?? [];
        $managers = $repository->updateDangerousAlertsManagers($workspaceId, $alerts_managers_ids);
        $message = __('workspace.managers_updated');
        return $this->apiResponse->successResponse($message, $managers->toArray());
    }

}
