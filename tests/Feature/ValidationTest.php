<?php

declare(strict_types=1);

use App\Domain\Users\Models\User;

beforeEach(function (): void {
    seedRoles();
    loginAdmin();
});

// User Validation Tests
test('user creation fails with invalid email', function (): void {
    $response = $this->post(route('admin.users.store'), [
        'name' => 'Test User',
        'email' => 'invalid-email',
        'password' => 'password123',
        'type' => 'user',
        'active' => true,
        'roles' => ['admin'],
    ]);

    $response->assertSessionHasErrors('email');
});

test('user creation fails with duplicate email', function (): void {
    $existingUser = User::factory()->create(['email' => 'existing@example.com']);

    $response = $this->post(route('admin.users.store'), [
        'name' => 'Test User',
        'email' => 'existing@example.com',
        'password' => 'password123',
        'type' => 'user',
        'active' => true,
        'roles' => ['admin'],
    ]);

    $response->assertSessionHasErrors('email');
});

test('user creation fails without required fields', function (): void {
    $response = $this->post(route('admin.users.store'), []);

    $response->assertSessionHasErrors(['name', 'email', 'password']);
});

test('user update fails with duplicate email', function (): void {
    $user1 = User::factory()->create(['email' => 'user1@example.com']);
    $user2 = User::factory()->create(['email' => 'user2@example.com']);

    $response = $this->post(route('admin.users.update', $user1), [
        'name' => $user1->name,
        'email' => 'user2@example.com', // Try to use user2's email
        'type' => 'user',
        'active' => true,
        'roles' => ['admin'],
    ]);

    $response->assertSessionHasErrors('email');
});

// Role Validation Tests
test('role creation fails without name', function (): void {
    $response = $this->post(route('admin.roles.store'), []);

    $response->assertSessionHasErrors('name');
});

// Profile Validation Tests
test('profile update fails with invalid email', function (): void {
    /** @var User $user */
    $user = auth()->user();

    $response = $this->post(route('admin.profile.update'), [
        'name' => 'Updated Name',
        'email' => 'invalid-email',
    ]);

    $response->assertSessionHasErrors('email');
});

test('password update fails with incorrect current password', function (): void {
    /** @var User $user */
    $user = auth()->user();

    $response = $this->post(route('admin.profile.password'), [
        'current_password' => 'wrong-password',
        'password' => 'NewPassword123!',
        'password_confirmation' => 'NewPassword123!',
    ]);

    $response->assertSessionHasErrors('current_password');
});

test('password update fails with weak password', function (): void {
    /** @var User $user */
    $user = auth()->user();

    $response = $this->post(route('admin.profile.password'), [
        'current_password' => 'password',
        'password' => 'weak',
        'password_confirmation' => 'weak',
    ]);

    $response->assertSessionHasErrors('password');
});

test('password update fails when passwords do not match', function (): void {
    /** @var User $user */
    $user = auth()->user();

    $response = $this->post(route('admin.profile.password'), [
        'current_password' => 'password',
        'password' => 'NewPassword123!',
        'password_confirmation' => 'DifferentPassword123!',
    ]);

    $response->assertSessionHasErrors('password');
});
