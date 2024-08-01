<?php
return [
    'staging_catch_all_email' => env('STAGING_CATCH_EMAIL', 'team@buzzvel.com'),
    'expired_password_days' => env('EXPIRED_PASSWORD_DAYS', '90'),
    'workspace' => [
        'license_types' => [
            'license_1' => 'License 1',
            'license_2' => 'License 2',
            'license_3' => 'License 3',
            'license_4' => 'License 4',
        ],
    ],
    'roles' => [
        1 => 'super_admin',
        2 => 'admin',
        3 => 'technicians',
        4 => 'external_service_provider',
    ],
    'alerts' => [
        'types' => [
            1 => 'alert',
            2 => 'wind',
            3 => 'ocean',
            4 => 'people',
        ],
        'category' => [
            1 => 'river',
            2 => 'sea',
            3 => 'lake',
            4 => 'air',
        ],
        'severity' => [
            1 => 'excellent',
            2 => 'good',
            3 => 'reasonable',
            4 => 'bad',
        ],
    ],
];
