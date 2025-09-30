@extends('layouts.app')

@section('content')
    <section id="produk" class="py-20 bg-white">
        <div class="container mx-auto px-4">
            {{-- Judul --}}
            <div class="text-center mb-16 mt-8">
                <h2 class="text-3xl md:text-4xl font-playfair font-bold mb-4">
                    Rekomendasi Gaya Rambut
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto mb-8">
                    Pilih bentuk wajah dan jenis rambut Anda untuk mendapatkan gaya terbaik yang direkomendasikan.
                </p>
            </div>

            {{-- Form Filter --}}
            <form method="GET" action="{{ route('rekomendasi.index') }}" class="mb-12">
                <div class="flex flex-col lg:flex-row lg:items-end gap-4">
                    {{-- Bentuk Wajah --}}
                    <div class="lg:w-1/4 w-full">
                        <label class="block mb-2 text-sm font-medium text-gray-700">Bentuk Wajah</label>
                        <select name="bentuk_kepala" class="w-full border px-4 py-3 rounded-md">
                            <option value="">Pilih bentuk wajah</option>
                            @foreach (['oval', 'bulat', 'persegi panjang', 'hati', 'kotak', 'segitiga'] as $shape)
                                <option value="{{ $shape }}"
                                    {{ request('bentuk_kepala') == $shape ? 'selected' : '' }}>
                                    {{ ucfirst($shape) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Tipe Rambut --}}
                    <div class="lg:w-1/4 w-full">
                        <label class="block mb-2 text-sm font-medium text-gray-700">Jenis Rambut</label>
                        <select name="tipe_rambut" class="w-full border px-4 py-3 rounded-md">
                            <option value="">Pilih jenis rambut</option>
                            @foreach (['lurus', 'bergelombang', 'keriting'] as $hair)
                                <option value="{{ $hair }}" {{ request('tipe_rambut') == $hair ? 'selected' : '' }}>
                                    {{ ucfirst($hair) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Preferensi Gaya --}}
                    <div class="lg:w-1/4 w-full">
                        <label class="block mb-2 text-sm font-medium text-gray-700">Preferensi Gaya</label>
                        <select name="preferensi_gaya" class="w-full border px-4 py-3 rounded-md">
                            <option value="">Pilih preferensi gaya</option>
                            @foreach (['modern', 'klasik', 'kasual'] as $style)
                                <option value="{{ $style }}"
                                    {{ request('preferensi_gaya') == $style ? 'selected' : '' }}>
                                    {{ ucfirst($style) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Tombol Submit --}}
                    <div class="lg:w-1/4 w-full">
                        <button type="submit"
                            class="mt-6 bg-[#d4af37] text-white px-6 py-3 rounded-lg hover:bg-yellow-600 transition w-full">
                            Terapkan Filter
                        </button>
                    </div>
                </div>
            </form>



            {{-- Hasil Rekomendasi --}}
            @if (!empty($results) && count($results))
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                    @foreach ($results as $index => $style)
                        <div class="bg-white shadow-md rounded-lg overflow-hidden hover:shadow-xl transition-all">
                            <img src="{{ $style['hairstyle']->image ? asset('storage/' . $style['hairstyle']->image) : asset('img/placeholder.svg') }}"
                                alt="{{ $style['hairstyle']->name }}" class="w-full h-60 object-cover">

                            <div class="p-4">
                                <h3 class="text-xl font-semibold text-gray-800 mb-2">
                                    #{{ $index + 1 }} - {{ $style['hairstyle']->name }}
                                </h3>
                                <p class="text-sm text-gray-600 truncate">
                                    {{ $style['hairstyle']->description }}
                                </p>
                                {{-- <div class="mt-2 text-sm text-yellow-700 font-semibold">
                                    Skor Rekomendasi: {{ number_format($style['score'], 2) }}
                                </div> --}}
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-center text-gray-500 mt-16">
                    Tidak ada gaya rambut yang cocok dengan filter yang dipilih.
                </p>
            @endif
        </div>
    </section>
@endsection
