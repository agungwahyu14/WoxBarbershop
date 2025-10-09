<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('receipt.invoice_title') }} - WOX Barbershop</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
        }

        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 3px solid #000;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .company-info {
            flex: 1;
        }

        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #000;
            margin-bottom: 5px;
        }

        .company-tagline {
            font-size: 12px;
            color: #666;
            margin-bottom: 10px;
        }

        .company-details {
            font-size: 10px;
            color: #666;
            line-height: 1.3;
        }

        .invoice-title {
            text-align: right;
            flex: 1;
        }

        .invoice-title h1 {
            font-size: 28px;
            font-weight: bold;
            color: #000;
            margin: 0;
        }

        .invoice-meta {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }

        .invoice-details,
        .customer-details {
            flex: 1;
        }

        .invoice-details {
            margin-right: 40px;
        }

        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #000;
            margin-bottom: 10px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }

        .info-label {
            font-weight: bold;
            min-width: 120px;
        }

        .info-value {
            flex: 1;
            text-align: right;
        }

        .services-table {
            width: 100%;
            border-collapse: collapse;
            margin: 30px 0;
        }

        .services-table th,
        .services-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .services-table th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #000;
        }

        .services-table .text-right {
            text-align: right;
        }

        .services-table .text-center {
            text-align: center;
        }

        .total-section {
            margin-top: 30px;
            border-top: 2px solid #000;
            padding-top: 15px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .grand-total {
            font-size: 18px;
            font-weight: bold;
            color: #000;
            border-top: 1px solid #333;
            padding-top: 10px;
        }

        .payment-info {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-top: 30px;
        }

        .payment-info h4 {
            margin: 0 0 10px 0;
            color: #000;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-success {
            background-color: #d4edda;
            color: #155724;
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-failed {
            background-color: #f8d7da;
            color: #721c24;
        }

        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }

        .thank-you {
            text-align: center;
            margin: 40px 0;
            font-size: 14px;
            font-weight: bold;
            color: #000;
        }
    </style>
</head>

<body>
    <!-- Invoice Header -->
    <div class="invoice-header">
        <div class="company-info">
            <div class="company-name">WOX BARBERSHOP</div>
            <div class="company-tagline">{{ __('receipt.company_tagline') }}</div>
            <div class="company-details">
                {{ __('receipt.company_address') }}<br>
                {{ __('receipt.company_city') }}<br>
                {{ __('receipt.company_phone') }}<br>
                {{ __('receipt.company_email') }}
            </div>
        </div>
        <div class="invoice-title">
            <h1>{{ __('receipt.invoice') }}</h1>
        </div>
    </div>

    <!-- Invoice Meta Information -->
    <div class="invoice-meta">
        <div class="invoice-details">
            <div class="section-title">{{ __('receipt.invoice_details') }}</div>
            <div class="info-row">
                <span class="info-label">{{ __('receipt.invoice_number') }}:</span>
                <span class="info-value">{{ $transaction['order_id'] ?? 'INV-' . time() }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">{{ __('receipt.date') }}:</span>
                <span class="info-value">
                    {{ isset($transaction['transaction_time'])
                        ? \Carbon\Carbon::parse($transaction['transaction_time'])->format('d M Y H:i')
                        : now()->format('d M Y H:i') }}
                </span>
            </div>
            <div class="info-row">
                <span class="info-label">{{ __('receipt.payment_status') }}:</span>
                <span class="info-value">
                    @php
                        $status = $transaction['transaction_status'] ?? ($transaction['status'] ?? 'unknown');
                        $statusClass = 'status-pending';
                        $statusText = 'Pending';

                        switch ($status) {
                            case 'settlement':
                            case 'capture':
                                $statusClass = 'status-success';
                                $statusText = 'Berhasil';
                                break;
                            case 'pending':
                                $statusClass = 'status-pending';
                                $statusText = 'Menunggu';
                                break;
                            case 'deny':
                            case 'failure':
                            case 'expire':
                                $statusClass = 'status-failed';
                                $statusText = 'Gagal';
                                break;
                        }
                    @endphp
                    <span class="status-badge {{ $statusClass }}">{{ $statusText }}</span>
                </span>
            </div>
        </div>

        <div class="customer-details">
            <div class="section-title">{{ __('receipt.customer_details') }}</div>
            <div class="info-row">
                <span class="info-label">{{ __('receipt.name') }}:</span>
                <span class="info-value">{{ $booking->name ?? ($transaction['name'] ?? 'N/A') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">{{ __('receipt.email') }}:</span>
                <span class="info-value">{{ $booking->user->email ?? ($transaction['email'] ?? 'N/A') }}</span>
            </div>
            @if (isset($booking->phone) && $booking->phone)
                <div class="info-row">
                    <span class="info-label">Telepon:</span>
                    <span class="info-value">{{ $booking->phone }}</span>
                </div>
            @endif
            @if (isset($booking->date_time))
                <div class="info-row">
                    <span class="info-label">Tanggal Booking:</span>
                    <span class="info-value">
                        {{ \Carbon\Carbon::parse($booking->date_time)->format('d M Y H:i') }}
                    </span>
                </div>
            @endif
        </div>
    </div>

    <!-- Services Table -->
    <table class="services-table">
        <thead>
            <tr>
                <th style="width: 50%">{{ __('receipt.service') }}</th>
                <th class="text-center" style="width: 15%">{{ __('receipt.qty') }}</th>
                <th class="text-right" style="width: 20%">{{ __('receipt.unit_price') }}</th>
                <th class="text-right" style="width: 15%">{{ __('receipt.total') }}</th>
            </tr>
        </thead>
        <tbody>
            @if (isset($booking) && $booking->service)
                <tr>
                    <td>
                        <strong>{{ $booking->service->name }}</strong>
                        @if ($booking->service->description)
                            <br><small style="color: #666;">{{ $booking->service->description }}</small>
                        @endif
                        @if (isset($booking->hairstyle) && $booking->hairstyle)
                            <br><small style="color: #888;"><strong>Gaya Rambut:</strong>
                                {{ $booking->hairstyle->name }}</small>
                        @endif
                    </td>
                    <td class="text-center">1</td>
                    <td class="text-right">Rp {{ number_format($booking->service->price, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($booking->service->price, 0, ',', '.') }}</td>
                </tr>
            @else
                <tr>
                    <td>Layanan Barbershop</td>
                    <td class="text-center">1</td>
                    <td class="text-right">Rp {{ number_format($transaction['gross_amount'] ?? 0, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($transaction['gross_amount'] ?? 0, 0, ',', '.') }}</td>
                </tr>
            @endif
        </tbody>
    </table>

    <!-- Total Section -->
    <div class="total-section">
        @php
            $subtotal = $booking->total_price ?? ($transaction['gross_amount'] ?? 0);
            $tax = 0; // No tax for now
            $total = $subtotal + $tax;
        @endphp

        <div class="total-row">
            <span>{{ __('receipt.subtotal') }}:</span>
            <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
        </div>

        @if ($tax > 0)
            <div class="total-row">
                <span>{{ __('receipt.tax') }}:</span>
                <span>Rp {{ number_format($tax, 0, ',', '.') }}</span>
            </div>
        @endif

        <div class="total-row grand-total">
            <span>{{ __('receipt.total_payment') }}:</span>
            <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
        </div>
    </div>

    <!-- Payment Information -->
    <div class="payment-info">
        <h4>{{ __('receipt.payment_information') }}</h4>
        <div class="info-row">
            <span class="info-label">{{ __('receipt.payment_method') }}:</span>
            <span class="info-value">
                @php
                    $paymentType = $transaction['payment_type'] ?? 'N/A';
                    $paymentText = match ($paymentType) {
                        'bank_transfer' => 'Transfer Bank',
                        'qris' => 'QRIS',
                        'gopay' => 'GoPay',
                        'shopeepay' => 'ShopeePay',
                        'credit_card' => 'Kartu Kredit',
                        default => ucwords(str_replace('_', ' ', $paymentType)),
                    };
                @endphp
                {{ $paymentText }}
            </span>
        </div>
        @if (isset($transaction['bank']) && $transaction['bank'])
            <div class="info-row">
                <span class="info-label">Bank:</span>
                <span class="info-value">{{ strtoupper($transaction['bank']) }}</span>
            </div>
        @endif
        @if (isset($transaction['va_number']) && $transaction['va_number'])
            <div class="info-row">
                <span class="info-label">No. Virtual Account:</span>
                <span class="info-value">{{ $transaction['va_number'] }}</span>
            </div>
        @endif
    </div>

    <!-- Thank You Message -->
    <div class="thank-you">
        {{ __('receipt.thank_you_message') }}<br>
        {{ __('receipt.serve_again_message') }}
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>{{ __('receipt.auto_generated_message') }}</p>
        <p>{{ __('receipt.contact_message') }}</p>
        <p><strong>WOX Barbershop</strong> - {{ __('receipt.tagline') }}</p>
    </div>
</body>

</html>
