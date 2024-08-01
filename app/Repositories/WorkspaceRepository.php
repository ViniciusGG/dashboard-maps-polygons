<?php

namespace App\Repositories;

use App\Models\License;
use App\Models\Workspace;
use App\Models\WorkspaceAreaPoint;
use App\Models\WorkspaceMember;
use Spatie\Permission\Models\Role;

class WorkspaceRepository extends BaseRepository
{

    /**
     * UserRepository constructor.
     */
    public function __construct()
    {
        parent::__construct(Workspace::class);
    }

    public function create($dataValidated)
    {
        $license = License::where('uuid', $dataValidated['license_id'])->firstOrFail();
        if ($license->members <= count($dataValidated['members'])) {
            abort(response()->json([
                'status' => false,
                'message' => __('workspace.members.max_members'),
            ], 404));
        }

        if($dataValidated['region_map_area']){
            $status = 'approved';
        }else{
            $status = 'pending';
        }

        $workspace = $this->model->create([
            'name' => $dataValidated['name'],
            'admin_email' => $dataValidated['admin_email'],
            'admin_name' => $dataValidated['admin_name'],
            'license_id' => $license->id,
            'status' => $status,
            'region_map_area' => $dataValidated['region_map_area'] ?? null,
            'code_azulfy' => $dataValidated['code_azulfy'] ?? null,
        ]);

        $dataUser['name'] = $dataValidated['admin_name'];
        $dataUser['email'] = $dataValidated['admin_email'];
        $dataUser['phone'] = $dataValidated['admin_phone'] ?? null;
        $user = (new UserRepository)->createUser($dataUser);

        WorkspaceMember::create([
            'workspace_id' => $workspace->id,
            'user_id' => $user->id,
            'role_id' => Role::where('name', 'admin')->first()->id,
        ]);

        //add members
        $members = $dataValidated['members'] ?? [];
        foreach ($members as $member) {
            $dataUser['name'] = $member['name'];
            $dataUser['email'] = $member['email'];
            $dataUser['phone'] = $member['phone'] ?? null;
            $user = (new UserRepository)->createUser($dataUser);
            WorkspaceMember::create([
                'workspace_id' => $workspace->id,
                'user_id' => $user->id,
                'role_id' => Role::where('name', $member['role'])->first()->id,
            ]);
        }

        //add area points
        $areaPoints = $dataValidated['region_map_area_points'] ?? [];
        foreach ($areaPoints as $areaPoint) {
            WorkspaceAreaPoint::create([
                'workspace_id' => $workspace->id,
                'name' => $areaPoint['name'],
                'region_map_area' => $areaPoint['json'],
            ]);
        }

        return $workspace;
    }

    public function getAllWorkspace($filters)
    {
        $query = $this->model->newQuery();
        $columns = $filters['columns'] ?? ['*'];
        $take = $filters['take'] ?? $this->take;
        $search = $filters['search'] ?? false;
        $sortBy = $filters['sortBy'] ?? 'created_at';
        $sortDirection = $filters['sortDirection'] ?? 'DESC';

        if ($search) {
            $query->where(function ($query) use ($search) {
                $searchFields = ($this->model->searchFields) ?? ['name'];
                foreach ($searchFields as $searchField) {
                    $query->orWhere($searchField, 'like', '%' . $search . '%');
                }
            });
        }
        $query->with('support');
        $query->with('areaPoints');
        $query->with('license');
        $query->withCount(['areaPoints as total_area_points']);
        $query->withCount(['supportMessageRead as total_unread_messages']);

        $query->orderBy($sortBy, $sortDirection);
        $workspaces = $query->paginate($take, $columns);

        return $workspaces;
    }

