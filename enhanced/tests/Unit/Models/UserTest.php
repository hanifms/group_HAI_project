<?php

namespace Tests\Unit\Models;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that isAdmin returns true for admin users.
     */
    public function test_is_admin_returns_true_for_admin_users(): void
    {
        // Create roles
        $adminRole = Role::create(['name' => 'admin']);
        $userRole = Role::create(['name' => 'user']);

        // Create an admin user
        $admin = User::factory()->create(['role_id' => $adminRole->id]);

        // Check that isAdmin returns true
        $this->assertTrue($admin->isAdmin());
    }

    /**
     * Test that isAdmin returns false for regular users.
     */
    public function test_is_admin_returns_false_for_regular_users(): void
    {
        // Create roles
        $adminRole = Role::create(['name' => 'admin']);
        $userRole = Role::create(['name' => 'user']);

        // Create a regular user
        $user = User::factory()->create(['role_id' => $userRole->id]);

        // Check that isAdmin returns false
        $this->assertFalse($user->isAdmin());
    }

    /**
     * Test that user has many bookings relationship.
     */
    public function test_user_has_many_bookings(): void
    {
        // Create user and bookings
        $user = User::factory()->create();
        $booking = \App\Models\Booking::factory()->create(['user_id' => $user->id]);

        // Test the relationship
        $this->assertTrue($user->bookings->contains($booking));
        $this->assertEquals(1, $user->bookings->count());
    }

    /**
     * Test that user has many reviews relationship.
     */
    public function test_user_has_many_reviews(): void
    {
        // Create user and reviews
        $user = User::factory()->create();
        $booking = \App\Models\Booking::factory()->completed()->create(['user_id' => $user->id]);
        $review = \App\Models\Review::factory()->create([
            'user_id' => $user->id,
            'booking_id' => $booking->id
        ]);

        // Test the relationship
        $this->assertTrue($user->reviews->contains($review));
        $this->assertEquals(1, $user->reviews->count());
    }
}
