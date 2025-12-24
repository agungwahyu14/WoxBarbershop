@extends('admin.layouts.app')

@section('title', __('admin.create_booking_title'))

@section('content')
    <!-- Page Header -->
    <div class="is-hero-bar">
        <div class="flex flex-col md:flex-row justify-between items-center">
            <div>
                <h1 class="text-4xl font-bold mb-2">
                    <i class="fas fa-plus-circle mr-3"></i>{{ __('admin.create_booking_title') }}
                </h1>
                <p class="text-black text-lg">
                    {{ __('admin.create_booking_subtitle') }}
                </p>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{ route('admin.bookings.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-md shadow-sm transition-colors duration-200">
                    <i class="fas fa-arrow-left mr-2"></i> {{ __('admin.back_to_bookings') }}
                </a>
            </div>
        </div>
    </div>

    <div class="mx-auto px-6 pt-8 mb-8">
        <form id="bookingForm" method="POST" action="{{ route('admin.bookings.store') }}"
            class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
            @csrf

            <!-- Booking Type Selection -->
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-users mr-2"></i> {{ __('admin.booking_type') }}
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <label class="relative flex items-center p-4 border-2 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors"
                        id="registered-option">
                        <input type="radio" name="booking_type" value="registered" class="hidden peer" required>
                        <div class="peer-checked:border-blue-600 peer-checked:ring-2 peer-checked:ring-blue-500 absolute inset-0 border-2 rounded-lg pointer-events-none">
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                <i class="fas fa-user-check text-2xl text-blue-600"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ __('admin.registered_customer') }}</p>
                                <p class="text-sm text-gray-500">Pilih dari daftar pelanggan terdaftar</p>
                            </div>
                        </div>
                    </label>

                    <label class="relative flex items-center p-4 border-2 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors"
                        id="guest-option">
                        <input type="radio" name="booking_type" value="guest" class="hidden peer">
                        <div class="peer-checked:border-green-600 peer-checked:ring-2 peer-checked:ring-green-500 absolute inset-0 border-2 rounded-lg pointer-events-none">
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                <i class="fas fa-user-plus text-2xl text-green-600"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ __('admin.guest_customer') }}</p>
                                <p class="text-sm text-gray-500">Pelanggan walk-in tanpa akun</p>
                            </div>
                        </div>
                    </label>
                </div>
                @error('booking_type')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Customer Details -->
            <div class="px-6 py-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-user mr-2"></i> {{ __('admin.customer_details') }}
                </h3>

                <!-- Registered Customer Section -->
                <div id="registered-customer-section" class="hidden">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                {{ __('admin.select_customer') }} <span class="text-red-500">*</span>
                            </label>
                            <select name="user_id" id="user_id"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">-- {{ __('admin.select_customer') }} --</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" data-name="{{ $user->name }}"
                                        data-phone="{{ $user->no_telepon }}">
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                {{ __('admin.booking_name') }} (Opsional)
                            </label>
                            <input type="text" name="name" id="booking_name"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Kosongkan untuk menggunakan nama pelanggan">
                            <p class="mt-1 text-sm text-gray-500">Biarkan kosong untuk menggunakan nama pelanggan yang
                                dipilih</p>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Guest Customer Section -->
                <div id="guest-customer-section" class="hidden">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                {{ __('admin.guest_name') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="guest_name" id="guest_name"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Masukkan nama pelanggan">
                            @error('guest_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                {{ __('admin.guest_phone') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="guest_phone" id="guest_phone"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Masukkan nomor telepon (contoh: 081234567890)">
                            @error('guest_phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Service Details -->
            <div class="px-6 py-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-cut mr-2"></i> {{ __('admin.service_details') }}
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('admin.select_service') }} <span class="text-red-500">*</span>
                        </label>
                        <select name="service_id" id="service_id" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">-- {{ __('admin.select_service') }} --</option>
                            @foreach ($services as $service)
                                <option value="{{ $service->id }}" data-price="{{ $service->price }}"
                                    data-duration="{{ $service->duration }}">
                                    {{ $service->name }} - Rp {{ number_format($service->price, 0, ',', '.') }}
                                </option>
                            @endforeach
                        </select>
                        @error('service_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500" id="service-info">Pilih layanan untuk melihat detail</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('admin.select_hairstyle') }} <span class="text-red-500">*</span>
                        </label>
                        <select name="hairstyle_id" id="hairstyle_id" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">-- {{ __('admin.select_hairstyle') }} --</option>
                            @foreach ($hairstyles as $hairstyle)
                                <option value="{{ $hairstyle->id }}">{{ $hairstyle->name }}</option>
                            @endforeach
                        </select>
                        @error('hairstyle_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Booking Information -->
            <div class="px-6 py-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-calendar-alt mr-2"></i> {{ __('admin.booking_information') }}
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Booking <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="booking_date" id="booking_date" required
                            min="{{ date('Y-m-d') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('booking_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('admin.select_shift') }} <span class="text-red-500">*</span>
                        </label>
                        <div class="space-y-2">
                            <label class="flex items-center p-3 border-2 rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="shift" value="morning" required
                                    class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                                <div class="ml-3 flex items-center justify-between flex-1">
                                    <div class="flex items-center">
                                        <i class="fas fa-sun text-amber-500 mr-2"></i>
                                        <span class="font-medium">{{ __('booking.shift_morning') }}</span>
                                    </div>
                                    <span class="text-sm text-gray-500">11:00 - 15:00</span>
                                </div>
                            </label>
                            <label class="flex items-center p-3 border-2 rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="shift" value="afternoon" required
                                    class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                                <div class="ml-3 flex items-center justify-between flex-1">
                                    <div class="flex items-center">
                                        <i class="fas fa-moon text-indigo-500 mr-2"></i>
                                        <span class="font-medium">{{ __('booking.shift_afternoon') }}</span>
                                    </div>
                                    <span class="text-sm text-gray-500">16:00 - 22:00</span>
                                </div>
                            </label>
                        </div>
                        @error('shift')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <div id="capacity-info" class="mt-2 text-sm text-gray-600"></div>
                    </div>
                </div>

                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Catatan (Opsional)
                    </label>
                    <textarea name="description" id="description" rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Tambahkan catatan khusus untuk booking ini"></textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Payment Information -->
            <div class="px-6 py-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-money-bill mr-2"></i> {{ __('admin.payment_information') }}
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Metode Pembayaran <span class="text-red-500">*</span>
                        </label>
                        <div class="space-y-2">
                            <label class="flex items-center p-3 border-2 rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="payment_method" value="cash" required
                                    class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                                <div class="ml-3 flex items-center">
                                    <i class="fas fa-money-bill-wave text-green-500 mr-2"></i>
                                    <span class="font-medium">Tunai</span>
                                </div>
                            </label>
                            <label class="flex items-center p-3 border-2 rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="payment_method" value="bank" required
                                    class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                                <div class="ml-3 flex items-center">
                                    <i class="fas fa-university text-blue-500 mr-2"></i>
                                    <span class="font-medium">Transfer Bank</span>
                                </div>
                            </label>
                        </div>
                        @error('payment_method')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('admin.total_price') }}
                        </label>
                        <div
                            class="px-4 py-3 bg-blue-50 border-2 border-blue-200 rounded-lg flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-600">Total:</span>
                            <span class="text-2xl font-bold text-blue-600" id="total-price">Rp 0</span>
                        </div>
                        <p class="mt-1 text-sm text-gray-500">Harga akan diupdate saat memilih layanan</p>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="px-6 py-4 bg-gray-50 flex justify-end space-x-3">
                <a href="{{ route('admin.bookings.index') }}"
                    class="inline-flex items-center px-6 py-2 border border-gray-300 bg-white text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                    <i class="fas fa-times mr-2"></i> {{ __('admin.cancel') }}
                </a>
                <button type="submit"
                    class="inline-flex items-center px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-sm transition-colors duration-200">
                    <i class="fas fa-check mr-2"></i> {{ __('admin.submit_booking') }}
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    {{-- Midtrans Snap Script --}}
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>

    <script>
        $(document).ready(function() {
            // Booking type selection
            $('input[name="booking_type"]').on('change', function() {
                const bookingType = $(this).val();

                if (bookingType === 'registered') {
                    $('#registered-customer-section').removeClass('hidden');
                    $('#guest-customer-section').addClass('hidden');
                    $('#user_id').prop('required', true);
                    $('#guest_name, #guest_phone').prop('required', false);
                } else {
                    $('#guest-customer-section').removeClass('hidden');
                    $('#registered-customer-section').addClass('hidden');
                    $('#guest_name, #guest_phone').prop('required', true);
                    $('#user_id').prop('required', false);
                }
            });

            // Service selection - update price
            $('#service_id').on('change', function() {
                const selectedOption = $(this).find('option:selected');
                const price = selectedOption.data('price');
                const duration = selectedOption.data('duration');

                if (price) {
                    const formattedPrice = 'Rp ' + price.toLocaleString('id-ID');
                    $('#total-price').text(formattedPrice);
                    $('#service-info').text('Durasi: ' + duration + ' | Harga: ' + formattedPrice);
                } else {
                    $('#total-price').text('Rp 0');
                    $('#service-info').text('Pilih layanan untuk melihat detail');
                }
            });

            // AJAX Form submission with Midtrans integration
            $('#bookingForm').on('submit', function(e) {
                e.preventDefault();

                const bookingType = $('input[name="booking_type"]:checked').val();

                // Client-side validation
                if (!bookingType) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Silakan pilih tipe pelanggan terlebih dahulu',
                        confirmButtonColor: '#d4af37',
                    });
                    return false;
                }

                if (bookingType === 'registered' && !$('#user_id').val()) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Silakan pilih pelanggan terdaftar',
                        confirmButtonColor: '#d4af37',
                    });
                    return false;
                }

                if (bookingType === 'guest') {
                    if (!$('#guest_name').val() || !$('#guest_phone').val()) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Silakan lengkapi nama dan nomor telepon pelanggan tamu',
                            confirmButtonColor: '#d4af37',
                        });
                        return false;
                    }
                }

                // Show loading
                Swal.fire({
                    title: 'Memproses...',
                    text: 'Sedang membuat booking',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // AJAX submit
                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            // Check payment method
                            if (response.payment_method === 'bank' && response.snap_token) {
                                // Bank transfer - show Midtrans Snap popup
                                Swal.close();
                                
                                snap.pay(response.snap_token, {
                                    onSuccess: function(result) {
                                        console.log('Payment success:', result);
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Pembayaran Berhasil!',
                                            text: response.message + ' Pembayaran telah berhasil diproses.',
                                            confirmButtonColor: '#d4af37',
                                        }).then(() => {
                                            window.location.href = response.redirect;
                                        });
                                    },
                                    onPending: function(result) {
                                        console.log('Payment pending:', result);
                                        Swal.fire({
                                            icon: 'info',
                                            title: 'Menunggu Pembayaran',
                                            text: response.message + ' Silakan selesaikan pembayaran untuk pelanggan.',
                                            confirmButtonColor: '#d4af37',
                                        }).then(() => {
                                            window.location.href = response.redirect;
                                        });
                                    },
                                    onError: function(result) {
                                        console.log('Payment error:', result);
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Pembayaran Gagal',
                                            text: 'Terjadi kesalahan saat memproses pembayaran.',
                                            confirmButtonColor: '#d4af37',
                                        }).then(() => {
                                            window.location.href = response.redirect;
                                        });
                                    },
                                    onClose: function() {
                                        console.log('Payment popup closed');
                                        Swal.fire({
                                            icon: 'warning',
                                            title: 'Pembayaran Belum Selesai',
                                            text: 'Anda menutup jendela pembayaran sebelum menyelesaikan transaksi.',
                                            confirmButtonColor: '#d4af37',
                                        }).then(() => {
                                            window.location.href = response.redirect;
                                        });
                                    }
                                });
                            } else {
                                // Cash payment - just redirect with success
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: response.message,
                                    confirmButtonColor: '#d4af37',
                                }).then(() => {
                                    window.location.href = response.redirect;
                                });
                            }
                        }
                    },
                    error: function(xhr) {
                        Swal.close();
                        
                        let errorMessage = 'Terjadi kesalahan saat membuat booking';
                        
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            // Validation errors
                            const errors = xhr.responseJSON.errors;
                            errorMessage = Object.values(errors).flat().join('<br>');
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            html: errorMessage,
                            confirmButtonColor: '#d4af37',
                        });
                    }
                });
            });

            // Auto-fill booking name from selected user
            $('#user_id').on('change', function() {
                const selectedOption = $(this).find('option:selected');
                const userName = selectedOption.data('name');

                if (userName && !$('#booking_name').val()) {
                    $('#booking_name').attr('placeholder', 'Akan menggunakan: ' + userName);
                }
            });
        });
    </script>
@endpush
