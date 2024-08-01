<?php

namespace App\Observers;

use App\Models\AlertMessage;

class AlertMessageObserver
{
    /**
     * Handle the AlertMessage "created" event.
     */
    public function created(AlertMessage $alertMessage): void
    {
        $alertMessage->addUserAlertMessageRead();
    }

    /**
     * Handle the AlertMessage "updated" event.
     */
    public function updated(AlertMessage $alertMessage): void
    {
        //
    }

    /**
     * Handle the AlertMessage "deleted" event.
     */
    public function deleted(AlertMessage $alertMessage): void
    {
        //
    }

    /**
     * Handle the AlertMessage "restored" event.
     */
    public function restored(AlertMessage $alertMessage): void
    {
        //
    }

    /**
     * Handle the AlertMessage "force deleted" event.
     */
    public function forceDeleted(AlertMessage $alertMessage): void
    {
        //
    }
}
