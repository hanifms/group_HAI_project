<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage Bookings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                    <!-- Filter Controls -->
                    <div class="mb-6">
                        <form action="{{ route('admin.bookings.filter') }}" method="GET" class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2">
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Filter by Status</label>
                                <select id="status" name="status" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                    <option value="all">All Statuses</option>
                                    <option value="pending" {{ isset($status) && $status === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="confirmed" {{ isset($status) && $status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                    <option value="on_hold" {{ isset($status) && $status === 'on_hold' ? 'selected' : '' }}>On Hold</option>
                                    <option value="ongoing" {{ isset($status) && $status === 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                                    <option value="completed" {{ isset($status) && $status === 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ isset($status) && $status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>
                            <div class="self-end">
                                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Filter
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        ID
                                    </th>
                                    <th class="px-6 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        User
                                    </th>
                                    <th class="px-6 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Travel Package
                                    </th>
                                    <th class="px-6 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Status
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
                                @forelse($bookings as $booking)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap border-b border-gray-200">
                                            {{ $booking->id }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap border-b border-gray-200">
                                            <a href="{{ route('admin.users.show', $booking->user->id) }}" class="text-indigo-600 hover:text-indigo-900">
                                                {{ $booking->user->name }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap border-b border-gray-200">
                                            <a href="{{ route('admin.travel-packages.show', $booking->travelPackage->id) }}" class="text-indigo-600 hover:text-indigo-900">
                                                {{ $booking->travelPackage->name }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap border-b border-gray-200">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                {{ $booking->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                                                {{ $booking->status === 'confirmed' ? 'bg-green-100 text-green-800' : '' }}
                                                {{ $booking->status === 'ongoing' ? 'bg-blue-100 text-blue-800' : '' }}
                                                {{ $booking->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                {{ $booking->status === 'on_hold' ? 'bg-orange-100 text-orange-800' : '' }}
                                                {{ $booking->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}
                                            ">
                                                {{ ucfirst($booking->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap border-b border-gray-200">
                                            {{ $booking->booking_date->format('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap border-b border-gray-200">
                                            {{ $booking->number_of_travelers }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap border-b border-gray-200 text-sm font-medium">
                                            <a href="{{ route('admin.bookings.edit', $booking->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Update Status</a>
                                            <a href="{{ route('admin.bookings.show', $booking->id) }}" class="text-blue-600 hover:text-blue-900">View</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap border-b border-gray-200 text-sm text-gray-500" colspan="7">
                                            No bookings found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $bookings->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
