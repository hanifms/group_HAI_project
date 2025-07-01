<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;
use App\Models\User;

Route::get('/', function () {
    return view('welcome');
});


//Routing for robots.txt 
Route::get('/robots.txt', function () {
    return response("User-agent: *\nDisallow:", 200)
        ->header('Content-Type', 'text/plain');
});

Route::get('/sitemap.xml', function () {
    return response('<?xml version="1.0"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"></urlset>', 200)
        ->header('Content-Type', 'application/xml');
});


// CSP Violation Reporting
Route::post('/csp-report', function (Illuminate\Http\Request $request) {
    $violation = $request->json()->all();

    // Log CSP violations for debugging
    if (app()->environment('local')) {
        \Log::warning('CSP Violation Report', [
            'document-uri' => $violation['document-uri'] ?? 'unknown',
            'violated-directive' => $violation['violated-directive'] ?? 'unknown',
            'blocked-uri' => $violation['blocked-uri'] ?? 'unknown',
            'line-number' => $violation['line-number'] ?? 'unknown',
            'column-number' => $violation['column-number'] ?? 'unknown',
            'source-file' => $violation['source-file'] ?? 'unknown',
            'user-agent' => $request->header('User-Agent'),
            'full-report' => $violation,
        ]);
    }

    return response('', 204); // No Content
})->middleware('throttle:60,1');


// Public Travel Package Routes - accessible to guests and authenticated users
Route::get('/travel-packages', [App\Http\Controllers\User\TravelController::class, 'index'])->name('travel-packages.index');
Route::get('/travel-packages/{travelPackage}', [App\Http\Controllers\User\TravelController::class, 'show'])->name('travel-packages.show');

// Default dashboard route - will redirect based on role
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',

])->group(function () {
    Route::get('/dashboard', function () {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        return view('dashboard');
    })->name('dashboard');
});

// User routes
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    // Booking Routes
    Route::get('/my-bookings', [App\Http\Controllers\User\BookingController::class, 'myBookings'])->name('bookings.my-bookings');
    Route::get('/travel-packages/{travelPackage}/book', [App\Http\Controllers\User\BookingController::class, 'create'])->name('bookings.create');
    Route::post('/travel-packages/{travelPackage}/book', [App\Http\Controllers\User\BookingController::class, 'store'])->name('bookings.store');
    Route::post('/bookings', [App\Http\Controllers\User\BookingController::class, 'storeDirectly'])->name('bookings.store-directly');
    Route::get('/bookings/{booking}', [App\Http\Controllers\User\BookingController::class, 'show'])->name('bookings.show');

    // Review Routes
    Route::get('/bookings/{booking}/review', [App\Http\Controllers\User\ReviewController::class, 'create'])->name('reviews.create');
    Route::post('/bookings/{booking}/review', [App\Http\Controllers\User\ReviewController::class, 'store'])->name('reviews.store');
    Route::get('/my-reviews', [App\Http\Controllers\User\ReviewController::class, 'index'])->name('reviews.index');
    Route::get('/reviews/{review}', [App\Http\Controllers\User\ReviewController::class, 'show'])->name('reviews.show');
});

// Admin routes
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    AdminMiddleware::class,
])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // User management routes
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    Route::patch('users/{user}/change-role', [\App\Http\Controllers\Admin\UserController::class, 'changeRole'])->name('users.change-role');
    Route::patch('users/{user}/role', [\App\Http\Controllers\Admin\UserController::class, 'changeRole'])->name('users.change-role.alt');

    // Booking management routes
    Route::resource('bookings', \App\Http\Controllers\Admin\BookingController::class)->except(['create', 'store', 'destroy']);
    Route::get('bookings/filter', [\App\Http\Controllers\Admin\BookingController::class, 'filter'])->name('bookings.filter');

    // Travel Package management routes
    Route::resource('travel-packages', \App\Http\Controllers\Admin\TravelPackageController::class);
    Route::match(['put', 'patch'], 'travel-packages/{travelPackage}/toggle-visibility', [\App\Http\Controllers\Admin\TravelPackageController::class, 'toggleVisibility'])
        ->name('travel-packages.toggle-visibility');

    // Review management routes
    Route::resource('reviews', \App\Http\Controllers\Admin\ReviewController::class)->only(['index', 'show']);

});
