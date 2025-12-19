# 🐳 Docker Deployment Guide

## Quick Start

Jalankan aplikasi dengan satu perintah:

```bash
docker-compose up -d --build
```

Aplikasi akan tersedia di:
- **Web Application:** http://localhost:8000
- **WebSocket (Reverb):** http://localhost:8080

## Setup Awal

### 1. Persiapan File Environment

```bash
# Copy file .env.example ke .env
cp .env.example .env

# Edit .env sesuai kebutuhan
# Pastikan DB_HOST=db (bukan localhost)
```

### 2. Build dan Jalankan Container

```bash
# Build dan jalankan semua container
docker-compose up -d --build

# Lihat status container
docker-compose ps

# Lihat logs
docker-compose logs -f app
```

### 3. Setup Database (Otomatis dengan Entrypoint)

Script `docker/entrypoint.sh` akan otomatis menjalankan:
- ✅ Menunggu database siap
- ✅ Generate APP_KEY jika belum ada
- ✅ Menjalankan migrasi database
- ✅ Cache config, routes, dan views
- ✅ Set permissions
- ✅ Create storage link

**Jika ingin menjalankan manual:**

```bash
# Generate application key
docker-compose exec app php artisan key:generate

# Jalankan migrasi
docker-compose exec app php artisan migrate --force

# Seed database (opsional)
docker-compose exec app php artisan db:seed
```

## Perintah Berguna

### Manajemen Container

```bash
# Start containers
docker-compose up -d

# Stop containers
docker-compose down

# Restart containers
docker-compose restart

# Rebuild containers
docker-compose up -d --build

# Lihat logs
docker-compose logs -f app
docker-compose logs -f nginx
docker-compose logs -f reverb
```

### Laravel Artisan Commands

```bash
# Jalankan artisan command
docker-compose exec app php artisan [command]

# Contoh:
docker-compose exec app php artisan migrate
docker-compose exec app php artisan db:seed
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan queue:work
```

### Composer & NPM

```bash
# Install PHP dependencies
docker-compose exec app composer install

# Install NPM dependencies
docker-compose exec app npm install

# Build assets
docker-compose exec app npm run build

# Watch assets (development)
docker-compose exec app npm run dev
```

### Database Access

```bash
# Akses MySQL console
docker-compose exec db mysql -u root -p

# Atau dengan user biasa
docker-compose exec db mysql -u ${DB_USERNAME} -p${DB_PASSWORD} ${DB_DATABASE}
```

### File Permissions

```bash
# Fix permissions jika ada error
docker-compose exec app chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
docker-compose exec app chmod -R 775 /var/www/storage /var/www/bootstrap/cache
```

## Troubleshooting

### Error: "pull access denied for swap-hub-app"

**Solusi:** Sudah diperbaiki! Service `reverb` sekarang menggunakan `build` configuration.

### Error: "Your lock file does not contain a compatible set of packages"

**Solusi:** Sudah diperbaiki dengan flag `--ignore-platform-reqs` di Dockerfile.

### Error: "Connection refused" ke database

**Solusi:** 
- Pastikan `DB_HOST=db` di file `.env`
- Tunggu beberapa detik sampai database siap
- Cek logs: `docker-compose logs db`

### Error: Permission denied

**Solusi:**
```bash
docker-compose exec app chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
docker-compose exec app chmod -R 775 /var/www/storage /var/www/bootstrap/cache
```

### Container terus restart

**Solusi:**
```bash
# Lihat logs untuk error
docker-compose logs -f app

# Cek status
docker-compose ps
```

## Production Deployment

### 1. Update Environment Variables

Edit `.env` untuk production:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

# Database
DB_HOST=db
DB_DATABASE=swap_hub
DB_USERNAME=your_user
DB_PASSWORD=strong_password

# Mail (gunakan SMTP real)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
```

### 2. Optimize untuk Production

```bash
# Cache everything
docker-compose exec app php artisan config:cache
docker-compose exec app php artisan route:cache
docker-compose exec app php artisan view:cache

# Optimize composer
docker-compose exec app composer install --optimize-autoloader --no-dev
```

### 3. Setup SSL/HTTPS

Tambahkan reverse proxy (Nginx/Caddy) di depan container atau gunakan Cloudflare.

## Container Architecture

```
┌─────────────────────────────────────────────────┐
│                  swap-hub-nginx                 │
│              (Port 8000 → 80)                   │
└────────────────────┬────────────────────────────┘
                     │
        ┌────────────┴────────────┐
        │                         │
┌───────▼────────┐      ┌────────▼────────┐
│  swap-hub-app  │      │ swap-hub-reverb │
│   (PHP-FPM)    │      │  (WebSocket)    │
│   Port 9000    │      │   Port 8080     │
└───────┬────────┘      └────────┬────────┘
        │                        │
        └────────┬───────────────┘
                 │
        ┌────────┴────────┐
        │                 │
┌───────▼────────┐ ┌─────▼──────┐
│  swap-hub-db   │ │swap-hub-redis│
│   (MySQL)      │ │   (Redis)    │
└────────────────┘ └──────────────┘
```

## Environment Variables

| Variable | Default | Description |
|----------|---------|-------------|
| `APP_NAME` | Laravel | Nama aplikasi |
| `APP_ENV` | local | Environment (local/production) |
| `APP_DEBUG` | true | Debug mode |
| `DB_HOST` | db | Database host (gunakan 'db' untuk Docker) |
| `DB_DATABASE` | swap_hub | Nama database |
| `DB_USERNAME` | root | Database user |
| `DB_PASSWORD` | - | Database password |

## Backup & Restore

### Backup Database

```bash
# Backup database
docker-compose exec db mysqldump -u root -p${DB_PASSWORD} ${DB_DATABASE} > backup.sql

# Atau dengan docker
docker-compose exec -T db mysqldump -u root -p${DB_PASSWORD} ${DB_DATABASE} > backup_$(date +%Y%m%d).sql
```

### Restore Database

```bash
# Restore database
docker-compose exec -T db mysql -u root -p${DB_PASSWORD} ${DB_DATABASE} < backup.sql
```

## Monitoring

```bash
# Lihat resource usage
docker stats

# Lihat logs real-time
docker-compose logs -f --tail=100

# Inspect container
docker-compose exec app bash
```

---

**Need Help?** Check logs dengan `docker-compose logs -f` untuk melihat error messages.
