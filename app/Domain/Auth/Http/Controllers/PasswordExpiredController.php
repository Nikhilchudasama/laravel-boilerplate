<?php

declare(strict_types=1);

namespace App\Domain\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;

class PasswordExpiredController extends Controller
{
    public function show()
    {
        return Inertia::render('Auth/PasswordExpired');
    }

    public function update(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(8)->letters()->mixedCase()->numbers()->symbols()],
        ]);

        $request->user()->update([
            'password' => Hash::make($request->password),
            'password_changed_at' => now(),
        ]);

        return to_route('admin.dashboard')
            ->with('success', 'Password updated successfully. You are back in!');
    }
}
