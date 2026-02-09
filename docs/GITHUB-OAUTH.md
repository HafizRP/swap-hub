# 🔐 GitHub OAuth Login Setup Guide

## Overview

Aplikasi Swap Hub sudah dilengkapi dengan fitur **Login dengan GitHub**. Dokumentasi ini menjelaskan cara setup GitHub OAuth untuk development dan production.

---

## 📋 Prerequisites

✅ Akun GitHub  
✅ Aplikasi Swap Hub sudah running  
✅ Akses ke GitHub Developer Settings

---

## 🚀 Quick Setup

### Step 1: Create GitHub OAuth App

1. **Login ke GitHub** → https://github.com

2. **Go to Developer Settings:**
   - Click profile picture (top right)
   - Settings → Developer settings
   - OAuth Apps → **New OAuth App**
   
   Or direct link: https://github.com/settings/developers

3. **Fill in Application Details:**

   **For Development (Local):**
   ```
   Application name: Swap Hub (Development)
   Homepage URL: http://localhost:5541
   Authorization callback URL: http://localhost:5541/auth/github/callback
   ```

   **For Production:**
   ```
   Application name: Swap Hub
   Homepage URL: https://swaphub.b14.my.id
   Authorization callback URL: https://swaphub.b14.my.id/auth/github/callback
   ```

4. **Click "Register application"**

