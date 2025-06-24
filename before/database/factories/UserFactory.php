<?php

namespace Database\Factories;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'remember_token' => Str::random(10),
            'profile_photo_path' => null,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Assign the user role to the user.
     */
    public function withUserRole(): static
    {
        return $this->state(function () {
            $userRole = Role::where('name', 'user')->first();

            return [
                'role_id' => $userRole ? $userRole->id : null,
            ];
        });
    }

    /**
     * Assign the admin role to the user.
     */
    public function withAdminRole(): static
    {
        return $this->state(function () {
            $adminRole = Role::where('name', 'admin')->first();

            return [
                'role_id' => $adminRole ? $adminRole->id : null,
            ];
        });
    }
}
