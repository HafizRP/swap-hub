# 🔄 Swap Hub

[![CI/CD Pipeline](https://github.com/HafizRP/swap-hub/actions/workflows/ci.yml/badge.svg)](https://github.com/HafizRP/swap-hub/actions/workflows/ci.yml)
[![PHP Version](https://img.shields.io/badge/PHP-8.4-777BB4.svg?logo=php&logoColor=white)](https://www.php.net/)
[![Laravel Version](https://img.shields.io/badge/Laravel-12.x-FF2D20.svg?logo=laravel&logoColor=white)](https://laravel.com/)
[![License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)

Swap Hub is a Laravel-based project collaboration and skill swapping platform designed for students. The platform helps students find matching team partners, manage tasks using an interactive Kanban board, and automatically synchronize project schedules via Google Calendar integration. With real-time chat, automatic activity logging through GitHub webhooks, and a peer-to-peer skill validation system, Swap Hub enables students to build verified professional portfolios during their college years.

## 📋 Table of Contents

- [Features](#features)
- [Tech Stack](#tech-stack)
- [Quick Start](#quick-start)
- [Development](#development)
- [Deployment](#deployment)
- [Documentation](#documentation)

## ✨ Features

- 👥 **Project Matchmaking & Member Management** — Create projects, define required skills, accept/reject applications, and manage project members.
- 📋 **Interactive Kanban Task Board** — Manage project tasks with visual Kanban/list views, priority settings, and assignees.
- 📅 **Google Calendar Integration** — Automatic creation of project calendars, syncing task events/due dates, and managing member access permissions.
- 💬 **Real-time Chat & WebSockets** — Project and direct chat rooms built with Livewire 3 and self-hosted Laravel Reverb (supports Markdown rendering & automated GitHub notifications).
- 🪙 **Campus Credit Ledger & Gamification** — Atomic student credit system (`CreditLedgerService`) for peer transfers, bounties, and rewards.
- 🔍 **Code Review Bounties** — Peer code review requests with credit escrow and automatic payout upon acceptance.
- 💻 **Study Desk & Virtual Rooms** — Collaborative study sessions with auto-generated Jitsi Meet meeting rooms and credit awards.
- 🏷️ **Course Tagging & Academic Linking** — Associate projects, students, and skill swaps with official academic course codes.
- 🗺️ **Milestone Roadmap** — Kanban and timeline views for project milestone planning with direct task-to-milestone associations.
- 📜 **Verified Student Portfolio & Resume** — Showcase verified project contributions, badges, skills, GitHub activity, and generate downloadable PDF resumes.
- 🤝 **Skill Swaps** — 1-on-1 peer skill exchange lifecycle (request, accept, complete, cancel).
- 🚀 **GitHub Webhook Syncing** — Automatic repository webhook integration to log commit activities, award member reputation points, and post live updates to project chat.
- 🔐 **Multi-Provider Authentication** — Secure user login with Google and GitHub OAuth options alongside standard email verification.
- 🩺 **Admin Dashboard & System Health** — Complete user/project management, toggle admin roles, and live `/healthz`, `/readyz`, and socket latency monitoring.
- 🐳 **Docker Support & Automated Testing** — Standardized multi-container environment and automated test suite.
## 🛠 Tech Stack

- **Backend:** Laravel 12 (PHP 8.4)
- **Frontend:** Blade, Livewire 3, TailwindCSS, Alpine.js
- **Database:** MariaDB / MySQL (production/dev), SQLite (testing)
- **Cache & Queue:** Redis
- **Real-time Broadcast:** Laravel Reverb (self-hosted WebSocket)
- **Containerization:** Docker & Docker Compose
- **CI/CD:** GitHub Actions
- **Calendar API:** Spatie Google Calendar

## 🚀 Quick Start

### Prerequisites

- Docker & Docker Compose
- Git

### Installation

**Option A: Automate Setup with Composer (Recommended)**
```bash
# Clone repository
git clone https://github.com/your-username/swap-hub.git
cd swap-hub

# Run the automated composer script to set up environment, build, and start containers
composer compose:start
```

**Option B: Start Manually with Docker Compose**
```bash
# Clone repository
git clone https://github.com/your-username/swap-hub.git
cd swap-hub

# Copy env file
cp .env.example .env

# Build and start services
docker compose up -d --build
```

**Access the application at:**
[http://localhost:5541](http://localhost:5541)

That's it! The application will automatically:
- ✅ Install dependencies
- ✅ Run migrations
- ✅ Seed database
- ✅ Build assets
- ✅ Configure storage

## 💻 Development

### Local Development (Without Docker)

```bash
# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Run migrations
php artisan migrate --seed

# Build assets
npm run dev

# Start server
php artisan serve
```

### Docker Development

```bash
# Start containers
docker-compose up -d

# View logs
docker-compose logs -f

# Run artisan commands
docker-compose exec app php artisan [command]

# Access container
docker-compose exec app bash
```

## 📦 Deployment

### Docker Deployment

See [docker.md](docs/docker.md) for detailed Docker deployment guide.

```bash
# Production deployment
docker-compose up -d --build

# Check status
docker-compose ps

# View logs
docker-compose logs -f
```

### Manual Deployment

```bash
# Install dependencies
composer install --optimize-autoloader --no-dev
npm install
npm run build

# Setup environment
cp .env.example .env
php artisan key:generate

# Optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations
php artisan migrate --force
```

## 📚 Documentation

Detailed documentation is available in the [`docs/`](docs/) directory:

- 🐳 [docker.md](docs/docker.md) — Complete Docker deployment & multi-container setup guide
- 🏗️ [architecture-and-ops.md](docs/architecture-and-ops.md) — System architecture, health & readiness probes, admin operations, and security hardening
- 🗄️ [database-schema-and-api.md](docs/database-schema-and-api.md) — Core database schema, relations, service ports, and route catalog
- 🎓 [campus-features.md](docs/campus-features.md) — Comprehensive guide to Credit Ledger, Code Review Bounties, Study Desk, Course Tagging, Matchmaker, Roadmap, and Portfolios
- ⚡ [websocket-reverb.md](docs/websocket-reverb.md) — Laravel Reverb WebSocket setup, Nginx reverse proxy, and private channels
- 🌐 [google-integration.md](docs/google-integration.md) — Google OAuth 2.0 and Spatie Google Calendar synchronization (Service Account)
- 🐙 [github-oauth.md](docs/github-oauth.md) — GitHub OAuth login configuration
- 🪝 [github-webhook-setup.md](docs/github-webhook-setup.md) — Automated GitHub webhook setup and HMAC-SHA256 verification
- ✉️ [gmail-setup.md](docs/gmail-setup.md) — Gmail SMTP setup for transactional email verification
## 🔧 Configuration

### Environment Variables

Key environment variables (see `.env.example`):

```env
APP_NAME="Swap Hub"
APP_ENV=production
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=db
DB_DATABASE=swap_hub

REDIS_HOST=redis

BROADCAST_CONNECTION=reverb
REVERB_APP_ID=554101
REVERB_APP_KEY=swaphubreverbkey
REVERB_APP_SECRET=swaphubreverbsecret
REVERB_HOST="127.0.0.1"
REVERB_PORT=8080
REVERB_SCHEME=http

# Google OAuth & Calendar (see docs/google-integration.md)
GOOGLE_CLIENT_ID=your-google-client-id.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=your-google-client-secret
GOOGLE_REDIRECT_URI=http://localhost:5541/auth/google/callback
GOOGLE_CALENDAR_AUTH_PROFILE=service_account
GOOGLE_CALENDAR_SERVICE_ACCOUNT_CREDENTIALS=storage/app/google-calendar/service-account-credentials.json

# GitHub OAuth (see docs/github-oauth.md for setup)
GITHUB_CLIENT_ID=your-github-client-id
GITHUB_CLIENT_SECRET=your-github-client-secret
GITHUB_REDIRECT_URI=http://localhost:5541/auth/github/callback
GITHUB_WEBHOOK_SECRET=your-github-webhook-secret

## 🧪 Testing

```bash
# Run tests
php artisan test

# Run with coverage
php artisan test --coverage

# Run specific test
php artisan test --filter=TestName
```

## 📊 Monitoring

### Application Logs

```bash
# Docker logs
docker compose logs -f app

# Laravel logs
tail -f storage/logs/laravel.log
```

### Container Stats

```bash
docker stats swap-hub-app-development swap-hub-db-development swap-hub-redis-development
```

## 🤝 Contributing

1. Fork the repository
2. Create feature branch (`git checkout -b feature/amazing-feature`)
3. Commit changes (`git commit -m 'Add amazing feature'`)
4. Push to branch (`git push origin feature/amazing-feature`)
5. Open Pull Request

## 📝 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 🙏 Acknowledgments

- Built with [Laravel](https://laravel.com)
- UI components from [TailwindCSS](https://tailwindcss.com)
- Real-time features powered by [Pusher](https://pusher.com)

---

**Need Help?** 
- 📖 Check the [documentation](docs/README.md)
- 🐛 [Report issues](https://github.com/your-username/swap-hub/issues)
- 💬 [Discussions](https://github.com/your-username/swap-hub/discussions)
