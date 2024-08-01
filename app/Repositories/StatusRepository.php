<?php

namespace App\Repositories;

use App\Models\Status;
use Illuminate\Support\Facades\Cache;

class StatusRepository extends BaseRepository
{

    public function __construct()
    {
        parent::__construct(Status::class);
    }

    public function getAllStatus()
    {
        return $this->model->all();
    }

    public function getStatusType($type)
    {
        return $this->model->where('type', $type)->first();
    }

    public function getStatusById($idStatus)
    {
        return Cache::rememberForever('status-' . $idStatus . '-' . app()->getLocale(), function () use ($idStatus) {
            $status = $this->model->where('id', $idStatus)->first();
            if (!$status) {
                return null;
            }
            return $status->name;
        });
    }
}
