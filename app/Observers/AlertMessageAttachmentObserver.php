<?php

namespace App\Observers;

use App\Models\Indicator;
use Illuminate\Support\Facades\Cache;

class AlertMessageAttachmentObserver
{
    public function updating(Indicator $indicator)
    {
        $currentImagePath = $indicator->getOriginal('file_name');
        $cacheKey = 'image_' . $currentImagePath;
        Cache::forget($cacheKey);
    }
}
