# 🐛 GitHub Webhook Troubleshooting

## ❌ Problem: Webhook Returns NULL

### Error Message:
```
"url is not supported because it isn't reachable over the public Internet (localhost)"
```

### Root Cause:
GitHub **cannot create webhooks** to `localhost` URLs because:
- GitHub servers need to send HTTP POST requests to your webhook URL
- Localhost is only accessible from your local machine
- GitHub servers cannot reach `http://localhost:8000`

---

## ✅ Solutions

### Option 1: Use ngrok (Recommended for Development)

**ngrok** creates a secure tunnel to your localhost and provides a public URL.

#### Step 1: Install ngrok

```bash
# Mac
brew install ngrok

# Or download from
https://ngrok.com/download

# Linux
curl -s https://ngrok-agent.s3.amazonaws.com/ngrok.asc | \
  sudo tee /etc/apt/trusted.gpg.d/ngrok.asc >/dev/null && \
  echo "deb https://ngrok-agent.s3.amazonaws.com buster main" | \
  sudo tee /etc/apt/sources.list.d/ngrok.list && \
  sudo apt update && sudo apt install ngrok
```

#### Step 2: Sign up & Get Auth Token

```bash
# Sign up at https://dashboard.ngrok.com/signup
# Get your authtoken from https://dashboard.ngrok.com/get-started/your-authtoken

# Add authtoken
ngrok config add-authtoken YOUR_AUTH_TOKEN
```

#### Step 3: Start ngrok Tunnel

```bash
# Expose your local server (port 8000)
ngrok http 8000

# Output:
# Forwarding: https://xxxx-xx-xx-xx-xx.ngrok-free.app -> http://localhost:8000
```

#### Step 4: Update .env

```env
# Update APP_URL to ngrok URL
APP_URL=https://xxxx-xx-xx-xx-xx.ngrok-free.app
```

#### Step 5: Clear Config Cache

```bash
php artisan config:clear
```

#### Step 6: Test Webhook Creation

Now create a project with webhook checkbox checked. It should work!

---

### Option 2: Deploy to Production Server

Deploy your application to a server with a public domain:

**Popular Options:**
- **DigitalOcean** - $5/month droplet
- **AWS EC2** - Free tier available
- **Heroku** - Free tier (with limitations)
- **Vercel** - Free for hobby projects
- **Railway** - Free tier available

**Example Production URLs:**
```
https://swaphub.b14.my.id
https://your-app.herokuapp.com
https://your-app.vercel.app
```

Update `.env` on production:
```env
APP_URL=https://swaphub.b14.my.id
```

---

### Option 3: Skip Webhook for Development

If you just want to test other features without webhook:

1. **Don't check** the "Auto-setup GitHub Webhook" checkbox
2. Test other features first
3. Setup webhook later when deploying to production

---

## 🔍 How to Check Logs

### View Laravel Logs

```bash
# View last 50 lines
tail -n 50 storage/logs/laravel.log

# Follow logs in real-time
tail -f storage/logs/laravel.log

# Search for webhook errors
grep "webhook" storage/logs/laravel.log
```

### Common Error Messages

#### 1. URL not reachable (localhost)
```json
{
  "message": "Validation Failed",
  "errors": [{
    "resource": "Hook",
    "code": "custom",
    "field": "url",
    "message": "url is not supported because it isn't reachable over the public Internet (localhost)"
  }]
}
```
**Solution:** Use ngrok or deploy to production

#### 2. Invalid token / No permission
```json
{
  "message": "Not Found",
  "documentation_url": "https://docs.github.com/rest/repos/webhooks#create-a-repository-webhook"
}
```
**Solution:** Check GitHub token has `admin:repo_hook` scope

#### 3. Repository not found
```json
{
  "message": "Not Found"
}
```
**Solution:** Verify repository URL is correct and you have access

---

## 🧪 Testing with ngrok

### Complete Testing Flow

```bash
# Terminal 1: Start Laravel server
php artisan serve

# Terminal 2: Start ngrok
ngrok http 8000

# Copy ngrok URL (e.g., https://xxxx.ngrok-free.app)

# Update .env
APP_URL=https://xxxx.ngrok-free.app

# Clear cache
php artisan config:clear

# Visit ngrok URL in browser
https://xxxx.ngrok-free.app

# Login with GitHub
# Create project with webhook checkbox
# Should work! ✅
```

### Verify Webhook Created

1. Go to GitHub repository
2. Settings → Webhooks
3. Should see webhook with ngrok URL
4. Click webhook to see delivery history

---

## 📊 Webhook Status Tracking

### Check Webhook Status in Database

```sql
SELECT 
    id, 
    title, 
    github_repo_url,
    github_webhook_id, 
    github_webhook_status 
FROM projects 
WHERE github_repo_url IS NOT NULL;
```

### Status Values

| Status | Meaning |
|--------|---------|
| `active` | Webhook created successfully |
| `failed` | Webhook creation failed |
| `inactive` | Webhook disabled |
| `NULL` | No webhook setup attempted |

---

## 🔐 GitHub Token Scopes

Make sure your GitHub token has the correct scopes:

### Required Scopes:
- ✅ `repo` - Full control of private repositories
- ✅ `admin:repo_hook` - Full control of repository hooks

### How to Check/Update Scopes:

1. Go to: https://github.com/settings/tokens
2. Find your token (or create new one)
3. Check scopes:
   - [x] repo
   - [x] admin:repo_hook
4. Update token if needed
5. Re-login to app to get new token

---

## 🎯 Production Deployment Checklist

- [ ] Deploy app to server with public domain
- [ ] Update `APP_URL` in production `.env`
- [ ] Clear config cache: `php artisan config:clear`
- [ ] Test webhook creation
- [ ] Verify webhook in GitHub settings
- [ ] Test webhook delivery with a commit
- [ ] Check activity feed in project page

---

## 🚀 Quick Commands

```bash
# Start ngrok
ngrok http 8000

# Update APP_URL
echo "APP_URL=https://your-ngrok-url.ngrok-free.app" >> .env

# Clear cache
php artisan config:clear

# View logs
tail -f storage/logs/laravel.log

# Test webhook
curl -X POST http://localhost:8000/webhooks/github \
  -H "Content-Type: application/json" \
  -d '{"test": "data"}'
```

---

## 📚 Resources

- [ngrok Documentation](https://ngrok.com/docs)
- [GitHub Webhooks API](https://docs.github.com/en/rest/webhooks)
- [GitHub Personal Access Tokens](https://github.com/settings/tokens)
- [Laravel HTTP Client](https://laravel.com/docs/11.x/http-client)

---

## 💡 Tips

1. **ngrok Free Tier:**
   - URL changes every time you restart ngrok
   - Need to update `APP_URL` each time
   - Limited to 40 connections/minute

2. **ngrok Paid Tier:**
   - Fixed subdomain (e.g., `myapp.ngrok.io`)
   - No need to update `APP_URL` every time
   - Higher connection limits

3. **Production:**
   - Use real domain with SSL
   - Set up proper DNS
   - Use environment-specific `.env` files

---

**Need Help?** Check logs at `storage/logs/laravel.log`
