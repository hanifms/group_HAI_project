<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('User Details') }}: {{ $user->name }}
            </h2>
            <a href="{{ route('admin.users.edit', $user->id) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring focus:ring-blue-300 active:bg-blue-600 transition ease-in-out duration-150">
                {{ __('Edit User') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- User Information -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-6">
                <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">User Information</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-600">Name</p>
                            <p class="font-medium">{{ $user->name }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-600">Email</p>
                            <p class="font-medium">{{ $user->email }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-600">Role</p>
                            <p class="font-medium">
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $user->isAdmin() ? 'bg-purple-100 text-purple-800' : 'bg-green-100 text-green-800' }}">
                                    {{ ucfirst($user->role->name) }}
                                </span>
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-600">Registered On</p>
                            <p class="font-medium">{{ $user->created_at->format('F j, Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Bookings -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-6">
                <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">Pending Bookings ({{ $pendingBookings->count() }})</h3>

                    @if($pendingBookings->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Package
                                        </th>
                                        <th class="px-6 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Destination
                                        </th>
                                        <th class="px-6 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Booking Date
                                        </th>
                                        <th class="px-6 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Travelers
                                        </th>
                                        <th class="px-6 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pendingBookings as $booking)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap border-b border-gray-200">
                                                {{ $booking->travelPackage->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap border-b border-gray-200">
                                                {{ $booking->travelPackage->destination }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap border-b border-gray-200">
                                                {{ $booking->booking_date->format('F j, Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap border-b border-gray-200">
                                                {{ $booking->number_of_travelers }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap border-b border-gray-200">
                                                <a href="{{ route('admin.bookings.edit', $booking->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-2">Update Status</a>
                                                <a href="{{ route('admin.bookings.show', $booking->id) }}" class="text-blue-600 hover:text-blue-900">View</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500">No pending bookings found.</p>
                    @endif
                </div>
            </div>

            <!-- Ongoing Bookings -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-6">
                <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">Ongoing Bookings ({{ $ongoingBookings->count() }})</h3>

                    @if($ongoingBookings->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Package
                                        </th>
                                        <th class="px-6 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Destination
                                        </th>
                                        <th class="px-6 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Booking Date
                                        </th>
                                        <th class="px-6 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Travelers
                                        </th>
                                        <th class="px-6 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($ongoingBookings as $booking)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap border-b border-gray-200">
                                                {{ $booking->travelPackage->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap border-b border-gray-200">
                                                {{ $booking->travelPackage->destination }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap border-b border-gray-200">
                                                {{ $booking->booking_date->format('F j, Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap border-b border-gray-200">
                                                {{ $booking->number_of_travelers }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap border-b border-gray-200">
                                                <a href="{{ route('admin.bookings.edit', $booking->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-2">Update Status</a>
                                                <a href="{{ route('admin.bookings.show', $booking->id) }}" class="text-blue-600 hover:text-blue-900">View</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500">No ongoing bookings found.</p>
                    @endif
                </div>
            </div>

            <!-- Completed Bookings -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-6">
                <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">Completed Bookings ({{ $completedBookings->count() }})</h3>

                    @if($completedBookings->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Package
                                        </th>
                                        <th class="px-6 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Destination
                                        </th>
                                        <th class="px-6 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Booking Date
                                        </th>
                                        <th class="px-6 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Travelers
                                        </th>
                                        <th class="px-6 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Review
                                        </th>
                                        <th class="px-6 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($completedBookings as $booking)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap border-b border-gray-200">
                                                {{ $booking->travelPackage->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap border-b border-gray-200">
                                                {{ $booking->travelPackage->destination }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap border-b border-gray-200">
                                                {{ $booking->booking_date->format('F j, Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap border-b border-gray-200">
                                                {{ $booking->number_of_travelers }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap border-b border-gray-200">
                                                @if($booking->review)
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                        {{ $booking->review->rating }}/5
                                                    </span>
                                                @else
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                        No Review
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap border-b border-gray-200">
                                                <a href="{{ route('admin.bookings.show', $booking->id) }}" class="text-blue-600 hover:text-blue-900">View</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500">No completed bookings found.</p>
                    @endif
                </div>
            </div>

            <!-- Reviews -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">User Reviews ({{ $reviews->count() }})</h3>

                    @if($reviews->count() > 0)
                        <div class="space-y-6">
                            @foreach($reviews as $review)
                                <div class="border-b border-gray-200 pb-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <div>
                                            <h4 class="font-medium">{{ $review->booking->travelPackage->name }}</h4>
                                            <p class="text-sm text-gray-500">{{ $review->booking->travelPackage->destination }}</p>
                                        </div>
                                        <div class="flex items-center">
                                            <span class="font-bold text-xl mr-1">{{ $review->rating }}</span>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                        </div>
                                    </div>
                                    <p class="text-gray-700 mb-2">{{ $review->comment }}</p>
                                    <p class="text-sm text-gray-500">{{ $review->review_date->format('F j, Y') }}</p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500">No reviews found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
