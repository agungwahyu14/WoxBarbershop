/**
 * Enhanced Navigation Manager
 * Handles modern navigation interactions with improved UX
 */

// Global navigation state
window.NavigationManager = {
    init: function () {
        this.setupDropdownInteractions();
        this.addKeyboardSupport();
        this.handleOutsideClicks();
        this.setupMobileMenuEnhancements();
        this.addTouchSupport();

        console.log('Enhanced Navigation Manager initialized');
    },

    // Setup modern dropdown interactions
    setupDropdownInteractions: function () {
        // Close dropdowns when switching between them
        const dropdownButtons = document.querySelectorAll('[aria-haspopup="true"]');
        dropdownButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                // Close other dropdowns when opening a new one
                this.closeOtherDropdowns(e.currentTarget);
            });
        });
    },

    // Close other dropdowns except the current one
    closeOtherDropdowns: function (currentButton) {
        if (window.Alpine) {
            document.querySelectorAll('[x-data]').forEach(element => {
                if (element !== currentButton.closest('[x-data]')) {
                    if (element.__x && element.__x.$data.open !== undefined) {
                        element.__x.$data.open = false;
                    }
                    if (element.__x && element.__x.$data.langOpen !== undefined) {
                        element.__x.$data.langOpen = false;
                    }
                }
            });
        }
    },

    // Enhanced mobile menu interactions
    setupMobileMenuEnhancements: function () {
        const mobileMenuItems = document.querySelectorAll('.mobile-nav-item');
        mobileMenuItems.forEach(item => {
            item.addEventListener('click', () => {
                // Close mobile menu when item is clicked
                if (window.Alpine) {
                    const navbar = document.getElementById('navbar');
                    if (navbar && navbar.__x) {
                        navbar.__x.$data.mobileMenuOpen = false;
                    }
                }
            });
        });

        // Handle window resize
        let resizeTimer;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(() => {
                if (window.innerWidth >= 1024) {
                    const navbar = document.getElementById('navbar');
                    if (navbar && navbar.__x) {
                        navbar.__x.$data.mobileMenuOpen = false;
                    }
                }
            }, 250);
        });
    },

    // Add touch support for better mobile experience
    addTouchSupport: function () {
        if ('ontouchstart' in window) {
            document.querySelectorAll('button, a').forEach(element => {
                element.addEventListener('touchstart', function () {
                    this.style.opacity = '0.8';
                });
                element.addEventListener('touchend', function () {
                    this.style.opacity = '';
                });
            });
        }
    },

    // Keyboard support for accessibility
    addKeyboardSupport: function () {
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                // Close all dropdowns on escape
                if (window.Alpine) {
                    document.querySelectorAll('[x-data]').forEach(element => {
                        if (element.__x && element.__x.$data.open !== undefined) {
                            element.__x.$data.open = false;
                        }
                        if (element.__x && element.__x.$data.langOpen !== undefined) {
                            element.__x.$data.langOpen = false;
                        }
                    });
                }
            }
        });
    },

    // Handle outside clicks more reliably
    handleOutsideClicks: function () {
        document.addEventListener('click', function (e) {
            // Check if click is outside any dropdown
            const isDropdownClick = e.target.closest('[x-data*="open"]') ||
                e.target.closest('[x-data*="langOpen"]') ||
                e.target.closest('.dropdown-content');

            if (!isDropdownClick) {
                // Close all dropdowns
                if (window.Alpine) {
                    document.querySelectorAll('[x-data]').forEach(element => {
                        if (element.__x && element.__x.$data.open !== undefined) {
                            element.__x.$data.open = false;
                        }
                        if (element.__x && element.__x.$data.langOpen !== undefined) {
                            element.__x.$data.langOpen = false;
                        }
                        if (element.__x && element.__x.$data.mobileMenuOpen !== undefined) {
                            element.__x.$data.mobileMenuOpen = false;
                        }
                    });
                }
            }
        });
    }
};

// Enhanced language switcher function
function switchLanguage(language) {
    console.log('Switching language to:', language);

    // Visual feedback with loading state
    const button = event.target.closest('button');
    if (button) {
        const originalContent = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Switching...';
        button.disabled = true;

        // Add visual feedback to button
        button.classList.add('opacity-75', 'cursor-not-allowed');
    }

    // Close all dropdowns with animation
    if (window.Alpine) {
        document.querySelectorAll('[x-data]').forEach(element => {
            if (element.__x && element.__x.$data.langOpen !== undefined) {
                element.__x.$data.langOpen = false;
            }
        });
    }

    // Navigate to language switch route with slight delay for UX
    setTimeout(() => {
        window.location.href = `/language/switch/${language}`;
    }, 150);
}

// Initialize enhanced navigation when DOM is loaded
document.addEventListener('DOMContentLoaded', function () {
    // Wait for Alpine.js to be ready
    document.addEventListener('alpine:init', () => {
        NavigationManager.init();
    });

    // Fallback if Alpine is already initialized
    if (window.Alpine && Alpine.version) {
        NavigationManager.init();
    }

    // Initialize immediately if no Alpine
    setTimeout(() => {
        if (!window.Alpine) {
            NavigationManager.init();
        }
    }, 100);
});