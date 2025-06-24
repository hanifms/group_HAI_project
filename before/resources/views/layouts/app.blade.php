<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="Explore the world with our travel booking platform. Discover exciting destinations, book your next adventure, and create memories that last a lifetime.">

        <title>{{ config('app.name', 'myTravelV2') }} - {{ isset($header) ? (is_string($header) ? $header : strip_tags($header)) : 'Your Travel Experience' }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles
    </head>
    <body class="font-sans antialiased flex flex-col min-h-screen bg-gray-50">
        <x-banner />

        <!-- Top Navigation -->
        <div class="sticky top-0 z-50 bg-white shadow-sm border-b border-gray-100">
            @livewire('navigation-menu')
        </div>

        <div class="flex-grow">
            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow-sm border-b border-gray-100">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                            <div class="flex-1 min-w-0">
                                {{ $header }}
                            </div>
                            @if(isset($headerActions))
                                <div class="mt-4 sm:mt-0 sm:ml-4 flex space-x-3 flex-shrink-0">
                                    {{ $headerActions }}
                                </div>
                            @endif
                        </div>
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main class="py-6 md:py-10">
                {{ $slot }}
            </main>

            <!-- Footer -->
            <footer class="bg-white border-t border-gray-100">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <!-- Main Footer -->
                    <div class="py-12 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                        <!-- Company Info -->
                        <div>
                            <div class="flex items-center mb-4">
                                <x-application-logo class="block h-9 w-auto text-primary-600 mr-2" />
                                <span class="text-xl font-semibold text-primary-800">Travel Booking</span>
                            </div>
                            <p class="text-gray-500 mb-4 text-sm">Your gateway to unforgettable adventures and experiences around the world.</p>
                            <div class="flex space-x-4">
                                <a href="#" class="text-gray-400 hover:text-primary-600 transition-colors">
                                    <span class="sr-only">Facebook</span>
                                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                                <a href="#" class="text-gray-400 hover:text-primary-600 transition-colors">
                                    <span class="sr-only">Instagram</span>
                                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                                <a href="#" class="text-gray-400 hover:text-primary-600 transition-colors">
                                    <span class="sr-only">Twitter</span>
                                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84" />
                                    </svg>
                                </a>
                            </div>
                        </div>

                        <!-- Quick Links -->
                        <div>
                            <h3 class="text-sm font-semibold text-gray-800 tracking-wider uppercase mb-4">Quick Links</h3>
                            <ul class="space-y-2">
                                <li><a href="/" class="text-gray-500 hover:text-primary-600 transition-colors text-sm">Home</a></li>
                                <li><a href="#" class="text-gray-500 hover:text-primary-600 transition-colors text-sm">About Us</a></li>
                                <li><a href="#" class="text-gray-500 hover:text-primary-600 transition-colors text-sm">Destinations</a></li>
                                <li><a href="#" class="text-gray-500 hover:text-primary-600 transition-colors text-sm">Travel Guides</a></li>
                                <li><a href="#" class="text-gray-500 hover:text-primary-600 transition-colors text-sm">Contact Us</a></li>
                            </ul>
                        </div>

                        <!-- Support -->
                        <div>
                            <h3 class="text-sm font-semibold text-gray-800 tracking-wider uppercase mb-4">Support</h3>
                            <ul class="space-y-2">
                                <li><a href="#" class="text-gray-500 hover:text-primary-600 transition-colors text-sm">Help Center</a></li>
                                <li><a href="#" class="text-gray-500 hover:text-primary-600 transition-colors text-sm">FAQs</a></li>
                                <li><a href="#" class="text-gray-500 hover:text-primary-600 transition-colors text-sm">Terms & Conditions</a></li>
                                <li><a href="#" class="text-gray-500 hover:text-primary-600 transition-colors text-sm">Privacy Policy</a></li>
                                <li><a href="#" class="text-gray-500 hover:text-primary-600 transition-colors text-sm">Cookie Policy</a></li>
                            </ul>
                        </div>

                        <!-- Newsletter -->
                        <div>
                            <h3 class="text-sm font-semibold text-gray-800 tracking-wider uppercase mb-4">Stay Updated</h3>
                            <p class="text-gray-500 mb-4 text-sm">Subscribe to our newsletter for exclusive deals and travel inspiration.</p>
                            <form class="sm:flex">
                                <label for="email-address" class="sr-only">Email address</label>
                                <input id="email-address" name="email" type="email" autocomplete="email" required class="w-full px-3 py-2 placeholder-gray-400 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm" placeholder="Enter your email">
                                <div class="mt-3 sm:mt-0 sm:ml-3">
                                    <button type="submit" class="w-full px-4 py-2 bg-primary-600 text-white font-medium rounded-md shadow-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:text-sm">
                                        Subscribe
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Bottom Footer -->
                    <div class="border-t border-gray-200 py-6 flex flex-col md:flex-row justify-between items-center">
                        <div class="text-sm text-gray-500 mb-4 md:mb-0">
                            &copy; {{ date('Y') }} Travel Booking. All rights reserved.
                        </div>
                        <div class="flex space-x-6">
                            <a href="#" class="text-sm text-gray-500 hover:text-primary-600">Terms</a>
                            <a href="#" class="text-sm text-gray-500 hover:text-primary-600">Privacy</a>
                            <a href="#" class="text-sm text-gray-500 hover:text-primary-600">Accessibility</a>
                            <a href="#" class="text-sm text-gray-500 hover:text-primary-600">Sitemap</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>

        @stack('modals')

        @livewireScripts

        @stack('scripts')
    </body>
</html>
