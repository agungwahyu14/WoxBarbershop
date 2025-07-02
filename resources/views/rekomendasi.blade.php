@extends('layouts.app')

@section('content')
    <section id="produk" class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-playfair font-bold mb-4">Rekomendasi Gaya Rambut</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto mb-8">
                    Pilih bentuk wajah Anda untuk mendapatkan rekomendasi gaya terpersonalisasi
                </p>
            </div>

            <div class="mb-8 w-full ">
                <div class="flex flex-wrap items-end gap-6 w-full">
                    <!-- Bentuk Wajah Dropdown -->
                    <div class="flex-1 min-w-[200px]">
                        <label for="face_shape" class="block text-primary font-bold mb-2">Bentuk Wajah</label>
                        <div class="relative">
                            <select id="face_shape" name="face_shape"
                                class="w-full px-4 py-3 border-b-4 border-primary focus:outline-none focus:border-secondary bg-transparent appearance-none">
                                <option value="" disabled selected>Pilih bentuk wajah</option>
                                <option value="oval">Oval</option>
                                <option value="round">Bulat</option>
                                <option value="square">Persegi</option>
                                <option value="heart">Heart</option>
                                <option value="diamond">Diamond</option>
                            </select>
                            <div class="absolute right-3 top-3.5 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Jenis Rambut Dropdown -->
                    <div class="flex-1 min-w-[200px]">
                        <label for="hair_type" class="block text-primary font-bold mb-2">Jenis Rambut</label>
                        <div class="relative">
                            <select id="hair_type" name="hair_type"
                                class="w-full px-4 py-3 border-b-4 border-primary focus:outline-none focus:border-secondary bg-transparent appearance-none">
                                <option value="" disabled selected>Pilih jenis rambut</option>
                                <option value="straight">Lurus</option>
                                <option value="wavy">Bergelombang</option>
                                <option value="curly">Keriting</option>
                                <option value="coily">Sangat Keriting</option>
                            </select>
                            <div class="absolute right-3 top-3.5 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Preferensi Gaya Dropdown -->
                    <div class="flex-1 min-w-[200px]">
                        <label for="style_preference" class="block text-primary font-bold mb-2">Preferensi Gaya</label>
                        <div class="relative">
                            <select id="style_preference" name="style_preference"
                                class="w-full px-4 py-3 border-b-4 border-primary focus:outline-none focus:border-secondary bg-transparent appearance-none">
                                <option value="" disabled selected>Pilih preferensi gaya</option>
                                <option value="modern">Modern</option>
                                <option value="classic">Klasik</option>
                                <option value="vintage">Vintage</option>
                                <option value="edgy">Edgy</option>
                                <option value="professional">Professional</option>
                            </select>
                            <div class="absolute right-3 top-3.5 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="flex-1 min-w-[300px] flex gap-3 justify-end">
                        <button type="button"
                            class="bg-[#d4af37] text-white px-6 py-3 rounded-lg hover:bg-[#d4af37] transition-colors">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                Terapkan
                            </span>
                        </button>
                        <button type="button" class="bg-gray-200 px-6 py-3 rounded-lg hover:bg-gray-300 transition-colors">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                Cari
                            </span>
                        </button>
                        <button type="button"
                            class="bg-red-500 text-white px-6 py-3 rounded-lg hover:bg-red-600 transition-colors">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Reset
                            </span>
                        </button>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                <!-- Product 1 -->
                <div class="bg-white  overflow-hidden shadow-md hover:shadow-xl transition-all duration-300">
                    <div class="relative h-64 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1600334129128-685c5582fd35?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1470&q=80"
                            alt="Hair Product" class="w-full h-full object-cover">
                    </div>

                </div>

                <!-- Product 2 -->
                <div class="bg-white  overflow-hidden shadow-md hover:shadow-xl transition-all duration-300">
                    <div class="relative h-64 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1631729371254-42c2892f0e6e?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1470&q=80"
                            alt="Hair Styling" class="w-full h-full object-cover">
                    </div>

                </div>

                <!-- Product 3 -->
                <div class="bg-white  overflow-hidden shadow-md hover:shadow-xl transition-all duration-300">
                    <div class="relative h-64 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1631729371254-42c2892f0e6e?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1470&q=80"
                            alt="Hair Styling" class="w-full h-full object-cover">
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection
