<?php

declare(strict_types=1);

namespace App\Domain\Auth\Http\Controllers;

use App\Domain\Users\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use PragmaRX\Google2FALaravel\Facade as Google2FA;

class MFAChallengeController extends Controller
{
    /**
     * Show the 2FA challenge page.
     */
    public function show(Request $request): Response|RedirectResponse
    {
        if (! session()->has('2fa_user_id')) {
            return to_route('login');
        }

        return Inertia::render('Auth/MFAChallenge');
    }

    /**
     * Verify the 2FA code and log the user in.
     */
    public function verify(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'string', 'size:6'],
        ]);

        $userId = session('2fa_user_id');

        if (! $userId) {
            return to_route('login');
        }

        $user = User::findOrFail($userId);

        $valid = Google2FA::verifyKey($user->google2fa_secret, $request->code);

        if (! $valid) {
            return back()->withErrors(['code' => 'Invalid verification code.']);
        }

        // Log the user in
        Auth::login($user, session('2fa_remember', false));

        // Clear 2FA session data
        session()->forget(['2fa_user_id', '2fa_remember']);
        session()->regenerate();

        // Redirect based on user access
        if ($user->hasAdminAccess()) {
            return redirect()->intended(route('admin.dashboard'));
        }

        return redirect()->intended(route('dashboard'));
    }
}
