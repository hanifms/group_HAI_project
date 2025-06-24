<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $travelPackage->name }}
            </h2>
            <a href="{{ route('travel-packages.index') }}" class="px-4 py-2 bg-gray-600 text-white text-sm rounded hover:bg-gray-700 transition">
                Back to Packages
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-800 mb-2">{{ $travelPackage->name }}</h1>
                            <span class="inline-block bg-blue-100 text-blue-800 rounded-full px-3 py-1 text-sm font-semibold">
                                {{ $travelPackage->destination }}
                            </span>
                        </div>
                        <div class="text-right">
                            <div class="text-2xl font-bold text-gray-800">${{ number_format($travelPackage->price, 2) }}</div>
                            <div class="text-sm text-gray-600">per person</div>
                        </div>
                    </div>

                    <div class="border-t border-b border-gray-200 py-4 mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-center">
                            <div>
                                <div class="text-gray-600 text-sm">Trip Duration</div>
                                <div class="font-semibold text-lg">
                                    {{ $travelPackage->start_date->diffInDays($travelPackage->end_date) + 1 }} days
                                </div>
                            </div>
                            <div>
                                <div class="text-gray-600 text-sm">Date</div>
                                <div class="font-semibold text-lg">
                                    {{ $travelPackage->start_date->format('M d, Y') }} - {{ $travelPackage->end_date->format('M d, Y') }}
                                </div>
                            </div>
                            <div>
                                <div class="text-gray-600 text-sm">Available Slots</div>
                                <div class="font-semibold text-lg {{ $travelPackage->available_slots > 5 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $travelPackage->available_slots }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">Trip Description</h2>
                        <p class="text-gray-600 leading-relaxed">
                            {{ $travelPackage->description }}
                        </p>
                    </div>

                    <!-- Reviews Section -->
                    <div class="mt-8 mb-6">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">Customer Reviews</h2>

                        @if($reviews->count() > 0)
                            <div class="space-y-4">
                                @foreach($reviews as $review)
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <div class="flex items-center mb-2">
                                            <div class="flex">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <span class="{{ $i <= $review->rating ? 'text-yellow-500' : 'text-gray-300' }}">★</span>
                                                @endfor
                                            </div>
                                            <span class="ml-2 text-gray-600 text-sm">{{ $review->created_at->format('M d, Y') }}</span>
                                        </div>
                                        <p class="text-gray-700 mb-2">{{ $review->comment }}</p>
                                        <p class="text-sm text-gray-500">By: {{ $review->user->name }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 italic">No reviews yet for this package.</p>
                        @endif
                    </div>

                    <div class="mt-8 flex justify-end">
                        @if ($travelPackage->available_slots > 0)
                            <a href="{{ route('bookings.create', $travelPackage) }}" class="px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition">
                                Book This Package
                            </a>
                        @else
                            <button disabled class="px-6 py-3 bg-gray-400 text-white font-medium rounded-lg cursor-not-allowed">
                                No Available Slots
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
