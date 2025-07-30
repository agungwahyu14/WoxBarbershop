<!DOCTYPE html>
<html>

<head>
    <title>Bukti Transaksi</title>
    <style>
        body {
            font-family: sans-serif;
        }

        .title {
            font-size: 18px;
            font-weight: bold;
        }

        .info {
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <div class="title">Bukti Transaksi</div>

    <div class="info">Order ID: {{ $transaction->order_id }}</div>
    <div class="info">Transaction Status: {{ $transaction->transaction_status }}</div>
    <div class="info">Payment Type: {{ $transaction->payment_type }}</div>

    @if (isset($transaction->va_numbers))
        <div class="info">Bank: {{ $transaction->va_numbers[0]->bank }}</div>
        <div class="info">VA Number: {{ $transaction->va_numbers[0]->va_number }}</div>
    @endif

    <div class="info">Gross Amount: Rp {{ number_format($transaction->gross_amount, 0, ',', '.') }}</div>
    <div class="info">Transaction Time: {{ $transaction->transaction_time }}</div>
</body>

</html>
