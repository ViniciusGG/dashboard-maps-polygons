<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class Indicator extends Model
{
    use HasFactory;
    use Uuids;

    protected $fillable = [
        'name',
        'filter_id',
        'description',
        'image'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'pivot',
        'filter'
    ];

    protected $appends = [
        'filter_uuid'
    ];

    protected $casts = [
        'description' => 'array',
    ];

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function getFilterUuidAttribute()
    {
        return $this->filter->uuid;
    }
    public function getImageAttribute($value)
    {
        $cacheKey = 'image_' . $value;
        $cachedImagePath = Cache::get($cacheKey);

        if ($cachedImagePath) {
            return $cachedImagePath;
        }

        $imagePath = parse_url($value)['path'] ?? null;

        try {
            if (Storage::disk('azulfy-default-images')->exists($imagePath)) {
                $imagePath = Storage::disk('azulfy-default-images')->temporaryUrl(
                    $imagePath,
                    now()->addMinutes(60 * 24 * 2)
                );
            } else {
                $imagePath = asset('storage/images/' . $value);
            }

            Cache::put($cacheKey, $imagePath, now()->addMinutes(30));
        } catch (\Exception $e) {
            $imagePath = $value;
            Log::error('Error get aws url: ' . $e->getMessage());
        }

        return $imagePath;
    }


    public static function getModelLabel()
    {
        return 'Indicator ';
    }

    public function filter()
    {
        return $this->belongsTo(Filter::class, 'filter_id', 'id');
    }
}
