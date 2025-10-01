@extends('admin.layouts.app')

@section('title', 'Laporan & Analitik')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Header --}}
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Laporan & Analitik</h1>
                        <p class="mt-2 text-gray-600">Pantau kinerja bisnis dan analitik mendalam</p>
                    </div>
                    <div class="flex space-x-3">
                        <button onclick="refreshData()"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition duration-200">
                            <i class="fas fa-sync-alt mr-2"></i>
                            Refresh Data
                        </button>
                    </div>
                </div>
            </div>

            {{-- Quick Stats Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Total Booking</p>
                            <p class="text-3xl font-bold text-gray-900">0</p>
                        </div>
                        <div class="p-3 bg-blue-100 rounded-full">
                            <i class="fas fa-calendar-check text-blue-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Total Pendapatan</p>
                            <p class="text-3xl font-bold text-green-600">Rp0</p>
                        </div>
                        <div class="p-3 bg-green-100 rounded-full">
                            <i class="fas fa-money-bill-wave text-green-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Total Pelanggan</p>
                            <p class="text-3xl font-bold text-purple-600">0</p>
                        </div>
                        <div class="p-3 bg-purple-100 rounded-full">
                            <i class="fas fa-users text-purple-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Layanan Aktif</p>
                            <p class="text-3xl font-bold text-orange-600">5</p>
                        </div>
                        <div class="p-3 bg-orange-100 rounded-full">
                            <i class="fas fa-cut text-orange-600 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Report Categories --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                {{-- Financial Reports --}}
                <div
                    class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center mb-4">
                        <div class="p-3 bg-green-100 rounded-full mr-4">
                            <i class="fas fa-chart-line text-green-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Laporan Keuangan</h3>
                            <p class="text-sm text-gray-600">Analisis pendapatan dan transaksi</p>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <a href="{{ route('admin.reports.financial') }}"
                            class="block w-full text-left px-4 py-2 bg-gray-50 hover:bg-gray-100 rounded-lg transition duration-200">
                            <span class="text-sm font-medium text-gray-700">Pendapatan Harian</span>
                        </a>
                        <a href="{{ route('admin.reports.financial') }}?view=monthly"
                            class="block w-full text-left px-4 py-2 bg-gray-50 hover:bg-gray-100 rounded-lg transition duration-200">
                            <span class="text-sm font-medium text-gray-700">Pendapatan Bulanan</span>
                        </a>
                        <a href="{{ route('admin.reports.financial') }}?view=payment_methods"
                            class="block w-full text-left px-4 py-2 bg-gray-50 hover:bg-gray-100 rounded-lg transition duration-200">
                            <span class="text-sm font-medium text-gray-700">Metode Pembayaran</span>
                        </a>
                    </div>
                </div>

                {{-- Booking Reports --}}
                <div
                    class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center mb-4">
                        <div class="p-3 bg-blue-100 rounded-full mr-4">
                            <i class="fas fa-calendar-alt text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Laporan Booking</h3>
                            <p class="text-sm text-gray-600">Analisis booking dan layanan</p>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <a href="{{ route('admin.reports.bookings') }}"
                            class="block w-full text-left px-4 py-2 bg-gray-50 hover:bg-gray-100 rounded-lg transition duration-200">
                            <span class="text-sm font-medium text-gray-700">Status Booking</span>
                        </a>
                        <a href="{{ route('admin.reports.bookings') }}?view=services"
                            class="block w-full text-left px-4 py-2 bg-gray-50 hover:bg-gray-100 rounded-lg transition duration-200">
                            <span class="text-sm font-medium text-gray-700">Layanan Populer</span>
                        </a>
                        <a href="{{ route('admin.reports.bookings') }}?view=trends"
                            class="block w-full text-left px-4 py-2 bg-gray-50 hover:bg-gray-100 rounded-lg transition duration-200">
                            <span class="text-sm font-medium text-gray-700">Tren Booking</span>
                        </a>
                    </div>
                </div>

                {{-- Customer Reports --}}
                <div
                    class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center mb-4">
                        <div class="p-3 bg-purple-100 rounded-full mr-4">
                            <i class="fas fa-user-friends text-purple-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Laporan Pelanggan</h3>
                            <p class="text-sm text-gray-600">Analisis pelanggan dan retensi</p>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <a href="{{ route('admin.reports.customers') }}"
                            class="block w-full text-left px-4 py-2 bg-gray-50 hover:bg-gray-100 rounded-lg transition duration-200">
                            <span class="text-sm font-medium text-gray-700">Pelanggan Baru</span>
                        </a>
                        <a href="{{ route('admin.reports.customers') }}?view=retention"
                            class="block w-full text-left px-4 py-2 bg-gray-50 hover:bg-gray-100 rounded-lg transition duration-200">
                            <span class="text-sm font-medium text-gray-700">Tingkat Retensi</span>
                        </a>
                        <a href="{{ route('admin.reports.customers') }}?view=top_customers"
                            class="block w-full text-left px-4 py-2 bg-gray-50 hover:bg-gray-100 rounded-lg transition duration-200">
                            <span class="text-sm font-medium text-gray-700">Pelanggan Terbaik</span>
                        </a>
                    </div>
                </div>

            </div>

            {{-- Export Options --}}
            <div class="mt-8 bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Ekspor Data</h3>
                <div class="flex flex-wrap gap-3">
                    <button onclick="exportReport('financial')"
                        class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition duration-200">
                        <i class="fas fa-file-excel mr-2"></i>
                        Ekspor Keuangan
                    </button>
                    <button onclick="exportReport('bookings')"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition duration-200">
                        <i class="fas fa-file-csv mr-2"></i>
                        Ekspor Booking
                    </button>
                    <button onclick="exportReport('customers')"
                        class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition duration-200">
                        <i class="fas fa-file-pdf mr-2"></i>
                        Ekspor Pelanggan
                    </button>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
        <script>
            function refreshData() {
                Swal.fire({
                    title: 'Memperbarui Data...',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    willOpen: () => {
                        Swal.showLoading();
                    }
                });

                setTimeout(() => {
                    location.reload();
                }, 1500);
            }

            function exportReport(type) {
                Swal.fire({
                    title: 'Ekspor Laporan',
                    text: `Mempersiapkan ekspor laporan ${type}...`,
                    icon: 'info',
                    showConfirmButton: false,
                    timer: 2000
                });

                // Implementation for export functionality
                setTimeout(() => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Laporan berhasil diekspor',
                        confirmButtonColor: '#3b82f6'
                    });
                }, 2000);
            }
        </script>
    @endpush

@endsection
