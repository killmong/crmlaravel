<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Run the RoleSeeder first
        $this->call([
            RoleSeeder::class,
        ]);

        // 2. Create the Super Admin user
        // $admin = User::factory()->create([
        //     'name' => 'Master Admin',
        //     'email' => 'admin@crm.com',
        //     'password' => bcrypt('password'), // Set a known password
        // ]);

        // // 3. Assign the role to the user
        // $admin->assignRole('master-admin');

        // // Optional: Create some regular users
        // User::factory(10)->create()->each(function ($user) {
        //     $user->assignRole('user');
        // });
    }
}
