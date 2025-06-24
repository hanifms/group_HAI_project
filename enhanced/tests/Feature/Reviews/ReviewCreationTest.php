<?php

namespace Tests\Feature\Reviews;

use App\Models\Booking;
use App\Models\Role;
use App\Models\TravelPackage;
use App\Models\User;
use App\Models\Review;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewCreationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Setup roles and users before tests.
     */
    private function setupUsersAndRoles()
    {
        // Create roles
        Role::create(['name' => 'user']);
        Role::create(['name' => 'admin']);

        // Create a user
        return User::factory()->withUserRole()->create();
    }

    /**
     * Test that users can submit a review for a completed booking.
     */
    public function test_users_can_submit_review_for_completed_booking(): void
    {
        $user = $this->setupUsersAndRoles();

        // Create a travel package
        $package = TravelPackage::factory()->create(['is_visible' => true]);

        // Create a completed booking for this user
        $booking = Booking::factory()->create([
            'user_id' => $user->id,
            'travel_package_id' => $package->id,
            'status' => 'completed',
            'number_of_travelers' => 2,
            'booking_date' => now()->subDays(30)
        ]);

        // Ensure the booking doesn't have a review yet
        $this->assertNull($booking->review);

        // Act as the user and visit the create review page
        $response = $this->actingAs($user)->get(route('reviews.create', $booking));

        // Assert the response is successful
        $response->assertStatus(200);

        // Submit a review
        $reviewData = [
            'rating' => 5,
            'comment' => 'This was an amazing experience!'
        ];

        $response = $this->actingAs($user)
            ->post(route('reviews.store', $booking), $reviewData);

        // Assert the user is redirected to the booking page
        $response->assertRedirect(route('bookings.show', $booking));

        // Assert that the review was created in the database
        $this->assertDatabaseHas('reviews', [
            'booking_id' => $booking->id,
            'user_id' => $user->id,
            'rating' => 5,
            'comment' => 'This was an amazing experience!'
        ]);

        // Reload the booking and check if it now has a review
        $booking->refresh();
        $this->assertNotNull($booking->review);
    }

    /**
     * Test that users cannot submit a review for a pending booking.
     */
    public function test_users_cannot_submit_review_for_pending_booking(): void
    {
        $user = $this->setupUsersAndRoles();

        // Create a travel package
        $package = TravelPackage::factory()->create(['is_visible' => true]);

        // Create a pending booking for this user
        $booking = Booking::factory()->create([
            'user_id' => $user->id,
            'travel_package_id' => $package->id,
            'status' => 'pending', // Important: This is not completed
            'number_of_travelers' => 2,
            'booking_date' => now()->subDays(5)
        ]);

        // Act as the user and try to visit the create review page
        $response = $this->actingAs($user)->get(route('reviews.create', $booking));

        // Assert the user is redirected away (can't leave a review)
        $response->assertStatus(302);

        // Try to submit a review anyway (this tests the server-side validation)
        $reviewData = [
            'rating' => 4,
            'comment' => 'Not completed yet but trying to review'
        ];

        $response = $this->actingAs($user)
            ->post(route('reviews.store', $booking), $reviewData);

        // Assert the user is redirected without creating a review
        $response->assertStatus(302);

        // Assert that no review was created in the database
        $this->assertDatabaseMissing('reviews', [
            'booking_id' => $booking->id
        ]);
    }

    /**
     * Test that users cannot submit a review for a booking that already has a review.
     */
    public function test_users_cannot_submit_multiple_reviews_for_same_booking(): void
    {
        $user = $this->setupUsersAndRoles();

        // Create a travel package
        $package = TravelPackage::factory()->create(['is_visible' => true]);

        // Create a completed booking for this user
        $booking = Booking::factory()->create([
            'user_id' => $user->id,
            'travel_package_id' => $package->id,
            'status' => 'completed',
            'number_of_travelers' => 2,
            'booking_date' => now()->subDays(30)
        ]);

        // Create a review for this booking
        Review::factory()->create([
            'user_id' => $user->id,
            'booking_id' => $booking->id,
            'rating' => 5,
            'comment' => 'Already reviewed',
            'review_date' => now()->subDays(2)
        ]);

        // Act as the user and try to visit the create review page
        $response = $this->actingAs($user)->get(route('reviews.create', $booking));

        // Assert the user is redirected away (already has a review)
        $response->assertStatus(302);

        // Try to submit another review anyway
        $reviewData = [
            'rating' => 3,
            'comment' => 'Trying to submit a second review'
        ];

        $response = $this->actingAs($user)
            ->post(route('reviews.store', $booking), $reviewData);

        // Assert the user is redirected without creating a new review
        $response->assertStatus(302);

        // Assert that there is still only one review for this booking
        $this->assertEquals(1, Review::where('booking_id', $booking->id)->count());
    }
}
