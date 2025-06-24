import './bootstrap';
import './profile-tabs';

/**
 * MyTravel - Main JavaScript File
 *
 * This file contains the main JavaScript functionality for the MyTravel application.
 * It initializes all interactive elements like dropdowns, notifications, modals, etc.
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize mobile menu toggles
    initMobileMenu();

    // Initialize notification dismissal
    initNotifications();

    // Initialize tooltips
    initTooltips();

    // Initialize dropdown menus
    initDropdowns();

    // Initialize form validations
    initFormValidations();

    // Initialize image previews
    initImagePreviews();
});

/**
 * Initialize mobile menu functionality
 */
function initMobileMenu() {
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');

    if (mobileMenuButton && mobileMenu) {
        mobileMenuButton.addEventListener('click', function() {
            const isExpanded = mobileMenuButton.getAttribute('aria-expanded') === 'true';
            mobileMenuButton.setAttribute('aria-expanded', !isExpanded);
            mobileMenu.classList.toggle('hidden');
        });
    }
}

/**
 * Initialize notification dismissal functionality
 */
function initNotifications() {
    const notifications = document.querySelectorAll('.notification');

    notifications.forEach(notification => {
        // Add close button if not already present
        if (!notification.querySelector('.notification-close')) {
            const closeButton = document.createElement('button');
            closeButton.className = 'notification-close';
            closeButton.innerHTML = '&times;';
            closeButton.setAttribute('aria-label', 'Close');
            notification.appendChild(closeButton);

            closeButton.addEventListener('click', function() {
                notification.classList.add('opacity-0');
                setTimeout(() => {
                    notification.remove();
                }, 300);
            });

            // Auto-dismiss after 5 seconds
            setTimeout(() => {
                if (document.body.contains(notification)) {
                    notification.classList.add('opacity-0');
                    setTimeout(() => {
                        if (document.body.contains(notification)) {
                            notification.remove();
                        }
                    }, 300);
                }
            }, 5000);
        }
    });
}

/**
 * Initialize tooltips
 */
function initTooltips() {
    const tooltipTriggers = document.querySelectorAll('[data-tooltip]');

    tooltipTriggers.forEach(trigger => {
        const tooltipText = trigger.getAttribute('data-tooltip');
        const tooltipPosition = trigger.getAttribute('data-tooltip-position') || 'top';

        trigger.addEventListener('mouseenter', function(e) {
            const tooltip = document.createElement('div');
            tooltip.className = `tooltip tooltip-${tooltipPosition}`;
            tooltip.textContent = tooltipText;
            document.body.appendChild(tooltip);

            const triggerRect = trigger.getBoundingClientRect();
            const tooltipRect = tooltip.getBoundingClientRect();

            let top, left;

            switch(tooltipPosition) {
                case 'top':
                    top = triggerRect.top - tooltipRect.height - 10;
                    left = triggerRect.left + (triggerRect.width / 2) - (tooltipRect.width / 2);
                    break;
                case 'bottom':
                    top = triggerRect.bottom + 10;
                    left = triggerRect.left + (triggerRect.width / 2) - (tooltipRect.width / 2);
                    break;
                case 'left':
                    top = triggerRect.top + (triggerRect.height / 2) - (tooltipRect.height / 2);
                    left = triggerRect.left - tooltipRect.width - 10;
                    break;
                case 'right':
                    top = triggerRect.top + (triggerRect.height / 2) - (tooltipRect.height / 2);
                    left = triggerRect.right + 10;
                    break;
            }

            tooltip.style.top = `${top + window.scrollY}px`;
            tooltip.style.left = `${left + window.scrollX}px`;
            tooltip.classList.add('visible');

            trigger._tooltip = tooltip;
        });

        trigger.addEventListener('mouseleave', function() {
            if (trigger._tooltip) {
                trigger._tooltip.remove();
                trigger._tooltip = null;
            }
        });
    });
}

/**
 * Initialize dropdown menus
 */
function initDropdowns() {
    const dropdowns = document.querySelectorAll('.dropdown');

    dropdowns.forEach(dropdown => {
        const trigger = dropdown.querySelector('.dropdown-trigger');
        const menu = dropdown.querySelector('.dropdown-menu');

        if (trigger && menu) {
            trigger.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                const isExpanded = trigger.getAttribute('aria-expanded') === 'true';
                trigger.setAttribute('aria-expanded', !isExpanded);
                menu.classList.toggle('hidden');

                // Close other dropdowns
                dropdowns.forEach(otherDropdown => {
                    if (otherDropdown !== dropdown) {
                        const otherTrigger = otherDropdown.querySelector('.dropdown-trigger');
                        const otherMenu = otherDropdown.querySelector('.dropdown-menu');

                        if (otherTrigger && otherMenu) {
                            otherTrigger.setAttribute('aria-expanded', 'false');
                            otherMenu.classList.add('hidden');
                        }
                    }
                });
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!dropdown.contains(e.target)) {
                    trigger.setAttribute('aria-expanded', 'false');
                    menu.classList.add('hidden');
                }
            });
        }
    });
}

/**
 * Initialize form validation
 */
function initFormValidations() {
    const forms = document.querySelectorAll('form[data-validate]');

    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('border-red-500');

                    // Add error message if not already present
                    let errorMessage = field.parentNode.querySelector('.error-message');
                    if (!errorMessage) {
                        errorMessage = document.createElement('p');
                        errorMessage.className = 'text-red-500 text-sm mt-1 error-message';
                        errorMessage.textContent = 'This field is required';
                        field.parentNode.appendChild(errorMessage);
                    }
                } else {
                    field.classList.remove('border-red-500');
                    const errorMessage = field.parentNode.querySelector('.error-message');
                    if (errorMessage) {
                        errorMessage.remove();
                    }
                }
            });

            if (!isValid) {
                e.preventDefault();
            }
        });
    });
}

/**
 * Initialize image preview functionality for file inputs
 */
function initImagePreviews() {
    const imageInputs = document.querySelectorAll('input[type="file"][data-preview]');

    imageInputs.forEach(input => {
        const previewContainerId = input.getAttribute('data-preview');
        const previewContainer = document.getElementById(previewContainerId);

        if (previewContainer) {
            input.addEventListener('change', function() {
                previewContainer.innerHTML = '';

                if (this.files && this.files[0]) {
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'max-h-48 rounded';
                        previewContainer.appendChild(img);
                    };

                    reader.readAsDataURL(this.files[0]);
                }
            });
        }
    });
}
