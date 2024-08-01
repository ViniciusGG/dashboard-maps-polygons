<?php

use App\Jobs\NotificationNewMessage;
use App\Jobs\SendEmailReport;
use Illuminate\Support\Facades\Route;
use Spatie\Health\Http\Controllers\HealthCheckResultsController;
use Spatie\Health\Http\Controllers\SimpleHealthCheckController;

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
Route::get('health-check', SimpleHealthCheckController::class)->name('health-check');
Route::get('health-dashboard', HealthCheckResultsController::class)->name('health-dashboard');

if (env('APP_ENV') !== 'production') {


    // Route::get('/', function () {
    //     return view('welcome');
    // });

    // Route::get('/carrousel', function () {
    //     return view('carrousel');
    // });

    // Route::get('/forge', function () {
    //     $user = App\Models\User::find(5);
    //     return new App\Mail\UserNewMessage($user, 1, 1, 'alert');
    // });

    // Route::get('/pass', function () {
    //     $user = App\Models\User::find(5);
    //     return new App\Mail\UserForgerPassword($user, '24');
    // });

    // Route::get('/new', function () {
    //     $user = App\Models\User::find(5);
    //     return new App\Mail\UserCreated($user, 'L6xOSnsEMs&Vss');
    // });


    // Route::get('/file', function () {
    //     $user = App\Models\User::find(5);
    //     $alert = App\Models\Alert::find(1);
    //     return new App\Mail\ReportMessagesAlert($user, $alert, null, null);
    // });

    // Route::get('/report', function () {
    //     $user = App\Models\User::find(5);
    //     $report = App\Models\Report::find(1);
    //     return new App\Mail\EmailReport($user, $report);
    // });

    // Route::get('/mail-invite', function () {
    //     $user = App\Models\User::find(1);
    //     $alert = App\Models\Alert::find(1);
    //     // NotificationNewMessage::dispatch($user,$user->workspaces->first());
    //     // SendEmailReport::dispatch($user, $report);
    //     return new App\Mail\ReportMessagesAlert($user, $alert);
    // });

    // Route::get('pdf', function () {
    //     $reportMessagesRepository = new ReportMessagesRepository();
    //     $messages = $reportMessagesRepository->getAllMessagesByWorkspace(15);
    //     $isShowMessages = true;
    //     $pdf = Pdf::loadView('pdf.report', ['messages' => $messages, 'isShowMessages' => $isShowMessages]);
    //     $pdf->setPaper('A4', 'portrait');

    //     $pdfFilePath = storage_path('app/public/report-alert.pdf');
    //     $pdf->save($pdfFilePath);
    //     return $pdf->stream();
    // });
}
