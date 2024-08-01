<?php

namespace App\Services\ExternalService;

use App\Repositories\SMSRepository;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SMS360NRSService
{
    private $from;

    private $to;

    private $url;

    private $countryIndicator;

    private $countryCode;

    private $campaignName;

    public function __construct()
    {

        $this->from = config('360nrs.from');

        $this->url = 'https://dashboard.360nrs.com/api/rest/sms';

        $this->campaignName = config('360nrs.campaign_name');
        $this->headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => 'Basic '.$this->generateAuthorizationCode(),
        ];
    }

    private function generateAuthorizationCode()
    {

        $username = config('360nrs.username');
        $password = config('360nrs.password');

        return base64_encode($username.':'.$password);
    }

    public function send($to, string $message)
    {
        // Remove extra spaces
        $to =  preg_replace('/[^A-Za-z0-9\-]/', '', $to); // Removes special chars.

        $to = str_replace('-', '', $to); // Removes special chars.
        $this->to = $to;

        $body = [
            'from' => $this->from,
            'to' => [$this->to],
            'message' => $message,
            'campaignName' => $this->campaignName,
            $body['encoding'] = 'gsm-pt'

        ];

        if (config('app.env') === 'production') {
            $response = Http::withHeaders($this->headers)->post($this->url, $body);
            $response = $response->json();

            if (in_array($response, ['error', 'failed'])) {
                Log::error('Number: '. $this->to .' SMS Error: '.json_encode($response));
            }

        } else {
            $response = json_encode(['body' => 'success', '']);
        }


        $SMSRepository = new SMSRepository();
        $SMSRepository->store($this->to, $this->from, $message, json_encode($body), json_encode($response));
    }


    private function setCampaignName($campaignName)
    {
        $this->campaignName = $campaignName;
    }
}
