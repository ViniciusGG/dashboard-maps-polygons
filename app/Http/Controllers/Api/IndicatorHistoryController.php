<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\IndicatorHistoryRequest;
use App\Repositories\IndicatorHistoryRepository;
use Illuminate\Http\Request;
/**
 * @group IndicatorHistory
 * APIs for managing licenses
 * @authenticated
 * @package App\Http\Controllers\Api

 */
class IndicatorHistoryController  extends Controller
{
    /**
     * List all indicator history
     * @queryParam search string Search term. Example: ""
     * @queryParam page int Number of items per page. Example: 1
     * @queryParam take int Number of items to take. Example: 10
     * @queryParam closed int Filter by trashed. Example: ""
     * @queryParam where string Where clause (type). Example: "1"
     * @queryParam what string What clause(indicator). Example: "1"
     * @queryParam lt string When clause(dates). Example: "1710872224"
     * @queryParam gt string When clause(dates). Example: "1610872917"
     *
     */
    public function index(IndicatorHistoryRequest $request, IndicatorHistoryRepository $repository)
    {
        $this->enableFilters();
        $filters = $this->getFilters($request);
        $data = $repository->getReports($filters, $this->workspaceId);

        return $this->apiResponse->successResponse(__('report.list'), $data);
    }
}
