<?php

namespace Tests\Unit\Models;

use App\Models\Booking;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that review belongs to user relationship works correctly.
     */
    public function test_review_belongs_to_user(): void
    {
        // Create user and review
        $user = User::factory()->create();
        $booking = Booking::factory()->completed()->create(['user_id' => $user->id]);
        $review = Review::factory()->create([
            'user_id' => $user->id,
            'booking_id' => $booking->id
        ]);

        // Test the relationship
        $this->assertEquals($user->id, $review->user->id);
    }

    /**
     * Test that review belongs to booking relationship works correctly.
     */
    public function test_review_belongs_to_booking(): void
    {
        // Create booking and review
        $booking = Booking::factory()->completed()->create();
        $review = Review::factory()->create([
            'booking_id' => $booking->id,
            'user_id' => $booking->user_id
        ]);

        // Test the relationship
        $this->assertEquals($booking->id, $review->booking->id);
    }

    /**
     * Test that review_date is cast as datetime.
     */
    public function test_review_date_is_cast_as_datetime(): void
    {
        // Create review
        $review = Review::factory()->create();

        // Test that review_date is a Carbon instance
        $this->assertInstanceOf(\Carbon\Carbon::class, $review->review_date);
    }
}
