<x-guest-layout>
    <div class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center sm:pt-0">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="flex items-center pt-8 sm:justify-start sm:pt-0">
                <div class="px-4 text-lg text-gray-500 border-r border-gray-400 tracking-wider">
                    @yield('code')
                </div>

                <div class="ml-4 text-lg text-primary-500 uppercase tracking-wider">
                    @yield('message')
                </div>
            </div>

            <div class="mt-8 bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg p-6">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="ml-4 text-lg leading-7 font-semibold"><span class="text-primary-700 dark:text-white">What happened?</span></div>
                </div>

                <div class="ml-12">
                    <div class="mt-2 text-gray-600 dark:text-gray-400 text-sm">
                        @yield('details', "We're sorry, but something went wrong. Please try again later or contact support if the problem persists.")
                    </div>
                </div>

                <div class="mt-6 text-center">
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-700 focus:bg-primary-700 active:bg-primary-800 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Return to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
