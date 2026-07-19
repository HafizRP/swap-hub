# 🚀 Jenkins CI/CD Configuration Guide

## Overview

Dokumentasi ini menjelaskan cara mengkonfigurasi Jenkins yang sudah ada untuk automated deployment project Swap Hub.

## 📦 Apa Saja yang Harus Disiapkan? (Preparation)

Sebelum melakukan setup di dashboard Jenkins, pastikan hal-hal berikut sudah siap:

1. **Jenkins Server**: Jenkins sudah terinstall dan berjalan di server (misal: berjalan di port `8080`).
2. **Akses Root/Terminal**: Untuk mengambil *initial admin password* dan konfigurasi Docker.
3. **Docker & Git**: Docker, Docker Compose, dan Git sudah terinstall di Jenkins server.
4. **Koneksi Internet**: Untuk mendownload plugin Jenkins saat setup.
5. **Git Credentials (Opsional)**: Username & Personal Access Token (PAT) GitHub atau SSH Key, jika repository bersifat private.

---

## 🛠️ 1. Initial Jenkins Dashboard Setup (Setup Awal)

Jika ini adalah pertama kalinya Jenkins diakses, ikuti langkah berikut:

### 1. Unlock Jenkins
1. Buka browser dan akses dashboard Jenkins: `http://<ip-server-anda>:8080`
2. Anda akan diminta untuk memasukkan **Administrator password**.
3. Buka terminal di server Anda dan jalankan perintah berikut untuk melihat password:
   ```bash
   sudo cat /var/lib/jenkins/secrets/initialAdminPassword
   ```
4. Copy teks hasil perintah tersebut, paste ke dalam kolom di browser, lalu klik **Continue**.

### 2. Customize Jenkins (Install Plugins)
1. Di layar berikutnya, pilih **"Install suggested plugins"**.
2. Tunggu hingga proses instalasi plugin dasar selesai.

### 3. Create First Admin User
1. Isi form untuk membuat akun Admin (Username, Password, Full Name, Email).
2. Simpan baik-baik kredensial ini untuk login selanjutnya.
3. Klik **Save and Continue**.

### 4. Instance Configuration
1. Konfirmasi Jenkins URL (biarkan default sesuai IP/Domain server Anda).
2. Klik **Save and Finish**, lalu klik **Start using Jenkins**.
3. Anda sekarang berada di halaman utama Dashboard Jenkins!

---

## ⚙️ 2. Konfigurasi Plugin Tambahan & Docker Access

Setelah masuk ke Dashboard utama, lakukan konfigurasi berikut:

### Install Required Plugins

Go to: **Manage Jenkins → Manage Plugins → Available**

Install these plugins:
- ✅ **Docker Pipeline**
- ✅ **Git Plugin** (usually pre-installed)
- ✅ **Pipeline** (usually pre-installed)
- ✅ **Workspace Cleanup Plugin**
- ✅ **Slack Notification** (optional)
- ✅ **Blue Ocean** (optional, for better UI)

### Configure Docker Access

Pastikan Jenkins user bisa akses Docker:

```bash
# Add Jenkins user to docker group
sudo usermod -aG docker jenkins

# Restart Jenkins
sudo systemctl restart jenkins

# Verify
sudo -u jenkins docker ps
```

---

## 🚀 3. Create Pipeline Job

### Step-by-Step:

1. **New Item** → Enter name: `swap-hub-deploy` → **Pipeline** → OK

2. **General Configuration:**
   - ✅ Description: "Automated deployment for Swap Hub"
   - ✅ Discard old builds: Keep last 10 builds
   - ✅ GitHub project: `https://github.com/your-username/swap-hub`

3. **Build Triggers:**
   
   **Option A - Poll SCM (Simple):**
   - ✅ Poll SCM
   - Schedule: `H/5 * * * *` (check every 5 minutes)
   
   **Option B - GitHub Webhook (Recommended):**
   - ✅ GitHub hook trigger for GITScm polling
   - Setup webhook di GitHub (lihat section GitHub Webhook)

4. **Pipeline Configuration:**
   - Definition: **Pipeline script from SCM**
   - SCM: **Git**
   - Repository URL: `https://github.com/your-username/swap-hub.git`
   - Credentials: Add your Git credentials if private repo
   - Branch Specifier: `*/main`
   - Script Path: `Jenkinsfile`

5. **Save**

---

## 🔗 4. Configure GitHub Webhook (Optional)

Untuk automatic builds on push:

### Di GitHub:

1. Go to repository → **Settings → Webhooks → Add webhook**

2. **Payload URL:** `http://your-jenkins-server:8080/github-webhook/`

3. **Content type:** `application/json`

4. **Which events:** Just the push event

5. **Active:** ✅

6. **Add webhook**

### Di Jenkins:

1. Job → **Configure**
2. **Build Triggers** → ✅ GitHub hook trigger for GITScm polling
3. **Save**

---

## 🔐 5. Environment Variables (Optional)

Configure di: **Manage Jenkins → Configure System → Global properties → Environment variables**

