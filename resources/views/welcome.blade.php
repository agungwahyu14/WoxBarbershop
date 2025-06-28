<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gourmet Restaurant | Modern Dining Experience</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#1a1a1a',
                        secondary: '#d4af37',
                        light: '#f8f8f8',
                        dark: '#111111',
                    },
                    fontFamily: {
                        playfair: ['Playfair Display', 'serif'],
                        roboto: ['Roboto', 'sans-serif'],
                    },
                    animation: {
                        'fade-in': 'fadeIn 1s ease-in-out',
                        'slide-up': 'slideUp 0.8s ease-out',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': {
                                opacity: '0'
                            },
                            '100%': {
                                opacity: '1'
                            },
                        },
                        slideUp: {
                            '0%': {
                                transform: 'translateY(20px)',
                                opacity: '0'
                            },
                            '100%': {
                                transform: 'translateY(0)',
                                opacity: '1'
                            },
                        },
                    },
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&family=Roboto:wght@300;400;500&display=swap');

        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -2px;
            left: 0;
            background-color: #d4af37;
            transition: width 0.3s;
        }

        .nav-link:hover::after {
            width: 100%;
        }

        .feature-card:hover .feature-number {
            opacity: 0.8;
        }

        .menu-item:hover .menu-img {
            transform: scale(1.05);
        }

        .parallax {
            background-attachment: fixed;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }
    </style>
</head>

