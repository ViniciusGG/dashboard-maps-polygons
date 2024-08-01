<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class AlertImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'alert_id',
        'geo_coordinates',
        'algorithm_type',
        'url',
    ];

    protected $casts = [
        'geo_coordinates' => 'array',
    ];

    public function alert()
    {
        return $this->belongsTo(Alert::class);
    }

    public function getUrlAttribute($value)
    {
        $ignoredFileNames = [
            'map-raster-filter-2.png',
            'map-raster-filter-1.png'
        ];

        $fileName = basename(parse_url($value)['path']);

        if (in_array($fileName, $ignoredFileNames)) {
            return $value;
        }

        $imagePath = parse_url($value)['path'] ?? $value;

        try {
            $imagePath = Storage::disk('azulfy-alerts-images')->temporaryUrl(
                $imagePath,
                now()->addMinutes(60 * 24 * 2)
            );
        } catch (\Exception $e) {
            $imagePath = $value;
        }

        return $imagePath;
    }
}
