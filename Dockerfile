# Default target environment (development or production)
ARG TARGET_ENV=production

# ==============================================================================
# Stage 1: Base PHP Alpine image with runtime extensions & minimal tools
# ==============================================================================
FROM php:8.4-fpm-alpine AS base

# Add PHP extension installer helper
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

# Install essential runtime tools & PHP extensions in a single cached layer
RUN apk add --no-cache \
    bash \
    curl \
    nginx \
    ca-certificates \
    tzdata \
    su-exec \
    && install-php-extensions \
    pdo_mysql \
    exif \
    pcntl \
    bcmath \
    gd \
    zip \
    intl \
    redis \
    && rm -rf /tmp/* /var/cache/apk/* /usr/local/bin/install-php-extensions

# Configure Alpine Nginx
COPY docker/nginx/conf.d/app.conf /etc/nginx/http.d/default.conf

WORKDIR /var/www

# Copy Entrypoint and Expose ports
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN sed -i 's/\r$//' /usr/local/bin/entrypoint.sh && chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 80 9000
HEALTHCHECK --interval=30s --timeout=5s --start-period=20s --retries=3 \
    CMD curl -f http://127.0.0.1:80/ || exit 1
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
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
ENV COMPOSER_PROCESS_TIMEOUT=1800

# Install Node.js, NPM, and Git for development
RUN apk add --no-cache nodejs npm git

# Leverage layer caching: Copy dependency manifests first
COPY composer.json composer.lock ./
COPY package.json package-lock.json* ./

# Install all dependencies (including dev)
RUN --mount=type=cache,target=/root/.composer/cache \
    composer install --no-interaction --prefer-dist --optimize-autoloader --no-scripts
RUN --mount=type=cache,target=/root/.npm \
    npm install && npm cache clean --force

# Copy application source code
COPY . .


# ==============================================================================
# Stage 3: Frontend Builder (Production Only)
# ==============================================================================
FROM node:20-alpine AS frontend-builder

WORKDIR /app

# Leverage layer caching for Node dependencies
COPY package.json package-lock.json* ./
RUN --mount=type=cache,target=/root/.npm \
    npm ci --prefer-offline --no-audit

# Copy application source and build frontend assets
COPY . .
RUN npm run build


# ==============================================================================
# Stage 4: Backend Builder (Production Only)
# ==============================================================================
FROM composer:2 AS backend-builder

WORKDIR /app

ENV COMPOSER_PROCESS_TIMEOUT=1800

# Leverage layer caching for PHP dependencies
COPY composer.json composer.lock ./

# Install production dependencies without autoloader to maximize cache usage
RUN --mount=type=cache,target=/root/.composer/cache \
    composer install --no-dev --no-interaction --prefer-dist --no-autoloader --no-scripts --ignore-platform-reqs

# Copy application code and generate optimized autoloader for production
COPY . .
RUN composer dump-autoload --optimize --classmap-authoritative --no-dev \
    && find vendor/ -type d \( -name ".git" -o -name "tests" -o -name "test" -o -name "docs" -o -name ".github" \) -exec rm -rf {} + 2>/dev/null || true \
    && find vendor/ -type f \( -name "*.md" -o -name "CHANGELOG*" -o -name "CONTRIBUTING*" -o -name "UPGRADE*" -o -name "README*" \) -exec rm -f {} + 2>/dev/null || true


# ==============================================================================
# Stage 5: Production Environment (Ultra-lightweight)
# ==============================================================================
FROM base AS production

LABEL maintainer="Swap Hub"
LABEL environment="production"

# Set production PHP configuration
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Copy production-grade configurations
COPY docker/php/conf.d/opcache.ini $PHP_INI_DIR/conf.d/opcache.ini
COPY docker/php/conf.d/security.ini $PHP_INI_DIR/conf.d/security.ini

# Copy application code with proper ownership to avoid duplicate filesystem layers
COPY --chown=www-data:www-data . .

# Copy vendor and compiled assets from builders
COPY --chown=www-data:www-data --from=backend-builder /app/vendor ./vendor
COPY --chown=www-data:www-data --from=frontend-builder /app/public/build ./public/build

# Set up storage and cache structure with proper permissions & clean unnecessary files
RUN mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs bootstrap/cache \
    && rm -rf docker docs tests .dockerignore phpunit.xml \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Production environment variables
ENV APP_ENV=production
ENV APP_DEBUG=false


# ==============================================================================
# Final Stage
# ==============================================================================
FROM ${TARGET_ENV}

