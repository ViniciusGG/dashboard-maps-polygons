<?php

namespace App\Mail;

use App\Models\Alert;
use App\Models\Support;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserNewMessage extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    protected $user;
    protected $workspaceId;
    protected $type;
    protected $identifier;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, int $workspaceId, int $identifier, string $type)
    {
        $this->user = $user;
        $this->workspaceId = $workspaceId;
        $this->type = $type;
        $this->identifier = $identifier;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $user = $this->user;
        $workspace = Workspace::findOrFail($this->workspaceId);
        
        if($this->type == 'alert') {
            $alert = Alert::findOrFail($this->identifier);
            $url = url(config('app.frontend_url').'/workspace/'.$workspace->uuid.'/alerts/?alert='.$alert->uuid);
        } else {
            $support = Support::findOrFail($this->identifier);
            if($user->isSuperAdmin()) {
                $url = url(config('app.frontend_url').'/support/?supportUuid='.$support->uuid.'&workspaceUuid='.$workspace->uuid);
            } else {
                $url = url(config('app.frontend_url').'/workspace/'.$workspace->uuid.'/support/'.$support->uuid);
            }   
        }

        $this->subject = __('mail.message.subject') . $workspace->name;

        return $this->markdown('emails.new-message', compact('user', 'workspace', 'url'));
    }
}
