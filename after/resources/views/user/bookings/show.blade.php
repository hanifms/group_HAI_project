<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Booking Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">{{ $booking->travelPackage->name }}</h3>
                            <p class="mt-1 text-sm text-gray-600">{{ $booking->travelPackage->description }}</p>
                        </div>
                        <span class="px-3 py-1 text-xs font-semibold rounded-full
                            @if($booking->status == 'pending') bg-yellow-100 text-yellow-800
                            @elseif($booking->status == 'confirmed') bg-green-100 text-green-800
                            @elseif($booking->status == 'completed') bg-blue-100 text-blue-800
                            @else bg-red-100 text-red-800
                            @endif">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </div>

                    <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Booking Information</h4>
                            <div class="mt-2 border rounded-md p-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Booking ID</p>
                                        <p class="mt-1 text-sm text-gray-900">#{{ $booking->id }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Booking Date</p>
                                        <p class="mt-1 text-sm text-gray-900">{{ $booking->booking_date instanceof \Carbon\Carbon ? $booking->booking_date->format('M d, Y H:i') : date('M d, Y H:i', strtotime($booking->booking_date)) }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Number of Travelers</p>
                                        <p class="mt-1 text-sm text-gray-900">{{ $booking->number_of_travelers }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Total Price</p>
                                        <p class="mt-1 text-sm text-gray-900">${{ number_format($booking->number_of_travelers * $booking->travelPackage->price, 2) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Travel Information</h4>
                            <div class="mt-2 border rounded-md p-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Destination</p>
                                        <p class="mt-1 text-sm text-gray-900">{{ $booking->travelPackage->destination }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Travel Dates</p>
                                        <p class="mt-1 text-sm text-gray-900">
                                            {{ $booking->travelPackage->start_date->format('M d, Y') }} -
                                            {{ $booking->travelPackage->end_date->format('M d, Y') }}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Duration</p>
                                        <p class="mt-1 text-sm text-gray-900">
                                            {{ $booking->travelPackage->start_date->diffInDays($booking->travelPackage->end_date) }} days
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Price per Person</p>
                                        <p class="mt-1 text-sm text-gray-900">${{ number_format($booking->travelPackage->price, 2) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if ($booking->status === 'completed')
                        <div class="mt-6 border-t pt-4">
                            @if($booking->review)
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <h4 class="text-sm font-medium text-gray-700">Your Review</h4>
                                    <div class="mt-2">
                                        <div class="flex items-center">
                                            <div class="flex text-yellow-500">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= $booking->review->rating)
                                                        <span>★</span>
                                                    @else
                                                        <span class="text-gray-300">★</span>
                                                    @endif
                                                @endfor
                                            </div>
                                            <span class="ml-2 text-xs text-gray-500">
                                                {{ $booking->review->review_date->format('F j, Y') }}
                                            </span>
                                        </div>
                                        @if($booking->review->comment)
                                            <p class="mt-2 text-sm text-gray-700">{{ $booking->review->comment }}</p>
                                        @endif
                                    </div>
                                </div>
                            @elseif($booking->isEligibleForReview())
                                <div>
                                    <p class="text-sm font-medium text-gray-700">This travel package is completed. Would you like to leave a review?</p>
                                    <div class="mt-2">
                                        <a href="{{ route('reviews.create', $booking) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:shadow-outline-indigo transition ease-in-out duration-150">
                                            Leave a Review
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif

                    <div class="mt-6 flex justify-between">
                        <a href="{{ route('bookings.my-bookings') }}" class="text-sm text-indigo-600 hover:text-indigo-900">← Back to My Bookings</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
