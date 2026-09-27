# CLAUDE.md — Claude Code Guidelines for Swap Hub

## Project Overview
Swap Hub is a Laravel 12 / PHP 8.4 platform for student project collaboration and skill swapping. Features include interactive Kanban boards, Spatie Google Calendar synchronization, real-time chat with Pusher/Reverb, GitHub webhook activity logging & reputation tracking, and multi-provider authentication.

---

## Essential Commands

> **Critical Note on Environment:** The host system may have an incompatible PHP version (PHP 7.4). Run all PHP, Artisan, Pint, and Composer commands via Docker.

### Development & Docker
```bash
# Start Docker environment
docker compose up -d
# or: composer compose:start

# View logs
docker compose logs -f app

# Run Artisan commands
docker compose exec app php artisan <command>

# Run migrations & seeders
docker compose exec app php artisan migrate
docker compose exec app php artisan db:seed

# Clear / optimize cache
docker compose exec app php artisan optimize:clear
```

### Testing & Code Quality
```bash
# Run PHPUnit tests
docker compose exec app php artisan test
# Single container fallback:
docker run --rm -v $(pwd):/var/www -w /var/www php:8.4-cli php artisan test

# Check code formatting (Laravel Pint)
docker compose exec app php vendor/bin/pint --test

# Fix code formatting
docker compose exec app php vendor/bin/pint

# Build frontend assets
docker run --rm -v $(pwd):/app -w /app node:20-alpine npm run build
```

---

## Code Style & Architecture

### Backend (PHP 8.4 / Laravel 12)
- Target **PHP 8.4** features (constructor property promotion, match expressions, typed properties).
- Keep controllers thin (`app/Http/Controllers`). Delegate complex domain logic to `app/Services` (e.g., `GitHubWebhookService.php`).
- Use dedicated Form Request classes for validation (`app/Http/Requests`).
- Maintain explicit return types on all controller methods, service methods, and Eloquent relationships (`BelongsTo`, `HasMany`, etc.).
- Never edit existing database migrations; create a new migration for schema changes.
- Ensure any PHP files comply with Laravel Pint standards (`pint.json`).

### Frontend (Livewire 3, Blade, Tailwind, Alpine.js)
- Use Livewire 3 components in `app/Livewire` paired with views in `resources/views/livewire`.
- Use `wire:model.live` or `wire:model.blur` prudently to avoid unnecessary network latency.
- Use Alpine.js (`x-data`, `x-show`, `x-cloak`) for client-side interactions (modals, dropdowns) without round-tripping to the server.
- Follow Tailwind CSS utility classes; avoid raw inline styles.

### Domain Integrations
- **Webhooks:** GitHub webhook route in `routes/web.php` or `routes/api.php` must remain exempt from CSRF in `bootstrap/app.php`. Signatures are verified with HMAC-SHA256 in `GitHubWebhookService`.
- **Calendar:** Spatie Google Calendar synchronization stores `google_calendar_id` on `Project` and `google_event_id` on `Task`.
- **Chat:** Pusher/Echo channels are authorized in `routes/channels.php`.
