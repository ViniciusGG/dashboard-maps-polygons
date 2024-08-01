<?php

namespace App\Observers;

use App\Models\SupportMessage;

class SupportMessageObserver
{
    /**
     * Handle the SupportMessage "created" event.
     */
    public function created(SupportMessage $supportMessage): void
    {
        $supportMessage->addUserSupportMessageRead();
    }

    /**
     * Handle the SupportMessage "updated" event.
     */
    public function updated(SupportMessage $supportMessage): void
    {
        //
    }

    /**
     * Handle the SupportMessage "deleted" event.
     */
    public function deleted(SupportMessage $supportMessage): void
    {
        //
    }

    /**
     * Handle the SupportMessage "restored" event.
     */
    public function restored(SupportMessage $supportMessage): void
    {
        //
    }

    /**
     * Handle the SupportMessage "force deleted" event.
     */
    public function forceDeleted(SupportMessage $supportMessage): void
    {
        //
    }
}
