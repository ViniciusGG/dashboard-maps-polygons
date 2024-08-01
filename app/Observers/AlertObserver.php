<?php

namespace App\Observers;

use App\Jobs\NotificationNewAlert;
use App\Models\Alert;
use Illuminate\Support\Facades\Cache;

class AlertObserver
{
    /**
     * Handle the Alert "created" event.
     */
    public function created(Alert $alert): void
    {
        NotificationNewAlert::dispatch($alert->id);
    }

    /**
     * Handle the Alert "updated" event.
     */
    public function updated(Alert $alert): void
    {
        //
    }

    /**
     * Handle the Alert "deleted" event.
     */
    public function deleted(Alert $alert): void
    {
    }

    /**
     * Handle the Alert "restored" event.
     */
    public function restored(Alert $alert): void
    {
        //
    }

    /**
     * Handle the Alert "force deleted" event.
     */
    public function forceDeleted(Alert $alert): void
    {
        //
    }
}
