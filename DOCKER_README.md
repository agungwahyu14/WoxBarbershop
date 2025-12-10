# Docker Setup untuk WOX Barbershop

## Prerequisites

-   Docker Desktop (untuk macOS/Windows) atau Docker Engine + Docker Compose (untuk Linux)
-   Minimal 4GB RAM untuk Docker
-   Port 3306, 6379, 8000, 8080, dan 5173 harus tersedia

## Cara Menjalankan

### 1. Setup Environment File

Pertama, copy file environment untuk Docker:

```bash
cp .env.docker .env
```

Atau edit manual file `.env` dan sesuaikan konfigurasi database:

```
DB_HOST=mysql
DB_DATABASE=barbershop_db
DB_USERNAME=barbershop_user
DB_PASSWORD=barbershop_pass

REDIS_HOST=redis
CACHE_DRIVER=redis
SESSION_DRIVER=redis
```

### 2. Build dan Jalankan Container

```bash
docker-compose up -d --build
```

Perintah ini akan:

-   Build image aplikasi Laravel
-   Download image MySQL 8.0, phpMyAdmin, dan Redis
-   Menjalankan semua service dalam mode detached

### 3. Tunggu Hingga Selesai

Proses pertama kali akan memakan waktu 5-10 menit karena:

-   Install dependencies Composer
-   Install dependencies NPM
-   Run migrations
-   Run seeders
-   Build assets Vite

Lihat progress dengan:

```bash
docker-compose logs -f app
```

### 4. Akses Aplikasi

Setelah selesai, Anda dapat mengakses:

-   **Laravel App**: http://localhost:8000
-   **phpMyAdmin**: http://localhost:8080
    -   Server: `mysql`
    -   Username: `root`
    -   Password: `root_password`
-   **MySQL**: localhost:3306
    -   Database: `barbershop_db`
    -   Username: `barbershop_user`
    -   Password: `barbershop_pass`

## Perintah Docker Berguna

### Stop semua container

```bash
docker-compose down
```

### Stop dan hapus semua data (volumes)

```bash
docker-compose down -v
```

### Restart aplikasi

```bash
docker-compose restart app
```

### Lihat logs

```bash
# Semua service
docker-compose logs -f

# Aplikasi saja
docker-compose logs -f app

# MySQL saja
docker-compose logs -f mysql
```

### Masuk ke container aplikasi

```bash
docker-compose exec app bash
```

Dalam container, Anda bisa menjalankan:

```bash
php artisan migrate
php artisan db:seed
php artisan tinker
composer install
npm run dev
```

### Menjalankan Artisan Commands

```bash
docker-compose exec app php artisan migrate
docker-compose exec app php artisan db:seed
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan key:generate
```

### Menjalankan Composer

```bash
docker-compose exec app composer install
docker-compose exec app composer update
docker-compose exec app composer require package/name
```

### Menjalankan NPM

```bash
docker-compose exec app npm install
docker-compose exec app npm run dev
docker-compose exec app npm run build
```

### Backup Database

```bash
docker-compose exec mysql mysqldump -u barbershop_user -pbarbershop_pass barbershop_db > backup.sql
```

### Restore Database

```bash
docker-compose exec -T mysql mysql -u barbershop_user -pbarbershop_pass barbershop_db < backup.sql
```

## Troubleshooting

### Port Sudah Digunakan

Jika port sudah digunakan, edit `docker-compose.yml` dan ubah mapping port:

```yaml
ports:
    - "3307:3306" # MySQL (ganti 3306 ke 3307)
    - "8001:8000" # Laravel (ganti 8000 ke 8001)
    - "8081:80" # phpMyAdmin (ganti 8080 ke 8081)
```

### Permission Errors

Jika ada error permission pada storage atau bootstrap/cache:

```bash
docker-compose exec app chmod -R 775 storage bootstrap/cache
docker-compose exec app chown -R www-data:www-data storage bootstrap/cache
```

### MySQL Connection Refused

Tunggu beberapa saat hingga MySQL selesai initialize (cek dengan `docker-compose logs mysql`), kemudian:

```bash
docker-compose restart app
```

### Clear Everything dan Mulai Fresh

```bash
docker-compose down -v
rm -rf vendor node_modules
docker-compose up -d --build
```

### Aplikasi Tidak Bisa Diakses

1. Cek status container:

```bash
docker-compose ps
```

2. Cek logs untuk error:

```bash
docker-compose logs app
```

3. Pastikan semua service running dan healthy:

```bash
docker-compose ps
```

## Development Mode

Untuk development dengan hot reload Vite, jalankan dalam mode terpisah:

1. Jalankan container biasa:

```bash
docker-compose up -d
```

2. Stop proses di container app dan jalankan Vite dev server:

```bash
docker-compose exec app bash
# Di dalam container:
php artisan serve --host=0.0.0.0 --port=8000 &
npm run dev -- --host
```

## Production Mode

Untuk production, edit `docker-compose.yml`:

-   Ubah `APP_ENV` menjadi `production`
-   Ubah `APP_DEBUG` menjadi `false`
-   Gunakan password yang kuat untuk MySQL
-   Hapus phpMyAdmin service (tidak perlu di production)

## Structure Container

```
┌─────────────────┐
│   app:8000      │  Laravel App + Vite
│   (PHP 8.1)     │
└────────┬────────┘
         │
    ┌────┴─────────────────┐
    │                      │
┌───▼──────┐      ┌────────▼─────┐
│  mysql   │      │    redis     │
│  :3306   │      │    :6379     │
└──────────┘      └──────────────┘
    │
┌───▼────────────┐
│  phpmyadmin    │
│     :8080      │
└────────────────┘
```

## Tips

1. **Persistent Data**: Data MySQL dan Redis disimpan dalam Docker volumes, jadi tidak akan hilang saat container restart
2. **Development Files**: Folder project di-mount ke container, perubahan code langsung terlihat
3. **Health Checks**: MySQL dan Redis memiliki health check, aplikasi akan tunggu hingga database ready
4. **Auto Migration**: Saat pertama kali dijalankan, migrations dan seeders otomatis dijalankan

## Contact

Jika ada masalah atau pertanyaan, silakan buka issue di repository ini.
