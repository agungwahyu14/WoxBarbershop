<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class EnhancedRememberMe
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Log remember token usage for security monitoring
        if ($request->hasCookie('remember_web_' . sha1(config('app.key')))) {
            $user = Auth::user();
            
            if ($user) {
                Log::channel('user_activity')->info('User authenticated via remember token', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'last_login' => $user->last_login_at
                ]);
                
                // Update last activity if user was remembered
                if (!$request->session()->has('auth.password_confirmed_at')) {
                    $user->updateLastLogin();
                }
            }
        }

        return $next($request);
    }
}
