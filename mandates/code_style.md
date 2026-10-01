# ARCHITECTURAL & CODE STYLE CONVENTIONS — SWAP HUB

## 1. PHP 8.4 & Laravel 12 Standards
- **Strict Typing**: Declare `declare(strict_types=1);` where appropriate.
- **Typed Properties & Returns**: All class properties, method parameters, and return types must be explicitly typed.
- **Modern PHP Features**: Use constructor property promotion, `match` expressions, nullsafe operators (`?->`), and readonly properties when suitable.
- **Laravel Pint**: All PHP code must pass Laravel Pint styling (`vendor/bin/pint --test`, preset: `laravel`). Run `vendor/bin/pint` before committing.

## 2. Layered Architecture
- **Controllers**: Keep controllers thin. They should only handle HTTP concerns: validate request via `FormRequest`, call service/action, return view or JSON response.
- **Form Requests**: All request validation belongs in dedicated `app/Http/Requests/*` classes, never inline in controller methods.
- **Services**: Business logic, external API calls, and complex data flows belong in `app/Services/*`.
- **Eloquent Models**:
  - Define explicit `$fillable` array.
  - Define `casts(): array` method for attribute casting.
  - Return typed relationships (`BelongsTo`, `HasMany`, `BelongsToMany`).

## 3. Frontend & Livewire 3 Conventions
- **Livewire Components**: Place in `app/Livewire/*` with corresponding views in `resources/views/livewire/*`.
- **Data Binding**: Prefer `wire:model.blur` or `wire:model.live.debounce.300ms` over eager `wire:model` to minimize network overhead.
- **Validation**: Use `#[Validate('...')]` PHP attributes on Livewire public properties.
- **Client Interactions**: Use Alpine.js (`x-data`, `x-show`, `x-cloak`, `x-transition`) for pure UI toggles (modals, dropdowns, tabs) rather than round-trips to the server.
- **Styling**: Use utility-first Tailwind CSS classes consistent with `tailwind.config.js`. Avoid raw inline styles or custom uncompiled CSS.

## 4. Background Workers & Queues
- **Queue Connection**: Default queue connection is `database`.
- **Idempotency**: All jobs in `app/Jobs/*` must be idempotent — capable of being retried without duplicating side effects.
- **Timeouts & Retries**: Explicitly set `$tries = 3` and `$timeout = 30` on queued jobs.
- **Graceful Degradation**: External API failures (GitHub API rate limits, Google Calendar errors) must be caught, logged, and handled gracefully without crashing the queue worker.

## 5. Testing Standards
- **Feature Tests**: Place in `tests/Feature/*`. Test end-to-end HTTP requests, authentication states, and database side effects.
- **Unit Tests**: Place in `tests/Unit/*`. Test isolated service logic and utility methods.
- **Refresh Database**: Use `Illuminate\Foundation\Testing\RefreshDatabase` trait for database test isolation.
- **Coverage**: Every new endpoint or service method must have corresponding passing test assertions.
