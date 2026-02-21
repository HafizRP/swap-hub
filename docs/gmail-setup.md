# Gmail SMTP Setup untuk Email Verification

## 📧 Langkah-langkah Setup Gmail SMTP

### 1. Generate App Password di Google Account

1. **Buka Google Account Security**
   - URL: https://myaccount.google.com/security

2. **Enable 2-Step Verification** (jika belum aktif)
   - Scroll ke "How you sign in to Google"
   - Click "2-Step Verification"
   - Follow instruksi untuk enable

3. **Generate App Password**
   - URL: https://myaccount.google.com/apppasswords
   - Atau: Google Account → Security → 2-Step Verification → App passwords
   - Select app: **Mail**
   - Select device: **Other (Custom name)** → ketik "Laravel Swap Hub"
   - Click **Generate**
   - **Copy password** yang muncul (16 karakter, tanpa spasi)

### 2. Update File `.env`

Edit file `.env` di root project:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=abcd efgh ijkl mnop
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

**Ganti:**
- `your-email@gmail.com` → Email Gmail Anda
- `abcd efgh ijkl mnop` → App password dari step 1 (bisa dengan atau tanpa spasi)

### 3. Clear Config Cache

Setelah update `.env`, jalankan:

```bash
php artisan config:clear
php artisan cache:clear
```

### 4. Test Email

Register account baru atau resend verification email. Email seharusnya masuk ke inbox.

## 🔍 Troubleshooting

### Email tidak masuk?

1. **Cek spam folder**
2. **Cek Laravel log** untuk error:
   ```bash
   tail -f storage/logs/laravel.log
   ```
3. **Pastikan App Password benar** (16 karakter)
4. **Pastikan 2-Step Verification aktif** di Google Account
5. **Test dengan Tinker**:
   ```bash
   php artisan tinker
   Mail::raw('Test email', function($msg) { $msg->to('test@example.com')->subject('Test'); });
   ```

### Error "Username and Password not accepted"

- App password salah atau expired
- 2-Step Verification belum aktif
- Generate ulang App Password

### Error "Connection timeout"

- Port 587 blocked (coba port 465 dengan `MAIL_ENCRYPTION=ssl`)
- Firewall blocking SMTP

## 📝 Notes

- **Development**: Bisa gunakan `MAIL_MAILER=log` untuk testing tanpa kirim email real
- **Production**: Wajib gunakan `MAIL_MAILER=smtp` dengan credentials yang benar
- **Security**: Jangan commit `.env` ke git (sudah ada di `.gitignore`)
- **Alternative**: Bisa gunakan Mailtrap.io untuk development testing

## 🎯 Email Verification Flow

1. User register → Email verification dikirim otomatis
2. User cek email → Klik link verification
3. Email verified → User bisa akses semua fitur
4. Jika belum verify → Redirect ke `/email/verify`

## 🔐 Production Setup

Di production (Docker), pastikan `env-server.txt` sudah diupdate dengan Gmail credentials yang benar, lalu rebuild:

```bash
docker compose down
docker compose build --no-cache
docker compose up -d
```
