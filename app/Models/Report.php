<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Report extends Model implements Auditable
{
    use HasFactory, Uuids, \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'description',
        'user_id',
    ];
    protected $hidden = [
        'id',
        'user_id',
        'updated_at'
    ];

    public static function getModelLabel()
    {
        return __('report.label');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reportMedia()
    {
        return $this->hasMany(ReportMedia::class);
    }
}
