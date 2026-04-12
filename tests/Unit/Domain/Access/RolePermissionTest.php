<?php

declare(strict_types=1);

use App\Domain\Access\Models\Permission;
use App\Domain\Access\Models\Role;
use App\Domain\Users\Models\User;

it('can attach permissions to roles', function (): void {
    $role = Role::create(['name' => 'editor']);
    $permission = Permission::create(['name' => 'edit_posts']);

    $role->givePermissionTo($permission);

    expect($role->hasPermissionTo('edit_posts'))->toBeTrue();
});

it('can assign roles to users', function (): void {
    $user = User::factory()->create();
    $role = Role::create(['name' => 'admin']);

    $user->assignRole($role);

    expect($user->hasRole('admin'))->toBeTrue();
});

it('inherits permissions from roles', function (): void {
    $user = User::factory()->create();
    $role = Role::create(['name' => 'manager']);
    $permission = Permission::create(['name' => 'view_reports']);

    $role->givePermissionTo($permission);
    $user->assignRole($role);

    expect($user->hasPermissionTo('view_reports'))->toBeTrue();
});

it('identifies admin user type correctly', function (): void {
    $user = User::factory()->admin()->create();

    expect($user->type)->toBe('admin');
});
