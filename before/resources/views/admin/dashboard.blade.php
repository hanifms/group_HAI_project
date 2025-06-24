<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-primary-900 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Admin Dashboard Header -->
            <div class="bg-gradient-to-r from-primary-600 to-secondary-600 rounded-xl shadow-md p-6 mb-8 text-white overflow-hidden relative">
                <div class="absolute inset-0 bg-black/10 backdrop-blur-sm"></div>
                <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between">
                    <div class="mb-4 md:mb-0">
                        <h2 class="text-2xl font-bold text-white mb-1">Welcome to the Admin Dashboard</h2>
                        <p class="text-white/80">Manage your travel booking platform from this control panel.</p>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('admin.travel-packages.create') }}" class="btn-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            New Package
                        </a>
                        <a href="{{ route('admin.bookings.index') }}" class="btn-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            Manage Bookings
                        </a>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Users Stats -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden transition-all duration-300 hover:shadow-md card-hover">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-primary-100 rounded-full p-3">
                                <svg class="h-6 w-6 text-primary-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h2 class="text-gray-600 text-sm font-medium">Total Users</h2>
                                <p class="text-gray-900 text-2xl font-bold">{{ $userCount }}</p>
                            </div>
                        </div>
                        <div class="mt-4 pt-3 border-t border-gray-100">
                            <a href="{{ route('admin.users.index') }}" class="group flex items-center justify-between text-sm font-medium text-primary-600 hover:text-primary-800 transition-colors">
                                <span>View all users</span>
                                <svg class="h-4 w-4 transition-transform group-hover:translate-x-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </a>
                        </div>
                    </div>
                    <div class="h-1 bg-primary-600"></div>
                </div>

                <!-- Travel Packages Stats -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden transition-all duration-300 hover:shadow-md card-hover">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-secondary-100 rounded-full p-3">
                                <svg class="h-6 w-6 text-secondary-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h2 class="text-gray-600 text-sm">Travel Packages</h2>
                                <p class="text-gray-900 text-xl font-semibold">{{ $packageCount }}</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.travel-packages.index') }}" class="group mt-3 flex items-center text-sm font-medium text-secondary-600 hover:text-secondary-800 transition-colors">
                            <span>Manage packages</span>
                            <svg class="ml-1 h-4 w-4 transition-transform group-hover:translate-x-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </div>

                    <!-- Bookings Stats -->
                    <div class="bg-white rounded-lg shadow-sm hover:shadow transition-shadow duration-300 p-5 border-l-4 border-green-500">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-green-100 rounded-full p-3">
                                <svg class="h-6 w-6 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h2 class="text-gray-600 text-sm">Active Bookings</h2>
                                <p class="text-gray-900 text-xl font-semibold">{{ $activeBookingsCount }}</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.bookings.index') }}" class="group mt-3 flex items-center text-sm font-medium text-green-600 hover:text-green-800 transition-colors">
                            <span>Manage bookings</span>
                            <svg class="ml-1 h-4 w-4 transition-transform group-hover:translate-x-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </div>

                    <!-- Reviews Stats -->
                    <div class="bg-white rounded-lg shadow-sm hover:shadow transition-shadow duration-300 p-5 border-l-4 border-purple-500">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-purple-100 rounded-full p-3">
                                <svg class="h-6 w-6 text-purple-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h2 class="text-gray-600 text-sm">Reviews</h2>
                                <p class="text-gray-900 text-xl font-semibold">{{ $reviewCount }}</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.bookings.index') }}?status=completed" class="group mt-3 flex items-center text-sm font-medium text-purple-600 hover:text-purple-800 transition-colors">
                            <span>View reviews</span>
                            <svg class="ml-1 h-4 w-4 transition-transform group-hover:translate-x-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="mb-8">
                    <div class="flex items-center mb-4">
                        <h3 class="text-lg font-semibold text-primary-900">Quick Actions</h3>
                        <div class="ml-2 h-1 w-16 bg-primary-200 rounded"></div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <a href="{{ route('admin.travel-packages.create') }}"
                           class="group bg-white border border-gray-100 rounded-lg p-5 flex items-center shadow-sm hover:shadow-md hover:border-primary-200 transition-all">
                            <div class="flex-shrink-0 bg-primary-100 rounded-full p-3 mr-4 group-hover:bg-primary-200 transition-colors">
                                <svg class="h-6 w-6 text-primary-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-gray-800 group-hover:text-primary-900 transition-colors">Add New Package</p>
                                <p class="text-sm text-gray-500">Create a new travel package</p>
                            </div>
                        </a>

                        <a href="{{ route('admin.users.create') }}"
                           class="group bg-white border border-gray-100 rounded-lg p-5 flex items-center shadow-sm hover:shadow-md hover:border-primary-200 transition-all">
                            <div class="flex-shrink-0 bg-primary-100 rounded-full p-3 mr-4 group-hover:bg-primary-200 transition-colors">
                                <svg class="h-6 w-6 text-primary-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-gray-800 group-hover:text-primary-900 transition-colors">Add New User</p>
                                <p class="text-sm text-gray-500">Create a new user account</p>
                            </div>
                        </a>

                        <a href="{{ route('admin.bookings.index') }}?status=pending"
                           class="group bg-white border border-gray-100 rounded-lg p-5 flex items-center shadow-sm hover:shadow-md hover:border-primary-200 transition-all">
                            <div class="flex-shrink-0 bg-amber-100 rounded-full p-3 mr-4 group-hover:bg-amber-200 transition-colors">
                                <svg class="h-6 w-6 text-amber-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-gray-800 group-hover:text-amber-900 transition-colors">Pending Bookings</p>
                                <p class="text-sm text-gray-500">Manage booking requests</p>
                            </div>
                        </a>

                        <a href="{{ route('admin.bookings.index') }}?status=ongoing"
                           class="group bg-white border border-gray-100 rounded-lg p-5 flex items-center shadow-sm hover:shadow-md hover:border-primary-200 transition-all">
                            <div class="flex-shrink-0 bg-green-100 rounded-full p-3 mr-4 group-hover:bg-green-200 transition-colors">
                                <svg class="h-6 w-6 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-gray-800 group-hover:text-green-900 transition-colors">Ongoing Bookings</p>
                                <p class="text-sm text-gray-500">Check active trips</p>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center">
                            <h3 class="text-lg font-semibold text-primary-900">Recent Bookings</h3>
                            <div class="ml-2 h-1 w-16 bg-primary-200 rounded"></div>
                        </div>
                        <a href="{{ route('admin.bookings.index') }}" class="group flex items-center text-sm font-medium text-primary-600 hover:text-primary-800 transition-colors">
                            <span>View all bookings</span>
                            <svg class="ml-1 h-4 w-4 transition-transform group-hover:translate-x-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </div>

                    <div class="bg-white rounded-lg border border-gray-100 overflow-hidden shadow-sm">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            User
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Package
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Date
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Action
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($recentBookings as $booking)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-8 w-8 bg-primary-100 rounded-full flex items-center justify-center text-primary-700 font-medium">
                                                        {{ substr($booking->user->name, 0, 1) }}
                                                    </div>
                                                    <div class="ml-3">
                                                        <div class="text-sm font-medium text-gray-900">{{ $booking->user->name }}</div>
                                                        <div class="text-xs text-gray-500">{{ $booking->user->email }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $booking->travelPackage->name }}</div>
                                                <div class="text-xs text-gray-500">{{ $booking->travelPackage->destination }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="status-badge
                                                    {{ $booking->status === 'completed' ? 'bg-green-100 text-green-800' :
                                                    ($booking->status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                                                    ($booking->status === 'on_hold' ? 'bg-red-100 text-red-800' :
                                                        'bg-blue-100 text-blue-800')) }}">
                                                    {{ ucfirst($booking->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $booking->booking_date->format('M d, Y') }}</div>
                                                <div class="text-xs text-gray-500">{{ $booking->booking_date->format('h:i A') }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <a href="{{ route('admin.bookings.show', $booking->id) }}"
                                                   class="inline-flex items-center px-3 py-1 border border-primary-300 text-sm leading-4 font-medium rounded-md text-primary-700 bg-white hover:bg-primary-50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-colors">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                    View
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-6 py-8 whitespace-nowrap text-sm text-gray-500 text-center">
                                                <div class="flex flex-col items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    <p>No recent bookings found</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
