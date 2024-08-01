<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class License extends Model
{
    use HasFactory;
    use Uuids;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'members',
        'color'
    ];

    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
    ];

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public static function getModelLabel()
    {
        return 'License ';
    }

    public function indicators()
    {
        return $this->belongsToMany(Indicator::class, 'license_indicators', 'license_id', 'indicator_id');
    }

    public function services()
    {
        return $this->belongsToMany(Services::class, 'license_services', 'license_id', 'service_id');
    }

    public function filters()
    {
        return $this->belongsToMany(Filter::class, 'license_filters', 'license_id', 'filter_id');
    }

    public function licensePermissionsAdmin()
    {
        return $this->hasMany(LicensePermissions::class)->where('role_id', 2)->with('permissions');
    }

    public function licensePermissionsTechnician()
    {
        return $this->hasMany(LicensePermissions::class)->where('role_id', 3)->with('permissions');
    }

    public function licensePermissionsExternalServiceProvider()
    {
        return $this->hasMany(LicensePermissions::class)->where('role_id', 4)->with('permissions');
    }

}
