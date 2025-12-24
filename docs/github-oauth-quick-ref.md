# GitHub OAuth Quick Reference

## 🚀 Quick Setup (3 Steps)

### 1. Create OAuth App
```
https://github.com/settings/developers
→ OAuth Apps → New OAuth App

Application name: Swap Hub
Homepage URL: http://localhost:5541
Callback URL: http://localhost:5541/auth/github/callback
```

### 2. Update .env
```env
GITHUB_CLIENT_ID=your_client_id_here
GITHUB_CLIENT_SECRET=your_client_secret_here
GITHUB_REDIRECT_URI=http://localhost:5541/auth/github/callback
```

### 3. Clear Cache
```bash
php artisan config:clear
# or
docker-compose exec app php artisan config:clear
```

---

## 🔗 URLs

### Development
```
Homepage: http://localhost:5541
Callback: http://localhost:5541/auth/github/callback
```

### Production
```
Homepage: https://swaphub.b14.my.id
Callback: https://swaphub.b14.my.id/auth/github/callback
```

---

## 🧪 Testing

```bash
# Visit login page
http://localhost:5541/login

# Click "Login with GitHub"
# Should redirect to GitHub → Authorize → Redirect back
```

---

## 🐛 Common Errors

### "The redirect URI provided does not match"
```bash
# Check .env matches GitHub OAuth App settings
GITHUB_REDIRECT_URI=http://localhost:5541/auth/github/callback

# Clear cache
php artisan config:clear
```

### "Client authentication failed"
```bash
# Verify credentials
GITHUB_CLIENT_ID=correct_id
GITHUB_CLIENT_SECRET=correct_secret

# Clear cache
php artisan config:clear
```

---

## 📋 Routes

```php
// Login with GitHub
GET /auth/github

// GitHub callback
GET /auth/github/callback
```

---

## 🔐 Security Checklist

- [ ] Never commit `.env` file
- [ ] Use different OAuth Apps for dev/prod
- [ ] Rotate secrets regularly
- [ ] Only request needed scopes
- [ ] Validate callback state (auto-handled)

---

## 📚 Full Documentation

See [GITHUB-OAUTH.md](GITHUB-OAUTH.md) for complete setup guide.
