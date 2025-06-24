<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New Travel Package') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="mb-6">
                    <a href="{{ route('admin.travel-packages.index') }}" class="text-indigo-600 hover:text-indigo-900">
                        &larr; Back to Travel Packages
                    </a>
                </div>

                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <strong>Whoops!</strong> There were some problems with your input.<br><br>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.travel-packages.store') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <div class="mb-4">
                                <label for="name" class="block font-medium text-sm text-gray-700">Name</label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                            </div>

                            <div class="mb-4">
                                <label for="destination" class="block font-medium text-sm text-gray-700">Destination</label>
                                <input type="text" name="destination" id="destination" value="{{ old('destination') }}" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                            </div>

                            <div class="mb-4">
                                <label for="price" class="block font-medium text-sm text-gray-700">Price</label>
                                <div class="mt-1 flex rounded-md shadow-sm">
                                    <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">$</span>
                                    <input type="number" name="price" id="price" value="{{ old('price') }}" min="0" step="0.01" class="focus:ring-indigo-500 focus:border-indigo-500 flex-1 block w-full rounded-none rounded-r-md border-gray-300" required>
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="mb-4">
                                <label for="start_date" class="block font-medium text-sm text-gray-700">Start Date</label>
                                <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                            </div>

                            <div class="mb-4">
                                <label for="end_date" class="block font-medium text-sm text-gray-700">End Date</label>
                                <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                            </div>

                            <div class="mb-4">
                                <label for="available_slots" class="block font-medium text-sm text-gray-700">Available Slots</label>
                                <input type="number" name="available_slots" id="available_slots" value="{{ old('available_slots') }}" min="0" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                            </div>
                        </div>
                    </div>

                    <div class="my-4">
                        <label for="description" class="block font-medium text-sm text-gray-700">Description</label>
                        <textarea name="description" id="description" rows="5" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>{{ old('description') }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label for="is_visible" class="inline-flex items-center">
                            <input type="checkbox" name="is_visible" id="is_visible" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" {{ old('is_visible') ? 'checked' : '' }} value="1">
                            <span class="ml-2 text-sm text-gray-600">Make this package visible to users</span>
                        </label>
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Create Package
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
