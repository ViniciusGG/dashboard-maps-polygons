<?php

namespace App\Repositories;

use App\Events\SendEmailReportEvent;
use App\Jobs\SendEmailReport;
use App\Models\Alert;
use App\Models\AlertMessage;
use App\Models\Report;
use ZipArchive;

class ReportMessagesRepository extends BaseRepository
{

    public function __construct()
    {
        parent::__construct(Report::class);
    }

    public function getAllMessagesByWorkspace($alertId)
    {
        $alertInfo = Alert::with(['status', 'filter'])->where('id', $alertId)->first();
        $messages = AlertMessage::with(['user', 'attachments'])->where('alert_id', $alertId)->orderBy('created_at', 'asc')->get();
        $verifyMessage = new AlertMessageRepository();
        $messages = $verifyMessage->verifyTypeMessage($messages);
        return [
            'alert' => $alertInfo,
            'messages' => $messages,
        ];
    }
}
