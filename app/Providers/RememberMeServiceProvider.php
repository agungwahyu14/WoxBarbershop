<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Auth\SessionGuard;
use Illuminate\Support\Facades\Auth;

class RememberMeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Extend the SessionGuard to customize remember token duration
        Auth::extend('custom-session', function ($app, $name, array $config) {
            $provider = Auth::createUserProvider($config['provider']);
            $guard = new SessionGuard($name, $provider, $app['session.store']);

            // Set remember token duration to 30 days (43200 minutes)
            $guard->setCookieJar($this->app['cookie']);
            $guard->setDispatcher($this->app['events']);
            $guard->setRequest($this->app->refresh('request', $guard, 'setRequest'));

            return $guard;
        });
    }
}
