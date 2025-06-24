<?php

namespace Tests\Feature\Reviews;

use App\Models\Booking;
use App\Models\Role;
use App\Models\TravelPackage;
use App\Models\User;
use App\Models\Review;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewListingTest extends TestCase
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
     * Test that users can view their own reviews.
     */
    public function test_users_can_view_their_own_reviews(): void
    {
        $user = $this->setupUsersAndRoles();

        // Create a travel package
        $package = TravelPackage::factory()->create(['is_visible' => true]);

        // Create completed bookings for this user
        $booking1 = Booking::factory()->create([
            'user_id' => $user->id,
            'travel_package_id' => $package->id,
            'status' => 'completed'
        ]);

        $booking2 = Booking::factory()->create([
            'user_id' => $user->id,
            'travel_package_id' => $package->id,
            'status' => 'completed'
        ]);

        // Create reviews for these bookings
        $review1 = Review::factory()->create([
            'user_id' => $user->id,
            'booking_id' => $booking1->id,
            'rating' => 5,
            'comment' => 'First amazing experience!'
        ]);

        $review2 = Review::factory()->create([
            'user_id' => $user->id,
            'booking_id' => $booking2->id,
            'rating' => 4,
            'comment' => 'Second good experience!'
        ]);

        // Act as the user and visit the my reviews page
        $response = $this->actingAs($user)->get(route('reviews.index'));

        // Assert the response is successful
        $response->assertStatus(200);

        // Assert that we can see both review comments
        $response->assertSee('First amazing experience!');
        $response->assertSee('Second good experience!');

        // Assert that we can see the package names
        $response->assertSee($package->name);
    }

    /**
     * Test that users cannot view other users' reviews directly (through URL manipulation).
     */
    public function test_users_cannot_access_other_users_reviews_directly(): void
    {
        $user1 = $this->setupUsersAndRoles();
        $user2 = User::factory()->withUserRole()->create();

        // Create a travel package
        $package = TravelPackage::factory()->create(['is_visible' => true]);

        // Create a completed booking for the second user
        $booking = Booking::factory()->create([
            'user_id' => $user2->id,
            'travel_package_id' => $package->id,
            'status' => 'completed'
        ]);

        // Create a review for this booking
        $review = Review::factory()->create([
            'user_id' => $user2->id,
            'booking_id' => $booking->id,
            'rating' => 3,
            'comment' => 'Private review from user 2'
        ]);

        // Act as the first user and try to access the review directly by ID
        // This assumes there's a route to directly view a review by ID
        // If there isn't such a route, this test might need adjusting
        $response = $this->actingAs($user1)->get('/reviews/' . $review->id);

        // Assert the user gets redirected (in our case it's a 302 redirect to login when unauthorized)
        $response->assertStatus(302);
    }

    /**
     * Test that admins can view all reviews in the admin panel.
     */
    public function test_admins_can_view_all_reviews(): void
    {
        // Create a regular user
        $user = $this->setupUsersAndRoles();

        // Create an admin user
        $admin = User::factory()->withAdminRole()->create();

        // Create a travel package
        $package = TravelPackage::factory()->create(['is_visible' => true]);

        // Create a completed booking for the regular user
        $booking = Booking::factory()->create([
            'user_id' => $user->id,
            'travel_package_id' => $package->id,
            'status' => 'completed'
        ]);

        // Create a review for this booking
        $review = Review::factory()->create([
            'user_id' => $user->id,
            'booking_id' => $booking->id,
            'rating' => 5,
            'comment' => 'This was the best trip ever!'
        ]);

        // Act as the admin and visit the admin reviews page
        $response = $this->actingAs($admin)->get('/admin/reviews');

        // Assert the response is successful
        $response->assertStatus(200);

        // Assert that the admin can see the review details
        $response->assertSee('This was the best trip ever!');
        $response->assertSee($user->name); // The reviewer's name should be visible
        $response->assertSee($package->name); // The package name should be visible
    }

    /**
     * Test that reviews appear on travel package details page.
     */
    public function test_reviews_appear_on_travel_package_details_page(): void
    {
        $user = $this->setupUsersAndRoles();

        // Create a travel package
        $package = TravelPackage::factory()->create([
            'is_visible' => true,
            'name' => 'Wonderful Paris Trip'
        ]);

        // Create completed bookings for this user
        $booking = Booking::factory()->create([
            'user_id' => $user->id,
            'travel_package_id' => $package->id,
            'status' => 'completed'
        ]);

        // Create a review for this booking
        Review::factory()->create([
            'user_id' => $user->id,
            'booking_id' => $booking->id,
            'rating' => 5,
            'comment' => 'Paris was magical!'
        ]);

        // Act as a guest and visit the package details page
        $response = $this->get('/travel-packages/' . $package->id);

        // Assert the response is successful
        $response->assertStatus(200);

        // Assert that we can see the review
        $response->assertSee('Paris was magical!');

        // Assert that we can see the rating
        $response->assertSee('5'); // Assuming the rating is displayed as a number
    }
}
