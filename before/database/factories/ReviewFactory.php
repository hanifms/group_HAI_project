<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'booking_id' => Booking::factory()->completed(),
            'rating' => fake()->numberBetween(1, 5),
            'comment' => fake()->paragraph(),
            'review_date' => fake()->dateTimeBetween('-1 month', 'now'),
        ];
    }

    /**
     * Create a review with a specific rating.
     *
     * @param int $rating
     * @return static
     */
    public function withRating(int $rating): static
    {
        return $this->state(fn (array $attributes) => [
            'rating' => max(1, min(5, $rating)),
        ]);
    }
}
