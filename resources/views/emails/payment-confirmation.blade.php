<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Konfirmasi Pembayaran - {{ config('app.name') }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            text-align: center;
            padding: 30px 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: bold;
        }
        .success-icon {
            font-size: 48px;
            margin: 10px 0;
        }
        .content {
            padding: 30px;
        }
        .payment-details {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 25px;
            margin: 20px 0;
            border-left: 4px solid #28a745;
        }
        .booking-details {
            background: #e3f2fd;
            border-radius: 8px;
            padding: 25px;
            margin: 20px 0;
            border-left: 4px solid #2196f3;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        .detail-row:last-child {
            border-bottom: none;
            font-weight: bold;
            font-size: 18px;
            color: #28a745;
        }
        .label {
            font-weight: bold;
            color: #555;
        }
        .value {
            color: #333;
        }
        .status-paid {
            background: #d4edda;
            color: #155724;
            padding: 10px 15px;
            border-radius: 25px;
            font-weight: bold;
            text-align: center;
            margin: 20px 0;
        }
        .button {
            display: inline-block;
            background: #28a745;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 25px;
            font-weight: bold;
            margin: 20px 0;
            text-align: center;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #666;
            font-size: 14px;
        }
        .receipt-box {
            border: 2px dashed #28a745;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            background: #f8fff9;
        }
        @media (max-width: 600px) {
            .container {
                margin: 10px;
                width: auto;
            }
            .detail-row {
                flex-direction: column;
            }
            .detail-row .value {
                font-weight: bold;
                margin-top: 5px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="success-icon">✅</div>
            <h1>Pembayaran Berhasil!</h1>
            <p>{{ config('app.name') }}</p>
        </div>
        
        <div class="content">
            <h2>Halo {{ $booking->user->name }},</h2>
            <p>Pembayaran Anda telah berhasil diproses! Booking Anda sekarang telah dikonfirmasi dan siap untuk dilayani.</p>
            
            <div class="status-paid">
                ✅ PEMBAYARAN BERHASIL
            </div>

            <div class="receipt-box">
                <h3 style="text-align: center; color: #28a745; margin-top: 0;">📧 RECEIPT / KWITANSI DIGITAL</h3>
                
                <div class="payment-details">
                    <h4>Detail Pembayaran</h4>
                    <div class="detail-row">
                        <span class="label">Order ID:</span>
                        <span class="value">{{ $transaction->order_id }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="label">Transaction ID:</span>
                        <span class="value">{{ $transaction->transaction_id ?? 'N/A' }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="label">Metode Pembayaran:</span>
                        <span class="value">{{ ucfirst(str_replace('_', ' ', $transaction->payment_type ?? $transaction->payment_method)) }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="label">Tanggal Pembayaran:</span>
                        <span class="value">{{ $transaction->paid_at ? $transaction->paid_at->format('d F Y, H:i') : now()->format('d F Y, H:i') }} WIB</span>
                    </div>
                    <div class="detail-row">
                        <span class="label">Total Dibayar:</span>
                        <span class="value">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <div class="booking-details">
                <h4>Detail Booking</h4>
                <div class="detail-row">
                    <span class="label">Booking ID:</span>
                    <span class="value">#{{ $booking->id }}</span>
                </div>
                <div class="detail-row">
                    <span class="label">Nama:</span>
                    <span class="value">{{ $booking->name }}</span>
                </div>
                <div class="detail-row">
                    <span class="label">Layanan:</span>
                    <span class="value">{{ $booking->service->name }}</span>
                </div>
                <div class="detail-row">
                    <span class="label">Gaya Rambut:</span>
                    <span class="value">{{ $booking->hairstyle->name }}</span>
                </div>
                <div class="detail-row">
                    <span class="label">Tanggal & Waktu:</span>
                    <span class="value">{{ $booking->date_time->format('d F Y, H:i') }} WIB</span>
                </div>
                <div class="detail-row">
                    <span class="label">Nomor Antrian:</span>
                    <span class="value">{{ $booking->queue_number }}</span>
                </div>
                <div class="detail-row">
                    <span class="label">Status:</span>
                    <span class="value" style="color: #28a745;">{{ ucfirst($booking->status) }}</span>
                </div>
            </div>

            <div style="background: #fff3cd; border: 1px solid #ffeaa7; border-radius: 8px; padding: 15px; margin: 20px 0;">
                <h4 style="color: #856404; margin: 0 0 10px 0;">📋 Langkah Selanjutnya:</h4>
                <ul style="margin: 10px 0; padding-left: 20px; color: #856404;">
                    <li>Datang tepat waktu sesuai jadwal booking</li>
                    <li>Tunjukkan email ini sebagai bukti pembayaran</li>
                    <li>Jika perlu mengubah jadwal, hubungi kami minimal 2 jam sebelumnya</li>
                    <li>Nikmati layanan premium kami!</li>
                </ul>
            </div>

            <div style="text-align: center;">
                <a href="{{ route('bookings.show', $booking->id) }}" class="button">
                    Lihat Detail Booking
                </a>
            </div>

            <p>Terima kasih telah menggunakan layanan kami. Kami tunggu kedatangan Anda!</p>

            <p><strong>Informasi Kontak:</strong></p>
            <ul>
                <li>📞 Telepon: {{ config('app.phone', '0812-3456-7890') }}</li>
                <li>📧 Email: {{ config('app.email', 'info@barbershop.com') }}</li>
                <li>📍 Alamat: {{ config('app.address', 'Jl. Contoh No. 123, Jakarta') }}</li>
            </ul>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Semua hak dilindungi.</p>
            <p>Simpan email ini sebagai bukti pembayaran yang sah.</p>
        </div>
    </div>
</body>
</html>