<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'myTravelV2') }} - {{ isset($header) ? (is_string($header) ? $header : strip_tags($header)) : 'Your Travel Experience' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    @livewireStyles

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js CSP-compatible build (CDN) -->
    <script defer src="https://unpkg.com/alpinejs@3.13.0/dist/cdn.min.js"></script>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.0/dist/cdn.min.js"></script>


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
    <!-- your existing footer content -->
    </footer>

    </div>

    <!-- ✅ FIXED LOCATION BELOW -->
    @stack('modals')
    @livewireScripts
    @stack('scripts')
</body>
</html>
