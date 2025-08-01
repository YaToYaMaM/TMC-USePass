<?php

namespace App\Providers;

use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;
use Tighten\Ziggy\Ziggy;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);

        Inertia::share([
            'auth' => [
                'user' => fn () => Auth::check()
                    ? Auth::user()->only(['id', 'name', 'email', 'role'])
                    : null,
            ],
            'ziggy' => fn () => [
                ...app(Ziggy::class)->toArray(),
                'location' => url()->current(),
            ],
        ]);
    }
}

