@extends('admin.exports.layout')

@section('title', 'Data Transaksi')

@section('content')
    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Order ID</th>
                <th>Pelanggan</th>
                <th>Layanan</th>
                <th>Jumlah</th>
                <th>Status</th>
                <th>Metode</th>
                <th>Tanggal Transaksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $index => $transaction)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $transaction->order_id }}</td>
                    <td>{{ $transaction->booking->user->name ?? '-' }}</td>
                    <td>{{ $transaction->booking->service->name ?? '-' }}</td>
                    <td>Rp{{ number_format($transaction->gross_amount, 0, ',', '.') }}</td>
                    <td>
                        <span class="badge badge-{{ $transaction->transaction_status }}">
                            {{ ucfirst($transaction->transaction_status) }}
                        </span>
                    </td>
                    <td>{{ $transaction->payment_type ?? '-' }}</td>
                    <td>{{ $transaction->transaction_time ? \Carbon\Carbon::parse($transaction->transaction_time)->format('d/m/Y H:i') : '-' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 20px; color: #999;">
                        Tidak ada data transaksi
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
