# Laravel 12 & Livewire 3 Coding Conventions

## 1. PHP 8.4 Standards
- Target **PHP 8.4**. Use constructor property promotion, strict typing, typed properties, and explicit return types on methods.
- Avoid loose typings in model attributes where type casting can be declared via `casts()`.

## 2. Controllers & Routing
- Keep controllers thin. Controller methods should orchestrate validation, service invocation, and view/JSON response generation.
- Place all validation inside dedicated `FormRequest` classes in `app/Http/Requests/`.
- Never put database transactions or complex external API queries directly inside controller actions; delegate them to `app/Services/`.

## 3. Eloquent Models & Database
- Always define explicit return types on relationship methods (e.g., `public function members(): HasMany`, `public function skills(): BelongsToMany`).
- Maintain the `$fillable` array on all models to prevent mass assignment vulnerabilities.
- Never modify existing database migrations in `database/migrations/`. Always create a new migration for schema changes.

## 4. Livewire 3 Components
- Livewire class components live under `app/Livewire/` and views under `resources/views/livewire/`.
- Use PHP attributes like `#[Validate]` for reactive form inputs.
- Prefer `wire:model.blur` or deferred updates for text fields to minimize network round-trips.
- Use Alpine.js for client-side toggles (modals, dropdowns, accordions).

## 5. Code Style & Pint
- Code style is enforced by Laravel Pint using the configuration in `pint.json`.
- Before finishing any PHP-related task, format code with Pint:
  ```bash
  docker compose exec app php vendor/bin/pint
  ```
