<?php

namespace App\Services\AzulfyConnect;


use App\Repositories\WorkspaceRepository;
use Illuminate\Support\Facades\Storage;

class AzulfyConnectService
{

    public function updateWorkspaceFile(){

        $r = new WorkspaceRepository();
        $clients = $r->getAllWith('areaPoints', 'indicators');

        $data = [];
        $geojsonFiles = [];

        foreach($clients as $client){

            if($client->code_azulfy === null){
                continue;
            }
            $region  = json_decode($client->region_map_area);
            $regionAreaPoints = $client->areaPoints->map(function($areaPoint){
                $region = json_decode($areaPoint->region_map_area);
                $regionPending = json_decode($areaPoint->region_map_area_pending);
                return [
                    'name' => $areaPoint->name,
                    'region_map_area' => $region->geometry ?? null,
                    'region_map_area_pending' => $regionPending->geometry ?? null,
                ];
            });

            $regionCoordinates = $region->geometry ?? null;
            $geojsonFiles[$client->code_azulfy] = $regionCoordinates;

            $indicators = $client->indicators->pluck('uuid')->toArray();


            $data[] = [
                'name' => $client->name,
                'azulfy_code' => $client->code_azulfy,
                'indicators' => $indicators,
                'region_map_area' => $regionCoordinates,
                'region_map_points' => $regionAreaPoints,
                'created_at' => $client->created_at->timestamp,
                'updated_at' => $client->updated_at->timestamp,
            ];
        }

        $path = config('internal.folder-azulfy-bucket').'/dumps';

        foreach($geojsonFiles as $key => $geojson){
            Storage::disk(config('internal.azulfy-bucket'))->put($path.'/'.'geojsons/'.$key.'.json', json_encode($geojson));
        }

        Storage::disk(config('internal.azulfy-bucket'))->put($path.'/'.'workspace.json', json_encode($data));
     }

}
