<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Wox's Barbershop</title>

    <!-- Favicons -->
    <link rel="icon" type="image/png" href="{{ asset('images/Logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/Logo.png') }}">
    <link rel="shortcut icon" href="{{ asset('images/Logo.png') }}">
    @vite('resources/css/app.css')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    {{--
    <script src="https://cdn.tailwindcss.com"></script> --}}
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&family=Roboto:wght@300;400;500&display=swap');

        /* ============================================
           GLOBAL ANIMATION UTILITIES
           ============================================ */

        /* Page Transition Wrapper */
        .page-transition {
            animation: pageEnter 0.5s ease-out forwards;
        }

        @keyframes pageEnter {
            0% {
                opacity: 0;
                transform: translateY(10px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Staggered Animation Delays */
        .stagger-1 {
            animation-delay: 0.1s;
        }

        .stagger-2 {
            animation-delay: 0.2s;
        }

        .stagger-3 {
            animation-delay: 0.3s;
        }

        .stagger-4 {
            animation-delay: 0.4s;
        }

        .stagger-5 {
            animation-delay: 0.5s;
        }

        .stagger-6 {
            animation-delay: 0.6s;
        }

        /* Initial state for animated elements */
        .animate-on-scroll {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .animate-on-scroll.is-visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* ============================================
           BUTTON ANIMATIONS
           ============================================ */

        /* Primary Button Style */
        .btn-primary {
            position: relative;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.6s ease, height 0.6s ease;
        }

        .btn-primary:hover::before {
            width: 300px;
            height: 300px;
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(212, 175, 55, 0.4);
        }

        .btn-primary:active {
            transform: translateY(-1px);
            box-shadow: 0 5px 15px rgba(212, 175, 55, 0.3);
        }

        /* Secondary/Outline Button */
        .btn-outline {
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .btn-outline::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 0;
            background: #d4af37;
            transition: height 0.3s ease;
            z-index: -1;
        }

        .btn-outline:hover::after {
            height: 100%;
        }

        .btn-outline:hover {
            color: #1a1a1a;
            border-color: #d4af37;
        }

        /* ============================================
           CARD ANIMATIONS
           ============================================ */

        /* Card Lift Effect */
        .card-lift {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .card-lift:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        /* Card Glow Effect */
        .card-glow {
            transition: all 0.4s ease;
        }

        .card-glow:hover {
            box-shadow: 0 0 30px rgba(212, 175, 55, 0.3);
        }

        /* Feature Card Enhanced */
        .feature-card {
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
        }

        .feature-card:hover .feature-number {
            opacity: 0.8;
            transform: scale(1.1);
        }

        .feature-number {
            transition: all 0.4s ease;
        }

        /* Product/Service Card */
        .product-card {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .product-card:hover {
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12);
        }

        .product-card img {
            transition: transform 0.5s ease;
        }

        .product-card:hover img {
            transform: scale(1.08);
        }

        /* ============================================
           NAVIGATION ANIMATIONS
           ============================================ */

        .nav-link {
            position: relative;
            transition: color 0.3s ease;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -2px;
            left: 0;
            background-color: #d4af37;
            transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .nav-link:hover::after {
            width: 100%;
        }

        .nav-link:hover {
            color: #d4af37;
        }

        /* ============================================
           IMAGE & GALLERY ANIMATIONS
           ============================================ */

        .menu-item:hover .menu-img {
            transform: scale(1.05);
        }

        .gallery-item {
            overflow: hidden;
            transition: all 0.4s ease;
        }

        .gallery-item img {
            transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .gallery-item:hover img {
            transform: scale(1.1);
        }

        .gallery-item:hover {
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        }

        /* ============================================
           FORM INPUT ANIMATIONS
           ============================================ */

        .input-animated {
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .input-animated:focus {
            border-color: #d4af37;
            box-shadow: 0 0 0 4px rgba(212, 175, 55, 0.1);
            transform: translateY(-2px);
        }

        /* ============================================
           ALERT & MODAL ANIMATIONS
           ============================================ */

        .alert-enter {
            animation: alertSlideIn 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55) forwards;
        }

        @keyframes alertSlideIn {
            0% {
                opacity: 0;
                transform: translateY(-20px) scale(0.95);
            }

            100% {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .modal-enter {
            animation: modalFadeIn 0.4s ease-out forwards;
        }

        @keyframes modalFadeIn {
            0% {
                opacity: 0;
                transform: scale(0.9);
            }

            100% {
                opacity: 1;
                transform: scale(1);
            }
        }

        .modal-backdrop {
            animation: backdropFadeIn 0.3s ease-out forwards;
        }

        @keyframes backdropFadeIn {
            0% {
                opacity: 0;
            }

            100% {
                opacity: 1;
            }
        }

        /* ============================================
           SUCCESS/NOTIFICATION ANIMATIONS
           ============================================ */

        .success-checkmark {
            animation: checkmarkBounce 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55) forwards;
        }

        @keyframes checkmarkBounce {
            0% {
                transform: scale(0);
            }

            50% {
                transform: scale(1.2);
            }

            100% {
                transform: scale(1);
            }
        }

        /* ============================================
           PARALLAX & BACKGROUND
           ============================================ */

        .parallax {
            background-attachment: fixed;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }

        /* ============================================
           DROPDOWN ANIMATIONS
           ============================================ */

        [x-cloak] {
            display: none !important;
        }

        .dropdown-enter-active {
            transition: all 0.2s ease-out;
        }

        .dropdown-leave-active {
            transition: all 0.15s ease-in;
        }

        .dropdown-enter-from,
        .dropdown-leave-to {
            opacity: 0;
            transform: translateY(-10px);
        }

        /* ============================================
           CONFETTI & SPECIAL EFFECTS
           ============================================ */

        @keyframes confetti-fall {
            0% {
                transform: translateY(-20px) rotate(0deg);
                opacity: 1;
            }

            100% {
                transform: translateY(30px) rotate(360deg);
                opacity: 0;
            }
        }

        .confetti {
            animation: confetti-fall 1.5s ease-in-out forwards;
        }

        /* ============================================
           REWARD BUTTON (LOYALTY)
           ============================================ */

        .reward-button {
            background: linear-gradient(135deg, #FFD700, #FFA500);
            box-shadow: 0 4px 15px rgba(255, 215, 0, 0.4);
            transition: all 0.3s ease;
        }

        .reward-button:hover {
            box-shadow: 0 6px 20px rgba(255, 215, 0, 0.6);
            transform: translateY(-2px);
        }

        /* ============================================
           GLOW EFFECTS
           ============================================ */

        @keyframes glow {
            0% {
                box-shadow: 0 0 5px rgba(255, 215, 0, 0.5);
            }

            50% {
                box-shadow: 0 0 20px rgba(255, 215, 0, 0.8), 0 0 30px rgba(255, 215, 0, 0.6);
            }

            100% {
                box-shadow: 0 0 5px rgba(255, 215, 0, 0.5);
            }
        }

        .glow-effect {
            animation: glow 2s infinite;
        }

        /* ============================================
           LOADING SKELETON
           ============================================ */

        .skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
        }

        @keyframes shimmer {
            0% {
                background-position: 200% 0;
            }

            100% {
                background-position: -200% 0;
            }
        }

        /* ============================================
           TESTIMONIAL SLIDE ANIMATION
           ============================================ */

        .testimonial-slide {
            animation: fadeInUp 0.5s ease-out forwards;
        }

        @keyframes fadeInUp {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ============================================
           SCROLL REVEAL (JavaScript Enhanced)
           ============================================ */

        .reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .reveal.active {
            opacity: 1;
            transform: translateY(0);
        }

        /* ============================================
           HOVER SCALE UTILITIES
           ============================================ */

        .hover-scale {
            transition: transform 0.3s ease;
        }

        .hover-scale:hover {
            transform: scale(1.05);
        }

        .hover-scale-sm:hover {
            transform: scale(1.02);
        }

        .hover-scale-lg:hover {
            transform: scale(1.1);
        }

        /* ============================================
           SERVICE ITEM ANIMATIONS
           ============================================ */

        .service-item {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 12px;
        }

        .service-item:hover {
            background: linear-gradient(135deg, rgba(212, 175, 55, 0.05), rgba(212, 175, 55, 0.1));
            transform: translateX(10px);
        }

        .service-item img {
            transition: transform 0.4s ease;
        }

        .service-item:hover img {
            transform: scale(1.1);
        }

        /* ============================================
           ENHANCED FEATURE NUMBER
           ============================================ */

        .card-lift .feature-number,
        .feature-card .feature-number {
            transition: all 0.4s ease;
        }

        .card-lift:hover .feature-number,
        .feature-card:hover .feature-number {
            opacity: 0.6;
            transform: scale(1.1) translateY(-5px);
            color: #d4af37;
        }

        /* ============================================
           SMOOTH PAGE SCROLLING
           ============================================ */

        html {
            scroll-behavior: smooth;
        }

        /* Focus states for accessibility */
        .btn-primary:focus,
        .btn-outline:focus,
        .input-animated:focus {
            outline: 2px solid #d4af37;
            outline-offset: 2px;
        }
    </style>
</head>

<body class="font-roboto text-gray-800 bg-light">

    @include('layouts.navigation')


    <!-- Page Content -->
    <main class="page-transition">
        @yield('content')
    </main>

    @include('layouts.footer')

    <!-- Back to Top Button -->
    <button id="back-to-top"
        class="fixed bottom-8 right-8 bg-secondary text-primary p-3 rounded-full shadow-lg opacity-0 invisible transition-all duration-300">
        <i class="fas fa-arrow-up"></i>
    </button>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script>
        // Mobile Menu Toggle
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');

        mobileMenuButton.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
            mobileMenu.classList.toggle('active');

            const icon = mobileMenuButton.querySelector('i');
            if (icon.classList.contains('fa-bars')) {
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-times');
            } else {
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
        });

        // Close mobile menu when clicking on a link
        const mobileMenuLinks = mobileMenu.querySelectorAll('a');
        mobileMenuLinks.forEach(link => {
            link.addEventListener('click', () => {
                mobileMenu.classList.add('hidden');
                mobileMenu.classList.remove('active');
                mobileMenuButton.querySelector('i').classList.remove('fa-times');
                mobileMenuButton.querySelector('i').classList.add('fa-bars');
            });
        });

        // Back to Top Button
        const backToTopButton = document.getElementById('back-to-top');
        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 300) {
                backToTopButton.classList.remove('opacity-0', 'invisible');
                backToTopButton.classList.add('opacity-100', 'visible');
            } else {
                backToTopButton.classList.add('opacity-0', 'invisible');
                backToTopButton.classList.remove('opacity-100', 'visible');
            }
        });

        backToTopButton.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });

        // Smooth scrolling for all anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                if (targetId === '#') return;
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 80,
                        behavior: 'smooth'
                    });
                }
            });
        });
    </script>

    <!-- Scroll Reveal Animation Script -->
    <script>
        // Scroll Reveal for elements with 'reveal' class
        document.addEventListener('DOMContentLoaded', function () {
            const revealElements = document.querySelectorAll('.reveal, .animate-on-scroll');

            const revealOnScroll = () => {
                revealElements.forEach((el, index) => {
                    const elementTop = el.getBoundingClientRect().top;
                    const elementVisible = 150;

                    if (elementTop < window.innerHeight - elementVisible) {
                        // Add stagger delay based on index within viewport
                        setTimeout(() => {
                            el.classList.add('active', 'is-visible');
                        }, (index % 4) * 100);
                    }
                });
            };

            // Initial check
            revealOnScroll();

            // Throttled scroll listener
            let ticking = false;
            window.addEventListener('scroll', () => {
                if (!ticking) {
                    window.requestAnimationFrame(() => {
                        revealOnScroll();
                        ticking = false;
                    });
                    ticking = true;
                }
            });
        });

        // Add ripple effect to buttons
        document.querySelectorAll('.btn-primary, .btn-ripple').forEach(button => {
            button.addEventListener('click', function (e) {
                const ripple = document.createElement('span');
                const rect = this.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                const x = e.clientX - rect.left - size / 2;
                const y = e.clientY - rect.top - size / 2;

                ripple.style.cssText = `
                    position: absolute;
                    width: ${size}px;
                    height: ${size}px;
                    left: ${x}px;
                    top: ${y}px;
                    background: rgba(255, 255, 255, 0.3);
                    border-radius: 50%;
                    transform: scale(0);
                    animation: rippleEffect 0.6s ease-out;
                    pointer-events: none;
                `;

                this.style.position = 'relative';
                this.style.overflow = 'hidden';
                this.appendChild(ripple);

                setTimeout(() => ripple.remove(), 600);
            });
        });

        // CSS for ripple animation
        const rippleStyle = document.createElement('style');
        rippleStyle.textContent = `
            @keyframes rippleEffect {
                to {
                    transform: scale(4);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(rippleStyle);
    </script>

    <!-- SweetAlert Script -->
    {{--
    <script>
        @if (session('warning') && !session('error_type'))
            Swal.fire({
                icon: 'warning',
                title: 'Warning!',
                text: '{{ session('warning') }}',
                timer: 4000,
                showConfirmButton: true,
                toast: true,
                position: 'top-end'
            });
        @endif

        // SweetAlert for Info Messages
        @if (session('info'))
            Swal.fire({
                icon: 'info',
                title: 'Info',
                text: '{{ session('info') }}',
                timer: 3000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        @endif

        // // SweetAlert for Status Messages (profile updated, etc)
        // @if (session('status'))
        //     Swal.fire({
        //         icon: 'success',
        //         title: 'Updated!',
        //         text: 'Profile updated successfully!',
        //         timer: 3000,
        //         showConfirmButton: false,
        //         toast: true,
        //         position: 'top-end'
        //     });
        // @endif
    </script> --}}

    @stack('scripts')

    <!-- Phone Number Validation (Global) -->
    <script src="{{ asset('js/phone-validation.js') }}"></script>
</body>

</html>