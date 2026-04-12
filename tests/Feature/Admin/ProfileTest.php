<?php

declare(strict_types=1);

use App\Domain\Users\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;

beforeEach(function (): void {
    seedRoles();
    loginAdmin();
});

test('admin can view profile edit page', function (): void {
    $response = $this->get(route('admin.profile'));
    $response->assertStatus(200);
});

test('admin can view security page', function (): void {
    $response = $this->get(route('admin.profile.security'));
    $response->assertStatus(200);
});

test('admin can update profile info', function (): void {
    /** @var User $user */
    $user = auth()->user();

    $response = $this->post(route('admin.profile.update'), [
        'name' => 'Updated Admin',
        'email' => $user->email,
    ]);

    $response->assertRedirect(route('admin.profile'));
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'name' => 'Updated Admin',
    ]);
});

test('admin can update password', function (): void {
    /** @var User $user */
    $user = auth()->user();

    $response = $this->post(route('admin.profile.password'), [
        'current_password' => 'password', // Default factory password
        'password' => 'NewPassword123!',
        'password_confirmation' => 'NewPassword123!',
    ]);

    $response->assertRedirect(route('admin.profile.security'));
    $response->assertSessionHas('success');

    $user->refresh();
    expect(Hash::check('NewPassword123!', $user->password))->toBeTrue();
});

test('admin can upload avatar', function (): void {
    /** @var User $user */
    $user = auth()->user();

    $response = $this->post(route('admin.profile.update'), [
        'name' => $user->name,
        'email' => $user->email,
        'avatar' => UploadedFile::fake()->image('avatar.jpg', 100, 100),
    ]);

    $response->assertRedirect(route('admin.profile'));
    $response->assertSessionHas('success');

    $user->refresh();
    expect($user->getFirstMediaUrl('profile_picture'))->not->toBeEmpty();
});
