<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\TravelPackage;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
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
            'travel_package_id' => TravelPackage::factory(),
            'booking_date' => fake()->dateTimeBetween('-1 month', 'now'),
            'status' => fake()->randomElement([
                Booking::STATUS_PENDING,
                Booking::STATUS_CONFIRMED,
                Booking::STATUS_ON_HOLD,
                Booking::STATUS_ONGOING,
                Booking::STATUS_COMPLETED,
                Booking::STATUS_CANCELLED,
            ]),
            'number_of_travelers' => fake()->numberBetween(1, 5),
        ];
    }

    /**
     * Indicate that the booking is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Booking::STATUS_PENDING,
        ]);
    }

    /**
     * Indicate that the booking is confirmed.
     */
    public function confirmed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Booking::STATUS_CONFIRMED,
        ]);
    }

    /**
     * Indicate that the booking is completed.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Booking::STATUS_COMPLETED,
        ]);
    }

    /**
     * Indicate that the booking is cancelled.
     */
    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Booking::STATUS_CANCELLED,
        ]);
    }
}
