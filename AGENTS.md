# AI Agent Instructions for Swap Hub

Welcome to **Swap Hub**. This repository contains a Laravel 12 project collaboration and skill-swapping platform designed for students. This document is the primary reference for AI agents (Antigravity, OpenAI Codex, Cursor, Claude Code, Windsurf, Aider, etc.) to understand the codebase, architectural conventions, execution environment, and quality standards.

---

## 1. Quick Overview & Tech Stack

- **Backend:** Laravel 12.x running on **PHP 8.4**
- **Frontend:** Laravel Blade, Livewire 3 (`livewire/livewire`), Tailwind CSS, Alpine.js, Bootstrap 5 (welcome landing page)
- **Database:** MariaDB 10.11 / MySQL (with migrations in `database/migrations`)
- **Cache & Queues:** Redis
- **Real-time Broadcasting:** Pusher / Laravel Reverb (`laravel/reverb`), Laravel Echo + Pusher JS
- **Calendar API:** Spatie Google Calendar (`spatie/laravel-google-calendar`)
- **PDF Generation:** Barryvdh DomPDF (`barryvdh/laravel-dompdf`)
- **Authentication:** Laravel Socialite (GitHub & Google OAuth) + Laravel Breeze email verification
- **Code Formatter / Linter:** Laravel Pint (`vendor/bin/pint`, preset: `laravel`)
- **Testing:** PHPUnit 11
- **Containerization & CI:** Docker, Docker Compose (Alpine-based multi-stage builds), Jenkins

---

## 2. Execution Environment & Command Matrix

> [!IMPORTANT]
> **Host PHP Mismatch:** The host machine may have an older PHP version (e.g., PHP 7.4), whereas this application requires **PHP ^8.2** (built on **PHP 8.4** in Docker).
> **Rule:** Whenever executing PHP, Composer, Artisan, or Pint commands, execute them **inside Docker** or using an on-demand container.

### Primary Commands (via Docker Compose)

```bash
# Start container stack (app, db, redis)
docker compose up -d
# or via composer script
composer compose:start

# View container logs
docker compose logs -f app

# Run Artisan commands
docker compose exec app php artisan <command>

# Run database migrations
docker compose exec app php artisan migrate

# Seed database
docker compose exec app php artisan db:seed

# Clear / Cache configuration
docker compose exec app php artisan optimize:clear
docker compose exec app php artisan optimize

# Run test suite
docker compose exec app php artisan test

# Check code formatting (Laravel Pint)
docker compose exec app php vendor/bin/pint --test

# Fix code formatting automatically
docker compose exec app php vendor/bin/pint
```

### Fallback Commands (Single Container Run without running compose stack)

If Docker Compose services are not running, you can run isolated commands using standard Docker images:

```bash
# Run tests
docker run --rm -v $(pwd):/var/www -w /var/www php:8.4-cli php artisan test

# Run Pint linting
docker run --rm -v $(pwd):/app -w /app php:8.4-cli php vendor/bin/pint --test

# Build frontend assets
docker run --rm -v $(pwd):/app -w /app node:20-alpine npm run build
```

---

## 3. Repository Architecture & Directory Map

```
swap-hub/
├── app/
│   ├── Events/                     # Broadcast and domain events (e.g. MessageSent)
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/              # Admin dashboard and user/project moderation
│   │   │   ├── Auth/               # Socialite (GoogleAuthController, GitHubAuthController) & Breeze auth
│   │   │   ├── ChatController.php  # Chat view routing
│   │   │   ├── DashboardController.php
│   │   │   ├── GitHubWebhookController.php # Ingests repo commits & triggers activities
│   │   │   ├── ProjectController.php       # Projects, memberships, matching, calendar
│   │   │   └── ResumeController.php        # PDF export of student profile/portfolio
│   │   ├── Middleware/             # Custom route middleware
│   │   └── Requests/               # Form validation requests
│   ├── Jobs/                       # Background queued jobs (Pusher sync, webhook processing)
│   ├── Livewire/
│   │   ├── Chat/                   # Real-time chat components (Room, MessageList, Input)
│   │   ├── Project/                # Project Kanban board, member requests, task cards
│   │   └── SystemHealth.php        # Real-time system monitoring component
│   ├── Mail/                       # Mailable classes for notifications
│   ├── Models/                     # Eloquent models (see Model Map below)
│   ├── Providers/                  # Service providers (AppServiceProvider, EventServiceProvider)
│   ├── Services/
│   │   ├── GitHubService.php       # GitHub API integration
│   │   └── GitHubWebhookService.php # Payload parsing, HMAC verification, reputation awards
│   └── View/                       # Custom Blade components
├── bootstrap/
│   └── app.php                     # Laravel 12 application bootstrap, middleware & CSRF exceptions
├── config/                         # Laravel configuration files
├── database/
│   ├── factories/                  # Model factories for testing
│   ├── migrations/                 # Database schema migrations
│   └── seeders/                    # Seeders (roles, skills, demo projects)
├── docker/                         # Docker entrypoints, nginx configs, PHP-FPM pool configs
├── docs/                           # Technical documentation (docker, OAuth, webhooks, Jenkins)
├── resources/
│   ├── css/                        # Tailwind CSS styles
│   ├── js/                         # Alpine.js, Echo/Pusher setup, Vite entrypoint
│   └── views/                      # Blade templates (admin, auth, projects, tasks, chat)
├── routes/
│   ├── auth.php                    # Authentication & OAuth routes
│   ├── channels.php                # Broadcast authorization channels for Echo/Pusher
│   ├── console.php                 # Artisan console commands
│   └── web.php                     # Main application web routes
├── tests/
│   ├── Feature/                    # Feature & HTTP endpoint tests
│   │   └── Auth/                   # Authentication tests
│   └── Unit/                       # Unit tests
├── docker-compose.yml              # Linux/macOS Docker Compose setup
├── docker-compose.windows.yml      # Windows-compatible Docker Compose setup
├── Dockerfile                      # Multi-stage production & development Dockerfile
├── Jenkinsfile                     # Automated CI/CD deployment pipeline
├── package.json                    # Frontend dependencies (Tailwind 3, Alpine, Echo, Vite)
├── pint.json                       # Laravel Pint formatting configuration
└── composer.json                   # PHP dependencies, autoloading, custom scripts
```

