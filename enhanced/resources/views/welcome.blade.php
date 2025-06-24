<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Explore the world with our travel booking platform. Discover exciting destinations, book your next adventure, and create memories that last a lifetime.">

        <title>{{ config('app.name', 'myTravelV2') }} - Your Travel Experience</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-gray-50 text-gray-800 antialiased">
        <header class="bg-white shadow-sm">
            <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <!-- Logo -->
                        <a href="/" class="flex items-center">
                            <x-application-logo class="block h-9 w-auto text-primary-600" />
                            <span class="ml-3 text-xl font-semibold text-primary-800">myTravelV2</span>
                        </a>
                    </div>

                    <!-- Navigation Links -->
                    <div class="flex items-center space-x-4">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}" class="text-primary-600 hover:text-primary-800 px-3 py-2 rounded-md text-sm font-medium transition">Dashboard</a>
                            @else
                                <a href="{{ route('login') }}" class="text-primary-600 hover:text-primary-800 px-3 py-2 rounded-md text-sm font-medium transition">Log in</a>

                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-700 focus:bg-primary-700 active:bg-primary-800 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition ease-in-out duration-150">Register</a>
                                @endif
                            @endauth
                        @endif
                    </div>
                </div>
            </nav>
        </header>

        <!-- Hero Section -->
        <section class="relative bg-gradient-to-r from-primary-800 to-primary-600 text-white overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-24">
                <div class="md:flex md:items-center md:justify-between">
                    <div class="md:w-1/2 md:pr-12">
                        <h1 class="text-4xl md:text-5xl font-bold mb-6 text-white">Discover Your Next Adventure</h1>
                        <p class="text-lg md:text-xl mb-8 text-primary-100">Explore exotic destinations, book your dream vacation, and create memories that last a lifetime. Your journey begins here.</p>

                        <div class="flex flex-wrap gap-4">
                            <a href="{{ Route::has('register') ? route('register') : '#' }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-primary-800 bg-white hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-white">
                                Get Started
                            </a>
                            <a href="#destinations" class="inline-flex items-center px-6 py-3 border border-white text-base font-medium rounded-md shadow-sm text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-white">
                                Explore Destinations
                            </a>
                        </div>
                    </div>

                    <div class="mt-10 md:mt-0 md:w-1/2">
                        <img src="https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80"
                             alt="Travel adventure"
                             class="w-full h-auto rounded-lg shadow-xl">
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="py-16 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold mb-4 text-secondary-800">Why Choose Us</h2>
                    <p class="text-lg text-gray-600 max-w-2xl mx-auto">We provide the best travel experiences with personalized service and unforgettable destinations.</p>
                </div>

                <div class="grid md:grid-cols-3 gap-8">
                    <!-- Feature 1 -->
                    <div class="bg-white p-6 rounded-lg shadow-md border border-gray-100 hover:shadow-lg transition">
                        <div class="text-primary-600 mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold mb-2">Exclusive Packages</h3>
                        <p class="text-gray-600">Carefully curated travel experiences that match your preferences and budget.</p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="bg-white p-6 rounded-lg shadow-md border border-gray-100 hover:shadow-lg transition">
                        <div class="text-primary-600 mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold mb-2">Secure Booking</h3>
                        <p class="text-gray-600">Your payments and personal information are always protected with our secure system.</p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="bg-white p-6 rounded-lg shadow-md border border-gray-100 hover:shadow-lg transition">
                        <div class="text-primary-600 mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold mb-2">24/7 Support</h3>
                        <p class="text-gray-600">Our travel experts are available anytime to assist you before, during, and after your journey.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Popular Destinations Section -->
        <section id="destinations" class="py-16 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold mb-4 text-secondary-800">Popular Destinations</h2>
                    <p class="text-lg text-gray-600 max-w-2xl mx-auto">Explore some of our most sought-after travel packages.</p>
                </div>

                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Destination 1 -->
                    <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition">
                        <img src="https://images.unsplash.com/photo-1528127269322-539801943592?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8NHx8amFwYW58ZW58MHx8MHx8fDA%3D&auto=format&fit=crop&w=600&q=60"
                             alt="Japan" class="w-full h-48 object-cover">
                        <div class="p-6">
                            <h3 class="text-xl font-bold mb-2 text-secondary-800">Japan</h3>
                            <p class="text-gray-600 mb-4">Experience the perfect blend of ancient traditions and cutting-edge technology.</p>
                            <div class="flex items-center justify-between">
                                <span class="text-primary-600 font-semibold">From $1,500</span>
                                <span class="text-sm text-gray-500">7 days</span>
                            </div>
                        </div>
                    </div>

                    <!-- Destination 2 -->
                    <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition">
                        <img src="https://images.unsplash.com/photo-1518709268805-4e9042af9f23?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8bWFsYXlzaWF8ZW58MHx8MHx8fDA%3D&auto=format&fit=crop&w=600&q=60"
                             alt="Malaysia" class="w-full h-48 object-cover">
                        <div class="p-6">
                            <h3 class="text-xl font-bold mb-2 text-secondary-800">Malaysia</h3>
                            <p class="text-gray-600 mb-4">Discover stunning beaches, vibrant cities, and rich cultural heritage.</p>
                            <div class="flex items-center justify-between">
                                <span class="text-primary-600 font-semibold">From $800</span>
                                <span class="text-sm text-gray-500">5 days</span>
                            </div>
                        </div>
                    </div>

                    <!-- Destination 3 -->
                    <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition">
                        <img src="https://images.unsplash.com/photo-1514222709107-a180c68d72b4?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Nnx8YXVzdHJhbGlhfGVufDB8fDB8fHww&auto=format&fit=crop&w=600&q=60"
                             alt="Australia" class="w-full h-48 object-cover">
                        <div class="p-6">
                            <h3 class="text-xl font-bold mb-2 text-secondary-800">Australia</h3>
                            <p class="text-gray-600 mb-4">Explore diverse landscapes from vibrant cities to the stunning outback.</p>
                            <div class="flex items-center justify-between">
                                <span class="text-primary-600 font-semibold">From $2,200</span>
                                <span class="text-sm text-gray-500">10 days</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-12">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-700 focus:bg-primary-700 active:bg-primary-800 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition ease-in-out duration-150">View All Packages</a>
                        @else
                            <a href="{{ route('login') }}" class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-700 focus:bg-primary-700 active:bg-primary-800 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition ease-in-out duration-150">Login to Book Now</a>
                        @endauth
                    @endif
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-secondary-800 text-white py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid md:grid-cols-4 gap-8">
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Travel Booking</h3>
                        <p class="text-gray-300 mb-4">Your gateway to unforgettable adventures and experiences around the world.</p>
                        <div class="flex space-x-4">
                            <a href="#" class="text-gray-300 hover:text-white">
                                <span class="sr-only">Facebook</span>
                                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" />
                                </svg>
                            </a>
                            <a href="#" class="text-gray-300 hover:text-white">
                                <span class="sr-only">Instagram</span>
                                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd" />
                                </svg>
                            </a>
                            <a href="#" class="text-gray-300 hover:text-white">
                                <span class="sr-only">Twitter</span>
                                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84" />
                                </svg>
                            </a>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
                        <ul class="space-y-2">
                            <li><a href="#" class="text-gray-300 hover:text-white">Home</a></li>
                            <li><a href="#" class="text-gray-300 hover:text-white">About Us</a></li>
                            <li><a href="#" class="text-gray-300 hover:text-white">Destinations</a></li>
                            <li><a href="#" class="text-gray-300 hover:text-white">Contact</a></li>
                            <li><a href="#" class="text-gray-300 hover:text-white">FAQs</a></li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold mb-4">Contact Us</h3>
                        <ul class="space-y-2">
                            <li class="flex items-start">
                                <svg class="h-6 w-6 mr-2 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span class="text-gray-300">123 Travel Street, City, Country</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="h-6 w-6 mr-2 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                <span class="text-gray-300">+1 234 567 890</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="h-6 w-6 mr-2 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                <span class="text-gray-300">info@travelbooking.com</span>
                            </li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold mb-4">Newsletter</h3>
                        <p class="text-gray-300 mb-4">Subscribe to our newsletter for the latest travel deals and updates.</p>
                        <form class="flex">
                            <input type="email" placeholder="Your email" class="w-full px-4 py-2 rounded-l-md focus:outline-none text-gray-900">
                            <button type="submit" class="bg-primary-600 hover:bg-primary-700 px-4 py-2 rounded-r-md">
                                <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>

                <div class="border-t border-gray-700 mt-12 pt-8 text-center text-gray-300">
                    <p>&copy; {{ date('Y') }} myTravelV2. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </body>
</html>
