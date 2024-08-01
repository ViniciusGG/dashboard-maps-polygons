<?php

namespace App\Mail;

use App\Models\Alert;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ReportMessagesAlert extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $alert;
    public $pdfFilePath;
    public $zipFilePath;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, Alert $alert, $zipFilePath, $pdfFilePath)
    {
        $this->user = $user;
        $this->alert = $alert;
        $this->pdfFilePath = $pdfFilePath;
        $this->zipFilePath = $zipFilePath;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email = $this->markdown('emails.report-messages-alert')
            ->subject('Report Messages Alert');

        if (!empty($this->pdfFilePath)) {
            $email->attach($this->pdfFilePath);
        }
        if (!empty($this->zipFilePath)) {
            $email->attach($this->zipFilePath);
        }

        return $email;
    }
}
