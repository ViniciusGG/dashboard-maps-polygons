<?php

namespace App\Jobs;

use App\Services\AzulfyConnect\AzulfyConnectService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AzulfyUpdateS3 implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Generate the workspace file and the geojsons files
        $azulfyConnectService = new AzulfyConnectService();
        $azulfyConnectService->updateWorkspaceFile();
    }
}
