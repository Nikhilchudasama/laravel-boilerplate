<?php

declare(strict_types=1);

use App\Domain\Access\Models\Role;
use App\Domain\Users\Models\User;
use Spatie\Permission\Models\Permission;

beforeEach(function (): void {
    seedRoles();
    loginAdmin();
});

test('admin can view roles index page', function (): void {
    $response = $this->get('/admin/roles');

    $response->assertStatus(200);
});

test('admin can view create role page', function (): void {
    $response = $this->get('/admin/roles/create');

    $response->assertStatus(200);
});

test('admin can create a new role', function (): void {
    $permission = Permission::first();

    $response = $this->post(route('admin.roles.store'), [
        'name' => 'Manager',
        'permissions' => [$permission->name],
    ]);

    $response->assertRedirect('/admin/roles');

    $this->assertDatabaseHas('roles', [
        'name' => 'Manager',
    ]);
});

test('role creation with permissions is atomic', function (): void {
    $permissions = Permission::take(3)->pluck('name')->toArray();

    $response = $this->post(route('admin.roles.store'), [
        'name' => 'Editor',
        'permissions' => $permissions,
    ]);

    $role = Role::where('name', 'Editor')->first();

    expect($role)->not->toBeNull()
        ->and($role->permissions->count())->toBe(3);
});

test('admin can view edit role page', function (): void {
    $role = Role::where('name', 'admin')->first();

    $response = $this->get(sprintf('/admin/roles/%s/edit', $role->id));

    $response->assertStatus(200);
});

test('admin can update a role', function (): void {
    $role = Role::create(['name' => 'OldRole']);
    $permission = Permission::first();

    $response = $this->post(route('admin.roles.update', $role), [
        'name' => 'UpdatedRole',
        'permissions' => [$permission->name],
    ]);

    $response->assertRedirect('/admin/roles');

    $this->assertDatabaseHas('roles', [
        'id' => $role->id,
        'name' => 'UpdatedRole',
    ]);
});

test('role update with permission sync is atomic', function (): void {
    $role = Role::create(['name' => 'TestRole']);
    $permissions = Permission::take(2)->pluck('name')->toArray();

    $response = $this->post(route('admin.roles.update', $role), [
        'name' => 'UpdatedTestRole',
        'permissions' => $permissions,
    ]);

    $role->refresh();

    expect($role->name)->toBe('UpdatedTestRole')
        ->and($role->permissions->count())->toBe(2);
});

test('admin can search roles', function (): void {
    Role::create(['name' => 'Supervisor']);
    Role::create(['name' => 'Moderator']);

    $response = $this->get('/admin/roles?search_text=Super');

    $response->assertStatus(200);
});

test('admin can export roles', function (): void {
    $response = $this->get('/admin/roles/export');

    $response->assertStatus(200);
});

test('regular user cannot access role management', function (): void {
    $user = User::factory()->user()->create();

    $this->actingAs($user);

    $response = $this->get('/admin/roles');

    $response->assertRedirect('/dashboard');
});
