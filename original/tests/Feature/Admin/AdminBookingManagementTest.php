<?php

namespace Tests\Feature\Admin;

use App\Models\Booking;
use App\Models\Role;
use App\Models\TravelPackage;
use App\Models\User;
use App\Models\Review;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminBookingManagementTest extends TestCase
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
     * Test that admins can view all bookings.
     */
    public function test_admins_can_view_all_bookings(): void
    {
        $admin = $this->setupAdminAndRoles();

        // Create some regular users
        $user1 = User::factory()->withUserRole()->create();
        $user2 = User::factory()->withUserRole()->create();

        // Create travel packages
        $package1 = TravelPackage::factory()->create(['is_visible' => true]);
        $package2 = TravelPackage::factory()->create(['is_visible' => true]);

        // Create bookings for different users
        $booking1 = Booking::factory()->create([
            'user_id' => $user1->id,
            'travel_package_id' => $package1->id,
            'status' => 'pending'
        ]);

        $booking2 = Booking::factory()->create([
            'user_id' => $user2->id,
            'travel_package_id' => $package2->id,
            'status' => 'confirmed'
        ]);

        // Act as admin and visit the bookings page
        $response = $this->actingAs($admin)->get('/admin/bookings');

        // Assert the response is successful
        $response->assertStatus(200);

        // Assert we can see both bookings
        $response->assertSee('pending');
        $response->assertSee('confirmed');
        $response->assertSee($user1->name);
        $response->assertSee($user2->name);
        $response->assertSee($package1->name);
        $response->assertSee($package2->name);
    }

    /**
     * Test that admins can update booking status.
     */
    public function test_admins_can_update_booking_status(): void
    {
        $admin = $this->setupAdminAndRoles();

        // Create a regular user
        $user = User::factory()->withUserRole()->create();

        // Create a travel package
        $package = TravelPackage::factory()->create(['is_visible' => true]);

        // Create a pending booking
        $booking = Booking::factory()->create([
            'user_id' => $user->id,
            'travel_package_id' => $package->id,
            'status' => 'pending'
        ]);

        // Act as admin and update the booking status
        $response = $this->actingAs($admin)
            ->patch('/admin/bookings/' . $booking->id, [
                'status' => 'confirmed'
            ]);

        // Assert the redirect
        $response->assertRedirect();

        // Assert the booking status was updated in the database
        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'confirmed'
        ]);
    }

    /**
     * Test that admins cannot change status of completed bookings with reviews.
     */
    public function test_admins_cannot_change_status_of_completed_bookings_with_reviews(): void
    {
        $admin = $this->setupAdminAndRoles();

        // Create a regular user
        $user = User::factory()->withUserRole()->create();

        // Create a travel package
        $package = TravelPackage::factory()->create(['is_visible' => true]);

        // Create a completed booking
        $booking = Booking::factory()->create([
            'user_id' => $user->id,
            'travel_package_id' => $package->id,
            'status' => 'completed'
        ]);

        // Add a review to this booking
        Review::factory()->create([
            'user_id' => $user->id,
            'booking_id' => $booking->id,
            'rating' => 5,
            'comment' => 'Great experience!'
        ]);

        // Act as admin and try to update the booking status
        $response = $this->actingAs($admin)
            ->patch('/admin/bookings/' . $booking->id, [
                'status' => 'confirmed'
            ]);

        // Assert the response indicates an error
        $response->assertSessionHasErrors();

        // Assert the booking status was not changed
        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'completed'
        ]);
    }

    /**
     * Test that regular users cannot access admin booking management.
     */
    public function test_regular_users_cannot_access_admin_booking_management(): void
    {
        // Create roles
        Role::create(['name' => 'user']);
        Role::create(['name' => 'admin']);

        // Create a regular user
        $user = User::factory()->withUserRole()->create();

        // Act as the user and try to access admin booking page
        $response = $this->actingAs($user)->get('/admin/bookings');

        // Assert the user is redirected away from admin page
        $response->assertStatus(302);

        // Try to update a booking status
        $response = $this->actingAs($user)
            ->patch('/admin/bookings/1', [
                'status' => 'confirmed'
            ]);

        // Assert the response status - could be 404 or 302 depending on middleware implementation
        $response->assertStatus(404); // The route doesn't exist for non-admin users
    }
}
