#!/bin/bash
set -e

# Ensure logs directory and file exist
mkdir -p /var/www/storage/logs
touch /var/www/storage/logs/laravel.log

# Set proper permissions at the very beginning
echo "🔒 Setting permissions for storage and cache..."
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
chmod -R 775 /var/www/storage /var/www/bootstrap/cache

echo "🚀 Starting Laravel application setup..."

# Wait for database to be ready
if [ "$1" = "php-fpm" ]; then

    # Create storage link if not exists
    if [ ! -L public/storage ]; then
        echo "🔗 Creating storage link..."
        php artisan storage:link || true
    fi

    # Migrate database
    if [ "$APP_ENV" = "development" ] || [ "$APP_ENV" = "local" ]; then
        echo "🗄️ Running database migrations and seeders..."
        php artisan migrate || true
        php artisan db:seed || true
    else
        echo "🗄️ Running database migrations..."
        php artisan migrate || true
    fi

    if [ "$APP_ENV" = "development" ] || [ "$APP_ENV" = "local" ]; then
        echo "⚡ Clearing caches for development..."
        php artisan optimize:clear
    else
        echo "⚡ Optimizing application for production..."
        php artisan optimize
    fi

    echo "✨ Application setup complete!"
fi

echo "🌐 Starting command: $@..."
exec "$@"
