@extends('admin.exports.layout')

@section('title', 'Data Booking')

@section('content')
    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Pelanggan</th>
                <th>Email</th>
                <th>Layanan</th>
                <th>Tanggal & Waktu</th>
                <th>Status</th>
                <th>Total Harga</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $index => $booking)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $booking->user->name ?? '-' }}</td>
                    <td>{{ $booking->user->email ?? '-' }}</td>
                    <td>{{ $booking->service->name ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($booking->date_time)->format('d/m/Y H:i') }}</td>
                    <td>
                        <span class="badge badge-{{ $booking->status }}">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </td>
                    <td>Rp{{ number_format($booking->total_price, 0, ',', '.') }}</td>
                    <td>{{ $booking->notes ?: '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 20px; color: #999;">
                        Tidak ada data booking
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
