<?php
namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    use HasFactory;
    use Uuids;

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    protected $hidden = [
        'pivot',
        'id'
    ];
}