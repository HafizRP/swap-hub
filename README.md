# 🔄 Swap Hub

Platform kolaborasi proyek dan skill swap mahasiswa berbasis Laravel.

## 📋 Table of Contents

- [Features](#features)
- [Tech Stack](#tech-stack)
- [Quick Start](#quick-start)
- [Development](#development)
- [Deployment](#deployment)
- [CI/CD with Jenkins](#cicd-with-jenkins)
- [Documentation](#documentation)

## ✨ Features

- 👥 **Project Matchmaking & Member Management** — Create projects, define required skills, accept/reject applications, and manage project members.
- 📋 **Interactive Kanban Task Board** — Manage project tasks with visual Kanban/list views, priority settings, and assignees.
- 📅 **Google Calendar Integration** — Automatic creation of project calendars, syncing task events/due dates, and managing member access permissions.
- 💬 **Real-time Chat** — Project and direct chat rooms built with Livewire and Pusher (supports Markdown rendering & automated GitHub notifications).
- 🚀 **GitHub Webhook Syncing** — Automatic repository webhook integration to log commit activities, award member reputation points, and post live updates to project chat.
- 🔐 **Multi-Provider Authentication** — Secure user login with Google and GitHub OAuth options alongside standard email verification.
- 📊 **Reputation & Skill Validation** — Peer review system where members rate contributions and validate skills upon project completion.
- 🩺 **Admin Dashboard & System Health** — Complete user/project management and live system health monitoring.
- 🐳 **Docker Support & Jenkins CI/CD** — Standardized environments and automated deployment pipeline.

## 🛠 Tech Stack

- **Backend:** Laravel 12 (PHP 8.4)
- **Frontend:** Blade, Livewire 3, TailwindCSS, Alpine.js, Bootstrap 5 (welcome page)
- **Database:** MariaDB / MySQL
- **Cache & Queue:** Redis
- **Real-time Broadcast:** Pusher
- **Containerization:** Docker & Docker Compose
- **CI/CD:** Jenkins
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

## 🔄 CI/CD with Jenkins

### Configure Jenkins Pipeline

See [jenkins.md](docs/jenkins.md) for comprehensive Jenkins configuration guide.

**Quick Steps:**
1. Create new Pipeline job in Jenkins
2. Configure Git repository
3. Set Script Path to `Jenkinsfile`
4. Build Now!

**Key Features:**
- ✅ Automated build on git push
- ✅ Run tests before deployment
- ✅ Docker-based deployment
- ✅ Database migrations
- ✅ Cache optimization
- ✅ Health checks
- ✅ Automatic rollback on failure

### Pipeline Stages

1. **Checkout** - Clone repository
2. **Install Dependencies** - Composer & NPM
3. **Build Assets** - Compile frontend
4. **Run Tests** - PHPUnit tests
5. **Build Docker** - Create images
6. **Deploy** - Start containers
7. **Migrate** - Update database
8. **Optimize** - Cache config/routes/views
9. **Health Check** - Verify deployment

## 📚 Documentation

- [docker.md](docs/docker.md) - Docker deployment guide
- [jenkins.md](docs/jenkins.md) - Jenkins CI/CD setup
- [github-oauth.md](docs/github-oauth.md) - GitHub OAuth login setup
- [github-oauth-quick-ref.md](docs/github-oauth-quick-ref.md) - GitHub OAuth quick reference
- [github-webhook-setup.md](docs/github-webhook-setup.md) - Auto-setup GitHub webhooks
- [gmail-setup.md](docs/gmail-setup.md) - Gmail SMTP setup for email verification
- [Jenkinsfile](Jenkinsfile) - Pipeline configuration

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

BROADCAST_CONNECTION=pusher
PUSHER_APP_ID=your-app-id
PUSHER_APP_KEY=your-app-key
PUSHER_APP_SECRET=your-app-secret

# GitHub OAuth (see docs/github-oauth.md for setup)
GITHUB_CLIENT_ID=your-github-client-id
GITHUB_CLIENT_SECRET=your-github-client-secret
GITHUB_REDIRECT_URI=http://localhost:5541/auth/github/callback
```

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
- 📖 Check the [documentation](docs/jenkins.md)
- 🐛 [Report issues](https://github.com/your-username/swap-hub/issues)
- 💬 [Discussions](https://github.com/your-username/swap-hub/discussions)
