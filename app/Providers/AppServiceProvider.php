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
        // Remember Me configuration - Disabled for now, may be used in the future
        /*
        // Configure remember token lifetime (30 days in minutes)
        $rememberDuration = config('session.remember_me_lifetime', 43200);
        
        // Set the cookie lifetime for remember tokens
        Cookie::macro('rememberForever', function ($name, $value, $domain = null, $secure = null, $httpOnly = true, $sameSite = null) use ($rememberDuration) {
            return Cookie::make($name, $value, $rememberDuration, '/', $domain, $secure, $httpOnly, false, $sameSite);
        });
        */
    }
}
