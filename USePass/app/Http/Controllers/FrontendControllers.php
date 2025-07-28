<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class FrontendControllers extends Controller
{
    public function index()
    {
        if (auth()->check()) {
            if (auth()->user()->isAdmin()) {
                return Inertia::render('Frontend/secDashboard');
            }

            if (auth()->user()->isGuard()) {
                return Inertia::render('Frontend/Ghome');
            }
        }

        return redirect()->route('login');
    }


    public function user()
    {
        return Inertia::render('Frontend/EUserId');
    }

    public function otp()
    {
        return Inertia::render('Frontend/Eotp');
    }

    public function scan()
    {
        return Inertia::render('Frontend/Gscan');
    }

    public function ghome()
    {
        return Inertia::render('Frontend/Ghome');
    }

    public function gin()
    {
        return Inertia::render('Frontend/Gin');
    }

    public function gout()
    {
        return Inertia::render('Frontend/Gout');
    }


    public function glog()
    {
        return Inertia::render('Frontend/Glogs');
    }

    public function deets()
    {
        return Inertia::render('Frontend/Edetails');
    }

    public function dashboard()
    {
        return Inertia::render('Frontend/secDashboard');
    }

    public function guard()
    {
        return Inertia::render('Frontend/Guard');
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
    public function incident()
    {
        return Inertia::render('Frontend/secIncident');
    }
}
