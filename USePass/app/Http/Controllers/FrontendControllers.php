<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class FrontendControllers extends Controller
{
    public function index()
    {
        return Inertia::render('Frontend/Home');
//        Route::get('/', function () {
//    return Inertia::render('Welcome', [
//        'canLogin' => Route::has('login'),
//        'canRegister' => Route::has('register'),
//        'laravelVersion' => Application::VERSION,
//        'phpVersion' => PHP_VERSION,
//    ]);
//});
    }


    public function dashboard()
    {
        return Inertia::render('Frontend/secDashboard');
    }

    public function guard()
    {
        return Inertia::render('Frontend/Guard');
//        Route::get('/', function () {
//    return Inertia::render('Welcome', [
//        'canLogin' => Route::has('login'),
//        'canRegister' => Route::has('register'),
//        'laravelVersion' => Application::VERSION,
//        'phpVersion' => PHP_VERSION,
//    ]);
//});
    }

    public function students()
    {
        return Inertia::render('Frontend/secStudents');
    }

    public function statistics()
    {
        return Inertia::render('Frontend/secStatistics');
    }

    public function reports()
    {
        return Inertia::render('Frontend/secReports');
    }

    public function logs()
    {
        return Inertia::render('Frontend/secLogs');
    }

}
