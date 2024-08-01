<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\StatusRepository;
use Illuminate\Http\Request;

/**
 * @group Status
 * APIs for managing status
 * @authenticated
 * @package App\Http\Controllers\Api
 */

class StatusController extends Controller
{

    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    /**
     * List all status
     */
    public function index(StatusRepository $repository)
    {
        $status = $repository->getAll();

        $message = ($status) ? __('status.status_list') : __('status.no_status');
        return $this->apiResponse->successResponse($message, $status->toArray());
    }

}
