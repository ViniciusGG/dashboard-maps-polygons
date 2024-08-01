<?php

namespace App\Http\Controllers\Internal;

use App\Http\Controllers\Controller;
use App\Http\Requests\SatelliteRequest;
use App\Repositories\SatelliteRepository;
use Illuminate\Http\Request;

/**
 * @group Webhook
 * @authenticated
 * APIs for managing created new satellite
 * @package App\Http\Controllers\Api
 */
class InternalSatelliteController extends Controller
{
    public function index(SatelliteRepository $satelliteRepository, Request $request)
    {
        $filter['columns'] = ['uuid', 'name', 'description'];

        if($request->has('take')){
            $take = $request->input('take', 10);
            $filter['take'] = $take == -1 ? 10 : $take;
        }

        return response()->json($satelliteRepository->filter($filter), 200);
    }


    public function store(SatelliteRequest $request, SatelliteRepository $satelliteRepository)
    {
        $data = $request->validated();

        $satellite = $satelliteRepository->create($data);
        return response()->json($satellite->only('uuid','name','description'), 201);
    }

    public function update($uuid, SatelliteRequest $request, SatelliteRepository $satelliteRepository)
    {
        $data = $request->validated();


        $satelliteRepository->existsByUuid($uuid);

        $satellite = $satelliteRepository->updateByUuid($data,$uuid);
        return response()->json($satellite->only('uuid','name','description'), 200);
    }
}
