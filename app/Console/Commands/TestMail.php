<?php

namespace App\Console\Commands;

use App\Services\ExternalService\SMS360NRSService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:mail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Mail';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        // Test Email


        Mail::raw('Hello World!', function($msg) {$msg->to("dev@buzzvel.com")->subject('Test Email'); });

    }
}
