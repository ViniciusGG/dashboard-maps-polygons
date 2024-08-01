<?php

namespace App\Http\Controllers;

use App\Models\Alert;
use App\Models\LicensePermissions;
use App\Models\Permission;
use App\Models\Workspace;
use Illuminate\Routing\Controller as BaseController;
use App\Services\Api\ApiResponseService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;

class Controller extends BaseController
{

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected ApiResponseService $apiResponse;
    public $workspaceId;
    public $workspace;
    protected bool $filters = false;
    protected array $queryParams = [];
    protected array $keysAvailable = ['search', 'sortBy', 'sortDirection', 'page', 'limit', 'take', 'offset', 'relationshipRules', 'active', 'year', 'month', 'day', 'start_date', 'end_date', 'trashed', 'where', 'what', 'gt', 'lt'];


    public function __construct(Request $request)
    {
        $this->apiResponse = new ApiResponseService();

        if (isset($request->workspace)) {
            $this->workspace = Workspace::where('uuid', $request->workspace)->firstOrFail();
            $this->workspaceId = $this->workspace->id;
        }
    }

    protected function enableFilters(): void
    {
        $this->filters = true;
    }

    protected function enableQueryParams($queryParams): void
    {
        $this->queryParams = $queryParams;
    }

    protected function getFilters(Request $request)
    {
        if ($this->filters) {
            return $request->only($this->keysAvailable);
        }
    }

    protected function getQueryParams(Request $request)
    {
        if ($this->queryParams) {
            return $request->only($this->queryParams);
        }
    }

    public function checkPermission($permissionName)
    {
        try {
            $user = auth()->user();
            if ($user->hasRole('super_admin')) {
                return true;
            }
            $permission = Permission::select('id')->where('name', $permissionName)->firstOrFail();

            $workspace = $user->workspaces()->firstOrFail();

            $role = $workspace->users()->where('user_id', $user->id)->firstOrFail();


            LicensePermissions::where('permission_id', $permission->id)
                ->where('license_id', $workspace->license_id)
                ->where('role_id', $role->pivot->role_id)
                ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            abort(response()->json([
                'status' => 'error',
                'message' => __('Unauthorized')
            ], 403));
        }
    }

    public function checkPermissionUpdateObservers($alertUuid, $workspaceUuid)
    {
        $user = auth()->user();
        $alert = Alert::where('uuid', $alertUuid)->first() ?? false;
        $workspace = Workspace::where('uuid', $workspaceUuid)->first() ?? false;
        $isAdmin = $workspace->admins()->where('uuid', $user->uuid)->first() ?? false;
        if ($user->hasRole('super_admin'))
            return true;

        if ($alert->alert_manager_id == $user->id)
            return true;

        if ($isAdmin)
            return true;

        return false;
    }

    public function checkPermissionUpdateManager($workspaceUuid)
    {
        $user = auth()->user();
        $workspace = Workspace::where('uuid', $workspaceUuid)->first() ?? false;
        $isAdmin = $workspace->admins()->where('uuid', $user->uuid)->first() ?? false;
        if ($user->hasRole('super_admin'))
            return true;

        if ($isAdmin)
            return true;

        return false;
    }

    public function checkPermissionUpdateWorkspace($workspaceId)
    {
        $user = auth()->user();
        $workspaceMember = auth()->user()->workspaceMembers()->where('workspace_id', $workspaceId)->first();
        $role = ($workspaceMember) ? $workspaceMember->role()->first() : false;
        if ($user->hasRole('super_admin'))
            return true;

        $workspace = Workspace::where('id', $workspaceId)->first();
        if ($workspace->status == 'pending' && $role->name != 'super_admin')
            return false;

        return true;
    }
}
