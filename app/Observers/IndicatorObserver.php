<?php

namespace App\Observers;

use App\Models\Indicator;
use Illuminate\Support\Facades\Cache;

class IndicatorObserver
{
    public function updating(Indicator $indicator)
    {
        $currentImagePath = $indicator->getOriginal('image');
        $cacheKey = 'image_' . $currentImagePath;
        Cache::forget($cacheKey);
    }
}
