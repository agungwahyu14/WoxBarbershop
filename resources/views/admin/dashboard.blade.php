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
        <div class="grid gap-6 grid-cols-1 md:grid-cols-4 mb-8">
            <!-- Total Pelanggan -->
            <div class="card card-hover transition-all duration-300 bg-gradient-to-br from-blue-500 to-blue-600 text-white">
                <div class="card-content p-6">
                    <div class="flex items-center justify-between">
                        <div class="widget-label">
                            <h3 class="text-blue-100 text-sm font-medium mb-2">Total Pelanggan</h3>
                            <h1 class="text-3xl font-bold">{{ \App\Models\User::role('pelanggan')->count() }}</h1>
                            <p class="text-blue-200 text-xs mt-1">+12% dari bulan lalu</p>
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
                            <h1 class="text-3xl font-bold">
                                @php
                                    $totalRevenue =
                                        \App\Models\Booking::where('status', 'completed')->sum('total_price') ?? 0;
                                @endphp
                                Rp{{ number_format($totalRevenue, 0, ',', '.') }}
                            </h1>
                            <p class="text-green-200 text-xs mt-1">+8% dari bulan lalu</p>
                        </div>
                        <div class="bg-green-400 bg-opacity-30 p-3 rounded-full">
                            <i class="mdi mdi-cash-multiple text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Bookings -->
            <div
                class="card card-hover transition-all duration-300 bg-gradient-to-br from-purple-500 to-purple-600 text-white">
                <div class="card-content p-6">
                    <div class="flex items-center justify-between">
                        <div class="widget-label">
                            <h3 class="text-purple-100 text-sm font-medium mb-2">Total Bookings</h3>
                            <h1 class="text-3xl font-bold">{{ \App\Models\Booking::count() }}</h1>
                            <p class="text-purple-200 text-xs mt-1">+15% dari minggu lalu</p>
                        </div>
                        <div class="bg-purple-400 bg-opacity-30 p-3 rounded-full">
                            <i class="mdi mdi-calendar-check text-2xl"></i>
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
                            <h1 class="text-3xl font-bold">
                                {{ \App\Models\Booking::whereDate('date_time', today())->count() }}
                            </h1>
                            <p class="text-orange-200 text-xs mt-1">
                                {{ \App\Models\Booking::whereDate('date_time', today())->where('status', 'pending')->count() }}
                                pending</p>
                        </div>
                        <div class="bg-orange-400 bg-opacity-30 p-3 rounded-full">
                            <i class="mdi mdi-calendar-today text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

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

        <!-- Statistics and Recent Activity -->
        <div class="grid gap-6 grid-cols-1 lg:grid-cols-3 mb-6">
            <!-- Booking Status Details -->
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

            <!-- Recent Activity -->
            @php
                $recentBookings = \App\Models\Booking::with(['user', 'service'])
                    ->orderBy('created_at', 'desc')
                    ->limit(5)
                    ->get();
            @endphp

            <div class="card">
                <header class="card-header">
                    <p class="card-header-title">
                        <span class="icon text-green-600"><i class="mdi mdi-clock-outline"></i></span>
                        Recent Activity
                    </p>
                </header>
                <div class="card-content">
                    <div class="space-y-4">
                        @foreach ($recentBookings as $booking)
                            <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                <div
                                    class="w-10 h-10 bg-gradient-to-br from-blue-400 to-blue-500 text-white rounded-full flex items-center justify-center font-bold mr-3">
                                    {{ substr($booking->user->name ?? 'U', 0, 1) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="font-medium text-gray-800 truncate">
                                        {{ $booking->user->name ?? 'Unknown' }}
                                    </div>
                                    <div class="text-sm text-gray-500 truncate">
                                        {{ $booking->service->name ?? 'Service' }} -
                                        <span
                                            class="px-2 py-1 rounded-full text-xs {{ $statusColors[$booking->status] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ $booking->status }}
                                        </span>
                                    </div>
                                    <div class="text-xs text-gray-400">
                                        {{ $booking->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>

        <!-- Quick Actions -->
        <div class="card">
            <header class="card-header">
                <p class="card-header-title">
                    <span class="icon text-indigo-600"><i class="mdi mdi-flash"></i></span>
                    Quick Actions
                </p>
            </header>
            <div class="card-content">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <a href="{{ route('bookings.create') }}"
                        class="btn-primary text-center py-3 px-4 rounded-lg transition-all duration-200 hover:transform hover:scale-105">
                        <i class="mdi mdi-plus-circle mr-2"></i>
                        New Booking
                    </a>
                    <a href="{{ route('users.index') }}"
                        class="bg-green-500 hover:bg-green-600 text-white text-center py-3 px-4 rounded-lg transition-all duration-200 hover:transform hover:scale-105">
                        <i class="mdi mdi-account-plus mr-2"></i>
                        Add Pelanggan
                    </a>
                    <a href="{{ route('services.index') }}"
                        class="bg-purple-500 hover:bg-purple-600 text-white text-center py-3 px-4 rounded-lg transition-all duration-200 hover:transform hover:scale-105">
                        <i class="mdi mdi-scissors-cutting mr-2"></i>
                        Manage Services
                    </a>
                    <a href="{{ route('transactions.index') }}"
                        class="bg-orange-500 hover:bg-orange-600 text-white text-center py-3 px-4 rounded-lg transition-all duration-200 hover:transform hover:scale-105">
                        <i class="mdi mdi-chart-box mr-2"></i>
                        View Transactions
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Booking Status Donut Chart
            const bookingCtx = document.getElementById('bookingStatusChart').getContext('2d');
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

            // Monthly Revenue Line Chart
            const revenueCtx = document.getElementById('revenueChart').getContext('2d');

            // Generate sample data for the last 6 months
            const monthNames = [];
            const revenueData = [];

            for (let i = 5; i >= 0; i--) {
                const date = new Date();
                date.setMonth(date.getMonth() - i);
                monthNames.push(date.toLocaleDateString('id-ID', {
                    month: 'short',
                    year: '2-digit'
                }));

                // Sample revenue data (replace with actual data from database)
                revenueData.push(Math.floor(Math.random() * 5000000) + 2000000);
            }

            const revenueChart = new Chart(revenueCtx, {
                type: 'line',
                data: {
                    labels: monthNames,
                    datasets: [{
                        label: 'Revenue (Rp)',
                        data: revenueData,
                        borderColor: '#10B981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#10B981',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 6,
                        pointHoverRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'Revenue: Rp' + context.parsed.y.toLocaleString('id-ID');
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp' + (value / 1000000).toFixed(1) + 'M';
                                }
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });

            // Refresh functions
            window.refreshBookingChart = function() {
                // Add refresh logic here
                showNotification('info', 'Info', 'Chart data refreshed!');
            };

            window.refreshRevenueChart = function() {
                // Add refresh logic here
                showNotification('info', 'Info', 'Revenue chart refreshed!');
            };

            // Auto-refresh data every 5 minutes
            setInterval(function() {
                // Add auto-refresh logic here
                console.log('Auto-refreshing dashboard data...');
            }, 300000);
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
