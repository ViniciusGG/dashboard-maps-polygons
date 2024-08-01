<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Workspace extends Model
{
    use HasFactory;
    use Uuids;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'identifier',
        'admin_email',
        'admin_name',
        'license_id',
        'region_map_area',
        'region_map_area_pending',
        'code_azulfy',
        'status',
        'alerts_managers_ids',
        'dangerous_alerts_managers_ids',
    ];

    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $casts = [
        'alerts_managers_ids' => 'array',
        'dangerous_alerts_managers_ids' => 'array',
    ];

    public $searchFields = [
        'name',
        'admin_email',
        'admin_name',
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

    public static function getModelLabel()
    {
        return 'Municipality ';
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'workspace_members', 'workspace_id', 'user_id')->withPivot('role_id')->wherePivot('deleted_at', null);
    }

    public function workspaceMembers()
    {
        return $this->hasMany(WorkspaceMember::class);
    }

    public function admins()
    {
        return $this->users()->where('role_id', 2)->get();
    }

    public function alerts()
    {
        return $this->hasMany(Alert::class);
    }

    public function support()
    {
        return $this->hasOne(Support::class);
    }

    public function supportMessageRead()
    {
        return $this->hasMany(SupportMessage::class)->where('read', 0);
    }

    public function areaPoints()
    {
        return $this->hasMany(WorkspaceAreaPoint::class);
    }

    public function license()
    {
        return $this->belongsTo(License::class);
    }

    public function videos()
    {
        return $this->hasMany(WorkspaceVideo::class);
    }

    public function indicators()
    {
        return $this->hasManyThrough(
            Indicator::class,
            LicenseIndicator::class,
            'license_id',
            'id',
            'license_id',
            'indicator_id'
        );
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($workspace) {
            $workspace->workspaceMembers()->delete();
            $workspace->alerts()->delete();
            $workspace->areaPoints()->delete();
            $workspace->support()->delete();
            $workspace->videos()->delete();
        });
    }
}
