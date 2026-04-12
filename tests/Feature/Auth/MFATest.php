<?php

declare(strict_types=1);

use App\Domain\Users\Models\User;
use Illuminate\Support\Facades\Auth;
use PragmaRX\Google2FALaravel\Facade as Google2FA;

beforeEach(function (): void {
    $this->user = User::factory()->create([
        'password' => bcrypt('password'),
        'google2fa_secret' => null,
    ]);
});

test('user can access the MFA setup page', function (): void {
    $this->actingAs($this->user)
        ->get(route('security'))
        ->assertStatus(200)
        ->assertInertia(fn ($page) => $page->component('Frontend/Security'));
});

test('user can generate an MFA secret', function (): void {
    $this->actingAs($this->user)
        ->post(route('2fa.setup'))
        ->assertStatus(200)
        ->assertInertia(
            fn ($page) => $page
                ->has('mfa_setup.secret')
                ->has('mfa_setup.qr_code')
        );

    expect(session('2fa_setup_secret'))->not->toBeNull();
});

test('user can enable MFA with a valid code', function (): void {
    $secret = Google2FA::generateSecretKey();
    session(['2fa_setup_secret' => $secret]);

    $code = Google2FA::getCurrentOtp($secret);

    $this->actingAs($this->user)
        ->post(route('2fa.enable'), ['code' => $code])
        ->assertRedirect()
        ->assertSessionHas('success');

    $this->user->refresh();
    expect($this->user->google2fa_secret)->toBe($secret);
});

test('user cannot enable MFA with an invalid code', function (): void {
    session(['2fa_setup_secret' => Google2FA::generateSecretKey()]);

    $this->actingAs($this->user)
        ->post(route('2fa.enable'), ['code' => '000000'])
        ->assertSessionHasErrors('code');

    $this->user->refresh();
    expect($this->user->google2fa_secret)->toBeNull();
});

test('login redirects to MFA challenge if enabled', function (): void {
    $this->user->update(['google2fa_secret' => Google2FA::generateSecretKey()]);

    $response = $this->post(route('login'), [
        'email' => $this->user->email,
        'password' => 'password',
    ]);

    $response->assertRedirect(route('2fa.challenge'));

    expect(Auth::check())->toBeFalse();
    expect(session('2fa_user_id'))->toBe($this->user->id);
});

test('user can complete login with valid MFA code', function (): void {
    $secret = Google2FA::generateSecretKey();
    $this->user->update(['google2fa_secret' => $secret]);

    session(['2fa_user_id' => $this->user->id]);

    $code = Google2FA::getCurrentOtp($secret);

    $this->post(route('2fa.challenge'), ['code' => $code])
        ->assertRedirect(route('dashboard'));

    expect(Auth::check())->toBeTrue();
    expect(Auth::id())->toBe($this->user->id);
});

test('user cannot bypass MFA with invalid code', function (): void {
    $this->user->update(['google2fa_secret' => Google2FA::generateSecretKey()]);
    session(['2fa_user_id' => $this->user->id]);

    $this->post(route('2fa.challenge'), ['code' => '000000'])
        ->assertSessionHasErrors('code');

    expect(Auth::check())->toBeFalse();
});
