<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Domain\Users\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request):Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            // Redirect unauthenticated users to frontend login
            return to_route('login');
        }

        /** @var User $user */
        $user = Auth::user();

        if ($user->hasAdminAccess()) {
            return $next($request);
        }

        return to_route('dashboard')->withErrors([
            'access' => 'You do not have administrative privileges. Welcome to your user dashboard.',
        ]);
    }
}
