# Set error preferences
$ErrorActionPreference = "Continue"

Write-Host "🚀 Starting Laravel application setup on Windows Container..." -ForegroundColor Green

Set-Location C:\app

# Ensure directories exist
New-Item -ItemType Directory -Force -Path "storage\logs", "storage\framework\sessions", "storage\framework\views", "storage\framework\cache\data", "bootstrap\cache" | Out-Null
New-Item -ItemType File -Force -Path "storage\logs\laravel.log" | Out-Null

# Grant full control permissions to IIS application pool user
icacls "C:\app\storage" /grant "IIS_IUSRS:(OI)(CI)F" /T /Q
icacls "C:\app\bootstrap\cache" /grant "IIS_IUSRS:(OI)(CI)F" /T /Q
icacls "C:\app\storage" /grant "IUSR:(OI)(CI)F" /T /Q
icacls "C:\app\bootstrap\cache" /grant "IUSR:(OI)(CI)F" /T /Q

# Handle .env file
if (-not (Test-Path ".env")) {
    if (Test-Path ".env.example") {
        Copy-Item ".env.example" ".env"
    }
}

# Generate App Key if not set
$envContent = Get-Content ".env" -Raw -ErrorAction SilentlyContinue
if ($envContent -notmatch "APP_KEY=base64:") {
    Write-Host "🔑 Generating application key..." -ForegroundColor Cyan
    php artisan key:generate --force
}

# Build assets if needed
if (-not (Test-Path "public\build")) {
    Write-Host "📦 Building frontend assets..." -ForegroundColor Cyan
    npm run build
}

# Create storage symlink
if (-not (Test-Path "public\storage")) {
    Write-Host "🔗 Linking storage..." -ForegroundColor Cyan
    php artisan storage:link
}

# Clear and optimize cache
Write-Host "⚡ Clearing caches..." -ForegroundColor Cyan
php artisan optimize:clear

# Run database migrations if configured
if ($env:RUN_MIGRATIONS -ne "false") {
    Write-Host "🗄️ Running database migrations..." -ForegroundColor Cyan
    php artisan migrate --force --no-interaction
}

Write-Host "✨ Application setup complete! Starting IIS Web Server..." -ForegroundColor Green

# Start IIS Service Monitor to keep container alive
if (Test-Path "C:\ServiceMonitor.exe") {
    & "C:\ServiceMonitor.exe" w3svc
} else {
    Start-Service W3SVC
    Write-Host "IIS Started. Listening for connections..."
    while ($true) { Start-Sleep -Seconds 3600 }
}
