<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manage Travel Packages') }}
            </h2>
            <a href="{{ route('admin.travel-packages.create') }}" class="btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Add New Package
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats Overview Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-lg shadow px-5 py-4 border-l-4 border-blue-600">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-blue-100 rounded-full p-3">
                            <svg class="h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <div class="text-sm font-medium text-gray-500">Total Packages</div>
                            <div class="text-lg font-semibold text-gray-800">{{ \App\Models\TravelPackage::count() }}</div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow px-5 py-4 border-l-4 border-green-600">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-100 rounded-full p-3">
                            <svg class="h-6 w-6 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <div class="text-sm font-medium text-gray-500">Active Packages</div>
                            <div class="text-lg font-semibold text-gray-800">{{ \App\Models\TravelPackage::where('is_visible', true)->count() }}</div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow px-5 py-4 border-l-4 border-amber-500">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-amber-100 rounded-full p-3">
                            <svg class="h-6 w-6 text-amber-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <div class="text-sm font-medium text-gray-500">Upcoming</div>
                            <div class="text-lg font-semibold text-gray-800">{{ \App\Models\TravelPackage::where('start_date', '>', now())->count() }}</div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow px-5 py-4 border-l-4 border-red-600">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-red-100 rounded-full p-3">
                            <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <div class="text-sm font-medium text-gray-500">Total Bookings</div>
                            <div class="text-lg font-semibold text-gray-800">{{ \App\Models\Booking::count() }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Card -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-6 flex flex-wrap justify-between items-center border-b pb-4">
                        <h2 class="text-lg font-semibold text-gray-800">All Travel Packages</h2>

                        <!-- Search and Filter -->
                        <div class="mt-3 md:mt-0 flex flex-wrap gap-2">
                            <div class="relative">
                                <input type="text" class="form-input pr-10" placeholder="Search packages...">
                                <div class="absolute inset-y-0 right-0 flex items-center mr-3 text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                            </div>

                            <select class="form-select">
                                <option>All Packages</option>
                                <option>Visible Only</option>
                                <option>Hidden Only</option>
                                <option>Upcoming</option>
                                <option>Past</option>
                            </select>
                        </div>
                    </div>

                    <!-- Notification Messages -->
                    @if (session('success'))
                        <div class="notification notification-success mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>{{ session('success') }}</span>
                        </div>
                    @endif

                    @if (session('warning'))
                        <div class="notification notification-warning mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <span>{{ session('warning') }}</span>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="notification notification-error mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>{{ session('error') }}</span>
                        </div>
                    @endif

                <div class="overflow-x-auto shadow rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-blue-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-blue-800 uppercase tracking-wider">
                                    ID
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-blue-800 uppercase tracking-wider">
                                    Package Details
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-blue-800 uppercase tracking-wider">
                                    Destination
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-blue-800 uppercase tracking-wider">
                                    Price
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-blue-800 uppercase tracking-wider">
                                    Dates
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-blue-800 uppercase tracking-wider">
                                    Status
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-blue-800 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($travelPackages as $package)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        #{{ $package->id }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-md flex items-center justify-center text-blue-600">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                                                </svg>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900 whitespace-nowrap max-w-xs truncate">
                                                    {{ $package->name }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $package->available_slots }} slots available
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center text-sm text-gray-700">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            {{ $package->destination }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 font-medium">${{ number_format($package->price, 2) }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center text-sm text-gray-700">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            {{ $package->start_date->format('M d, Y') }} - {{ $package->end_date->format('M d, Y') }}
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1">
                                            @if($package->start_date->isPast() && $package->end_date->isFuture())
                                                <span class="text-green-600">In Progress</span>
                                            @elseif($package->start_date->isFuture())
                                                <span class="text-blue-600">Upcoming</span>
                                            @else
                                                <span class="text-gray-500">Completed</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex flex-col space-y-2">
                                            <span class="badge {{ $package->is_visible ? 'badge-success' : 'badge-danger' }}">
                                                {{ $package->is_visible ? 'Visible' : 'Hidden' }}
                                            </span>

                                            @if($package->featured)
                                                <span class="badge badge-warning">Featured</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-y-2">
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('admin.travel-packages.show', $package) }}" class="btn-link">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                View
                                            </a>

                                            <a href="{{ route('admin.travel-packages.edit', $package) }}" class="btn-link text-amber-600">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                                Edit
                                            </a>
                                        </div>

                                        <div class="flex items-center space-x-2 pt-1">
                                            <form action="{{ route('admin.travel-packages.toggle-visibility', $package) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn-link {{ $package->is_visible ? 'text-gray-600' : 'text-green-600' }}">
                                                    @if($package->is_visible)
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                                        </svg>
                                                        Hide
                                                    @else
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                        </svg>
                                                        Show
                                                    @endif
                                                </button>
                                            </form>

                                            <form action="{{ route('admin.travel-packages.destroy', $package) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-link text-red-600" onclick="return confirm('Are you sure you want to delete this package? This action cannot be undone.')">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 whitespace-nowrap text-sm text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <div class="text-gray-600 font-medium text-lg">No travel packages found</div>
                                            <p class="text-gray-500 mt-1">Create your first travel package to get started</p>
                                            <a href="{{ route('admin.travel-packages.create') }}" class="btn-primary mt-4">
                                                Create New Package
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <!-- Pagination Links (if available) -->
                    @if(method_exists($travelPackages, 'links'))
                        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                            {{ $travelPackages->links() }}
                        </div>
                    @endif
                </div>
                </div>
            </div>

            <!-- Quick Access Card -->
            <div class="mt-6 bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Quick Actions</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <a href="{{ route('admin.travel-packages.create') }}" class="border border-gray-200 rounded-lg p-4 hover:bg-blue-50 hover:border-blue-200 transition-colors flex items-center">
                        <div class="rounded-full bg-blue-100 p-3 mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-800">New Travel Package</h4>
                            <p class="text-sm text-gray-500">Create a new travel package</p>
                        </div>
                    </a>

                    <a href="{{ route('admin.bookings.index') }}" class="border border-gray-200 rounded-lg p-4 hover:bg-blue-50 hover:border-blue-200 transition-colors flex items-center">
                        <div class="rounded-full bg-green-100 p-3 mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-800">Manage Bookings</h4>
                            <p class="text-sm text-gray-500">View and manage bookings</p>
                        </div>
                    </a>

                    <a href="{{ route('admin.users.index') }}" class="border border-gray-200 rounded-lg p-4 hover:bg-blue-50 hover:border-blue-200 transition-colors flex items-center">
                        <div class="rounded-full bg-amber-100 p-3 mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-800">Manage Users</h4>
                            <p class="text-sm text-gray-500">View and manage users</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