<body class="font-roboto text-gray-800 bg-light">
    <!-- Navigation -->
    <header class="fixed w-full bg-white bg-opacity-95 z-50 shadow-sm transition-all duration-300" id="navbar">
        <div class="container mx-auto px-4 py-4">
            <nav class="flex justify-between items-center">
                <div class="text-2xl font-playfair font-bold tracking-wider">
                    <span class="text-primary">WOX'S Barbershop</span><span class="text-secondary">.</span>
                </div>

                <div class="hidden md:flex space-x-8">
                    <a href="#"
                        class="nav-link relative text-gray-800 hover:text-secondary transition-colors duration-300">Beranda</a>
                    <a href="#about"
                        class="nav-link relative text-gray-800 hover:text-secondary transition-colors duration-300">Tentang</a>
                    <a href="#products"
                        class="nav-link relative text-gray-800 hover:text-secondary transition-colors duration-300">Product</a>
                    <a href="#services"
                        class="nav-link relative text-gray-800 hover:text-secondary transition-colors duration-300">Service</a>
                    <a href="#reservation"
                        class="nav-link relative text-gray-800 hover:text-secondary transition-colors duration-300">Reservasi</a>
                </div>

                <button class="md:hidden text-2xl focus:outline-none" id="mobile-menu-button">
                    <i class="fas fa-bars"></i>
                </button>
            </nav>
        </div>

        <!-- Mobile Menu -->
        <div class="md:hidden hidden bg-white py-4 px-4 shadow-lg" id="mobile-menu">
            <div class="flex flex-col space-y-4">
                <a href="#" class="text-gray-800 hover:text-secondary transition-colors">Beranda</a>
                <a href="#about" class="text-gray-800 hover:text-secondary transition-colors">Tentang</a>
                <a href="#products" class="text-gray-800 hover:text-secondary transition-colors">Product</a>
                <a href="#services" class="text-gray-800 hover:text-secondary transition-colors">Service</a>
                <a href="#reservation" class="text-gray-800 hover:text-secondary transition-colors">Reservasi</a>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="h-screen flex items-center justify-center text-center text-white parallax relative"
        style="background-image: url('{{ asset('images/hero.jpg') }}');">
        <div class="absolute inset-0 bg-black opacity-60"></div>

        <div class="container mx-auto px-4 animate-fade-in relative z-10">
            <h1 class="text-4xl md:text-6xl lg:text-7xl font-playfair font-bold mb-6 leading-tight">
                Tampilan Rapi, Gaya Terbaik <br> di Setiap Potongan
            </h1>
            <p class="text-xl md:text-2xl mb-8 max-w-2xl mx-auto opacity-90">
                Pangkas rambut profesional untuk gaya yang sempurna. Rasakan pengalaman barbershop premium kami.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="#reservation"
                    class="bg-secondary hover:bg-white text-primary hover:text-primary px-8 py-3 rounded-full font-medium transition-all duration-300 transform hover:-translate-y-1 shadow-lg">
                    Buat Janji
                </a>
                <a href="#services"
                    class="border-2 border-white hover:border-secondary text-white hover:text-secondary px-8 py-3 rounded-full font-medium transition-all duration-300 transform hover:-translate-y-1">
                    Lihat Layanan
                </a>
            </div>
        </div>

        <div class="absolute bottom-10 left-1/2 transform -translate-x-1/2 animate-bounce z-10">
            <a href="#about" class="text-white text-2xl">
                <i class="fas fa-chevron-down"></i>
            </a>
        </div>
    </section>

    <!-- Features Section -->


    <!-- Services Section -->
    <section id="services" class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16 animate-slide-up">
                <h2 class="text-3xl md:text-4xl font-playfair font-bold mb-4">Layanan Kami</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Berbagai layanan profesional untuk penampilan terbaik Anda
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Service 1 -->
                <div
                    class="feature-card bg-white p-8 rounded-lg shadow-md hover:shadow-xl transition-all duration-500 transform hover:-translate-y-2">
                    <div
                        class="feature-number text-6xl font-playfair font-bold text-secondary opacity-20 mb-6 transition-all duration-300">
                        01</div>
                    <h3 class="text-xl font-bold mb-4">Potongan Rambut</h3>
                    <p class="text-gray-600 mb-4">
                        Potongan rambut klasik atau modern oleh barber profesional kami dengan teknik terbaik.
                    </p>
                    <a href="#reservation" class="text-secondary font-medium flex items-center group">
                        Pesan Sekarang
                        <i class="fas fa-arrow-right ml-2 group-hover:ml-3 transition-all duration-300"></i>
                    </a>
                </div>

                <!-- Service 2 -->
                <div
                    class="feature-card bg-white p-8 rounded-lg shadow-md hover:shadow-xl transition-all duration-500 transform hover:-translate-y-2">
                    <div
                        class="feature-number text-6xl font-playfair font-bold text-secondary opacity-20 mb-6 transition-all duration-300">
                        02</div>
                    <h3 class="text-xl font-bold mb-4">Cukur Rambut</h3>
                    <p class="text-gray-600 mb-4">
                        Pengalaman cukur tradisional dengan pisau cukur dan perawatan kulit wajah profesional.
                    </p>
                    <a href="#reservation" class="text-secondary font-medium flex items-center group">
                        Pesan Sekarang
                        <i class="fas fa-arrow-right ml-2 group-hover:ml-3 transition-all duration-300"></i>
                    </a>
                </div>

                <!-- Service 3 -->
                <div
                    class="feature-card bg-white p-8 rounded-lg shadow-md hover:shadow-xl transition-all duration-500 transform hover:-translate-y-2">
                    <div
                        class="feature-number text-6xl font-playfair font-bold text-secondary opacity-20 mb-6 transition-all duration-300">
                        03</div>
                    <h3 class="text-xl font-bold mb-4">Perawatan Jenggot</h3>
                    <p class="text-gray-600 mb-4">
                        Perawatan lengkap untuk jenggot Anda mulai dari trim, styling, hingga perawatan kulit.
                    </p>
                    <a href="#reservation" class="text-secondary font-medium flex items-center group">
                        Pesan Sekarang
                        <i class="fas fa-arrow-right ml-2 group-hover:ml-3 transition-all duration-300"></i>
                    </a>
                </div>

                <!-- Service 4 -->
                <div
                    class="feature-card bg-white p-8 rounded-lg shadow-md hover:shadow-xl transition-all duration-500 transform hover:-translate-y-2">
                    <div
                        class="feature-number text-6xl font-playfair font-bold text-secondary opacity-20 mb-6 transition-all duration-300">
                        04</div>
                    <h3 class="text-xl font-bold mb-4">Perawatan Rambut</h3>
                    <p class="text-gray-600 mb-4">
                        Perawatan khusus untuk rambut Anda dengan produk premium dan teknik profesional.
                    </p>
                    <a href="#reservation" class="text-secondary font-medium flex items-center group">
                        Pesan Sekarang
                        <i class="fas fa-arrow-right ml-2 group-hover:ml-3 transition-all duration-300"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-20 bg-light">
        <div class="container mx-auto px-4">
            <div class="flex flex-col lg:flex-row items-center gap-12">
                <div class="lg:w-1/2 animate-slide-up">
                    <h2 class="text-3xl md:text-4xl font-playfair font-bold mb-6">Tentang Wox's Barbershop</h2>
                    <p class="text-gray-600 mb-6">
                        Wox's Barbershop didirikan pada tahun 2010 dengan misi memberikan pengalaman grooming
                        terbaik untuk pria modern. Kami menggabungkan teknik tradisional dengan gaya kontemporer
                        untuk menciptakan tampilan sempurna.
                    </p>
                    <p class="text-gray-600 mb-8">
                        Setiap barber kami adalah profesional berpengalaman yang terus mengikuti perkembangan tren
                        terkini. Kami hanya menggunakan produk-produk berkualitas tinggi untuk memastikan hasil
                        terbaik bagi pelanggan.
                    </p>
                    <a href="#reservation"
                        class="bg-secondary hover:bg-primary text-primary hover:text-white px-8 py-3 rounded-full font-medium transition-all duration-300 inline-block transform hover:-translate-y-1 shadow-lg">
                        Buat Janji
                    </a>
                </div>
                <div class="lg:w-1/2 relative animate-fade-in">
                    <img src="https://images.unsplash.com/photo-1585747860715-2ba37e788b70?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1474&q=80"
                        alt="Barbershop interior" class="rounded-lg shadow-xl w-full h-auto">
                    <div class="absolute -bottom-6 -right-6 bg-secondary p-4 rounded-lg shadow-lg hidden lg:block">
                        <div class="text-4xl font-playfair font-bold text-white">10+</div>
                        <div class="text-white font-medium">Tahun Pengalaman</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Menu Section -->
    <section id="products" class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-playfair font-bold mb-4">Produk Kami</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto mb-8">
                    Produk-produk premium untuk perawatan rambut dan jenggot Anda
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Product 1 -->
                <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-xl transition-all duration-300">
                    <div class="relative h-64 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1600334129128-685c5582fd35?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1470&q=80"
                            alt="Hair Product" class="w-full h-full object-cover">
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-2">Shampoo & Conditioner</h3>
                        <p class="text-gray-600 mb-4">Membersihkan dan melembabkan rambut dengan formula khusus.
                        </p>
                        <div class="flex justify-between items-center">
                            <span class="text-secondary font-bold">Rp 150.000</span>
                            <button
                                class="bg-secondary text-white px-4 py-2 rounded-lg hover:bg-primary transition-colors">
                                Beli Sekarang
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Product 2 -->
                <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-xl transition-all duration-300">
                    <div class="relative h-64 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1596704017256-8e8a0a809b32?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=687&q=80"
                            alt="Beard Oil" class="w-full h-full object-cover">
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-2">Beard Oil</h3>
                        <p class="text-gray-600 mb-4">Minyak jenggot dengan bahan alami untuk jenggot sehat dan
                            lembut.</p>
                        <div class="flex justify-between items-center">
                            <span class="text-secondary font-bold">Rp 200.000</span>
                            <button
                                class="bg-secondary text-white px-4 py-2 rounded-lg hover:bg-primary transition-colors">
                                Beli Sekarang
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Product 3 -->
                <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-xl transition-all duration-300">
                    <div class="relative h-64 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1631729371254-42c2892f0e6e?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1470&q=80"
                            alt="Hair Styling" class="w-full h-full object-cover">
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-2">Hair Styling Wax</h3>
                        <p class="text-gray-600 mb-4">Wax styling dengan hold kuat namun mudah dibentuk.</p>
                        <div class="flex justify-between items-center">
                            <span class="text-secondary font-bold">Rp 180.000</span>
                            <button
                                class="bg-secondary text-white px-4 py-2 rounded-lg hover:bg-primary transition-colors">
                                Beli Sekarang
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Testimonials Section -->
    <section class="py-20 bg-light">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-playfair font-bold mb-4">Testimoni Pelanggan</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Apa kata mereka tentang Wox's Barbershop
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Testimonial 1 -->
                <div class="bg-white p-8 rounded-lg shadow-md">
                    <div class="flex mb-4">
                        <i class="fas fa-star text-secondary mr-1"></i>
                        <i class="fas fa-star text-secondary mr-1"></i>
                        <i class="fas fa-star text-secondary mr-1"></i>
                        <i class="fas fa-star text-secondary mr-1"></i>
                        <i class="fas fa-star text-secondary mr-1"></i>
                    </div>
                    <p class="text-gray-600 italic mb-6">
                        "Pengalaman cukur terbaik yang pernah saya dapatkan. Barber sangat profesional dan hasilnya
                        sempurna!"
                    </p>
                    <div class="font-bold">Andi Wijaya</div>
                    <div class="text-gray-500 text-sm">Pelanggan sejak 2018</div>
                </div>

                <!-- Testimonial 2 -->
                <div class="bg-white p-8 rounded-lg shadow-md">
                    <div class="flex mb-4">
                        <i class="fas fa-star text-secondary mr-1"></i>
                        <i class="fas fa-star text-secondary mr-1"></i>
                        <i class="fas fa-star text-secondary mr-1"></i>
                        <i class="fas fa-star text-secondary mr-1"></i>
                        <i class="fas fa-star text-secondary mr-1"></i>
                    </div>
                    <p class="text-gray-600 italic mb-6">
                        "Produk perawatan jenggot mereka sangat bagus. Jenggot saya jadi lebih sehat dan mudah
                        diatur."
                    </p>
                    <div class="font-bold">Budi Santoso</div>
                    <div class="text-gray-500 text-sm">Pelanggan sejak 2020</div>
                </div>

                <!-- Testimonial 3 -->
                <div class="bg-white p-8 rounded-lg shadow-md">
                    <div class="flex mb-4">
                        <i class="fas fa-star text-secondary mr-1"></i>
                        <i class="fas fa-star text-secondary mr-1"></i>
                        <i class="fas fa-star text-secondary mr-1"></i>
                        <i class="fas fa-star text-secondary mr-1"></i>
                        <i class="fas fa-star text-secondary mr-1"></i>
                    </div>
                    <p class="text-gray-600 italic mb-6">
                        "Atmosfernya sangat nyaman dan stafnya ramah. Potongan rambutnya selalu sesuai permintaan."
                    </p>
                    <div class="font-bold">Rudi Hartono</div>
                    <div class="text-gray-500 text-sm">Pelanggan sejak 2015</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-20 bg-primary text-white parallax"
        style="background-image: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('https://images.unsplash.com/photo-1585747860715-2ba37e788b70?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1474&q=80')">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                <div class="stat-item">
                    <div class="text-5xl md:text-6xl font-playfair font-bold mb-2 text-secondary" data-count="10">
                        0</div>
                    <div class="text-xl">Tahun Pengalaman</div>
                </div>
                <div class="stat-item">
                    <div class="text-5xl md:text-6xl font-playfair font-bold mb-2 text-secondary" data-count="8">0
                    </div>
                    <div class="text-xl">Barber Profesional</div>
                </div>
                <div class="stat-item">
                    <div class="text-5xl md:text-6xl font-playfair font-bold mb-2 text-secondary" data-count="5000">0
                    </div>
                    <div class="text-xl">Pelanggan Puas</div>
                </div>
                <div class="stat-item">
                    <div class="text-5xl md:text-6xl font-playfair font-bold mb-2 text-secondary" data-count="50">
                        0</div>
                    <div class="text-xl">Produk Premium</div>
                </div>
            </div>
        </div>
    </section>


    <div id="table-modal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4 max-h-[80vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold">Pilih Meja</h3>
                <button id="close-table-modal" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="grid grid-cols-2 gap-3 mb-6" id="tables-grid">
                <!-- Tables will be loaded here -->
            </div>

            <button id="confirm-table"
                class="w-full bg-secondary text-white px-4 py-2 rounded-lg font-medium disabled:opacity-50 disabled:cursor-not-allowed"
                disabled>
                Konfirmasi Meja
            </button>
        </div>
    </div>

    <!-- Debug Panel (can be removed in production) -->
    <!-- <div class="fixed bottom-4 right-4 bg-white p-4 rounded-lg shadow-lg border z-40">
        <h4 class="font-bold mb-2 text-sm">Debug Panel</h4>
        <div class="flex flex-col gap-2">
            <button onclick="window.reservationSystem.showReservations()"
                class="px-3 py-1 bg-blue-500 text-white rounded text-xs hover:bg-blue-600">
                Lihat Reservasi
            </button>
            <button onclick="window.reservationSystem.clearAllReservations()"
                class="px-3 py-1 bg-red-500 text-white rounded text-xs hover:bg-red-600">
                Hapus Semua
            </button>
        </div>
    </div> -->


    <section id="reservation" class="pt-8 pb-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-playfair font-bold mb-4">Reservasi</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Buat janji untuk pengalaman grooming terbaik di Wox's Barbershop
                </p>
            </div>

            <div class="flex justify-center">
                <!-- Booking Form -->
                <div class="w-full lg:w-1/2 bg-light rounded-xl shadow-md p-6">
                    <h3 class="text-xl font-bold font-playfair mb-6">Detail Reservasi</h3>

                    <form action="{{ route('booking.store') }}" method="POST" id="booking-form" class="space-y-4">
                        @csrf

                        <!-- Tanggal -->
                        <div>
                            <label for="date" class="block text-gray-700 mb-2">Tanggal</label>
                            <input type="date" id="date" name="date"
                                class="w-full px-4 py-3 border rounded-lg" required>
                        </div>

                        <!-- Waktu -->
                        <div>
                            <label for="time" class="block text-gray-700 mb-2">Waktu</label>
                            <select id="time" name="time" class="w-full px-4 py-3 border rounded-lg"
                                required>
                                <option value="">Pilih Waktu</option>
                                @for ($i = 9; $i <= 19; $i++)
                                    <option value="{{ sprintf('%02d:00', $i) }}">{{ sprintf('%02d:00', $i) }}</option>
                                @endfor
                            </select>
                        </div>

                        <!-- Layanan -->
                        <div>
                            <label for="service_id" class="block text-gray-700 mb-2">Layanan</label>
                            <select name="service_id" id="service_id" class="w-full px-4 py-3 border rounded-lg"
                                required>
                                @foreach ($services as $service)
                                    <option value="{{ $service->id }}">{{ $service->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Gaya Rambut -->
                        <div>
                            <label for="hairstyle_id" class="block text-gray-700 mb-2">Gaya Rambut</label>
                            <select name="hairstyle_id" id="hairstyle_id" class="w-full px-4 py-3 border rounded-lg"
                                required>
                                @foreach ($hairstyles as $hairstyle)
                                    <option value="{{ $hairstyle->id }}">{{ $hairstyle->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Nomor Telepon (jika ingin diinput ulang, atau ambil dari user jika sudah login) -->
                        <div>
                            <label for="no_telepon" class="block text-gray-700 mb-2">Nomor Telepon</label>
                            <input type="tel" name="no_telepon" id="no_telepon"
                                class="w-full px-4 py-3 border rounded-lg" required
                                value="{{ old('no_telepon', auth()->user()->no_telepon ?? '') }}">
                        </div>

                        <!-- Catatan -->
                        <div>
                            <label for="notes" class="block text-gray-700 mb-2">Catatan Khusus</label>
                            <textarea name="notes" id="notes" rows="3" class="w-full px-4 py-3 border rounded-lg"></textarea>
                        </div>

                        <!-- Submit -->
                        <button type="submit"
                            class="w-full bg-secondary hover:bg-primary text-white px-6 py-3 rounded-lg font-medium transition">
                            Konfirmasi Reservasi
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </section>


    <!-- Gallery Section -->
    <section class="py-12 bg-light">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl md:text-4xl font-playfair font-bold mb-12 text-center">Galeri Kami</h2>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                <a href="#"
                    class="gallery-item overflow-hidden rounded-lg shadow-md hover:shadow-xl transition-all duration-300">
                    <img src="https://images.unsplash.com/photo-1595476108010-b4d1f102b1b1?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=688&q=80"
                        alt="Barber at work"
                        class="w-full h-48 object-cover transform hover:scale-110 transition-all duration-500">
                </a>
                <a href="#"
                    class="gallery-item overflow-hidden rounded-lg shadow-md hover:shadow-xl transition-all duration-300">
                    <img src="https://images.unsplash.com/photo-1519699047748-de8e457a634e?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=880&q=80"
                        alt="Haircut"
                        class="w-full h-48 object-cover transform hover:scale-110 transition-all duration-500">
                </a>
                <a href="#"
                    class="gallery-item overflow-hidden rounded-lg shadow-md hover:shadow-xl transition-all duration-300">
                    <img src="https://images.unsplash.com/photo-1567894340315-735d7c361db0?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=687&q=80"
                        alt="Beard trim"
                        class="w-full h-48 object-cover transform hover:scale-110 transition-all duration-500">
                </a>
                <a href="#"
                    class="gallery-item overflow-hidden rounded-lg shadow-md hover:shadow-xl transition-all duration-300">
                    <img src="https://images.unsplash.com/photo-1585747860715-2ba37e788b70?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1474&q=80"
                        alt="Barbershop interior"
                        class="w-full h-48 object-cover transform hover:scale-110 transition-all duration-500">
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer id="contact" class="bg-dark text-white pt-20 pb-10">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 mb-12">
                <div>
                    <h3 class="text-2xl font-playfair font-bold mb-6 text-secondary">WOX'S Barbershop.</h3>
                    <p class="text-gray-400 mb-6">
                        Memberikan pengalaman grooming terbaik dengan layanan profesional dan produk berkualitas
                        tinggi.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-secondary transition-colors duration-300">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-secondary transition-colors duration-300">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-secondary transition-colors duration-300">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                    </div>
                </div>

                <div>
                    <h4 class="text-lg font-bold mb-6">Jam Buka</h4>
                    <ul class="space-y-3 text-gray-400">
                        <li class="flex justify-between">
                            <span>Senin - Jumat</span>
                            <span>09.00 - 20.00</span>
                        </li>
                        <li class="flex justify-between">
                            <span>Sabtu</span>
                            <span>09.00 - 22.00</span>
                        </li>
                        <li class="flex justify-between">
                            <span>Minggu</span>
                            <span>10.00 - 18.00</span>
                        </li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-lg font-bold mb-6">Kontak Kami</h4>
                    <ul class="space-y-3 text-gray-400">
                        <li class="flex items-start">
                            <i class="fas fa-map-marker-alt mt-1 mr-3 text-secondary"></i>
                            <span>Jl. Barber No. 123, Jakarta Selatan</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-phone-alt mr-3 text-secondary"></i>
                            <span>(021) 1234-5678</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-envelope mr-3 text-secondary"></i>
                            <span>info@woxsbarbershop.com</span>
                        </li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-lg font-bold mb-6">Newsletter</h4>
                    <p class="text-gray-400 mb-4">
                        Daftar newsletter kami untuk info promo dan tips perawatan rambut.
                    </p>
                    <form class="flex">
                        <input type="email" placeholder="Email Anda"
                            class="px-4 py-3 w-full rounded-l-lg focus:outline-none text-gray-800">
                        <button type="submit" class="bg-secondary text-primary px-4 rounded-r-lg">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>
                </div>
            </div>

            <div class="border-t border-gray-800 pt-6 text-center text-gray-500">
                <p>&copy; 2023 WOX'S Barbershop. Hak cipta dilindungi.</p>
            </div>
        </div>
    </footer>

    <!-- Back to Top Button -->
    <button id="back-to-top"
        class="fixed bottom-8 right-8 bg-secondary text-primary p-3 rounded-full shadow-lg opacity-0 invisible transition-all duration-300">
        <i class="fas fa-arrow-up"></i>
    </button>



</body>

</html>
