# Swap Hub - LLMs Context & Developer Guide

> AI & Developer reference file detailing architecture, contracts, database models, background services, real-time websockets, and deployment blueprints.

## Project Overview
Swap Hub is a full-stack collaborative platform built with **Laravel 12**, **Livewire 3**, **Alpine.js**, **Tailwind CSS**, and **Laravel Reverb**. It enables university students to exchange peer-to-peer technical skills, collaborate on team projects with Kanban task boards, manage files, chat in real-time, integrate GitHub webhooks, and sync milestone deadlines with Google Calendar.

---

## Technical Stack
- **Framework:** Laravel 12.x (PHP 8.4)
- **Frontend / Reactivity:** Livewire 3 + Alpine.js + Tailwind CSS (Vite bundler)
- **Database:** MariaDB 10.11 / MySQL 8.0 (production/dev), SQLite in-memory (testing)
- **Cache & Session:** Redis 7.x (Alpine)
- **Real-time Broadcasting:** Self-hosted Laravel Reverb WebSocket (Port 8080 internal / Reverse Proxied via Nginx on Port 5541)
- **Queue Worker:** Redis queue driver
- **Containerization:** Multi-stage Alpine Dockerfile with Nginx + PHP-FPM 8.4 supervisor

---

## Core Domain Architecture

### 1. Skill Swap Engine (`/skills`)
- Allows students to list offered skills and request desired skills.
- Direct peer-to-peer swap proposals with state machine: `pending` -> `accepted` -> `completed` / `cancelled`.
- Awards reputation points on successful verification.

### 2. Project Collaboration Hub (`/projects`)
- Multi-member project workspaces.
- Real-time chat per project room (`/chat/{id}`).
- Task Kanban board with status transitions (`todo`, `in_progress`, `in_review`, `completed`).
- Project file repository and repository contribution tracker via GitHub Webhooks.

### 3. Real-Time Communication (`/chat`)
- Livewire 3 reactive chat interface with auto-scrolling and attachment upload validation.
- Laravel Reverb broadcast events (`MessageSent`, `TaskStatusUpdated`).
- Private and presence channels via `routes/channels.php`.

### 4. Third-Party Integrations
- **GitHub Webhooks & OAuth:** OAuth authentication via Socialite, push/PR reputation rewards with HMAC SHA256 validation.
- **Google Calendar API:** Background queue jobs sync project milestone events.
- **Mailers:** Markdown transactional emails for project applications and swap status changes.

---

## Key Directories & File Maps
```text
├── app/
│   ├── Events/          # Broadcasting events (MessageSent, TaskStatusUpdated)
│   ├── Http/Controllers# Web and API controllers (SkillSwap, Project, Health, etc.)
│   ├── Jobs/            # Asynchronous queue jobs (Google Calendar, Webhook dispatcher)
│   ├── Livewire/        # Reactive components (ChatPage, TaskBoard, SystemHealth)
│   ├── Models/          # Eloquent Models (User, Skill, Project, Task, SkillSwapRequest)
│   └── Services/        # Domain services (GitHubWebhookService, CalendarService)
├── config/
│   ├── broadcasting.php # Reverb broadcaster config
│   ├── reverb.php       # Server host/port/credentials
│   └── database.php     # MariaDB, Redis, and SQLite connections
├── docker/
│   ├── entrypoint.sh    # Container startup, migration, and Reverb supervisor
│   └── nginx/           # Nginx reverse proxy routing HTTP + WebSocket /app
├── resources/
│   ├── js/echo.js       # Dynamic Laravel Echo & Pusher-js client configuration
│   └── views/           # Blade & Livewire views
├── routes/
│   ├── web.php          # Web routes & authentication guards
│   └── channels.php     # Broadcast channel authorization
└── tests/
    ├── Feature/         # End-to-end feature test suite (Auth, Projects, Swaps, Health)
    └── Unit/            # Unit test suite (HMAC signature validation, helper tests)
```

---

## Environment & Configuration Matrix
| Key | Default / Example | Purpose |
|-----|-------------------|---------|
| `APP_PORT` | `5541` | Host port exposed for web and websocket proxy |
| `DB_CONNECTION` | `mysql` | Primary database driver |
| `DB_HOST` | `db` (docker) / `127.0.0.1` | MariaDB service hostname |
| `REDIS_HOST` | `redis` (docker) / `127.0.0.1` | Redis cache and queue broker |
| `BROADCAST_CONNECTION` | `reverb` | Real-time event broadcasting driver |
| `REVERB_HOST` | `0.0.0.0` | Reverb daemon bind address |
| `REVERB_PORT` | `8080` | Reverb daemon internal listening port |
| `VITE_REVERB_PORT` | `5541` | Client-facing WebSocket reverse proxy port |

---

## Common Development Commands
```bash
# Start Docker environment
docker compose up -d

# Run complete test suite
docker exec swap-hub-app-development php artisan test

# Check code style with Pint
docker exec swap-hub-app-development php vendor/bin/pint --test

# Build frontend assets
npm run build

# Seed admin and demo dataset
docker exec swap-hub-app-development php artisan db:seed
```
