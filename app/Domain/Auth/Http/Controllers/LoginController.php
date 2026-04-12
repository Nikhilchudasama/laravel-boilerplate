<?php

declare(strict_types=1);

namespace App\Domain\Auth\Http\Controllers;

use App\Domain\Auth\Data\LoginData;
use App\Domain\Users\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class LoginController extends Controller
{
    public function create(): Response
    {
        return Inertia::render('Admin/Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
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

        session()->regenerate();

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

        return to_route('admin.login');
    }
}
