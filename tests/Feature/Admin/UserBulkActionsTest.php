<?php

declare(strict_types=1);

use App\Domain\Users\Models\User;

beforeEach(function (): void {
    seedRoles();
    $this->adminUser = adminSeed();
    $this->standardUser = userSeed();
});

test('admin can bulk delete users', function (): void {
    $usersToDelete = User::factory(3)->create();

    $ids = $usersToDelete->pluck('id')->toArray();

    loginAdmin($this->adminUser);

    $response = $this->post(route('admin.users.bulk-delete'), [
        'ids' => $ids,
    ]);

    $response->assertRedirect(route('admin.users.index'))
        ->assertSessionHas('success');

    foreach ($ids as $id) {
        $this->assertSoftDeleted('users', ['id' => $id]);
    }
});

test('admin cannot bulk delete themselves', function (): void {
    $usersToDelete = User::factory(2)->create();

    $ids = $usersToDelete->pluck('id')->toArray();
    $ids[] = $this->adminUser->id; // Add self to list

    loginAdmin($this->adminUser);

    $response = $this->post(route('admin.users.bulk-delete'), [
        'ids' => $ids,
    ]);

    $response->assertRedirect(route('admin.users.index'));

    // Verify self is still active
    $this->assertDatabaseHas('users', [
        'id' => $this->adminUser->id,
        'deleted_at' => null,
    ]);
});

test('standard user cannot bulk delete', function (): void {
    loginUser($this->standardUser);

    $response = $this->post(route('admin.users.bulk-delete'), [
        'ids' => [1, 2, 3],
    ]);

    $response->assertRedirect('/dashboard');
});

test('admin can bulk toggle active status', function (): void {
    $usersToToggle = User::factory(3)->create(['active' => true]);
    $ids = $usersToToggle->pluck('id')->toArray();

    loginAdmin($this->adminUser);

    $response = $this->post(route('admin.users.bulk-toggle-active'), [
        'ids' => $ids,
        'active' => false,
    ]);

    $response->assertRedirect(route('admin.users.index'))
        ->assertSessionHas('success');

    foreach ($ids as $id) {
        $this->assertDatabaseHas('users', [
            'id' => $id,
            'active' => false,
        ]);
    }
});
