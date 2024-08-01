<?php

namespace App\Observers;

use App\Jobs\AzulfyUpdateS3;
use App\Models\Workspace;

class WorkspaceObserver
{
    /**
     * Handle the Workspace "created" event.
     */
    public function created(Workspace $workspace): void
    {
        $orderIdentifier =  Workspace::select('*')
            ->orderBy('identifier', 'desc')
            ->withTrashed()
            ->first();


        $workspace['identifier'] = $orderIdentifier ? $orderIdentifier['identifier'] + 1 : 1;
        $workspace->save();

        //Create support
        $workspace->support()->create([
            'workspace_id' => $workspace->id,
        ]);

        AzulfyUpdateS3::dispatch();

    }

    /**
     * Handle the Workspace "updated" event.
     */
    public function updated(Workspace $workspace): void
    {
        AzulfyUpdateS3::dispatch();

    }

    /**
     * Handle the Workspace "deleted" event.
     */
    public function deleted(Workspace $workspace): void
    {
        AzulfyUpdateS3::dispatch();
    }

    /**
     * Handle the Workspace "restored" event.
     */
    public function restored(Workspace $workspace): void
    {
        AzulfyUpdateS3::dispatch();
    }

    /**
     * Handle the Workspace "force deleted" event.
     */
    public function forceDeleted(Workspace $workspace): void
    {
        AzulfyUpdateS3::dispatch();
    }
}
