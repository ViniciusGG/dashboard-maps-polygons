<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LicensePermissions extends Model
{
    use HasFactory;
    use Uuids;

    protected $fillable = [
        'license_id',
        'permission_id',
        'role_id'
    ];

    protected $hidden = [
        'id',
        'license_id',
        'permission_id',
        'role_id',
        'created_at',
        'updated_at',
    ];

    public function licenses()
    {
        return $this->belongsTo(License::class);
    }

    public function permissions()
    {
        return $this->belongsTo(Permission::class, 'permission_id');
    }

    public function roles()
    {
        return $this->belongsTo(Role::class);
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public static function getModelLabel()
    {
        return 'License Permission ';
    }

}
