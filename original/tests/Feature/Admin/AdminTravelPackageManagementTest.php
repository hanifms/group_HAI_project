<?php

namespace Tests\Feature\Admin;

use App\Models\Booking;
use App\Models\Role;
use App\Models\TravelPackage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminTravelPackageManagementTest extends TestCase
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
     * Test that admins can create new travel packages.
     */
    public function test_admins_can_create_new_travel_packages(): void
    {
        $admin = $this->setupAdminAndRoles();

        // Act as admin and visit the create travel package page
        $response = $this->actingAs($admin)->get('/admin/travel-packages/create');

        // Assert the response is successful
        $response->assertStatus(200);

        // Create a new travel package
        $packageData = [
            'name' => 'New Zealand Adventure',
            'description' => 'Explore the beautiful landscapes of New Zealand',
            'destination' => 'New Zealand',
            'price' => 2500.00,
            'duration' => 10,
            'available_slots' => 20,
            'start_date' => now()->addMonths(2)->format('Y-m-d'),
            'end_date' => now()->addMonths(2)->addDays(10)->format('Y-m-d'),
            'is_visible' => true
        ];

        // Submit the form to create a new package
        $response = $this->actingAs($admin)->post('/admin/travel-packages', $packageData);

        // Assert the redirect
        $response->assertRedirect();

        // Assert the travel package was created in the database
        $this->assertDatabaseHas('travel_packages', [
            'name' => 'New Zealand Adventure',
            'destination' => 'New Zealand',
            'price' => 2500.00,
            'is_visible' => 1
        ]);
    }

    /**
     * Test that admins can update existing travel packages.
     */
    public function test_admins_can_update_travel_packages(): void
    {
        $admin = $this->setupAdminAndRoles();

        // Create a travel package
        $package = TravelPackage::factory()->create([
            'name' => 'Original Package Name',
            'description' => 'Original description',
            'is_visible' => true
        ]);

        // Act as admin and visit the edit travel package page
        $response = $this->actingAs($admin)->get('/admin/travel-packages/' . $package->id . '/edit');

        // Assert the response is successful
        $response->assertStatus(200);

        // Update the travel package
        $updatedData = [
            'name' => 'Updated Package Name',
            'description' => 'Updated description',
            'destination' => $package->destination,
            'price' => $package->price,
            'duration' => $package->duration,
            'available_slots' => $package->available_slots,
            'start_date' => $package->start_date->format('Y-m-d'),
            'end_date' => $package->end_date->format('Y-m-d'),
            'is_visible' => false
        ];

        // Submit the form to update the package
        $response = $this->actingAs($admin)->put('/admin/travel-packages/' . $package->id, $updatedData);

        // Assert the redirect
        $response->assertRedirect();

        // Assert the travel package was updated in the database
        $this->assertDatabaseHas('travel_packages', [
            'id' => $package->id,
            'name' => 'Updated Package Name',
            'description' => 'Updated description',
            'is_visible' => 0
        ]);
    }

    /**
     * Test that admins can toggle visibility of travel packages.
     */
    public function test_admins_can_toggle_travel_package_visibility(): void
    {
        $admin = $this->setupAdminAndRoles();

        // Create a visible travel package
        $package = TravelPackage::factory()->create([
            'is_visible' => true
        ]);

        // Act as admin and toggle visibility
        $response = $this->actingAs($admin)->patch('/admin/travel-packages/' . $package->id . '/toggle-visibility');

        // Assert the redirect
        $response->assertRedirect();

        // Assert the travel package visibility was toggled
        $this->assertDatabaseHas('travel_packages', [
            'id' => $package->id,
            'is_visible' => 0
        ]);

        // Toggle it back
        $response = $this->actingAs($admin)->patch('/admin/travel-packages/' . $package->id . '/toggle-visibility');

        // Assert the travel package visibility was toggled back
        $this->assertDatabaseHas('travel_packages', [
            'id' => $package->id,
            'is_visible' => 1
        ]);
    }

    /**
     * Test that admins cannot delete travel packages with active bookings.
     */
    public function test_admins_cannot_delete_travel_packages_with_active_bookings(): void
    {
        $admin = $this->setupAdminAndRoles();

        // Create a user
        $user = User::factory()->withUserRole()->create();

        // Create a travel package
        $package = TravelPackage::factory()->create(['is_visible' => true]);

        // Create an active booking for this package
        $booking = Booking::factory()->create([
            'user_id' => $user->id,
            'travel_package_id' => $package->id,
            'status' => 'confirmed' // Active booking
        ]);

        // Act as admin and try to delete the package
        $response = $this->actingAs($admin)->delete('/admin/travel-packages/' . $package->id);

        // Assert the response indicates an error
        $response->assertSessionHasErrors();

        // Assert the package still exists in the database
        $this->assertDatabaseHas('travel_packages', [
            'id' => $package->id
        ]);
    }

    /**
     * Test that admins can delete travel packages without active bookings.
     */
    public function test_admins_can_delete_travel_packages_without_active_bookings(): void
    {
        $admin = $this->setupAdminAndRoles();

        // Create a travel package without bookings
        $package = TravelPackage::factory()->create(['is_visible' => true]);

        // Act as admin and delete the package
        $response = $this->actingAs($admin)->delete('/admin/travel-packages/' . $package->id);

        // Assert the redirect
        $response->assertRedirect();

        // Assert the package was deleted from the database
        $this->assertDatabaseMissing('travel_packages', [
            'id' => $package->id
        ]);
    }
}
