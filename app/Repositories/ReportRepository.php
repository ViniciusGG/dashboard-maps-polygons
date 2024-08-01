<?php

namespace App\Repositories;

use App\Events\SendEmailReportEvent;
use App\Jobs\SendEmailReport;
use App\Models\Report;

class ReportRepository extends BaseRepository
{

    public function __construct()
    {
        parent::__construct(Report::class);
    }

    public function createReport($dataValidated)
    {
        $report = Report::create([
            'description' => $dataValidated['description'],
            'user_id' => $this->user->id,
        ]);

        if (isset($dataValidated['files'])) {
            foreach ($dataValidated['files'] as $key => $file) {
                $storedAttachment = $file->store('reports', 'public');
                $mimeType = $file->getMimeType();
                $type = in_array($mimeType, ['image/jpeg', 'image/png', 'image/jpg', 'image/gif']) ? 'image' : 'video';        
                $path = 'storage/' . $storedAttachment;
                $report->reportMedia()->create([
                    'url' => url($path),
                    'type' => $type, 
                    'order' => $key,
                    'user_id' => $this->user->id,
                ]);
            }
        }
        SendEmailReport::dispatch($this->user, $report);
        return $report->load('reportMedia');
    }
}
