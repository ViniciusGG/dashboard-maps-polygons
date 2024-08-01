<?php

namespace App\Jobs;

use App\Mail\ReportMessagesAlert;
use App\Models\Alert;
use App\Models\User;
use App\Repositories\ReportMessagesRepository;
use App\Services\ZipAttachmentsService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ReportMessagesAlerts implements ShouldQueue
{
    public $userId;
    public $alertId;
    public $isShowMessages;
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(int $userId, int $alertId, bool $isShowMessages)
    {
        $this->userId = $userId;
        $this->alertId = $alertId;
        $this->isShowMessages = $isShowMessages;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $reportMessagesRepository = new ReportMessagesRepository();
        $messages = $reportMessagesRepository->getAllMessagesByWorkspace($this->alertId);

        $user = User::find($this->userId);
        $alert = Alert::find($this->alertId);
        $zipService = new ZipAttachmentsService();

        $zipFilePath = $zipService->zipAttachments($messages, $user->id);

        try {
            $pdf = Pdf::loadView('pdf.report', ['messages' => $messages, 'isShowMessages' => $this->isShowMessages]);
            $pdf->setPaper('A4', 'portrait');

            $pdfFilePath = storage_path('app/public/report-alert.pdf');
            $pdf->save($pdfFilePath);

            app()->setLocale($user->language);

            Mail::to($user->email)->send(
                new ReportMessagesAlert($user, $alert, $zipFilePath, $pdfFilePath)
            );

            unlink($zipFilePath);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
