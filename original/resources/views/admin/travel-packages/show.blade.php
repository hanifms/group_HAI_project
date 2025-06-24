<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Package Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-lg font-semibold">{{ $travelPackage->name }}</h2>
                    <div>
                        <a href="{{ route('admin.travel-packages.edit', $travelPackage) }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600 focus:bg-yellow-600 active:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition ease-in-out duration-150 mr-2">
                            Edit
                        </a>
                        <a href="{{ route('admin.travel-packages.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Back to List
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <div class="bg-gray-50 p-4 rounded-lg mb-4">
                            <h3 class="text-md font-semibold mb-2">Package Information</h3>
                            <p><strong>Name:</strong> {{ $travelPackage->name }}</p>
                            <p><strong>Destination:</strong> {{ $travelPackage->destination }}</p>
                            <p><strong>Price:</strong> ${{ number_format($travelPackage->price, 2) }}</p>
                            <p><strong>Start Date:</strong> {{ $travelPackage->start_date->format('M d, Y') }}</p>
                            <p><strong>End Date:</strong> {{ $travelPackage->end_date->format('M d, Y') }}</p>
                            <p><strong>Available Slots:</strong> {{ $travelPackage->available_slots }}</p>
                        </div>
                    </div>

                    <div>
                        <div class="bg-gray-50 p-4 rounded-lg mb-4">
                            <h3 class="text-md font-semibold mb-2">Description</h3>
                            <p>{{ $travelPackage->description }}</p>
                        </div>
                    </div>
                </div>

                <div class="mt-6">
                    <h3 class="text-md font-semibold mb-2">Bookings for this Package</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        User
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Date Booked
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Travelers
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($travelPackage->bookings as $booking)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $booking->user->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $booking->booking_date->format('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                {{ $booking->status === 'completed' ? 'bg-green-100 text-green-800' :
                                                   ($booking->status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                                                   ($booking->status === 'on_hold' ? 'bg-red-100 text-red-800' :
                                                   'bg-blue-100 text-blue-800')) }}">
                                                {{ ucfirst($booking->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $booking->number_of_travelers }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('admin.bookings.show', $booking) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                            No bookings for this package
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mt-8 border-t pt-4 flex justify-end">
                    <form action="{{ route('admin.travel-packages.destroy', $travelPackage) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150" onclick="return confirm('Are you sure you want to delete this package?')">
                            Delete Package
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
