<?php

namespace App\Mail;

use App\Models\Report;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailReport extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $report;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, Report $report)
    {
        $this->user = $user;
        $this->report = $report;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject(__('mail.report.subject'))
            ->markdown('emails.report');
    }
}
