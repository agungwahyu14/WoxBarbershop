@extends('layouts.app')

@section('content')
    <section id="edit-booking" class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            {{-- Header --}}
            <div class="text-center mb-12 mt-8">
                <h2 class="text-3xl md:text-4xl font-bold font-playfair text-gray-900">Edit Booking</h2>
                <p class="text-lg text-gray-600 max-w-xl mx-auto mt-2">Ubah detail booking Anda sesuai kebutuhan.</p>
            </div>

            {{-- Edit Form Card --}}
            <div class="max-w-2xl mx-auto">
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                    {{-- Card Header --}}
                    <div class="bg-black px-6 py-4">
                        <h3 class="text-white font-semibold text-lg">
                            <i class="fas fa-edit mr-2"></i>
                            Edit Booking #{{ $booking->id }}
                        </h3>
                        <p class="text-blue-100 text-sm">Perbarui informasi booking Anda</p>
                    </div>

                    {{-- Card Body --}}
                    <div class="p-6">
                        {{-- Current Booking Info --}}
                        <div class="bg-gray-50 rounded-lg p-4 mb-6">
                            <h4 class="text-sm font-semibold text-gray-700 uppercase mb-2">Informasi Saat Ini</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="text-gray-500">Status:</span>
                                    <span
                                        class="ml-2 px-2 py-1 rounded-full text-xs font-medium
                                        {{ $booking->status === 'pending'
                                            ? 'bg-yellow-100 text-yellow-700'
                                            : ($booking->status === 'confirmed'
                                                ? 'bg-green-100 text-green-700'
                                                : 'bg-gray-100 text-gray-600') }}">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </div>
                                <div>
                                    <span class="text-gray-500">Nomor Antrian:</span>
                                    <span class="ml-2 font-semibold text-blue-600">#{{ $booking->queue_number }}</span>
                                </div>
                                @if ($booking->payment_status)
                                    <div>
                                        <span class="text-gray-500">Status Pembayaran:</span>
                                        <span
                                            class="ml-2 px-2 py-1 rounded-full text-xs font-medium
                                            {{ $booking->payment_status === 'paid'
                                                ? 'bg-green-100 text-green-700'
                                                : ($booking->payment_status === 'pending'
                                                    ? 'bg-yellow-100 text-yellow-700'
                                                    : 'bg-red-100 text-red-700') }}">
                                            {{ $booking->payment_status === 'unpaid' ? 'Belum Bayar' : ucfirst($booking->payment_status) }}
                                        </span>
                                    </div>
                                @endif
                                @if ($booking->total_price)
                                    <div>
                                        <span class="text-gray-500">Total:</span>
                                        <span class="ml-2 font-semibold text-green-600" id="current-price">Rp
                                            {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Edit Form --}}
                        <form action="{{ route('bookings.update', $booking->id) }}" method="POST" class="space-y-6"
                            id="editBookingForm">
                            @csrf
                            @method('PUT')

                            {{-- Name --}}
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-user mr-1 text-[#d4af37]   "></i>
                                    Nama Pelanggan
                                </label>
                                <input type="text" name="name" id="name"
                                    value="{{ old('name', $booking->name) }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror"
                                    placeholder="Masukkan nama pelanggan" required>
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Date & Time --}}
                            <div>
                                <label for="date_time" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-calendar-alt mr-1 text-[#d4af37]   "></i>
                                    Tanggal & Waktu
                                </label>
                                <input type="datetime-local" name="date_time" id="date_time"
                                    value="{{ old('date_time', \Carbon\Carbon::parse($booking->date_time)->format('Y-m-d\TH:i')) }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('date_time') border-red-500 @enderror"
                                    required>
                                @error('date_time')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Service --}}
                            @if (isset($services) && $services->count() > 0)
                                <div>
                                    <label for="service_id" class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-cut mr-1 text-[#d4af37]"></i>
                                        Layanan <span class="text-red-500">*</span>
                                    </label>
                                    <select name="service_id" id="service_id" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('service_id') border-red-500 @enderror">
                                        <option value="">Pilih Layanan</option>
                                        @foreach ($services as $service)
                                            <option value="{{ $service->id }}" data-price="{{ $service->price }}"
                                                {{ old('service_id', $booking->service_id) == $service->id ? 'selected' : '' }}>
                                                {{ $service->name }} - Rp
                                                {{ number_format($service->price, 0, ',', '.') }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('service_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            @else
                                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                                    <p class="text-red-600 text-sm">
                                        <i class="fas fa-exclamation-triangle mr-2"></i>
                                        Tidak ada layanan tersedia saat ini.
                                    </p>
                                </div>
                            @endif

                            {{-- Hairstyle --}}
                            @if (isset($hairstyles) && $hairstyles->count() > 0)
                                <div>
                                    <label for="hairstyle_id" class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-magic mr-1 text-[#d4af37]"></i>
                                        Gaya Rambut <span class="text-red-500">*</span>
                                    </label>
                                    <select name="hairstyle_id" id="hairstyle_id" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('hairstyle_id') border-red-500 @enderror">
                                        <option value="">Pilih Gaya Rambut</option>
                                        @foreach ($hairstyles as $style)
                                            <option value="{{ $style->id }}"
                                                {{ old('hairstyle_id', $booking->hairstyle_id) == $style->id ? 'selected' : '' }}>
                                                {{ $style->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('hairstyle_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            @else
                                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                                    <p class="text-red-600 text-sm">
                                        <i class="fas fa-exclamation-triangle mr-2"></i>
                                        Tidak ada gaya rambut tersedia saat ini.
                                    </p>
                                </div>
                            @endif

                            {{-- Description --}}
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-comment-alt mr-1 text-[#d4af37]    "></i>
                                    Deskripsi / Catatan Khusus
                                </label>
                                <textarea name="description" id="description" rows="4"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-500 @enderror"
                                    placeholder="Tambahkan catatan khusus untuk booking Anda (opsional)">{{ old('description', $booking->description) }}</textarea>
                                @error('description')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Action Buttons --}}
                            <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t">
                                <a href="{{ route('bookings.show', $booking->id) }}"
                                    class="flex-1 inline-flex justify-center items-center px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors duration-200">
                                    <i class="fas fa-arrow-left mr-2"></i>
                                    Kembali
                                </a>

                                <button type="submit"
                                    class="flex-1 inline-flex justify-center items-center px-6 py-3 bg-black hover:bg-[#d4af37] text-white font-medium rounded-lg transition-colors duration-200">
                                    <i class="fas fa-save mr-2"></i>
                                    Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Additional Info Card --}}
                <div class="mt-6 bg-[#d4af37]-50 border border-[#d4af37] rounded-lg p-4">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-[#d4af37]  mt-0.5 mr-3"></i>
                        <div class="text-sm text-[#d4af37]">
                            <h4 class="font-semibold mb-1">Informasi Penting:</h4>
                            <ul class="list-disc list-inside space-y-1">
                                <li>Perubahan tanggal dan waktu mungkin mempengaruhi nomor antrian Anda</li>
                                <li>Jika mengubah layanan, total harga akan dihitung ulang</li>
                                <li>Booking hanya dapat diubah jika status masih "Pending" atau "Confirmed"</li>

                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- JavaScript untuk handling form --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const serviceSelect = document.getElementById('service_id');
            const currentPriceElement = document.getElementById('current-price');
            const form = document.getElementById('editBookingForm');

            // Service prices data
            const servicePrices = {
                @foreach ($services as $service)
                    {{ $service->id }}: {{ $service->price }},
                @endforeach
            };

            // Update price display when service changes
            if (serviceSelect && currentPriceElement) {
                serviceSelect.addEventListener('change', function() {
                    const selectedServiceId = this.value;
                    if (selectedServiceId && servicePrices[selectedServiceId]) {
                        const newPrice = servicePrices[selectedServiceId];
                        const formattedPrice = new Intl.NumberFormat('id-ID', {
                            style: 'currency',
                            currency: 'IDR',
                            minimumFractionDigits: 0
                        }).format(newPrice).replace('IDR', 'Rp');

                        currentPriceElement.textContent = formattedPrice;
                        currentPriceElement.classList.add('text-orange-600');
                        currentPriceElement.classList.remove('text-green-600');
                    }
                });
            }

            // Form validation
            if (form) {
                form.addEventListener('submit', function(e) {
                    const dateTimeInput = document.getElementById('date_time');
                    const serviceInput = document.getElementById('service_id');
                    const hairstyleInput = document.getElementById('hairstyle_id');
                    const nameInput = document.getElementById('name');

                    let hasError = false;
                    let errorMessage = '';

                    // Validate required fields
                    if (!nameInput.value.trim()) {
                        hasError = true;
                        errorMessage += '• Nama wajib diisi\n';
                    }

                    if (!serviceInput.value) {
                        hasError = true;
                        errorMessage += '• Layanan wajib dipilih\n';
                    }

                    if (!hairstyleInput.value) {
                        hasError = true;
                        errorMessage += '• Gaya rambut wajib dipilih\n';
                    }

                    if (!dateTimeInput.value) {
                        hasError = true;
                        errorMessage += '• Tanggal dan waktu wajib diisi\n';
                    } else {
                        // Validate business hours
                        const selectedDateTime = new Date(dateTimeInput.value);
                        const hours = selectedDateTime.getHours();
                        const dayOfWeek = selectedDateTime.getDay();

                        if (dayOfWeek === 0) { // Sunday
                            hasError = true;
                            errorMessage += '• Maaf, kami tutup pada hari Minggu\n';
                        }

                        if (hours < 9 || hours >= 21) {
                            hasError = true;
                            errorMessage += '• Booking hanya dapat dilakukan antara jam 09:00 - 21:00\n';
                        }
                    }

                    if (hasError) {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'error',
                            title: 'Validation Error',
                            text: errorMessage,
                            confirmButtonColor: '#d4af37'
                        });
                    }
                });
            }

            // Handle validation errors from server
            @if ($errors->any())
                let errorMessage = '';
                @foreach ($errors->all() as $error)
                    errorMessage += '• {{ $error }}\n';
                @endforeach

                Swal.fire({
                    icon: 'error',
                    title: 'Update Gagal!',
                    text: errorMessage,
                    confirmButtonColor: '#d4af37',
                    confirmButtonText: 'Coba Lagi'
                });
            @endif

            // Handle success message
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    confirmButtonColor: '#d4af37',
                    confirmButtonText: 'Oke'
                });
            @endif

            // Handle error message
            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan!',
                    text: '{{ session('error') }}',
                    confirmButtonColor: '#d4af37',
                    confirmButtonText: 'Oke'
                });
            @endif
        });
    </script>

@endsection
