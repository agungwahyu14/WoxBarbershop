@extends('layouts.app')

@section('content')
    <section id="produk" class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-playfair font-bold mb-4">Rekomendasi Gaya Rambut</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto mb-8">
                    Pilih bentuk wajah dan jenis rambut Anda untuk mendapatkan gaya terbaik yang direkomendasikan.
                </p>
            </div>

            <form method="GET" action="{{ route('rekomendasi.index') }}" class="mb-12">
                <div class="flex flex-col lg:flex-row lg:items-end gap-4">
                    {{-- Bentuk Wajah --}}
                    <div class="lg:w-1/4 w-full">
                        <label class="block mb-2 text-sm font-medium text-gray-700">Bentuk Wajah</label>
                        <select name="bentuk_kepala" class="w-full border px-4 py-3 rounded-md">
                            <option value="">Pilih bentuk wajah</option>
                            <option value="oval" {{ request('bentuk_kepala') == 'oval' ? 'selected' : '' }}>Oval</option>
                            <option value="round" {{ request('bentuk_kepala') == 'round' ? 'selected' : '' }}>Bulat
                            </option>
                            <option value="square" {{ request('bentuk_kepala') == 'square' ? 'selected' : '' }}>Persegi
                            </option>
                            <option value="heart" {{ request('bentuk_kepala') == 'heart' ? 'selected' : '' }}>Heart
                            </option>
                            <option value="diamond" {{ request('bentuk_kepala') == 'diamond' ? 'selected' : '' }}>Berlian
                            </option>
                            <option value="oblong" {{ request('bentuk_kepala') == 'oblong' ? 'selected' : '' }}>Persegi
                                Panjang</option>
                            <option value="triangle" {{ request('bentuk_kepala') == 'triangle' ? 'selected' : '' }}>Segitiga
                            </option>
                        </select>

                    </div>

                    {{-- Tipe Rambut --}}
                    <div class="lg:w-1/4 w-full">
                        <label class="block mb-2 text-sm font-medium text-gray-700">Jenis Rambut</label>
                        <select name="tipe_rambut" class="w-full border px-4 py-3 rounded-md">
                            <option value="">Pilih jenis rambut</option>
                            <option value="straight" {{ request('tipe_rambut') == 'straight' ? 'selected' : '' }}>Lurus
                                (Straight)</option>
                            <option value="wavy" {{ request('tipe_rambut') == 'wavy' ? 'selected' : '' }}>Bergelombang
                                (Wavy)</option>
                            <option value="curly" {{ request('tipe_rambut') == 'curly' ? 'selected' : '' }}>Keriting
                                (Curly)</option>
                            <option value="coily" {{ request('tipe_rambut') == 'coily' ? 'selected' : '' }}>Sangat
                                Keriting / Spiral (Coily)</option>
                            <option value="fine" {{ request('tipe_rambut') == 'fine' ? 'selected' : '' }}>Rambut Tipis
                                (Fine)</option>
                            <option value="thick" {{ request('tipe_rambut') == 'thick' ? 'selected' : '' }}>Rambut Tebal
                                (Thick)</option>
                            <option value="dry" {{ request('tipe_rambut') == 'dry' ? 'selected' : '' }}>Rambut Kering
                            </option>
                            <option value="oily" {{ request('tipe_rambut') == 'oily' ? 'selected' : '' }}>Rambut
                                Berminyak</option>
                            <option value="normal" {{ request('tipe_rambut') == 'normal' ? 'selected' : '' }}>Rambut Normal
                            </option>
                            <option value="damaged" {{ request('tipe_rambut') == 'damaged' ? 'selected' : '' }}>Rambut
                                Rusak</option>
                        </select>
                    </div>

                    {{-- Preferensi Gaya --}}
                    <div class="lg:w-1/4 w-full">
                        <label class="block mb-2 text-sm font-medium text-gray-700">Preferensi Gaya</label>
                        <select name="preferensi_gaya" class="w-full border px-4 py-3 rounded-md">
                            <option value="">Pilih preferensi gaya</option>
                            <option value="modern" {{ request('preferensi_gaya') == 'modern' ? 'selected' : '' }}>Modern
                            </option>
                            <option value="klasik" {{ request('preferensi_gaya') == 'klasik' ? 'selected' : '' }}>Klasik
                            </option>
                            <option value="sporty" {{ request('preferensi_gaya') == 'sporty' ? 'selected' : '' }}>Sporty
                            </option>
                            <option value="natural" {{ request('preferensi_gaya') == 'natural' ? 'selected' : '' }}>Natural
                            </option>
                            <option value="professional"
                                {{ request('preferensi_gaya') == 'professional' ? 'selected' : '' }}>Professional</option>
                            <option value="trendy" {{ request('preferensi_gaya') == 'trendy' ? 'selected' : '' }}>Trendy
                            </option>
                            <option value="casual" {{ request('preferensi_gaya') == 'casual' ? 'selected' : '' }}>Casual
                            </option>
                            <option value="bohemian" {{ request('preferensi_gaya') == 'bohemian' ? 'selected' : '' }}>
                                Bohemian</option>
                            <option value="edgy" {{ request('preferensi_gaya') == 'edgy' ? 'selected' : '' }}>Edgy
                            </option>
                            <option value="beach" {{ request('preferensi_gaya') == 'beach' ? 'selected' : '' }}>Beach
                            </option>
                            <option value="urban" {{ request('preferensi_gaya') == 'urban' ? 'selected' : '' }}>Urban
                            </option>
                            <option value="romantic" {{ request('preferensi_gaya') == 'romantic' ? 'selected' : '' }}>
                                Romantis</option>
                            <option value="korean" {{ request('preferensi_gaya') == 'korean' ? 'selected' : '' }}>Korean
                            </option>
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


            @if (count($results))
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                    @foreach ($results as $style)
                        <div class="bg-white shadow-md rounded-lg overflow-hidden hover:shadow-xl transition-all">
                            <img src="{{ asset('storage/' . $style['hairstyle']->image) }}"
                                alt="{{ $style['hairstyle']->name }}" class="w-full h-60 object-cover">
                            <div class="p-4">
                                <h3 class="text-xl font-semibold text-gray-800 mb-2">{{ $style['hairstyle']->name }}</h3>
                                <p class="text-sm text-gray-600">{{ $style['hairstyle']->description }}</p>
                                <div class="mt-3 text-xs text-gray-500">
                                    <strong>Bentuk Wajah:</strong> {{ ucfirst($style['hairstyle']->bentuk_kepala) }}<br>
                                    <strong>Tipe Rambut:</strong> {{ ucfirst($style['hairstyle']->tipe_rambut) }}
                                </div>
                                <div class="mt-2 text-sm text-yellow-700">
                                    <strong>Skor Rekomendasi:</strong> {{ $style['score'] }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-center text-gray-500 mt-16">Tidak ada gaya rambut yang cocok dengan filter yang dipilih.</p>
            @endif
        </div>
    </section>
@endsection
