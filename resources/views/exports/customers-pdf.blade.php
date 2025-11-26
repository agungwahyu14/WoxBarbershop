<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>{{ __('export.customer_report') }}</title>
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

        .col-name {
            width: 20%;
        }

        .col-email {
            width: 25%;
        }

        .col-phone {
            width: 12%;
        }

        .col-date {
            width: 12%;
        }

        .col-bookings {
            width: 10%;
        }

        .col-status {
            width: 16%;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
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
        <h1 class="title">{{ strtoupper(__('export.customer_report')) }}</h1>
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
        {{ __('export.total_customers') }}: {{ $customers->count() }} {{ __('export.people') }}<br>
        {{ __('export.total_bookings') }}:
        {{ $customers->sum(function ($customer) {return $customer->bookings->count();}) }} {{ __('export.bookings') }}
    </div>

    <table>
        <thead>
            <tr>
                <th class="col-no">{{ __('export.no') }}</th>
                <th class="col-name">{{ __('export.name') }}</th>
                <th class="col-email">{{ __('export.email') }}</th>
                <th class="col-phone">{{ __('export.phone_number') }}</th>
                <th class="col-date">{{ __('export.registration_date') }}</th>
                <th class="col-bookings text-center">{{ __('export.total_bookings') }}</th>
                <th class="col-status">{{ __('export.last_status') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($customers as $index => $customer)
                <tr>
                    <td class="col-no">{{ $index + 1 }}</td>
                    <td class="col-name">{{ Str::limit($customer->name, 25) }}</td>
                    <td class="col-email">{{ Str::limit($customer->email, 30) }}</td>
                    <td class="col-phone">{{ $customer->phone ?? __('export.na') }}</td>
                    <td class="col-date">{{ \Carbon\Carbon::parse($customer->created_at)->format('d/m/Y') }}</td>
                    <td class="col-bookings text-center">{{ $customer->bookings->count() }}</td>
                    <td class="col-status">
                        {{ $customer->bookings->last() ? __('booking.status_' . $customer->bookings->last()->status) : __('export.no_bookings_yet') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center;">{{ __('export.no_customer_data') }}</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <footer>
        {{ __('export.printed_on') }} {{ now()->format('d/m/Y H:i') }}
    </footer>
</body>

</html>
