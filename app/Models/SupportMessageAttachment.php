<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SupportMessageAttachment extends Model
{
    use HasFactory, Uuids, SoftDeletes;

    protected $fillable = [
        'support_id',
        'workspace_id',
        'file_name',
        'file_path',
    ];

    protected $hidden = [
        'id',
        'alert_message_id',
        'workspace_id',
        'deleted_at',
    ];

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public static function getModelLabel()
    {
        return 'Support Message Attachment ';
    }

    public function support()
    {
        return $this->belongsTo(Support::class);
    }

    public function workspace()
    {
        return $this->belongsTo(Workspace::class);
    }
}
