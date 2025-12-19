#!/bin/bash
set -e

echo "🚀 Starting Laravel application setup..."

# Wait for database to be ready
if [ "$1" = "php-fpm" ]; then
    echo "⏳ Waiting for database connection..."
    max_tries=30
    count=0
    until php artisan db:show --database=mysql > /dev/null 2>&1 || [ $count -eq $max_tries ]; do
        count=$((count+1))
        echo "Database not ready, waiting... ($count/$max_tries)"
        sleep 2
    done
    
    if [ $count -eq $max_tries ]; then
        echo "⚠️  Database connection timeout, but continuing..."
    else
        echo "✅ Database is ready!"
    fi

    # Generate application key if not set
    if ! grep -q "APP_KEY=base64:" .env 2>/dev/null; then
        echo "🔑 Generating application key..."
        php artisan key:generate --force
    fi

    # Run migrations
    echo "🗄️  Running database migrations..."
    php artisan migrate --force || echo "⚠️  Migration failed, continuing..."

    # Create storage link if not exists
    if [ ! -L public/storage ]; then
        echo "🔗 Creating storage link..."
        php artisan storage:link || true
    fi

    # Clear and cache config
    echo "⚡ Optimizing application..."
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache

    # Set proper permissions
    echo "🔒 Setting permissions..."
    chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
    chmod -R 775 /var/www/storage /var/www/bootstrap/cache

    echo "✨ Application setup complete!"
fi

echo "🌐 Starting $@..."

# Execute the main command (php-fpm or reverb)
exec "$@"
