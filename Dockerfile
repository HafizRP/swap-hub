# ==============================================================================
# Stage 1: Base PHP image with extensions
# ==============================================================================
FROM php:8.4-fpm AS base

# Add highly optimized PHP extension installer
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

# Install basic tools and let the installer handle complex extensions
RUN apt-get update && apt-get install -y --no-install-recommends zip unzip curl \
    && install-php-extensions pdo_mysql mbstring exif pcntl bcmath gd zip intl opcache \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www

# Copy Entrypoint and Expose port
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 9000
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


# ==============================================================================
# Stage 3: Frontend Builder (Production Only)
# ==============================================================================
FROM node:20-alpine AS frontend-builder

WORKDIR /app
COPY package*.json ./
RUN npm install --quiet
COPY . .
RUN npm run build


# ==============================================================================
# Stage 4: Backend Builder (Production Only)
# ==============================================================================
FROM composer:latest AS backend-builder

WORKDIR /app
COPY composer.json composer.lock ./
# Install production dependencies only
RUN composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev --ignore-platform-reqs --no-scripts


# ==============================================================================
# Stage 5: Production Environment
# ==============================================================================
FROM base AS production

LABEL maintainer="Swap Hub"
LABEL environment="production"

# Set production PHP configuration
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Copy vendor, assets, and source code
COPY --from=backend-builder /app/vendor ./vendor
COPY --from=frontend-builder /app/public/build ./public/build
COPY . .

# Copy production-grade configurations
COPY docker/php/conf.d/opcache.ini /usr/local/etc/php/conf.d/opcache.ini
COPY docker/php/conf.d/security.ini /usr/local/etc/php/conf.d/security.ini

# Fix permissions for Laravel storage and bootstrap cache
RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Production environment variables
ENV APP_ENV=production
ENV APP_DEBUG=false
