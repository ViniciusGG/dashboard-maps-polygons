<?php

namespace App\Repositories;

use App\Jobs\NotificationNewMessage;
use App\Models\Support;
use App\Models\SupportMessage;
use App\Models\UserSupportMessageRead;

class SupportMessageRepository extends BaseRepository
{

    public function __construct()
    {
        parent::__construct(SupportMessage::class);
    }

    public function supportMessageFilter($filters, $workspaceId, $supportUuid)
    {
        $support = Support::where('uuid', $supportUuid)->firstOrFail();

        $queryRead = $this->model->newQuery();
        $columns = $filters['columns'] ?? ['*'];
        $take = $filters['take'] ?? $this->take;
        $sortBy = $filters['sortBy'] ?? 'created_at';
        $sortDirection = $filters['sortDirection'] ?? 'DESC';
        $queryRead->with('attachments', 'user');

        $queryRead->where('workspace_id', $workspaceId);
        $queryRead->where('support_id', $support->id);

        $queryRead->whereHas('userSupportMessageRead', function ($query) {
            $query->where('is_read', 1);
        });  

        $queryRead->orderBy($sortBy, $sortDirection);

        $readMessages = $queryRead->paginate($take, $columns);

        $queryUnread = $this->model->newQuery();
        $queryUnread->with('attachments', 'user');
        $queryUnread->where('workspace_id', $workspaceId);
        $queryUnread->where('support_id', $support->id);
        $queryUnread->whereHas('userSupportMessageRead', function ($query) {
            $query->where('is_read', 0);
            $query->where('user_id', auth()->user()->id);
        });
        $unreadMessages = $queryUnread->get();

        $originalUnreadMessages = $unreadMessages->map(function ($unreadMessage) {
            return $unreadMessage->replicate();
        });
        
        UserSupportMessageRead::where('user_id', auth()->user()->id)
            ->whereIn('support_message_id', $unreadMessages->pluck('id'))
            ->update(['is_read' => 1]);

        return [
            'read' => $readMessages->toArray(),
            'unread' => ['data' => $originalUnreadMessages->toArray()],
        ];
    }

    public function showSupportMessage($uuid)
    {
        $supportMessage = $this->model->where('uuid', $uuid)->firstOrFail();
        return $supportMessage->load('attachments', 'user')->toArray();
    }


    public function createSupportMessage($dataValidated, $supportUuid)
    {
        $support = Support::where('uuid', $supportUuid)->firstOrFail();
        $lastNotificationSent = SupportMessage::where('support_id', $support->id)
        ->where('workspace_id', $dataValidated['workspace_id'])
        ->max('created_at');
        $dataMessage['support_id'] = $support->id;
        $dataMessage['user_id'] = auth()->user()->id;
        $dataMessage['workspace_id'] = $dataValidated['workspace_id'];
        $dataMessage['message'] = $dataValidated['message'];
        $supportMessage = $this->model->create($dataMessage);

        if (isset($dataValidated['attachments'])) {
            foreach ($dataValidated['attachments'] as $attachment) {
                $storedAttachment = $attachment->store('support', 'public');
                $path = 'storage/' . $storedAttachment;
                $supportMessage->attachments()->create([
                    'file_name' => url($path),
                    'workspace_id' => $dataValidated['workspace_id'],
                ]);
            }
        }
        NotificationNewMessage::dispatch($support->workspace->id, $support->id, $lastNotificationSent, 'support');
        return $supportMessage->load('attachments', 'user')->toArray();
      
    }

    public function updateSupportMessage($uuid, $dataValidated)
    {
        $supportMessage = $this->model->where('uuid', $uuid)->firstOrFail();
        $supportMessage->update($dataValidated);
        return $supportMessage->load('attachments', 'user')->toArray();
    }

    public function destroySupportMessage($uuid)
    {
        $supportMessage = $this->model->where('uuid', $uuid)->firstOrFail();
        $supportMessage->delete();
        return $supportMessage->load('attachments', 'user')->toArray();
    }

}
