<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class MatchFilesS3 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:match-files-s3';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $disk = Storage::disk('azulfy-default-images');
        $files = $disk->allFiles();
        $presignedUrls = [];

        foreach ($files as $file) {
            $presignedUrl = $disk->temporaryUrl($file, now()->addMinutes(60));
            $presignedUrls[] = $presignedUrl;
        }

    }
}
