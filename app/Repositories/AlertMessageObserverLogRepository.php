<?php

namespace App\Repositories;

use App\Jobs\NotificationNewInvite;
use App\Models\AlertMessageObserverLog;
use Illuminate\Support\Facades\Log;

class AlertMessageObserverLogRepository extends BaseRepository
{

    public function __construct()
    {
        parent::__construct(AlertMessageObserverLog::class);
    }

    public function getByAlert($alertId, $userId)
    {
        return $this->model->where('alert_id', $alertId)->where('user_id', $userId)->first();
    }

    public function createLog($alert, $addUserIds = [], $removeUserIds = [])
    {
        if($addUserIds) {
            $this->addUsers($alert, $addUserIds);
        }
        if($removeUserIds) {
            $this->removeUsers($alert, $removeUserIds);
        }
    }

    private function sendEmailInvite($alert, $userIds)
    {
        NotificationNewInvite::dispatch($alert->workspace->id, $alert->id, $userIds);
    }

    private function checkIfUserWasInvited($alert, $userIds)
    {
        $newUserIds = [];
        foreach ($userIds as $userId) {
            $log = $this->getByAlert($alert->id, $userId);
            if (!$log) {
                $newUserIds[] = $userId;
            }
        }
        return $newUserIds;
    }

    private function removeUsers($alert, $userIds)
    {
        $this->model->where('alert_id', $alert->id)->whereIn('user_id', $userIds)->delete();
    }

    private function addUsers($alert, $userIds)
    {
        $newUserIds = $this->checkIfUserWasInvited($alert, $userIds);

        foreach ($newUserIds as $userId) {
            $this->model->create([
                'user_id' => $userId,
                'alert_id' => $alert->id,
                'invited_at' => now()
            ]);
        }

        if (count($newUserIds) > 0) {
            $this->sendEmailInvite($alert, $newUserIds);
        }
    }
}
