<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Pelanggan</title>
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

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="title">LAPORAN PELANGGAN</div>
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
        Total Pelanggan: {{ $customers->count() }} orang<br>
        Total Booking: {{ $customers->sum(function ($customer) {return $customer->bookings->count();}) }} booking
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Email</th>
                <th>No. Telepon</th>
                <th>Tanggal Daftar</th>
                <th class="text-center">Total Booking</th>
                <th>Status Terakhir</th>
            </tr>
        </thead>
        <tbody>
            @forelse($customers as $index => $customer)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $customer->name }}</td>
                    <td>{{ $customer->email }}</td>
                    <td>{{ $customer->phone ?? 'N/A' }}</td>
                    <td>{{ \Carbon\Carbon::parse($customer->created_at)->format('d/m/Y') }}</td>
                    <td class="text-center">{{ $customer->bookings->count() }}</td>
                    <td>{{ $customer->bookings->last()->status ?? 'Belum ada booking' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center;">Tidak ada data pelanggan</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
