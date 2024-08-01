<?php
namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    use HasFactory;
    use Uuids;


    public function getRouteKeyName()
    {
        return 'uuid';
    }

    protected $hidden = [
        'pivot',
        'id',
        'guard_name',
        'created_at',
        'updated_at'
    ];
}