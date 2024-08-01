<?php

namespace App\Repositories;

use App\Models\Alert;
use App\Models\License;
use App\Models\User;
use App\Models\Workspace;
use App\Models\WorkspaceMember;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class WorkspaceMemberRepository extends BaseRepository
{

    /**
     * UserRepository constructor.
     */
    public function __construct()
    {
        parent::__construct(WorkspaceMember::class);
    }

    public function getMembersByRole($filters, $workspaceId)
    {
        $query = $this->model->newQuery();
        $columns = $filters['columns'] ?? ['*'];
        $take = $filters['take'] ?? $this->take;
        $search = $filters['search'] ?? false;

        $query->with('user');

        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->orWhereHas('user', function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%');
                });
            });
        }

        $query->where('workspace_id', $workspaceId);
        $query->join('roles', 'roles.id', '=', 'workspace_members.role_id');
        $query->where('roles.name', '!=', 'super_admin');

        $members = $query
            ->select('workspace_members.*', 'roles.name as role_name')
            ->orderByRaw("
            CASE
                WHEN roles.name = 'admin' THEN 1
                WHEN roles.name = 'technicians' THEN 2
                WHEN roles.name = 'external_service_provider' THEN 3
                ELSE 4
            END
        ")
            ->paginate($take, $columns);
        $groupedMembers = $members->groupBy('role_name');
        $page = $members->toArray();
        $page['data'] = $groupedMembers;

        return $page;
    }

    public function getMembersByWorkspace($workspaceId, $alertUuid, $type, $search = null)
    {
        $alert = Alert::where('uuid', $alertUuid)->firstOrFail();
        $alertId = $alert->id;
        $managerId = $alert->alert_manager_id;
        $superAdminId = $this->getSuperAdminRoleId();

        $query = $this->getModelQuery($workspaceId, $superAdminId, $search);
        if($type == 'observer') {
            $userUuids = $this->getObserversUuids($alertId);
            $query->whereNot('user_id', $managerId);
        }

        if($type == 'manager'){
            $userUuids = [$this->getManagerUuid($managerId)];
        }

        return $query->get()
            ->map(function ($item) use ($userUuids) {
                $user = $item->user;
                $item->selected = collect($userUuids)->contains($user->uuid);
                return ['user' => $user, 'selected' => $item->selected];
            })
            ->sortByDesc('selected')
            ->values();

    }

    private function getSuperAdminRoleId()
    {
        return Role::where('name', 'super_admin')->firstOrFail()->id;
    }

    private function getObserversUuids($alertId)
    {
        $userIds = Alert::where('id', $alertId)
            ->select('observers_ids')
            ->get()
            ->pluck('observers_ids')
            ->flatMap(function ($observersId) {
                return json_decode($observersId, true) ?? [];
            });

        $userUuids = [];
        foreach ($userIds as $userId) {
            $user = User::where('id', $userId)->firstOrFail();
            $userUuids[] = $user->uuid;
        }
        return $userUuids;
    }

    private function getManagerUuid($userId)
    {
        $user = User::where('id', $userId)->first();
        if (!$user) {
            return null;
        }
        return $user->uuid;
    }

    private function getModelQuery($workspaceId, $superAdminId, $search)
    {
        return $this->model
            ->where('workspace_id', $workspaceId)
            ->where('role_id', '!=', $superAdminId)
            ->with(['user' => function ($query) {
                $query->select('id', 'uuid', 'name');
            }])
            ->when($search, function ($query) use ($search) {
                $query->whereHas('user', function ($subquery) use ($search) {
                    $subquery->where('name', 'like', '%' . $search . '%');
                });
            });
    }

    public function addMemberToWorkspace($workspaceId, $dataValidated)
    {
        $email = config('app.key') . $dataValidated['member_email'];
        $emailDecrypted = base64_encode($email);
        $user = User::where('email', $emailDecrypted)->first();

        if (!$user) {
            $dataUser['name'] = $dataValidated['member_name'];
            $dataUser['email'] = $dataValidated['member_email'];
            $dataUser['phone'] = $dataValidated['member_phone'] ?? null;
            $user = (new UserRepository)->createUser($dataUser);
        }

        $userWorkspace = $this->verifyIfUserIsAlreadyOnWorkspace($user->id, $workspaceId);

        if ($userWorkspace) {
            return ['status' => false, 'message' => __('workspace.members.already_on_workspace')];
        }

        $workspace = Workspace::where('id', $workspaceId)->firstOrFail();
        $license = License::where('id', $workspace->license_id)->firstOrFail();

        if ($license->members <= $workspace->users()->count()) {
            return ['status' => false, 'message' => __('workspace.members.max_members')];
        }

        $workspaceMember = $this->model->create([
            'workspace_id' => $workspaceId,
            'user_id' => $user->id,
            'role_id' => Role::where('name', $dataValidated['role'])->first()->id,
        ]);

        return ['status' => true, 'message' => __('workspace.members.created'), 'data' => $workspaceMember->load('user', 'role')];
    }

    public function updateRole($userUuid, $workspaceId, $roleType)
    {
        $userRepository = new UserRepository();
        $user = $userRepository->getByUuid($userUuid);
        $role = Role::where('name', $roleType)->firstOrFail();
        $workspaceMember = $this->model->where(['workspace_id' => $workspaceId, 'user_id' => $user->id])->firstOrFail();
        $workspaceMember->update(['role_id' => $role->id]);
        return $workspaceMember->with('user', 'role')->first();
    }

    public function getMemberWorkspaceWithUuid($workspaceId, $userId)
    {
        return $this->model->where('user_id', $userId)->where('workspace_id', $workspaceId)->first();
    }


    public function deleteMemberFromWorkspace($workspaceId, $userUuid)
    {
        $userRepository = new UserRepository();
        $user = $userRepository->getByUuid($userUuid);
        $workspaceMember = $this->model->where(['workspace_id' => $workspaceId, 'user_id' => $user->id])->firstOrFail();
        $workspaceMember->delete();
        return $workspaceMember;
    }

    public function verifyIfUserIsAlreadyOnWorkspace($userId, int $workspaceId)
    {
        return $this->model->where('user_id', $userId)->where('workspace_id', $workspaceId)->first();
    }

    public function deleteFromWorkspace($workspaceId)
    {
        $this->model->where(['workspace_id' => $workspaceId])->delete();
    }
}
