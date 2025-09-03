<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CheckUserActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && !Auth::user()->is_active) {
            $user = Auth::user();
            
            Log::warning('Inactive user blocked', [
                'user_id' => $user->id,
                'email' => $user->email,
                'route' => $request->route()->getName(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);
            
            Auth::logout();
            
            return redirect()->route('login')->withErrors([
                'email' => 'Your account has been deactivated. Please contact administrator.'
            ])->with('error', 'Your account has been deactivated. Please contact administrator.');
        }

        return $next($request);
    }
}
