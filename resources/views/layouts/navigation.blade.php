<header class="fixed w-full bg-white bg-opacity-95 z-50 shadow-sm transition-all duration-300" id="navbar">
    <div class="container mx-auto px-4 py-4">
        <nav class="flex items-center justify-between">
            <!-- Left Section: Logo -->
            <div class="flex items-center space-x-3 flex-shrink-0">
                <img src="{{ asset('images/Logo.png') }}" alt="WOX'S Barbershop Logo" class="h-12 w-auto object-contain">

            </div>

            <!-- Center Section: Desktop Navigation Menu -->
            <div class="hidden lg:flex items-center space-x-8 flex-1 justify-center">
                <!-- Beranda -->
                @auth
                    <a href="{{ route('dashboard') }}"
                        class="nav-link relative text-gray-800 hover:text-secondary transition-colors duration-300 font-medium">
                        {{ __('general.home') }}
                    </a>
                @else
                    <a href="#beranda"
                        class="nav-link relative text-gray-800 hover:text-secondary transition-colors duration-300 font-medium">
                        {{ __('general.home') }}
                    </a>
                @endauth

                <!-- Layanan -->
                @auth
                    <a href="{{ route('dashboard') }}#layanan"
                        class="nav-link relative text-gray-800 hover:text-secondary transition-colors duration-300 font-medium">
                        {{ __('general.services') }}
                    </a>
                @else
                    <a href="#layanan"
                        class="nav-link relative text-gray-800 hover:text-secondary transition-colors duration-300 font-medium">
                        {{ __('general.services') }}
                    </a>
                @endauth

                <!-- Tentang -->
                @auth
                    <a href="{{ route('dashboard') }}#tentang"
                        class="nav-link relative text-gray-800 hover:text-secondary transition-colors duration-300 font-medium">
                        {{ __('welcome.about') }}
                    </a>
                @else
                    <a href="#tentang"
                        class="nav-link relative text-gray-800 hover:text-secondary transition-colors duration-300 font-medium">
                        {{ __('welcome.about') }}
                    </a>
                @endauth

                <!-- Produk -->
                @auth
                    <a href="{{ route('dashboard') }}#produk"
                        class="nav-link relative text-gray-800 hover:text-secondary transition-colors duration-300 font-medium">
                        {{ __('welcome.products') }}
                    </a>
                @else
                    <a href="#produk"
                        class="nav-link relative text-gray-800 hover:text-secondary transition-colors duration-300 font-medium">
                        {{ __('welcome.products') }}
                    </a>
                @endauth

                <!-- Reservasi -->
                @auth
                    <a href="{{ route('dashboard') }}#reservasi"
                        class="nav-link relative text-gray-800 hover:text-secondary transition-colors duration-300 font-medium">
                        {{ __('welcome.reservation') }}
                    </a>
                @else
                    <a href="#reservasi"
                        class="nav-link relative text-gray-800 hover:text-secondary transition-colors duration-300 font-medium">
                        {{ __('welcome.reservation') }}
                    </a>
                @endauth

                <!-- Rekomendasi Gaya -->
                <a href="{{ route('rekomendasi.index') }}"
                    class="nav-link relative text-gray-800 hover:text-secondary transition-colors duration-300 font-medium">
                    {{ __('welcome.style_recommendations') }}
                </a>
            </div>

            <!-- Right Section: Language Switcher & User Profile -->
            <div class="flex items-center space-x-4 flex-shrink-0">
                <!-- Language Switcher -->
                @include('components.language-switcher')

                @auth
                    <!-- Loyalty Notification Icon -->
                    @php
                        $userLoyalty = Auth::user()->loyalty;
                        $loyaltyPoints = $userLoyalty ? $userLoyalty->points : 0;
                        $showNotification = $loyaltyPoints == 10;
                    @endphp

                    @if ($showNotification)
                        <div class="relative" x-data="{ showTooltip: false }">
                            <button @mouseenter="showTooltip = true" @mouseleave="showTooltip = false"
                                @click="showLoyaltyNotification()"
                                class="relative flex items-center justify-center w-10 h-10 bg-yellow-500 rounded-full text-white hover:bg-yellow-600 transform hover:scale-105 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-opacity-50">

                                <!-- Bell Icon -->
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M10 2a6 6 0 00-6 6v2.586l-.707.707A1 1 0 004 13h12a1 1 0 00.707-1.707L16 10.586V8a6 6 0 00-6-6zM10 18a2 2 0 01-2-2h4a2 2 0 01-2 2z" />
                                </svg>

                                <!-- Notification Dot -->
                                <span class="absolute top-0 right-0 w-3 h-3 bg-red-500 rounded-full animate-pulse"></span>

                            </button>

                            <!-- Tooltip -->
                            <div x-show="showTooltip" x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90"
                                class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 text-xs text-white bg-gray-800 rounded whitespace-nowrap z-50"
                                x-cloak>
                                {{ __('welcome.loyalty_notification') }}
                                <div class="absolute top-full left-1/2 transform -translate-x-1/2 -mt-1">
                                    <div class="border-4 border-transparent border-t-gray-800"></div>
                                </div>
                            </div>

                        </div>
                    @endif


                    <div class="relative" x-data="{ open: false }" x-init="open = false">
                        <!-- Profile Circle Button -->
                        <button @click="open = !open"
                            class="flex items-center justify-center w-10 h-10 bg-[#d4af37] rounded-full text-white font-semibold text-sm hover:shadow-lg transform hover:scale-105 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50"
                            aria-label="User menu" aria-haspopup="true" :aria-expanded="open">
                            @if (Auth::user()->profile_photo)
                                <img src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}"
                                    class="w-full h-full rounded-full object-cover">
                            @else
                                <span class="text-sm font-bold">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </span>
                            @endif
                        </button>

                        <!-- Dropdown menu -->
                        <div x-show="open" x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                            @click.away="open = false" @keydown.escape.window="open = false"
                            class="absolute right-0 mt-2 w-64 bg-white rounded-lg shadow-xl py-2 z-50 focus:outline-none border border-gray-100"
                            x-cloak>

                            <!-- User Info Header -->
                            <div class="px-4 py-3 border-b border-gray-100">
                                <div class="flex items-center space-x-3">
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ Auth::user()->name }}</p>
                                        <p class="text-sm text-gray-500">{{ Auth::user()->email }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Profile link -->
                            <a href="{{ route('profile.edit') }}"
                                class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 hover:text-gray-900 transition-colors">
                                <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span>{{ __('welcome.profile_settings') }}</span>
                            </a>

                            <!-- Riwayat Transaksi -->
                            <a href="{{ route('payment.index') }}"
                                class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 hover:text-gray-900 transition-colors">
                                <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 17v-2h6v2m-7 4h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14l2-2 2 2 2-2 2 2 2-2" />
                                </svg>
                                <span>{{ __('welcome.transaction_history') }}</span>
                            </a>

                            <!-- Riwayat Booking -->
                            <a href="{{ route('bookings.index') }}"
                                class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 hover:text-gray-900 transition-colors">
                                <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 4H7a2 2 0 01-2-2V6a2 2 0 012-2h7l5 5v11a2 2 0 01-2 2z" />
                                </svg>
                                <span>{{ __('welcome.booking_history') }}</span>
                            </a>

                            <!-- Divider -->
                            <div class="border-t border-gray-100 my-2"></div>

                            <!-- Logout form -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="flex items-center w-full px-4 py-3 text-sm text-red-600 hover:bg-red-50 hover:text-red-700 transition-colors">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    <span>{{ __('welcome.logout') }}</span>
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <!-- Login/Register buttons for guests -->
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('login') }}"
                            class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 transition-colors">
                            {{ __('auth.login') }}
                        </a>
                        <a href="{{ route('register') }}"
                            class="px-4 py-2 text-sm font-medium bg-[#d4af37] text-white rounded-lg hover:from-blue-600 hover:to-purple-700 transform hover:scale-105 transition-all duration-200">
                            {{ __('auth.register') }}
                        </a>
                    </div>
                @endauth

                <!-- Mobile Menu Button -->
                <button class="lg:hidden text-2xl focus:outline-none ml-4" id="mobile-menu-button">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </nav>
    </div>

    <!-- Mobile Menu -->
    <div class="lg:hidden hidden bg-white py-4 px-4 shadow-lg" id="mobile-menu">
        <div class="flex flex-col space-y-4">
            <!-- Mobile Navigation Links -->
            <!-- Beranda -->
            @auth
                <a href="{{ route('dashboard') }}" class="text-gray-800 hover:text-secondary transition-colors py-2">
                    {{ __('welcome.home') }}
                </a>
            @else
                <a href="#beranda" class="text-gray-800 hover:text-secondary transition-colors py-2">
                    {{ __('welcome.home') }}
                </a>
            @endauth

            <!-- Layanan -->
            @auth
                <a href="{{ route('dashboard') }}#layanan"
                    class="text-gray-800 hover:text-secondary transition-colors py-2">
                    {{ __('welcome.services') }}
                </a>
            @else
                <a href="#layanan" class="text-gray-800 hover:text-secondary transition-colors py-2">
                    {{ __('welcome.services') }}
                </a>
            @endauth

            <!-- Tentang -->
            @auth
                <a href="{{ route('dashboard') }}#tentang"
                    class="text-gray-800 hover:text-secondary transition-colors py-2">
                    {{ __('welcome.about') }}
                </a>
            @else
                <a href="#tentang" class="text-gray-800 hover:text-secondary transition-colors py-2">
                    {{ __('welcome.about') }}
                </a>
            @endauth

            <!-- Produk -->
            @auth
                <a href="{{ route('dashboard') }}#produk"
                    class="text-gray-800 hover:text-secondary transition-colors py-2">
                    {{ __('welcome.products') }}
                </a>
            @else
                <a href="#produk" class="text-gray-800 hover:text-secondary transition-colors py-2">
                    {{ __('welcome.products') }}
                </a>
            @endauth

            <!-- Reservasi -->
            @auth
                <a href="{{ route('dashboard') }}#reservasi"
                    class="text-gray-800 hover:text-secondary transition-colors py-2">
                    {{ __('welcome.reservation') }}
                </a>
            @else
                <a href="#reservasi" class="text-gray-800 hover:text-secondary transition-colors py-2">
                    {{ __('welcome.reservation') }}
                </a>
            @endauth

            <!-- Rekomendasi Gaya -->
            <a href="{{ route('rekomendasi.index') }}"
                class="text-gray-800 hover:text-secondary transition-colors py-2">
                {{ __('welcome.style_recommendations') }}
            </a>


        </div>
    </div>
