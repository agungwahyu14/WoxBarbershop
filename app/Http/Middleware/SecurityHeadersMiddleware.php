<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeadersMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Add security headers
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');
        
        // Content Security Policy
        $csp = [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' cdn.jsdelivr.net unpkg.com",
            "style-src 'self' 'unsafe-inline' fonts.googleapis.com cdn.jsdelivr.net",
            "img-src 'self' data: https:",
            "font-src 'self' fonts.gstatic.com",
            "connect-src 'self'",
            "media-src 'self'",
            "object-src 'none'",
            "child-src 'none'",
            "worker-src 'none'",
            "frame-ancestors 'none'",
            "base-uri 'self'",
            "form-action 'self'"
        ];
        
        $response->headers->set('Content-Security-Policy', implode('; ', $csp));

        // HSTS (HTTP Strict Transport Security) - only for HTTPS
        if ($request->secure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        }

        // Log security events
        $this->logSecurityEvents($request);

        return $response;
    }

    /**
     * Log potential security threats
     */
    private function logSecurityEvents(Request $request): void
    {
        $userAgent = $request->userAgent();
        $ip = $request->ip();
        
        // Detect common attack patterns
        $suspiciousPatterns = [
            'script', 'javascript:', 'vbscript:', 'onload=', 'onerror=',
            '<script', '</script>', 'eval(', 'alert(', 'document.cookie',
            'union select', 'drop table', 'insert into', 'delete from',
            '../', '..\\', '/etc/passwd', '/proc/', 'cmd.exe'
        ];

        $requestData = json_encode([
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'headers' => $request->headers->all(),
            'input' => $request->all()
        ]);

        foreach ($suspiciousPatterns as $pattern) {
            if (stripos($requestData, $pattern) !== false) {
                Log::warning('Suspicious request detected', [
                    'pattern' => $pattern,
                    'ip' => $ip,
                    'user_agent' => $userAgent,
                    'url' => $request->fullUrl(),
                    'method' => $request->method(),
                    'user_id' => auth()->id()
                ]);
                break;
            }
        }

        // Detect suspicious user agents
        $maliciousUserAgents = [
            'sqlmap', 'nikto', 'nmap', 'masscan', 'nessus', 'burp',
            'w3af', 'skipfish', 'grabber', 'vega', 'golismero'
        ];

        foreach ($maliciousUserAgents as $agent) {
            if (stripos($userAgent, $agent) !== false) {
                Log::alert('Malicious user agent detected', [
                    'ip' => $ip,
                    'user_agent' => $userAgent,
                    'url' => $request->fullUrl()
                ]);
                break;
            }
        }
    }
}