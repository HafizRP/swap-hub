# 🔗 Auto-Setup GitHub Webhook

## Overview

Fitur ini memungkinkan Anda untuk **otomatis setup GitHub webhook** ketika membuat project baru. Webhook akan dikonfigurasi secara otomatis menggunakan GitHub API, sehingga Anda tidak perlu manual setup di GitHub repository settings.

---

## ✨ Features

✅ **One-Click Setup** - Checkbox saat create project  
✅ **Automatic Configuration** - No manual GitHub settings needed  
✅ **Real-time Tracking** - Instant commit activity updates  
✅ **Status Monitoring** - Track webhook status (active/failed)  
✅ **Duplicate Prevention** - Checks existing webhooks  
✅ **Error Handling** - Graceful failure with status updates  

---

## 🚀 How to Use

### Step 1: Login dengan GitHub

Pastikan Anda sudah login menggunakan GitHub OAuth:

```
http://localhost:5541/login
→ Click "Login with GitHub"
→ Authorize
```

### Step 2: Create Project

1. Go to: http://localhost:5541/projects/create

2. Fill in project details:
   - Title
   - Description
   - **GitHub Repository URL** (required for webhook)
   - Category
   - Start Date

3. **Check the webhook checkbox:**
   ```
   ✅ 🚀 Auto-setup GitHub Webhook
      Automatically configure webhook for real-time commit tracking
   ```

4. Click **Launch Project**

### Step 3: Webhook Auto-Created! 🎉

The system will:
1. ✅ Create project
2. ✅ Call GitHub API
3. ✅ Create webhook in your repository
4. ✅ Save webhook ID to database
5. ✅ Set status to 'active'

---

## 🔧 How It Works

### Flow Diagram

```
User creates project
         ↓
Checks "Auto-setup Webhook" ✅
         ↓
Submits form
         ↓
ProjectController.store()
         ↓
Validates data
         ↓
Creates project
         ↓
Checks if setup_webhook = true
         ↓
    ┌────┴────┐
    │         │
   YES       NO
    │         │
    ▼         ▼
setupGitHub  Skip
Webhook()    webhook
    │         │
    ▼         │
GitHub API    │
POST /hooks   │
    │         │
    ▼         │
Success?      │
    │         │
┌───┴───┐     │
│       │     │
YES    NO     │
│       │     │
▼       ▼     │
Save   Set    │
webhook status│
ID     failed │
│       │     │
└───┬───┘     │
    │         │
    └────┬────┘
         ▼
   Redirect to
   project page
```

---

## 📊 Database Schema

### Projects Table (Updated)

| Column | Type | Description |
|--------|------|-------------|
| `github_webhook_id` | string | Webhook ID from GitHub |
| `github_webhook_status` | enum | active, inactive, failed |

---

## 🔐 Requirements

### 1. GitHub Personal Access Token

User harus sudah login dengan GitHub OAuth. Token disimpan di `users.github_token`.

**Required Scopes:**
- ✅ `repo` - Full control of private repositories
- ✅ `admin:repo_hook` - Full control of repository hooks

### 2. GitHub Repository Access

User harus memiliki **admin access** ke repository untuk create webhooks.

---

## 📝 Code Implementation

### Service Class

`app/Services/GitHubWebhookService.php`

**Methods:**
- `createWebhook()` - Create webhook via GitHub API
- `getWebhooks()` - Get existing webhooks
- `deleteWebhook()` - Delete webhook
- `parseRepoUrl()` - Parse GitHub URL
- `getRepoInfo()` - Get repository information

### Controller

`app/Http/Controllers/ProjectController.php`

**Updated Methods:**
- `store()` - Added webhook setup logic
- `setupGitHubWebhook()` - Helper method for webhook creation

### View

`resources/views/projects/create.blade.php`

**Added:**
- Checkbox untuk auto-setup webhook
- Only shown if user has GitHub token
- Styled dengan indigo background

---

## 🧪 Testing

### Test Auto-Setup

