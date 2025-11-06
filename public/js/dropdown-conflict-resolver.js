// Dropdown Conflict Resolution
document.addEventListener('DOMContentLoaded', function () {
    console.log('Dropdown conflict resolver initialized');

    // Get all dropdown containers
    const languageDropdown = document.querySelector('.language-switcher-container [x-data]');
    const profileDropdown = document.querySelector('[x-data*="open"]');

    // Function to close all dropdowns except the specified one
    function closeOtherDropdowns(exceptElement) {
        document.querySelectorAll('[x-data]').forEach(element => {
            if (element !== exceptElement && element.__x && element.__x.$data.open !== undefined) {
                element.__x.$data.open = false;
            }
        });
    }

    // Enhanced click handling for language switcher
    if (languageDropdown) {
        const languageButton = languageDropdown.querySelector('button');
        if (languageButton) {
            languageButton.addEventListener('click', function (e) {
                e.stopPropagation();
                console.log('Language dropdown clicked');

                // Close other dropdowns first
                setTimeout(() => {
                    closeOtherDropdowns(languageDropdown);
                }, 0);
            });
        }
    }

    // Enhanced click handling for profile dropdown
    if (profileDropdown && profileDropdown !== languageDropdown) {
        const profileButton = profileDropdown.querySelector('button');
        if (profileButton) {
            profileButton.addEventListener('click', function (e) {
                e.stopPropagation();
                console.log('Profile dropdown clicked');

                // Close other dropdowns first
                setTimeout(() => {
                    closeOtherDropdowns(profileDropdown);
                }, 0);
            });
        }
    }

    // Global click handler to close all dropdowns
    document.addEventListener('click', function (e) {
        // Check if click is outside all dropdowns
        const isInsideDropdown = e.target.closest('[x-data]');

        if (!isInsideDropdown) {
            console.log('Clicked outside dropdowns, closing all');
            closeOtherDropdowns(null);
        }
    });

    // Escape key handler
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            console.log('Escape pressed, closing all dropdowns');
            closeOtherDropdowns(null);
        }
    });

    // Handle language switching with proper cleanup
    window.switchLanguageEnhanced = function (language) {
        console.log('Enhanced language switch to:', language);

        // Close all dropdowns immediately
        closeOtherDropdowns(null);

        // Call original switch function
        if (typeof switchLanguage === 'function') {
            switchLanguage(language);
        }
    };

    // Override the existing switchLanguage function
    const originalSwitchLanguage = window.switchLanguage;
    window.switchLanguage = function (language) {
        // Close all dropdowns first
        closeOtherDropdowns(null);

        // Call enhanced version or original
        if (originalSwitchLanguage) {
            originalSwitchLanguage(language);
        } else {
            // Fallback implementation
            console.log('Fallback language switch to:', language);
            window.location.href = `/language/switch/${language}`;
        }
    };
});

// Alpine.js integration
document.addEventListener('alpine:init', () => {
    console.log('Alpine initialized - setting up dropdown coordination');

    // Add global state management if needed
    Alpine.store('dropdowns', {
        activeDropdown: null,

        open(name) {
            console.log('Opening dropdown:', name);
            if (this.activeDropdown && this.activeDropdown !== name) {
                this.close(this.activeDropdown);
            }
            this.activeDropdown = name;
        },

        close(name) {
            console.log('Closing dropdown:', name);
            if (this.activeDropdown === name) {
                this.activeDropdown = null;
            }
        }
    });
});

// Debug function for testing
window.debugDropdowns = function () {
    console.log('=== Dropdown Debug Info ===');

    document.querySelectorAll('[x-data]').forEach((element, index) => {
        console.log(`Dropdown ${index}:`, {
            element: element,
            data: element.__x?.$data,
            classes: element.className
        });
    });
};