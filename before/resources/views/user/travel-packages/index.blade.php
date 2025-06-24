<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Explore Travel Packages') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Notifications -->
            @if (session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md shadow-sm" role="alert">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p>{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md shadow-sm" role="alert">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p>{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Filter Section -->
            <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
                <div class="flex items-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900">Filter Packages</h3>
                </div>

                <form action="{{ route('travel-packages.index') }}" method="GET">
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                        <!-- Destination Filter -->
                        <div class="form-group">
                            <label for="destination" class="form-label">Destination</label>
                            <select id="destination" name="destination" class="form-input">
                                <option value="">All Destinations</option>
                                <option value="Korea" {{ request('destination') == 'Korea' ? 'selected' : '' }}>Korea</option>
                                <option value="Japan" {{ request('destination') == 'Japan' ? 'selected' : '' }}>Japan</option>
                                <option value="China" {{ request('destination') == 'China' ? 'selected' : '' }}>China</option>
                                <option value="Malaysia" {{ request('destination') == 'Malaysia' ? 'selected' : '' }}>Malaysia</option>
                                <option value="Australia" {{ request('destination') == 'Australia' ? 'selected' : '' }}>Australia</option>
                            </select>
                        </div>

                        <!-- Price Filter -->
                        <div class="form-group">
                            <label for="price" class="form-label">Max Price</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <span class="text-gray-500">$</span>
                                </div>
                                <input type="number" id="price" name="max_price" min="0" value="{{ request('max_price') }}"
                                       class="form-input pl-7" placeholder="Any price">
                            </div>
                        </div>

                        <!-- Date Filter (Optional) -->
                        <div class="form-group">
                            <label for="travel-date" class="form-label">Travel Month</label>
                            <select id="travel-date" name="travel_month" class="form-input">
                                <option value="">Any Time</option>
                                @foreach(range(1, 12) as $month)
                                    <option value="{{ $month }}" {{ request('travel_month') == $month ? 'selected' : '' }}>
                                        {{ date('F', mktime(0, 0, 0, $month, 1)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Filter Actions -->
                    <div class="mt-6 flex items-center justify-between">
                        <div class="text-sm text-gray-500">
                            <span>Showing {{ $travelPackages->count() }} packages</span>
                            @if(request()->anyFilled(['destination', 'max_price', 'travel_month']))
                                <a href="{{ route('travel-packages.index') }}" class="ml-2 text-primary-600 hover:text-primary-800">
                                    Clear filters
                                </a>
                            @endif
                        </div>
                        <button type="submit" class="btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            Apply Filters
                        </button>
                    </div>
                </form>
            </div>

            <!-- Travel Packages Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($travelPackages as $travelPackage)
                    <div class="bg-white overflow-hidden rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-gray-100 card-hover">
                        <!-- Package Image -->
                        <div class="h-52 bg-gradient-to-r from-primary-600 to-secondary-500 relative overflow-hidden">
                            <!-- Destination image placeholder - in production, replace with actual images -->
                            <div class="absolute inset-0 opacity-75 bg-center bg-cover"
                                 style="background-image: url('https://source.unsplash.com/featured/?{{ urlencode($travelPackage->destination) }},travel')">
                            </div>

                            <!-- Overlay -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>

                            <!-- Badge -->
                            <div class="absolute top-0 right-0 mt-3 mr-3">
                                <span class="badge bg-white/90 backdrop-blur-sm text-primary-800 shadow-sm">
                                    {{ $travelPackage->destination }}
                                </span>
                            </div>

                            <!-- Duration badge -->
                            <div class="absolute bottom-0 left-0 mb-3 ml-3">
                                <span class="badge bg-white/90 backdrop-blur-sm text-gray-800 shadow-sm flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $travelPackage->start_date->diffInDays($travelPackage->end_date) + 1 }} days
                                </span>
                            </div>
                        </div>

                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-primary-900 mb-2">{{ $travelPackage->name }}</h3>
                            <p class="text-gray-600 mb-4 text-sm">{{ Str::limit($travelPackage->description, 100) }}</p>

                            <div class="flex flex-wrap gap-3 mb-4">
                                <!-- Dates -->
                                <div class="flex items-center text-gray-500 text-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    {{ $travelPackage->start_date->format('M d') }} - {{ $travelPackage->end_date->format('M d, Y') }}
                                </div>

                                <!-- Available slots -->
                                <div class="flex items-center text-sm {{ $travelPackage->available_slots > 5 ? 'text-green-600' : 'text-red-600' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                    {{ $travelPackage->available_slots }} {{ Str::plural('slot', $travelPackage->available_slots) }}

                                    @if($travelPackage->available_slots <= 3 && $travelPackage->available_slots > 0)
                                        <span class="ml-1 text-xs text-red-600 font-medium">Almost full!</span>
                                    @endif
                                </div>
                            </div>

                            <div class="flex justify-between items-center mt-6 pt-4 border-t border-gray-100">
                                <div>
                                    <div class="text-primary-600 font-bold text-lg">
                                        ${{ number_format($travelPackage->price, 0) }}
                                    </div>
                                    <div class="text-gray-500 text-xs">per person</div>
                                </div>

                                <a href="{{ route('travel-packages.show', $travelPackage) }}" class="btn-primary">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white p-8 text-center rounded-xl shadow-sm">
                        <div class="bg-gray-50 rounded-full h-20 w-20 flex items-center justify-center mx-auto mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-medium text-gray-900 mb-2">No Travel Packages Found</h3>
                        <p class="text-gray-500 mb-6">We couldn't find any travel packages matching your criteria.</p>
                        <a href="{{ route('travel-packages.index') }}" class="btn-outline">
                            Reset Filters
                        </a>
                    </div>
                @endforelse
            </div>

            <!-- Pagination Links (if applicable) -->
            @if(isset($travelPackages) && method_exists($travelPackages, 'links'))
                <div class="mt-6">
                    {{ $travelPackages->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
