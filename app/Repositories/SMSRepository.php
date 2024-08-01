<?php

namespace App\Repositories;

use App\Models\SmsLog;
use App\Repositories\BaseRepository;

class SMSRepository extends BaseRepository
{
    /**
     * @var Store
     */
    public function __construct()
    {
        parent::__construct(SmsLog::class);
    }

    public function store($to, $from, $message, $payload, $status)
    {
        return $this->model->create([
            'message' => $message,
            'to' => $to,
            'from' => $from,
            'payload' => $payload,
            'response' => $status,
        ]);
    }
}
