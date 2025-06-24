<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ReviewController extends Controller
{
    /**
     * Display a form to create a new review for a booking.
     */
    public function create(Booking $booking): View|RedirectResponse
    {
        // Ensure the booking belongs to the authenticated user
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Check if the booking is eligible for review
        if (!$booking->isEligibleForReview()) {
            return redirect()->route('bookings.show', $booking)
                ->with('error', 'This booking is not eligible for review.');
        }

        // Check if a review already exists
        if ($booking->review) {
            return redirect()->route('bookings.show', $booking)
                ->with('error', 'You have already reviewed this booking.');
        }

        return view('user.reviews.create', compact('booking'));
    }

    /**
     * Store a newly created review in storage.
     */
    public function store(Request $request, Booking $booking): RedirectResponse
    {
        // Ensure the booking belongs to the authenticated user
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Check if the booking is eligible for review
        if (!$booking->isEligibleForReview()) {
            return redirect()->route('bookings.show', $booking)
                ->with('error', 'This booking is not eligible for review.');
        }

        // Check if a review already exists
        if ($booking->review) {
            return redirect()->route('bookings.show', $booking)
                ->with('error', 'You have already reviewed this booking.');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $review = new Review([
            'user_id' => Auth::id(),
            'booking_id' => $booking->id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
            'review_date' => now(),
        ]);

        $review->save();

        return redirect()->route('bookings.show', $booking)
            ->with('success', 'Thank you for your review!');
    }

    /**
     * Display a listing of the user's reviews.
     */
    public function index(): View
    {
        $reviews = Auth::user()->reviews()->with('booking.travelPackage')->latest('review_date')->paginate(10);
        return view('user.reviews.index', compact('reviews'));
    }

    /**
     * Display the specified review.
     */
    public function show(Review $review): View|RedirectResponse
    {
        // Ensure the review belongs to the authenticated user
        if ($review->user_id !== Auth::id()) {
            return redirect()->route('reviews.index')
                ->with('error', 'You cannot view this review.');
        }

        return view('user.reviews.show', compact('review'));
    }
}
