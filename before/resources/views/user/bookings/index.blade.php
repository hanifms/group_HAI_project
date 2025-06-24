<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('My Bookings') }}
            </h2>
            <a href="{{ route('travel-packages.index') }}" class="btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Book New Trip
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Notification Messages -->
            @if (session('success'))
                <div class="mb-4 notification notification-success">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <!-- Empty State -->
                    @if ($bookings->isEmpty())
                        <div class="text-center py-16 bg-gray-50 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-blue-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <h3 class="text-xl font-bold text-gray-700">You don't have any bookings yet.</h3>
                            <p class="mt-2 text-gray-500 max-w-md mx-auto">
                                Ready to embark on your next adventure? Browse through our curated travel packages to find your perfect destination.
                            </p>
                            <div class="mt-6">
                                <a href="{{ route('travel-packages.index') }}" class="btn-primary">
                                    Browse Travel Packages
                                </a>
                            </div>
                        </div>
                    @else
                        <!-- Search and Filter Section -->
                        <div class="mb-6 flex flex-wrap gap-4 justify-between items-center">
                            <div class="w-full md:w-auto">
                                <select class="form-select" onchange="window.location.href=this.value">
                                    <option value="{{ route('bookings.my-bookings') }}">All Bookings</option>
                                    <option value="{{ route('bookings.my-bookings', ['status' => 'pending']) }}" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="{{ route('bookings.my-bookings', ['status' => 'confirmed']) }}" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                    <option value="{{ route('bookings.my-bookings', ['status' => 'completed']) }}" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="{{ route('bookings.my-bookings', ['status' => 'cancelled']) }}" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>
                        </div>

                        <!-- Bookings Table -->
                        <div class="overflow-x-auto shadow rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-blue-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-blue-800 uppercase tracking-wider">Travel Package</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-blue-800 uppercase tracking-wider">Destination</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-blue-800 uppercase tracking-wider">Travel Dates</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-blue-800 uppercase tracking-wider">Travelers</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-blue-800 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-blue-800 uppercase tracking-wider">Review</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-blue-800 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($bookings as $booking)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="font-medium text-gray-900">{{ $booking->travelPackage->name }}</div>
                                                @if($booking->travelPackage->featured)
                                                    <span class="badge badge-success">Featured</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center text-sm text-gray-700">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    </svg>
                                                    {{ $booking->travelPackage->destination }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center text-sm text-gray-700">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                    {{ $booking->travelPackage->start_date->format('M d, Y') }} - {{ $booking->travelPackage->end_date->format('M d, Y') }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center text-sm text-gray-700">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                                    </svg>
                                                    {{ $booking->number_of_travelers }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="badge
                                                    @if($booking->status == 'pending') badge-warning
                                                    @elseif($booking->status == 'confirmed') badge-success
                                                    @elseif($booking->status == 'completed') badge-info
                                                    @else badge-danger
                                                    @endif">
                                                    {{ ucfirst($booking->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($booking->review)
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
                                                    </div>
                                                @elseif($booking->isEligibleForReview())
                                                    <a href="{{ route('reviews.create', $booking) }}" class="btn-secondary text-xs">Leave a Review</a>
                                                @else
                                                    <span class="text-gray-400">Not eligible yet</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                                <a href="{{ route('bookings.show', $booking) }}" class="btn-link">View Details</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <!-- Pagination Links (if available) -->
                            @if(method_exists($bookings, 'links'))
                                <div class="px-6 py-4 bg-gray-50">
                                    {{ $bookings->links() }}
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
