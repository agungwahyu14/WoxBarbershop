<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

-   [Simple, fast routing engine](https://laravel.com/docs/routing).
-   [Powerful dependency injection container](https://laravel.com/docs/container).
-   Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
-   Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
-   Database agnostic [schema migrations](https://laravel.com/docs/migrations).
-   [Robust background job processing](https://laravel.com/docs/queues).
-   [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

-   **[Vehikl](https://vehikl.com/)**
-   **[Tighten Co.](https://tighten.co)**
-   **[WebReinvent](https://webreinvent.com/)**
-   **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
-   **[64 Robots](https://64robots.com)**
-   **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
-   **[Cyber-Duck](https://cyber-duck.co.uk)**
-   **[DevSquad](https://devsquad.com/hire-laravel-developers)**
-   **[Jump24](https://jump24.co.uk)**
-   **[Redberry](https://redberry.international/laravel/)**
-   **[Active Logic](https://activelogic.com)**
-   **[byte5](https://byte5.de)**
-   **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

# WOX Barbershop - Sistem Manajemen Barbershop

Sistem manajemen barbershop modern dengan fitur booking online, sistem loyalty, dan dashboard admin yang komprehensif.

## 📋 Daftar Isi

-   [Fitur Utama](#fitur-utama)
-   [Requirements](#requirements)
-   [Instalasi](#instalasi)
-   [Konfigurasi](#konfigurasi)
-   [Penggunaan](#penggunaan)
-   [API Documentation](#api-documentation)
-   [Testing](#testing)
-   [Troubleshooting](#troubleshooting)
-   [Contributing](#contributing)

## ✨ Fitur Utama

### 🎯 Customer Features

-   **Online Booking System** - Reservasi layanan barbershop secara online
-   **User Authentication** - Register, login, dan manajemen profil
-   **Loyalty Program** - Sistem poin loyalty (gratis potong rambut setelah 10 kunjungan)
-   **Payment Integration** - Integrasi dengan Midtrans untuk pembayaran
-   **Booking History** - Riwayat booking dan transaksi

### 👨‍💼 Admin Features

-   **Dashboard Analytics** - Statistik booking, revenue, dan customer
-   **Booking Management** - Kelola semua booking dengan status tracking
-   **User Management** - Manajemen customer dan staff
-   **Service Management** - Kelola layanan dan harga
-   **Transaction Management** - Monitor semua transaksi dan pembayaran
-   **Reports & Export** - Export data ke Excel, PDF, CSV

### 🎨 Design Features

-   **Responsive Design** - Mobile-first design dengan Tailwind CSS
-   **Interactive UI** - SweetAlert notifications dan smooth transitions
-   **Dark Mode Support** - Theme switching capability
-   **Modern Charts** - Chart.js untuk visualisasi data

## 💻 Requirements

-   **PHP** >= 8.1
-   **Laravel** 10.x
-   **MySQL** >= 8.0
-   **Node.js** >= 16.x
-   **NPM** atau **Yarn**
-   **Composer**

### PHP Extensions

```
- BCMath
- Ctype
- Fileinfo
- JSON
- Mbstring
- OpenSSL
- PDO
- Tokenizer
- XML
- cURL
- GD
```

## 🚀 Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/username/wox-barbershop.git
cd wox-barbershop
```

### 2. Install Dependencies

```bash
# PHP Dependencies
composer install

# Node.js Dependencies
npm install
```

### 3. Environment Setup

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Database Setup

```bash
# Create database
mysql -u root -p
CREATE DATABASE wox_barbershop;

# Run migrations
php artisan migrate

# Seed database (optional)
php artisan db:seed
```

### 5. Storage Link

```bash
php artisan storage:link
```

### 6. Build Assets

```bash
# Development
npm run dev

# Production
npm run build
```

## ⚙️ Konfigurasi

### Database Configuration

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=wox_barbershop
DB_USERNAME=root
DB_PASSWORD=your_password
```

### Midtrans Payment Configuration

```env
MIDTRANS_SERVER_KEY=your_server_key
MIDTRANS_CLIENT_KEY=your_client_key
MIDTRANS_IS_PRODUCTION=false
MIDTRANS_IS_SANITIZED=true
MIDTRANS_IS_3DS=true
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
MAIL_FROM_NAME="WOX Barbershop"
```

### App Configuration

```env
APP_NAME="WOX Barbershop"
APP_ENV=local
APP_KEY=base64:generated_key
APP_DEBUG=true
APP_URL=http://localhost:8000
```

## 🎮 Penggunaan

### Development Server

```bash
# Start Laravel development server
php artisan serve

# Start Vite development server (new terminal)
npm run dev
```

### Default Admin Account

```
Email: admin@woxbarbershop.com
Password: password
```

### Default Customer Account

```
Email: customer@example.com
Password: password
```

## 📁 Struktur Project

```
wox-barbershop/
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/           # Admin controllers
│   │   ├── Auth/            # Authentication controllers
│   │   └── BookingController.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Booking.php
│   │   ├── Service.php
│   │   ├── Transaction.php
│   │   └── Loyalty.php
│   └── Services/            # Business logic services
├── database/
│   ├── migrations/          # Database migrations
│   └── seeders/            # Database seeders
├── resources/
│   ├── views/
│   │   ├── admin/          # Admin views
│   │   ├── auth/           # Authentication views
│   │   ├── profile/        # User profile views
│   │   └── welcome.blade.php
│   └── css/
└── routes/
    ├── web.php
    └── api.php
```

## 🔌 API Documentation

### Authentication Endpoints

```bash
POST /api/register     # User registration
POST /api/login        # User login
POST /api/logout       # User logout
```

### Booking Endpoints

```bash
GET    /api/bookings           # Get user bookings
POST   /api/bookings           # Create new booking
GET    /api/bookings/{id}      # Get booking detail
PATCH  /api/bookings/{id}      # Update booking
DELETE /api/bookings/{id}      # Cancel booking
```

### Payment Endpoints

```bash
POST /api/payments/create      # Create payment
POST /api/payments/callback    # Midtrans callback
GET  /api/payments/{id}/status # Check payment status
```

## 🧪 Testing

### Run Tests

```bash
# Run all tests
php artisan test

# Run specific test
php artisan test --filter BookingTest

# Run with coverage
php artisan test --coverage
```

### Feature Tests

-   User Registration & Authentication
-   Booking Creation & Management
-   Payment Processing
-   Loyalty System
-   Admin Dashboard

## 🐛 Troubleshooting

### Common Issues

#### 1. Vite CORS Error

```bash
# Add to .env
VITE_DEV_SERVER_HOST=localhost
VITE_DEV_SERVER_PORT=5173
```

#### 2. Storage Permission

```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

#### 3. Database Connection Error

```bash
# Check MySQL service
sudo systemctl status mysql

# Restart MySQL
sudo systemctl restart mysql
```

#### 4. Composer Memory Limit

```bash
php -d memory_limit=-1 /usr/local/bin/composer install
```

### Log Files

```bash
# Laravel logs
tail -f storage/logs/laravel.log

# Error logs
tail -f storage/logs/error.log
```

## 📦 Deployment

### Production Setup

```bash
# Optimize application
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set permissions
chmod -R 755 storage
chmod -R 755 bootstrap/cache

# Build assets
npm run build
```

### Environment Variables (Production)

```env
APP_ENV=production
APP_DEBUG=false
MIDTRANS_IS_PRODUCTION=true
```

## 🤝 Contributing

1. Fork the repository
2. Create feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Open Pull Request

### Coding Standards

-   Follow PSR-12 coding standards
-   Use meaningful variable names
-   Add comments for complex logic
-   Write tests for new features

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 👥 Team

-   **Developer**: Your Name
-   **Email**: your.email@example.com
-   **Project**: WOX Barbershop Management System

## 🙏 Acknowledgments

-   Laravel Framework
-   Tailwind CSS
-   Midtrans Payment Gateway
-   Chart.js
-   SweetAlert2
-   DataTables

---

**WOX Barbershop** -
