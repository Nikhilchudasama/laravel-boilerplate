<?php

declare(strict_types=1);

use App\Domain\Users\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

it('renders forgot password page', function (): void {
    $this->get('/forgot-password')
        ->assertStatus(200);
});

it('can request reset password link', function (): void {
    $user = User::factory()->create();

    $this->post('/forgot-password', [
        'email' => $user->email,
    ])->assertSessionHas('success');

    $this->assertDatabaseHas('password_reset_tokens', [
        'email' => $user->email,
    ]);
});

it('renders reset password page', function (): void {
    $user = User::factory()->create();
    $token = Password::createToken($user);

    $this->get('/reset-password/' . $token . '?email=' . $user->email)
        ->assertStatus(200);
});

it('can reset password with valid token', function (): void {
    $user = User::factory()->create();
    $token = Password::createToken($user);

    $this->post('/reset-password', [
        'token' => $token,
        'email' => $user->email,
        'password' => 'new-password',
        'password_confirmation' => 'new-password',
    ])->assertRedirect('/login')
        ->assertSessionHas('success');

    expect(Hash::check('new-password', $user->fresh()->password))->toBeTrue();
});
