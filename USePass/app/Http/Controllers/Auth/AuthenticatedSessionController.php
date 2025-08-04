<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\ActivityLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();

        // Redirect based on role
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'guard') {
            return redirect()->route('guardHome');
        }

        $this->logActivity(
            $request->user()->id ?? null, // Assuming you have authenticated user, use null if not
            $request->user()->role ?? 'System', // Get user role or default to 'System'
            'Account Login',
            "{$user->first_name} {$user->last_name} Logged In"
        );

        // fallback redirect if role is unknown
        return redirect('/');
    }

    private function logActivity($userId, $role, $action, $description)
    {
        try {
            ActivityLog::create([
                'user_id' => $userId,
                'role' => $role,
                'log_action' => $action,
                'log_description' => $description,
            ]);
        } catch (\Exception $e) {
            // Log to Laravel's default log if activity logging fails
            \Log::error('Failed to create activity log: ' . $e->getMessage());
        }
    }
    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
