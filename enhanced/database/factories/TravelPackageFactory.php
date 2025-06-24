<?php

namespace Database\Factories;

use App\Models\TravelPackage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TravelPackage>
 */
class TravelPackageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('+1 week', '+1 month');
        $endDate = fake()->dateTimeBetween($startDate, '+2 months');

        return [
            'name' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'destination' => fake()->city() . ', ' . fake()->country(),
            'price' => fake()->randomFloat(2, 100, 5000),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'available_slots' => fake()->numberBetween(5, 50),
            'is_visible' => true,
        ];
    }

    /**
     * Indicate that the travel package is not visible.
     */
    public function hidden(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_visible' => false,
        ]);
    }

    /**
     * Indicate that the travel package has limited slots.
     */
    public function limitedAvailability(): static
    {
        return $this->state(fn (array $attributes) => [
            'available_slots' => fake()->numberBetween(1, 5),
        ]);
    }

    /**
     * Indicate that the travel package has no available slots.
     */
    public function soldOut(): static
    {
        return $this->state(fn (array $attributes) => [
            'available_slots' => 0,
        ]);
    }
}
