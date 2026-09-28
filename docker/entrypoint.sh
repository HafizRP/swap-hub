#!/bin/sh
set -e

# Ensure storage and bootstrap cache structure exist
mkdir -p /var/www/storage/logs \
         /var/www/storage/framework/sessions \
         /var/www/storage/framework/views \
         /var/www/storage/framework/cache/data \
         /var/www/bootstrap/cache

touch /var/www/storage/logs/laravel.log

# Ensure proper permissions for runtime directories
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache 2>/dev/null || true
chmod -R 775 /var/www/storage /var/www/bootstrap/cache 2>/dev/null || true

# Run setup when launching the main service
if [ "$1" = "php-fpm" ]; then
    echo "🚀 Starting Laravel application setup..."

    # Clear potentially stale cache files from mounted volumes
    rm -f /var/www/bootstrap/cache/*.php

    # Generate application key if missing
    if [ ! -f ".env" ] || ! grep -q "APP_KEY=base64:" .env 2>/dev/null; then
        if [ -f ".env.example" ] && [ ! -f ".env" ]; then
            cp .env.example .env
        fi
        echo "🔑 Generating application key..."
        php artisan key:generate --no-interaction --force || true
    fi

    # Build frontend assets if missing in development
    if [ "$APP_ENV" = "development" ] || [ "$APP_ENV" = "local" ]; then
        if [ ! -d "public/build" ] && command -v npm > /dev/null 2>&1; then
            echo "📦 Building frontend assets for development..."
            npm run build
        fi
    fi

    # Create storage link if not exists
    if [ ! -L public/storage ]; then
        echo "🔗 Creating storage link..."
        php artisan storage:link || true
    fi

    # Run database migrations
    if [ "$APP_ENV" = "development" ] || [ "$APP_ENV" = "local" ]; then
        echo "🗄️ Running database migrations and seeders..."
        php artisan migrate --no-interaction || true
        php artisan db:seed --no-interaction || true
        echo "⚡ Clearing caches for development..."
        php artisan optimize:clear || true
    else
        echo "🗄️ Running database migrations..."
        php artisan migrate --force --no-interaction || true
        echo "⚡ Optimizing application for production..."
        php artisan optimize || true
    fi

    echo "✨ Application setup complete!"
fi

# Start Reverb server in background with supervisor loop when running php-fpm
if [ "$1" = "php-fpm" ]; then
    echo "🔊 Starting Reverb WebSocket server supervisor..."
    (
        while true; do
            php artisan reverb:start --host=0.0.0.0 --port=8080 >> /var/www/storage/logs/reverb.log 2>&1
            sleep 2
        done
    ) &
fi

# Start Nginx in background when running php-fpm
if [ "$1" = "php-fpm" ]; then
    echo "🌐 Starting Nginx webserver..."
    nginx
fi

echo "🚀 Starting command: $@..."
exec "$@"

