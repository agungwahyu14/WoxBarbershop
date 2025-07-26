<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class RateLimitMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $key = 'global', int $maxAttempts = 60, int $decayMinutes = 1): Response
    {
        $limiterKey = $this->resolveRequestSignature($request, $key);

        if (RateLimiter::tooManyAttempts($limiterKey, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($limiterKey);
            
            return response()->json([
                'message' => 'Too many requests. Please try again in ' . $seconds . ' seconds.',
                'retry_after' => $seconds
            ], 429);
        }

        RateLimiter::hit($limiterKey, $decayMinutes * 60);

        $response = $next($request);

        $response->headers->set('X-RateLimit-Limit', $maxAttempts);
        $response->headers->set('X-RateLimit-Remaining', RateLimiter::remaining($limiterKey, $maxAttempts));

        return $response;
    }

    /**
     * Resolve the rate limiter key for the request
     */
    protected function resolveRequestSignature(Request $request, string $key): string
    {
        $user = $request->user();
        
        return match($key) {
            'booking' => 'booking:' . ($user?->id ?? $request->ip()),
            'login' => 'login:' . $request->ip(),
            'api' => 'api:' . ($user?->id ?? $request->ip()),
            'upload' => 'upload:' . ($user?->id ?? $request->ip()),
            default => 'global:' . ($user?->id ?? $request->ip())
        };
    }
}