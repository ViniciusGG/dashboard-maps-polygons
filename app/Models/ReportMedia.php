<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportMedia extends Model implements Auditable
{
    use HasFactory, Uuids, \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'url',
        'order',
        'type',
        'report_id',
        'user_id',
    ];
    protected $hidden = [
        'id',
        'user_id',
        'report_id',
        'updated_at',
        'created_at'
    ];

    public function scopeVideos($query)
    {
        return $query->where('type', 'video');
    }

    public static function getModelLabel()
    {
        return __('reportMedia.label');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class);
    }
}
