<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\ReportMessagesAlerts;
use App\Models\Alert;
use App\Repositories\ReportMessagesRepository;
use App\Services\ZipAttachmentsService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * @group Report Alert
 * APIs for managing auth
 * @authenticated
 * @package App\Http\Controllers\Api

 */
class ReportMessagesController extends Controller
{
    private $reportMessagesRepository;
    private $zipAttachmentsService;

    public function __construct(ReportMessagesRepository $repository)
    {
        $this->reportMessagesRepository = $repository;
    }

    /**
     * Create a new report Messages
     * @queryParam isShowMessages boolean Show messages in report. Example: true
     */
    public function reportMessagesAlert(string $workspace, string $alert, Request $request)
    {
        $alertInfo = Alert::where('uuid', $alert)->withTrashed()->firstOrFail();
        $isShowMessages = $request->query('isShowMessages', false);

        if ($alertInfo->attachments->count() == 0 && !$isShowMessages) {
            return response()->json([
                'status' => false,
                'message' => 'There are no messages to generate the report.',
            ]);
        }

        if ($alertInfo->alertMessages->count() == 0 && $isShowMessages) {
            return response()->json([
                'status' => false,
                'message' => 'There are no messages to generate the report.',
            ]);
        }
        
        ReportMessagesAlerts::dispatch($request->user()->id, $alertInfo->id, $isShowMessages);

        return response()->json([
            'status' => true,
            'message' => 'Report generation process initiated.',
        ]);
    }
}