5. **Get Credentials:**
   - Copy **Client ID**
   - Click **Generate a new client secret**
   - Copy **Client Secret** (⚠️ save it now, you won't see it again!)

---

### Step 2: Configure Environment Variables

Add to your `.env` file:

```env
# GitHub OAuth Configuration
GITHUB_CLIENT_ID=your_github_client_id_here
GITHUB_CLIENT_SECRET=your_github_client_secret_here
GITHUB_REDIRECT_URI=http://localhost:5541/auth/github/callback
```

**For Production:**
```env
GITHUB_CLIENT_ID=your_production_client_id
GITHUB_CLIENT_SECRET=your_production_client_secret
GITHUB_REDIRECT_URI=https://swaphub.b14.my.id/auth/github/callback
```

---

### Step 3: Install Laravel Socialite (If Not Installed)

```bash
# Check if already installed
composer show laravel/socialite

# If not installed, install it
composer require laravel/socialite
```

---

### Step 4: Clear Cache & Test

```bash
# Clear config cache
php artisan config:clear

# Or with Docker
docker-compose exec app php artisan config:clear

# Test the application
# Visit: http://localhost:5541/login
# Click "Login with GitHub"
```

---

## 🔧 Configuration Details

### Routes

Routes sudah dikonfigurasi di `routes/web.php`:

```php
// GitHub OAuth
Route::get('/auth/github', [GitHubAuthController::class, 'redirect'])
    ->name('auth.github');
    
Route::get('/auth/github/callback', [GitHubAuthController::class, 'callback']);
```

### Services Configuration

Konfigurasi di `config/services.php`:

```php
'github' => [
    'client_id' => env('GITHUB_CLIENT_ID'),
    'client_secret' => env('GITHUB_CLIENT_SECRET'),
    'redirect' => env('GITHUB_REDIRECT_URI'),
],
```

### Controller Logic

`app/Http/Controllers/GitHubAuthController.php` handles:

1. **Redirect to GitHub** - Request OAuth permission
2. **Callback from GitHub** - Process authentication
3. **Link Account** - If user already logged in
4. **Login** - If user not logged in

---

## 🎯 How It Works

### Flow Diagram

```
┌─────────────┐
│    User     │
│ Click Login │
│ with GitHub │
└──────┬──────┘
       │
       ▼
┌─────────────────────────────────┐
│  Redirect to GitHub OAuth       │
│  /auth/github                   │
└──────┬──────────────────────────┘
       │
       ▼
┌─────────────────────────────────┐
│  GitHub Authorization Page      │
│  User grants permission         │
└──────┬──────────────────────────┘
       │
       ▼
┌─────────────────────────────────┐
│  Callback to Application        │
│  /auth/github/callback          │
└──────┬──────────────────────────┘
       │
       ▼
┌─────────────────────────────────┐
│  Check if user exists           │
│  (by email)                     │
└──────┬──────────────────────────┘
       │
       ├─── User exists ───────────┐
       │                           │
       │                           ▼
       │                    ┌──────────────┐
       │                    │ Login User   │
       │                    │ Update GitHub│
       │                    │ info         │
       │                    └──────┬───────┘
       │                           │
       │                           ▼
       │                    ┌──────────────┐
       │                    │ Redirect to  │
       │                    │ Dashboard    │
       │                    └──────────────┘
       │
       └─── User NOT exists ──────┐
                                  │
                                  ▼
                           ┌──────────────┐
                           │ Auto-Create  │
                           │ New User     │
                           │ - Name       │
                           │ - Email      │
                           │ - GitHub info│
                           │ - Random pwd │
                           └──────┬───────┘
                                  │
                                  ▼
                           ┌──────────────┐
                           │ Login User   │
                           │ Auto-verify  │
                           │ email        │
                           └──────┬───────┘
                                  │
                                  ▼
                           ┌──────────────┐
                           │ Redirect to  │
                           │ Dashboard    │
                           │ (Welcome!)   │
                           └──────────────┘
```

### Scenarios

#### Scenario 1: New User Login (Auto-Register)
1. User clicks "Login with GitHub"
2. Redirected to GitHub for authorization
3. GitHub redirects back with user info
4. **Email NOT in database** → Auto-create new user account
5. User logged in automatically
6. Redirect to dashboard with welcome message

#### Scenario 2: Existing User Login
1. User clicks "Login with GitHub"
2. Redirected to GitHub for authorization
3. GitHub redirects back with user info
4. **Email exists in database** → Login successful
5. Update GitHub username and token
6. Redirect to dashboard

#### Scenario 2: Linking GitHub Account
1. User already logged in
2. Goes to Profile → Click "Link GitHub"
3. Redirected to GitHub for authorization
4. GitHub redirects back
5. GitHub username and token saved to user profile

---

## 🔍 Testing

### Test Login Flow

1. **Start Application:**
   ```bash
   # Local
   php artisan serve
   
   # Or Docker
   docker-compose up -d
   ```

2. **Visit Login Page:**
   ```
   http://localhost:5541/login
   ```

3. **Click "Login with GitHub"**

4. **Authorize on GitHub**

5. **Should redirect to dashboard** (if email exists in database)

### Test Account Linking

1. **Login with regular credentials**

2. **Go to Profile:**
   ```
   http://localhost:5541/profile
   ```

3. **Click "Link GitHub Account"**

4. **Authorize on GitHub**

5. **Should redirect back to profile** with success message

---

## 🐛 Troubleshooting

### ❌ Error: "The redirect URI provided does not match"

**Problem:** Callback URL mismatch

**Solution:**
```bash
# Check your .env
GITHUB_REDIRECT_URI=http://localhost:5541/auth/github/callback

# Must match exactly with GitHub OAuth App settings
# Go to GitHub → Settings → Developer settings → OAuth Apps
# Update "Authorization callback URL"
```

### ❌ Error: "No account found with this GitHub email"

**Problem:** User belum register dengan email yang sama

**Solution:**
1. Register terlebih dahulu dengan email yang sama dengan GitHub
2. Atau update logic di controller untuk auto-create user

### ❌ Error: "Client authentication failed"

**Problem:** Invalid Client ID or Client Secret

**Solution:**
```bash
# Verify credentials in .env
GITHUB_CLIENT_ID=correct_client_id
GITHUB_CLIENT_SECRET=correct_client_secret

# Clear config cache
php artisan config:clear
```

### ❌ Error: "cURL error 60: SSL certificate problem"

**Problem:** SSL certificate issue (Windows/Local development)

**Solution:**
```bash
# Option 1: Disable SSL verification (DEVELOPMENT ONLY!)
# In config/services.php, add:
'github' => [
    'client_id' => env('GITHUB_CLIENT_ID'),
    'client_secret' => env('GITHUB_CLIENT_SECRET'),
    'redirect' => env('GITHUB_REDIRECT_URI'),
    'guzzle' => [
        'verify' => false, // ONLY for development!
    ],
],

# Option 2: Download CA bundle
# https://curl.se/docs/caextract.html
# Update php.ini:
# curl.cainfo = "C:\path\to\cacert.pem"
```

### ❌ GitHub returns but nothing happens

**Problem:** Session/Cookie issue

**Solution:**
```bash
# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# Check session driver in .env
SESSION_DRIVER=database  # or file, redis
```

---

## 🔐 Security Best Practices

### 1. Never Commit Secrets

```bash
# Always add to .gitignore
.env
.env.local
.env.production

# Check before commit
git status
```

### 2. Use Different Apps for Different Environments

Create separate OAuth Apps:
- **Development:** `Swap Hub (Dev)`
- **Staging:** `Swap Hub (Staging)`
- **Production:** `Swap Hub`

### 3. Rotate Secrets Regularly

```bash
# In GitHub OAuth App settings
# Click "Generate a new client secret"
# Update .env
# Clear cache
php artisan config:clear
```

### 4. Limit Scopes

Current scopes in `GitHubAuthController.php`:
```php
->scopes(['repo', 'user:email'])
```

Only request what you need:
- `user:email` - Get user email
- `repo` - Access repositories (if needed)
- `read:user` - Read user profile

### 5. Validate Callback State

Laravel Socialite automatically handles CSRF protection via state parameter.

---

## 📊 Database Schema

GitHub data disimpan di tabel `users`:

```sql
-- Columns for GitHub OAuth
github_username VARCHAR(255) NULL
github_token TEXT NULL
```

### Migration (If Not Exists)

```bash
# Create migration
php artisan make:migration add_github_fields_to_users_table

# Edit migration file
public function up()
{
    Schema::table('users', function (Blueprint $table) {
        $table->string('github_username')->nullable();
        $table->text('github_token')->nullable();
    });
}

# Run migration
php artisan migrate
```

---

## 🚀 Production Deployment

### 1. Create Production OAuth App

Follow Step 1 but use production URL:
```
Homepage URL: https://swaphub.b14.my.id
Callback URL: https://swaphub.b14.my.id/auth/github/callback
```

### 2. Update Production .env

```env
GITHUB_CLIENT_ID=production_client_id
GITHUB_CLIENT_SECRET=production_client_secret
GITHUB_REDIRECT_URI=https://swaphub.b14.my.id/auth/github/callback
```

### 3. Deploy & Clear Cache

```bash
# On server
cd /path/to/swap-hub
git pull

# Update .env with production credentials

# Clear cache
php artisan config:clear
php artisan cache:clear

# Or with Docker
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan cache:clear
```

### 4. Test Production

```bash
# Visit production site
https://swaphub.b14.my.id/login

# Click "Login with GitHub"
# Should work with production OAuth app
```

---

## 🎨 UI Integration

### Login Page

Button sudah ada di login page. Jika belum, tambahkan:

```blade
{{-- resources/views/auth/login.blade.php --}}

<div class="mt-6">
    <div class="relative">
        <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-gray-300"></div>
        </div>
        <div class="relative flex justify-center text-sm">
            <span class="px-2 bg-white text-gray-500">Or continue with</span>
        </div>
    </div>

    <div class="mt-6">
        <a href="{{ route('auth.github') }}" 
           class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 0C4.477 0 0 4.484 0 10.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0110 4.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.203 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.942.359.31.678.921.678 1.856 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0020 10.017C20 4.484 15.522 0 10 0z" clip-rule="evenodd"/>
            </svg>
            Login with GitHub
        </a>
    </div>
</div>
```

### Profile Page

Link GitHub account button:

```blade
{{-- resources/views/profile/edit.blade.php --}}

<div class="mt-6">
    @if(auth()->user()->github_username)
        <div class="flex items-center">
            <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <span class="text-sm text-gray-700">
                GitHub linked: <strong>{{ auth()->user()->github_username }}</strong>
            </span>
        </div>
    @else
        <a href="{{ route('auth.github') }}" 
           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 0C4.477 0 0 4.484 0 10.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0110 4.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.203 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.942.359.31.678.921.678 1.856 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0020 10.017C20 4.484 15.522 0 10 0z" clip-rule="evenodd"/>
            </svg>
            Link GitHub Account
        </a>
    @endif
</div>
```

---

## 📚 Additional Resources

- [GitHub OAuth Documentation](https://docs.github.com/en/developers/apps/building-oauth-apps)
- [Laravel Socialite Documentation](https://laravel.com/docs/11.x/socialite)
- [OAuth 2.0 Specification](https://oauth.net/2/)

---

## ✅ Checklist

- [ ] GitHub OAuth App created
- [ ] Client ID and Secret copied
- [ ] `.env` updated with credentials
- [ ] Config cache cleared
- [ ] Tested login flow
- [ ] Tested account linking
- [ ] Production OAuth App created (if deploying)
- [ ] Production `.env` updated

---

**Need Help?** Check the troubleshooting section or open an issue on GitHub.
