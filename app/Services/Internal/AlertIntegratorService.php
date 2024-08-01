<?php

namespace App\Services\Internal;

use App\Models\Workspace;
use App\Repositories\AlertImageRepository;
use App\Repositories\AlertRepository;
use App\Repositories\BaseRepository;
use App\Repositories\IndicatorRepository;
use App\Repositories\SatelliteRepository;
use App\Repositories\WorkspaceAreaPointRepository;
use App\Services\PolygonService;
use Carbon\Carbon;

class AlertIntegratorService
{
    private $workspace;
    private $polygonName;
    private $workspaceAreas;
    private $polygonService;

    public function __construct()
    {
        $this->polygonService = new PolygonService();
    }

    public function bulkCreate($request)
    {
        $codeAzulfy = $request['azulfy_internal_code'];
        $this->workspace = Workspace::where('code_azulfy', $codeAzulfy)->firstOrFail();
        $content = $request['alerts'];
        $alertsCreatedIds = [];
        $indicatorRepository = new IndicatorRepository();
        $satelliteRepository = new SatelliteRepository();
        $workspaceAreaPointRepository = new WorkspaceAreaPointRepository();
        $this->workspaceAreas = $workspaceAreaPointRepository->getAreaPointByWorkspaceId(
            $this->workspace->id
        );
        foreach ($content as $contentAlert) {
            $alertRepository = new AlertRepository();
            $alertImageRepository = new AlertImageRepository();
            $coordinate =  $contentAlert['lng']. ',' . $contentAlert['lat'];
            if ($this->validatedPolygon($coordinate)) {
                $name = $this->polygonName;
            } else {
                $name = "Não identificado";
            }
            $indicator = $indicatorRepository->getByUuid(
                $contentAlert['indicator_uuid']
            );
            $satellite = $satelliteRepository->getByUuid(
                $contentAlert['satellite_uuid']
            );
            $alertDatetime = Carbon::createFromTimestamp($contentAlert['alert_timestamp']);
            $alert = $alertRepository->create([
                'workspace_id' => $this->workspace->id,
                'indicator' => $indicator->id,
                'name' => $name,
                'algorithm_source' => $contentAlert['algorithm_source'],
                'lat' => $contentAlert['lat'],
                'lng' => $contentAlert['lng'],
                'area' => $contentAlert['area'],
                'intensity' => $contentAlert['intensity'],
                'severity' => $contentAlert['severity'],
                'status_id' => 1, // default 'new'
                'category' => 3, // default 'ocean'
                'satellite_id' => $satellite->id,
                'description' => null,
                'alert_datetime' => $alertDatetime,
            ]);

            $alertsCreatedIds[] = $alert->uuid;
            if ($contentAlert['images']) {

                foreach ($contentAlert['images'] as $image) {
                    $alertImageRepository->create([
                        'alert_id' => $alert['id'],
                        'algorithm_type' => $image['algorithm_type'],
                        'url' => $image['url'],
                        'geo_coordinates' => ($image['geo_coordinates'])
                    ]);
                }
            }
        }

        return $alertsCreatedIds;
    }

    public function validatedPolygon($coordinate): bool
    {
        if (!isset($this->workspaceAreas)) {
            return false;
        }

        foreach ($this->workspaceAreas as $workspaceArea) {
            $region_map_area = json_decode($workspaceArea->region_map_area);
            $polygon = $region_map_area->geometry->coordinates[0] ?? null;

            if ($polygon && $this->polygonService->pointInPolygon($coordinate, $polygon)) {
                $this->polygonName = $region_map_area->properties->name;
                return true;
            }
        }
        return false;
    }


}
