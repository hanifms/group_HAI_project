<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Review;
use App\Models\TravelPackage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index(): View
    {
        // Get counts for dashboard statistics
        $userCount = User::count();
        $packageCount = TravelPackage::count();
        $activeBookingsCount = Booking::whereIn('status', ['pending', 'ongoing'])->count();
        $reviewCount = Review::count();

        // Get recent bookings for the activity feed
        $recentBookings = Booking::with(['user', 'travelPackage'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'userCount',
            'packageCount',
            'activeBookingsCount',
            'reviewCount',
            'recentBookings'
        ));
    }
}
