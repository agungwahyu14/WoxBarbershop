<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>{{ __('export.financial_report') }}</title>
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
        <div class="title">{{ strtoupper(__('export.financial_report')) }}</div>
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
        {{ __('export.total_transactions') }}: {{ $transactions->count() }} {{ __('export.transactions') }}<br>
        {{ __('export.total_revenue') }}: Rp{{ number_format($totalRevenue, 0, ',', '.') }}
    </div>

    <table>
        <thead>
            <tr>
                <th>{{ __('export.no') }}</th>
                <th>{{ __('export.date') }}</th>
                <th>{{ __('export.order_id') }}</th>
                <th>{{ __('export.customer') }}</th>
                <th>{{ __('export.service') }}</th>
                <th>{{ __('export.payment_method') }}</th>
                <th>{{ __('export.status') }}</th>
                <th class="text-right">{{ __('export.amount_rp') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $index => $transaction)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($transaction->created_at)->format('d/m/Y H:i') }}</td>
                    <td>{{ $transaction->order_id }}</td>
                    <td>{{ $transaction->booking->user->name ?? __('export.na') }}</td>
                    <td>{{ $transaction->booking->service->name ?? __('export.na') }}</td>
                    <td>{{ $transaction->payment_type ?? __('export.na') }}</td>
                    <td>{{ __('export.transaction_status_' . $transaction->transaction_status) }}</td>
                    <td class="text-right">{{ number_format($transaction->gross_amount, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center;">{{ __('export.no_transaction_data') }}</td>
                </tr>
            @endforelse
            @if ($transactions->count() > 0)
                <tr class="total-row">
                    <td colspan="7" class="text-right"><strong>{{ strtoupper(__('export.total')) }}:</strong></td>
                    <td class="text-right"><strong>Rp{{ number_format($totalRevenue, 0, ',', '.') }}</strong></td>
                </tr>
            @endif
        </tbody>
    </table>
</body>

</html>
