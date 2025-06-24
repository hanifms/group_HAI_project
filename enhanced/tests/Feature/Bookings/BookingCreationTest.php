<?php

namespace Tests\Feature\Bookings;

use App\Models\Booking;
use App\Models\Role;
use App\Models\TravelPackage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingCreationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that authenticated users can book a travel package.
     */
    public function test_authenticated_users_can_book_travel_package(): void
    {
        // Create roles
        Role::create(['name' => 'user']);
        Role::create(['name' => 'admin']);

        // Create a user
        $user = User::factory()->withUserRole()->create();

        // Create a travel package with available slots
        $package = TravelPackage::factory()->create([
            'is_visible' => true,
            'available_slots' => 10,
        ]);

        // Act as the user and attempt to book the package
        $response = $this->actingAs($user)->post('/bookings', [
            'travel_package_id' => $package->id,
            'number_of_travelers' => 2,
        ]);

        // Assert the booking was created and we're redirected
        $response->assertRedirect();

        // Check that the booking exists in the database
        $this->assertDatabaseHas('bookings', [
            'user_id' => $user->id,
            'travel_package_id' => $package->id,
            'number_of_travelers' => 2,
            'status' => Booking::STATUS_PENDING,
        ]);

        // Make sure available slots are decreased
        $this->assertEquals(8, $package->fresh()->available_slots);
    }

    /**
     * Test that guests cannot book a travel package.
     */
    public function test_guests_cannot_book_travel_package(): void
    {
        // Create a travel package
        $package = TravelPackage::factory()->create([
            'is_visible' => true,
        ]);

        // Attempt to book the package as a guest
        $response = $this->post('/bookings', [
            'travel_package_id' => $package->id,
            'number_of_travelers' => 2,
        ]);

        // Assert we're redirected to login
        $response->assertRedirect('/login');

        // Check that the booking doesn't exist
        $this->assertDatabaseMissing('bookings', [
            'travel_package_id' => $package->id,
            'number_of_travelers' => 2,
        ]);
    }

    /**
     * Test that users cannot book more travelers than available slots.
     */
    public function test_cannot_book_more_travelers_than_available_slots(): void
    {
        // Create roles
        Role::create(['name' => 'user']);
        Role::create(['name' => 'admin']);

        // Create a user
        $user = User::factory()->withUserRole()->create();

        // Create a travel package with limited slots
        $package = TravelPackage::factory()->create([
            'is_visible' => true,
            'available_slots' => 3,
        ]);

        // Act as the user and attempt to book more travelers than available slots
        $response = $this->actingAs($user)->post('/bookings', [
            'travel_package_id' => $package->id,
            'number_of_travelers' => 5,
        ]);

        // Assert the response has validation errors
        $response->assertSessionHasErrors('number_of_travelers');

        // Check that the booking doesn't exist
        $this->assertDatabaseMissing('bookings', [
            'user_id' => $user->id,
            'travel_package_id' => $package->id,
        ]);
    }

    /**
     * Test that users cannot book a travel package with zero slots.
     */
    public function test_cannot_book_sold_out_travel_package(): void
    {
        // Create roles
        Role::create(['name' => 'user']);
        Role::create(['name' => 'admin']);

        // Create a user
        $user = User::factory()->withUserRole()->create();

        // Create a sold out travel package
        $package = TravelPackage::factory()->soldOut()->create([
            'is_visible' => true,
        ]);

        // Act as the user and attempt to book the package
        $response = $this->actingAs($user)->post('/bookings', [
            'travel_package_id' => $package->id,
            'number_of_travelers' => 1,
        ]);

        // Assert the response has validation errors
        $response->assertSessionHasErrors('travel_package_id');

        // Check that the booking doesn't exist
        $this->assertDatabaseMissing('bookings', [
            'user_id' => $user->id,
            'travel_package_id' => $package->id,
        ]);
    }
}
