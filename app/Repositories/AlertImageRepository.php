<?php

namespace App\Repositories;

use App\Models\AlertImage;
use App\Models\Indicator;
use App\Models\LicenseIndicator;
use App\Models\Workspace;

class AlertImageRepository extends BaseRepository
{

    public function __construct()
    {
        parent::__construct(AlertImage::class);
    }

    public function getAlertImage($alerImagetUuid)
    {
        $alert = $this->model->where('uuid', $alerImagetUuid)->firstOrFail();
        return $alert->toArray();
    }

    public function createAlertImage($data)
    {
        $alert = $this->model->create($data);
        return $alert->toArray();
    }

}
