<?php

namespace App\Http\Controllers\Internal;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AlertCreatedInternalRequest;
use App\Repositories\AlertInternalRepository;
use App\Repositories\AlertRepository;
use App\Services\Internal\AlertIntegratorRepository;
use App\Services\Internal\AlertIntegratorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * @group Webhook
 * @authenticated
 * APIs for managing created new alert
 * @package App\Http\Controllers\Api
 */
class InternalAlertsController extends Controller
{

    public function index(AlertInternalRepository $alertRepository, Request $request)
    {
        $filter['columns'] = ['*'];

        $whereValues = $request->only([ 'client_uuid', 'indicator_group_uuid', 'indicator_uuid']);

        foreach($whereValues as $key => $value){
            if($value === "" || $value === null){
                continue;
            }
            $filter[$key] = $value;
        }

        if($request->has('take')){
            $take = $request->input('take', 10);
            $filter['take'] = $take == -1 ? 10 : $take;
        }

        return response()->json($alertRepository->filter($filter), 200);
    }

    /**
     * Create a bulk alerts
     */
    public function store(AlertCreatedInternalRequest $request, AlertIntegratorService $alertIntegratorService)
    {
        Log::channel('internal')->info('Alert Azulfy', $request->all());

        $requestValidated = $request->validated();

        $response = $alertIntegratorService->bulkCreate($requestValidated);

        return response()->json([
            'status' => 'success',
            'message' => $response,
            'data' => $request->all()
        ]);
    }

    /**
     * Delete a alert
     */

    public function destroy($uuid, AlertRepository $alertRepository){

        $response = $alertRepository->deleteByUuid($uuid);

        return response()->json([
            'status' => 'success',
            'message' => $response,
            'data' => $uuid
        ]);

    }
}
