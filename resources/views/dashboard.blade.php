@extends('layouts.app')

@section('content')
    <section id="beranda" class="h-screen flex items-center justify-center text-center text-white parallax relative"
        style="background-image: url('{{ asset('images/hero.jpg') }}');">
        <div class="absolute inset-0 bg-black opacity-60"></div>

        <div class="container mx-auto px-4 animate-fade-in relative z-10">
            <!-- Contoh pada Hero Section -->
            <h1 class="font-playfair font-bold text-3xl sm:text-4xl md:text-6xl lg:text-7xl mb-6 leading-tight">
                Tampilan Rapi, Gaya Terbaik <br> di Setiap Potongan
            </h1>
            <p class="text-base sm:text-lg md:text-xl lg:text-2xl mb-8 max-w-2xl mx-auto opacity-90">
                Pangkas rambut profesional untuk gaya yang sempurna. Rasakan pengalaman barbershop premium kami.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="#reservation"
                    class="bg-secondary hover:bg-white text-primary hover:text-primary px-8 py-3  font-medium transition-all duration-300 transform hover:-translate-y-1 shadow-lg uppercase">
                    RESERVASI
                </a>
                <a href="#services"
                    class="border-2 border-white hover:border-secondary text-white hover:text-secondary px-8 py-3  font-medium transition-all duration-300 transform hover:-translate-y-1 uppercase">
                    LIHAT LAYANAN
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
    <section id="layanan" class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16 animate-slide-up">
                <!-- Contoh pada Section Judul -->
                <h2 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-playfair font-bold mb-4">
                    Layanan Kami
                </h2>
                <p class="text-base sm:text-lg md:text-xl text-gray-600 max-w-2xl mx-auto">
                    Berbagai layanan profesional untuk penampilan terbaik Anda
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Service 1 -->
                <div
                    class="feature-card bg-white p-8  shadow-md hover:shadow-xl transition-all duration-500 transform hover:-translate-y-2">
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
                    class="feature-card bg-white p-8  shadow-md hover:shadow-xl transition-all duration-500 transform hover:-translate-y-2">
                    <div
                        class="feature-number text-6xl font-playfair font-bold text-secondary opacity-20 mb-6 transition-all duration-300">
                        02</div>
                    <h3 class="text-xl font-bold mb-4">Produk Kami</h3>
                    <p class="text-gray-600 mb-4">
                        Kami mempercayakan hanya produk-produk pilihan yang telah teruji kualitasnya.
                    </p>
                    <a href="#reservation" class="text-secondary font-medium flex items-center group">
                        Pesan Sekarang
                        <i class="fas fa-arrow-right ml-2 group-hover:ml-3 transition-all duration-300"></i>
                    </a>
                </div>

                <!-- Service 3 -->
                <div
                    class="feature-card bg-white p-8  shadow-md hover:shadow-xl transition-all duration-500 transform hover:-translate-y-2">
                    <div
                        class="feature-number text-6xl font-playfair font-bold text-secondary opacity-20 mb-6 transition-all duration-300">
                        03</div>
                    <h3 class="text-xl font-bold mb-4">Rekomendasi Gaya</h3>
                    <p class="text-gray-600 mb-4">
                        Rekomendasi gaya rambut berdasarkan wajah, tren sesuai dengan keinginan.
                    </p>
                    <a href="#reservation" class="text-secondary font-medium flex items-center group">
                        Pesan Sekarang
                        <i class="fas fa-arrow-right ml-2 group-hover:ml-3 transition-all duration-300"></i>
                    </a>
                </div>

                <!-- Service 4 -->
                <div
                    class="feature-card bg-white p-8  shadow-md hover:shadow-xl transition-all duration-500 transform hover:-translate-y-2">
                    <div
                        class="feature-number text-6xl font-playfair font-bold text-secondary opacity-20 mb-6 transition-all duration-300">
                        04</div>
                    <h3 class="text-xl font-bold mb-4">Loyalty</h3>
                    <p class="text-gray-600 mb-4">
                        Gratis 1x cukur setelah 10 kali kunjungan.Apresiasi kami untuk pelanggan setia!
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
    <section id="tentang" class="py-20 bg-light">
        <div class="container mx-auto px-4">
            <div class="flex flex-col lg:flex-row items-center gap-12">
                <div class="lg:w-1/2 animate-slide-up">
                    <h2 class="text-3xl md:text-4xl font-playfair font-bold mb-6">Tentang Wox's Barbershop</h2>
                    <p class="text-gray-600 mb-6 text-justify">
                        Wox’s Barbershop, yang berlokasi strategis di Jalan Raya Semabaung, Gianyar, didirikan pada
                        tahun 2023 dengan misi memberikan pengalaman grooming terbaik bagi pria modern. Meskipun
                        tergolong baru, Wox's Barbershop telah berhasil menarik perhatian pelanggan dengan menggabungkan
                        teknik barber tradisional dan gaya kontemporer, menghasilkan potongan rambut yang stylish dan
                        sesuai tren terkini.
                    </p>
                    <p class="text-gray-600 mb-8 text-justify">
                        Setiap barber yang bertugas merupakan tenaga profesional berpengalaman yang terus mengikuti
                        perkembangan gaya dan kebutuhan pelanggan. Untuk menjaga kualitas layanan, Wox’s Barbershop
                        hanya menggunakan produk-produk perawatan rambut yang telah teruji dan berkualitas tinggi,
                        sehingga menjamin hasil optimal bagi setiap pelanggan.
                    </p>
                    <a href="#reservation"
                        class="bg-secondary hover:bg-primary text-primary hover:text-white px-8 py-3  font-medium transition-all duration-300 inline-block transform hover:-translate-y-1 shadow-lg">
                        RESERVASI
                    </a>
                </div>
                <div class="lg:w-1/2 relative animate-fade-in">
                    <img src="https://images.unsplash.com/photo-1585747860715-2ba37e788b70?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1474&q=80"
                        alt="Barbershop interior" class=" shadow-xl w-full h-auto">
                </div>
            </div>
        </div>
    </section>

    <!-- Menu Section -->
    <section id="produk" class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-playfair font-bold mb-4">Produk Kami</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto mb-8">
                    Produk-produk premium untuk perawatan rambut dan jenggot Anda
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Product 1 -->
                <div class="bg-white  overflow-hidden shadow-md hover:shadow-xl transition-all duration-300">
                    <div class="relative h-64 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1600334129128-685c5582fd35?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1470&q=80"
                            alt="Hair Product" class="w-full h-full object-cover">
                    </div>
                    <div class="p-6">
                        <!-- Contoh pada Produk -->
                        <h3 class="text-lg sm:text-xl md:text-2xl font-bold mb-2">Shampoo & Conditioner</h3>
                        <p class="text-sm sm:text-base md:text-lg text-gray-600 mb-4">
                            Membersihkan dan melembabkan rambut dengan formula khusus.
                        </p>

                    </div>
                </div>

                <!-- Product 2 -->
                <div class="bg-white  overflow-hidden shadow-md hover:shadow-xl transition-all duration-300">
                    <div class="relative h-64 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1631729371254-42c2892f0e6e?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1470&q=80"
                            alt="Hair Styling" class="w-full h-full object-cover">
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-2">Beard Oil</h3>
                        <p class="text-gray-600 mb-4">Minyak jenggot dengan bahan alami untuk jenggot sehat dan
                            lembut.</p>

                    </div>
                </div>

                <!-- Product 3 -->
                <div class="bg-white  overflow-hidden shadow-md hover:shadow-xl transition-all duration-300">
                    <div class="relative h-64 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1631729371254-42c2892f0e6e?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1470&q=80"
                            alt="Hair Styling" class="w-full h-full object-cover">
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-2">Hair Styling Wax</h3>
                        <p class="text-gray-600 mb-4">Wax styling dengan hold kuat namun mudah dibentuk.</p>

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
                <div class="bg-white p-8  shadow-md">

                    <p class="text-gray-600 italic mb-6">
                        "Pengalaman cukur terbaik yang pernah saya dapatkan. Barber sangat profesional dan hasilnya
                        sempurna!"
                    </p>
                    <div class="font-bold">Andi Wijaya</div>
                    <div class="text-gray-500 text-sm">Pelanggan sejak 2018</div>
                </div>

                <!-- Testimonial 2 -->
                <div class="bg-white p-8  shadow-md">

                    <p class="text-gray-600 italic mb-6">
                        "Produk perawatan jenggot mereka sangat bagus. Jenggot saya jadi lebih sehat dan mudah
                        diatur."
                    </p>
                    <div class="font-bold">Budi Santoso</div>
                    <div class="text-gray-500 text-sm">Pelanggan sejak 2020</div>
                </div>

                <!-- Testimonial 3 -->
                <div class="bg-white p-8  shadow-md">

                    <p class="text-gray-600 italic mb-6">
                        "Atmosfernya sangat nyaman dan stafnya ramah. Potongan rambutnya selalu sesuai permintaan."
                    </p>
                    <div class="font-bold">Rudi Hartono</div>
                    <div class="text-gray-500 text-sm">Pelanggan sejak 2015</div>
                </div>
            </div>
        </div>
    </section>


    <section id="reservasi" class="pt-8 pb-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-playfair font-bold mb-4">Reservasi</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Buat janji untuk pengalaman grooming terbaik di Wox's Barbershop
                </p>
            </div>

            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Left Column - Services -->
                <div class="w-full lg:w-1/2">
                    <div class=" shadow-md">
                        <!-- Dewasa -->
                        <div class=" p-6 ">
                            <div class="flex items-center gap-6">
                                <!-- Gambar Layanan -->
                                <div class="flex-shrink-0 w-24 h-24   ">
                                    <img src="{{ asset('images/dewasa.png') }}" alt="Dewasa"
                                        class="w-full h-full object-cover">
                                </div>
                                <!-- Teks Layanan -->
                                <div>
                                    <h4 class="font-bold text-xl font-playfair">DEWASA</h4>
                                    <p class="text-gray-600 mt-2">Nulla egestas sapien integer mi fermentum telius
                                        tristique consequatoim pulvinar sagittis Lorem ipsum dolor sit amet consectetur
                                        adipisicing elit. Esse optio fugit modi labore sit numquam ut incidunt
                                        reiciendis minima ex, rerum eligendi dignissimos mollitia maxime magni atque,
                                        nam nisi enim?</p>
                                    <span class="font-bold text-lg font-playfair ">RP 24.000</span>
                                </div>
                            </div>
                        </div>

                        <!-- Anak-anak -->
                        <div class=" p-6 ">
                            <div class="flex items-center gap-6">
                                <!-- Gambar Layanan -->
                                <div class="flex-shrink-0 w-24 h-24   ">
                                    <img src="{{ asset('images/anak.png') }}" alt="Anak Anak"
                                        class="w-full h-full object-cover">
                                </div>
                                <!-- Teks Layanan -->
                                <div>
                                    <h4 class="font-bold text-xl font-playfair uppercase">Anak Anak</h4>
                                    <p class="text-gray-600 mt-2">Nulla egestas sapien integer mi fermentum telius
                                        tristique consequatoim pulvinar sagittis Lorem ipsum dolor sit amet consectetur
                                        adipisicing elit. Quo, quibusdam perspiciatis laboriosam, officiis earum illo,
                                        animi quasi ex consectetur vitae magnam aperiam quidem accusamus! Accusamus
                                        tempora odio fugit corporis corrupti.</p>
                                    <span class="font-bold text-lg font-playfair">RP 30.000</span>
                                </div>
                            </div>
                        </div>

                        <!-- Cukur Leher & Jenggot -->
                        <div class=" p-6 ">
                            <div class="flex items-center gap-6">
                                <!-- Gambar Layanan -->
                                <div class="flex-shrink-0 w-24 h-24">
                                    <img src="{{ asset('images/kumis.png') }}" alt="Kumis"
                                        class="w-full h-full object-cover">
                                </div>
                                <!-- Teks Layanan -->
                                <div>
                                    <h4 class="font-bold text-xl font-playfair uppercase">Cukur Jenggot dan Kumis</h4>
                                    <p class="text-gray-600 mt-2">Nulla egestas sapien integer mi fermentum telius
                                        tristique consequatoim pulvinar sagittis Lorem ipsum dolor, sit amet consectetur
                                        adipisicing elit. Libero dicta cum eos similique velit voluptate eveniet facilis
                                        quae quod excepturi, explicabo non ad nulla maiores consectetur nisi error odit?
                                        Quae.</p>
                                    <span class="font-bold text-lg font-playfair">RP 25.000</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Booking Form -->
                <div class="w-full lg:w-1/2 bg-white shadow-xl p-8">
                    <form action="{{ route('bookings.store') }}" method="POST" id="booking-form" class="space-y-6">
                        @csrf

                        <!-- Nama -->
                        <div>
                            <label for="name"
                                class="block text-primary mb-2 font-bold font-playfair text-xl">NAMA</label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}"
                                class="w-full px-4 py-3 border-b-4 border-primary focus:outline-none focus:border-secondary bg-transparent">
                            @error('name')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Layanan -->
                        <div>
                            <label for="service_id"
                                class="block text-primary mb-2 font-bold font-playfair text-xl">LAYANAN</label>
                            <select id="service_id" name="service_id"
                                class="w-full px-4 py-3 border-b-4 border-primary focus:outline-none focus:border-secondary bg-transparent">
                                <option value="">Pilih Layanan</option>
                                @foreach ($services as $service)
                                    <option value="{{ $service->id }}"
                                        {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                        {{ $service->name }} - Rp {{ number_format($service->price, 0, ',', '.') }}
                                    </option>
                                @endforeach
                            </select>
                            @error('service_id')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Gaya Rambut & Tanggal -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="hairstyle_id"
                                    class="block text-primary mb-2 font-bold font-playfair text-xl">GAYA RAMBUT</label>
                                <select id="hairstyle_id" name="hairstyle_id"
                                    class="w-full px-4 py-3 border-b-4 border-primary focus:outline-none focus:border-secondary bg-transparent">
                                    <option value="">Pilih Gaya</option>
                                    @foreach ($hairstyles as $hairstyle)
                                        <option value="{{ $hairstyle->id }}"
                                            {{ old('hairstyle_id') == $hairstyle->id ? 'selected' : '' }}>
                                            {{ $hairstyle->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('hairstyle_id')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="date_time"
                                    class="block text-primary mb-2 font-bold font-playfair text-xl">TANGGAL & WAKTU</label>
                                <input type="datetime-local" id="date_time" name="date_time"
                                    value="{{ old('date_time') }}"
                                    class="w-full px-4 py-3 border-b-4 border-primary focus:outline-none focus:border-secondary bg-transparent">
                                @error('date_time')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>

                        <!-- Deskripsi -->
                        <div>
                            <label for="description"
                                class="block text-primary mb-2 font-bold font-playfair text-xl">DESKRIPSI</label>
                            <textarea id="description" name="description" rows="3"
                                class="w-full px-4 py-3 border-b-4 border-primary focus:outline-none focus:border-secondary bg-transparent">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <button type="submit"
                            class="w-full bg-secondary hover:bg-primary text-white px-6 py-4 font-bold text-lg mt-6 transition transform hover:scale-105 shadow-md">
                            RESERVASI
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </section>


    <!-- Gallery Section -->
    <section class="py-12 bg-white ">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl md:text-4xl font-playfair font-bold mb-12 text-center">Galeri Kami</h2>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                <a href="#"
                    class="gallery-item overflow-hidden  shadow-md hover:shadow-xl transition-all duration-300">
                    <img src="https://images.unsplash.com/photo-1595476108010-b4d1f102b1b1?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=688&q=80"
                        alt="Barber at work"
                        class="w-full h-48 object-cover transform hover:scale-110 transition-all duration-500">
                </a>
                <a href="#"
                    class="gallery-item overflow-hidden  shadow-md hover:shadow-xl transition-all duration-300">
                    <img src="https://images.unsplash.com/photo-1519699047748-de8e457a634e?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=880&q=80"
                        alt="Haircut"
                        class="w-full h-48 object-cover transform hover:scale-110 transition-all duration-500">
                </a>
                <a href="#"
                    class="gallery-item overflow-hidden  shadow-md hover:shadow-xl transition-all duration-300">
                    <img src="https://images.unsplash.com/photo-1567894340315-735d7c361db0?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=687&q=80"
                        alt="Beard trim"
                        class="w-full h-48 object-cover transform hover:scale-110 transition-all duration-500">
                </a>
                <a href="#"
                    class="gallery-item overflow-hidden  shadow-md hover:shadow-xl transition-all duration-300">
                    <img src="https://images.unsplash.com/photo-1585747860715-2ba37e788b70?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1474&q=80"
                        alt="Barbershop interior"
                        class="w-full h-48 object-cover transform hover:scale-110 transition-all duration-500">
                </a>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 2500
            });
        @elseif (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '{{ session('error') }}',
                showConfirmButton: false,
                timer: 3000
            });
        @endif
    </script>
@endpush
