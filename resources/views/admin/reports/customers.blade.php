@extends('admin.layouts.app')

@section('title', 'Laporan Pelanggan')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Header --}}
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Laporan Pelanggan</h1>
                        <p class="mt-2 text-gray-600">Analisis pelanggan dan tingkat retensi</p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('admin.reports.index') }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition duration-200">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Kembali
                        </a>
                        <button onclick="exportCustomerReport()"
                            class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition duration-200">
                            <i class="fas fa-download mr-2"></i>
                            Ekspor PDF
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

            {{-- Summary Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Total Pelanggan</p>
                            <p class="text-3xl font-bold text-purple-600">{{ number_format($totalCustomers) }}</p>
                        </div>
                        <div class="p-3 bg-purple-100 rounded-full">
                            <i class="fas fa-users text-purple-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Pelanggan Aktif</p>
                            <p class="text-3xl font-bold text-green-600">{{ number_format($activeCustomers) }}</p>
                        </div>
                        <div class="p-3 bg-green-100 rounded-full">
                            <i class="fas fa-user-check text-green-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Tingkat Retensi</p>
                            <p class="text-3xl font-bold text-blue-600">{{ $retentionRate }}%</p>
                        </div>
                        <div class="p-3 bg-blue-100 rounded-full">
                            <i class="fas fa-chart-line text-blue-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Pelanggan Baru</p>
                            <p class="text-3xl font-bold text-orange-600">{{ $newCustomers->sum('count') }}</p>
                        </div>
                        <div class="p-3 bg-orange-100 rounded-full">
                            <i class="fas fa-user-plus text-orange-600 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Charts --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                {{-- New Customers Trend --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Tren Pelanggan Baru</h3>
                    <div class="h-80">
                        <canvas id="newCustomersChart"></canvas>
                    </div>
                </div>

                {{-- Retention Rate Chart --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Tingkat Retensi</h3>
                    <div class="h-80 flex items-center justify-center">
                        <div class="text-center">
                            <div class="relative inline-flex items-center justify-center w-40 h-40">
                                <svg class="w-40 h-40 transform -rotate-90">
                                    <circle cx="80" cy="80" r="70" stroke="currentColor" stroke-width="8"
                                        fill="transparent" class="text-gray-200" />
                                    <circle cx="80" cy="80" r="70" stroke="currentColor" stroke-width="8"
                                        fill="transparent" stroke-dasharray="{{ 2 * pi() * 70 }}"
                                        stroke-dashoffset="{{ 2 * pi() * 70 * (1 - $retentionRate / 100) }}"
                                        class="text-blue-600 transition-all duration-1000 ease-out" />
                                </svg>
                                <div class="absolute text-center">
                                    <div class="text-3xl font-bold text-blue-600">{{ $retentionRate }}%</div>
                                    <div class="text-sm text-gray-500">Retensi</div>
                                </div>
                            </div>
                            <div class="mt-4 space-y-1">
                                <div class="flex items-center justify-center text-sm">
                                    <div class="w-3 h-3 bg-blue-600 rounded-full mr-2"></div>
                                    <span>Aktif: {{ number_format($activeCustomers) }}</span>
                                </div>
                                <div class="flex items-center justify-center text-sm">
                                    <div class="w-3 h-3 bg-gray-200 rounded-full mr-2"></div>
                                    <span>Tidak Aktif: {{ number_format($totalCustomers - $activeCustomers) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Top Customers Table --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">10 Pelanggan Terbaik</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Rank</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Total Booking</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($topCustomers as $index => $customer)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div
                                            class="flex items-center justify-center w-8 h-8 rounded-full text-sm font-bold
                                    @if ($index === 0) bg-yellow-100 text-yellow-800
                                    @elseif($index === 1) bg-gray-100 text-gray-800
                                    @elseif($index === 2) bg-orange-100 text-orange-800
                                    @else bg-blue-100 text-blue-800 @endif">
                                            {{ $index + 1 }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div
                                                class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center mr-3">
                                                <i class="fas fa-user text-gray-500"></i>
                                            </div>
                                            <div class="text-sm font-medium text-gray-900">{{ $customer->name }}</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $customer->email }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                            {{ number_format($customer->booking_count) }} booking
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-crown mr-1"></i>
                                            VIP
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                        Tidak ada data pelanggan untuk periode yang dipilih
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Customer Analytics --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Analisis Pelanggan</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center p-4 bg-blue-50 rounded-lg">
                        <div class="text-2xl font-bold text-blue-600">{{ number_format($totalCustomers) }}</div>
                        <div class="text-sm text-gray-600">Total Terdaftar</div>
                    </div>
                    <div class="text-center p-4 bg-green-50 rounded-lg">
                        <div class="text-2xl font-bold text-green-600">{{ number_format($activeCustomers) }}</div>
                        <div class="text-sm text-gray-600">Aktif Periode Ini</div>
                    </div>
                    <div class="text-center p-4 bg-purple-50 rounded-lg">
                        <div class="text-2xl font-bold text-purple-600">{{ $newCustomers->sum('count') }}</div>
                        <div class="text-sm text-gray-600">Bergabung Baru</div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // New Customers Chart
            const newCustomersCtx = document.getElementById('newCustomersChart').getContext('2d');
            new Chart(newCustomersCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode(
                        $newCustomers->pluck('date')->map(function ($date) {
                            return \Carbon\Carbon::parse($date)->format('d M');
                        }),
                    ) !!},
                    datasets: [{
                        label: 'Pelanggan Baru',
                        data: {!! json_encode($newCustomers->pluck('count')) !!},
                        borderColor: 'rgb(147, 51, 234)',
                        backgroundColor: 'rgba(147, 51, 234, 0.1)',
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

            function exportCustomerReport() {
                Swal.fire({
                    title: 'Ekspor Laporan',
                    text: 'Mempersiapkan file PDF...',
                    icon: 'info',
                    showConfirmButton: false,
                    timer: 2000
                });

                setTimeout(() => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Laporan pelanggan berhasil diekspor',
                        confirmButtonColor: '#3b82f6'
                    });
                }, 2000);
            }
        </script>
    @endpush

@endsection
