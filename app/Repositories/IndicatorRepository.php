<?php

namespace App\Repositories;

use App\Models\AlertImage;
use App\Models\Filter;
use App\Models\Indicator;
use App\Models\LicenseIndicator;
use App\Models\Workspace;

class IndicatorRepository extends BaseRepository
{

    public function __construct()
    {
        parent::__construct(Indicator::class);
    }

    public function create($data)
    {
        /* Get filter ID from uuid */
        $filter = Filter::select('id')->where('uuid', $data['parent_group_indicator_uuid'])->first();
        $indicator = $this->model;
        $indicator->name = $data['name'];
        $indicator->description = [
            'en' => $data['description_en'],
            'pt' => $data['description_pt']
            ];
        $indicator->image = $data['s3_default_path'];
        $indicator->filter_id = $filter->id;
        $indicator->status = $data['status'];
        $indicator->save();

        return $indicator;
    }

    public function updateByUuid($data, $uuid)
    {
        $indicator = $this->model->where('uuid', $uuid)->first();

        /* Can be optional to update:
        *  - parent_group_indicator_uuid
         * - status
         * - name
         * - description_en
         * - description_pt
         * - s3_default_path

         */

        if(isset($data['parent_group_indicator_uuid'])){
            $filter = Filter::select('id')->where('uuid', $data['parent_group_indicator_uuid'])->first();
            $indicator->filter_id = $filter->id;
        }

        if (isset($data['status'])) {
            $indicator->status = $data['status'];
        }

        if (isset($data['name'])) {
            $indicator->name = $data['name'];
        }
        if (isset($data['description_en']) || isset($data['description_pt'])) {

        $descriptionEn = $indicator->description['en'];
        $descriptionPt = $indicator->description['pt'];

        if (isset($data['description_en'])) {
            $descriptionEn = $data['description_en'];
        }
        if (isset($data['description_pt'])) {
            $descriptionPt = $data['description_pt'];
        }
            $indicator->description = [
                'en' => $descriptionEn,
                'pt' => $descriptionPt
            ];
        }


        if (isset($data['s3_default_path'])) {
            $indicator->image = $data['s3_default_path'];
        }

        $indicator->save();

        return $indicator->fresh();

    }


}