| Variable | Default | Description |
|----------|---------|-------------|
| `DOCKER_IMAGE` | `swap-hub-app` | Docker image name |
| `APP_PORT` | `5541` | Application port |
| `GIT_BRANCH` | `main` | Git branch to deploy |

---

## 🧪 6. Test Pipeline

### Manual Build:

1. Go to `swap-hub-deploy` job
2. Click **Build Now**
3. Monitor progress in **Console Output**

### Expected Output:

```
Started by user admin
Checking out git https://github.com/your-username/swap-hub.git
[Pipeline] stage (Checkout)
[Pipeline] stage (Environment Setup)
[Pipeline] stage (Install Dependencies)
[Pipeline] stage (Build Assets)
[Pipeline] stage (Run Tests)
[Pipeline] stage (Build Docker Images)
[Pipeline] stage (Deploy)
[Pipeline] stage (Database Migration)
[Pipeline] stage (Cache Optimization)
[Pipeline] stage (Health Check)
✅ Application is healthy!
[Pipeline] stage (Cleanup)
✅ Pipeline completed successfully!
```

---

## Pipeline Stages Explained

### 1. **Checkout** 📥
- Clone repository from Git
- Checkout specified branch

### 2. **Environment Setup** ⚙️
- Copy `env-server.txt` to `.env`
- Prepare environment configuration

### 3. **Install Dependencies** 📦 (Parallel)
- Install PHP dependencies with Composer
- Install Node dependencies with NPM
- Runs in parallel for faster build

### 4. **Build Assets** 🏗️
- Compile frontend assets with Vite
- Generate production-ready CSS/JS

### 5. **Run Tests** 🧪
- Execute PHPUnit tests
- Run in parallel mode
- Continue on test failure (UNSTABLE)

### 6. **Build Docker Images** 🐳
- Build Docker images from Dockerfile
- Multi-stage build for optimization

### 7. **Stop Old Containers** 🛑
- Gracefully stop running containers
- Prepare for new deployment

### 8. **Deploy** 🚀
- Start new containers with docker-compose
- Wait for containers to be healthy
- Verify container status

### 9. **Database Migration** 🗄️
- Run Laravel migrations
- Update database schema

### 10. **Cache Optimization** ⚡
- Cache config, routes, and views
- Optimize application performance

### 11. **Health Check** 🏥
- Verify application is responding
- Check HTTP endpoint

### 12. **Cleanup** 🧹
- Remove dangling Docker images
- Clean up unused volumes

**Total Time:** ~5-7 minutes

---

## Monitoring

### View Build Status

```bash
# Via Jenkins CLI
curl http://your-jenkins-server:8080/job/swap-hub-deploy/lastBuild/api/json

# Via browser
http://your-jenkins-server:8080/job/swap-hub-deploy/
```

### View Logs

1. Go to job → Build number → **Console Output**
2. Or check archived logs in workspace

### Check Application

```bash
# Health check
curl http://your-server:5541

# Check containers
docker-compose ps

# View logs
docker-compose logs -f
```

---

## Troubleshooting

### ❌ Docker permission denied

```bash
# Add Jenkins to docker group
sudo usermod -aG docker jenkins
sudo systemctl restart jenkins

# Verify
sudo -u jenkins docker ps
```

### ❌ Port already in use

```bash
# Check what's using port 5541
sudo lsof -i :5541

# Stop old containers
docker-compose down

# Or change port in docker-compose.yml
```

### ❌ Build fails on tests

Tests are set to UNSTABLE on failure, so deployment continues.

To make tests mandatory, edit `Jenkinsfile`:
```groovy
// Remove try-catch in 'Run Tests' stage
sh 'docker run --rm -v $(pwd):/var/www -w /var/www php:8.4-cli php artisan test'
```

### ❌ Database migration fails

```bash
# Check database connection
docker-compose exec app php artisan migrate:status

# Manual migration
docker-compose exec app php artisan migrate --force

# Reset database (CAUTION: destroys data)
docker-compose exec app php artisan migrate:fresh --seed
```

### ❌ Health check fails

```bash
# Check if nginx is running
docker-compose ps nginx

# Check nginx logs
docker-compose logs nginx

# Test manually
curl -v http://localhost:5541
```

### ❌ Git authentication fails

**For HTTPS:**
```bash
# Add credentials in Jenkins
Manage Jenkins → Manage Credentials → Add Credentials
- Kind: Username with password
- Username: your-github-username
- Password: your-github-token
```

**For SSH:**
```bash
# Add SSH key in Jenkins
Manage Jenkins → Manage Credentials → Add Credentials
- Kind: SSH Username with private key
- Private Key: Enter directly or from file
```

---

## Advanced Configuration

### 1. Multi-Environment Deployment

Edit `Jenkinsfile` untuk support multiple environments:

```groovy
parameters {
    choice(name: 'ENVIRONMENT', choices: ['dev', 'staging', 'production'], description: 'Deployment environment')
}

environment {
    APP_ENV = "${params.ENVIRONMENT}"
}
```

### 2. Slack Notifications

