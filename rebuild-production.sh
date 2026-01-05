#!/bin/bash

echo "🔧 Rebuilding production with clean dependencies..."

# Clean node_modules
echo "📦 Cleaning node_modules..."
rm -rf node_modules package-lock.json

# Fresh install
echo "📥 Installing dependencies..."
npm install

# Build frontend
echo "🏗️  Building frontend assets..."
npm run build

echo "✅ Local build complete!"
echo ""
echo "📤 Now deploy to production:"
echo "   git add ."
echo "   git commit -m 'Rebuild frontend without Alpine'"
echo "   git push"
echo ""
echo "🐳 Then on production server run:"
echo "   cd ~/Documents/apps/swap-hub"
echo "   git pull"
echo "   sudo docker compose down"
echo "   sudo docker compose build --no-cache"
echo "   sudo docker compose up -d"
