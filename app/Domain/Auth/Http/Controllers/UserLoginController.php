<?php

declare(strict_types=1);

namespace App\Domain\Auth\Http\Controllers;

use App\Domain\Auth\Data\LoginData;
use App\Domain\Users\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class UserLoginController extends Controller
{
    public function create(): Response
    {
        return Inertia::render('Frontend/Auth/Login', [
            'demo' => config('app.demo_mode') ? [
                'email' => 'user@demo.com',
                'password' => 'password',
            ] : null,
        ]);
    }

    public function store(LoginData $data): RedirectResponse
    {
        if (! Auth::attempt(['email' => $data->email, 'password' => $data->password], $data->remember)) {
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ]);
        }

        /** @var User $user */
        $user = Auth::user();

        // Check if 2FA is enabled
        if ($user->google2fa_secret) {
            // Log them out immediately as they are "pre-authed"
            Auth::logout();

            // Stash info in session
            session(['2fa_user_id' => $user->id, '2fa_remember' => $data->remember]);

            return to_route('2fa.challenge');
        }

        session()->regenerate();

        // Redirect based on user access
        if ($user->hasAdminAccess()) {
            return redirect()->intended(route('admin.dashboard'));
        }

        return redirect()->intended(route('dashboard'));
    }

    public function destroy(): RedirectResponse
    {
        Auth::guard('web')->logout();

        session()->invalidate();
        session()->regenerateToken();

        return to_route('login');
    }
}
