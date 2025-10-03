<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Booking</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .subtitle {
            color: #666;
            margin-bottom: 20px;
        }

        .summary {
            margin: 20px 0;
            padding: 15px;
            background-color: #f5f5f5;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .text-right {
            text-align: right;
        }

        .status {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-confirmed {
            background-color: #d4edda;
            color: #155724;
        }

        .status-completed {
            background-color: #d1ecf1;
            color: #0c5460;
        }

        .status-cancelled {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="title">LAPORAN BOOKING</div>
        <div class="title">WOX BARBERSHOP</div>
        <div class="subtitle">
            @if ($month && $year)
                Periode: {{ \Carbon\Carbon::create($year, $month)->format('F Y') }}
            @elseif($year)
                Periode: Tahun {{ $year }}
            @else
                Periode: {{ \Carbon\Carbon::now()->format('F Y') }}
            @endif
        </div>
        <div class="subtitle">Dicetak pada: {{ \Carbon\Carbon::now()->format('d F Y, H:i') }} WIB</div>
    </div>

    <div class="summary">
        <strong>Ringkasan:</strong><br>
        Total Booking: {{ $bookings->count() }} booking<br>
        Pending: {{ $bookings->where('status', 'pending')->count() }}<br>
        Confirmed: {{ $bookings->where('status', 'confirmed')->count() }}<br>
        Completed: {{ $bookings->where('status', 'completed')->count() }}<br>
        Cancelled: {{ $bookings->where('status', 'cancelled')->count() }}
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Waktu</th>
                <th>Pelanggan</th>
                <th>Layanan</th>
                <th>Status</th>
                <th class="text-right">Harga (Rp)</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bookings as $index => $booking)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($booking->date_time)->format('d/m/Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($booking->date_time)->format('H:i') }}</td>
                    <td>{{ $booking->user->name ?? 'N/A' }}</td>
                    <td>{{ $booking->service->name ?? 'N/A' }}</td>
                    <td>
                        <span class="status status-{{ $booking->status }}">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </td>
                    <td class="text-right">{{ number_format($booking->total_price, 0, ',', '.') }}</td>
                    <td>{{ $booking->notes ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center;">Tidak ada data booking</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
