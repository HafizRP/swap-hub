# GitHub Copilot Instructions for Swap Hub

This repository contains **Swap Hub**, a student collaboration and skill-swapping platform built on **Laravel 12 (PHP 8.4)**, **Livewire 3**, **Tailwind CSS**, and **Alpine.js**.

## Core Development Guidelines

### 1. PHP & Laravel Conventions
- Target **PHP 8.4** syntax (typed properties, parameter types, explicit method return types, constructor property promotion).
- Follow PSR-12 and Laravel Pint style standards.
- In Eloquent models, always declare explicit return types on relationship methods (e.g. `: BelongsTo`, `: HasMany`, `: BelongsToMany`).
- Use dedicated Form Request classes for HTTP validation instead of validating in controller actions.
- Avoid placing heavy business or integration logic inside controllers; place them in `app/Services` (e.g., `GitHubService`, `GitHubWebhookService`).
- When writing migrations, never modify existing migration files. Always create a new migration for schema changes.

### 2. Livewire 3 & Frontend Conventions
- Livewire components reside in `app/Livewire/` and corresponding templates in `resources/views/livewire/`.
- Use `wire:model.live` or `wire:model.blur` thoughtfully to prevent unnecessary server requests.
- Use Alpine.js (`x-data`, `x-show`, `x-cloak`) for client-side state and UI animations (modals, dropdowns, tabs) rather than triggering server round-trips.
- Style components with Tailwind CSS utility classes. Avoid inline style attributes.

### 3. Integrations & Security
- **GitHub Webhook:** Webhook endpoints must be excluded from CSRF verification in `bootstrap/app.php` and use HMAC-SHA256 signature verification via `GitHubWebhookService`.
- **Google Calendar:** Calendar operations use `spatie/laravel-google-calendar`. Tasks link to calendar events via `google_event_id` and projects store `google_calendar_id`.
- **Real-Time Broadcast:** Real-time chat uses Pusher / Laravel Reverb. Private channels are authorized in `routes/channels.php`.

### 4. Running Commands & Testing
- The host system default PHP version may be older (PHP 7.4). Always run PHP commands inside Docker:
  - Run tests: `docker compose exec app php artisan test`
  - Code style fix: `docker compose exec app php vendor/bin/pint`
  - Artisan commands: `docker compose exec app php artisan <command>`
