<?php

namespace App\Mail;

use App\Models\Alert;
use App\Models\Indicator;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserNewInvite extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    protected $user;
    protected $workspaceId;
    protected $alertId;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, int $workspaceId, int $alertId)
    {
        $this->user = $user;
        $this->workspaceId = $workspaceId;
        $this->alertId = $alertId;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $admin = auth()->user();
        $user = $this->user;
        $workspace = Workspace::findOrFail($this->workspaceId);
        $alert = Alert::findOrFail($this->alertId);
        $indicator = Indicator::findOrFail($alert->indicator);
        $this->subject = __('mail.invite-alert.subject') . $workspace->name;
        $url = url(config('app.frontend_url').'/workspace/'.$workspace->uuid.'/alerts/?alert='.$alert->uuid);

        return $this->markdown('emails.new-invite', compact('user', 'alert', 'workspace', 'admin', 'indicator', 'url'));

    }
}
