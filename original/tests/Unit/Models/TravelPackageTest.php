<?php

namespace Tests\Unit\Models;

use App\Models\Booking;
use App\Models\TravelPackage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TravelPackageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that travel package has many bookings relationship.
     */
    public function test_travel_package_has_many_bookings(): void
    {
        // Create travel package and bookings
        $travelPackage = TravelPackage::factory()->create();
        $booking = Booking::factory()->create(['travel_package_id' => $travelPackage->id]);

        // Test the relationship
        $this->assertTrue($travelPackage->bookings->contains($booking));
        $this->assertEquals(1, $travelPackage->bookings->count());
    }

    /**
     * Test the hasActiveBookings method returns true when there are non-completed bookings.
     */
    public function test_has_active_bookings_returns_true_with_active_bookings(): void
    {
        // Create travel package and active booking
        $travelPackage = TravelPackage::factory()->create();
        $booking = Booking::factory()->confirmed()->create(['travel_package_id' => $travelPackage->id]);

        // Test the method
        $this->assertTrue($travelPackage->hasActiveBookings());
    }

    /**
     * Test the hasActiveBookings method returns false when there are only completed or cancelled bookings.
     */
    public function test_has_active_bookings_returns_false_with_only_completed_or_cancelled_bookings(): void
    {
        // Create travel package and completed/cancelled bookings
        $travelPackage = TravelPackage::factory()->create();
        $completedBooking = Booking::factory()->completed()->create(['travel_package_id' => $travelPackage->id]);
        $cancelledBooking = Booking::factory()->cancelled()->create(['travel_package_id' => $travelPackage->id]);

        // Test the method
        $this->assertFalse($travelPackage->hasActiveBookings());
    }

    /**
     * Test the scopeVisible method only returns visible travel packages.
     */
    public function test_scope_visible_only_returns_visible_packages(): void
    {
        // Create visible and hidden travel packages
        $visiblePackage = TravelPackage::factory()->create(['is_visible' => true]);
        $hiddenPackage = TravelPackage::factory()->create(['is_visible' => false]);

        // Test the scope
        $visiblePackages = TravelPackage::visible()->get();

        $this->assertTrue($visiblePackages->contains($visiblePackage));
        $this->assertFalse($visiblePackages->contains($hiddenPackage));
    }
}
