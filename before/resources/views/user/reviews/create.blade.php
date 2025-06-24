<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Leave a Review') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900">Review for: {{ $booking->travelPackage->name }} to {{ $booking->travelPackage->destination }}</h3>
                    <p class="text-sm text-gray-600">Booking Date: {{ $booking->booking_date->format('F j, Y') }}</p>
                </div>

                <form method="POST" action="{{ route('reviews.store', $booking) }}">
                    @csrf

                    <div class="mb-6">
                        <x-label for="rating" :value="__('Rating')" />

                        <div class="mt-1 flex items-center">
                            <div class="flex space-x-2">
                                <label class="rating-label">
                                    <input type="radio" name="rating" value="1" required class="hidden" />
                                    <span class="text-2xl cursor-pointer hover:text-yellow-500">★</span>
                                </label>
                                <label class="rating-label">
                                    <input type="radio" name="rating" value="2" class="hidden" />
                                    <span class="text-2xl cursor-pointer hover:text-yellow-500">★</span>
                                </label>
                                <label class="rating-label">
                                    <input type="radio" name="rating" value="3" class="hidden" />
                                    <span class="text-2xl cursor-pointer hover:text-yellow-500">★</span>
                                </label>
                                <label class="rating-label">
                                    <input type="radio" name="rating" value="4" class="hidden" />
                                    <span class="text-2xl cursor-pointer hover:text-yellow-500">★</span>
                                </label>
                                <label class="rating-label">
                                    <input type="radio" name="rating" value="5" class="hidden" />
                                    <span class="text-2xl cursor-pointer hover:text-yellow-500">★</span>
                                </label>
                            </div>
                            <span class="ml-2 text-sm text-gray-500">(1 to 5 stars)</span>
                        </div>

                        @error('rating')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <x-label for="comment" :value="__('Comment')" />
                        <textarea
                            id="comment"
                            name="comment"
                            rows="4"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            placeholder="Share your travel experience...">{{ old('comment') }}</textarea>

                        @error('comment')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <a href="{{ route('bookings.show', $booking) }}" class="text-gray-600 hover:underline mr-4">Cancel</a>

                        <x-button>
                            {{ __('Submit Review') }}
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const stars = document.querySelectorAll('.rating-label span');
            const inputs = document.querySelectorAll('.rating-label input');

            // Function to update stars
            function updateStars(rating) {
                stars.forEach((star, index) => {
                    if (index < rating) {
                        star.classList.add('text-yellow-500');
                    } else {
                        star.classList.remove('text-yellow-500');
                    }
                });
            }

            // Event listeners for each star
            stars.forEach((star, index) => {
                star.addEventListener('click', () => {
                    const rating = index + 1;
                    inputs[index].checked = true;
                    updateStars(rating);
                });

                star.addEventListener('mouseover', () => {
                    updateStars(index + 1);
                });

                star.addEventListener('mouseout', () => {
                    const selectedRating = Array.from(inputs).findIndex(input => input.checked) + 1;
                    updateStars(selectedRating > 0 ? selectedRating : 0);
                });
            });
        });
    </script>
    @endpush
</x-app-layout>
