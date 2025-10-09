<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>{{ __('export.booking_report') }}</title>
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
        <div class="title">{{ strtoupper(__('export.booking_report')) }}</div>
        <div class="title">WOX BARBERSHOP</div>
        <div class="subtitle">
            @if ($month && $year)
                {{ __('export.period') }}: {{ \Carbon\Carbon::create($year, $month)->format('F Y') }}
            @elseif($year)
                {{ __('export.period') }}: {{ __('export.year') }} {{ $year }}
            @else
                {{ __('export.period') }}: {{ \Carbon\Carbon::now()->format('F Y') }}
            @endif
        </div>
        <div class="subtitle">{{ __('export.printed_on') }}: {{ \Carbon\Carbon::now()->format('d F Y, H:i') }} WIB</div>
    </div>

    <div class="summary">
        <strong>{{ __('export.summary') }}:</strong><br>
        {{ __('export.total_bookings') }}: {{ $bookings->count() }} {{ __('export.bookings') }}<br>
        {{ __('booking.status_pending') }}: {{ $bookings->where('status', 'pending')->count() }}<br>
        {{ __('booking.status_confirmed') }}: {{ $bookings->where('status', 'confirmed')->count() }}<br>
        {{ __('booking.status_completed') }}: {{ $bookings->where('status', 'completed')->count() }}<br>
        {{ __('booking.status_cancelled') }}: {{ $bookings->where('status', 'cancelled')->count() }}
    </div>

    <table>
        <thead>
            <tr>
                <th>{{ __('export.no') }}</th>
                <th>{{ __('export.date') }}</th>
                <th>{{ __('export.time') }}</th>
                <th>{{ __('export.customer') }}</th>
                <th>{{ __('export.service') }}</th>
                <th>{{ __('export.status') }}</th>
                <th class="text-right">{{ __('export.price_rp') }}</th>
                <th>{{ __('export.notes') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bookings as $index => $booking)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($booking->date_time)->format('d/m/Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($booking->date_time)->format('H:i') }}</td>
                    <td>{{ $booking->user->name ?? __('export.na') }}</td>
                    <td>{{ $booking->service->name ?? __('export.na') }}</td>
                    <td>
                        <span class="status status-{{ $booking->status }}">
                            {{ __('booking.status_' . $booking->status) }}
                        </span>
                    </td>
                    <td class="text-right">{{ number_format($booking->total_price, 0, ',', '.') }}</td>
                    <td>{{ $booking->notes ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center;">{{ __('export.no_booking_data') }}</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
