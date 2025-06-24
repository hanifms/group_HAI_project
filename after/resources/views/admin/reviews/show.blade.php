<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Review Details') }}
            </h2>
            <div class="mt-3 sm:mt-0">
                <a href="{{ route('admin.reviews.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo active:bg-indigo-700 transition ease-in-out duration-150">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Reviews
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                    <div class="mb-6">
                        <h3 class="font-semibold text-lg text-gray-800 mb-2">Review Information</h3>
                        <div class="bg-gray-50 rounded-lg p-4 shadow-sm">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-600">User</p>
                                    <p class="text-base font-medium">
                                        <a href="{{ route('admin.users.show', $review->user->id) }}" class="text-indigo-600 hover:text-indigo-900">
                                            {{ $review->user->name }}
                                        </a>
                                    </p>
                                </div>

                                <div>
                                    <p class="text-sm text-gray-600">Travel Package</p>
                                    <p class="text-base font-medium">
                                        <a href="{{ route('admin.travel-packages.show', $review->booking->travelPackage->id) }}" class="text-indigo-600 hover:text-indigo-900">
                                            {{ $review->booking->travelPackage->name }}
                                        </a>
                                    </p>
                                </div>

                                <div>
                                    <p class="text-sm text-gray-600">Rating</p>
                                    <div class="flex items-center">
                                        @for($i = 1; $i <= 5; $i++)
                                            <span class="{{ $i <= $review->rating ? 'text-yellow-500' : 'text-gray-300' }}">★</span>
                                        @endfor
                                        <span class="ml-1 text-base font-medium">{{ $review->rating }}/5</span>
                                    </div>
                                </div>

                                <div>
                                    <p class="text-sm text-gray-600">Date</p>
                                    <p class="text-base font-medium">{{ $review->created_at->format('M d, Y') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h3 class="font-semibold text-lg text-gray-800 mb-2">Review Comment</h3>
                        <div class="bg-gray-50 rounded-lg p-4 shadow-sm">
                            <p class="text-gray-700">{{ $review->comment }}</p>
                        </div>
                    </div>

                    <div>
                        <h3 class="font-semibold text-lg text-gray-800 mb-2">Booking Details</h3>
                        <div class="bg-gray-50 rounded-lg p-4 shadow-sm">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-600">Booking ID</p>
                                    <p class="text-base font-medium">
                                        <a href="{{ route('admin.bookings.show', $review->booking->id) }}" class="text-indigo-600 hover:text-indigo-900">
                                            #{{ $review->booking->id }}
                                        </a>
                                    </p>
                                </div>

                                <div>
                                    <p class="text-sm text-gray-600">Booking Date</p>
                                    <p class="text-base font-medium">{{ $review->booking->booking_date->format('M d, Y') }}</p>
                                </div>

                                <div>
                                    <p class="text-sm text-gray-600">Status</p>
                                    <p class="text-base font-medium">{{ ucfirst($review->booking->status) }}</p>
                                </div>

                                <div>
                                    <p class="text-sm text-gray-600">Number of Travelers</p>
                                    <p class="text-base font-medium">{{ $review->booking->number_of_travelers }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
