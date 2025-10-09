<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('admin.transaction_export') }}</title>
    <style>
        body {
            font-family: "DejaVu Sans", Arial, sans-serif;
            font-size: 11px;
            color: #333;
            margin: 20px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 18px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #2c3e50;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
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
            font-size: 12px;
        }

        tr:nth-child(even) {
            background-color: #f8f8f8;
        }

        .status-pending {
            color: orange;
            font-weight: bold;
        }

        .status-settlement {
            color: green;
            font-weight: bold;
        }

        .status-cancel {
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
    <h1>{{ __('admin.transaction_export') }}</h1>
    @if ($month && $year)
        <p style="text-align: center; margin-bottom: 20px; font-weight: bold;">
            {{ __('admin.period') }}: {{ \Carbon\Carbon::create($year, $month)->format('F Y') }}
        </p>
    @elseif($year)
        <p style="text-align: center; margin-bottom: 20px; font-weight: bold;">
            {{ __('admin.period') }}: {{ __('admin.year') }} {{ $year }}
        </p>
    @else
        <p style="text-align: center; margin-bottom: 20px; font-weight: bold;">
            {{ __('admin.period') }}: {{ __('admin.all_data') }}
        </p>
    @endif
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>{{ __('admin.name') }}</th>
                <th>{{ __('admin.email') }}</th>
                <th>{{ __('admin.date') }}</th>
                <th>{{ __('admin.order_id') }}</th>
                <th>{{ __('admin.type') }}</th>
                <th>{{ __('admin.status') }}</th>
                <th>{{ __('admin.amount') }}</th>
                <th>{{ __('admin.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transactions as $i => $transaction)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $transaction->name ?? '-' }}</td>
                    <td>{{ $transaction->email ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($transaction->transaction_time)->format('d/m/Y') }}</td>
                    <td>{{ $transaction->order_id ?? '-' }}</td>
                    <td>
                        @switch($transaction->payment_type)
                            @case('bank_transfer')
                                {{ __('admin.bank_transfer') }}
                            @break

                            @case('cash')
                                {{ __('admin.cash') }}
                            @break

                            @default
                                {{ __('admin.unknown') }}
                        @endswitch
                    </td>
                    <td
                        class="
                        @if ($transaction->transaction_status == 'settlement') status-settlement
                        @elseif ($transaction->transaction_status == 'pending') status-pending
                        @elseif ($transaction->transaction_status == 'cancel') status-cancel @endif
                    ">
                        {{ ucfirst($transaction->transaction_status) }}
                    </td>
                    <td>Rp {{ number_format($transaction->gross_amount, 0, ',', '.') }}</td>
                    <td>
                        <!-- Add any actions you want, for now it will just show "N/A" -->
                        N/A
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <footer>
        {{ __('admin.printed_on') }} {{ now()->format('d/m/Y H:i') }}
    </footer>
</body>

</html>
