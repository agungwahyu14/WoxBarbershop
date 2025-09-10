@extends('layouts.app')

@section('content')
    <section class="py-20">
        <div class="container mx-auto px-4">

            <div class="text-center mb-10 mt-8">
                <h2 class="text-3xl md:text-4xl font-bold font-playfair text-gray-900">Detail Transaksi</h2>
                <p class="text-lg text-gray-600 max-w-xl mx-auto mt-2">Lihat detail transaksi Anda di sini.</p>
            </div>

            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="p-6 space-y-6">


                    {{-- Informasi Transaksi --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

                        {{-- Customer Information --}}
                        <div class="col-span-full border-b border-gray-200 pb-4 mb-4">
                            <h3 class="text-lg font-semibold text-gray-800 mb-3">
                                <i class="fas fa-user mr-2 text-blue-600"></i> Informasi Pelanggan
                            </h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <h4 class="text-sm text-gray-500 uppercase mb-1">Nama Pelanggan</h4>
                                    <p class="text-lg font-medium text-gray-800">{{ $data['customer_name'] ?? '-' }}</p>
                                </div>
                                <div>
                                    <h4 class="text-sm text-gray-500 uppercase mb-1">Email</h4>
                                    <p class="text-lg text-gray-700">{{ $data['customer_email'] ?? '-' }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Order ID --}}
                        <div>
                            <h4 class="text-sm text-gray-500 uppercase mb-1">ID Pesan</h4>
                            <p class="text-lg font-semibold text-gray-800">{{ $data['order_id'] }}</p>
                        </div>

                        {{-- Status Pembayaran --}}
                        <div>
                            <h4 class="text-sm text-gray-500 uppercase mb-1">Status Pembayaran</h4>
                            <span
                                class="inline-block px-3 py-1 rounded-full text-sm font-medium
                            @switch($data['transaction_status'])
                                @case('pending')
                                    bg-yellow-100 text-yellow-700
                                    @break
                                @case('settlement')
                                @case('capture')
                                    bg-green-100 text-green-700
                                    @break
                                @case('expire')
                                @case('cancel')
                                @case('deny')
                                    bg-red-100 text-red-700
                                    @break
                                @default
                                    bg-gray-100 text-gray-600
                            @endswitch">
                                {{ ucfirst($data['transaction_status']) }}
                            </span>
                        </div>

                        {{-- Waktu Transaksi --}}
                        <div>
                            <h4 class="text-sm text-gray-500 uppercase mb-1">Waktu Transaksi</h4>
                            <p class="text-lg font-semibold text-gray-800">
                                {{ \Carbon\Carbon::parse($data['transaction_time'])->format('d M Y H:i') }}
                            </p>
                        </div>

                        {{-- Metode Pembayaran --}}
                        <div>
                            <h4 class="text-sm text-gray-500 uppercase mb-1">Metode Pembayaran</h4>
                            <p class="text-lg font-semibold text-gray-800">{{ strtoupper($data['payment_type']) }}</p>
                        </div>

                        {{-- Nomor Virtual Account (jika ada) --}}
                        @if (!empty($data['va_number']))
                            <div>
                                <h4 class="text-sm text-gray-500 uppercase mb-1">Bank</h4>
                                <p class="text-lg font-semibold text-gray-800">{{ strtoupper($data['bank']) }}</p>
                            </div>
                            <div>
                                <h4 class="text-sm text-gray-500 uppercase mb-1">Virtual Account</h4>
                                <p class="text-lg font-mono text-blue-600">{{ $data['va_number'] }}</p>
                            </div>
                        @endif

                        {{-- Total Pembayaran --}}
                        <div>
                            <h4 class="text-sm text-gray-500 uppercase mb-1">Total Pembayaran</h4>
                            <p class="text-lg font-bold text-gray-800">
                                Rp {{ number_format($data['amount'], 0, ',', '.') }}
                            </p>
                        </div>
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="pt-6 border-t flex justify-between items-center">
                        <a href="{{ route('payment.index') }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition duration-200">
                            <i class="fas fa-arrow-left mr-2"></i> Kembali
                        </a>
                    </div>

                </div>
            </div>

        </div>
    </section>
@endsection
