<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domain\Access\Models\Role;
use App\Domain\Users\Models\User;
use Illuminate\Database\Seeder;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Seeding demo data...');

        // Create demo admin user (skip if already exists)
        $admin = User::where('email', 'admin@demo.com')->first();

        if (! $admin) {
            $admin = User::factory()->admin()->create([
                'name' => 'Demo Admin',
                'email' => 'admin@demo.com',
                'password' => bcrypt('password'),
            ]);
            $admin->assignRole('admin');
            $this->command->info('✓ Created demo admin: admin@demo.com / password');
        } else {
            $this->command->info('ℹ Demo admin already exists: admin@demo.com / password');
        }

        // Create demo regular user
        $user = User::where('email', 'user@demo.com')->first();
        if (! $user) {
            $user = User::factory()->create([
                'name' => 'Demo User',
                'email' => 'user@demo.com',
                'password' => bcrypt('password'),
            ]);
            $user->assignRole('user');
            $this->command->info('✓ Created demo user: user@demo.com / password');
        } else {
            $this->command->info('ℹ Demo user already exists: user@demo.com / password');
        }

        // Create demo regular users (random)
        $users = User::factory()->count(10)->create();
        $this->command->info('✓ Created 10 demo users');

        // Assign random roles to some users
        $userRole = Role::where('name', 'user')->first();
        foreach ($users->random(5) as $user) {
            $user->assignRole($userRole);
        }

        $this->command->info('✓ Assigned roles to demo users');

        $this->command->info('Demo data seeded successfully!');
        $this->command->newLine();
        $this->command->info('You can log in with:');
        $this->command->info('  Email: admin@demo.com');
        $this->command->info('  Password: password');
    }
}
