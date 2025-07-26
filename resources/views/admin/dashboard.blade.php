@extends('admin.layouts.app')

@section('content')
    <section class="is-title-bar">
        <div class="flex flex-col md:flex-row items-center justify-between space-y-6 md:space-y-0">
            <ul>
                <li>Admin</li>
                <li>Dashboard</li>
            </ul>
            <div class="text-sm text-gray-600">
                Selamat datang, {{ Auth::user()->name }} 
                @if(Auth::user()->hasRole('admin'))
                    (Administrator)
                @elseif(Auth::user()->hasRole('pegawai'))
                    (Pegawai)
                @endif
            </div>
        </div>
    </section>

    <section class="is-hero-bar">
        <div class="flex flex-col md:flex-row items-center justify-between space-y-6 md:space-y-0">
            <h1 class="title">
                Dashboard WOX Barbershop
            </h1>
            <div class="text-sm text-gray-500">
                {{ now()->format('d M Y, H:i') }}
            </div>
        </div>
    </section>

    <section class="section main-section">
        <div class="grid gap-6 grid-cols-1 md:grid-cols-3 mb-6">
            <div class="card">
                <div class="card-content">
                    <div class="flex items-center justify-between">
                        <div class="widget-label">
                            <h3>
                                Total Customers
                            </h3>
                            <h1>
                                {{ \App\Models\User::role('customer')->count() }}
                            </h1>
                        </div>
                        <span class="icon widget-icon text-green-500"><i
                                class="mdi mdi-account-multiple mdi-48px"></i></span>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-content">
                    <div class="flex items-center justify-between">
                        <div class="widget-label">
                            <h3>
                                Total Revenue
                            </h3>
                            <h1>
                                Rp{{ number_format(\App\Models\Transaction::where('payment_status', 'settlement')->sum('total_amount'), 0, ',', '.') }}
                            </h1>
                        </div>
                        <span class="icon widget-icon text-blue-500"><i class="mdi mdi-cart-outline mdi-48px"></i></span>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-content">
                    <div class="flex items-center justify-between">
                        <div class="widget-label">
                            <h3>
                                Total Bookings
                            </h3>
                            <h1>
                                {{ \App\Models\Booking::count() }}
                            </h1>
                        </div>
                        <span class="icon widget-icon text-red-500"><i class="mdi mdi-calendar-check mdi-48px"></i></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid gap-6 grid-cols-1 md:grid-cols-2 mb-6">
            <div class="card">
                <header class="card-header">
                    <p class="card-header-title">
                        <span class="icon"><i class="mdi mdi-trending-up"></i></span>
                        Booking Status
                    </p>
                </header>
                <div class="card-content">
                    <div class="space-y-2">
                        @php
                            $bookingStats = \App\Models\Booking::selectRaw('status, COUNT(*) as count')
                                ->groupBy('status')
                                ->get();
                        @endphp
                        @foreach($bookingStats as $stat)
                        <div class="flex justify-between items-center">
                            <span class="capitalize">{{ $stat->status }}</span>
                            <span class="font-semibold">{{ $stat->count }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="card">
                <header class="card-header">
                    <p class="card-header-title">
                        <span class="icon"><i class="mdi mdi-cash"></i></span>
                        Payment Status
                    </p>
                </header>
                <div class="card-content">
                    <div class="space-y-2">
                        @php
                            $paymentStats = \App\Models\Transaction::selectRaw('payment_status, COUNT(*) as count')
                                ->groupBy('payment_status')
                                ->get();
                        @endphp
                        @foreach($paymentStats as $stat)
                        <div class="flex justify-between items-center">
                            <span class="capitalize">{{ $stat->payment_status }}</span>
                            <span class="font-semibold">{{ $stat->count }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="card has-table">
            <header class="card-header">
                <p class="card-header-title">
                    <span class="icon"><i class="mdi mdi-calendar-today"></i></span>
                    Recent Bookings
                </p>
                <a href="#" class="card-header-icon">
                    <span class="icon"><i class="mdi mdi-reload"></i></span>
                </a>
            </header>
            <div class="card-content">
                <table>
                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Service</th>
                            <th>Date & Time</th>
                            <th>Status</th>
                            <th>Amount</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $recentBookings = \App\Models\Booking::with(['user', 'service'])
                                ->orderBy('created_at', 'desc')
                                ->limit(10)
                                ->get();
                        @endphp
                        @foreach($recentBookings as $booking)
                        <tr>
                            <td data-label="Customer">{{ $booking->user->name ?? 'N/A' }}</td>
                            <td data-label="Service">{{ $booking->service->name ?? 'N/A' }}</td>
                            <td data-label="Date & Time">
                                {{ $booking->booking_date ? \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') : 'N/A' }}<br>
                                <small class="text-gray-500">{{ $booking->booking_time ?? 'N/A' }}</small>
                            </td>
                            <td data-label="Status">
                                <span class="px-2 py-1 rounded text-xs
                                    @if($booking->status === 'completed') bg-green-100 text-green-800
                                    @elseif($booking->status === 'confirmed') bg-blue-100 text-blue-800
                                    @elseif($booking->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($booking->status === 'cancelled') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </td>
                            <td data-label="Amount">
                                Rp{{ number_format($booking->service->price ?? 0, 0, ',', '.') }}
                            </td>
                            <td class="actions-cell">
                                <div class="buttons right nowrap">
                                    <button class="button small green" type="button">
                                        <span class="icon"><i class="mdi mdi-eye"></i></span>
                                    </button>
                                    @if(Auth::user()->hasRole('admin'))
                                    <button class="button small blue" type="button">
                                        <span class="icon"><i class="mdi mdi-pencil"></i></span>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
