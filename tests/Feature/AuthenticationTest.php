<?php

declare(strict_types=1);

use App\Domain\Users\Models\User;

beforeEach(function (): void {
    seedRoles();
});

test('admin login page can be accessed', function (): void {
    $response = $this->get('/admin/login');

    $response->assertStatus(200);
});

test('admin can login with correct credentials', function (): void {
    $admin = User::factory()->admin()->create([
        'email' => 'admin@example.com',
        'password' => bcrypt('password'),
    ]);
    $admin->assignRole('admin');

    $response = $this->post('/admin/login', [
        'email' => 'admin@example.com',
        'password' => 'password',
        'remember' => false,
        'timezone' => 'UTC',
    ]);

    $response->assertRedirect('/admin/dashboard');
    $this->assertAuthenticatedAs($admin);
});

test('regular user is redirected to user dashboard after login', function (): void {
    $user = User::factory()->user()->create([
        'email' => 'user@example.com',
        'password' => bcrypt('password'),
    ]);

    $response = $this->post('/admin/login', [
        'email' => 'user@example.com',
        'password' => 'password',
        'remember' => false,
        'timezone' => 'UTC',
    ]);

    $response->assertRedirect('/dashboard');
    $this->assertAuthenticatedAs($user);
});

test('login fails with incorrect password', function (): void {
    User::factory()->admin()->create([
        'email' => 'admin@example.com',
        'password' => bcrypt('password'),
    ]);

    $response = $this->post('/admin/login', [
        'email' => 'admin@example.com',
        'password' => 'wrong-password',
        'remember' => false,
        'timezone' => 'UTC',
    ]);

    $response->assertSessionHasErrors('email');
    $this->assertGuest();
});

test('admin can logout', function (): void {
    $admin = User::factory()->admin()->create();
    $admin->assignRole('admin');

    $this->actingAs($admin);

    $response = $this->post('/admin/logout');

    $response->assertRedirect('/admin/login');
    $this->assertGuest();
});

test('unauthenticated user cannot access admin dashboard', function (): void {
    $response = $this->get('/admin/dashboard');

    $response->assertRedirect('/admin/login');
});

test('regular user cannot access admin dashboard', function (): void {
    $user = User::factory()->user()->create();

    $this->actingAs($user);

    $response = $this->get('/admin/dashboard');

    $response->assertRedirect('/dashboard');
});

test('admin can access admin dashboard', function (): void {
    $admin = User::factory()->admin()->create();
    $admin->assignRole('admin');

    $this->actingAs($admin);

    $response = $this->get('/admin/dashboard');

    $response->assertStatus(200);
});
