<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Book Travel Package') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900">{{ $travelPackage->name }}</h3>
                        <p class="mt-1 text-sm text-gray-600">{{ $travelPackage->description }}</p>

                        <div class="mt-4 flex flex-col sm:flex-row sm:space-x-4">
                            <div class="mb-2 sm:mb-0">
                                <span class="text-sm font-medium text-gray-500">Destination:</span>
                                <span class="ml-1 text-sm text-gray-900">{{ $travelPackage->destination }}</span>
                            </div>
                            <div class="mb-2 sm:mb-0">
                                <span class="text-sm font-medium text-gray-500">Price per person:</span>
                                <span class="ml-1 text-sm text-gray-900">${{ number_format($travelPackage->price, 2) }}</span>
                            </div>
                            <div class="mb-2 sm:mb-0">
                                <span class="text-sm font-medium text-gray-500">Travel dates:</span>
                                <span class="ml-1 text-sm text-gray-900">
                                    {{ $travelPackage->start_date->format('M d, Y') }} - {{ $travelPackage->end_date->format('M d, Y') }}
                                </span>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-500">Available slots:</span>
                                <span class="ml-1 text-sm text-gray-900">{{ $travelPackage->available_slots }}</span>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('bookings.store', $travelPackage) }}">
                        @csrf

                        <div class="mt-6">
                            <x-label for="number_of_travelers" :value="__('Number of Travelers')" />
                            <x-input id="number_of_travelers" class="block mt-1 w-full" type="number" min="1" max="{{ $travelPackage->available_slots }}" name="number_of_travelers" value="{{ old('number_of_travelers', 1) }}" required />
                            <div class="mt-1 text-sm text-gray-500">Maximum: {{ $travelPackage->available_slots }} travelers</div>
                            @error('number_of_travelers')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mt-6">
                            <p class="text-sm font-medium text-gray-700">Total price: $<span id="totalPrice">{{ number_format($travelPackage->price, 2) }}</span></p>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('travel-packages.show', $travelPackage) }}" class="text-sm text-gray-600 underline mr-4">Cancel</a>
                            <x-button>
                                {{ __('Book Now') }}
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const numberOfTravelersInput = document.getElementById('number_of_travelers');
            const totalPriceElement = document.getElementById('totalPrice');
            const pricePerPerson = {{ $travelPackage->price }};

            numberOfTravelersInput.addEventListener('input', function() {
                const numberOfTravelers = parseInt(this.value) || 0;
                const totalPrice = (numberOfTravelers * pricePerPerson).toFixed(2);
                totalPriceElement.textContent = totalPrice;
            });
        });
    </script>
</x-app-layout>
