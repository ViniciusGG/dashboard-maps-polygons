<?php

return [
    'from' => env('SMS_FROM', 'AZULFY'),
    'username' => env('SMS_USERNAME'),
    'password' => env('SMS_PASSWORD'),
    'campaign_name' => env('SMS_CAMPAIGN_NAME', 'Alerts'),
];
