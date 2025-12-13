# 🐳 Docker Quick Start Guide

## 🚀 Cara Tercepat untuk Memulai

### Otomatis (Recommended)
```bash
./docker-setup.sh
```

### Manual

#### 1. Build & Start
```bash
docker-compose up -d --build
```

#### 2. Setup Laravel
```bash
# Install dependencies
docker-compose exec app composer install

# Generate key
docker-compose exec app php artisan key:generate

# Run migrations
docker-compose exec app php artisan migrate

# Set permissions
docker-compose exec app chmod -R 775 storage bootstrap/cache
```

## 🌐 Akses Aplikasi

| Service | URL | Username | Password |
|---------|-----|----------|----------|
| **Laravel App** | http://localhost:30000 | - | - |
| **PHPMyAdmin** | http://localhost:30001 | root | root |
| **MySQL** | localhost:30002 | wox_user | root |

## ⚙️ Konfigurasi .env

**Penting!** Pastikan konfigurasi database di `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=db              # HARUS 'db', bukan 'localhost'
DB_PORT=3306
DB_DATABASE=wox_barbershop
DB_USERNAME=wox_user
DB_PASSWORD=root
```

## 📝 Perintah Penting

```bash
# Lihat status
docker-compose ps

# Lihat logs
docker-compose logs -f

# Restart
docker-compose restart

# Stop
docker-compose down

# Stop + hapus data
docker-compose down -v

# Masuk ke container
docker-compose exec app bash

# Artisan commands
docker-compose exec app php artisan [command]

# Composer commands
docker-compose exec app composer [command]
```

## 🔧 Troubleshooting

### Port sudah digunakan?
Edit `docker-compose.yml`:
- Ubah `30000:80` menjadi `PORT_ANDA:80`
- Ubah `30001:80` menjadi `PORT_ANDA:80`
- Ubah `30002:3306` menjadi `PORT_ANDA:3306`

### Permission error?
```bash
docker-compose exec app chmod -R 777 storage bootstrap/cache
```

### MySQL connection error?
Pastikan `DB_HOST=db` di file `.env` (bukan `localhost`!)

### Clear semua cache
```bash
docker-compose exec app php artisan optimize:clear
```

## 📦 Struktur File

```
WoxBarbershop/
├── docker/
│   ├── nginx/nginx.conf     # Konfigurasi web server
│   └── php/local.ini        # Konfigurasi PHP
├── Dockerfile               # Image PHP-FPM
├── docker-compose.yml       # Orchestration services
├── docker-setup.sh         # Setup otomatis
└── .dockerignore           # Exclude files
```

## 📚 Dokumentasi Lengkap

Lihat `DOCKER_SETUP.md` untuk dokumentasi lengkap dan tips advanced.

## 💡 Tips

- **Development**: Kode Anda otomatis sync dengan container (hot reload)
- **Database**: Data MySQL persist di volume `mysql_data`
- **Logs**: Gunakan `docker-compose logs -f [service]` untuk debugging
- **Performance**: Tutup container yang tidak dipakai dengan `docker-compose down`

---

**Butuh bantuan?** Cek `DOCKER_SETUP.md` atau lihat logs dengan `docker-compose logs -f`
