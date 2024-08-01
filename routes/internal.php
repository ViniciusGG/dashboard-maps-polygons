<?php

use App\Http\Controllers\Api\WorkspaceController;
use App\Http\Controllers\Internal\InternalAlertsController;
use App\Http\Controllers\Internal\InternalIndicatorsGroupController;
use App\Http\Controllers\Internal\InternalSatelliteController;
use App\Http\Controllers\Internal\InternalIndicatorsController;
use App\Http\Controllers\Internal\MunicipalitiesCodeController;
use App\Jobs\NotificationNewMessage;
use App\Jobs\SendEmailReport;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::resource('alerts', InternalAlertsController::class)->only(['store','destroy','index'])->names('internal.alerts');

Route::resource('indicators', InternalIndicatorsController::class)->only(['index', 'store','update'])->names('internal.indicators');
Route::resource('satellites', InternalSatelliteController::class)->only(['index', 'store','update'])->names('internal.satellites');
Route::get('indicators-groups', [InternalIndicatorsGroupController::class, 'index'])->name('internal.indicators-groups.index');
Route::get('municipalities', [MunicipalitiesCodeController::class, 'index'])->name('internal.municipalities.index');

