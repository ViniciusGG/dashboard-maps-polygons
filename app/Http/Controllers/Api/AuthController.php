<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\RecoverPasswordApiRequest;
use App\Http\Requests\Api\ResetPasswordApiRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Filter;
use App\Models\Indicator;
use App\Models\LicenseFilter;
use App\Models\LicenseIndicator;
use App\Models\LicensePermissions;
use App\Models\LicenseService;
use App\Models\Permission;
use App\Models\Services;
use App\Repositories\WorkspaceRepository;
use App\Services\Api\AuthApiService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log as FacadesLog;

/**
 * @group Auth
 * APIs for managing auth
 * @package App\Http\Controllers\Api

 */
class AuthController extends Controller
{

    /**
     * Login
     * @bodyParam email string required The email of the user. Example: admin@buzzvel.com
     * @bodyParam password string required The password of the user. Example: password
     */
    public function login(LoginRequest $request)
    {
        $request->authenticate();
        $user = auth()->user();
        $workspace = $user->workspaces()->with('license')->first();
        if ($user->hasRole('super_admin')) {
            $permissionSuperAdmin = Permission::all()->pluck('name');
            $responseData = [
                'user' => $user,
                'token' => $user->createToken('authToken')->plainTextToken,
                'roles' => 'super_admin',
                'permissions' => $permissionSuperAdmin,
                'workspaces' => ['*'],
                'support' => ['*'],
            ];
            return $this->apiResponse->successResponse(__('auth')['login'], $responseData);
        }

        if ($workspace) {
            $total_alerts_managers_ids = isset($workspace->alerts_managers_ids) ? count($workspace->alerts_managers_ids) : 0;
            $workspace->total_alerts_managers_ids = $total_alerts_managers_ids;
            $workspace['total_users'] = $workspace->users()->count() ?? 0;
            $support = $workspace->support()->first();
            $role = $user->workspaceMembers()->where('workspace_id', $workspace->id)->first()->role()->first();
            if ($role) {
                $licensePermissions = LicensePermissions::where('license_id', $workspace->license_id)
                    ->where('role_id', $role->id)
                    ->pluck('permission_id')
                    ->toArray();
                $permissions = Permission::whereIn('id', $licensePermissions)->pluck('name');
                //filters
                $licenseFilters = LicenseFilter::where('license_id', $workspace->license_id)->pluck('filter_id')->toArray();
                $filters = Filter::whereIn('id', $licenseFilters)->pluck('name')->toArray();
                $permissions = $permissions->merge($filters);
            }
            //indicators
            $licenseIndicators = LicenseIndicator::where('license_id', $workspace->license_id)
                ->pluck('indicator_id')
                ->toArray();
            $indicators = Indicator::whereIn('id', $licenseIndicators)->pluck('name')->toArray();
            $permissions = $permissions->merge($indicators);
            // //services
            $licenseServices = LicenseService::where('license_id', $workspace->license_id)->pluck('service_id')->toArray();
            $services = Services::whereIn('id', $licenseServices)->pluck('name')->toArray();
            $permissions = $permissions->merge($services);
        }

        $responseData = [
            'user' => $user,
            'token' => $user->createToken('authToken')->plainTextToken,
            'roles' => $role->name ?? null,
        ];
        if(isset($workspace->region_map_area)){
            $workspace->region_map_area = json_decode($workspace->region_map_area);
            $center = (new WorkspaceRepository)->centerPolygon($workspace->region_map_area->geometry->coordinates);
            $workspace->lat_center = $center['lat'];
            $workspace->lng_center = $center['lng'];
        }
        $responseData['permissions'] = $permissions;
        $responseData['workspaces'] = $workspace;
        if ($support) {
            $support->total_unread_messages = $support->supportMessageRead()->count();
            $responseData['support'] = $support;
        }

        if ($workspace->status == 'pending' && $workspace->region_map_area == null) {
            $responseData['error'] = 160;
        }
        if ($workspace->status == 'approved' && $workspace->region_map_area == null) {
            $responseData['error'] = 161;
        }


        return $this->apiResponse->successResponse(__('auth')['login'], $responseData);
    }


    /**
     * Logout
     * @authenticated
     */
    public function logout()
    {
        try {
            auth()->user()->tokens()->delete();
            return $this->apiResponse->successResponse(__('auth.logout'), []);
        } catch (Exception $e) {
            return $this->apiResponse->errorResponse(__('auth')['logout_error'], 400);
        }
    }


    /**
     * Forget password
     * @bodyParam email string required The email of the user. Example: admin@buzzvel.com
     */
    public function forgetPassword(RecoverPasswordApiRequest $request, AuthApiService $service)
    {
        $data = $request->validated();
        $message = $service->forgetPassword($data['email']);

        return $this->apiResponse->successResponse($message, []);
    }

    /**
     *  Reset password
     * @bodyParam email string required The email of the user. Example: admin@buzzvel.com
     * @bodyParam password string required The password of the user. Example: password
     * @bodyParam password_confirmation string required The password of the user. Example: password
     * @bodyParam token string required The token of the user. Example: 123456
     */
    public function resetPassword(ResetPasswordApiRequest $request, AuthApiService $service)
    {
        $validation = $request->validated();
        $message = $service->resetPassword($validation);

        return $this->apiResponse->successResponse($message, []);
    }


    /**
     * Refresh token.
     */
    public function refreshToken()
    {
        $user = Auth::user();
        $user->tokens()->delete();
        $token = $user->createToken('authToken')->plainTextToken;

        return $this->apiResponse->successResponse(__('auth')['refresh_token'], [
            'token' => $token
        ]);
    }
}
