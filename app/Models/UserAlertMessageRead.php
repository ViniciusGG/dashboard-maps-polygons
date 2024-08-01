<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserAlertMessageRead extends Model
{
    use HasFactory, Uuids, SoftDeletes;

    protected $fillable = [
        'user_id',
        'alert_message_id',
        'alert_id',
        'is_read',
    ];

    protected $hidden = [
        'id',
        'user_id',
        'alert_id',
        'alert_message_id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function alertMessage()
    {
        return $this->belongsTo(AlertMessage::class);
    }
}