Install **Slack Notification Plugin**, then add to `Jenkinsfile`:

```groovy
post {
    success {
        slackSend(
            color: 'good',
            message: "✅ Deployment successful: ${env.JOB_NAME} #${env.BUILD_NUMBER}\n${env.BUILD_URL}"
        )
    }
    failure {
        slackSend(
            color: 'danger',
            message: "❌ Deployment failed: ${env.JOB_NAME} #${env.BUILD_NUMBER}\n${env.BUILD_URL}"
        )
    }
}
```

### 3. Email Notifications

Install **Email Extension Plugin**, then add:

```groovy
post {
    always {
        emailext(
            subject: "Build ${currentBuild.result}: ${env.JOB_NAME} #${env.BUILD_NUMBER}",
            body: """
                Build: ${env.BUILD_URL}
                Status: ${currentBuild.result}
                Duration: ${currentBuild.durationString}
            """,
            to: 'team@example.com'
        )
    }
}
```

### 4. Deploy to Remote Server

For deploying to remote server via SSH:

```groovy
stage('Deploy to Production') {
    steps {
        sshagent(['production-server-key']) {
            sh '''
                ssh user@production-server "
                    cd /path/to/swap-hub &&
                    git pull origin main &&
                    docker-compose up -d --build
                "
            '''
        }
    }
}
```

### 5. Parallel Testing

Run tests in parallel for faster execution:

```groovy
stage('Run Tests') {
    parallel {
        stage('Unit Tests') {
            steps {
                sh 'php artisan test --testsuite=Unit'
            }
        }
        stage('Feature Tests') {
            steps {
                sh 'php artisan test --testsuite=Feature'
            }
        }
    }
}
```

---

## Security Best Practices

### 1. Secure Credentials

Store sensitive data in Jenkins Credentials:

**Manage Jenkins → Manage Credentials → Add Credentials**

```groovy
environment {
    DB_PASSWORD = credentials('swap-hub-db-password')
    APP_KEY = credentials('swap-hub-app-key')
}
```

### 2. Restrict Job Permissions

**Manage Jenkins → Configure Global Security → Authorization**

- Use **Matrix-based security**
- Limit who can trigger builds
- Separate read/write permissions

### 3. Audit Logs

Enable audit logging:

**Manage Jenkins → Configure System → Audit Trail**

---

## Performance Optimization

### 1. Docker Layer Caching

Already implemented in Jenkinsfile:
```groovy
sh 'docker-compose build --no-cache'
```

### 2. Workspace Cleanup

Already implemented in `post.always` block

### 3. Concurrent Builds

Allow concurrent builds for different branches:

Job → **Configure** → ✅ Execute concurrent builds if necessary

---

## Backup Strategy

### Backup Jenkins Job Configuration

```bash
# Backup job config
cp -r /var/lib/jenkins/jobs/swap-hub-deploy ~/jenkins-backup/

# Or use Jenkins CLI
java -jar jenkins-cli.jar -s http://localhost:8080/ get-job swap-hub-deploy > swap-hub-deploy.xml
```

### Restore Job

```bash
# Via Jenkins CLI
java -jar jenkins-cli.jar -s http://localhost:8080/ create-job swap-hub-deploy < swap-hub-deploy.xml
```

---

## Common Commands

### Trigger Build via CLI

```bash
# Download Jenkins CLI
wget http://your-jenkins:8080/jnlpJars/jenkins-cli.jar

# Trigger build
java -jar jenkins-cli.jar -s http://your-jenkins:8080/ build swap-hub-deploy

# With parameters
java -jar jenkins-cli.jar -s http://your-jenkins:8080/ build swap-hub-deploy -p ENVIRONMENT=production
```

### Trigger Build via API

```bash
# Get API token from Jenkins UI
# User → Configure → API Token

# Trigger build
curl -X POST http://your-jenkins:8080/job/swap-hub-deploy/build \
  --user username:api-token

# With parameters
curl -X POST http://your-jenkins:8080/job/swap-hub-deploy/buildWithParameters \
  --user username:api-token \
  --data ENVIRONMENT=production
```

---

## Resources

- 📚 [Jenkins Documentation](https://www.jenkins.io/doc/)
- 🐳 [Docker Pipeline Plugin](https://plugins.jenkins.io/docker-workflow/)
- 🔧 [Pipeline Syntax](https://www.jenkins.io/doc/book/pipeline/syntax/)
- 📖 [Jenkinsfile](./Jenkinsfile) - Pipeline configuration

---

## Quick Checklist

- [ ] Jenkins plugins installed (Docker Pipeline, Git, Pipeline)
- [ ] Jenkins user has Docker access
- [ ] Pipeline job created (`swap-hub-deploy`)
- [ ] Git repository configured
- [ ] Branch specifier set to `*/main`
- [ ] Script path set to `Jenkinsfile`
- [ ] GitHub webhook configured (optional)
- [ ] First build successful
- [ ] Application accessible at http://server:5541

---

**Need Help?** Check Jenkins logs: `sudo journalctl -u jenkins -f`
