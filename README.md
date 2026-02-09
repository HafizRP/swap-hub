# 🔄 Swap Hub

Platform untuk skill swap dan property exchange berbasis Laravel.

## 📋 Table of Contents

- [Features](#features)
- [Tech Stack](#tech-stack)
- [Quick Start](#quick-start)
- [Development](#development)
- [Deployment](#deployment)
- [CI/CD with Jenkins](#cicd-with-jenkins)
- [Documentation](#documentation)

## ✨ Features

- 🏠 Property listing and management
- 💬 Real-time chat with Pusher
- 🔐 Authentication with GitHub OAuth login
- 📊 Admin dashboard
- 🔍 Advanced search and filtering
- 📱 Responsive design
- 🐳 Docker support
- 🚀 CI/CD with Jenkins

## 🛠 Tech Stack

- **Backend:** Laravel 11
- **Frontend:** Blade, TailwindCSS, Alpine.js
- **Database:** MySQL/MariaDB
- **Cache:** Redis
- **Real-time:** Pusher
- **Containerization:** Docker & Docker Compose
- **CI/CD:** Jenkins

## 🚀 Quick Start

### Prerequisites

- Docker & Docker Compose
- Git

### Installation

```bash
# Clone repository
git clone https://github.com/your-username/swap-hub.git
cd swap-hub

# Start with Docker Compose
docker-compose up -d --build

# Application will be available at:
# http://localhost:5541
```

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

See [DOCKER.md](docs/DOCKER.md) for detailed Docker deployment guide.

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

See [JENKINS.md](docs/JENKINS.md) for comprehensive Jenkins configuration guide.

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

- [DOCKER.md](docs/DOCKER.md) - Docker deployment guide
- [JENKINS.md](docs/JENKINS.md) - Jenkins CI/CD setup
- [GITHUB-OAUTH.md](docs/GITHUB-OAUTH.md) - GitHub OAuth login setup
- [GITHUB-WEBHOOK-SETUP.md](docs/GITHUB-WEBHOOK-SETUP.md) - Auto-setup GitHub webhooks
- [GMAIL_SETUP.md](docs/GMAIL_SETUP.md) - Gmail SMTP setup for email verification
- [TESTING-GUIDE.md](docs/TESTING-GUIDE.md) - Testing guide for Livewire chat
- [TEST_CASES.md](docs/TEST_CASES.md) - Test cases checklist
- [SPESIFIKASI_DATABASE.md](docs/SPESIFIKASI_DATABASE.md) - Database specification
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

# GitHub OAuth (see GITHUB-OAUTH.md for setup)
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
docker-compose logs -f app

# Laravel logs
tail -f storage/logs/laravel.log
```

### Container Stats

```bash
docker stats swap-hub-app swap-hub-nginx swap-hub-db
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
- 📖 Check the [documentation](docs/JENKINS.md)
- 🐛 [Report issues](https://github.com/your-username/swap-hub/issues)
- 💬 [Discussions](https://github.com/your-username/swap-hub/discussions)
