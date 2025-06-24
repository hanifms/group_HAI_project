<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\TravelPackage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TravelController extends Controller
{
    /**
     * Display a listing of the travel packages.
     */
    public function index(Request $request): View
    {
        // If user is admin, show all packages (both visible and hidden)
        if (Auth::check() && Auth::user()->isAdmin()) {
            $travelPackages = TravelPackage::all();
        } else {
            // For non-admins and guests, only show visible packages
            $travelPackages = TravelPackage::visible()->get();
        }

        return view('user.travel-packages.index', compact('travelPackages'));
    }

    /**
     * Display the specified travel package.
     */
    public function show(TravelPackage $travelPackage): View|RedirectResponse
    {
        // If the package is not visible and the user is not an admin, return 404
        if (!$travelPackage->is_visible && (!Auth::check() || !Auth::user()->isAdmin())) {
            abort(404, 'The requested travel package is not available.');
        }

        // Load reviews for this travel package
        $reviews = $travelPackage->bookings()->whereHas('review')->with(['review', 'user'])->get()
            ->pluck('review');

        return view('user.travel-packages.show', compact('travelPackage', 'reviews'));
    }
}
