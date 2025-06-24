<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage Reviews') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
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
                                        Comment
                                    </th>
                                    <th class="px-6 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Rating
                                    </th>
                                    <th class="px-6 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Date
                                    </th>
                                    <th class="px-6 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reviews as $review)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap border-b border-gray-200">
                                            {{ $review->id }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap border-b border-gray-200">
                                            <a href="{{ route('admin.users.show', $review->user->id) }}" class="text-indigo-600 hover:text-indigo-900">
                                                {{ $review->user->name }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap border-b border-gray-200">
                                            <a href="{{ route('admin.travel-packages.show', $review->booking->travelPackage->id) }}" class="text-indigo-600 hover:text-indigo-900">
                                                {{ $review->booking->travelPackage->name }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 border-b border-gray-200">
                                            <div class="truncate max-w-xs">{{ $review->comment }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap border-b border-gray-200">
                                            <div class="flex items-center">
                                                <span class="text-yellow-500">★</span>
                                                <span class="ml-1">{{ $review->rating }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap border-b border-gray-200">
                                            {{ $review->created_at->format('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap border-b border-gray-200 text-sm font-medium">
                                            <a href="{{ route('admin.reviews.show', $review->id) }}" class="text-blue-600 hover:text-blue-900">View</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500 border-b border-gray-200">
                                            No reviews found
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $reviews->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
