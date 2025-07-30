<header class="fixed w-full bg-white bg-opacity-95 z-50 shadow-sm transition-all duration-300" id="navbar">
    <div class="container mx-auto px-4 py-4">
        <nav class="flex justify-between items-center">
            <div class="text-2xl font-playfair font-bold tracking-wider">
                <span class="text-primary">WOX'S Barbershop</span><span class="text-secondary">.</span>
            </div>

            <div class="hidden md:flex space-x-8">
                <!-- Beranda -->
                @auth
                    <a href="{{ route('dashboard') }}"
                        class="nav-link relative text-gray-800 hover:text-secondary transition-colors duration-300">Beranda</a>
                @else
                    <a href="#beranda"
                        class="nav-link relative text-gray-800 hover:text-secondary transition-colors duration-300">Beranda</a>
                @endauth

                <!-- Layanan -->
                @auth
                    <a href="{{ route('dashboard') }}#layanan"
                        class="nav-link relative text-gray-800 hover:text-secondary transition-colors duration-300">Layanan</a>
                @else
                    <a href="#layanan"
                        class="nav-link relative text-gray-800 hover:text-secondary transition-colors duration-300">Layanan</a>
                @endauth

                <!-- Tentang -->
                @auth
                    <a href="{{ route('dashboard') }}#tentang"
                        class="nav-link relative text-gray-800 hover:text-secondary transition-colors duration-300">Tentang</a>
                @else
                    <a href="#tentang"
                        class="nav-link relative text-gray-800 hover:text-secondary transition-colors duration-300">Tentang</a>
                @endauth

                <!-- Produk -->
                @auth
                    <a href="{{ route('dashboard') }}#produk"
                        class="nav-link relative text-gray-800 hover:text-secondary transition-colors duration-300">Produk</a>
                @else
                    <a href="#produk"
                        class="nav-link relative text-gray-800 hover:text-secondary transition-colors duration-300">Produk</a>
                @endauth

                <!-- Reservasi -->
                @auth
                    <a href="{{ route('dashboard') }}#reservasi"
                        class="nav-link relative text-gray-800 hover:text-secondary transition-colors duration-300">Reservasi</a>
                @else
                    <a href="#reservasi"
                        class="nav-link relative text-gray-800 hover:text-secondary transition-colors duration-300">Reservasi</a>
                @endauth

                <!-- Rekomendasi Gaya -->
                @auth
                    <a href="{{ route('rekomendasi') }}"
                        class="nav-link relative text-gray-800 hover:text-secondary transition-colors duration-300">Rekomendasi
                        Gaya</a>
                @else
                    <a href="{{ route('rekomendasi') }}"
                        class="nav-link relative text-gray-800 hover:text-secondary transition-colors duration-300">Rekomendasi
                        Gaya</a>
                @endauth

                @auth

                    <div class="relative" x-data="{ open: false }" x-init="open = false">
                        <!-- Dropdown trigger button -->
                        <button @click="open = !open" class="flex items-center space-x-1 focus:outline-none"
                            aria-label="User menu" aria-haspopup="true" :aria-expanded="open">
                            <span class="font-medium text-gray-700 hover:text-secondary transition-colors">
                                {{ Auth::user()->name }}
                            </span>
                            <svg class="w-4 h-4 text-gray-500 transition-transform duration-200"
                                :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <!-- Dropdown menu -->
                        <div x-show="open" x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                            @click.away="open = false" @keydown.escape.window="open = false"
                            class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 focus:outline-none"
                            x-cloak>
                            <!-- Profile link -->
                            <a href="{{ route('profile.edit') }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 transition-colors">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>

                                    Profile
                                </div>
                            </a>

                            <a href="{{ route('midtrans.index') }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 transition-colors">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 17v-2h6v2m-7 4h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14l2-2 2 2 2-2 2 2 2-2" />
                                    </svg>

                                    Riwayat Transaksi
                                </div>
                            </a>

                            <a href="{{ route('bookings.index') }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 transition-colors">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 4H7a2 2 0 01-2-2V6a2 2 0 012-2h7l5 5v11a2 2 0 01-2 2z" />
                                    </svg>


                                    Riwayat Booking
                                </div>
                            </a>

                            <!-- Divider -->
                            <div class="border-t border-gray-100 my-1"></div>

                            <!-- Logout form -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 transition-colors">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                        </svg>
                                        Logout
                                    </div>
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <!-- Tampilan sebelum login -->
                    <a href="{{ route('login') }}"
                        class="nav-link relative text-gray-800 hover:text-secondary transition-colors duration-300">
                        Login
                    </a>
                @endauth
            </div>

            <button class="md:hidden text-2xl focus:outline-none" id="mobile-menu-button">
                <i class="fas fa-bars"></i>
            </button>
        </nav>
    </div>

    <!-- Mobile Menu -->
    <div class="md:hidden hidden bg-white py-4 px-4 shadow-lg" id="mobile-menu">
        <div class="flex flex-col space-y-4">
            <!-- Beranda -->
            @auth
                <a href="{{ route('dashboard') }}"
                    class="text-gray-800 hover:text-secondary transition-colors">Beranda</a>
            @else
                <a href="#beranda" class="text-gray-800 hover:text-secondary transition-colors">Beranda</a>
            @endauth

            <!-- Layanan -->
            @auth
                <a href="{{ route('dashboard') }}#layanan"
                    class="text-gray-800 hover:text-secondary transition-colors">Layanan</a>
            @else
                <a href="#layanan" class="text-gray-800 hover:text-secondary transition-colors">Layanan</a>
            @endauth

            <!-- Tentang -->
            @auth
                <a href="{{ route('dashboard') }}#tentang"
                    class="text-gray-800 hover:text-secondary transition-colors">Tentang</a>
            @else
                <a href="#tentang" class="text-gray-800 hover:text-secondary transition-colors">Tentang</a>
            @endauth

            <!-- Produk -->
            @auth
                <a href="{{ route('dashboard') }}#produk"
                    class="text-gray-800 hover:text-secondary transition-colors">Produk</a>
            @else
                <a href="#produk" class="text-gray-800 hover:text-secondary transition-colors">Produk</a>
            @endauth

            <!-- Reservasi -->
            @auth
                <a href="{{ route('dashboard') }}#reservasi"
                    class="text-gray-800 hover:text-secondary transition-colors">Reservasi</a>
            @else
                <a href="#reservasi" class="text-gray-800 hover:text-secondary transition-colors">Reservasi</a>
            @endauth

            <!-- Rekomendasi Gaya -->
            <a href="{{ route('rekomendasi') }}"
                class="text-gray-800 hover:text-secondary transition-colors">Rekomendasi
                Gaya</a>

            @auth
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-gray-800 hover:text-secondary transition-colors w-full text-left">
                        Logout
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="text-gray-800 hover:text-secondary transition-colors">Login</a>
            @endauth
        </div>
    </div>
</header>
