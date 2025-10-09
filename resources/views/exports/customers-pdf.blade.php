<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>{{ __('export.customer_report') }}</title>
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
        <div class="title">{{ strtoupper(__('export.customer_report')) }}</div>
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
        {{ __('export.total_customers') }}: {{ $customers->count() }} {{ __('export.people') }}<br>
        {{ __('export.total_bookings') }}:
        {{ $customers->sum(function ($customer) {return $customer->bookings->count();}) }} {{ __('export.bookings') }}
    </div>

    <table>
        <thead>
            <tr>
                <th>{{ __('export.no') }}</th>
                <th>{{ __('export.name') }}</th>
                <th>{{ __('export.email') }}</th>
                <th>{{ __('export.phone_number') }}</th>
                <th>{{ __('export.registration_date') }}</th>
                <th class="text-center">{{ __('export.total_bookings') }}</th>
                <th>{{ __('export.last_status') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($customers as $index => $customer)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $customer->name }}</td>
                    <td>{{ $customer->email }}</td>
                    <td>{{ $customer->phone ?? __('export.na') }}</td>
                    <td>{{ \Carbon\Carbon::parse($customer->created_at)->format('d/m/Y') }}</td>
                    <td class="text-center">{{ $customer->bookings->count() }}</td>
                    <td>{{ $customer->bookings->last() ? __('booking.status_' . $customer->bookings->last()->status) : __('export.no_bookings_yet') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center;">{{ __('export.no_customer_data') }}</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
