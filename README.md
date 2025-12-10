# 💈 WOX Barbershop - Management System

<p align="center">
  <img src="public/images/Logo.png" alt="WOX Barbershop Logo" width="200">
</p>

<p align="center">
  <strong>Sistem Manajemen Barbershop Modern dengan Fitur Booking Online, Payment Gateway, dan Rekomendasi Gaya Rambut Berbasis AHP</strong>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-10.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel">
  <img src="https://img.shields.io/badge/PHP-8.1+-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/Tailwind_CSS-3.x-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white" alt="Tailwind CSS">
  <img src="https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
</p>

---

## 📋 Daftar Isi

-   [Tentang Proyek](#-tentang-proyek)
-   [Fitur Utama](#-fitur-utama)
-   [Teknologi yang Digunakan](#-teknologi-yang-digunakan)
-   [Sistem Rekomendasi AHP](#-sistem-rekomendasi-ahp)
-   [Persyaratan Sistem](#-persyaratan-sistem)
-   [Instalasi](#-instalasi)
-   [Konfigurasi](#-konfigurasi)
-   [Struktur Proyek](#-struktur-proyek)
-   [Panduan Penggunaan](#-panduan-penggunaan)
-   [API Endpoints](#-api-endpoints)
-   [Database Schema](#-database-schema)
-   [Testing](#-testing)
-   [Troubleshooting](#-troubleshooting)
-   [Kontribusi](#-kontribusi)
-   [Lisensi](#-lisensi)

---

## 📖 Tentang Proyek

**WOX Barbershop** adalah aplikasi web manajemen barbershop yang komprehensif, dirancang untuk memudahkan operasional barbershop modern. Aplikasi ini menyediakan sistem booking online, integrasi payment gateway (Midtrans), program loyalitas pelanggan, dan fitur rekomendasi gaya rambut menggunakan metode **Analytical Hierarchy Process (AHP)**.

### Latar Belakang

Proyek ini dikembangkan sebagai Tugas Akhir untuk mendemonstrasikan implementasi sistem informasi berbasis web yang terintegrasi dengan berbagai teknologi modern.

---

## ✨ Fitur Utama

### 👤 Untuk Pelanggan

-   **🗓️ Booking Online** - Reservasi layanan barbershop dengan pemilihan tanggal, waktu, dan layanan
-   **💇 Rekomendasi Gaya Rambut** - Sistem rekomendasi berbasis AHP berdasarkan bentuk kepala, tipe rambut, dan preferensi gaya
-   **💳 Pembayaran Online** - Integrasi dengan Midtrans (GoPay, QRIS, Bank Transfer, E-Wallet, dll)
-   **🎁 Program Loyalitas** - Kumpulkan poin dari setiap transaksi dan tukarkan dengan reward
-   **⭐ Feedback & Rating** - Berikan ulasan dan rating setelah layanan selesai
-   **📱 Responsive Design** - Tampilan optimal di semua perangkat (desktop, tablet, mobile)
-   **🌐 Multi-bahasa** - Dukungan Bahasa Indonesia dan English

### 👨‍💼 Untuk Admin/Pegawai

-   **📊 Dashboard Analytics** - Statistik real-time pendapatan, booking, dan pelanggan
-   **📅 Manajemen Booking** - Kelola reservasi, update status, dan antrian
-   **💰 Manajemen Keuangan** - Laporan transaksi, pendapatan, dan export data
-   **👥 Manajemen Pelanggan** - Data pelanggan, riwayat transaksi, dan poin loyalitas
-   **✂️ Manajemen Layanan** - CRUD layanan barbershop dengan harga dan durasi
-   **💇‍♂️ Manajemen Gaya Rambut** - Katalog gaya rambut dengan skor AHP
-   **🛍️ Manajemen Produk** - Katalog produk barbershop
-   **👮 Role & Permission** - Sistem hak akses berbasis role (Admin, Pegawai, Pelanggan)
-   **📤 Export Data** - Export laporan ke Excel, PDF, dan CSV
-   **💾 Backup & Restore** - Sistem backup dan restore database

---

## 🛠️ Teknologi yang Digunakan

### Backend

| Teknologi         | Versi | Deskripsi                      |
| ----------------- | ----- | ------------------------------ |
| PHP               | ^8.1  | Bahasa pemrograman server-side |
| Laravel           | 10.x  | Framework PHP modern           |
| Laravel Sanctum   | 3.x   | API Authentication             |
| Spatie Permission | 6.x   | Role & Permission management   |
| Laravel DomPDF    | 3.x   | Generate PDF documents         |
| Maatwebsite Excel | 3.x   | Export data ke Excel           |
| Midtrans PHP      | 2.x   | Payment gateway integration    |

### Frontend

| Teknologi    | Versi | Deskripsi                        |
| ------------ | ----- | -------------------------------- |
| Tailwind CSS | 3.x   | Utility-first CSS framework      |
| Alpine.js    | 3.x   | Lightweight JavaScript framework |
| Vite         | 5.x   | Next generation frontend tooling |
| Chart.js     | -     | Library untuk visualisasi data   |
| SweetAlert2  | 11.x  | Beautiful alerts & modals        |
| DataTables   | -     | Advanced table interactions      |
| Font Awesome | 6.x   | Icon library                     |

### Database & Tools

| Teknologi        | Deskripsi                  |
| ---------------- | -------------------------- |
| MySQL 8.0        | Relational database        |
| Yajra DataTables | Server-side processing     |
| Laravel Breeze   | Authentication scaffolding |

---

## 🧮 Sistem Rekomendasi AHP

Sistem ini menggunakan **Analytical Hierarchy Process (AHP)** untuk memberikan rekomendasi hairstyle yang personal berdasarkan karakteristik customer.

### 📊 **Kriteria Penilaian**

| Kriteria            | Weight | Prioritas    | Deskripsi                                        |
| ------------------- | ------ | ------------ | ------------------------------------------------ |
| **Bentuk Kepala**   | 50.03% | 🥇 Tertinggi | Faktor paling penting dalam menentukan hairstyle |
| **Tipe Rambut**     | 29.98% | 🥈 Sedang    | Tekstur rambut mempengaruhi hasil styling        |
| **Preferensi Gaya** | 19.99% | 🥉 Terendah  | Gaya personal customer                           |

### 🎯 **Cara Kerja**

1. **Input Customer:** Bentuk kepala, tipe rambut, preferensi gaya
2. **Perhitungan AHP:** Sistem menghitung bobot kriteria berdasarkan pairwise comparison
3. **Scoring:** Setiap hairstyle dinilai untuk setiap sub-kriteria (skala 1-10)
4. **Recommendation:**
    ```
    Total Score = (0.5003 × Score_BentukKepala) +
                  (0.2998 × Score_TipeRambut) +
                  (0.2000 × Score_PreferensiGaya)
    ```
5. **Ranking:** Hairstyle diurutkan dari score tertinggi

### 📖 **Dokumentasi Lengkap**

Untuk penjelasan detail tentang perhitungan AHP, contoh kasus, dan formula matematis, lihat:

**📄 [DOKUMENTASI_AHP.md](DOKUMENTASI_AHP.md)**

Dokumentasi mencakup:

-   ✅ Penjelasan metode AHP step-by-step
-   ✅ Contoh perhitungan dengan data aktual
-   ✅ Matriks perbandingan berpasangan
-   ✅ Validasi Consistency Ratio (CR)
-   ✅ Multiple contoh kasus
-   ✅ Analisis sensitivitas
-   ✅ Implementasi kode

### 🔍 **Validasi Konsistensi**

**Consistency Ratio (CR):** 0.00086 < 0.1 ✅

Nilai CR yang sangat rendah menunjukkan bahwa penilaian pairwise comparison sangat konsisten dan dapat diandalkan.

---

## 💻 Persyaratan Sistem

### Minimum Requirements

-   **PHP** >= 8.1
-   **Composer** >= 2.0
-   **Node.js** >= 16.x
-   **NPM** >= 8.x
-   **MySQL** >= 8.0 atau MariaDB >= 10.3
-   **Web Server** Apache/Nginx

### PHP Extensions

```
BCMath, Ctype, cURL, DOM, Fileinfo, JSON, Mbstring,
OpenSSL, PCRE, PDO, Tokenizer, XML, GD/Imagick
```

---

## 🚀 Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/agungwahyu14/WoxBarbershop.git
cd WoxBarbershop
```

### 2. Install Dependencies

**PHP Dependencies:**

```bash
composer install
```

**Node.js Dependencies:**

```bash
npm install
```

### 3. Environment Setup

```bash
# Copy file environment
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Konfigurasi Database

Edit file `.env` dan sesuaikan konfigurasi database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=wox_barbershop
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 5. Migrasi & Seeder

```bash
# Jalankan migrasi database
php artisan migrate

# (Opsional) Jalankan seeder untuk data dummy
php artisan db:seed
```

### 6. Storage Link

```bash
php artisan storage:link
```

### 7. Build Assets

```bash
# Development
npm run dev

# Production
npm run build
```

### 8. Jalankan Server

```bash
# Laravel development server
php artisan serve

# Vite development server (terminal terpisah)
npm run dev
```

Akses aplikasi di: `http://localhost:8000`

---

## ⚙️ Konfigurasi

### Midtrans Payment Gateway

Edit file `.env`:

```env
MIDTRANS_SERVER_KEY=your_server_key
MIDTRANS_CLIENT_KEY=your_client_key
MIDTRANS_IS_PRODUCTION=false
MIDTRANS_MERCHANT_ID=your_merchant_id
```

### Mail Configuration

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your_email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

### Jam Operasional

Jam operasional default barbershop:

-   **Buka:** 11:00 WIB
-   **Tutup:** 22:00 WIB
-   **Hari Kerja:** Setiap hari (tidak ada hari libur default)

Konfigurasi dapat diubah di `app/Services/BookingService.php`

---

## 📁 Struktur Proyek

```
WoxBarbershop/
├── app/
│   ├── Console/           # Artisan commands
│   ├── DataTables/        # DataTables classes
│   ├── Exceptions/        # Exception handlers
│   ├── Exports/           # Excel/PDF export classes
│   │   ├── BookingsExport.php
│   │   ├── CustomersExport.php
│   │   └── FinancialExport.php
│   ├── Helpers/           # Helper functions
│   │   └── LanguageHelper.php
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/     # Admin controllers
│   │   │   │   ├── FeedbackController.php
│   │   │   │   ├── HairstyleController.php
│   │   │   │   ├── LoyaltyController.php
│   │   │   │   ├── ProductController.php
│   │   │   │   ├── RoleController.php
│   │   │   │   ├── ServiceController.php
│   │   │   │   ├── TransactionController.php
│   │   │   │   └── UserController.php
│   │   │   ├── Auth/      # Authentication controllers
│   │   │   ├── BookingController.php
│   │   │   ├── DashboardController.php
│   │   │   ├── FeedbackController.php
│   │   │   ├── PaymentController.php
│   │   │   ├── ProfileController.php
│   │   │   └── RecommendationController.php
│   │   ├── Middleware/    # HTTP middleware
│   │   └── Requests/      # Form requests
│   ├── Mail/              # Mailable classes
│   ├── Models/            # Eloquent models
│   │   ├── Booking.php
│   │   ├── Criteria.php
│   │   ├── Feedback.php
│   │   ├── Hairstyle.php
│   │   ├── HairstyleScore.php
│   │   ├── Loyalty.php
│   │   ├── PairwiseComparison.php
│   │   ├── Product.php
│   │   ├── Service.php
│   │   ├── Transaction.php
│   │   └── User.php
│   ├── Notifications/     # Notification classes
│   ├── Policies/          # Authorization policies
│   ├── Providers/         # Service providers
│   ├── Rules/             # Custom validation rules
│   ├── Services/          # Business logic services
│   └── View/              # View components
├── bootstrap/             # Framework bootstrap
├── config/                # Configuration files
│   ├── midtrans.php       # Midtrans config
│   ├── permission.php     # Spatie permission config
│   └── ...
├── database/
│   ├── factories/         # Model factories
│   ├── migrations/        # Database migrations
│   └── seeders/           # Database seeders
├── public/                # Public assets
│   ├── css/               # Compiled CSS
│   ├── js/                # JavaScript files
│   ├── images/            # Image assets
│   └── index.php          # Entry point
├── resources/
│   ├── css/               # Source CSS
│   ├── js/                # Source JavaScript
│   ├── lang/              # Localization files
│   │   ├── en/            # English translations
│   │   └── id/            # Indonesian translations
│   └── views/             # Blade templates
│       ├── admin/         # Admin views
│       ├── auth/          # Authentication views
│       ├── exports/       # Export templates
│       ├── layouts/       # Layout templates
│       └── ...
├── routes/
│   ├── api.php            # API routes
│   ├── auth.php           # Auth routes
│   └── web.php            # Web routes
├── storage/               # Storage directory
├── tests/                 # Test files
│   ├── Feature/           # Feature tests
│   └── Unit/              # Unit tests
├── .env.example           # Environment example
├── composer.json          # PHP dependencies
├── package.json           # Node dependencies
├── tailwind.config.js     # Tailwind configuration
├── vite.config.js         # Vite configuration
└── README.md              # This file
```

---

## 📚 Panduan Penggunaan

### Akun Default (Setelah Seeder)

| Role      | Email             | Password |
| --------- | ----------------- | -------- |
| Admin     | admin@wox.com     | password |
| Pegawai   | pegawai@wox.com   | password |
| Pelanggan | pelanggan@wox.com | password |

### Alur Booking Pelanggan

1. **Login/Register** - Daftar atau masuk ke akun
2. **Pilih Layanan** - Pilih layanan yang diinginkan
3. **Pilih Gaya Rambut** - Pilih gaya rambut (opsional)
4. **Pilih Jadwal** - Tentukan tanggal dan waktu
5. **Konfirmasi** - Review dan konfirmasi booking
6. **Pembayaran** - Pilih metode pembayaran (Cash/Online)
7. **Selesai** - Terima nomor antrian dan konfirmasi

### Fitur Rekomendasi AHP

Sistem rekomendasi menggunakan metode **Analytical Hierarchy Process (AHP)** untuk memberikan rekomendasi gaya rambut berdasarkan:

| Kriteria        | Deskripsi                            |
| --------------- | ------------------------------------ |
| Bentuk Kepala   | Oval, Bulat, Persegi, Panjang, Hati  |
| Tipe Rambut     | Lurus, Bergelombang, Keriting, Coily |
| Preferensi Gaya | Klasik, Modern, Casual, Formal       |

**Cara Kerja:**

1. Sistem menghitung bobot kriteria menggunakan matriks perbandingan berpasangan
2. Setiap gaya rambut memiliki skor untuk masing-masing kriteria
3. Skor akhir = Σ (Bobot Kriteria × Skor Gaya Rambut)
4. Hasil diurutkan berdasarkan skor tertinggi

---

## 🔌 API Endpoints

### Authentication

```
POST   /api/login                 # Login user
POST   /api/register              # Register user
POST   /api/logout                # Logout user
GET    /api/user                  # Get current user
```

### Booking API

```
POST   /api/validate-booking-time # Validate booking time
GET    /api/available-slots       # Get available time slots
```

### Midtrans Callback

```
POST   /api/midtrans/callback     # Payment notification callback
```

### Query Parameters

**Available Slots:**

```
GET /api/available-slots?date=2025-01-15&service_id=1
```

---

## 🗄️ Database Schema

### Entity Relationship

```
Users ─────────────┬─────────────── Bookings
                   │                    │
                   │                    ├── Services
                   │                    │
                   │                    ├── Hairstyles
                   │                    │
                   │                    └── Transactions
                   │
                   ├─────────────── Loyalty
                   │
                   └─────────────── Feedbacks

Hairstyles ────────┬─────────────── HairstyleScores
                   │                    │
                   ├── BentukKepala     └── Criteria
                   │
                   ├── TipeRambut
                   │
                   └── StylePreference

Criteria ──────────────────────────── PairwiseComparisons
```

### Tabel Utama

| Tabel                  | Deskripsi                                 |
| ---------------------- | ----------------------------------------- |
| `users`                | Data pengguna (pelanggan, pegawai, admin) |
| `bookings`             | Data reservasi/booking                    |
| `services`             | Layanan barbershop                        |
| `hairstyles`           | Katalog gaya rambut                       |
| `transactions`         | Transaksi pembayaran                      |
| `loyalties`            | Poin loyalitas pelanggan                  |
| `feedbacks`            | Ulasan dan rating pelanggan               |
| `products`             | Produk barbershop                         |
| `criteria`             | Kriteria untuk AHP                        |
| `pairwise_comparisons` | Matriks perbandingan AHP                  |
| `hairstyle_scores`     | Skor gaya rambut per kriteria             |

---

## 🧪 Testing

### Menjalankan Tests

```bash
# Semua tests
php artisan test

# Dengan coverage
php artisan test --coverage

# Test spesifik
php artisan test --filter BookingTest

# Test feature saja
php artisan test --testsuite=Feature

# Test unit saja
php artisan test --testsuite=Unit
```

### Test Structure

```
tests/
├── Feature/
│   ├── Auth/
│   ├── Booking/
│   └── ...
└── Unit/
    ├── Models/
    ├── Services/
    └── ...
```

---

## 🔧 Troubleshooting

### Masalah Umum

#### 1. Class not found

```bash
composer dump-autoload
php artisan optimize:clear
```

#### 2. Permission denied pada storage

```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

#### 3. Asset tidak loading

```bash
npm run build
php artisan view:clear
php artisan cache:clear
```

#### 4. Database connection refused

-   Pastikan MySQL service berjalan
-   Cek konfigurasi `.env` (DB_HOST, DB_PORT, DB_DATABASE)
-   Pastikan database sudah dibuat

#### 5. Midtrans callback tidak berfungsi

-   Pastikan `MIDTRANS_SERVER_KEY` benar
-   Untuk development, gunakan ngrok untuk expose localhost
-   Cek log di `storage/logs/laravel.log`

#### 6. Email tidak terkirim

-   Pastikan konfigurasi SMTP di `.env` benar
-   Untuk Gmail, gunakan App Password
-   Cek apakah firewall memblokir port SMTP

### Debugging Tips

```bash
# Cek Laravel logs
tail -f storage/logs/laravel.log

# Interactive debugging
php artisan tinker

# Clear all cache
php artisan optimize:clear
```

---

## 🤝 Kontribusi

Kontribusi sangat diterima! Silakan ikuti langkah berikut:

1. Fork repository
2. Buat branch baru (`git checkout -b feature/AmazingFeature`)
3. Commit perubahan (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Buat Pull Request

### Coding Standards

-   Ikuti PSR-12 coding standard
-   Gunakan meaningful variable dan function names
-   Tambahkan komentar untuk logic yang kompleks
-   Tulis tests untuk fitur baru

---

## 📄 Lisensi

Proyek ini dilisensikan di bawah [MIT License](LICENSE).

---

## 👨‍💻 Developer

**Agung Wahyu**

-   GitHub: [@agungwahyu14](https://github.com/agungwahyu14)

---

## 🙏 Acknowledgments

-   [Laravel](https://laravel.com) - The PHP Framework
-   [Tailwind CSS](https://tailwindcss.com) - CSS Framework
-   [Midtrans](https://midtrans.com) - Payment Gateway
-   [Spatie](https://spatie.be) - Laravel Permission Package
-   [Yajra](https://yajrabox.com) - Laravel DataTables

---

<p align="center">
  Made with ❤️ for WOX Barbershop
</p>
