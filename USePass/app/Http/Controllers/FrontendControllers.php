<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class FrontendControllers extends Controller
{
    public function index(){
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

    public function user()
    {
        return Inertia::render('Frontend/EUserId');
    }
    public function dashboard()
    {
        return Inertia::render('Frontend/secDashboard');
    }

    public function otp()
    {
        return Inertia::render('Frontend/Eotp');
    }
    public function deets()
    {
        return Inertia::render('Frontend/Edetails');
    }

    public function guard()
    {
        return Inertia::render('Frontend/Guard');
    }
}
