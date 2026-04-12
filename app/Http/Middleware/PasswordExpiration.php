<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PasswordExpiration
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request):Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        if (! $user) {
            return $next($request);
        }

        if (! $user->password_changed_at) {
            return $next($request);
        }

        // Check if password older than 90 days
        if ($user->password_changed_at->diffInDays(now()) <= 90) {
            return $next($request);
        }

        // Allow them to access the password change route
        if (! $request->routeIs('password.expired') && ! $request->routeIs('password.update')) {
            return to_route('password.expired')
                ->with('warning', 'Your password has expired. Please update it.');
        }

        return $next($request);
    }
}
