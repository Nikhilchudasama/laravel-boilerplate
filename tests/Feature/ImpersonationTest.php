<?php

declare(strict_types=1);

use App\Domain\Users\Models\User;

beforeEach(function (): void {
    seedRoles();
});

test('admin can impersonate a user', function (): void {
    $admin = User::factory()->admin()->create();
    $admin->assignRole('admin');

    $user = User::factory()->user()->create();

    $this->actingAs($admin);

    $response = $this->get(route('admin.users.impersonate', $user));

    $response->assertRedirect(route('admin.dashboard'));
    $response->assertSessionHas('success');

    // Lab404 Impersonate stores impersonator id in session
    expect(session()->has('impersonated_by'))->toBeTrue();
});

test('admin cannot impersonate themselves', function (): void {
    $admin = User::factory()->admin()->create();
    $admin->assignRole('admin');

    $this->actingAs($admin);

    $response = $this->get(route('admin.users.impersonate', $admin));

    $response->assertRedirect();
    $response->assertSessionHas('error');
});

test('regular user cannot impersonate', function (): void {
    $user = User::factory()->user()->create();
    $target = User::factory()->user()->create();

    $this->actingAs($user);

    $response = $this->get(route('admin.users.impersonate', $target));

    $response->assertRedirect(route('dashboard'));
});
