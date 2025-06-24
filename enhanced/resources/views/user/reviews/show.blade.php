<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Review Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <!-- Back button -->
                <div class="mb-6">
                    <a href="{{ route('reviews.index') }}" class="text-gray-600 hover:text-gray-900 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back to Reviews
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Travel Package Details -->
                    <div class="md:col-span-1">
                        <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
                            <h3 class="text-lg font-semibold text-gray-800 mb-3">Travel Package</h3>
                            <div class="text-sm space-y-2">
                                <p><span class="font-medium">Package:</span> {{ $review->booking->travelPackage->name }}</p>
                                <p><span class="font-medium">Destination:</span> {{ $review->booking->travelPackage->destination }}</p>
                                <p><span class="font-medium">Booking Reference:</span> #{{ $review->booking->id }}</p>
                                <p><span class="font-medium">Booking Date:</span> {{ $review->booking->created_at->format('d M Y') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Review Details -->
                    <div class="md:col-span-2">
                        <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-semibold text-gray-800">Review Details</h3>
                                <div class="flex items-center">
                                    <div class="flex items-center">
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= $review->rating)
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-500" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                </svg>
                                            @else
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-300" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                </svg>
                                            @endif
                                        @endfor
                                    </div>
                                    <span class="ml-2 text-gray-600">{{ $review->rating }}/5</span>
                                </div>
                            </div>

                            <div class="mb-4">
                                <p class="text-sm text-gray-600">
                                    <span class="font-medium">Submitted on:</span> {{ $review->review_date->format('d M Y, H:i') }}
                                </p>
                            </div>

                            <div class="bg-white p-4 rounded-lg border border-gray-200 mb-4">
                                @if ($review->comment)
                                    <p class="text-gray-800">{{ $review->comment }}</p>
                                @else
                                    <p class="text-gray-500 italic">No comments provided.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
