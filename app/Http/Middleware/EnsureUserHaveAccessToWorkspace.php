<?php

namespace App\Http\Middleware;

use App\Repositories\UserRepository;
use App\Repositories\WorkspaceRepository;
use App\Services\Api\ApiResponseService;
use Closure;
use Illuminate\Http\Request;

class EnsureUserHaveAccessToWorkspace
{
    /**
     * Handle an incoming request.
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $roleType = "")
    {
        $apiResponse = new ApiResponseService();

        if ($request->user()->isSuperAdmin()) {
            return $next($request);
        }

        $workspaceUuid = $request->route()?->parameter('workspace');
        if (empty($workspaceUuid) || $workspaceUuid === "undefined")
            return $apiResponse->errorResponse(__('workspace.no_workspace_id'), 140);
            
        $workspaceRepo = new WorkspaceRepository();     
        $workspace = $workspaceRepo->haveAccessToWorkspace($workspaceUuid)->first();
        if (empty($workspace)) {
            return $apiResponse->errorResponse(__('workspace.no_access_to_workspace'), 150);
        }
        if ($workspace->status == 'pending' && $workspace->region_map_area == null) {
            return $apiResponse->errorResponse(__('workspace.workspace_not_approved'), 160);
        }

        return $next($request);
    }
}
