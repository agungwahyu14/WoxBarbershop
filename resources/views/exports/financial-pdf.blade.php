<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Keuangan</title>
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

        .total-row {
            font-weight: bold;
            background-color: #e7f3ff;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="title">LAPORAN KEUANGAN</div>
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
        Total Transaksi: {{ $transactions->count() }} transaksi<br>
        Total Pendapatan: Rp{{ number_format($totalRevenue, 0, ',', '.') }}
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Order ID</th>
                <th>Pelanggan</th>
                <th>Layanan</th>
                <th>Metode Bayar</th>
                <th>Status</th>
                <th class="text-right">Jumlah (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $index => $transaction)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($transaction->created_at)->format('d/m/Y H:i') }}</td>
                    <td>{{ $transaction->order_id }}</td>
                    <td>{{ $transaction->booking->user->name ?? 'N/A' }}</td>
                    <td>{{ $transaction->booking->service->name ?? 'N/A' }}</td>
                    <td>{{ $transaction->payment_type ?? 'N/A' }}</td>
                    <td>{{ ucfirst($transaction->transaction_status) }}</td>
                    <td class="text-right">{{ number_format($transaction->gross_amount, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center;">Tidak ada data transaksi</td>
                </tr>
            @endforelse
            @if ($transactions->count() > 0)
                <tr class="total-row">
                    <td colspan="7" class="text-right"><strong>TOTAL:</strong></td>
                    <td class="text-right"><strong>Rp{{ number_format($totalRevenue, 0, ',', '.') }}</strong></td>
                </tr>
            @endif
        </tbody>
    </table>
</body>

</html>
