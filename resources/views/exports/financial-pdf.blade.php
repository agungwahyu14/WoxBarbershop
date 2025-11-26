<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>{{ __('export.financial_report') }}</title>
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
            width: 15%;
        }

        .col-order {
            width: 15%;
        }

        .col-customer {
            width: 18%;
        }

        .col-service {
            width: 18%;
        }

        .col-payment {
            width: 12%;
        }

        .col-status {
            width: 10%;
        }

        .col-amount {
            width: 7%;
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

        .total-row {
            font-weight: bold;
            background-color: #e7f3ff;
        }
    </style>

</head>

<body>
    <div class="header">
        <h1 class="title">{{ strtoupper(__('export.financial_report')) }}</h1>
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
        {{ __('export.total_transactions') }}: {{ $transactions->count() }} {{ __('export.transactions') }}<br>
        {{ __('export.total_revenue') }}: Rp{{ number_format($totalRevenue, 0, ',', '.') }}
    </div>

    <table>
        <thead>
            <tr>
                <th class="col-no">{{ __('export.no') }}</th>
                <th class="col-date">{{ __('export.date') }}</th>
                <th class="col-order">{{ __('export.order_id') }}</th>
                <th class="col-customer">{{ __('export.customer') }}</th>
                <th class="col-service">{{ __('export.service') }}</th>
                <th class="col-payment">{{ __('export.payment_method') }}</th>
                <th class="col-status">{{ __('export.status') }}</th>
                <th class="col-amount text-right">{{ __('export.amount_rp') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $index => $transaction)
                <tr>
                    <td class="col-no">{{ $index + 1 }}</td>
                    <td class="col-date">{{ \Carbon\Carbon::parse($transaction->created_at)->format('d/m/Y H:i') }}
                    </td>
                    <td class="col-order">{{ Str::limit($transaction->order_id, 12) }}</td>
                    <td class="col-customer">{{ Str::limit($transaction->booking->user->name ?? __('export.na'), 18) }}
                    </td>
                    <td class="col-service">
                        {{ Str::limit($transaction->booking->service->name ?? __('export.na'), 18) }}</td>
                    <td class="col-payment">{{ Str::limit($transaction->payment_type ?? __('export.na'), 10) }}</td>
                    <td class="col-status">{{ __('export.transaction_status_' . $transaction->transaction_status) }}
                    </td>
                    <td class="col-amount text-right">{{ number_format($transaction->gross_amount, 0, ',', '.') }}</td>
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

    <footer>
        {{ __('export.printed_on') }} {{ now()->format('d/m/Y H:i') }}
    </footer>
</body>

</html>
