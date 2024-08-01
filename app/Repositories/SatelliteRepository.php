<?php

namespace App\Repositories;

use App\Models\Filter;
use App\Models\Satellite;
use App\Models\Workspace;
use App\Services\PolygonService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SatelliteRepository extends BaseRepository
{

    public function __construct()
    {
        parent::__construct(Satellite::class);
    }

    public function create($data)
    {
        $satellite = $this->model;
        $satellite->name = $data['name'];
        $satellite->description = [
            'en' => $data['description_en'],
            'pt' => $data['description_pt']
        ];
        $satellite->save();

        return $satellite;
    }

    public function updateByUuid($data, $uuid)
    {
        $satellite = $this->model->where('uuid', $uuid)->first();

        /* Can be optional to update:
         * - name
         * - description_en
         * - description_pt

         */
        if (isset($data['name'])) {
            $satellite->name = $data['name'];
        }
        if (isset($data['description_en']) || isset($data['description_pt'])) {

            $descriptionEn = $satellite->description['en'];
            $descriptionPt = $satellite->description['pt'];

            if (isset($data['description_en'])) {
                $descriptionEn = $data['description_en'];
            }
            if (isset($data['description_pt'])) {
                $descriptionPt = $data['description_pt'];
            }
            $satellite->description = [
                'en' => $descriptionEn,
                'pt' => $descriptionPt
            ];
        }


        $satellite->save();

        return $satellite->fresh();

    }


}