---

## 4. Domain Concepts & Business Workflows

### 4.1 Project Matchmaking & Skill Swapping
- **Users** have skills (`Skill` model via `skill_user` pivot).
- **Projects** require skills and open roles.
- Users apply for projects with their matching skills (`ProjectMember` model with status `pending`, `accepted`, `rejected`).
- When a project finishes, peer reviews award reputation points.

### 4.2 Interactive Kanban Task Board & Google Calendar Sync
- **Tasks** belong to a `Project` and have statuses: `todo`, `in_progress`, `done`.
- Tasks have priority (`low`, `medium`, `high`), assignees, and due dates.
- Each `Project` can have a synced `google_calendar_id`.
- Creating or editing a task with a due date triggers a Spatie Google Calendar event sync, storing `google_event_id` on the `Task` record.

### 4.3 Real-Time Chat & Pusher / Reverb
- Multi-user or direct chat rooms via `Conversation` and `Message`.
- Broadcasted on private channels defined in `routes/channels.php`.
- Supports Markdown formatting and file attachments via `MessageAttachment`.
- Automated GitHub commit events also post notifications into the associated project chat.

### 4.4 GitHub Webhook & Reputation System
- GitHub repositories connected to a project send push webhooks to `POST /api/github/webhook` (or configured webhook route).
- Route is exempt from CSRF verification in `bootstrap/app.php`.
- `GitHubWebhookService` verifies the webhook signature using `hash_hmac('sha256', ...)`.
- Valid commits are stored in `GitHubActivity`, member reputation is updated, and a message is broadcast to the project chat.

### 4.5 Multi-Provider OAuth
- Google OAuth and GitHub OAuth via Laravel Socialite.
- Handles both initial registration and linking to existing accounts.

---

## 5. Coding Standards & Conventions

### 5.1 PHP & Laravel Standards
- Always write modern **PHP 8.4** code (constructor property promotion, match expressions, explicit return types).
- Use typed properties and strict type declarations where appropriate.
- **Controllers:** Keep controllers thin. Delegate business logic to `app/Services` and data integrity/queries to Eloquent models or scopes.
- **Validation:** Use dedicated Form Request classes (`app/Http/Requests`) or Livewire's `#[Validate]` attributes. Do not perform manual validation inside controller actions.
- **Database Migrations:** Never edit previously committed migrations that may have run in production. Always create a new migration for schema changes (`php artisan make:migration ...`).
- **Eloquent Relationships:** Explicitly specify relationship return types (`BelongsTo`, `HasMany`, `BelongsToMany`) and foreign keys if non-standard.

### 5.2 Livewire & Blade Standards
- Use **Livewire 3** conventions:
  - Use `wire:model.live` or `wire:model.blur` thoughtfully to avoid unnecessary network overhead.
  - Utilize Alpine.js (`x-data`, `x-show`, `x-transition`) for purely client-side UI toggles (modals, dropdowns, tooltips) instead of round-tripping to the server.
- Follow Tailwind CSS utility classes; avoid inline styles.

### 5.3 Code Style & Formatting (Pint)
- Laravel Pint is configured in `pint.json`.
- Before completing any task involving PHP changes, ensure code complies with Pint formatting:
  ```bash
  docker compose exec app php vendor/bin/pint
  ```

---

## 6. Testing & Quality Assurance

- All new features and bug fixes must have corresponding tests in `tests/Feature` or `tests/Unit`.
- Use `RefreshDatabase` trait when testing database interactions.
- When testing Livewire components, use the Livewire testing utility:
  ```php
  Livewire::test(ProjectTaskList::class, ['project' => $project])
      ->call('updateTaskStatus', $taskId, 'done')
      ->assertHasNoErrors();
  ```
- Run tests via Docker:
  ```bash
  docker compose exec app php artisan test
  ```

---

## 7. AI Agent Guardrails & Common Gotchas

1. **Host Environment Trap:** Never assume `php` or `composer` commands can be run directly on the host shell without checking their version. Run them via Docker (`docker compose exec app ...` or `docker run --rm ...`).
2. **CSRF Protection on Webhooks:** Never remove the CSRF exemption for GitHub Webhook endpoints in `bootstrap/app.php`.
3. **Secrets & Credentials:** Never write real API secrets, Google Service Account JSON keys, or `.env` credentials into source files. Use `.env.example` to document required keys.
4. **Database Changes:** Always inspect existing migrations in `database/migrations` before proposing schema changes to maintain foreign key consistency and data types.
5. **Asset Compilation:** If changes are made to `resources/css` or `resources/js`, remind the user to recompile assets via `npm run build` or verify with `npm run dev`.
