<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Booking Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="mb-6 flex justify-between items-center">
                    <div>
                        <a href="{{ route('admin.bookings.index') }}" class="text-indigo-600 hover:text-indigo-900">
                            &larr; Back to All Bookings
                        </a>
                    </div>
                    <a href="{{ route('admin.bookings.edit', $booking) }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600 focus:bg-yellow-600 active:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Update Status
                    </a>
                </div>

                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Booking Details -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-lg font-semibold mb-4">Booking Information</h3>
                        <p><strong>Booking ID:</strong> #{{ $booking->id }}</p>
                        <p><strong>Booking Date:</strong> {{ $booking->booking_date->format('F j, Y') }}</p>
                        <p><strong>Status:</strong>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                {{ $booking->status === 'completed' ? 'bg-green-100 text-green-800' :
                                   ($booking->status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                                   ($booking->status === 'on_hold' ? 'bg-red-100 text-red-800' :
                                    'bg-blue-100 text-blue-800')) }}">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </p>
                        <p><strong>Number of Travelers:</strong> {{ $booking->number_of_travelers }}</p>
                    </div>

                    <!-- User Information -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-lg font-semibold mb-4">User Information</h3>
                        <p><strong>Name:</strong> {{ $booking->user->name }}</p>
                        <p><strong>Email:</strong> {{ $booking->user->email }}</p>
                        <a href="{{ route('admin.users.show', $booking->user) }}" class="text-indigo-600 hover:text-indigo-900">View User Profile</a>
                    </div>

                    <!-- Travel Package Information -->
                    <div class="bg-gray-50 p-4 rounded-lg col-span-1 lg:col-span-2">
                        <h3 class="text-lg font-semibold mb-4">Travel Package Details</h3>
                        <p><strong>Package:</strong> {{ $booking->travelPackage->name }}</p>
                        <p><strong>Destination:</strong> {{ $booking->travelPackage->destination }}</p>
                        <p><strong>Travel Dates:</strong> {{ $booking->travelPackage->start_date->format('M d, Y') }} - {{ $booking->travelPackage->end_date->format('M d, Y') }}</p>
                        <p><strong>Price:</strong> ${{ number_format($booking->travelPackage->price, 2) }}</p>
                        <p><strong>Total Amount:</strong> ${{ number_format($booking->travelPackage->price * $booking->number_of_travelers, 2) }}</p>
                        <div class="mt-2">
                            <a href="{{ route('admin.travel-packages.show', $booking->travelPackage) }}" class="text-indigo-600 hover:text-indigo-900">View Travel Package</a>
                        </div>
                    </div>
                </div>

                <!-- Review Section (if booking is completed and has a review) -->
                @if($booking->status === 'completed')
                    <div class="mt-6">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold mb-4">User Review</h3>

                            @if($booking->review)
                                <div class="mb-2">
                                    <span class="font-medium">Rating:</span>
                                    <div class="flex mt-1">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $booking->review->rating)
                                                <!-- Filled star -->
                                                <svg class="h-5 w-5 text-yellow-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                </svg>
                                            @else
                                                <!-- Empty star -->
                                                <svg class="h-5 w-5 text-gray-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                </svg>
                                            @endif
                                        @endfor
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <span class="font-medium">Comment:</span>
                                    <p class="mt-1 text-gray-600">{{ $booking->review->comment }}</p>
                                </div>
                                <div class="mb-2">
                                    <span class="font-medium">Review Date:</span>
                                    <p class="mt-1 text-gray-600">{{ $booking->review->review_date->format('F j, Y') }}</p>
                                </div>
                            @else
                                <p class="text-gray-500">No review has been submitted for this booking yet.</p>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
