# Goal: Fix Audit Issues Across Security, Bugs, Docker, Pint, and Tests

## User Request

Fix semua yang tadi dicheck, jangan berhenti sampai semuanya sudah fix & tested.

## Refined Goal

Resolve all security vulnerabilities, runtime/functional bugs, Docker/CI execution traps, code formatting issues, and test coverage gaps identified during the repository audit. Ensure the full test suite passes with external APIs mocked, and ensure Laravel Pint formatting passes with zero style violations.

## Acceptance Criteria

- [ ] Criterion 1 (Security - RCE & Uploads): Nginx configuration blocks execution of `.php` files inside `/storage/` and public upload paths; `ChatPage` strictly validates uploaded file MIME types and extensions (rejecting executable scripts).
- [ ] Criterion 2 (Security - GitHub Webhook HMAC): `GitHubWebhookService` and `GitHubWebhookController` enforce HMAC-SHA256 signature verification (`X-Hub-Signature-256`) using a configured webhook secret before processing push payloads or awarding reputation.
- [ ] Criterion 3 (Security - Stored XSS & SSRF): Chat markdown rendering in `chat-page.blade.php` is sanitized against XSS (script tags, img onerror, javascript: links); `ResumeController` validates avatar URLs to prevent SSRF against internal/private IPs and cloud metadata services.
- [ ] Criterion 4 (Security - Authorization & SQL Query Safety): `TaskBoard` Livewire component authorizes user membership before task creation, status updates, or deletion, and validates status values; Admin controllers (`UserController`, `ProjectController`) validate sort and order fields against an allowlist.
- [ ] Criterion 5 (Bugs - Roles & OAuth): `Admin\UserController` correctly updates `role_id` and checks `Role` model slugs (`admin`, `student`) instead of querying the dropped `role` column; `GitHubAuthController` and `GoogleAuthController` assign default student `role_id` upon new user registration.
- [ ] Criterion 6 (Bugs - Configuration & Env): `SystemHealth.php` retrieves Pusher/Reverb host and port from `config()` rather than `env()`; duplicate `BROADCAST_CONNECTION` entry in `.env.example` is resolved.
- [ ] Criterion 7 (Docker & CI): `docker/entrypoint.sh` only executes automated migrations and config optimization for `php-fpm` (not generic `php` CLI commands); `Jenkinsfile` test stage uses an environment equipped with the required database driver (`pdo_sqlite` / application container).
- [ ] Criterion 8 (Code Style - Pint): Running Laravel Pint (`vendor/bin/pint --test`) produces 0 style violations across all files.
- [ ] Criterion 9 (Automated Tests): Comprehensive Feature/Unit tests are implemented for Project operations, Webhook HMAC verification, Task management, and Admin role management; all external services (Google Calendar, GitHub) are mocked with `Http::fake()` or mock objects; test suite executes and passes 100%.

## Scope Boundaries

**In scope:**
- Security fixes (Nginx config, chat attachment validation, webhook HMAC verification, XSS sanitization, SSRF protection, TaskBoard authorization, admin sort allowlisting).
- Functional bug fixes (Admin role_id mapping, OAuth user role assignment, SystemHealth config calls).
- Docker entrypoint and Jenkinsfile test configuration adjustments.
- Codebase-wide Pint formatting fixes.
- New automated Feature tests for Projects, Tasks, Webhooks, and Admin Users.

**Out of scope:**
- UI redesign or non-essential feature additions beyond the audited issues.
- Modifying previously committed migrations in `database/migrations/` (new schema changes only if strictly necessary).

## Applicable Project Conventions

**Quality gate command:**
- Lint: `docker run --rm -v $(pwd):/app -w /app php:8.4-cli php vendor/bin/pint --test` (or inside container: `php vendor/bin/pint --test`)
- Test: `docker run --rm --entrypoint php -v $(pwd):/var/www swap-hub-app-development:latest artisan test` (or inside app container: `php artisan test`)
- Preflight: Pint test must pass AND Artisan test suite must pass with 0 failures.

**Commit convention:**
- Conventional Commits: `type(scope): [B] description` (Builder) or `chore(scope): [I] description` (Inspector)
- Trailer:
  - Builder: `Assisted-by: OpenAI:GPT-5.6 Luna`
  - Inspector: `Assisted-by: OpenAI:GPT-5.6 Sol`

**Guidelines:**
- PHP 8.4 syntax, strict typing, explicit return types on relationships and controller methods.
- Livewire 3 conventions.
- External API calls (Google Calendar, GitHub) must be mocked/faked in test suites.

**Rules:**
- Run PHP/Composer commands in Docker environment.
- Keep controllers thin and use Form Requests where applicable.
