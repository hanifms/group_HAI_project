<?php

namespace Tests\Feature\Admin;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminUserManagementTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Setup roles and users before tests.
     */
    private function setupAdminAndRoles()
    {
        // Create roles
        Role::create(['name' => 'user']);
        Role::create(['name' => 'admin']);

        // Create an admin user
        return User::factory()->withAdminRole()->create();
    }

    /**
     * Test that admins can view all users.
     */
    public function test_admins_can_view_all_users(): void
    {
        $admin = $this->setupAdminAndRoles();

        // Create some regular users
        $user1 = User::factory()->withUserRole()->create(['name' => 'Test User One']);
        $user2 = User::factory()->withUserRole()->create(['name' => 'Test User Two']);

        // Act as admin and visit the users page
        $response = $this->actingAs($admin)->get('/admin/users');

        // Assert the response is successful
        $response->assertStatus(200);

        // Assert we can see both users
        $response->assertSee('Test User One');
        $response->assertSee('Test User Two');
        $response->assertSee($user1->email);
        $response->assertSee($user2->email);
    }

    /**
     * Test that admins can view user details.
     */
    public function test_admins_can_view_user_details(): void
    {
        $admin = $this->setupAdminAndRoles();

        // Create a regular user
        $user = User::factory()->withUserRole()->create([
            'name' => 'John Doe',
            'email' => 'john@example.com'
        ]);

        // Act as admin and visit the user details page
        $response = $this->actingAs($admin)->get('/admin/users/' . $user->id);

        // Assert the response is successful
        $response->assertStatus(200);

        // Assert we can see the user details
        $response->assertSee('John Doe');
        $response->assertSee('john@example.com');
    }

    /**
     * Test that admins can edit user details.
     */
    public function test_admins_can_edit_user_details(): void
    {
        $admin = $this->setupAdminAndRoles();

        // Create a regular user
        $user = User::factory()->withUserRole()->create([
            'name' => 'Original Name'
        ]);

        // Act as admin and visit the edit user page
        $response = $this->actingAs($admin)->get('/admin/users/' . $user->id . '/edit');

        // Assert the response is successful
        $response->assertStatus(200);

        // Update the user
        $updatedData = [
            'name' => 'Updated Name',
            'email' => $user->email, // Keep the same email
            'role_id' => $user->role_id, // Include the required role_id
        ];

        // Submit the form to update the user
        $response = $this->actingAs($admin)->put('/admin/users/' . $user->id, $updatedData);

        // Assert the redirect
        $response->assertRedirect();

        // Assert the user was updated in the database
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name'
        ]);
    }

    /**
     * Test that admins can change user roles.
     */
    public function test_admins_can_change_user_roles(): void
    {
        $admin = $this->setupAdminAndRoles();

        // Get the admin role ID
        $adminRole = Role::where('name', 'admin')->first();
        $userRole = Role::where('name', 'user')->first();

        // Create a regular user
        $user = User::factory()->withUserRole()->create();

        // Verify the user starts with the 'user' role
        $this->assertEquals($userRole->id, $user->role_id);

        // Act as admin and update the user's role
        $response = $this->actingAs($admin)->patch('/admin/users/' . $user->id . '/role', [
            'role_id' => $adminRole->id
        ]);

        // Assert the redirect
        $response->assertRedirect();

        // Assert the user's role was updated in the database
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'role_id' => $adminRole->id
        ]);

        // Refresh the user model
        $user->refresh();

        // Verify the user is now an admin
        $this->assertTrue($user->isAdmin());
    }

    /**
     * Test that admins can delete users.
     */
    public function test_admins_can_delete_users(): void
    {
        $admin = $this->setupAdminAndRoles();

        // Create a regular user
        $user = User::factory()->withUserRole()->create();

        // Act as admin and delete the user
        $response = $this->actingAs($admin)->delete('/admin/users/' . $user->id);

        // Assert the redirect
        $response->assertRedirect();

        // Assert the user was deleted from the database
        $this->assertDatabaseMissing('users', [
            'id' => $user->id
        ]);
    }

    /**
     * Test that admins cannot delete themselves.
     */
    public function test_admins_cannot_delete_themselves(): void
    {
        $admin = $this->setupAdminAndRoles();

        // Act as admin and try to delete themselves
        $response = $this->actingAs($admin)->delete('/admin/users/' . $admin->id);

        // Assert the response indicates an error
        $response->assertSessionHasErrors();

        // Assert the admin still exists in the database
        $this->assertDatabaseHas('users', [
            'id' => $admin->id
        ]);
    }

    /**
     * Test that regular users cannot access admin user management.
     */
    public function test_regular_users_cannot_access_admin_user_management(): void
    {
        // Create roles
        Role::create(['name' => 'user']);
        Role::create(['name' => 'admin']);

        // Create a regular user
        $user = User::factory()->withUserRole()->create();

        // Act as the user and try to access admin users page
        $response = $this->actingAs($user)->get('/admin/users');

        // Assert the user is redirected away from admin page
        $response->assertStatus(302);
    }
}
