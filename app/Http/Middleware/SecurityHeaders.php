<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Prevent clickjacking attacks
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // Prevent MIME type sniffing
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Enable XSS protection
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Referrer policy
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Permissions policy
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');

        // Content Security Policy (adjust based on your needs)
        if (config('app.env') === 'production') {
            $response->headers->set(
                'Content-Security-Policy',
                "default-src 'self'; " .
                    "script-src 'self' 'unsafe-inline' 'unsafe-eval'; " .
                    "style-src 'self' 'unsafe-inline'; " .
                    "img-src 'self' data: https:; " .
                    "font-src 'self' data:; " .
                    "connect-src 'self'; " .
                    "frame-ancestors 'self';"
            );
        }

        // HSTS (HTTP Strict Transport Security) - only in production
        if (config('app.env') === 'production' && $request->secure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        return $response;
    }
}
