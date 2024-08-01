<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AlertMessageObserverLog extends Model
{
    use HasFactory;
    use Uuids;
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'user_id',
        'alert_id',
        'invited_at',
    ];

    protected $hidden = [
        'id',
        'alert_id',
        'user_id',
        'workspace_id',
        'updated_at',
        'created_at',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'alert_id' => 'integer',
        'invited_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function alert()
    {
        return $this->belongsTo(Alert::class);
    }
}
