<?php

namespace App\Services;

use App\Models\Workspace;
use App\Services\ExternalService\SMS360NRSService;

class SMSService
{
    public function sendSMS($workspaceId, $admins)
    {
        $service = new SMS360NRSService();
        $workspace = Workspace::findOrFail($workspaceId);

        foreach ($admins as $admin) {
            if ($admin->phone == null || $admin->phone == '') {
                continue;
            }
            app()->setLocale($admin->language);
            $message = __('sms.new-alert').$workspace->name;
            $service->send($admin->phone, $message);
        }
    }
}
