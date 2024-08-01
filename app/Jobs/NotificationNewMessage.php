<?php

namespace App\Jobs;

use App\Mail\UserNewMessage;
use App\Models\Workspace;
use App\Services\SMSService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NotificationNewMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $workspaceId;
    protected $identifier;
    protected $lastNotificationSent;
    protected $type;

    /**
     * Create a new job instance.
     */
    public function __construct(int $workspaceId, int $identifier, string $lastNotificationSent = null, string $type)
    {
        $this->workspaceId = $workspaceId;
        $this->identifier = $identifier;
        $this->lastNotificationSent = $lastNotificationSent;
        $this->type = $type;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $workspace = Workspace::findOrFail($this->workspaceId);
        if (now()->diffInMinutes($this->lastNotificationSent) > 5 || empty($this->lastNotificationSent)) {
            $managerIds = $workspace->alerts_managers_ids;
            if (empty($managerIds)) {
                return;
            }
            $admins = $workspace->users()->whereIn('users.id', $managerIds)->get();
            foreach ($admins as $admin) {
                app()->setLocale($admin->language);
                try {
                    Mail::to($admin->email)->send(new UserNewMessage($admin, $workspace->id, $this->identifier, $this->type));
                } catch (\Exception $e) {
                    Log::error($e->getMessage());
                }
            }

        }
    }
}