    public function getWorkspace($workspaceId)
    {
        $query = $this->model->newQuery();
        $query->where('id', $workspaceId);
        $query->withCount(['users as total_users']);
        $query->with('areaPoints');
        $query->withCount(['areaPoints as total_area_points']);
        $query->withCount(['supportMessageRead as total_unread_messages']);
        $workspace = $query->first();

        $total_alerts_managers_ids = isset($workspace->alerts_managers_ids) ? count($workspace->alerts_managers_ids) : 0;

        $workspace->total_alerts_managers_ids = $total_alerts_managers_ids;

        $users = $workspace->users()
            ->select('users.*', 'roles.name as role_name')
            ->join('roles', 'roles.id', '=', 'workspace_members.role_id')
            ->where('roles.name', '!=', 'super_admin')
            ->orderByRaw("
            CASE
                WHEN roles.name = 'admin' THEN 1
                WHEN roles.name = 'technicians' THEN 2
                WHEN roles.name = 'external_service_provider' THEN 3
                ELSE 4
            END
        ")
            ->get()
            ->groupBy('role_name')
            ->toArray();
        $workspace->users = $users;
        if ($workspace->region_map_area) {
            $workspace->region_map_area = json_decode($workspace->region_map_area);
            $center = $this->centerPolygon($workspace->region_map_area->geometry->coordinates);
            $workspace->lat_center = $center['lat'];
            $workspace->lng_center = $center['lng'];
        }
        if ($workspace->region_map_area_pending) {
            $workspace->region_map_area_pending = json_decode($workspace->region_map_area_pending);
        }

        $areaPoints = $workspace->areaPoints ?? [];
        foreach ($areaPoints as $areaPoint) {
            if ($areaPoint->region_map_area && is_string($areaPoint->region_map_area)) {
                $areaPoint->region_map_area = json_decode($areaPoint->region_map_area);
            }
            if ($areaPoint->region_map_area_pending && is_string($areaPoint->region_map_area_pending)) {
                $areaPoint->region_map_area_pending = json_decode($areaPoint->region_map_area_pending);
            }
        }


        return $workspace;
    }

    public function haveAccessToWorkspace($workspaceUuid)
    {
        $query = $this->model->newQuery();
        $query->where('uuid', $workspaceUuid);
        $query->whereHas('users', function ($query) {
            $query->where('user_id', auth()->user()->id);
        });
        return $query;
    }

    public function updateInfoWorkspace($workspaceId, $dataValidated)
    {
        $dataWorkspace = [];
        if (isset($dataValidated['name'])) {
            $dataWorkspace['name'] = $dataValidated['name'];
        }
        if (isset($dataValidated['code_azulfy'])) {
            $dataWorkspace['code_azulfy'] = $dataValidated['code_azulfy'];
        }
        if (isset($dataValidated['license_id'])) {
            $dataWorkspace['license_id'] = License::where('uuid', $dataValidated['license_id'])->first()->id;
        }
        $workspace = $this->model->find($workspaceId);
        $workspace->update($dataWorkspace);
        return $workspace;
    }

    public function updateWorkspace($workspaceId, $dataValidated)
    {
        $dataWorkspace = [];
        $workspace = $this->model->find($workspaceId);
        if (isset($dataValidated['name'])) {
            $dataWorkspace['name'] = $dataValidated['name'];
        }

        if (isset($dataValidated['admin_email'])) {
            $dataWorkspace['admin_email'] = $dataValidated['admin_email'];
        }

        if (isset($dataValidated['status'])) {
            $dataWorkspace['status'] = $dataValidated['status'];
        }

        if (isset($dataValidated['admin_name'])) {
            $dataWorkspace['admin_name'] = $dataValidated['admin_name'];
        }

        if (isset($dataValidated['code_azulfy'])) {
            $dataWorkspace['code_azulfy'] = $dataValidated['code_azulfy'];
        }
        if (isset($dataValidated['license_id'])) {
            $dataWorkspace['license_id'] = License::where('uuid', $dataValidated['license_id'])->first()->id;
        }
        if ($this->user->hasRole('super_admin')) {
            if (isset($dataValidated['region_map_area'])) {
                $dataWorkspace['region_map_area'] = $dataValidated['region_map_area'];
            }
        } else {
            $dataWorkspace['status'] = 'pending';
            if (isset($dataValidated['region_map_area'])) {
                $dataWorkspace['region_map_area_pending'] = $dataValidated['region_map_area'];
            }
        }


        $workspace->update($dataWorkspace);

        $members = $dataValidated['members'] ?? [];
        foreach ($members as $member) {
            $dataUser['name'] = $member['name'];
            $dataUser['email'] = $member['email'];
            $user = (new UserRepository)->createUser($dataUser);
            WorkspaceMember::create([
                'workspace_id' => $workspace->id,
                'user_id' => $user->id,
                'role_id' => Role::where('name', $member['role'])->first()->id,
            ]);
        }

        $areaPoints = $dataValidated['region_map_area_points'] ?? [];
        if (!empty($areaPoints)) {
            if ($this->user->hasRole('super_admin')) {
                WorkspaceAreaPoint::where('workspace_id', $workspace->id)->whereNotNull('region_map_area')->forceDelete();
            }

            foreach ($areaPoints as $areaPoint) {

                $dataWorkspaceAreaPoint = [
                    'workspace_id' => $workspace->id,
                    'name' => $areaPoint['name'],
                ];

                if ($this->user->hasRole('super_admin')) {
                    $dataWorkspaceAreaPoint['region_map_area'] = $areaPoint['json'];
                } else {
                    $dataWorkspaceAreaPoint['region_map_area_pending'] = $areaPoint['json'];
                }

                WorkspaceAreaPoint::create($dataWorkspaceAreaPoint);
            }
        }

        return $workspace;
    }

    public function updateStatusWorkspace($workspaceId, $data)
    {
        $workspace = $this->model->find($workspaceId);
        if ($data['status'] == 'approved') {
            if ($workspace->region_map_area_pending) {
                $workspace->region_map_area = $workspace->region_map_area_pending;
            }
            $areaPoints = WorkspaceAreaPoint::where('workspace_id', $workspace->id)->whereNotNull('region_map_area_pending')->get();
            if ($areaPoints->count() > 0) {
                WorkspaceAreaPoint::where('workspace_id', $workspace->id)->forceDelete();

                foreach ($areaPoints as $areaPoint) {
                    WorkspaceAreaPoint::create([
                        'workspace_id' => $workspace->id,
                        'name' => $areaPoint->name,
                        'region_map_area' => $areaPoint->region_map_area_pending,
                    ]);
                }
            }
        }
        if ($data['status'] == 'rejected') {
            $workspace->region_map_area_pending = null;
            $areaPoints = WorkspaceAreaPoint::where('workspace_id', $workspace->id)->whereNotNull('region_map_area_pending')->get();
            if ($areaPoints->count() > 0) {
                WorkspaceAreaPoint::where('workspace_id', $workspace->id)->whereNotNull('region_map_area_pending')->forceDelete();
            }
        }

        $workspace->region_map_area_pending = null;
        $workspace->update($data);

        return $workspace;
    }


    public function getManagersAdminByWorkspace($workspaceId)
    {
        $query = $this->model->newQuery();
        $query->where('id', $workspaceId);

        $workspace = $query->first();
        $admins = $workspace->admins();

        $managerIds = $workspace->alerts_managers_ids;
        $dangerousAlertsManagersIds = $workspace->dangerous_alerts_managers_ids;

        if (!is_array($managerIds)) {
            foreach ($admins as $admin) {
                $admin->is_manager = false;
            }
        } else {
            foreach ($admins as $admin) {
                if (in_array($admin->id, $managerIds)) {
                    $admin->is_manager = true;
                } else {
                    $admin->is_manager = false;
                }
            }
        }
        if (!is_array($dangerousAlertsManagersIds)) {
            foreach ($admins as $admin) {
                $admin->is_dangerous_alerts_manager = false;
            }
        } else {
            foreach ($admins as $admin) {
                if (in_array($admin->id, $dangerousAlertsManagersIds)) {
                    $admin->is_dangerous_alerts_manager = true;
                } else {
                    $admin->is_dangerous_alerts_manager = false;
                }
            }
        }

        return $admins;
    }


    public function updateManagersAdminByWorkspace($workspaceId, $alertsManagersUuids)
    {
        $workspace = $this->model->where('id', $workspaceId)->firstOrFail();
        $alertsManagersIds = [];
        foreach ($alertsManagersUuids as $alertsManagersUuid) {
            $alertsManagersIds[] = (new UserRepository)->getByUuid($alertsManagersUuid)->id;
        }
        $workspace->update(['alerts_managers_ids' => $alertsManagersIds]);
        return $workspace;
    }

    public function updateDangerousAlertsManagers($workspaceId, $dangerousAlertsManagersUuids)
    {
        $workspace = $this->model->where('id', $workspaceId)->firstOrFail();
        $dangerousAlertsManagersIds = [];
        foreach ($dangerousAlertsManagersUuids as $dangerousAlertsManagersUuid) {
            $dangerousAlertsManagersIds[] = (new UserRepository)->getByUuid($dangerousAlertsManagersUuid)->id;
        }
        $workspace->update(['dangerous_alerts_managers_ids' => $dangerousAlertsManagersIds]);
        return $workspace;
    }

    public function centerPolygon($coordinates)
    {
        $center = ['lat' => 0, 'lng' => 0];
        $count = count($coordinates[0]);
        foreach ($coordinates[0] as $point) {
            $center['lat'] += $point[1];
            $center['lng'] += $point[0];
        }
        $center['lat'] = $center['lat'] / $count;
        $center['lng'] = $center['lng'] / $count;
        return $center;
    }

}
