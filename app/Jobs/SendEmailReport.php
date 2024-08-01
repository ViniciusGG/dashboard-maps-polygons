<?php

namespace App\Jobs;

use App\Mail\EmailReport;
use App\Mail\UserNewMessage;
use App\Models\AlertMessage;
use App\Models\Report;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendEmailReport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $report;

    /**
     * Create a new job instance.
     */
    public function __construct(User $user, Report $report)
    {
        $this->user = $user;
        $this->report = $report;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $user = $this->user;
        $report = $this->report;
        app()->setLocale($user->language);

        try {
            Mail::to($user->email)->send(new EmailReport($user, $report));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
