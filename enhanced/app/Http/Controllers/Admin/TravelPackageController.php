<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TravelPackage;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TravelPackageController extends Controller
{
    /**
     * Display a listing of all travel packages.
     */
    public function index(): View
    {
        $travelPackages = TravelPackage::orderBy('start_date', 'asc')
            ->paginate(10);

        return view('admin.travel-packages.index', compact('travelPackages'));
    }

    /**
     * Show the form for creating a new travel package.
     */
    public function create(): View
    {
        return view('admin.travel-packages.create');
    }

    /**
     * Store a newly created travel package in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'destination' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'available_slots' => ['required', 'integer', 'min:1'],
            'is_visible' => ['sometimes', 'boolean'],
        ]);

        TravelPackage::create([
            'name' => $request->name,
            'description' => $request->description,
            'destination' => $request->destination,
            'price' => $request->price,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'available_slots' => $request->available_slots,
            'is_visible' => $request->has('is_visible') ? true : false,
        ]);

        return redirect()->route('admin.travel-packages.index')
            ->with('success', 'Travel package created successfully.');
    }

    /**
     * Display the specified travel package.
     */
    public function show(TravelPackage $travelPackage): View
    {
        // Load related bookings and reviews for the package
        $travelPackage->load('bookings.user', 'bookings.review');

        return view('admin.travel-packages.show', compact('travelPackage'));
    }

    /**
     * Show the form for editing the specified travel package.
     */
    public function edit(TravelPackage $travelPackage): View
    {
        return view('admin.travel-packages.edit', compact('travelPackage'));
    }

    /**
     * Update the specified travel package in storage.
     */
    public function update(Request $request, TravelPackage $travelPackage)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'destination' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'available_slots' => ['required', 'integer', 'min:0'],
            'is_visible' => ['sometimes', 'boolean'],
        ]);

        // The is_visible value needs special handling as checkboxes don't submit a value when unchecked
        $isVisible = $request->has('is_visible') && $request->is_visible ? true : false;

        $travelPackage->update([
            'name' => $request->name,
            'description' => $request->description,
            'destination' => $request->destination,
            'price' => $request->price,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'available_slots' => $request->available_slots,
            'is_visible' => $isVisible,
        ]);

        return redirect()->route('admin.travel-packages.index')
            ->with('success', 'Travel package updated successfully.');
    }

    /**
     * Remove the specified travel package from storage.
     */
    public function destroy(TravelPackage $travelPackage)
    {
        // Check if the package has any active bookings before allowing deletion
        if ($travelPackage->hasActiveBookings()) {
            return redirect()->route('admin.travel-packages.index')
                ->withErrors(['package' => "Cannot delete this package as it has active bookings (pending, on hold, or ongoing). You can hide it by setting it to invisible instead."]);
        }

        // If there are completed bookings, warn but allow deletion
        $completedBookingsCount = $travelPackage->bookings()
            ->whereIn('status', ['completed', 'cancelled'])
            ->count();

        if ($completedBookingsCount > 0) {
            // We have only completed/cancelled bookings, so we can delete, but warn about history
            $travelPackage->delete();
            return redirect()->route('admin.travel-packages.index')
                ->with('warning', "Package deleted. Note that {$completedBookingsCount} completed/cancelled bookings were linked to this package and will now reference a deleted package.");
        }

        // No bookings at all
        $travelPackage->delete();
        return redirect()->route('admin.travel-packages.index')
            ->with('success', 'Travel package deleted successfully.');
    }

    /**
     * Toggle the visibility of the travel package.
     */
    public function toggleVisibility(TravelPackage $travelPackage)
    {
        $travelPackage->is_visible = !$travelPackage->is_visible;
        $travelPackage->save();

        $status = $travelPackage->is_visible ? 'visible' : 'invisible';
        return redirect()->route('admin.travel-packages.index')
            ->with('success', "Travel package is now {$status} to users.");
    }
}
