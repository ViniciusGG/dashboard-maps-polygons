<?php

namespace App\Repositories;

use App\Models\EmailLog;
use App\Repositories\BaseRepository;

class EmailLogRepository extends BaseRepository
{
    /**
     * @var Store
     */
    public function __construct()
    {
        parent::__construct(EmailLog::class);
    }

    public function store($to, $from, $message, $payload, $response)
    {
        return $this->model->create([
            'message' => $message,
            'to' => $to,
            'from' => $from,
            'payload' => $payload,
            'response' => $response,
        ]);
    }
}
