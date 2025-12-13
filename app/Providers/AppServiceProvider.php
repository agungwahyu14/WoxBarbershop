<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\SessionGuard;
use Illuminate\Support\Facades\Cookie;

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
        // Force HTTP scheme in local/development environment
        // This prevents SSL errors when accessing via IP address for testing
        if (config('app.env') === 'local' || config('app.env') === 'development') {
            \URL::forceScheme('http');
        }

        // Remember Me configuration - Disabled for now, may be used in the future
        /*
        // Configure remember token lifetime (30 days in minutes)
        $rememberDuration = config('session.remember_me_lifetime', 43200);

        // Set the cookie lifetime for remember tokens
        Cookie::macro('rememberForever', function ($name, $value, $domain = null, $secure = null, $httpOnly = true, $sameSite = null) use ($rememberDuration) {
            return Cookie::make($name, $value, $rememberDuration, '/', $domain, $secure, $httpOnly, false, $sameSite);
        });
        */
        \URL::forceScheme('https');
    }
}
