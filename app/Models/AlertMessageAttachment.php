<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AlertMessageAttachment extends Model
{
    use HasFactory, Uuids, SoftDeletes;

    protected $fillable = [
        'alert_message_id',
        'workspace_id',
        'file_type',
        'file_name',
        'alert_id',
    ];

    protected $casts = [
        'alert_message_id' => 'integer',
        'file_name' => 'string',
    ];

    protected $hidden = [
        'id',
        'alert_message_id',
        'workspace_id',
        'deleted_at',
    ];

    public function alertMessage()
    {
        return $this->belongsTo(AlertMessage::class);
    }

    public function workspace()
    {
        return $this->belongsTo(Workspace::class);
    }

    public function alert()
    {
        return $this->belongsTo(Alert::class);
    }

    public function getFileNameAttribute($value)
    {

        $cacheKey = 'image_' . $value;
        $cachedImagePath = Cache::get($cacheKey);

        if ($cachedImagePath) {
            return $cachedImagePath;
        }

        $imagePath = parse_url($value)['path'] ?? null;

        try {

            if (Storage::disk(config('internal.azulfy-bucket'))->exists($imagePath)) {
                $imagePath = Storage::disk(config('internal.azulfy-bucket'))->temporaryUrl(
                    $imagePath,
                    now()->addMinutes(60 * 24 * 2)
                );
            } else {
                $imagePath = config('app.url') . '/storage/images/no-image.png';
            }

            Cache::put($cacheKey, $imagePath, now()->addMinutes(30));
        } catch (\Exception $e) {
            $imagePath = config('app.url') . '/storage/images/no-image.png';
            Log::error('Error get aws url: ' . $e->getMessage());
        }
        return $imagePath;
    }

}
