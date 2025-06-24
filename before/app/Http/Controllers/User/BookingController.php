<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\TravelPackage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class BookingController extends Controller
{
    /**
     * Display a list of the user's bookings.
     */
    public function myBookings(Request $request): View
    {
        $query = Auth::user()->bookings()->with(['travelPackage', 'review']);

        // Filter by status if provided
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $bookings = $query->latest()->get();
        $status = $request->status ?? null;

        return view('user.bookings.index', compact('bookings', 'status'));
    }

    /**
     * Show the form for creating a new booking.
     */
    public function create(TravelPackage $travelPackage): View
    {
        // Check if the package is visible
        if (!$travelPackage->is_visible) {
            abort(404, 'The travel package is not available.');
        }

        return view('user.bookings.create', compact('travelPackage'));
    }

    /**
     * Store a newly created booking in storage.
     *
     * @throws ValidationException
     */
    public function store(Request $request, TravelPackage $travelPackage): RedirectResponse
    {
        // Check if the package is visible
        if (!$travelPackage->is_visible) {
            return redirect()->route('travel-packages.index')
                ->with('error', 'The requested travel package is not available.');
        }

        $request->validate([
            'number_of_travelers' => 'required|integer|min:1|max:' . $travelPackage->available_slots,
        ]);

        // Check if there are enough available slots
        if ($travelPackage->available_slots < $request->number_of_travelers) {
            return back()->withErrors(['number_of_travelers' => 'Not enough available slots for this booking.']);
        }

        // Create the booking
        $booking = new Booking([
            'user_id' => Auth::id(),
            'travel_package_id' => $travelPackage->id,
            'number_of_travelers' => $request->number_of_travelers,
            'status' => 'pending',
        ]);

        $booking->save();

        // Update available slots
        $travelPackage->available_slots -= $request->number_of_travelers;
        $travelPackage->save();

        return redirect()->route('bookings.my-bookings')
            ->with('success', 'Booking created successfully.');
    }

    /**
     * Display the specified booking.
     */
    public function show(Booking $booking): View
    {
        // Make sure the booking belongs to the authenticated user
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Eager load the review relationship if it exists
        $booking->load(['review', 'travelPackage']);

        return view('user.bookings.show', compact('booking'));
    }

    /**
     * Store a newly created booking directly from POST /bookings
     *
     * @throws ValidationException
     */
    public function storeDirectly(Request $request): RedirectResponse
    {
        $request->validate([
            'travel_package_id' => 'required|exists:travel_packages,id',
            'number_of_travelers' => 'required|integer|min:1',
        ]);

        $travelPackage = TravelPackage::findOrFail($request->travel_package_id);

        // Check if the package is visible
        if (!$travelPackage->is_visible) {
            return redirect()->route('travel-packages.index')
                ->withErrors(['travel_package_id' => 'The requested travel package is not available.']);
        }

        // Validate against available slots
        if ($travelPackage->available_slots < $request->number_of_travelers) {
            if ($travelPackage->available_slots <= 0) {
                // Package is sold out
                return back()->withErrors(['travel_package_id' => 'This travel package is sold out.']);
            }
            return back()->withErrors(['number_of_travelers' => 'Not enough available slots for this booking.']);
        }

        // Create the booking
        $booking = new Booking([
            'user_id' => Auth::id(),
            'travel_package_id' => $travelPackage->id,
            'number_of_travelers' => $request->number_of_travelers,
            'status' => 'pending',
        ]);

        $booking->save();

        // Update available slots
        $travelPackage->available_slots -= $request->number_of_travelers;
        $travelPackage->save();

        return redirect()->route('bookings.my-bookings')
            ->with('success', 'Booking created successfully.');
    }
}
