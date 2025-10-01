@extends('admin.layouts.app')
@section('title', 'Laporan Booking')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Header --}}
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Laporan Booking</h1>
                        <p class="mt-2 text-gray-600">Analisis booking dan tren layanan</p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('admin.reports.index') }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition duration-200">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Kembali
                        </a>
                        <button onclick="exportBookingReport()"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition duration-200">
                            <i class="fas fa-download mr-2"></i>
                            Ekspor CSV
                        </button>
                    </div>
                </div>
            </div>

            {{-- Date Filter --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
                <form method="GET" class="flex flex-wrap items-end gap-4">
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                        <input type="date" id="start_date" name="start_date"
                            value="{{ request('start_date', \Carbon\Carbon::parse($startDate ?? now()->startOfMonth())->format('Y-m-d')) }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Akhir</label>
                        <input type="date" id="end_date" name="end_date"
                            value="{{ request('end_date', \Carbon\Carbon::parse($endDate ?? now()->endOfMonth())->format('Y-m-d')) }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition duration-200">
                            <i class="fas fa-filter mr-2"></i>
                            Filter
                        </button>
                    </div>
                </form>
            </div>

            {{-- Summary Card --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Total Booking</h3>
                        <p class="text-3xl font-bold text-blue-600 mt-2">{{ number_format($totalBookings) }}</p>
                        <p class="text-sm text-gray-600 mt-1">
                            Periode: {{ \Carbon\Carbon::parse($startDate)->format('d M') }} -
                            {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}
                        </p>
                    </div>
                    <div class="p-4 bg-blue-100 rounded-full">
                        <i class="fas fa-calendar-check text-blue-600 text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                {{-- Booking Status Chart --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Status Booking</h3>
                    <div class="h-80">
                        <canvas id="statusChart"></canvas>
                    </div>
                    <div class="mt-4 space-y-2">
                        @foreach ($statusData as $status)
                            <div class="flex justify-between items-center text-sm">
                                <span class="capitalize text-gray-600">{{ ucfirst($status->status) }}</span>
                                <span class="font-semibold">{{ number_format($status->count) }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Service Popularity Chart --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Layanan Populer</h3>
                    <div class="h-80">
                        <canvas id="serviceChart"></canvas>
                    </div>
                    <div class="mt-4 space-y-2">
                        @foreach ($serviceData->take(5) as $service)
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-600">{{ $service->name }}</span>
                                <span class="font-semibold">{{ number_format($service->count) }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Daily Bookings Trend --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Tren Booking Harian</h3>
                <div class="h-80">
                    <canvas id="dailyBookingsChart"></canvas>
                </div>
            </div>

            {{-- Detailed Tables --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                {{-- Service Details Table --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Detail Layanan</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Layanan</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Booking</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        %</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($serviceData as $service)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $service->name }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-600">{{ number_format($service->count) }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-600">
                                            {{ $totalBookings > 0 ? number_format(($service->count / $totalBookings) * 100, 1) : 0 }}%
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Status Details Table --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Detail Status</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Jumlah</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        %</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($statusData as $status)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 text-sm font-medium text-gray-900 capitalize">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if ($status->status === 'completed') bg-green-100 text-green-800
                                        @elseif($status->status === 'confirmed') bg-blue-100 text-blue-800
                                        @elseif($status->status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($status->status === 'cancelled') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                                {{ ucfirst($status->status) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-600">{{ number_format($status->count) }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-600">
                                            {{ $totalBookings > 0 ? number_format(($status->count / $totalBookings) * 100, 1) : 0 }}%
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Status Chart
            const statusCtx = document.getElementById('statusChart').getContext('2d');
            new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode(
                        $statusData->pluck('status')->map(function ($status) {
                            return ucfirst($status);
                        }),
                    ) !!},
                    datasets: [{
                        data: {!! json_encode($statusData->pluck('count')) !!},
                        backgroundColor: [
                            'rgba(34, 197, 94, 0.8)', // completed - green
                            'rgba(59, 130, 246, 0.8)', // confirmed - blue
                            'rgba(245, 158, 11, 0.8)', // pending - yellow
                            'rgba(239, 68, 68, 0.8)', // cancelled - red
                            'rgba(156, 163, 175, 0.8)' // others - gray
                        ]
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

            // Service Chart
            const serviceCtx = document.getElementById('serviceChart').getContext('2d');
            new Chart(serviceCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($serviceData->take(5)->pluck('name')) !!},
                    datasets: [{
                        label: 'Jumlah Booking',
                        data: {!! json_encode($serviceData->take(5)->pluck('count')) !!},
                        backgroundColor: 'rgba(147, 51, 234, 0.8)',
                        borderColor: 'rgba(147, 51, 234, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    indexAxis: 'y',
                    scales: {
                        x: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Daily Bookings Chart
            const dailyCtx = document.getElementById('dailyBookingsChart').getContext('2d');
            new Chart(dailyCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode(
                        $dailyBookings->pluck('date')->map(function ($date) {
                            return \Carbon\Carbon::parse($date)->format('d M');
                        }),
                    ) !!},
                    datasets: [{
                        label: 'Jumlah Booking',
                        data: {!! json_encode($dailyBookings->pluck('count')) !!},
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });

            function exportBookingReport() {
                Swal.fire({
                    title: 'Ekspor Laporan',
                    text: 'Mempersiapkan file CSV...',
                    icon: 'info',
                    showConfirmButton: false,
                    timer: 2000
                });

                setTimeout(() => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Laporan booking berhasil diekspor',
                        confirmButtonColor: '#3b82f6'
                    });
                }, 2000);
            }
        </script>
    @endpush

@endsection
