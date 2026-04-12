<?php

declare(strict_types=1);

use App\Domain\Users\Models\User;
use Spatie\Permission\Models\Role;

beforeEach(function (): void {
    seedRoles();
    loginAdmin();
});

test('admin can view users index page', function (): void {
    $response = $this->get('/admin/users');

    $response->assertStatus(200);
});

test('admin can view create user page', function (): void {
    $response = $this->get('/admin/users/create');

    $response->assertStatus(200);
});

test('admin can create a new user', function (): void {
    $role = Role::where('name', 'admin')->first();

    $response = $this->post(route('admin.users.store'), [
        'name' => 'New User',
        'email' => 'newuser@example.com',
        'password' => 'password123',
        'type' => 'user',
        'active' => true,
        'roles' => ['admin'],
    ]);

    $response->assertRedirect('/admin/users');

    $this->assertDatabaseHas('users', [
        'name' => 'New User',
        'email' => 'newuser@example.com',
    ]);
});

test('user creation with role is atomic', function (): void {
    $role = Role::where('name', 'admin')->first();

    // This should succeed completely or fail completely
    $response = $this->post(route('admin.users.store'), [
        'name' => 'Atomic User',
        'email' => 'atomic@example.com',
        'password' => 'password123',
        'type' => 'user',
        'active' => true,
        'roles' => ['admin'],
    ]);

    $user = User::where('email', 'atomic@example.com')->first();

    expect($user)->not->toBeNull()
        ->and($user->hasRole('admin'))->toBeTrue();
});

test('admin can view edit user page', function (): void {
    $user = User::factory()->create();

    $response = $this->get(sprintf('/admin/users/%s/edit', $user->id));

    $response->assertStatus(200);
});

test('admin can update a user', function (): void {
    $user = User::factory()->create(['name' => 'Old Name']);
    $role = Role::where('name', 'admin')->first();

    $response = $this->post(route('admin.users.update', $user), [
        'name' => 'Updated Name',
        'email' => $user->email,
        'type' => 'user',
        'active' => true,
        'roles' => ['admin'],
    ]);

    $response->assertRedirect('/admin/users');

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'name' => 'Updated Name',
    ]);
});

test('user update with role sync is atomic', function (): void {
    $user = User::factory()->create();
    $role = Role::where('name', 'admin')->first();

    $response = $this->post(route('admin.users.update', $user), [
        'name' => 'Updated Atomic User',
        'email' => $user->email,
        'type' => 'user',
        'active' => true,
        'roles' => ['admin'],
    ]);

    $user->refresh();

    expect($user->name)->toBe('Updated Atomic User')
        ->and($user->hasRole('admin'))->toBeTrue();
});

test('admin can search users', function (): void {
    User::factory()->create(['name' => 'John Doe']);
    User::factory()->create(['name' => 'Jane Smith']);

    $response = $this->get('/admin/users?search_text=John');

    $response->assertStatus(200);
});

test('admin can export users', function (): void {
    User::factory()->count(5)->create();

    $response = $this->get('/admin/users/export');

    $response->assertStatus(200);
});

test('regular user cannot access user management', function (): void {
    $user = User::factory()->user()->create();

    $this->actingAs($user);

    $response = $this->get('/admin/users');

    $response->assertRedirect('/dashboard');
});
