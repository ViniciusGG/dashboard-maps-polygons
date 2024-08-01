<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;
use App\Casts\AzulfyEncrypted;

class User extends Authenticatable implements Auditable
{
    use HasApiTokens, HasFactory, Notifiable, Uuids, \OwenIt\Auditing\Auditable, SoftDeletes, HasRoles;

    protected $guard_name = 'api';

    protected $fillable = [
        'name',
        'email',
        'password',
        'language',
        'password_expires_at',
        'accepted_invite',
        'phone',
    ];

    protected $hidden = [
        'id',
        'pivot',
        'password',
        'remember_token',
        'created_at',
        'updated_at',
        'deleted_at',
        'roles',
        'permissions'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'email' => AzulfyEncrypted::class,
        'phone' => AzulfyEncrypted::class,
    ];

    protected $appends = [
        'initials'
    ];

    public function workspaces()
    {
        return $this->belongsToMany(Workspace::class, 'workspace_members', 'user_id', 'workspace_id');
    }

    public function workspaceMembers()
    {
        return $this->hasMany(WorkspaceMember::class, 'user_id', 'id');
    }

    public function role()
    {
        return $this->hasOne(Role::class, 'id', 'role_id');
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public static function getModelLabel()
    {
        return 'User ';
    }

    public function getInitialsAttribute()
    {
        $name = $this->name;
        $words = explode(' ', $name);
        if (count($words) >= 2) {
            return mb_strtoupper(
                mb_substr($words[0], 0, 1, 'UTF-8') .
                    mb_substr(end($words), 0, 1, 'UTF-8'),
                'UTF-8'
            );
        }

        preg_match_all('#([A-Z]+)#', $name, $capitals);
        if (count($capitals[1]) >= 2) {
            return mb_substr(implode('', $capitals[1]), 0, 2, 'UTF-8');
        }
        return mb_strtoupper(mb_substr($name, 0, 2, 'UTF-8'), 'UTF-8');
    }

    public function isSuperAdmin()
    {
        return $this->hasRole('super_admin');
    }

}
