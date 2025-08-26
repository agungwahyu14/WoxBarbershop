@extends('admin.layouts.app')

@section('content')
    <section class="is-title-bar">
        <div class="flex flex-col md:flex-row items-center justify-between space-y-6 md:space-y-0">
            <ul>
                <li>Admin</li>
                <li>Dashboard</li>
            </ul>
            <div class="text-sm text-gray-600 flex items-center">
                <i class="fas fa-user-circle mr-2"></i>
                Selamat datang, <span class="font-semibold ml-1">{{ Auth::user()->name }}</span>
                @if (Auth::user()->hasRole('admin'))
                    <span class="ml-2 px-2 py-1 bg-red-100 text-red-700 rounded-full text-xs">(Administrator)</span>
                @elseif(Auth::user()->hasRole('pegawai'))
                    <span class="ml-2 px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-xs">(Pegawai)</span>
                @endif
            </div>
        </div>
    </section>

    <section class="is-hero-bar">
        <div class="flex flex-col md:flex-row items-center justify-between space-y-6 md:space-y-0">
            <div>
                <h1 class="title text-3xl font-bold text-gray-800">
                    Dashboard WOX Barbershop
                </h1>
                <p class="text-gray-600 mt-2">Monitor performa bisnis barbershop Anda</p>
            </div>
            <div class="text-right">
                <div class="text-sm text-gray-500">{{ now()->format('l, d F Y') }}</div>
                <div class="text-lg font-semibold text-gray-800">{{ now()->format('H:i') }} WIB</div>
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
                            <h3 class=" text-sm font-medium mb-2">Total Pelanggan</h3>
                            <h1 class="text-3xl font-bold" id="total-customers">{{ $totalCustomers }}</h1>

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
                            <h3 class="text-green-100 text-sm font-medium mb-2">Total Revenue</h3>
                            <h1 class="text-3xl font-bold" id="total-revenue">
                                Rp{{ number_format($totalRevenue, 0, ',', '.') }}
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
                            <h3 class="text-orange-100 text-sm font-medium mb-2">Today's Bookings</h3>
                            <h1 class="text-3xl font-bold" id="today-bookings">
                                {{ $todayBookings }}
                            </h1>
                            <p class="text-orange-200 text-xs mt-1">
                                <span id="pending-bookings">{{ $pendingBookings }}</span> pending
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
                            <h3 class="text-pink-100 text-sm font-medium mb-2">Most Popular Service</h3>
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
                    Booking Hari Ini
                </p>
                <div class="card-header-icon">
                    <div class="flex items-center space-x-2">
                        <span class="text-xs text-gray-500">Last updated: <span
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
                                    No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Pelanggan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Layanan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Waktu</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Total</th>
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
                                            <p class="text-sm">Belum ada booking hari ini</p>
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
            <!-- Charts Row -->
            <div class="grid gap-6 grid-cols-1 lg:grid-cols-2 mb-8">
                <!-- Booking Status Chart -->
                <div class="card">
                    <header class="card-header bg-gradient-to-r from-blue-50 to-indigo-50">
                        <p class="card-header-title text-gray-800">
                            <span class="icon text-blue-600"><i class="mdi mdi-chart-donut"></i></span>
                            Booking Status Distribution
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
                            Monthly Revenue Trend
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
            </div>

            <div class="grid gap-6 grid-cols-1 lg:grid-cols-2 mb-8">
                <!-- Booking Status Chart -->
                <div class="card">
                    <header class="card-header bg-gradient-to-r from-purple-50 to-purple-100">
                        <p class="card-header-title text-gray-800">
                            <span class="icon text-purple-600"><i class="mdi mdi-chart-bar"></i></span>
                            User Activity (Monthly)
                        </p>
                    </header>
                    <div class="card-content">
                        <canvas id="userActivityChart" height="400"></canvas>
                    </div>
                </div>

                <!-- Monthly Revenue Chart -->
                <div class="card">
                    <header class="card-header">
                        <p class="card-header-title">
                            <span class="icon text-blue-600"><i class="mdi mdi-trending-up"></i></span>
                            Booking Status
                        </p>
                    </header>
                    <div class="card-content">
                        <div class="space-y-4">
                            @php
                                $bookingStats = \App\Models\Booking::selectRaw('status, COUNT(*) as count')
                                    ->groupBy('status')
                                    ->get();
                                $total = $bookingStats->sum('count');

                                $statusColors = [
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'confirmed' => 'bg-blue-100 text-blue-800',
                                    'completed' => 'bg-green-100 text-green-800',
                                    'cancelled' => 'bg-red-100 text-red-800',
                                ];
                            @endphp
                            @foreach ($bookingStats as $stat)
                                @php
                                    $percentage = $total > 0 ? round(($stat->count / $total) * 100, 1) : 0;
                                @endphp
                                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                    <div class="flex items-center">
                                        <span
                                            class="px-3 py-1 rounded-full text-xs font-medium {{ $statusColors[$stat->status] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ ucfirst($stat->status) }}
                                        </span>
                                    </div>
                                    <div class="text-right">
                                        <div class="font-semibold text-gray-800">{{ $stat->count }}</div>
                                        <div class="text-xs text-gray-500">{{ $percentage }}%</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endrole

        <!-- Statistics and Recent Activity -->
        <div class="grid gap-6 grid-cols-1 lg:grid-cols-3 mb-6">
            <!-- Booking Status Details -->
            <!-- Popular Services -->
            <div class="card">
                <header class="card-header">
                    <p class="card-header-title">
                        <span class="icon text-purple-600"><i class="mdi mdi-star-circle"></i></span>
                        Popular Services
                    </p>
                </header>
                <div class="card-content">
                    <div class="space-y-4">
                        @php
                            $popularServices = \App\Models\Booking::select('service_id')
                                ->selectRaw('COUNT(*) as booking_count')
                                ->with('service')
                                ->groupBy('service_id')
                                ->orderBy('booking_count', 'desc')
                                ->limit(5)
                                ->get();
                        @endphp
                        @foreach ($popularServices as $index => $bookingService)
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center">
                                    <div
                                        class="w-8 h-8 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-sm font-bold mr-3">
                                        {{ $index + 1 }}
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-800">
                                            {{ $bookingService->service->name ?? 'N/A' }}</div>
                                        <div class="text-sm text-gray-500">
                                            Rp{{ number_format($bookingService->service->price ?? 0, 0, ',', '.') }}
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="font-semibold text-gray-800">{{ $bookingService->booking_count }}</div>
                                    <div class="text-xs text-gray-500">bookings</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
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
                    });
            }

            // Start auto-refresh every 3 seconds
            setInterval(refreshDashboardData, 3000);

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

            // Popular Service Chart (only if canvas exists)
            const serviceCtx = getCanvasContext('popularServiceChart');
            if (serviceCtx) {
                const serviceChart = new Chart(serviceCtx, {
                    type: 'pie',
                    data: {
                        labels: [
                            @foreach ($popularServices as $service)
                                '{{ $service->service->name }}',
                            @endforeach
                        ],
                        datasets: [{
                            data: [
                                @foreach ($popularServices as $service)
                                    {{ $service->count }},
                                @endforeach
                            ],
                            backgroundColor: ['#F87171', '#60A5FA', '#34D399', '#FBBF24', '#A78BFA']
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                });
            }

            // User Activity Bar Chart
            const userCtx = getCanvasContext('userActivityChart');
            if (userCtx) {
                const userChart = new Chart(userCtx, {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode($userActivity->pluck('month') ?? []) !!},
                        datasets: [{
                            label: 'New Users',
                            data: {!! json_encode($userActivity->pluck('count') ?? []) !!},
                            backgroundColor: '#8B5CF6'
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
                                beginAtZero: true
                            },
                            x: {
                                grid: {
                                    display: false
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
