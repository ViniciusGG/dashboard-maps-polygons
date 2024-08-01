<?php

namespace App\Http\Controllers\Internal;

use App\Http\Controllers\Controller;
use App\Repositories\IndicatorRepository;
use App\Repositories\WorkspaceRepository;
use Illuminate\Http\Request;

class MunicipalitiesCodeController extends Controller
{

    public function index(WorkspaceRepository $workspaceRepository, Request $request)
    {
        if ($request->get('columns')) {
            $columns = $request->get('columns');
            $filter['columns'] = explode(',', $columns);
        } else {
            $filter['columns'] = ['id', 'license_id', 'uuid', 'name', 'code_azulfy', 'created_at', 'region_map_area', 'updated_at'];
        }

        if ($request->has('take')) {
            $take = $request->input('take', 10);
            $filter['take'] = $take == -1 ? 10 : $take;
        }
        $relations = ['areaPoints', 'indicators'];

        $response = $workspaceRepository->filter($filter, [], $relations);
        $response->map(function ($item) {
            $region  = json_decode($item->region_map_area);
            $indicators = $item->indicators->pluck('uuid')->toArray();
            unset($item->indicators);
            $item->indicators = $indicators;
            if (isset($region->geometry)) {
                $region = $region->geometry;
                $item->region_map_area = $region;
            } else {
                $item->region_map_area = null;
            }
            foreach ($item->areaPoints as $area_point) {
                if (is_string($area_point->region_map_area)) {
                    $region = json_decode($area_point->region_map_area);
                    if ($region !== null && isset($region->geometry)) {
                        $area_point->region_map_area = $region->geometry;
                    } else {
                        $area_point->region_map_area = null;
                    }
                }
                if(is_string($area_point->region_map_area_pending)){
                    $region = json_decode($area_point->region_map_area_pending);
                    if ($region !== null && isset($region->geometry)) {
                        $area_point->region_map_area_pending = $region->geometry;
                    } else {
                        $area_point->region_map_area_pending = null;
                    }
                }
            }

            return $item;
        });

        return response()->json($response, 200);
    }
}
