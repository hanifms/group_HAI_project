<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            AdminUserSeeder::class,
            TravelPackageSeeder::class,
        ]);

        // Create a regular user for testing
        User::factory()->withUserRole()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
