<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReviewController extends Controller
{
    /**
     * Display a listing of all reviews.
     */
    public function index(): View
    {
        $reviews = Review::with(['user', 'booking.travelPackage'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.reviews.index', compact('reviews'));
    }

    /**
     * Display the specified review.
     */
    public function show(Review $review): View
    {
        $review->load(['user', 'booking.travelPackage']);

        return view('admin.reviews.show', compact('review'));
    }
}
