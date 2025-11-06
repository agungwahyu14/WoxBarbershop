@extends('admin.layouts.app')

@section('content')
    <section class="is-title-bar">
        <div class="flex flex-col md:flex-row items-center justify-between space-y-6 md:space-y-0">
            <ul>

                @if (Auth::user()->hasRole('admin'))
                    <li>{{ __('admin.admin') }}</li>
                @elseif(Auth::user()->hasRole('pegawai'))
                    <li>{{ __('admin.staff') }}</li>
                @endif
                <li>{{ __('menu.dashboard') }}</li>
            </ul>
            <div class="text-sm text-gray-600 flex items-center">
                <i class="fas fa-user-circle mr-2"></i>
                {{ __('admin.welcome_back') }}, <span class="font-semibold ml-1">{{ Auth::user()->name }}</span>
                @if (Auth::user()->hasRole('admin'))
                    <span
                        class="ml-2 px-2 py-1 bg-red-100 text-red-700 rounded-full text-xs">({{ __('admin.administrator') }})</span>
                @elseif(Auth::user()->hasRole('pegawai'))
                    <span
                        class="ml-2 px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-xs">({{ __('admin.staff') }})</span>
                @endif
            </div>
        </div>
    </section>

    <section class="is-hero-bar">
        <div class="flex flex-col md:flex-row items-center justify-between space-y-6 md:space-y-0">
            <div>
                <h1 class="title text-3xl font-bold text-gray-800">
                    {{ __('admin.dashboard_title') }}
                </h1>
                <p class="text-gray-600 mt-2">{{ __('admin.dashboard_subtitle') }}</p>
            </div>
            <div class="text-right">
                <div class="text-sm text-gray-500">
                    {{ now()->setTimezone('Asia/Makassar')->translatedFormat('l, d F Y') }}
                </div>
                <div class="text-lg font-semibold text-gray-800">
                    {{ now()->setTimezone('Asia/Makassar')->format('H:i') }} WITA
                </div>
            </div>

        </div>
    </section>

    <section class="section main-section">
        <!-- Stats Cards -->
        <div class="grid gap-6 grid-cols-4 md:grid-cols-4 mb-8">
            <!-- Total Pelanggan -->
            <div class="card card-hover transition-all duration-300 bg-gradient-to-br from-blue-500 to-blue-600 text-white">
                <div class="card-content p-6">
                    <div class="flex items-center justify-between">
                        <div class="widget-label">
                            <h3 class="text-sm font-medium mb-2 text-white">{{ __('admin.customers') }}</h3>
                            <h1 class="text-3xl font-bold text-white" id="total-customers">{{ $todayCustomers }}</h1>
                        </div>
                        <div class="bg-blue-400 bg-opacity-30 p-3 rounded-full">
                            <i class="mdi mdi-account-multiple text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Total Revenue -->
            <div
                class="card card-hover transition-all duration-300 bg-gradient-to-br from-green-500 to-green-600 text-white">
                <div class="card-content p-6">
                    <div class="flex items-center justify-between">
                        <div class="widget-label">
                            <h3 class="text-green-100 text-sm font-medium mb-2">{{ __('admin.transactions') }}</h3>
                            <h1 class="text-3xl font-bold text-white" id="today-bookings">
                                {{ $todayTransactions }}
                            </h1>

                        </div>
                        <div class="bg-green-400 bg-opacity-30 p-3 rounded-full">
                            <i class="mdi mdi-cash-multiple text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Today's Bookings -->
            <div
                class="card card-hover transition-all duration-300 bg-gradient-to-br from-orange-500 to-orange-600 text-white">
                <div class="card-content p-6">
                    <div class="flex items-center justify-between">
                        <div class="widget-label">
                            <h3 class="text-orange-100 text-sm font-medium mb-2">{{ __('admin.bookings') }}</h3>
                            <h1 class="text-3xl font-bold text-white" id="today-bookings">
                                {{ $todayBookings }}
                            </h1>
                            <p class="text-orange-200 text-xs mt-1">
                                <span id="pending-bookings">{{ $pendingBookings }}</span> {{ __('admin.pending') }}
                            </p>
                        </div>
                        <div class="bg-orange-400 bg-opacity-30 p-3 rounded-full">
                            <i class="mdi mdi-calendar-today text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Most Popular Service -->

            <div class="card card-hover transition-all duration-300 bg-gradient-to-br from-pink-500 to-pink-600 text-white">
                <div class="card-content p-6">
                    <div class="flex items-center justify-between">
                        <div class="widget-label">
                            <h3 class="text-pink-100 text-sm font-medium mb-2">{{ __('admin.popular_service') }}</h3>
                            <h1 class="text-3xl font-bold text-white" id="popular-service">{{ $popularServiceName }}</h1>

                        </div>
                        <div class="bg-pink-400 bg-opacity-30 p-3 rounded-full">
                            <i class="mdi mdi-star-outline text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Today's Bookings Table -->
        <div class="card mb-8">
            <header class="card-header bg-gradient-to-r from-indigo-50 to-blue-50">
                <p class="card-header-title text-gray-800">
                    <span class="icon text-indigo-600"><i class="mdi mdi-calendar-today"></i></span>
                    {{ __('admin.today_bookings') }}
                </p>

            </header>
            <div class="card-content">
                <div class="overflow-x-auto">
                    <table class="min-w-full table-auto">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('admin.no') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('admin.customer') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('admin.service') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('admin.time') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('admin.status') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('admin.total') }}</th>
                            </tr>
                        </thead>
                        <tbody id="today-bookings-table" class="bg-white divide-y divide-gray-200">
                            @forelse($todayBookingsData as $index => $booking)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $booking->user->name ?? 'N/A' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $booking->service->name ?? 'N/A' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ \Carbon\Carbon::parse($booking->date_time)->format('H:i') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $statusColors = [
                                                'pending' => 'bg-yellow-100 text-yellow-800',
                                                'confirmed' => 'bg-blue-100 text-blue-800',
                                                'completed' => 'bg-green-100 text-green-800',
                                                'cancelled' => 'bg-red-100 text-red-800',
                                            ];
                                        @endphp
                                        <span
                                            class="px-2 py-1 text-xs font-medium rounded-full {{ $statusColors[$booking->status] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ __('booking.status_' . $booking->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            Rp{{ number_format($booking->total_price, 0, ',', '.') }}</div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                        <div class="flex flex-col items-center">
                                            <i class="mdi mdi-calendar-remove text-4xl mb-2 text-gray-300"></i>
                                            <p class="text-sm">{{ __('admin.no_bookings_today') }}</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @role('admin')
            <!-- Analytics Categories -->
            <div class="grid gap-6 grid-cols-1 lg:grid-cols-3 mb-8">
                <!-- Financial Reports -->
                <div class="card hover:shadow-lg transition-shadow duration-200">
                    <div class="card-content p-6">
                        <div class="flex items-center mb-4">
                            <div class="p-3 bg-green-100 rounded-full mr-4">
                                <i class="mdi mdi-chart-line text-green-600 text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">{{ __('admin.financial_reports') }}</h3>
                                <p class="text-sm text-gray-600">{{ __('admin.revenue_analysis') }}</p>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <div class="flex items-center justify-between p-2 bg-gray-50 rounded">
                                <span class="text-sm text-gray-700">{{ __('admin.daily_revenue') }}</span>
                                <span class="font-medium text-green-600"
                                    id="daily-revenue">Rp{{ number_format($dailyRevenue ?? 0, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex items-center justify-between p-2 bg-gray-50 rounded">
                                <span class="text-sm text-gray-700">{{ __('admin.monthly_revenue') }}</span>
                                <span class="font-medium text-green-600"
                                    id="monthly-revenue">Rp{{ number_format($monthlyRevenue ?? 0, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Booking Analytics -->
                <div class="card hover:shadow-lg transition-shadow duration-200">
                    <div class="card-content p-6">
                        <div class="flex items-center mb-4">
                            <div class="p-3 bg-blue-100 rounded-full mr-4">
                                <i class="mdi mdi-calendar-check text-blue-600 text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">{{ __('admin.booking_analysis') }}</h3>
                                <p class="text-sm text-gray-600">{{ __('admin.booking_status_trends') }}</p>
                            </div>
                        </div>
                        <div class="space-y-2">
                            @php
                                $bookingStats = \App\Models\Booking::selectRaw('status, COUNT(*) as count')
                                    ->groupBy('status')
                                    ->get();
                                $total = $bookingStats->sum('count');
                                $statusColors = [
                                    'pending' => 'text-yellow-600',
                                    'confirmed' => 'text-blue-600',
                                    'completed' => 'text-green-600',
                                    'cancelled' => 'text-red-600',
                                ];
                            @endphp
                            @foreach ($bookingStats->take(2) as $stat)
                                <div class="flex items-center justify-between p-2 bg-gray-50 rounded">
                                    <span class="text-sm text-gray-700">{{ __('admin.' . $stat->status) }}</span>
                                    <span
                                        class="font-medium {{ $statusColors[$stat->status] ?? 'text-gray-600' }}">{{ $stat->count }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Customer Analytics -->
                <div class="card hover:shadow-lg transition-shadow duration-200">
                    <div class="card-content p-6">
                        <div class="flex items-center mb-4">
                            <div class="p-3 bg-purple-100 rounded-full mr-4">
                                <i class="mdi mdi-account-group text-purple-600 text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">{{ __('admin.customer_data') }}</h3>
                                <p class="text-sm text-gray-600">{{ __('admin.customer_statistics') }}</p>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <div class="flex items-center justify-between p-2 bg-gray-50 rounded">
                                <span class="text-sm text-gray-700">{{ __('admin.new_customers') }}</span>
                                <span class="font-medium text-purple-600" id="new-customers">{{ $newCustomers ?? 0 }}</span>
                            </div>
                            <div class="flex items-center justify-between p-2 bg-gray-50 rounded">
                                <span class="text-sm text-gray-700">{{ __('admin.total_customers') }}</span>
                                <span class="font-medium text-purple-600"
                                    id="total-customers-count">{{ $totalCustomersCount ?? 0 }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Export Options -->
            <div class="card mb-8">
                <header class="card-header bg-gradient-to-r from-gray-50 to-gray-100">
                    <p class="card-header-title text-gray-800">
                        <span class="icon text-gray-600"><i class="mdi mdi-download"></i></span>
                        {{ __('admin.export_data_reports') }}
                    </p>
                </header>
                <div class="card-content">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <button onclick="openExportModal('financial', '{{ __('admin.export_financial_report') }}')"
                            class="inline-flex items-center justify-center px-4 py-3 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition duration-200 hover:shadow-md">
                            <i class="mdi mdi-file-excel mr-2"></i>
                            {{ __('admin.export_financial') }}
                        </button>
                        <button onclick="openExportModal('bookings', '{{ __('admin.export_booking_report') }}')"
                            class="inline-flex items-center justify-center px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition duration-200 hover:shadow-md">
                            <i class="mdi mdi-file-csv mr-2"></i>
                            {{ __('admin.export_bookings') }}
                        </button>
                        <button onclick="openExportModal('customers', '{{ __('admin.export_customer_report') }}')"
                            class="inline-flex items-center justify-center px-4 py-3 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition duration-200 hover:shadow-md">
                            <i class="mdi mdi-file-pdf mr-2"></i>
                            {{ __('admin.export_customers') }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- Export Filter Modal -->
            <div id="exportModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
                <div class="bg-white rounded-lg p-6 m-4 max-w-md w-full shadow-lg">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900" id="modalTitle">{{ __('admin.export_report') }}</h3>
                        <button onclick="closeExportModal()" class="text-gray-400 hover:text-red-500 transition-colors">
                            <i class="mdi mdi-close text-xl"></i>
                        </button>
                    </div>

                    <form id="exportForm" method="POST" action="{{ route('dashboard.export') }}">
                        @csrf
                        <input type="hidden" id="exportType" name="type" value="">

                        <div class="mb-4">
                            <label
                                class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.export_format') }}</label>
                            <select name="format" id="exportFormat"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="pdf">PDF</option>
                                <option value="excel">Excel (XLSX)</option>
                                <option value="csv">CSV</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label
                                class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.filter_period') }}</label>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <select name="month" id="exportMonth"
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="">{{ __('admin.all_months') }}</option>
                                        <option value="1">{{ __('admin.january') }}</option>
                                        <option value="2">{{ __('admin.february') }}</option>
                                        <option value="3">{{ __('admin.march') }}</option>
                                        <option value="4">{{ __('admin.april') }}</option>
                                        <option value="5">{{ __('admin.may') }}</option>
                                        <option value="6">{{ __('admin.june') }}</option>
                                        <option value="7">{{ __('admin.july') }}</option>
                                        <option value="8">{{ __('admin.august') }}</option>
                                        <option value="9">{{ __('admin.september') }}</option>
                                        <option value="10">{{ __('admin.october') }}</option>
                                        <option value="11">{{ __('admin.november') }}</option>
                                        <option value="12">{{ __('admin.december') }}</option>
                                    </select>
                                </div>
                                <div>
                                    <select name="year" id="exportYear"
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="">{{ __('admin.all_years') }}</option>
                                        @for ($year = date('Y'); $year >= date('Y') - 5; $year--)
                                            <option value="{{ $year }}" {{ $year == date('Y') ? 'selected' : '' }}>
                                                {{ $year }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-3">
                            <!-- Tombol Cancel dengan warna abu-abu -->
                            <button type="button" onclick="closeExportModal()"
                                class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 bg-gray-100 hover:bg-gray-200 hover:text-gray-900 transition-colors">
                                {{ __('admin.cancel') }}
                            </button>

                            <!-- Tombol Export dengan warna biru -->
                            <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:ring-2 focus:ring-blue-300 transition flex items-center">
                                <i class="mdi mdi-download mr-2"></i>
                                {{ __('admin.export') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endrole
    </section>

    <script>
        // Function to open the export modal
        function openExportModal(type, title) {
            // Set the modal title dynamically
            document.getElementById('modalTitle').innerText = title;

            // Set the export type value to the hidden input
            document.getElementById('exportType').value = type;

            // Show the modal
            const modal = document.getElementById('exportModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        // Function to close the export modal
        function closeExportModal() {
            // Hide the modal
            const modal = document.getElementById('exportModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        // Close modal when clicking outside
        document.getElementById('exportModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeExportModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeExportModal();
            }
        });
    </script>

    @if (session('success'))
        <script script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#10B981',
                timer: 3000,
                timerProgressBar: true
            });
        </script>
    @endif
@endsection
