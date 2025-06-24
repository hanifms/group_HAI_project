<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingController extends Controller
{
    /**
     * Display a listing of all bookings.
     */
    public function index(): View
    {
        $bookings = Booking::with(['user', 'travelPackage'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.bookings.index', compact('bookings'));
    }

    /**
     * Display the specified booking.
     */
    public function show(Booking $booking): View
    {
        $booking->load(['user', 'travelPackage', 'review']);
        return view('admin.bookings.show', compact('booking'));
    }

    /**
     * Show the form for editing the booking status.
     */
    public function edit(Booking $booking): View
    {
        $statusOptions = [
            Booking::STATUS_PENDING => 'Pending',
            Booking::STATUS_CONFIRMED => 'Confirmed',
            Booking::STATUS_ON_HOLD => 'On Hold',
            Booking::STATUS_ONGOING => 'Ongoing',
            Booking::STATUS_COMPLETED => 'Completed',
            Booking::STATUS_CANCELLED => 'Cancelled'
        ];

        return view('admin.bookings.edit', compact('booking', 'statusOptions'));
    }

    /**
     * Update the booking status.
     */
    public function update(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => ['required', 'string', 'in:'.implode(',', [
                Booking::STATUS_PENDING,
                Booking::STATUS_CONFIRMED,
                Booking::STATUS_ON_HOLD,
                Booking::STATUS_ONGOING,
                Booking::STATUS_COMPLETED,
                Booking::STATUS_CANCELLED
            ])],
        ]);

        // Store the previous status for conditional checks
        $previousStatus = $booking->status;

        // Check if the booking is completed and has a review
        if ($previousStatus === Booking::STATUS_COMPLETED) {
            $hasReview = $booking->review()->exists();

            if ($hasReview && $request->status !== Booking::STATUS_COMPLETED) {
                return back()->withErrors(['status' => 'Cannot change status from "completed" when a review exists.']);
            }
        }

        // Update the booking status
        $booking->status = $request->status;
        $booking->save();

        // Additional business logic when a booking is marked as completed
        if ($request->status === Booking::STATUS_COMPLETED && $previousStatus !== Booking::STATUS_COMPLETED) {
            // The booking is now eligible for review
            // We could notify the user that they can leave a review
        }

        return redirect()->route('admin.bookings.index')
            ->with('success', 'Booking status updated successfully.');
    }

    /**
     * Filter bookings by status.
     */
    public function filter(Request $request)
    {
        $status = $request->status;
        $query = Booking::with(['user', 'travelPackage']);

        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        $bookings = $query->orderBy('created_at', 'desc')->paginate(10);
        $bookings->appends(['status' => $status]);

        return view('admin.bookings.index', compact('bookings', 'status'));
    }
}
