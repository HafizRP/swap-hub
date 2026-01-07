#!/bin/bash
# deploy.sh - Auto deploy script for production

echo "🚀 Starting deployment..."

# Pull latest code
echo "📥 Pulling latest code..."
git pull

# Rebuild Docker
echo "🔨 Building Docker images..."
sudo docker compose build

# Restart containers
echo "♻️  Restarting containers..."
sudo docker compose up -d

# Clear cache
echo "🧹 Clearing cache..."
sudo docker compose exec app php artisan view:clear
sudo docker compose exec app php artisan config:clear

# Check status
echo "✅ Deployment complete!"
sudo docker compose ps

echo ""
echo "📊 Build assets info:"
sudo docker compose exec app ls -la /var/www/public/build/
