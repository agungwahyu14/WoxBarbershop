@extends('layouts.app')

@section('content')
    <!-- Success Alert for Registration -->
    @if (session('success'))
        <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700">
                        {{ session('success') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- Email Verification Alert (hanya muncul jika user belum verified dan bukan dari registrasi) -->
    @auth
        @if (!auth()->user()->hasVerifiedEmail() && !session('success'))
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">
                            {{ __('auth.verify_email_notification') }}
                        <form method="POST" action="{{ route('verification.send') }}" class="inline">
                            @csrf
                            <button type="submit" class="font-medium text-yellow-700 underline hover:text-yellow-600">
                                {{ __('auth.resend_verification') }}
                            </button>
                        </form>
                        </p>
                    </div>
                </div>
            </div>
        @endif
    @endauth

    <section id="beranda" class="h-screen flex items-center justify-center text-center text-white parallax relative"
        style="background-image: url('{{ asset('images/hero2.jpeg') }}');">
        <div class="absolute inset-0 bg-black opacity-60"></div>

        <div class="container mx-auto px-4 animate-fade-in relative z-10">
            <!-- Contoh pada Hero Section -->
            <h1 class="font-playfair font-bold text-3xl sm:text-4xl md:text-6xl lg:text-7xl mb-6 leading-tight">
                {{ __('general.welcome_message') }} <br> {{ __('general.tagline') }}
            </h1>
            <p class="text-base sm:text-lg md:text-xl lg:text-2xl mb-8 max-w-2xl mx-auto opacity-90">
                {{ __('general.welcome_message_detail') }} <br> {{ __('general.tagline_detail') }}.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="{{ route('dashboard') }}#reservasi"
                    class="rounded-lg bg-secondary hover:bg-white text-primary hover:text-primary px-8 py-3  font-medium transition-all duration-300 transform hover:-translate-y-1 shadow-lg ">
                    {{ __('welcome.reservation_button') }}
                </a>
                <a href="{{ route('dashboard') }}#layanan"
                    class="rounded-lg border-2 border-white hover:border-secondary text-white hover:text-secondary px-8 py-3  font-medium transition-all duration-300 transform hover:-translate-y-1 ">
                    {{ __('welcome.view_services') }}
                </a>
            </div>
        </div>

        <div class="absolute bottom-10 left-1/2 transform -translate-x-1/2 animate-bounce z-10">
            <a href="#layanan" class="text-white text-2xl">
                <i class="fas fa-chevron-down"></i>
            </a>
        </div>
    </section>

    <!-- Features Section -->


    <!-- Services Section -->
    <section id="layanan" class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16 animate-slide-up mt-8">
                <!-- Contoh pada Section Judul -->
                <h2 class="text-3xl md:text-4xl font-playfair font-bold mb-4">
                    {{ __('welcome.our_services') }}
                </h2>
                <p class="text-base sm:text-lg md:text-xl text-gray-600 max-w-2xl mx-auto">
                    {{ __('welcome.services_description') }}
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Service 1 -->
                <div
                    class="feature-card bg-white p-8  shadow-md hover:shadow-xl transition-all duration-500 transform hover:-translate-y-2 rounded-lg ">
                    <div
                        class="feature-number text-6xl font-playfair font-bold text-secondary opacity-20 mb-6 transition-all duration-300">
                        01</div>
                    <h3 class="text-xl font-bold mb-4">{{ __('welcome.haircut') }}</h3>
                    <p class="text-gray-600 mb-4">
                        {{ __('welcome.haircut_description') }}
                    </p>
                    {{-- <a href="{{ route('dashboard') }}#reservasi"
                        class="text-secondary font-medium flex items-center group">
                        {{ __('welcome.book_now') }}
                        <i class="fas fa-arrow-right ml-2 group-hover:ml-3 transition-all duration-300"></i>
                    </a> --}}
                </div>

                <!-- Service 2 -->
                <div
                    class="feature-card bg-white p-8  shadow-md hover:shadow-xl transition-all duration-500 transform hover:-translate-y-2 rounded-lg">
                    <div
                        class="feature-number text-6xl font-playfair font-bold text-secondary opacity-20 mb-6 transition-all duration-300">
                        02</div>
                    <h3 class="text-xl font-bold mb-4">{{ __('welcome.our_products') }}</h3>
                    <p class="text-gray-600 mb-4">
                        {{ __('welcome.products_description') }}
                    </p>
                    {{-- <a href="{{ route('dashboard') }}#produk" class="text-secondary font-medium flex items-center group">
                        {{ __('welcome.book_now') }}
                        <i class="fas fa-arrow-right ml-2 group-hover:ml-3 transition-all duration-300"></i>
                    </a> --}}
                </div>

                <!-- Service 3 -->
                <div
                    class="feature-card bg-white p-8  shadow-md hover:shadow-xl transition-all duration-500 transform hover:-translate-y-2 rounded-lg">
                    <div
                        class="feature-number text-6xl font-playfair font-bold text-secondary opacity-20 mb-6 transition-all duration-300">
                        03</div>
                    <h3 class="text-xl font-bold mb-4">{{ __('welcome.style_recommendations') }}</h3>
                    <p class="text-gray-600 mb-4">
                        {{ __('welcome.recommendations_description') }}
                    </p>
                    {{-- <a href="{{ route('rekomendasi.index') }}" class="text-secondary font-medium flex items-center group">
                        {{ __('welcome.book_now') }}
                        <i class="fas fa-arrow-right ml-2 group-hover:ml-3 transition-all duration-300"></i>
                    </a> --}}
                </div>

                <!-- Service 4 -->
                <div
                    class="feature-card bg-white p-8  shadow-md hover:shadow-xl transition-all duration-500 transform hover:-translate-y-2 rounded-lg">
                    <div
                        class="feature-number text-6xl font-playfair font-bold text-secondary opacity-20 mb-6 transition-all duration-300">
                        04</div>
                    <h3 class="text-xl font-bold mb-4">{{ __('welcome.loyalty') }}</h3>
                    <p class="text-gray-600 mb-4">
                        {{ __('welcome.loyalty_description') }}
                    </p>
                    {{-- <a href="{{ route('dashboard') }}#reservasi"
                        class="text-secondary font-medium flex items-center group">
                        {{ __('welcome.book_now') }}
                        <i class="fas fa-arrow-right ml-2 group-hover:ml-3 transition-all duration-300"></i>
                    </a> --}}
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <!-- About Section -->
    <section id="tentang" class="py-20 bg-light">
        <div class="container mx-auto px-4">
            <div class="flex flex-col lg:flex-row items-center gap-12">
                <!-- Left Column - Text Content -->
                <div class="lg:w-1/2 animate-slide-up">
                    <h2 class="text-3xl md:text-4xl font-playfair font-bold mb-6">{{ __('welcome.about_wox') }}</h2>
                    <p class="text-gray-600 mb-6 text-justify">
                        {{ __('welcome.about_paragraph_1') }}
                    </p>
                    <p class="text-gray-600 mb-8 text-justify">
                        {{ __('welcome.about_paragraph_2') }}
                    </p>
                    <a href="{{ route('dashboard') }}#reservasi"
                        class="rounded-lg bg-secondary hover:bg-primary text-primary hover:text-white px-8 py-3 font-medium transition-all duration-300 inline-block transform hover:-translate-y-1 shadow-lg">
                        {{ __('welcome.reservation_button') }}
                    </a>
                </div>

                <!-- Right Column - Image -->
                <div class="lg:w-1/2 relative animate-fade-in">
                    <img src="https://images.unsplash.com/photo-1585747860715-2ba37e788b70?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1474&q=80"
                        alt="Barbershop interior" class="shadow-xl w-full h-auto rounded-lg">
                </div>
            </div>
        </div>
    </section>
    <!-- Menu Section -->
    <section id="produk" class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16 mt-8">
                <h2 class="text-3xl md:text-4xl font-playfair font-bold mb-4">{{ __('welcome.our_products') }}</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto mb-8">
                    {{ __('welcome.premium_products_description') }}
                </p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($products as $product)
                    <div
                        class="bg-white overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 rounded-lg flex flex-col h-full">
                        <div class="relative overflow-hidden bg-gray-50">
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                                class="w-full h-80 object-cover hover:scale-105 transition-transform duration-300">
                        </div>
                        <div class="p-6 flex-1 flex flex-col">
                            <h3 class="text-lg sm:text-xl md:text-2xl font-bold mb-3">{{ $product->name }}</h3>
                            <p class="text-sm sm:text-base text-gray-600 mb-4 flex-1 line-clamp-4">
                                {{ $product->description }}
                            </p>
                            @if ($product->price)
                                <div class="mt-auto">
                                    <span class="font-bold text-lg font-playfair text-secondary">Rp
                                        {{ number_format($product->price, 0, ',', '.') }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <!-- Fallback jika tidak ada produk -->
                    <div class="col-span-3 text-center py-8">
                        <p class="text-gray-600">{{ __('welcome.no_products_available') }}</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>


    <!-- Testimonials Section -->
    <section class="py-20 bg-light">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16 mt-8">
                <h2 class="text-3xl md:text-4xl font-playfair font-bold mb-4">{{ __('welcome.customer_testimonials') }}
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    {{ __('welcome.testimonials_description') }}
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @forelse($testimonials as $testimonial)
                    <div class="bg-white p-8 shadow-md rounded-lg">
                        <div class="flex items-center mb-4">
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= $testimonial->rating)
                                    <i class="fas fa-star text-yellow-400"></i>
                                @else
                                    <i class="fas fa-star text-gray-300"></i>
                                @endif
                            @endfor
                            <span class="ml-2 text-sm text-gray-600">({{ $testimonial->rating }}/5)</span>
                        </div>
                        <p class="text-gray-600 italic mb-6">
                            "{{ $testimonial->comment }}"
                        </p>
                        <div class="font-bold">{{ $testimonial->user->name }}</div>
                        <div class="text-gray-500 text-sm">{{ $testimonial->created_at->format('d M Y') }}</div>
                    </div>
                @empty
                    <!-- Fallback jika tidak ada testimonial -->
                    <div class="col-span-3 text-center py-8">
                        <p class="text-gray-600">{{ __('welcome.no_testimonials_available') }}</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>



    <section id="reservasi" class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16 animate-slide-up">
                <h2 class="text-3xl md:text-4xl font-playfair font-bold mb-4">{{ __('welcome.reservation') }}</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    {{ __('welcome.reservation_description') }}
                </p>
            </div>

            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Left Column - Services -->
                <div class="w-full lg:w-1/2">
                    @forelse($services->take(4) as $service)
                        <div class="p-6">
                            <div class="flex items-center gap-6">
                                <!-- Gambar Layanan -->
                                <div class="flex-shrink-0 w-24 h-24">
                                    @if ($service->image)
                                        <img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->name }}"
                                            class="w-full h-full object-cover rounded-lg">
                                    @else
                                        <!-- Default images based on service name -->
                                        @if (str_contains(strtolower($service->name), 'dewasa') || str_contains(strtolower($service->name), 'adult'))
                                            <img src="{{ asset('images/dewasa.png') }}" alt="{{ $service->name }}"
                                                class="w-full h-full object-cover rounded-lg">
                                        @elseif(str_contains(strtolower($service->name), 'anak') || str_contains(strtolower($service->name), 'kid'))
                                            <img src="{{ asset('images/anak.png') }}" alt="{{ $service->name }}"
                                                class="w-full h-full object-cover rounded-lg">
                                        @elseif(str_contains(strtolower($service->name), 'jenggot') ||
                                                str_contains(strtolower($service->name), 'kumis') ||
                                                str_contains(strtolower($service->name), 'beard'))
                                            <img src="{{ asset('images/kumis.png') }}" alt="{{ $service->name }}"
                                                class="w-full h-full object-cover rounded-lg">
                                        @else
                                            <img src="{{ asset('images/dewasa.png') }}" alt="{{ $service->name }}"
                                                class="w-full h-full object-cover rounded-lg">
                                        @endif
                                    @endif
                                </div>
                                <!-- Teks Layanan -->
                                <div>
                                    <h4 class="font-bold text-xl font-playfair">{{ $service->name }}</h4>
                                    <p class="text-gray-600 mt-2">{{ $service->description }}</p>
                                    <span class="font-bold text-lg font-playfair text-secondary">
                                        Rp {{ number_format($service->price, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <!-- Fallback jika tidak ada layanan -->
                        <div class="p-6 text-center">
                            <p class="text-gray-600">{{ __('welcome.no_services_available') }}</p>
                        </div>
                    @endforelse
                </div>
                <!-- Right Column - Booking Form -->
                <div class="w-full lg:w-1/2 bg-white shadow-xl p-6 rounded-lg">
                    <form action="{{ route('bookings.store') }}" method="POST" id="booking-form" class="space-y-6">
                        @csrf

                        <!-- Nama -->
                        <div>
                            <label for="name"
                                class="block text-primary mb-2 font-bold font-playfair text-xl">{{ __('welcome.name') }}</label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}"
                                class="w-full px-4 py-3 border-primary focus:outline-none focus:border-secondary bg-transparent rounded-lg">
                            @error('name')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Layanan & Gaya Rambut -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="service_id"
                                    class="block text-primary mb-2 font-bold font-playfair text-xl">{{ __('welcome.service') }}</label>
                                <select id="service_id" name="service_id"
                                    class="w-full px-4 py-3 border-primary focus:outline-none focus:border-secondary bg-transparent rounded-lg">
                                    <option value="">{{ __('welcome.choose_service') }}</option>
                                    @foreach ($services as $service)
                                        <option value="{{ $service->id }}" data-duration="{{ $service->duration }}"
                                            {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                            {{ $service->name }} - Rp {{ number_format($service->price, 0, ',', '.') }}
                                        </option>
                                    @endforeach
                                </select>

                                {{-- Tempat menampilkan durasi --}}
                                <p id="service-duration" class="text-gray-600 text-sm mt-2"></p>

                                @error('service_id')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="hairstyle_id"
                                    class="block text-primary mb-2 font-bold font-playfair text-xl">{{ __('welcome.choose_hairstyle') }}</label>
                                <select id="hairstyle_id" name="hairstyle_id"
                                    class="w-full px-4 py-3 border-primary focus:outline-none focus:border-secondary bg-transparent rounded-lg">
                                    <option value="">{{ __('welcome.choose_style') }}</option>
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
                        </div>

                        {{-- Duration will be handled by the enhanced script in @push section --}}





                        <!-- Pembayaran & Tanggal -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="payment_method"
                                    class="block text-primary mb-2 font-bold font-playfair text-xl">{{ __('welcome.payment') }}</label>
                                <select id="payment_method" name="payment_method"
                                    class="w-full px-4 py-3 border-primary focus:outline-none focus:border-secondary bg-transparent rounded-lg">
                                    <option value="">{{ __('welcome.choose_payment_method') }}</option>
                                    <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>
                                        {{ __('welcome.cash') }}
                                    </option>
                                    <option value="bank" {{ old('payment_method') == 'bank' ? 'selected' : '' }}>
                                        {{ __('welcome.bank') }}
                                    </option>
                                </select>
                                @error('payment_method')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="date_time"
                                    class="block text-primary mb-2 font-bold font-playfair text-xl">{{ __('welcome.date_time') }}</label>
                                <input type="datetime-local" id="date_time" name="date_time"
                                    value="{{ old('date_time') }}"
                                    class="w-full px-4 py-3 border-primary focus:outline-none focus:border-secondary bg-transparent rounded-lg">
                                @error('date_time')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Deskripsi -->
                        <div>
                            <label for="description"
                                class="block text-primary mb-2 font-bold font-playfair text-xl">{{ __('welcome.description') }}</label>
                            <textarea id="description" name="description" rows="3"
                                class="w-full px-4 py-3 border-primary focus:outline-none focus:border-secondary bg-transparent rounded-lg">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        @auth
                            <button type="submit" id="submit-booking"
                                class="rounded-lg w-full bg-secondary hover:bg-primary text-black hover:text-white px-6 py-4 font-bold text-lg mt-6 transition-all duration-300 inline-block transform hover:-translate-y-1">
                                <span id="submit-text">{{ __('welcome.reservation_submit') }}</span>
                                <span id="loading-text" class="hidden">
                                    <i class="fas fa-spinner fa-spin mr-2"></i>{{ __('welcome.processing') }}...
                                </span>
                            </button>
                        @else
                            <a href="{{ route('login') }}"
                                class="text-center w-full bg-secondary hover:bg-primary text-white px-6 py-4 font-bold text-lg mt-6 transition-all duration-300 inline-block transform hover:-translate-y-1 rounded-lg">
                                {{ __('welcome.login_to_reserve') }}
                            </a>
                        @endauth
                    </form>
                </div>
            </div>
        </div>
        </div>
    </section>

    <!-- Gallery Section -->
    <section class="py-12 bg-white ">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl md:text-4xl font-playfair font-bold mb-12 text-center">{{ __('welcome.our_gallery') }}
            </h2>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                <a href="#"
                    class="gallery-item overflow-hidden rounded-xl shadow-md hover:shadow-xl transition-all duration-300">
                    <img src="{{ asset('images/galeri1.jpeg') }}" alt="Wox's Barbershop Gallery 1"
                        class="w-full h-60 object-cover transform hover:scale-110 transition-all duration-500 rounded-xl ">
                </a>
                <a href="#"
                    class="gallery-item overflow-hidden rounded-xl shadow-md hover:shadow-xl transition-all duration-300">
                    <img src="{{ asset('images/galeri2.jpeg') }}" alt="Wox's Barbershop Gallery 2"
                        class="w-full h-60 object-cover transform hover:scale-110 transition-all duration-500 rounded-xl">
                </a>
                <a href="#"
                    class="gallery-item overflow-hidden rounded-xl shadow-md hover:shadow-xl transition-all duration-300">
                    <img src="{{ asset('images/hero.jpeg') }}" alt="Wox's Barbershop Gallery 3"
                        class="w-full h-60 object-cover transform hover:scale-110 transition-all duration-500 rounded-xl">
                </a>
                <a href="#"
                    class="gallery-item overflow-hidden rounded-xl shadow-md hover:shadow-xl transition-all duration-300">
                    <img src="{{ asset('images/galeri4.jpeg') }}" alt="Wox's Barbershop Gallery 4"
                        class="w-full h-60 object-cover transform hover:scale-110 transition-all duration-500 rounded-xl">
                </a>
            </div>
        </div>
    </section>
@endsection


@push('scripts')
    <!-- jQuery untuk AJAX -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- SweetAlert2 untuk notifikasi -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Pass translations to JavaScript -->
    <script>
        window.bookingTranslations = @json(__('booking'));
    </script>

    <!-- Multi-Language Booking Form AJAX Handler -->
    <script src="{{ asset('js/booking-form-ajax-multilang.js') }}"></script>

    <!-- Duration display functionality -->
    <script>
        // Simple duration display - page refreshes on language change
        document.addEventListener('DOMContentLoaded', function() {
            const serviceSelect = document.getElementById('service_id');
            const durationDisplay = document.getElementById('service-duration');

            if (serviceSelect && durationDisplay) {
                serviceSelect.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    const duration = selectedOption.getAttribute('data-duration');

                    if (duration && duration.trim() !== '') {
                        // Use server-side translation since page refreshes on language change
                        const durationNumber = duration.replace(/[^0-9]/g, '');
                        const formattedDuration = @json(__('welcome.duration_minutes', ['duration' => '__DURATION__'])).replace('__DURATION__',
                            durationNumber);

                        durationDisplay.textContent = formattedDuration;
                        durationDisplay.style.display = 'block';
                    } else {
                        durationDisplay.textContent = '';
                        durationDisplay.style.display = 'none';
                    }
                });

                // Update on page load if there's a selected value
                if (serviceSelect.value) {
                    const event = new Event('change');
                    serviceSelect.dispatchEvent(event);
                }
            }
        });
    </script>


    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: '{{ __('auth.success') }}',
                text: '{{ session('success') }}',
                confirmButtonColor: '#10B981',
                timer: 3000,
                timerProgressBar: true
            });
        </script>
    @endif
@endpush
