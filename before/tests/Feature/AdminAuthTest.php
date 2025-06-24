<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that a regular user cannot access the admin dashboard.
     */
    public function test_regular_user_cannot_access_admin_dashboard(): void
    {
        // Create roles
        $userRole = Role::create(['name' => 'user']);
        $adminRole = Role::create(['name' => 'admin']);

        // Create a regular user
        $user = User::factory()->withUserRole()->create();

        // Attempt to access admin dashboard as regular user
        $response = $this->actingAs($user)->get('/admin/dashboard');
        $response->assertRedirect('/dashboard');
    }

    /**
     * Test that an admin user can access the admin dashboard.
     */
    public function test_admin_user_can_access_admin_dashboard(): void
    {
        // Create roles
        $userRole = Role::create(['name' => 'user']);
        $adminRole = Role::create(['name' => 'admin']);

        // Create an admin user
        $admin = User::factory()->withAdminRole()->create();

        // Access admin dashboard as admin user
        $response = $this->actingAs($admin)->get('/admin/dashboard');
        $response->assertStatus(200);
        $response->assertViewIs('admin.dashboard');
    }

    /**
     * Test that an admin user is redirected to admin dashboard from regular dashboard.
     */
    public function test_admin_user_is_redirected_to_admin_dashboard(): void
    {
        // Create roles
        $userRole = Role::create(['name' => 'user']);
        $adminRole = Role::create(['name' => 'admin']);

        // Create an admin user
        $admin = User::factory()->withAdminRole()->create();

        // Access regular dashboard as admin user
        $response = $this->actingAs($admin)->get('/dashboard');
        $response->assertRedirect('/admin/dashboard');
    }
}
