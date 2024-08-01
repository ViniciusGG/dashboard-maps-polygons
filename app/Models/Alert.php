<?php

namespace App\Models;

use App\Repositories\AlertMessageObserverLogRepository;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Alert extends Model implements Auditable
{
    use HasFactory;

    use HasFactory;
    use Uuids;
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'name',
        'lat',
        'lng',
        'alert_datetime',
        'category',
        'description',
        'status_id',
        'indicator',
        'workspace_id',
        'user_id',
        'active',
        'alert_manager_id',
        'observers_ids',
        'uuid',
        'closed_at',
        'satellite_id',
        'intensity',
        'area',
        'severity',
        'algorithm_source'
    ];

    protected $guarded = [
        'created_at',
        'updated_at',
        'deleted_at',
        'workspace_id',
        'user_id',
        'id'
    ];

    protected $hidden = [
        'id',
        'alert_manager_id',
        'created_at',
        'updated_at',
        'workspace_id',
        'user_id',
        'workspace'
    ];

    public $searchFields = [
        'name',
        'indicator',
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
        return 'Alerta ';
    }

    public function workspace()
    {
        return $this->belongsTo(Workspace::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function alertMessages()
    {
        return $this->hasMany(AlertMessage::class);
    }

    public function alertMessageRead()
    {
        $dateAlertLog = (new AlertMessageObserverLogRepository)
            ->getByAlert($this->id, auth()->user()->id)
            ->invited_at ?? '';
        return $this->hasMany(UserAlertMessageRead::class)
            ->where('user_id', auth()->user()->id)
            ->where('is_read', 0)
            ->where('created_at', '>', $dateAlertLog);
    }

    public function attachments()
    {
        return $this->hasMany(AlertMessageAttachment::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function alertManager()
    {
        return $this->belongsTo(User::class, 'alert_manager_id');
    }

    public function indicators()
    {
        return $this->belongsTo(Indicator::class, 'indicator');
    }

    public function satellite()
    {
        return $this->belongsTo(Satellite::class);
    }
    public function getSatelliteUuidAttribute()
    {
        return $this->satellite ? $this->satellite->uuid : null;
    }
    public function getWorkspaceUuidAttribute()
    {
        return $this->workspace ? $this->workspace->uuid : null;
    }
    public function getFilterGroupUuidAttribute()
    {
        return $this->filter ? $this->filter->uuid : null;
    }
    public function getIndicatorsUuidAttribute()
    {
        return $this->indicators ? $this->indicators->uuid : null;
    }

    public function UserAlertMessageRead()
    {
        return $this->hasMany(UserAlertMessageRead::class);
    }

    public function filter()
    {
        return $this->belongsTo(Filter::class, 'category');
    }

    public function alertImages()
    {
        return $this->hasMany(AlertImage::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($alert) {
            $alert->alertImages()->delete();
            $alert->UserAlertMessageRead()->delete();
            $alert->alertMessages()->delete();
            $alert->attachments()->delete();
        });
    }

}
