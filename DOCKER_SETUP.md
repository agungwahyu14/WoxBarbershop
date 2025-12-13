# Docker Setup untuk WoxBarbershop

Dokumentasi lengkap untuk menjalankan aplikasi WoxBarbershop menggunakan Docker.

## 📋 Daftar Service

| Service | Port | Deskripsi |
|---------|------|-----------|
| **Nginx (FPM)** | 30000 | Web server untuk akses aplikasi Laravel |
| **PHPMyAdmin** | 30001 | Interface untuk manajemen database MySQL |
| **MySQL** | 30002 | Database server |

## 🚀 Cara Menjalankan

### 1. Pastikan .env sudah dikonfigurasi

Sesuaikan konfigurasi database di file `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=wox_barbershop
DB_USERNAME=wox_user
DB_PASSWORD=root
```

### 2. Build dan Jalankan Container

```bash
# Build dan jalankan semua service
docker-compose up -d --build

# Atau tanpa rebuild (jika sudah pernah build)
docker-compose up -d
```

### 3. Install Dependencies Laravel

```bash
# Masuk ke container app
docker-compose exec app bash

# Install composer dependencies
composer install

# Generate application key
php artisan key:generate

# Jalankan migration
php artisan migrate

# Jalankan seeder (opsional)
php artisan db:seed

# Keluar dari container
exit
```

### 4. Set Permission (jika diperlukan)

```bash
docker-compose exec app chown -R www-data:www-data /var/www/html/storage
docker-compose exec app chmod -R 775 /var/www/html/storage
docker-compose exec app chmod -R 775 /var/www/html/bootstrap/cache
```

## 🌐 Akses Aplikasi

Setelah semua container berjalan, Anda dapat mengakses:

- **Aplikasi Laravel**: http://localhost:30000
- **PHPMyAdmin**: http://localhost:30001
  - Server: `db`
  - Username: `root`
  - Password: sesuai dengan `DB_PASSWORD` di `.env` (default: `root`)

## 📝 Perintah-Perintah Berguna

### Melihat Status Container

```bash
docker-compose ps
```

### Melihat Logs

```bash
# Semua service
docker-compose logs -f

# Service tertentu
docker-compose logs -f app
docker-compose logs -f db
docker-compose logs -f nginx
```

### Menghentikan Container

```bash
# Hentikan semua service
docker-compose down

# Hentikan dan hapus volumes
docker-compose down -v
```

### Restart Container

```bash
# Restart semua service
docker-compose restart

# Restart service tertentu
docker-compose restart app
```

### Masuk ke Container

```bash
# Masuk ke container app (PHP-FPM)
docker-compose exec app bash

# Masuk ke container MySQL
docker-compose exec db bash

# Atau akses MySQL langsung
docker-compose exec db mysql -u root -p
```

### Menjalankan Artisan Command

```bash
# Tanpa masuk ke container
docker-compose exec app php artisan migrate
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan route:list
```

### Composer Commands

```bash
docker-compose exec app composer install
docker-compose exec app composer update
docker-compose exec app composer dump-autoload
```

### NPM Commands (jika diperlukan)

```bash
docker-compose exec app npm install
docker-compose exec app npm run dev
docker-compose exec app npm run build
```

## 🔧 Troubleshooting

### Port sudah digunakan

Jika port 30000, 30001, atau 30002 sudah digunakan, Anda dapat mengubahnya di `docker-compose.yml`:

```yaml
ports:
  - "PORT_BARU:80"  # untuk nginx
  - "PORT_BARU:3306"  # untuk mysql
```

### Permission Error pada Storage

```bash
docker-compose exec app chmod -R 777 storage
docker-compose exec app chmod -R 777 bootstrap/cache
```

### Clear Cache Laravel

```bash
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan view:clear
docker-compose exec app php artisan route:clear
```

### Rebuild Container

Jika ada perubahan pada Dockerfile:

```bash
docker-compose down
docker-compose up -d --build
```

### Hapus Semua Container dan Volume

```bash
docker-compose down -v --remove-orphans
docker-compose up -d --build
```

## 📊 Backup Database

### Export Database

```bash
docker-compose exec db mysqldump -u root -p wox_barbershop > backup.sql
```

### Import Database

```bash
docker-compose exec -T db mysql -u root -p wox_barbershop < backup.sql
```

## 🔒 Keamanan

Untuk production:

1. Ubah password database di `.env`
2. Jangan expose port MySQL (30002) ke public
3. Gunakan environment variables yang aman
4. Set `APP_DEBUG=false` di `.env`
5. Aktifkan HTTPS untuk Nginx

## 📚 Struktur File Docker

```
WoxBarbershop/
├── docker/
│   ├── nginx/
│   │   └── nginx.conf          # Konfigurasi Nginx
│   └── php/
│       └── local.ini            # Konfigurasi PHP
├── Dockerfile                   # Dockerfile untuk PHP-FPM
├── docker-compose.yml           # Orchestration semua service
└── .dockerignore               # File yang diabaikan saat build
```

## 💡 Tips

1. **Development**: Gunakan volumes untuk hot-reload tanpa rebuild
2. **Production**: Build image dengan kode yang sudah di-compile
3. **Database**: Volume MySQL akan persist data meskipun container dihapus
4. **Logs**: Gunakan `docker-compose logs -f` untuk debugging real-time

## 🆘 Support

Jika mengalami masalah, coba langkah berikut:

1. Cek logs: `docker-compose logs -f`
2. Restart container: `docker-compose restart`
3. Rebuild: `docker-compose down && docker-compose up -d --build`
4. Hapus semua: `docker-compose down -v && docker-compose up -d --build`
