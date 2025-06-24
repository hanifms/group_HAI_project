<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <!-- Profile Header with User Information -->
                <div class="px-6 py-5 bg-blue-50 border-b border-gray-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                <img class="h-16 w-16 rounded-full object-cover border-4 border-white shadow" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}">
                            @else
                                <div class="h-16 w-16 rounded-full bg-blue-100 flex items-center justify-center text-blue-800 text-xl font-bold uppercase border-4 border-white shadow">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                            @endif
                        </div>
                        <div class="ml-6">
                            <h3 class="text-2xl font-bold text-gray-800">{{ Auth::user()->name }}</h3>
                            <p class="text-gray-500">{{ Auth::user()->email }}</p>
                            <div class="mt-1 flex items-center">
                                <span class="badge badge-info">{{ Auth::user()->role->name ?? 'User' }}</span>
                                <span class="mx-2 text-gray-300">|</span>
                                <span class="text-sm text-gray-500">Member since {{ Auth::user()->created_at->format('F Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 p-4 sm:px-6 border-b">
                    <div class="flex overflow-x-auto space-x-4 pb-2" role="tablist">
                        <a href="javascript:void(0)" onclick="switchTab('personal-info')" class="profile-tab active" data-target="personal-info">Personal Information</a>
                        <a href="javascript:void(0)" onclick="switchTab('password')" class="profile-tab" data-target="password">Password</a>
                        @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                            <a href="javascript:void(0)" onclick="switchTab('two-factor')" class="profile-tab" data-target="two-factor">Two Factor Authentication</a>
                        @endif
                        <a href="javascript:void(0)" onclick="switchTab('browser-sessions')" class="profile-tab" data-target="browser-sessions">Browser Sessions</a>
                        @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
                            <a href="javascript:void(0)" onclick="switchTab('delete-account')" class="profile-tab" data-target="delete-account">Delete Account</a>
                        @endif
                    </div>
                </div>

                <div class="p-6">
                    <!-- Tab Content Sections -->
                    <div id="tab-personal-info" class="tab-content active">
                        @if (Laravel\Fortify\Features::canUpdateProfileInformation())
                            @livewire('profile.update-profile-information-form')
                        @endif
                    </div>

                    <div id="tab-password" class="tab-content hidden">
                        @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
                            @livewire('profile.update-password-form')
                        @endif
                    </div>

                    @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                        <div id="tab-two-factor" class="tab-content hidden">
                            @livewire('profile.two-factor-authentication-form')
                        </div>
                    @endif

                    <div id="tab-browser-sessions" class="tab-content hidden">
                        @livewire('profile.logout-other-browser-sessions-form')
                    </div>

                    @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
                        <div id="tab-delete-account" class="tab-content hidden">
                            @livewire('profile.delete-user-form')
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>    <!-- Direct script for profile tabs -->
    <script>
        // Immediately available function for tab switching
        function switchTab(tabTarget) {
            console.log('Switching to tab:', tabTarget);

            // Hide all tab content
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
                content.classList.remove('active');
            });

            // Show selected tab content
            const selectedTab = document.getElementById('tab-' + tabTarget);
            if (selectedTab) {
                selectedTab.classList.remove('hidden');
                selectedTab.classList.add('active');
            } else {
                console.error('Tab content not found:', 'tab-' + tabTarget);
            }

            // Update active tab styling
            document.querySelectorAll('.profile-tab').forEach(tab => {
                if (tab.dataset.target === tabTarget) {
                    tab.classList.add('active');
                } else {
                    tab.classList.remove('active');
                }
            });
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Profile page initialized');
        });
    </script>

    @push('scripts')
    <script>
        // Additional tab initialization to ensure it works
        document.addEventListener('livewire:load', function() {
            // Re-initialize tab functionality after Livewire updates
            const tabs = document.querySelectorAll('.profile-tab');

            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    const target = this.dataset.target;

                    // Hide all tab content
                    document.querySelectorAll('.tab-content').forEach(content => {
                        content.classList.add('hidden');
                        content.classList.remove('active');
                    });

                    // Show selected tab content
                    document.getElementById('tab-' + target).classList.remove('hidden');
                    document.getElementById('tab-' + target).classList.add('active');

                    // Update active tab styling
                    tabs.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                });
            });
        });
    </script>
    @endpush
</x-app-layout>
