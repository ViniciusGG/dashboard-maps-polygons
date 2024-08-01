<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SupportMessage extends Model
{
    use HasFactory, Uuids, SoftDeletes;

    protected $fillable = [
        'support_id',
        'user_id',
        'workspace_id',
        'message',
        'read'
    ];


    protected $hidden = [
        'id',
        'user_id',
        'support_id',
        'workspace_id',
        'deleted_at',
    ];

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public static function getModelLabel()
    {
        return 'Support Message ';
    }

    public function support()
    {
        return $this->belongsTo(Support::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function workspace()
    {
        return $this->belongsTo(Workspace::class);
    }

    public function attachments()
    {
        return $this->hasMany(SupportMessageAttachment::class);
    }

    public function addUserSupportMessageRead()
    {
        $users = $this->workspace->users;
        foreach ($users as $user) {
            $is_read = ($user->id == $this->user_id) ? 1 : 0;
            UserSupportMessageRead::create([
                'user_id' => $user->id,
                'support_message_id' => $this->id,
                'support_id' => $this->support_id,
                'is_read' => $is_read
            ]);
        }
    }

    public function userSupportMessageRead()
    {
        return $this->hasOne(UserSupportMessageRead::class);
    }
}
