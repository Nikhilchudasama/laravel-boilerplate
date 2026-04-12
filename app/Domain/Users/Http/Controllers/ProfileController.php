<?php

declare(strict_types=1);

namespace App\Domain\Users\Http\Controllers;

use App\Domain\Users\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;

class ProfileController extends Controller
{
    /**
     * Edit the user's profile.
     */
    public function edit(Request $request)
    {
        return Inertia::render('Frontend/Profile', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
            'user' => $request->user(),
            'activeTab' => 'profile',
        ]);
    }

    /**
     * View the user's security settings.
     */
    public function security(Request $request)
    {
        return Inertia::render('Frontend/Security', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
            'user' => $request->user(),
            'activeTab' => 'security',
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'avatar' => ['nullable', 'image', 'max:1024'], // 1MB Max
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        if ($request->hasFile('avatar')) {
            $user->clearMediaCollection('profile_picture');
            $user->addMediaFromRequest('avatar')
                ->toMediaCollection('profile_picture');
        }

        return to_route('profile')->with('success', 'Profile updated successfully.');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(8)->letters()->mixedCase()->numbers()->symbols()],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
            'password_changed_at' => now(),
        ]);

        return back()->with('success', 'Password updated successfully.');
    }
}
