<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Konfirmasi Booking - {{ config('app.name') }}</title>
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-align: center;
            padding: 30px 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: bold;
        }
        .content {
            padding: 30px;
        }
        .booking-details {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 25px;
            margin: 20px 0;
            border-left: 4px solid #667eea;
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
            color: #667eea;
        }
        .label {
            font-weight: bold;
            color: #555;
        }
        .value {
            color: #333;
        }
        .queue-info {
            background: #e3f2fd;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            text-align: center;
            border: 2px solid #2196f3;
        }
        .queue-number {
            font-size: 36px;
            font-weight: bold;
            color: #2196f3;
            margin: 10px 0;
        }
        .button {
            display: inline-block;
            background: #667eea;
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
        .important-note {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
        }
        .important-note h4 {
            color: #856404;
            margin: 0 0 10px 0;
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
            <h1>{{ config('app.name') }}</h1>
            <p>Booking Confirmation</p>
        </div>
        
        <div class="content">
            <h2>Halo {{ $booking->user->name }},</h2>
            <p>Terima kasih! Booking Anda telah berhasil dikonfirmasi. Berikut adalah detail booking Anda:</p>
            
            <div class="booking-details">
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
                    <span class="label">Estimasi Durasi:</span>
                    <span class="value">{{ $booking->service->duration }} menit</span>
                </div>
                @if($booking->description)
                <div class="detail-row">
                    <span class="label">Catatan:</span>
                    <span class="value">{{ $booking->description }}</span>
                </div>
                @endif
                <div class="detail-row">
                    <span class="label">Total Harga:</span>
                    <span class="value">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="queue-info">
                <h3>Nomor Antrian Anda</h3>
                <div class="queue-number">{{ $booking->queue_number }}</div>
                <p>Harap tiba 15 menit sebelum waktu yang dijadwalkan</p>
            </div>

            <div class="important-note">
                <h4>⚠️ Informasi Penting:</h4>
                <ul style="margin: 10px 0; padding-left: 20px;">
                    <li>Mohon datang tepat waktu sesuai jadwal</li>
                    <li>Jika terlambat lebih dari 15 menit, booking dapat dibatalkan</li>
                    <li>Untuk perubahan jadwal, hubungi kami minimal 2 jam sebelumnya</li>
                    <li>Pembayaran dapat dilakukan di tempat atau melalui aplikasi</li>
                </ul>
            </div>

            <div style="text-align: center;">
                <a href="{{ route('bookings.show', $booking->id) }}" class="button">
                    Lihat Detail Booking
                </a>
            </div>

            <p>Jika Anda memiliki pertanyaan, jangan ragu untuk menghubungi kami di:</p>
            <ul>
                <li>📞 Telepon: {{ config('app.phone', '0812-3456-7890') }}</li>
                <li>📧 Email: {{ config('app.email', 'info@barbershop.com') }}</li>
                <li>📍 Alamat: {{ config('app.address', 'Jl. Contoh No. 123, Jakarta') }}</li>
            </ul>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Semua hak dilindungi.</p>
            <p>Email ini dikirim otomatis, mohon jangan membalas email ini.</p>
        </div>
    </div>
</body>
</html>