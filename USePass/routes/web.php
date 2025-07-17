<?php
use App\Http\Controllers\Auth\CustomForgotPasswordController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\FrontendControllers;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Tighten\Ziggy\Ziggy;
use Inertia\Inertia;



Route::get('/user', [App\Http\Controllers\FrontendControllers::class, 'user'])->name('user');
Route::get('/otp', [App\Http\Controllers\FrontendControllers::class, 'otp'])->name('otp');
Route::get('/scan', [App\Http\Controllers\FrontendControllers::class, 'scan'])->name('scan');


Route::get('/Details', [App\Http\Controllers\FrontendControllers::class, 'deets'])->name('deets');

// Admin Dashboard
Route::middleware(['auth', 'can:isAdmin'])->group(function () {
//    Route::get('/', [FrontendControllers::class, 'index'])->name('home');
    Route::get('/dashboard', [FrontendControllers::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/guard', [FrontendControllers::class, 'guard'])->name('guard');
    Route::get('/students', [FrontendControllers::class, 'students'])->name('students');
    Route::get('/statistics', [FrontendControllers::class, 'statistics'])->name('statistics');
    Route::get('/reports', [FrontendControllers::class, 'reports'])->name('reports');
    Route::get('/logs', [FrontendControllers::class, 'logs'])->name('logs');
    Route::get('/incident', [FrontendControllers::class, 'incident'])->name('incident');
});

// User Guard Dashboard
Route::middleware(['auth', 'can:isGuard'])->group(function () {
    Route::get('/', [FrontendControllers::class, 'ghome'])->name('guard.ghome');
    Route::get('/scan', [FrontendControllers::class, 'scan'])->name('scan');
    Route::get('/glog', [FrontendControllers::class, 'glog'])->name('glog');
});
//Route::get('/', function () {
//    return Inertia::render('Welcome', [
//        'canLogin' => Route::has('login'),
//        'canRegister' => Route::has('register'),
//        'laravelVersion' => Application::VERSION,
//        'phpVersion' => PHP_VERSION,
//    ]);
//});

Route::get('/incident-report/print', function () {
    return Inertia::render('Frontend/IncidentReportTemplate', [
        'report' => [
            'name' => request('name'),
            'date' => request('date'),
            'what' => request('what'),
            'who' => request('who'),
            'where' => request('where'),
            'when' => request('when'),
            'how' => request('how'),
            'why' => request('why'),
            'recommendation' => request('recommendation'),
        ],
    ]);
});
Route::get('/spot-report/print', function () {
    return Inertia::render('Frontend/SpotReportTemplate', [
        'report' => [
            'guardName' => request('guardName'),
            'date' => request('date'),
            'findings' => request('findings'),
            'time' => request('time'),
            'location' => request('location'),
            'actionTaken' => request('actionTaken'),
            'teamLeader' => request('teamLeader'),
            'departmentRepresentative' => request('departmentRepresentative'),
        ],
    ]);
});
Route::post('/students/import', [StudentController::class, 'import']);
Route::post('/students', [StudentController::class, 'store'])->name('students.store');
//Route::get('/dashboard', function () {
//    return Inertia::render('Dashboard');
//})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::post('/otp/request', [CustomForgotPasswordController::class, 'sendOtp'])->name('otp.request');
Route::get('/otp/verify', [CustomForgotPasswordController::class, 'showOtpForm'])->name('otp.form');
Route::post('/otp/verify', [CustomForgotPasswordController::class, 'verifyOtp'])->name('otp.verify');
Route::post('/reset-password', [CustomForgotPasswordController::class, 'resetPassword'])->name('password.store');

//Route::get('/usepass-otp', function () {
//    return Inertia::render('Frontend/userOTP');
//});

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/');
})->name('logout');

Route::get('/js-routes', function () {
    return response()->json(new Ziggy);
});

require __DIR__.'/auth.php';
