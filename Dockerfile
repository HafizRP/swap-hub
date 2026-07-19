# Default target environment (development or production)
ARG TARGET_ENV=development

# ==============================================================================
# Stage 1: Base PHP image with extensions
# ==============================================================================
FROM php:8.4-fpm AS base

# Add highly optimized PHP extension installer
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

# Install basic tools and let the installer handle complex extensions
RUN apt-get update && apt-get install -y --no-install-recommends \
    zip unzip curl nginx \
    && install-php-extensions pdo_mysql mbstring exif pcntl bcmath gd zip intl opcache \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Configure internal Nginx for standalone HTTP access using app.conf
COPY docker/nginx/conf.d/app.conf /etc/nginx/sites-available/default

WORKDIR /var/www

# Copy Entrypoint and Expose port
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 9000 80
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["php-fpm"]

# ==============================================================================
# Stage 2: Development Environment
# ==============================================================================
FROM base AS development

LABEL environment="development"

# Set development PHP configuration
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install Node.js and NPM for frontend development
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Leverage layer caching: Copy dependency files first
COPY composer.json composer.lock ./
COPY package.json package-lock.json* ./

# Install all dependencies (including dev)
RUN composer install --no-interaction --prefer-dist --optimize-autoloader --no-scripts \
    && npm install

# Copy the rest of the application
COPY . .


# ==============================================================================
# Stage 3: Frontend Builder (Production Only)
# ==============================================================================
FROM node:20-alpine AS frontend-builder

WORKDIR /app

# Leverage layer caching for Node dependencies
COPY package.json package-lock.json* ./
RUN npm ci

# Copy the rest of the application and build frontend assets
COPY . .
RUN npm run build


# ==============================================================================
# Stage 4: Backend Builder (Production Only)
# ==============================================================================
FROM composer:latest AS backend-builder

WORKDIR /app

# Leverage layer caching for PHP dependencies
COPY composer.json composer.lock ./
# Install dependencies without autoloader and scripts to maximize cache usage
RUN composer install --no-interaction --prefer-dist --no-dev --no-autoloader --no-scripts --ignore-platform-reqs

# Copy application code and generate optimized autoloader for production
COPY . .
RUN composer dump-autoload --optimize --no-dev


# ==============================================================================
# Stage 5: Production Environment
# ==============================================================================
FROM base AS production

LABEL maintainer="Swap Hub"
LABEL environment="production"

# Set production PHP configuration
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Copy production-grade configurations
COPY docker/php/conf.d/opcache.ini $PHP_INI_DIR/conf.d/opcache.ini
COPY docker/php/conf.d/security.ini $PHP_INI_DIR/conf.d/security.ini

# Copy application code FIRST
COPY . .

# Then copy vendor and assets from builders to overlay the app code
COPY --from=backend-builder /app/vendor ./vendor
COPY --from=frontend-builder /app/public/build ./public/build

# Fix permissions for Laravel storage and bootstrap cache
RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Production environment variables
ENV APP_ENV=production
ENV APP_DEBUG=false

# ==============================================================================
# Final Stage
# ==============================================================================
# This stage uses the TARGET_ENV build argument to determine which stage to
# build. By default, it builds the 'development' stage.
FROM ${TARGET_ENV}
