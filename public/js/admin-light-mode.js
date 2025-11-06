/**
 * Admin Light Mode Enforcement Script
 * This script ensures admin panel always stays in light mode
 */

(function () {
    'use strict';

    // Immediate execution to prevent dark mode flashing
    function forceLightMode() {
        // Remove dark classes from document and body
        document.documentElement.classList.remove('dark', 'dark-mode');
        document.body.classList.remove('dark', 'dark-mode');

        // Force color scheme
        document.documentElement.style.colorScheme = 'light';
        document.body.style.colorScheme = 'light';

        // Set meta theme color to light
        let metaTheme = document.querySelector('meta[name="theme-color"]');
        if (!metaTheme) {
            metaTheme = document.createElement('meta');
            metaTheme.name = 'theme-color';
            document.head.appendChild(metaTheme);
        }
        metaTheme.content = '#f9fafb';
    }

    // Execute immediately
    forceLightMode();

    // Override localStorage and sessionStorage dark mode settings for admin
    function overrideStorageSettings() {
        if (window.location.pathname.includes('/admin')) {
            // Clear dark mode preferences
            localStorage.removeItem('darkMode');
            localStorage.removeItem('theme');
            localStorage.removeItem('color-theme');
            sessionStorage.removeItem('darkMode');
            sessionStorage.removeItem('theme');
            sessionStorage.removeItem('color-theme');

            // Set admin light mode flag
            localStorage.setItem('adminLightMode', 'true');
        }
    }

    // Disable dark mode functions globally for admin
    function disableDarkModeFunctions() {
        // Common dark mode function names
        const darkModeFunctions = [
            'enableDarkMode',
            'disableDarkMode',
            'toggleDarkMode',
            'setDarkMode',
            'switchTheme',
            'toggleTheme',
            'setTheme',
            'darkModeToggle'
        ];

        darkModeFunctions.forEach(funcName => {
            window[funcName] = function () {
                console.log(`Admin Panel: ${funcName} disabled - light mode enforced`);
                return false;
            };
        });
    }

    // Watch for DOM changes and prevent dark mode
    function setupDarkModeWatcher() {
        const observer = new MutationObserver(function (mutations) {
            mutations.forEach(function (mutation) {
                if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                    const target = mutation.target;
                    if (target.classList.contains('dark') || target.classList.contains('dark-mode')) {
                        target.classList.remove('dark', 'dark-mode');
                        console.log('Admin Panel: Removed dark mode class from', target);
                    }
                }
            });
        });

        // Observe document and body
        observer.observe(document.documentElement, {
            attributes: true,
            subtree: true,
            attributeFilter: ['class']
        });

        if (document.body) {
            observer.observe(document.body, {
                attributes: true,
                subtree: true,
                attributeFilter: ['class']
            });
        }

        return observer;
    }

    // Disable dark mode toggles
    function disableDarkModeToggles() {
        const selectors = [
            '[data-toggle="dark-mode"]',
            '.dark-mode-toggle',
            '.theme-toggle',
            '.mode-toggle',
            '.color-scheme-toggle',
            'button[id*="dark"]',
            'button[class*="dark"]'
        ];

        selectors.forEach(selector => {
            document.querySelectorAll(selector).forEach(toggle => {
                toggle.style.display = 'none';
                toggle.disabled = true;
                toggle.removeEventListener('click', toggle.onclick);
                toggle.onclick = function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    return false;
                };
            });
        });
    }

    // Override media query detection
    function overrideMediaQuery() {
        // Override matchMedia for dark mode detection
        const originalMatchMedia = window.matchMedia;
        window.matchMedia = function (query) {
            const result = originalMatchMedia.call(this, query);
            if (query.includes('prefers-color-scheme: dark')) {
                // Force it to always return false for dark mode
                return {
                    matches: false,
                    media: query,
                    onchange: null,
                    addEventListener: function () { },
                    removeEventListener: function () { },
                    dispatchEvent: function () { return false; }
                };
            }
            return result;
        };
    }

    // Periodic check for dark mode classes
    function periodicLightModeEnforcement() {
        setInterval(function () {
            forceLightMode();
            disableDarkModeToggles();
        }, 500); // Check every 500ms
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () {
            overrideStorageSettings();
            disableDarkModeFunctions();
            setupDarkModeWatcher();
            disableDarkModeToggles();
            overrideMediaQuery();
            periodicLightModeEnforcement();
        });
    } else {
        // DOM already loaded
        overrideStorageSettings();
        disableDarkModeFunctions();
        setupDarkModeWatcher();
        disableDarkModeToggles();
        overrideMediaQuery();
        periodicLightModeEnforcement();
    }

    // Also run on page show (back/forward navigation)
    window.addEventListener('pageshow', function () {
        forceLightMode();
        overrideStorageSettings();
        disableDarkModeToggles();
    });

})();