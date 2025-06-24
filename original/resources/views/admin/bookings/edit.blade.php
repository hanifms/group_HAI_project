<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Update Booking Status') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="mb-6">
                    <a href="{{ route('admin.bookings.index') }}" class="text-indigo-600 hover:text-indigo-900">
                        &larr; Back to All Bookings
                    </a>
                </div>

                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="mb-8">
                    <h3 class="text-lg font-semibold mb-4">Booking Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p><strong>Booking ID:</strong> #{{ $booking->id }}</p>
                            <p><strong>User:</strong> {{ $booking->user->name }}</p>
                            <p><strong>Travel Package:</strong> {{ $booking->travelPackage->name }}</p>
                            <p><strong>Destination:</strong> {{ $booking->travelPackage->destination }}</p>
                        </div>
                        <div>
                            <p><strong>Booking Date:</strong> {{ $booking->booking_date->format('M d, Y') }}</p>
                            <p><strong>Travel Dates:</strong> {{ $booking->travelPackage->start_date->format('M d, Y') }} - {{ $booking->travelPackage->end_date->format('M d, Y') }}</p>
                            <p><strong>Number of Travelers:</strong> {{ $booking->number_of_travelers }}</p>
                            <p><strong>Current Status:</strong>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    {{ $booking->status === 'completed' ? 'bg-green-100 text-green-800' :
                                       ($booking->status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                                       ($booking->status === 'on_hold' ? 'bg-red-100 text-red-800' :
                                        'bg-blue-100 text-blue-800')) }}">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.bookings.update', $booking) }}">
                    @csrf
                    @method('PUT')

                    @if($booking->review()->exists())
                        <div class="mb-4 bg-indigo-50 border-l-4 border-indigo-500 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-indigo-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M18 13V5a2 2 0 00-2-2H4a2 2 0 00-2 2v8a2 2 0 002 2h3l3 3 3-3h3a2 2 0 002-2zM5 7a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1zm1 3a1 1 0 100 2h3a1 1 0 100-2H6z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-indigo-700">
                                        <span class="font-bold">Review Present:</span> This booking has a review. If the booking status is changed from "completed", the review will become inconsistent with the booking status.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="mb-4">
                        <label for="status" class="block font-medium text-sm text-gray-700">Update Status</label>
                        <select id="status" name="status" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            @foreach($statusOptions as $value => $label)
                                <option value="{{ $value }}" {{ $booking->status === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-yellow-700">
                                    <strong>Important:</strong> If you change the status to "Completed", the user will be able to leave a review for this booking.
                                    <strong>Once a review has been left, you cannot change the status from "Completed" to avoid inconsistencies.</strong>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Update Status
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
