# Stage 1: Build Frontend Assets
FROM node:20-alpine AS frontend

WORKDIR /app
COPY package*.json ./
COPY vite.config.js ./
COPY resources/ ./resources/
COPY public/ ./public/
# Copy env file to ensure Vite can read environment variables during build
COPY env-server.txt .env

RUN npm install
RUN npm run build

# Stage 2: Build Backend Dependencies
FROM composer:latest AS backend

WORKDIR /app
COPY composer.json composer.lock ./
COPY artisan ./
COPY app/ ./app/
COPY bootstrap/ ./bootstrap/
COPY config/ ./config/
COPY database/ ./database/
COPY routes/ ./routes/
COPY public/ ./public/
COPY resources/ ./resources/
COPY storage/ ./storage/

# Install production dependencies only, optimized autoloader
RUN composer install --no-interaction --prefer-dist --optimize-autoloader --ignore-platform-reqs --no-dev
# Install Pusher PHP SDK
RUN composer require pusher/pusher-php-server --no-interaction --ignore-platform-reqs

# Stage 3: Production Image
FROM php:8.4-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    libicu-dev \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip intl

# Install OpCache
RUN docker-php-ext-install opcache
COPY docker/php/conf.d/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

# Set working directory
WORKDIR /var/www

# Copy backend dependencies from stage 2
COPY --from=backend /app/vendor /var/www/vendor

# Copy frontend assets from stage 1
COPY --from=frontend /app/public/build /var/www/public/build

# Copy application code
COPY . .

# Ensure .env exists (fallback)
RUN if [ -f env-server.txt ]; then \
        cp env-server.txt .env; \
    else \
        cp .env.example .env; \
    fi

# Set permissions
RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 /var/www/storage \
    && chmod -R 775 /var/www/bootstrap/cache

# Copy entrypoint script
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 9000

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["php-fpm"]

