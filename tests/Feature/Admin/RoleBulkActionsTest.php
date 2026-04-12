<?php

declare(strict_types=1);

use App\Domain\Access\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    seedRoles();
    $this->adminUser = adminSeed();
    $this->standardUser = userSeed();
});

test('admin can bulk delete roles', function (): void {
    $rolesToDelete = collect([
        Role::create(['name' => 'role_1']),
        Role::create(['name' => 'role_2']),
    ]);

    $ids = $rolesToDelete->pluck('id')->toArray();

    loginAdmin($this->adminUser);

    $response = $this->post(route('admin.roles.bulk-delete'), [
        'ids' => $ids,
    ]);

    $response->assertRedirect(route('admin.roles.index'))
        ->assertSessionHas('success');

    foreach ($ids as $id) {
        $this->assertDatabaseMissing('roles', ['id' => $id]);
    }
});

test('standard user cannot bulk delete roles', function (): void {
    loginUser($this->standardUser);

    $response = $this->post(route('admin.roles.bulk-delete'), [
        'ids' => [1, 2],
    ]);

    $response->assertRedirect('/dashboard');
});
