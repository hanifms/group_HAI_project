/**
 * Profile Page Tab Functionality
 *
 * This script handles the tab switching functionality on the profile page.
 * It ensures that tabs can be changed to view different sections of the profile settings.
 */

document.addEventListener('DOMContentLoaded', function() {
    initProfileTabs();
});

// Initialize after any Livewire updates
document.addEventListener('livewire:load', function() {
    initProfileTabs();
});

function initProfileTabs() {
    // Find all profile tabs
    const tabs = document.querySelectorAll('.profile-tab');

    if (tabs.length === 0) {
        console.log('No profile tabs found on this page.');
        return;
    }

    console.log('Profile tabs initialized:', tabs.length);

    // Add click event listeners to each tab
    tabs.forEach(tab => {
        tab.addEventListener('click', function(e) {
            e.preventDefault();
            const target = this.dataset.target;

            console.log('Tab clicked:', target);

            // Hide all tab content
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
                content.classList.remove('active');
            });

            // Show selected tab content
            const selectedTab = document.getElementById('tab-' + target);
            if (selectedTab) {
                selectedTab.classList.remove('hidden');
                selectedTab.classList.add('active');
                console.log('Tab content displayed:', 'tab-' + target);
            } else {
                console.error('Tab content not found:', 'tab-' + target);
            }

            // Update active tab styling
            tabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
        });
    });
}
