<?php

declare(strict_types=1);

use App\Domain\Users\Models\User;

test('user registration page can be accessed', function (): void {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('user can register with valid data', function (): void {
    $response = $this->post('/register', [
        'name' => 'New User',
        'email' => 'newuser@example.com',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
        'timezone' => 'UTC',
        'terms' => true,
    ]);

    $response->assertRedirect('/dashboard');

    $this->assertDatabaseHas('users', [
        'name' => 'New User',
        'email' => 'newuser@example.com',
        'type' => 'user',
    ]);
});

test('user registration fails with invalid data', function (): void {
    $response = $this->post('/register', [
        'name' => '',
        'email' => 'invalid-email',
        'password' => 'weak',
        'password_confirmation' => 'different',
    ]);

    $response->assertSessionHasErrors(['name', 'email', 'password']);
});

test('user login page can be accessed', function (): void {
    $response = $this->get('/login');

    $response->assertStatus(200);
});

test('user can login with correct credentials', function (): void {
    $user = User::factory()->user()->create([
        'email' => 'user@example.com',
        'password' => bcrypt('password'),
    ]);

    $response = $this->post('/login', [
        'email' => 'user@example.com',
        'password' => 'password',
    ]);

    $response->assertRedirect('/dashboard');
    $this->assertAuthenticatedAs($user);
});

test('user login fails with incorrect password', function (): void {
    User::factory()->user()->create([
        'email' => 'user@example.com',
        'password' => bcrypt('password'),
    ]);

    $response = $this->post('/login', [
        'email' => 'user@example.com',
        'password' => 'wrong-password',
    ]);

    $response->assertSessionHasErrors('email');
    $this->assertGuest();
});

test('authenticated user can access dashboard', function (): void {
    $user = User::factory()->user()->create();

    $this->actingAs($user);

    $response = $this->get('/dashboard');

    $response->assertStatus(200);
});

test('unauthenticated user cannot access dashboard', function (): void {
    $response = $this->get('/dashboard');

    $response->assertRedirect('/login');
});

test('user can view profile page', function (): void {
    $user = User::factory()->user()->create();

    $this->actingAs($user);

    $response = $this->get(route('profile'));

    $response->assertStatus(200);
});

test('user can update their profile', function (): void {
    $user = User::factory()->user()->create();

    $this->actingAs($user);

    $response = $this->post(route('profile.update'), [
        'name' => 'Updated User Name',
        'email' => $user->email,
    ]);

    $response->assertRedirect(route('profile'));

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'name' => 'Updated User Name',
    ]);
});

test('user can logout', function (): void {
    $user = User::factory()->user()->create();

    $this->actingAs($user);

    $response = $this->post('/logout');

    $response->assertRedirect('/login');
    $this->assertGuest();
});
