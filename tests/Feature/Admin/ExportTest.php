<?php

declare(strict_types=1);

use App\Domain\Access\Exports\RolesExport;
use App\Domain\Access\Models\Role;
use App\Domain\Users\Exports\UsersExport;
use App\Domain\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Maatwebsite\Excel\Facades\Excel;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    seedRoles();
    $this->adminUser = adminSeed();
});

test('admin can download users export', function (): void {
    Excel::fake();

    User::factory(5)->create();

    loginAdmin($this->adminUser);

    $response = $this->get(route('admin.users.export'));

    $response->assertSuccessful();

    Excel::assertDownloaded('users.xlsx', fn (UsersExport $export): bool => true);
});

test('admin can download roles export', function (): void {
    Excel::fake();

    // Using existing admin role from seeder instead of creating a new one
    $role = Role::where('name', 'admin')->first();

    loginAdmin($this->adminUser);

    $response = $this->get(route('admin.roles.export'));

    $response->assertSuccessful();

    Excel::assertDownloaded('roles.xlsx', fn (RolesExport $export): bool => true);
});
