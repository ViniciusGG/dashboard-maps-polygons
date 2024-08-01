<?php

use App\Http\Controllers\Api\AlertController;
use App\Http\Controllers\Api\AlertMessageController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FilterController;
use App\Http\Controllers\Api\IndicatorHistoryController;
use App\Http\Controllers\Api\LicenseController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\ReportMessagesController;
use App\Http\Controllers\Api\StatusController;
use App\Http\Controllers\Api\SupportMessageController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\WorkspaceAreaPointController;
use App\Http\Controllers\Api\WorkspaceController;
use App\Http\Controllers\Api\WorkspaceMemberController;
use App\Http\Controllers\Api\WorkspaceVideoController;
use App\Http\Controllers\Internal\InternalIndicatorsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['prefix' => 'auth'], function () {


    //auth
    // Route::post('/register', [UserController::class, 'store']);
    // Route::post('/refresh-token', [AuthController::class, 'refreshToken']);
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/forget-password', [AuthController::class, 'forgetPassword'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);
});



Route::middleware(['auth:api'])->group(function () {

    Route::apiResource('reports', ReportController::class);

    Route::get('permissions', [LicenseController::class, 'permissions']);

    Route::apiResource('license', LicenseController::class);

    Route::post('/password/expired/', [UserController::class, 'passwordExpired'])->name('password.expired');

    Route::get('filters', [FilterController::class, 'index']);

    Route::middleware('secure-password')->group(function () {

        Route::group(['prefix' => 'auth'], function () {
            Route::post('/logout', [AuthController::class, 'logout']);
        });
        Route::apiResource('users', UserController::class);
        Route::post('checkEmail', [UserController::class, 'checkEmail']);
        Route::apiResource('workspace', WorkspaceController::class)->middleware(['workspace.verification']);
        Route::get('status', [StatusController::class, 'index']);

        Route::group(['prefix' => 'workspace/{workspace}'], function () {
            Route::put('info', [WorkspaceController::class, 'updateInfoWorkspace']);
            Route::get('indicator-history', [IndicatorHistoryController::class, 'index']);
            Route::post('status', [WorkspaceController::class, 'status']);
            Route::apiResource('alerts', AlertController::class);
            Route::put('alert/{alerts}/users-manager', [AlertController::class, 'updateAlertManager']);
            Route::put('alert/{alerts}/users-observers', [AlertController::class, 'updateObservers']);
            Route::get('alerts/{alerts}/{type}/users', [AlertController::class, 'getMembersByAlert']);
            Route::put('alerts/{alert}/status', [AlertController::class, 'updateStatus']);
            Route::apiResource('alerts/{alert}/messages', AlertMessageController::class);
            Route::apiResource('members', WorkspaceMemberController::class);
            Route::get('manager-admin', [WorkspaceController::class, 'getManagersAdminByWorkspace']);
            Route::put('manager-admin', [WorkspaceController::class, 'updateManagersAdminByWorkspace']);
            Route::put('dangerous-alerts', [WorkspaceController::class, 'updateDangerousAlertsManagers']);


            Route::apiResource('support/{support}/messages', SupportMessageController::class)->names('support.messages');
            Route::apiResource('area-points', WorkspaceAreaPointController::class);
            Route::apiResource('videos', WorkspaceVideoController::class)->names('workspace.videos');

            Route::get('alerts/{alert}/report-alert', [ReportMessagesController::class, 'reportMessagesAlert']);
        })->middleware(['workspace.verification']);
    });
});

