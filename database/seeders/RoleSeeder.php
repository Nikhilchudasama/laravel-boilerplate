<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domain\Access\Models\Permission;
use App\Domain\Access\Models\Role;
use Illuminate\Database\Seeder;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Permissions
        Permission::create(['name' => 'access_admin_panel']);
        Permission::create(['name' => 'view_users']);
        Permission::create(['name' => 'create_users']);
        Permission::create(['name' => 'edit_users']);
        Permission::create(['name' => 'delete_users']);

        Permission::create(['name' => 'view_roles']);
        Permission::create(['name' => 'create_roles']);
        Permission::create(['name' => 'edit_roles']);
        Permission::create(['name' => 'delete_roles']);

        Permission::create(['name' => 'view_activity_logs']);

        // Create Admin Role
        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo(Permission::all());

        // Create Editor Role
        $editor = Role::create(['name' => 'editor']);
        $editor->givePermissionTo(['view_users', 'create_users']);
    }
}
