<?php

namespace Tests\Feature\TravelPackages;

use App\Models\Role;
use App\Models\TravelPackage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TravelPackageListingTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that users can view the list of travel packages.
     */
    public function test_users_can_view_travel_packages_list(): void
    {
        // Create roles
        Role::create(['name' => 'user']);
        Role::create(['name' => 'admin']);

        // Create a user
        $user = User::factory()->withUserRole()->create();

        // Create some travel packages
        $visiblePackage = TravelPackage::factory()->create(['is_visible' => true]);
        $hiddenPackage = TravelPackage::factory()->hidden()->create();

        // Act as the user and visit the travel packages page
        $response = $this->actingAs($user)->get('/travel-packages');

        // Assert the response is successful
        $response->assertStatus(200);

        // Assert that we can see the visible package but not the hidden one
        $response->assertSee($visiblePackage->name);
        $response->assertDontSee($hiddenPackage->name);
    }

    /**
     * Test that guests can view the list of travel packages.
     */
    public function test_guests_can_view_travel_packages_list(): void
    {
        // Create some travel packages
        $visiblePackage = TravelPackage::factory()->create(['is_visible' => true]);
        $hiddenPackage = TravelPackage::factory()->hidden()->create();

        // Visit the travel packages page as a guest
        $response = $this->get('/travel-packages');

        // Assert the response is successful
        $response->assertStatus(200);

        // Assert that we can see the visible package but not the hidden one
        $response->assertSee($visiblePackage->name);
        $response->assertDontSee($hiddenPackage->name);
    }

    /**
     * Test that admins can view all travel packages including hidden ones.
     */
    public function test_admins_can_view_all_travel_packages(): void
    {
        // Create roles
        Role::create(['name' => 'user']);
        Role::create(['name' => 'admin']);

        // Create an admin
        $admin = User::factory()->withAdminRole()->create();

        // Create some travel packages
        $visiblePackage = TravelPackage::factory()->create(['is_visible' => true]);
        $hiddenPackage = TravelPackage::factory()->hidden()->create();

        // Act as the admin and visit the admin travel packages page
        $response = $this->actingAs($admin)->get('/admin/travel-packages');

        // Assert the response is successful
        $response->assertStatus(200);

        // Assert that we can see both visible and hidden packages
        $response->assertSee($visiblePackage->name);
        $response->assertSee($hiddenPackage->name);
    }
}
