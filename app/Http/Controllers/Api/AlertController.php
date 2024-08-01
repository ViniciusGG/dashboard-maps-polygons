<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AlertManagerRequest;
use App\Http\Requests\Api\AlertObserverRequest;
use App\Http\Requests\Api\AlertRequest;
use App\Http\Requests\Api\AlertStatusRequest;
use App\Repositories\AlertRepository;
use App\Repositories\WorkspaceMemberRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group Alert
 * APIs for managing alerts
 * @authenticated
 * @package App\Http\Controllers\Api
 */
class AlertController extends Controller
{

    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    /**
     * List all alerts
     * @queryParam search string Search term. Example: ""
     * @queryParam sortBy string Column to sort by. Example: name
     * @queryParam sortDirection string Sort direction. Example: asc
     * @queryParam page int Number of items per page. Example: 1
     * @queryParam take int Number of items to take. Example: 10
     * @queryParam trashed int Filter by trashed. Example: ""
     * @queryParam closed int Filter by trashed. Example: ""
     * @queryParam where string Where clause (type). Example: "1"
     * @queryParam what string What clause(indicator). Example: "1"
     * @queryParam lt string When clause(dates). Example: "1710872224"
     * @queryParam gt string When clause(dates). Example: "1700872917"
     *
     * @param Request $request
     */
    public function index(Request $request, AlertRepository $alertRepository)
    {
        $this->enableFilters();
        $filters = $this->getFilters($request);
        $workspaceId = $this->workspaceId;

        if ($request->trashed) {
            $alerts = $alertRepository->getTrashedByWorkspace($filters, $workspaceId);
        } elseif ($request->closed) {
            $alerts = $alertRepository->getClosedByWorkspace($filters, $workspaceId);
        } else {
            $alerts = $alertRepository->getByWorkspace($filters, $workspaceId);
        }
        $message = __('workspace.alerts.list');
        return $this->apiResponse->successResponse($message, $alerts);
    }

    /**
     * Get a alert by uuid
     * @param AlertRepository $repository
     * @return JsonResponse
     */
    public function show(string $workspaceId, string $alertUuid, AlertRepository $repository)
    {
        $alert = $repository->getAlert($alertUuid, $this->workspaceId);

        $message = empty($alert) ? __('alerts.no_alert') : __('alerts.alert_found');
        return $this->apiResponse->successResponse($message, $alert);
    }

    /**
     * Create a new alert
     * @bodyParam name string required The alert name. Example: Praia da Torre
     * @bodyParam lat string required The alert latitude. Example: 38.695
     * @bodyParam lng string required The alert longitude. Example: -9.328
     */
    public function store(AlertRequest $request)
    {
        $requestValidated = $request->validated();
        $alertRepo = new AlertRepository();
        $requestValidated['workspace_id'] = $this->workspaceId;
        $alert = $alertRepo->createAlert($requestValidated);

        $message = __('alerts.alert_created');
        return $this->apiResponse->successResponse($message, $alert);
    }

    /**
     * Update a alert
     * @bodyParam name string required The alert name. Example: Praia da Torre
     * @bodyParam lat string required The alert latitude. Example: 38.695
     * @bodyParam lng string required The alert longitude. Example: -9.328
     */
    public function update(AlertRequest $request, string $workspaceId, string $alertUuid, AlertRepository $repository)
    {
        $requestValidated = $request->validated();
        $alert = $repository->updateAlert($alertUuid, $requestValidated);
        $message = __('alerts.alert_updated');
        return $this->apiResponse->successResponse($message, $alert->toArray());
    }

    /**
     * Delete a alert
     */
    public function destroy(string $workspaceId, string $alertUuid, AlertRepository $repository)
    {
        $alert = $repository->destroyAlert($alertUuid);

        $message = __('alerts.alert_deleted');
        return $this->apiResponse->successResponse($message, $alert->toArray());
    }

    /**
     * Update alert manager
     * @bodyParam user_id string required The user uuid. Example: 152c8589-d24a-4814-a0c3-9f15691083d4
     */
    public function updateAlertManager(
        AlertManagerRequest $request,
        string $workspaceUuid,
        string $alertUuid,
        AlertRepository $repository
    ) {

        if (!$this->checkPermissionUpdateManager($workspaceUuid)) {
            $message = __('Unauthorized');
            return $this->apiResponse->errorResponse($message, 403);
        }
        $requestValidated = $request->validated();
        $user_id = $requestValidated['user_id'] ?? null;
        $alert = $repository->updateAlertManagerId($alertUuid, $user_id);
        $message = __('alerts.alert_manager_updated');
        return $this->apiResponse->successResponse($message, $alert->toArray());
    }

    /**
     * Update observers
     */
    public function updateObservers(
        AlertObserverRequest $request,
        string $workspaceUuid,
        string $alertUuid,
        AlertRepository $repository
    ) {
        if (!$this->checkPermissionUpdateObservers($alertUuid, $workspaceUuid)) {
            $message = __('Unauthorized');
            return $this->apiResponse->errorResponse($message, 403);
        }
        $requestValidated = $request->validated();
        $addObserversIds = $requestValidated['add'] ?? [];
        $removeObserversIds = $requestValidated['remove'] ?? [];
        $alert = $repository->updateObserversIds($alertUuid, $addObserversIds, $removeObserversIds);
        $message = __('alerts.observers_updated');
        return $this->apiResponse->successResponse($message, $alert->toArray());
    }


    public function showUsers(string $workspaceId, string $alertUuid, AlertRepository $repository)
    {
        $this->checkPermission('view alerts');
        $alert = $repository->getAlert($alertUuid, $this->workspaceId);
        $users = $alert->users;
        $message = __('alerts.alert_found');
        return $this->apiResponse->successResponse($message, $users);
    }

    /**
     * Get all members by alert
     * @queryParam search string Search user. Example: ""
     * @urlParam type string required Type of member "observer" or "manager". Example: "observer"
     * @param WorkspaceMemberRepository $repository
     * @return Response
     */
    public function getMembersByAlert(
        Request $request,
        string $workspaceUuid,
        string $alertUuid,
        string $type,
        WorkspaceMemberRepository $repository
    ) {
        $this->enableFilters();
        $filters = $this->getFilters($request);
        if ($type != 'observer' && $type != 'manager') {
            return $this->apiResponse->errorResponse(__('workspace.members.type_error'), []);
        }
        $search = $filters['search'] ?? '';
        $workspaceId = $this->workspaceId;
        $members = $repository->getMembersByWorkspace($workspaceId, $alertUuid, $type, $search);
        return $this->apiResponse->successResponse(__('workspace.members.list'), $members->toArray());
    }

    /**
     * Update a alert message status
     * @bodyParam type string required The status type. Example: "closed"
     */
    public function updateStatus(AlertStatusRequest $request, string $workspaceId,
    string $alertUuid, AlertRepository $repository)
    {
        $requestValidated = $request->validated();
        $alert = $repository->updateStatus($alertUuid, $this->workspaceId, $requestValidated['type']);

        $message = __('alerts.alert_status_updated');
        return $this->apiResponse->successResponse($message, $alert->toArray());
    }
}
