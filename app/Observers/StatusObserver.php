<?php

namespace App\Observers;

use App\Models\Status;
use Illuminate\Support\Facades\Cache;

class StatusObserver
{
    /**
     * Handle the Status "created" event.
     */
    public function created(Status $status): void
    {
        Cache::forget('status-'.$status->id.'en');
        Cache::forget('status-'.$status->id.'pt');
    }

    /**
     * Handle the Status "updated" event.
     */
    public function updated(Status $status): void
    {
        Cache::forget('status-'.$status->id.'en');
        Cache::forget('status-'.$status->id.'pt');    }

    /**
     * Handle the Status "deleted" event.
     */
    public function deleted(Status $status): void
    {
        Cache::forget('status-'.$status->id.'en');
        Cache::forget('status-'.$status->id.'pt');    }

    /**
     * Handle the Status "restored" event.
     */
    public function restored(Status $status): void
    {
        //
    }

    /**
     * Handle the Status "force deleted" event.
     */
    public function forceDeleted(Status $status): void
    {
        //
    }
}
