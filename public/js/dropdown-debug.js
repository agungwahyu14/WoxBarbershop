// Profile Dropdown Debug & Enhancement
document.addEventListener('DOMContentLoaded', function () {
    console.log('Profile dropdown script loaded');

    // Debug Alpine.js data
    setTimeout(() => {
        const profileDropdown = document.querySelector('[x-data*="open"]');
        if (profileDropdown) {
            console.log('Profile dropdown found:', profileDropdown);

            // Add click listener to button for debugging
            const button = profileDropdown.querySelector('button');
            if (button) {
                button.addEventListener('click', function () {
                    console.log('Profile button clicked');
                    setTimeout(() => {
                        const dropdown = profileDropdown.querySelector('[x-show="open"]');
                        if (dropdown) {
                            console.log('Dropdown element:', dropdown);
                            console.log('Dropdown style:', getComputedStyle(dropdown).display);
                            console.log('Alpine data:', profileDropdown.__x?.$data);
                        }
                    }, 100);
                });
            }
        } else {
            console.log('Profile dropdown not found');
        }
    }, 1000);

    // Force show dropdown for testing (remove in production)
    window.testDropdown = function () {
        const profileDropdown = document.querySelector('[x-data*="open"]');
        if (profileDropdown && profileDropdown.__x) {
            profileDropdown.__x.$data.open = true;
            console.log('Dropdown forced open');
        }
    };

    // Enhanced dropdown behavior
    function enhanceProfileDropdown() {
        const dropdownContainer = document.querySelector('[x-data*="open"]');
        if (!dropdownContainer) return;

        const button = dropdownContainer.querySelector('button');
        const dropdown = dropdownContainer.querySelector('[x-show="open"]');

        if (!button || !dropdown) return;

        // Add classes for better styling
        dropdownContainer.classList.add('dropdown-container');
        dropdown.classList.add('dropdown-menu');

        // Ensure dropdown is properly positioned
        function positionDropdown() {
            const rect = button.getBoundingClientRect();
            const viewportWidth = window.innerWidth;

            // Reset positioning
            dropdown.style.right = '0';
            dropdown.style.left = 'auto';

            // Check if dropdown goes off screen
            const dropdownRect = dropdown.getBoundingClientRect();
            if (dropdownRect.right > viewportWidth - 20) {
                dropdown.style.right = '0';
                dropdown.style.left = 'auto';
            }
        }

        // Position dropdown when it opens
        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                if (mutation.type === 'attributes' && mutation.attributeName === 'style') {
                    const isVisible = !dropdown.hasAttribute('style') ||
                        !dropdown.style.display.includes('none');
                    if (isVisible && dropdownContainer.__x?.$data.open) {
                        positionDropdown();
                    }
                }
            });
        });

        observer.observe(dropdown, { attributes: true });

        // Close dropdown when clicking on links
        const links = dropdown.querySelectorAll('a, button[type="submit"]');
        links.forEach(link => {
            link.addEventListener('click', (e) => {
                // Don't close immediately for form submissions
                if (e.target.type !== 'submit') {
                    setTimeout(() => {
                        if (dropdownContainer.__x) {
                            dropdownContainer.__x.$data.open = false;
                        }
                    }, 100);
                }
            });
        });
    }

    enhanceProfileDropdown();
});

// Add global styles to ensure dropdown visibility
const style = document.createElement('style');
style.textContent = `
    /* Dropdown visibility fixes */
    .dropdown-menu[x-show="open"] {
        display: block !important;
    }
    
    /* Prevent dropdown from being cut off */
    header {
        overflow: visible !important;
    }
    
    nav {
        overflow: visible !important;
    }
    
    .dropdown-container {
        overflow: visible !important;
    }
    
    /* Ensure proper layering */
    .dropdown-menu {
        position: absolute !important;
        z-index: 99999 !important;
    }
`;
document.head.appendChild(style);

// Alpine.js plugin to debug x-show
document.addEventListener('alpine:init', () => {
    console.log('Alpine.js initialized');
});