<?php

namespace App\Jobs;

use App\Mail\NewAlert;
use App\Models\Alert;
use App\Models\User;
use App\Services\SMSService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NotificationNewAlert implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $alertId;

    /**
     * Create a new job instance.
     */
    public function __construct(int $alertId)
    {
        $this->alertId = $alertId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $alert = Alert::findOrFail($this->alertId);
        $workspace = $alert->workspace;
        $managerIds = $workspace->alerts_managers_ids;
        $dangerousAlertsManagersIds = $workspace->dangerous_alerts_managers_ids;
        if ($alert->severity == 4 && !empty($dangerousAlertsManagersIds)) {
            $managerIds = array_unique(array_merge($managerIds, $dangerousAlertsManagersIds));
        }
        if (empty($managerIds)) {
            return;
        }
        $admins = User::whereIn('id', $managerIds)->get();
        foreach ($admins as $admin) {
            app()->setLocale($admin->language);
            try {
                Mail::to($admin->email)->send(new NewAlert($admin, $workspace->id, $alert->id));
            } catch (\Exception $e) {
                Log::error('Error sending email to ' . $admin->email . ' ' . $e->getMessage());
            }
        }
        $service = new SMSService();
        $service->sendSMS($workspace->id, $admins);
    }
}
