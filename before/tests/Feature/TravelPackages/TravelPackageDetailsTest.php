<?php

namespace Tests\Feature\TravelPackages;

use App\Models\Role;
use App\Models\TravelPackage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TravelPackageDetailsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that users can view a visible travel package's details.
     */
    public function test_users_can_view_visible_travel_package_details(): void
    {
        // Create roles
        Role::create(['name' => 'user']);
        Role::create(['name' => 'admin']);

        // Create a user
        $user = User::factory()->withUserRole()->create();

        // Create a visible travel package
        $package = TravelPackage::factory()->create([
            'is_visible' => true,
            'name' => 'Amazing Trip to Japan',
            'description' => 'Experience the beauty of Japan',
            'destination' => 'Tokyo, Japan',
            'price' => 1500.00
        ]);

        // Act as the user and visit the travel package details page
        $response = $this->actingAs($user)->get('/travel-packages/' . $package->id);

        // Assert the response is successful
        $response->assertStatus(200);

        // Assert that we can see the package details
        $response->assertSee('Amazing Trip to Japan');
        $response->assertSee('Experience the beauty of Japan');
        $response->assertSee('Tokyo, Japan');
        $response->assertSee('1,500.00'); // Price should be formatted with commas
    }

    /**
     * Test that users cannot view a hidden travel package's details.
     */
    public function test_users_cannot_view_hidden_travel_package_details(): void
    {
        // Create roles
        Role::create(['name' => 'user']);
        Role::create(['name' => 'admin']);

        // Create a user
        $user = User::factory()->withUserRole()->create();

        // Create a hidden travel package
        $package = TravelPackage::factory()->hidden()->create();

        // Act as the user and visit the hidden travel package details page
        $response = $this->actingAs($user)->get('/travel-packages/' . $package->id);

        // Assert the response is a redirect or not found
        $response->assertStatus(404);
    }

    /**
     * Test that admins can view details of any travel package, even hidden ones.
     */
    public function test_admins_can_view_hidden_travel_package_details(): void
    {
        // Create roles
        Role::create(['name' => 'user']);
        Role::create(['name' => 'admin']);

        // Create an admin
        $admin = User::factory()->withAdminRole()->create();

        // Create a hidden travel package
        $package = TravelPackage::factory()->hidden()->create([
            'name' => 'Secret Trip to Antarctica',
            'description' => 'Exclusive journey to the frozen continent',
        ]);

        // Act as the admin and visit the admin travel package details page
        $response = $this->actingAs($admin)->get('/admin/travel-packages/' . $package->id);

        // Assert the response is successful
        $response->assertStatus(200);

        // Assert that we can see the hidden package details
        $response->assertSee('Secret Trip to Antarctica');
        $response->assertSee('Exclusive journey to the frozen continent');
    }
}
