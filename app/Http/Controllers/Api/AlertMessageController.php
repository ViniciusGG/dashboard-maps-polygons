<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AlertMessageRequest;
use App\Repositories\AlertMessageRepository;
use Illuminate\Http\Request;

/**
 * @group Alert Message
 * @authenticated
 * APIs for managing alert message
 * @package App\Http\Controllers\Api

 */
class AlertMessageController extends Controller
{

    /**
     * List all alert message
     * @queryParam search string Search term. Example: Bom dia
     * @queryParam sortBy string Column to sort by. Example: created_at
     * @queryParam sortDirection string Sort direction. Example: asc
     * @queryParam page int Number of items per page. Example: 1
     * @queryParam take int Number of items to take. Example: 10
     * @param Request $request
     */
    public function index(Request $request, string $workspaceUuid, string $alertUuid,  AlertMessageRepository $repository)
    {
        $this->enableFilters();
        $filters = $this->getFilters($request);
        $data = $repository->alertMessageFilter($filters, $this->workspaceId, $alertUuid);

        return $this->apiResponse->successResponse(__('alerts.alerts_list'), $data);
    }

    /**
     * Get a alert message by uuid
     */
    public function show(string $alertUuid, AlertMessageRepository $repository)
    {
        $alert = $repository->getAlertMessage($alertUuid);

        $message = empty($alert) ? __('alerts.no_alert') : __('alerts.alert_found');
        return $this->apiResponse->successResponse($message, $alert);
    }

    /**
     * Create a new alert message
     */
    public function store(AlertMessageRequest $request, string $workspaceUuid, string $alertUuid, AlertMessageRepository $repository)
    {
        $requestValidated = $request->validated();
        $requestValidated['workspace_id'] = $this->workspaceId;
        $alert = $repository->createAlertMessage($requestValidated, $alertUuid);

        $message = __('alerts.alert_created');
        return $this->apiResponse->successResponse($message, $alert);
    }

    /**
     * Update a alert message
     */
    public function update(AlertMessageRequest $request, string $workspaceId, string $alertUuid, AlertMessageRepository $repository)
    {
        $requestValidated = $request->validated();
        $alert = $repository->updateAlertMessage($alertUuid, $requestValidated);

        $message = __('alerts.alert_updated');
        return $this->apiResponse->successResponse($message, $alert->toArray());
    }

    /**
     * Delete a alert message
     */
    public function destroy(string $workspaceId, string $alertUuid, AlertMessageRepository $repository)
    {
        $alert = $repository->destroyAlertMessage($alertUuid);

        $message = __('alerts.alert_deleted');
        return $this->apiResponse->successResponse($message, $alert->toArray());
    }
}
