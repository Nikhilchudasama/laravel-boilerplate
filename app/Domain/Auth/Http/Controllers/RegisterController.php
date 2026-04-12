<?php

declare(strict_types=1);

namespace App\Domain\Auth\Http\Controllers;

use App\Domain\Auth\Data\RegisterData;
use App\Domain\Users\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;

class RegisterController extends Controller
{
    public function create(): Response
    {
        return Inertia::render('Frontend/Auth/Register');
    }

    public function store(RegisterData $data): RedirectResponse
    {
        $user = User::create([
            'name' => $data->name,
            'email' => $data->email,
            'password' => Hash::make($data->password),
            'type' => User::TYPE_USER,
            'active' => true,
            'timezone' => $data->timezone,
        ]);

        Auth::login($user);

        return to_route('dashboard');
    }
}
