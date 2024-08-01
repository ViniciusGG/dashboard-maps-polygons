<?php

namespace App\Traits;

trait AuditCreatedUpdatedByUser
{

    protected static function bootAuditCreatedUpdatedByUser()
    {
        static::creating(function ($model) {
            $model->created_by = auth()->check() ? auth()->user()->uuid : null;
        });

        static::updating(function ($model) {
            if (array_key_exists('updated_by', $model->toArray()))
                $model->updated_by = auth()->check() ? auth()->user()->uuid : null;
        });
    }
}
