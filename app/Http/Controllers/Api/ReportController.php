<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ReportRequest;
use App\Repositories\ReportRepository;

/**
 * @group Report
 * APIs for managing auth
 * @authenticated
 * @package App\Http\Controllers\Api

 */
class ReportController extends Controller
{
    /**
     * Create a new report
     */
    public function store(ReportRequest $request, ReportRepository $repository)
    {
        $requestValidated = $request->validated();
        $report = $repository->createReport($requestValidated);
        return $this->apiResponse->successResponse(__('report.store'), $report->toArray());
    }
}
