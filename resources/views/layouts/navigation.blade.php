<header class="fixed w-full bg-white bg-opacity-95 z-50 shadow-sm transition-all duration-300" id="navbar">
    <div class="container mx-auto px-4 py-4">
        <nav class="flex items-center justify-between">
            <!-- Left Section: Logo -->
            <div class="flex items-center space-x-3 flex-shrink-0">
                <img src="{{ asset('images/Logo.jpeg') }}" alt="WOX'S Barbershop Logo" class="h-12 w-auto object-contain">

            </div>

            <!-- Center Section: Desktop Navigation Menu -->
            <div class="hidden lg:flex items-center space-x-8 flex-1 justify-center">
                <!-- Beranda -->
                @auth
                    <a href="{{ route('dashboard') }}"
                        class="nav-link relative text-gray-800 hover:text-secondary transition-colors duration-300 font-medium">
                        Beranda
                    </a>
                @else
                    <a href="#beranda"
                        class="nav-link relative text-gray-800 hover:text-secondary transition-colors duration-300 font-medium">
                        Beranda
                    </a>
                @endauth

                <!-- Layanan -->
                @auth
                    <a href="{{ route('dashboard') }}#layanan"
                        class="nav-link relative text-gray-800 hover:text-secondary transition-colors duration-300 font-medium">
                        Layanan
                    </a>
                @else
                    <a href="#layanan"
                        class="nav-link relative text-gray-800 hover:text-secondary transition-colors duration-300 font-medium">
                        Layanan
                    </a>
                @endauth

                <!-- Tentang -->
                @auth
                    <a href="{{ route('dashboard') }}#tentang"
                        class="nav-link relative text-gray-800 hover:text-secondary transition-colors duration-300 font-medium">
                        Tentang
                    </a>
                @else
                    <a href="#tentang"
                        class="nav-link relative text-gray-800 hover:text-secondary transition-colors duration-300 font-medium">
                        Tentang
                    </a>
                @endauth

                <!-- Produk -->
                @auth
                    <a href="{{ route('dashboard') }}#produk"
                        class="nav-link relative text-gray-800 hover:text-secondary transition-colors duration-300 font-medium">
                        Produk
                    </a>
                @else
                    <a href="#produk"
                        class="nav-link relative text-gray-800 hover:text-secondary transition-colors duration-300 font-medium">
                        Produk
                    </a>
                @endauth

                <!-- Reservasi -->
                @auth
                    <a href="{{ route('dashboard') }}#reservasi"
                        class="nav-link relative text-gray-800 hover:text-secondary transition-colors duration-300 font-medium">
                        Reservasi
                    </a>
                @else
                    <a href="#reservasi"
                        class="nav-link relative text-gray-800 hover:text-secondary transition-colors duration-300 font-medium">
                        Reservasi
                    </a>
                @endauth

                <!-- Rekomendasi Gaya -->
                <a href="{{ route('rekomendasi.index') }}"
                    class="nav-link relative text-gray-800 hover:text-secondary transition-colors duration-300 font-medium">
                    Rekomendasi Gaya
                </a>
            </div>

            <!-- Right Section: User Profile Circle -->
            <div class="flex items-center space-x-4 flex-shrink-0">
                @auth
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
                                    <div
                                        class="flex items-center justify-center w-12 h-12 bg-[#d4af37] rounded-full text-white font-semibold">
                                        @if (Auth::user()->profile_photo)
                                            <img src="{{ Auth::user()->profile_photo_url }}"
                                                alt="{{ Auth::user()->name }}"
                                                class="w-full h-full rounded-full object-cover">
                                        @else
                                            <span class="text-lg font-bold">
                                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                            </span>
                                        @endif
                                    </div>
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
                                <span>Profile Settings</span>
                            </a>

                            <!-- Riwayat Transaksi -->
                            <a href="{{ route('payment.index') }}"
                                class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 hover:text-gray-900 transition-colors">
                                <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 17v-2h6v2m-7 4h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14l2-2 2 2 2-2 2 2 2-2" />
                                </svg>
                                <span>Riwayat Transaksi</span>
                            </a>

                            <!-- Riwayat Booking -->
                            <a href="{{ route('bookings.index') }}"
                                class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 hover:text-gray-900 transition-colors">
                                <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 4H7a2 2 0 01-2-2V6a2 2 0 012-2h7l5 5v11a2 2 0 01-2 2z" />
                                </svg>
                                <span>Riwayat Booking</span>
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
                                    <span>Logout</span>
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <!-- Login/Register buttons for guests -->
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('login') }}"
                            class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 transition-colors">
                            Login
                        </a>
                        <a href="{{ route('register') }}"
                            class="px-4 py-2 text-sm font-medium bg-[#d4af37] text-white rounded-lg hover:from-blue-600 hover:to-purple-700 transform hover:scale-105 transition-all duration-200">
                            Register
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
                    Beranda
                </a>
            @else
                <a href="#beranda" class="text-gray-800 hover:text-secondary transition-colors py-2">
                    Beranda
                </a>
            @endauth

            <!-- Layanan -->
            @auth
                <a href="{{ route('dashboard') }}#layanan"
                    class="text-gray-800 hover:text-secondary transition-colors py-2">
                    Layanan
                </a>
            @else
                <a href="#layanan" class="text-gray-800 hover:text-secondary transition-colors py-2">
                    Layanan
                </a>
            @endauth

            <!-- Tentang -->
            @auth
                <a href="{{ route('dashboard') }}#tentang"
                    class="text-gray-800 hover:text-secondary transition-colors py-2">
                    Tentang
                </a>
            @else
                <a href="#tentang" class="text-gray-800 hover:text-secondary transition-colors py-2">
                    Tentang
                </a>
            @endauth

            <!-- Produk -->
            @auth
                <a href="{{ route('dashboard') }}#produk"
                    class="text-gray-800 hover:text-secondary transition-colors py-2">
                    Produk
                </a>
            @else
                <a href="#produk" class="text-gray-800 hover:text-secondary transition-colors py-2">
                    Produk
                </a>
            @endauth

            <!-- Reservasi -->
            @auth
                <a href="{{ route('dashboard') }}#reservasi"
                    class="text-gray-800 hover:text-secondary transition-colors py-2">
                    Reservasi
                </a>
            @else
                <a href="#reservasi" class="text-gray-800 hover:text-secondary transition-colors py-2">
                    Reservasi
                </a>
            @endauth

            <!-- Rekomendasi Gaya -->
            <a href="{{ route('rekomendasi.index') }}"
                class="text-gray-800 hover:text-secondary transition-colors py-2">
                Rekomendasi Gaya
            </a>

            <!-- Authentication Links for Mobile -->
            @auth
                <!-- Mobile Profile Section -->
                <div class="border-t border-gray-200 pt-4 mt-4">
                    <div class="flex items-center space-x-3 mb-4">
                        <div
                            class="flex items-center justify-center w-10 h-10 bg-[#d4af37] rounded-full text-white font-semibold text-sm">
                            @if (Auth::user()->profile_photo)
                                <img src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}"
                                    class="w-full h-full rounded-full object-cover">
                            @else
                                <span class="text-sm font-bold">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </span>
                            @endif
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">{{ Auth::user()->name }}</p>
                            <p class="text-sm text-gray-500">{{ Auth::user()->email }}</p>
                        </div>
                    </div>

                    <a href="{{ route('profile.edit') }}"
                        class="flex items-center text-gray-800 hover:text-secondary transition-colors py-2">
                        <svg class="w-5 h-5 mr-3 text-gray-500" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Profile Settings
                    </a>

                    <a href="{{ route('payment.index') }}"
                        class="flex items-center text-gray-800 hover:text-secondary transition-colors py-2">
                        <svg class="w-5 h-5 mr-3 text-gray-500" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 17v-2h6v2m-7 4h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14l2-2 2 2 2-2 2 2 2-2" />
                        </svg>
                        Riwayat Transaksi
                    </a>

                    <a href="{{ route('bookings.index') }}"
                        class="flex items-center text-gray-800 hover:text-secondary transition-colors py-2">
                        <svg class="w-5 h-5 mr-3 text-gray-500" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 4H7a2 2 0 01-2-2V6a2 2 0 012-2h7l5 5v11a2 2 0 01-2 2z" />
                        </svg>
                        Riwayat Booking
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="flex items-center text-red-600 hover:text-red-700 transition-colors w-full text-left py-2">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            Logout
                        </button>
                    </form>
                </div>
            @else
                <div class="border-t border-gray-200 pt-4 mt-4 space-y-2">
                    <a href="{{ route('login') }}"
                        class="block w-full text-center py-2 px-4 text-gray-800 hover:text-secondary transition-colors border border-gray-300 rounded-lg">
                        Login
                    </a>
                    <a href="{{ route('register') }}"
                        class="block w-full text-center py-2 px-4 bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-lg hover:from-blue-600 hover:to-purple-700 transition-all">
                        Register
                    </a>
                </div>
            @endauth
        </div>
    </div>
</header>
