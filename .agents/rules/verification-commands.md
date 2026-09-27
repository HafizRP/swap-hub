# Verification Commands & Testing Standards

## Host Environment Alert
The host machine may default to an older PHP version (PHP 7.4). The application requires **PHP 8.4**.
**Rule:** Always run verification commands through Docker.

## Primary Verification Commands
```bash
# Run complete test suite
docker compose exec app php artisan test

# Run single test file
docker compose exec app php artisan test tests/Feature/ExampleTest.php

# Check code formatting (Pint)
docker compose exec app php vendor/bin/pint --test

# Fix formatting automatically
docker compose exec app php vendor/bin/pint

# Run migrations in test environment
docker compose exec app php artisan migrate --env=testing
```

## Fallback Commands (Without Running Stack)
```bash
# Run tests
docker run --rm -v $(pwd):/var/www -w /var/www php:8.4-cli php artisan test

# Check Pint styling
docker run --rm -v $(pwd):/app -w /app php:8.4-cli php vendor/bin/pint --test

# Build frontend assets
docker run --rm -v $(pwd):/app -w /app node:20-alpine npm run build
```
