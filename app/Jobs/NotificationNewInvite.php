<?php

namespace App\Jobs;

use App\Mail\UserNewInvite;
use App\Mail\UserNewMessage;
use App\Models\User;
use App\Models\Workspace;
use App\Services\SMSService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NotificationNewInvite implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $workspaceId;
    protected $alertId;
    protected $userIds;

    /**
     * Create a new job instance.
     */
    public function __construct(int $workspaceId, int $alertId, array $userIds = null)
    {
        $this->workspaceId = $workspaceId;
        $this->alertId = $alertId;
        $this->userIds = $userIds;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $workspace = Workspace::findOrFail($this->workspaceId);
        foreach ($this->userIds as $userId) {
            $user = User::findOrFail($userId);
            app()->setLocale($user->language);

            try {
                Mail::to($user->email)->send(new UserNewInvite($user, $workspace->id, $this->alertId));
            } catch (\Exception $e) {
                Log::error($e->getMessage());
            }
        }
    }
}
