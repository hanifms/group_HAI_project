<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create user role
        Role::create([
            'name' => 'user',
        ]);

        // Create admin role
        Role::create([
            'name' => 'admin',
        ]);
    }
}
