<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\FilterRepository;
use Illuminate\Http\Request;

/**
 * @group Filter
 * @authenticated
 * APIs for filters
 * @package App\Http\Controllers\Api
 */
class FilterController extends Controller
{

    /**
     * List all filters
     * @param Request $request
     */
    public function index(Request $request, FilterRepository $repository)
    {
        $indicators = $repository->getAllWith('indicators');

        return $this->apiResponse->successResponse(__('alerts.alerts_list'), $indicators->toArray());
    }

}
