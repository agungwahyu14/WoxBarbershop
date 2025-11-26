<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>{{ __('export.booking_report') }}</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 20px;
        }

        body {
            font-family: "DejaVu Sans", Arial, sans-serif;
            font-size: 11px;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #2c3e50;
        }

        .subtitle {
            color: #666;
            font-size: 11px;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .summary {
            margin: 20px 0;
            padding: 15px;
            background-color: #f8f8f8;
            font-size: 11px;
            border: 1px solid #ddd;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
            font-size: 10px;
        }

        th,
        td {
            border: 1px solid #bbb;
            padding: 6px 8px;
            text-align: left;
        }

        th {
            background-color: #2c3e50;
            color: white;
            font-size: 11px;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f8f8f8;
        }

        /* Column widths optimized for landscape A4 */
        .col-no {
            width: 5%;
        }

        .col-date {
            width: 12%;
        }

        .col-time {
            width: 8%;
        }

        .col-customer {
            width: 18%;
        }

        .col-service {
            width: 18%;
        }

        .col-status {
            width: 12%;
        }

        .col-price {
            width: 12%;
        }

        .col-notes {
            width: 15%;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .status-pending {
            color: orange;
            font-weight: bold;
        }

        .status-confirmed {
            color: blue;
            font-weight: bold;
        }

        .status-completed {
            color: green;
            font-weight: bold;
        }

        .status-cancelled {
            color: red;
            font-weight: bold;
        }

        footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #777;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1 class="title">{{ strtoupper(__('export.booking_report')) }}</h1>
        @if ($month && $year)
            <p class="subtitle">
                {{ __('export.period') }}: {{ \Carbon\Carbon::create($year, $month)->format('F Y') }}
            </p>
        @elseif($year)
            <p class="subtitle">
                {{ __('export.period') }}: {{ __('export.year') }} {{ $year }}
            </p>
        @else
            <p class="subtitle">
                {{ __('export.period') }}: {{ \Carbon\Carbon::now()->format('F Y') }}
            </p>
        @endif
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
                <th class="col-no">{{ __('export.no') }}</th>
                <th class="col-date">{{ __('export.date') }}</th>
                <th class="col-time">{{ __('export.time') }}</th>
                <th class="col-customer">{{ __('export.customer') }}</th>
                <th class="col-service">{{ __('export.service') }}</th>
                <th class="col-status">{{ __('export.status') }}</th>
                <th class="col-price text-right">{{ __('export.price_rp') }}</th>
                <th class="col-notes">{{ __('export.notes') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bookings as $index => $booking)
                <tr>
                    <td class="col-no">{{ $index + 1 }}</td>
                    <td class="col-date">{{ \Carbon\Carbon::parse($booking->date_time)->format('d/m/Y') }}</td>
                    <td class="col-time">{{ \Carbon\Carbon::parse($booking->date_time)->format('H:i') }}</td>
                    <td class="col-customer">{{ Str::limit($booking->user->name ?? __('export.na'), 20) }}</td>
                    <td class="col-service">{{ Str::limit($booking->service->name ?? __('export.na'), 20) }}</td>
                    <td class="col-status status-{{ $booking->status }}">
                        {{ __('booking.status_' . $booking->status) }}
                    </td>
                    <td class="col-price text-right">{{ number_format($booking->total_price, 0, ',', '.') }}</td>
                    <td class="col-notes">{{ Str::limit($booking->notes ?? '-', 25) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center;">{{ __('export.no_booking_data') }}</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <footer>
        {{ __('export.printed_on') }} {{ now()->format('d/m/Y H:i') }}
    </footer>
</body>

</html>
