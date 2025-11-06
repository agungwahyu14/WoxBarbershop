// Enhanced Navigation JavaScript
document.addEventListener('DOMContentLoaded', function () {

    // Smooth navigation enhancements
    function enhanceNavigation() {
        // Add active link highlighting
        const navLinks = document.querySelectorAll('.nav-link');
        const currentPath = window.location.pathname;

        navLinks.forEach(link => {
            if (link.getAttribute('href') === currentPath) {
                link.classList.add('text-[#d4af37]');
            }
        });

        // Enhanced scroll behavior for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                if (targetId === '#') return;

                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    // Close mobile menu if open
                    const mobileMenu = document.querySelector('[x-data]').__x.$data.mobileMenuOpen;
                    if (mobileMenu) {
                        document.querySelector('[x-data]').__x.$data.mobileMenuOpen = false;
                    }

                    // Smooth scroll with offset for fixed header
                    const headerOffset = 80;
                    const elementPosition = targetElement.getBoundingClientRect().top;
                    const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

                    window.scrollTo({
                        top: offsetPosition,
                        behavior: 'smooth'
                    });
                }
            });
        });
    }

    // Mobile menu keyboard navigation
    function enhanceKeyboardNavigation() {
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenuItems = document.querySelectorAll('#mobile-menu a, #mobile-menu button');

        if (mobileMenuButton) {
            mobileMenuButton.addEventListener('keydown', function (e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    this.click();
                }
            });
        }

        // Trap focus within mobile menu when open
        let focusableElements = [];
        let firstFocusableElement;
        let lastFocusableElement;

        mobileMenuItems.forEach((item, index) => {
            item.addEventListener('keydown', function (e) {
                focusableElements = Array.from(document.querySelectorAll('#mobile-menu a:not([disabled]), #mobile-menu button:not([disabled])'));
                firstFocusableElement = focusableElements[0];
                lastFocusableElement = focusableElements[focusableElements.length - 1];

                if (e.key === 'Tab') {
                    if (e.shiftKey) {
                        if (document.activeElement === firstFocusableElement) {
                            lastFocusableElement.focus();
                            e.preventDefault();
                        }
                    } else {
                        if (document.activeElement === lastFocusableElement) {
                            firstFocusableElement.focus();
                            e.preventDefault();
                        }
                    }
                }

                if (e.key === 'Escape') {
                    // Close mobile menu
                    const alpineComponent = document.querySelector('[x-data]').__x;
                    if (alpineComponent && alpineComponent.$data.mobileMenuOpen) {
                        alpineComponent.$data.mobileMenuOpen = false;
                        mobileMenuButton.focus();
                    }
                }
            });
        });
    }

    // Enhanced header behavior on scroll
    function enhanceHeaderScroll() {
        const header = document.getElementById('navbar');
        let lastScrollY = window.scrollY;
        let ticking = false;

        function updateHeader() {
            const currentScrollY = window.scrollY;

            if (currentScrollY > 100) {
                header.classList.add('shadow-lg');
                header.classList.remove('shadow-sm');
            } else {
                header.classList.remove('shadow-lg');
                header.classList.add('shadow-sm');
            }

            // Auto-hide header on scroll down (optional)
            if (currentScrollY > lastScrollY && currentScrollY > 200) {
                header.style.transform = 'translateY(-100%)';
            } else {
                header.style.transform = 'translateY(0)';
            }

            lastScrollY = currentScrollY;
            ticking = false;
        }

        function requestTick() {
            if (!ticking) {
                requestAnimationFrame(updateHeader);
                ticking = true;
            }
        }

        window.addEventListener('scroll', requestTick);
    }

    // Initialize all enhancements
    enhanceNavigation();
    enhanceKeyboardNavigation();
    enhanceHeaderScroll();

    // Add loading states for navigation
    document.querySelectorAll('nav a').forEach(link => {
        link.addEventListener('click', function (e) {
            if (!this.getAttribute('href').startsWith('#') &&
                !this.getAttribute('href').startsWith('javascript:') &&
                !e.ctrlKey && !e.metaKey) {

                // Add loading indicator
                const originalText = this.innerHTML;
                this.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>' + this.textContent;

                // Restore original content if navigation fails
                setTimeout(() => {
                    if (this.innerHTML.includes('fa-spinner')) {
                        this.innerHTML = originalText;
                    }
                }, 3000);
            }
        });
    });

    // Enhance dropdown interactions
    function enhanceDropdowns() {
        const dropdowns = document.querySelectorAll('[x-data*="open"]');

        dropdowns.forEach(dropdown => {
            const button = dropdown.querySelector('button');
            const menu = dropdown.querySelector('[x-show="open"]');

            if (button && menu) {
                // Add ARIA attributes
                button.setAttribute('aria-haspopup', 'true');
                button.setAttribute('aria-expanded', 'false');

                // Update ARIA when dropdown opens/closes
                const observer = new MutationObserver((mutations) => {
                    mutations.forEach((mutation) => {
                        if (mutation.type === 'attributes' && mutation.attributeName === 'style') {
                            const isOpen = !menu.hasAttribute('x-cloak') &&
                                getComputedStyle(menu).display !== 'none';
                            button.setAttribute('aria-expanded', isOpen);
                        }
                    });
                });

                observer.observe(menu, { attributes: true });
            }
        });
    }

    enhanceDropdowns();
});

// Global utility functions
window.NavigationUtils = {
    closeAllDropdowns: function () {
        document.querySelectorAll('[x-data]').forEach(element => {
            if (element.__x && element.__x.$data.open !== undefined) {
                element.__x.$data.open = false;
            }
        });
    },

    closeMobileMenu: function () {
        const element = document.querySelector('[x-data*="mobileMenuOpen"]');
        if (element && element.__x) {
            element.__x.$data.mobileMenuOpen = false;
        }
    },

    showNotification: function (message, type = 'success') {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: type,
                title: type === 'success' ? 'Berhasil!' : 'Perhatian!',
                text: message,
                timer: 3000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end',
                showClass: {
                    popup: 'animate__animated animate__fadeInDown animate__faster'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp animate__faster'
                }
            });
        }
    }
};