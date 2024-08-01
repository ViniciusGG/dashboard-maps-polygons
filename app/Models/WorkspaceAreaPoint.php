<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkspaceAreaPoint extends Model
{
    use HasFactory, SoftDeletes, Uuids;

    protected $fillable = [
        'workspace_id',
        'name',
        'region_map_area',
        'region_map_area_pending',
    ];

    protected $casts = [
        'region_map_area' => 'array',
        'region_map_area_pending' => 'array',
    ];

    protected $hidden = [
        'id',
        'workspace_id',
        'created_at',
        'updated_at',
        'deleted_at',
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

    public static function getModelLabel()
    {
        return __('workspace.area_points.label');
    }   

}
