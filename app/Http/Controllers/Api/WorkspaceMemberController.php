<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\WorkspaceMemberRequest;
use App\Http\Requests\Api\WorkspaceMemberUpdateRequest;
use App\Repositories\WorkspaceMemberRepository;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * @group Workspace member
 * APIs for managing workspaces members (councils accounts)
 * @authenticated
 * @package App\Http\Controllers\Api
 */
class WorkspaceMemberController extends Controller
{

    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    /**
     * Get all members of a workspace by role
     * @queryParam search string Search term. Example: John
     * @queryParam sortBy string Column to sort by. Example: created_at
     * @queryParam sortDirection string Sort direction. Example: asc
     * @queryParam page int Number of items per page. Example: 1
     * @queryParam take int Number of items to take. Example: 10
     * @param WorkspaceMemberRepository $repository
     */
    public function index(Request $request, WorkspaceMemberRepository $repository)
    {
        $this->checkPermission('view members workspace');
        $this->enableFilters();
        $filters = $this->getFilters($request);
        $workspaceId = $this->workspaceId;
        $members = $repository->getMembersByRole($filters,$workspaceId);
        return $this->apiResponse->successResponse(__('workspace.members.list'), $members);
    }

    /**
     * Store a new member of a workspace
     * @bodyParam member_email string required The email of the user. Example: johndoe@buzzvel.com
     * @bodyParam member_name string required The name of the user. Example: John Doe

     * @param WorkspaceMemberRepository $repository
     * @return Response
     */
    public function store(WorkspaceMemberRequest $request, WorkspaceMemberRepository $repository)
    {
        $this->checkPermission('create members workspace');
        $requestValidated = $request->validated();
        $response = $repository->addMemberToWorkspace($this->workspaceId, $requestValidated);

        if ($response['status']) {
            return $this->apiResponse->successResponse($response['message'], $response['data']->toArray());
        } else {
            return $this->apiResponse->errorResponse($response['message'], []);
        }
    }


    /**
     * Update a member of a workspace
     * @param WorkspaceMemberRepository $repository
     * @param WorkspaceMemberUpdateRequest $request
     * @urlParam id string required The member uuid. Example: 152c8589-d24a-4814-a0c3-9f15691083d4
     * @return Response
     */
    public function update(WorkspaceMemberUpdateRequest $request, string $workspaceUuid, string $memberUuid, WorkspaceMemberRepository $repository)
    {
        $this->checkPermission('edit members workspace');
        $requestValidated = $request->validated();
        $response = $repository->updateRole($memberUuid, $this->workspaceId, $requestValidated['role']);
        $message = __('workspace.members.updated');
        return $this->apiResponse->successResponse($message, $response->toArray());
    }

    /**
     * Delete a member of a workspace
     * @return Response
     */
    public function destroy(string $workspaceUuid, string $memberUuid, WorkspaceMemberRepository $repository)
    {
        $this->checkPermission('delete members workspace');
        $response = $repository->deleteMemberFromWorkspace($this->workspaceId, $memberUuid);

        return $this->apiResponse->successResponse(__('workspace.members.deleted'), $response->toArray());
    }

}
