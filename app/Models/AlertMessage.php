<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AlertMessage extends Model
{
    use HasFactory, Uuids, SoftDeletes;

    protected $fillable = [
        'user_id',
        'alert_id',
        'workspace_id',
        'message',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'alert_id' => 'integer',
        'workspace_id' => 'integer',
        'message' => 'string',
    ];

    protected $hidden = [
        'id',
        'user_id',
        'alert_id',
        'workspace_id',
        'workspace',
        'deleted_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function workspace()
    {
        return $this->belongsTo(Workspace::class);
    }

    public function alert()
    {
        return $this->belongsTo(Alert::class);
    }

    public function attachments()
    {
        return $this->hasMany(AlertMessageAttachment::class);
    }

    public function addUserAlertMessageRead()
    {
        $users = $this->workspace->users;
        foreach ($users as $user) {
            $is_read = ($user->id == $this->user_id) ? 1 : 0;
            $userAlertMessageRead = new UserAlertMessageRead();
            $userAlertMessageRead->user_id = $user->id;
            $userAlertMessageRead->alert_message_id = $this->id;
            $userAlertMessageRead->alert_id = $this->alert_id;
            $userAlertMessageRead->is_read = $is_read;
            $userAlertMessageRead->save();
        }
    }

    public function userAlertMessageRead()
    {
        return $this->hasOne(UserAlertMessageRead::class);
    }

    public function read()
    {
        return $this->hasOne(UserAlertMessageRead::class);
    }

    public function statusBefore()
    {
        return $this->belongsTo(Status::class, 'status_id_before');
    }

    public function statusAfter()
    {
        return $this->belongsTo(Status::class, 'status_id_after');
    }

    public static function getModelLabel()
    {
        return 'Alert Message ';
    }
}
