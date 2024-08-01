<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkspaceVideo extends Model
{
    use HasFactory;
    use Uuids;
    use SoftDeletes;

    protected $fillable = [
        'workspace_id',
        'name',
        'url',
        'region_map_area',
    ];

    protected $casts = [
        'region_map_area' => 'array',
    ];

    protected $hidden = [
        'id',
        'workspace_id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function workspace()
    {
        return $this->belongsTo(Workspace::class);
    }
}
