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
                            <h3 class=" text-sm font-medium mb-2">{{ __('admin.customers') }}</h3>
                            <h1 class="text-3xl font-bold" id="total-customers">{{ $todayCustomers }}</h1>

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
                            <h1 class="text-3xl font-bold" id="today-bookings">
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
                            <h1 class="text-3xl font-bold" id="today-bookings">
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
                <div class="card-header-icon">
                    <div class="flex items-center space-x-2">
                        <span class="text-xs text-gray-500">{{ __('admin.last_updated') }}: <span
                                id="last-update">{{ now()->format('H:i:s') }}</span></span>
                        <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse" title="Auto-refresh active"></div>
                    </div>
                </div>
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
                                            {{ ucfirst($booking->status) }}
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
        <!-- Analytics and Charts Section -->
        {{-- <div class="grid gap-6 grid-cols-1 lg:grid-cols-2 mb-8">
            <!-- Booking Status Chart -->
            <div class="card">
                <header class="card-header bg-gradient-to-r from-blue-50 to-indigo-50">
                    <p class="card-header-title text-gray-800">
                        <span class="icon text-blue-600"><i class="mdi mdi-chart-donut"></i></span>
                        {{ __('admin.booking_status_chart') }}
                    </p>
                    <div class="card-header-icon">
                        <button class="button is-small is-white" onclick="refreshBookingChart()">
                            <i class="mdi mdi-refresh"></i>
                        </button>
                    </div>
                </header>
                <div class="card-content">
                    <canvas id="bookingStatusChart" width="400" height="200"></canvas>
                </div>
            </div>

            <!-- Monthly Revenue Chart -->
            <div class="card">
                <header class="card-header bg-gradient-to-r from-green-50 to-emerald-50">
                    <p class="card-header-title text-gray-800">
                        <span class="icon text-green-600"><i class="mdi mdi-chart-line"></i></span>
                        {{ __('admin.monthly_revenue_trend') }}
                    </p>
                    <div class="card-header-icon">
                        <button class="button is-small is-white" onclick="refreshRevenueChart()">
                            <i class="mdi mdi-refresh"></i>
                        </button>
                    </div>
                </header>
                <div class="card-content">
                    <canvas id="revenueChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div> --}}

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
            <div class="bg-white rounded-lg p-6 m-4 max-w-md w-full">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900" id="modalTitle">{{ __('admin.export_report') }}</h3>
                    <button onclick="closeExportModal()" class="text-gray-400 hover:text-gray-600">
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
                        <button type="button" onclick="closeExportModal()"
                            class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                            {{ __('admin.cancel') }}
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 flex items-center">
                            <i class="mdi mdi-download mr-2"></i>
                            {{ __('admin.export') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>



    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-refresh dashboard data every 3 seconds
            function refreshDashboardData() {
                fetch('{{ route('dashboard.data') }}', {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.error) {
                            console.error('Dashboard refresh error:', data.error);
                            return;
                        }

                        // Update stats cards
                        document.getElementById('total-customers').textContent = data.totalCustomers;
                        document.getElementById('total-revenue').textContent = data.totalRevenue;
                        document.getElementById('today-bookings').textContent = data.todayBookings;
                        document.getElementById('pending-bookings').textContent = data.pendingBookings;
                        document.getElementById('popular-service').textContent = data.popularServiceName;
                        document.getElementById('last-update').textContent = data.lastUpdate;

                        // Update today's bookings table
                        const tableBody = document.getElementById('today-bookings-table');
                        if (data.todayBookingsData.length > 0) {
                            tableBody.innerHTML = data.todayBookingsData.map((booking, index) => `
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${index + 1}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">${booking.customer_name}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">${booking.service_name}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">${booking.time}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    ${booking.status_badge}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">${booking.total_price}</div>
                                </td>
                            </tr>
                        `).join('');
                        } else {
                            tableBody.innerHTML = `
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <i class="mdi mdi-calendar-remove text-4xl mb-2 text-gray-300"></i>
                                        <p class="text-sm">Belum ada booking hari ini</p>
                                    </div>
                                </td>
                            </tr>
                        `;
                        }
                    })
                    .catch(error => {
                        console.error('Error refreshing dashboard:', error);
                        // Clear interval on persistent errors
                        if (window.dashboardRefreshInterval) {
                            clearInterval(window.dashboardRefreshInterval);
                        }
                    });
            }

            // Start auto-refresh every 3 seconds with authentication check
            function startDashboardRefresh() {
                window.dashboardRefreshInterval = setInterval(function() {
                    // Check if user is still authenticated
                    fetch('{{ route('dashboard.data') }}', {
                        method: 'HEAD',
                        credentials: 'same-origin'
                    }).then(response => {
                        if (response.ok) {
                            // User is still authenticated, refresh dashboard
                            refreshDashboardData();
                        } else {
                            // User is not authenticated, clear interval and redirect
                            clearInterval(window.dashboardRefreshInterval);
                            if (response.status === 401 || response.status === 419) {
                                window.location.href = '{{ route('login') }}';
                            }
                        }
                    }).catch(error => {
                        // Connection error, clear interval
                        clearInterval(window.dashboardRefreshInterval);
                        console.log('Dashboard auto-refresh stopped due to connection error');
                    });
                }, 3000);
            }

            // Start the refresh
            startDashboardRefresh();

            // Stop refresh when page is about to unload
            window.addEventListener('beforeunload', function() {
                if (window.dashboardRefreshInterval) {
                    clearInterval(window.dashboardRefreshInterval);
                }
            });

            // Helper function to safely get canvas context
            function getCanvasContext(id) {
                const canvas = document.getElementById(id);
                return canvas ? canvas.getContext('2d') : null;
            }

            // Booking Status Donut Chart
            const bookingCtx = getCanvasContext('bookingStatusChart');
            if (bookingCtx) {
                const bookingStatusChart = new Chart(bookingCtx, {
                    type: 'doughnut',
                    data: {
                        labels: [
                            @foreach ($bookingStats as $stat)
                                '{{ ucfirst($stat->status) }}',
                            @endforeach
                        ],
                        datasets: [{
                            data: [
                                @foreach ($bookingStats as $stat)
                                    {{ $stat->count }},
                                @endforeach
                            ],
                            backgroundColor: [
                                '#FCD34D', // Yellow for pending
                                '#60A5FA', // Blue for confirmed
                                '#34D399', // Green for completed
                                '#F87171' // Red for cancelled
                            ],
                            borderWidth: 0,
                            hoverOffset: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    padding: 20,
                                    usePointStyle: true,
                                    font: {
                                        size: 12
                                    }
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const label = context.label || '';
                                        const value = context.parsed;
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        const percentage = ((value / total) * 100).toFixed(1);
                                        return `${label}: ${value} (${percentage}%)`;
                                    }
                                }
                            }
                        },
                        cutout: '60%'
                    }
                });
            }

            // Monthly Revenue Line Chart
            const revenueCtx = getCanvasContext('revenueChart');
            if (revenueCtx) {
                const revenueChart = new Chart(revenueCtx, {
                    type: 'line',
                    data: {
                        labels: @json($monthLabels ?? []),
                        datasets: [{
                            label: 'Revenue (Rp)',
                            data: @json($revenueData ?? []),
                            borderColor: '#10B981',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                ticks: {
                                    callback: function(value) {
                                        return 'Rp' + value.toLocaleString('id-ID');
                                    }
                                }
                            }
                        }
                    }
                });
            }


            // Refresh functions
            window.refreshBookingChart = function() {
                Swal.fire({
                    title: 'Booking Chart',
                    text: 'Data booking berhasil di-refresh!',
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
                // Di sini bisa tambahkan logic refresh chart sebenarnya
                // fetchBookingData().then(data => updateChart(bookingStatusChart, data));
            };

            window.refreshRevenueChart = function() {
                Swal.fire({
                    title: 'Revenue Chart',
                    text: 'Data pendapatan berhasil di-refresh!',
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
                // Di sini bisa tambahkan logic refresh chart sebenarnya
                // fetchRevenueData().then(data => updateChart(revenueChart, data));
            };

            // Export Modal Functions
            window.openExportModal = function(type, title) {
                document.getElementById('exportType').value = type;
                document.getElementById('modalTitle').textContent = title;
                document.getElementById('exportModal').classList.remove('hidden');
                document.getElementById('exportModal').classList.add('flex');

                // Set current month and year as default
                document.getElementById('exportMonth').value = new Date().getMonth() + 1;
                document.getElementById('exportYear').value = new Date().getFullYear();
            };

            window.closeExportModal = function() {
                document.getElementById('exportModal').classList.add('hidden');
                document.getElementById('exportModal').classList.remove('flex');
            };

            // Handle form submission with loading indicator
            document.getElementById('exportForm').addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                const type = formData.get('type');

                Swal.fire({
                    title: 'Memproses Export...',
                    text: `Sedang menyiapkan laporan ${type}`,
                    icon: 'info',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Use XMLHttpRequest for better file download handling
                const xhr = new XMLHttpRequest();
                xhr.open('POST', this.action);
                xhr.responseType = 'blob';

                xhr.onload = function() {
                    if (xhr.status === 200) {
                        // Create download link
                        const blob = xhr.response;
                        const url = window.URL.createObjectURL(blob);
                        const a = document.createElement('a');
                        a.style.display = 'none';
                        a.href = url;

                        // Generate filename
                        const format = formData.get('format');
                        const extension = format === 'excel' ? 'xlsx' : (format === 'csv' ? 'csv' :
                            'pdf');
                        a.download = `${type}_report.${extension}`;

                        document.body.appendChild(a);
                        a.click();
                        document.body.removeChild(a);
                        window.URL.revokeObjectURL(url);

                        Swal.fire({
                            title: 'Berhasil!',
                            text: 'Laporan berhasil diunduh',
                            icon: 'success',
                            confirmButtonText: 'OK',
                            timer: 2000
                        });

                        closeExportModal();
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Terjadi kesalahan saat export',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                };

                xhr.onerror = function() {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Terjadi kesalahan saat export',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                };

                // Set headers
                xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]')
                    .getAttribute('content'));
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

                // Send form data
                xhr.send(formData);
            });

            // Auto-refresh data every 3 seconds - this is now handled by the main refresh function above
            // setInterval(function() {
            //     console.log('Chart auto-refresh running...');
            //     // Add your auto-refresh logic here
            //     // You could make AJAX calls to update the charts with fresh data
            // }, 3000);
        });
    </script>

    <style>
        /* Additional custom styles */
        .card {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .widget-label h1 {
            font-size: 2rem;
            font-weight: 700;
        }

        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }

        /* Chart container improvements */
        canvas {
            max-height: 300px !important;
        }

        /* Button hover effects */
        .btn-primary,
        .bg-green-500,
        .bg-purple-500,
        .bg-orange-500 {
            transition: all 0.2s ease;
        }

        .btn-primary:hover,
        .bg-green-500:hover,
        .bg-purple-500:hover,
        .bg-orange-500:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
    </style>
@endpush
