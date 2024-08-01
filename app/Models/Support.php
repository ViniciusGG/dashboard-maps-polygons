<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Support extends Model
{
    use HasFactory, Uuids, SoftDeletes;

    protected $fillable = [
        'workspace_id',
        'user_id',
    ];

    protected $hidden = [
        'id',
        'workspace_id',
        'user_id',
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

    public static function getModelLabel()
    {
        return 'Support ';
    }

    public function workspace()
    {
        return $this->belongsTo(Workspace::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function supportMessageRead()
    {
        return $this->hasMany(UserSupportMessageRead::class)->where('user_id', auth()->user()->id)->where('is_read', 0);
    }

    public function supportMessage()
    {
        return $this->hasMany(SupportMessage::class);
    }

    public function supportMessageReadAll()
    {
        return $this->hasMany(UserSupportMessageRead::class);
    }

    public function supportMessageAttachment()
    {
        return $this->hasMany(SupportMessageAttachment::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($support) {
            $support->supportMessageReadAll()->delete();
            $support->supportMessage()->delete();
            $support->supportMessageAttachment()->delete();
        });
    }
}