```bash
# 1. Login dengan GitHub
http://localhost:5541/login

# 2. Create project
http://localhost:5541/projects/create

# 3. Fill form:
Title: Test Project
Description: Testing webhook auto-setup
GitHub Repo: https://github.com/username/test-repo
✅ Auto-setup GitHub Webhook

# 4. Submit

# 5. Check database
SELECT github_webhook_id, github_webhook_status FROM projects WHERE id = 1;

# Expected:
# github_webhook_id: 123456789
# github_webhook_status: active
```

### Verify on GitHub

1. Go to: `https://github.com/username/repo/settings/hooks`
2. Should see webhook:
   - **Payload URL:** `http://your-domain/webhooks/github`
   - **Content type:** `application/json`
   - **Events:** push, pull_request, issues
   - **Active:** ✅

---

## 🐛 Troubleshooting

### ❌ Webhook Status = 'failed'

**Possible Causes:**
1. Invalid GitHub token
2. No admin access to repository
3. Repository doesn't exist
4. Network error

**Solution:**
```bash
# Check logs
tail -f storage/logs/laravel.log

# Look for:
# "Failed to create GitHub webhook"
# "Exception creating GitHub webhook"

# Verify token has correct scopes
# Go to: https://github.com/settings/tokens
# Check: repo, admin:repo_hook
```

### ❌ Checkbox Not Showing

**Cause:** User tidak punya GitHub token

**Solution:**
```bash
# Link GitHub account
http://localhost:5541/auth/github
```

### ❌ Duplicate Webhook

**Cause:** Webhook already exists

**Solution:**
Service automatically checks for duplicates and returns existing webhook instead of creating new one.

---

## 🔄 Manual Webhook Setup (Fallback)

Jika auto-setup gagal, user bisa manual setup:

1. Go to GitHub repository settings
2. Webhooks → Add webhook
3. **Payload URL:** `http://your-domain/webhooks/github`
4. **Content type:** `application/json`
5. **Events:** Select individual events → push
6. **Active:** ✅
7. Add webhook

---

## 📚 API Reference

### GitHub Webhooks API

**Create Webhook:**
```http
POST https://api.github.com/repos/{owner}/{repo}/hooks
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "web",
  "active": true,
  "events": ["push", "pull_request", "issues"],
  "config": {
    "url": "http://your-domain/webhooks/github",
    "content_type": "json",
    "insecure_ssl": "0"
  }
}
```

**Response:**
```json
{
  "id": 123456789,
  "url": "https://api.github.com/repos/owner/repo/hooks/123456789",
  "active": true,
  "events": ["push", "pull_request", "issues"],
  "config": {
    "url": "http://your-domain/webhooks/github",
    "content_type": "json"
  }
}
```

---

## 🎯 Future Enhancements

- [ ] Webhook status indicator on project page
- [ ] Manual webhook sync button
- [ ] Webhook test/ping functionality
- [ ] Webhook delivery logs
- [ ] Support for more events (PR, issues, etc.)
- [ ] Webhook secret for security
- [ ] Retry failed webhook setup

---

## 🔐 Security Considerations

### 1. Token Storage

GitHub tokens are stored encrypted in database:
```php
// User model
protected $hidden = ['github_token'];
```

### 2. Webhook Secret (TODO)

Future: Add webhook secret for payload verification:
```php
'config' => [
    'url' => $webhookUrl,
    'content_type' => 'json',
    'secret' => env('GITHUB_WEBHOOK_SECRET'),
]
```

### 3. Rate Limiting

GitHub API has rate limits:
- **Authenticated:** 5,000 requests/hour
- **Unauthenticated:** 60 requests/hour

---

## 📋 Checklist

- [x] GitHubWebhookService created
- [x] Migration for webhook fields
- [x] Project model updated
- [x] ProjectController updated
- [x] Create form updated with checkbox
- [x] Auto-setup logic implemented
- [x] Error handling added
- [x] Duplicate prevention
- [x] Status tracking
- [ ] Webhook secret (future)
- [ ] Status indicator UI (future)
- [ ] Manual sync button (future)

---

## 📚 Related Documentation

- [GITHUB-OAUTH.md](GITHUB-OAUTH.md) - GitHub OAuth setup
- [GitHub Webhooks API](https://docs.github.com/en/rest/webhooks)
- [Laravel HTTP Client](https://laravel.com/docs/11.x/http-client)

---

**Need Help?** Check logs at `storage/logs/laravel.log`
