<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction Export</title>
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
    <h1>Transaction Export</h1>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Date</th>
                <th>Order ID</th>
                <th>Type</th>
                <th>Status</th>
                <th>Amount</th>
                <th>Action</th>
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
                                Bank Transfer
                            @break

                            @case('cash')
                                Cash
                            @break

                            @default
                                Unknown
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
        Dicetak pada {{ now()->format('d/m/Y H:i') }}
    </footer>
</body>

</html>
