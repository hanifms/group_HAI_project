<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Reviews') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                @if($reviews->count() > 0)
                    <div class="space-y-6">
                        @foreach($reviews as $review)
                            <div class="border-b border-gray-200 pb-6 mb-6 last:border-0 last:pb-0 last:mb-0">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h3 class="text-lg font-medium text-gray-900">
                                            {{ $review->booking->travelPackage->name }} to {{ $review->booking->travelPackage->destination }}
                                        </h3>
                                        <div class="mt-1 flex items-center">
                                            <div class="flex text-yellow-500">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= $review->rating)
                                                        <span class="text-lg">★</span>
                                                    @else
                                                        <span class="text-lg text-gray-300">★</span>
                                                    @endif
                                                @endfor
                                            </div>
                                            <span class="ml-2 text-sm text-gray-500">
                                                {{ $review->review_date->format('F j, Y') }}
                                            </span>
                                        </div>
                                    </div>
                                    <a href="{{ route('bookings.show', $review->booking) }}" class="text-indigo-600 hover:text-indigo-900 text-sm">
                                        View Booking
                                    </a>
                                </div>

                                @if($review->comment)
                                    <div class="mt-4 text-gray-700">
                                        {{ $review->comment }}
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6">
                        {{ $reviews->links() }}
                    </div>
                @else
                    <div class="text-center py-10">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="mt-2 text-lg font-medium text-gray-900">No reviews yet</h3>
                        <p class="mt-1 text-gray-500">After completing a travel package, you can come back and share your experience.</p>
                        <div class="mt-6">
                            <a href="{{ route('bookings.my-bookings') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                View My Bookings
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
