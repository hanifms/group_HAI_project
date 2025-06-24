<?php

namespace Tests\Unit\Models;

use App\Models\Booking;
use App\Models\Review;
use App\Models\TravelPackage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the user relationship works correctly.
     */
    public function test_booking_belongs_to_user(): void
    {
        // Create user and booking
        $user = User::factory()->create();
        $booking = Booking::factory()->create(['user_id' => $user->id]);

        // Test the relationship
        $this->assertEquals($user->id, $booking->user->id);
    }

    /**
     * Test the travel package relationship works correctly.
     */
    public function test_booking_belongs_to_travel_package(): void
    {
        // Create travel package and booking
        $travelPackage = TravelPackage::factory()->create();
        $booking = Booking::factory()->create(['travel_package_id' => $travelPackage->id]);

        // Test the relationship
        $this->assertEquals($travelPackage->id, $booking->travelPackage->id);
    }

    /**
     * Test the review relationship works correctly.
     */
    public function test_booking_has_one_review(): void
    {
        // Create booking and review
        $booking = Booking::factory()->completed()->create();
        $review = Review::factory()->create(['booking_id' => $booking->id]);

        // Test the relationship
        $this->assertEquals($review->id, $booking->review->id);
    }

    /**
     * Test isEligibleForReview returns true for completed bookings without reviews.
     */
    public function test_is_eligible_for_review_returns_true_for_completed_booking_without_review(): void
    {
        // Create completed booking without a review
        $booking = Booking::factory()->completed()->create();

        // Test the method
        $this->assertTrue($booking->isEligibleForReview());
    }

    /**
     * Test isEligibleForReview returns false for completed bookings with reviews.
     */
    public function test_is_eligible_for_review_returns_false_for_completed_booking_with_review(): void
    {
        // Create completed booking with a review
        $booking = Booking::factory()->completed()->create();
        $review = Review::factory()->create([
            'booking_id' => $booking->id,
            'user_id' => $booking->user_id
        ]);

        // Test the method
        $this->assertFalse($booking->isEligibleForReview());
    }

    /**
     * Test isEligibleForReview returns false for non-completed bookings.
     */
    public function test_is_eligible_for_review_returns_false_for_non_completed_booking(): void
    {
        // Create non-completed booking
        $booking = Booking::factory()->confirmed()->create();

        // Test the method
        $this->assertFalse($booking->isEligibleForReview());
    }
}