</header>

<!-- Custom Dialog Modal -->
<div id="loyalty-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl max-w-md mx-4 transform transition-all duration-300 scale-95"
        id="loyalty-modal-content">
        <div class="p-6">
            <div class="flex items-center justify-center w-16 h-16 bg-yellow-500 rounded-full mx-auto mb-4">
                <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path
                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.538 1.118l2.8-2.034a1 1 0 011.175 0l2.8 2.034c.783.193 1.175-.197 1.118-1.538l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.969-1.688-.588-1.81l1.07-3.292z" />
                </svg>
            </div>

            <h3 class="text-lg font-semibold text-gray-900 text-center mb-2">{{ __('welcome.congratulations') }}</h3>
            <p class="text-gray-600 text-center mb-6">{{ __('welcome.free_haircut_available') }}</p>

            <div class="flex justify-center space-x-3">
                <button onclick="closeLoyaltyNotification()"
                    class="px-6 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors duration-200 font-medium">
                    {{ __('general.close') }}
                </button>

            </div>
        </div>
    </div>
</div>

<script>
    function showLoyaltyNotification() {
        const modal = document.getElementById('loyalty-modal');
        const modalContent = document.getElementById('loyalty-modal-content');

        modal.classList.remove('hidden');
        modal.classList.add('flex');

        // Trigger animation
        setTimeout(function() {
            modalContent.classList.remove('scale-95');
            modalContent.classList.add('scale-100');
        }, 10);

        // Close modal when clicking outside
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeLoyaltyNotification();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeLoyaltyNotification();
            }
        });
    }

    function closeLoyaltyNotification() {
        const modal = document.getElementById('loyalty-modal');
        const modalContent = document.getElementById('loyalty-modal-content');

        modalContent.classList.remove('scale-100');
        modalContent.classList.add('scale-95');

        setTimeout(function() {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 200);
    }
</script>
