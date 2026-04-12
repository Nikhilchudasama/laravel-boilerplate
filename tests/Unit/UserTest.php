<?php

declare(strict_types=1);

use App\Domain\Users\Models\User;

beforeEach(function (): void {
    seedRoles();
});

test('user can be created with uuid', function (): void {
    $user = User::factory()->create([
        'name' => 'Test User',
        'email' => 'test@example.com',
    ]);

    expect($user->id)->toBeString()
        ->and(strlen($user->id))->toBeGreaterThan(30);
});

test('user has admin access when type is admin', function (): void {
    $user = User::factory()->admin()->create();
    $user->assignRole('admin');

    expect($user->isAdmin())->toBeTrue()
        ->and($user->hasAdminAccess())->toBeTrue();
});

test('user does not have admin access when type is user', function (): void {
    $user = User::factory()->user()->create();

    expect($user->isAdmin())->toBeFalse();
});

test('user with admin role has admin access', function (): void {
    $user = User::factory()->user()->create();
    $user->assignRole('admin');

    expect($user->hasAdminAccess())->toBeTrue();
});

test('user avatar url returns fallback when no media', function (): void {
    $user = User::factory()->create(['name' => 'John Doe']);

    expect($user->avatar_url)
        ->toContain('ui-avatars.com')
        ->toContain('John+Doe');
});

test('user can be soft deleted', function (): void {
    $user = User::factory()->create();
    $userId = $user->id;

    $user->delete();

    expect(User::find($userId))->toBeNull()
        ->and(User::withTrashed()->find($userId))->not->toBeNull();
});

test('user password is hashed automatically', function (): void {
    $user = User::factory()->create(['password' => 'password123']);

    expect($user->password)->not->toBe('password123')
        ->and(strlen($user->password))->toBeGreaterThan(50);
});
