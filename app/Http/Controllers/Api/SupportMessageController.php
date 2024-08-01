<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\SupportMessageRequest;
use App\Models\Workspace;
use App\Repositories\SupportMessageRepository;
use Illuminate\Http\Request;

/**
 * @group Support Message 
 * @authenticated
 * APIs for managing support message
 * @package App\Http\Controllers\Api

 */
class SupportMessageController extends Controller
{
    /**
     * List all support messages
     * @queryParam sortBy string Column to sort by. Example: created_at
     * @queryParam sortDirection string Sort direction. Example: asc
     * @queryParam page int Number of items per page. Example: 1
     * @queryParam take int Number of items to take. Example: 10
     * @param Request $request
     */
    public function index(Request $request, string $workspaceUuid, string $supportUuid, SupportMessageRepository $repository)
    {
        $this->checkPermission('view message customer support');
        $this->enableFilters();
        $filters = $this->getFilters($request);
        $data = $repository->supportMessageFilter($filters, $this->workspaceId, $supportUuid);

        return $this->apiResponse->successResponse(__('supports.supports_list'), $data);
    }

    /**
     * Get a support message by uuid
     */
    public function show(string $workspaceId, string $supportUuid, string $supportMessageUuid, supportMessageRepository $repository)
    {
        $this->checkPermission('view message customer support');
        $support = $repository->showSupportMessage($supportMessageUuid);
        $message = empty($support) ? __('supports.no_support') : __('supports.support_found');
        return $this->apiResponse->successResponse($message, $support);
    }

    /**
     * Create a new support message
     * @urlParam support uuid required Support uuid. Example: 123e4567-e89b-12d3-a456-426614174000
     */
    public function store(SupportMessageRequest $request, string $workspaceUuid, string $supportUuid, supportMessageRepository $repository)
    {
        $this->checkPermission('create message customer support');
        $requestValidated = $request->validated();
        $requestValidated['workspace_id'] = $this->workspaceId;
        $support = $repository->createSupportMessage($requestValidated, $supportUuid);

        $message = __('supports.support_created');
        return $this->apiResponse->successResponse($message, $support);
    }

    /**
     * Update a support message
     * @urlParam support uuid required Support uuid. Example: 123e4567-e89b-12d3-a456-426614174000
     * @urlParam support id required Support uuid. Example: 123e4567-e89b-12d3-a456-426614174000
     */
    public function update(supportMessageRequest $request, string $workspaceId, string $supportUuid, string $supportMessageUuid, supportMessageRepository $repository)
    {
        $this->checkPermission('edit message customer support');
        $requestValidated = $request->validated();
        $support = $repository->updateSupportMessage($supportMessageUuid, $requestValidated);

        $message = __('supports.support_updated');
        return $this->apiResponse->successResponse($message, $support);
    }

    /**
     * Delete a support message
     */
    public function destroy(string $workspaceId, string $supportUuid, string $supportMessageUuid, supportMessageRepository $repository)
    {    
        $this->checkPermission('delete message customer support');
        $support = $repository->destroySupportMessage($supportMessageUuid);

        $message = __('supports.support_deleted');
        return $this->apiResponse->successResponse($message, $support);
    }
}
