<?php

namespace App\Console\Commands;

use App\Services\ExternalService\SMS360NRSService;
use Illuminate\Console\Command;

class TestSMS extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:sms';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test SMS';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $orderIdentifier = '1000';
        $orderCode = 'x4r481';
        $smsUrl = config('sms-status-config.sms-order-url');

        $message = 'Alert ' . $orderIdentifier . ' ' . $orderCode;
          $orderCode;

        $phone = $this->ask('Qual o número de telefone?', '+351915948695');

        $smsService = new SMS360NRSService();
        $r = $smsService->send($phone, $message);

        dd($r);
    }
}
