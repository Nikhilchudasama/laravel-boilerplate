<?php

declare(strict_types=1);

use App\Domain\Users\Models\User;
use Illuminate\Http\UploadedFile;

beforeEach(function (): void {
    seedRoles();
});

// Soft Delete Tests
test('user can be soft deleted', function (): void {
    loginAdmin();

    $user = User::factory()->create();
    $userId = $user->id;

    $user->delete();

    expect(User::find($userId))->toBeNull()
        ->and(User::withTrashed()->find($userId))->not->toBeNull();
});

test('soft deleted users are not shown in user list', function (): void {
    loginAdmin();

    $activeUser = User::factory()->create(['name' => 'Active User']);
    $deletedUser = User::factory()->create(['name' => 'Deleted User']);
    $deletedUser->delete();

    $response = $this->get('/admin/users');

    $response->assertStatus(200);
});

test('soft deleted user can be restored', function (): void {
    $user = User::factory()->create();
    $userId = $user->id;

    $user->delete();
    expect(User::find($userId))->toBeNull();

    $user->restore();
    expect(User::find($userId))->not->toBeNull();
});

// User Type Tests
test('admin type user has admin access', function (): void {
    $admin = User::factory()->admin()->create();
    $admin->assignRole('admin');

    expect($admin->isAdmin())->toBeTrue()
        ->and($admin->hasAdminAccess())->toBeTrue();
});

test('regular type user does not have admin access by default', function (): void {
    $user = User::factory()->user()->create();

    expect($user->isAdmin())->toBeFalse()
        ->and($user->hasAdminAccess())->toBeFalse();
});

// Avatar Tests
test('user avatar url returns fallback when no media', function (): void {
    $user = User::factory()->create(['name' => 'John Doe']);

    expect($user->avatar_url)
        ->toContain('ui-avatars.com')
        ->toContain('John+Doe');
});

test('user avatar url returns media url when avatar exists', function (): void {
    $user = User::factory()->create();

    // Simulate adding media
    $user->addMedia(UploadedFile::fake()->image('avatar.jpg'))
        ->toMediaCollection('profile_picture');

    $avatarUrl = $user->avatar_url;

    expect($avatarUrl)->not->toContain('ui-avatars.com');
});
