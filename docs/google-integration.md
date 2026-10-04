# 🌐 Google Integration Guide (OAuth & Google Calendar)

Panduan konfigurasi integrasi Google di Swap Hub, mencakup **Google OAuth 2.0 (Socialite)** dan **Google Calendar API (Spatie Laravel Google Calendar)**.

---

## 1. Arsitektur Integrasi

Swap Hub memanfaatkan dua layanan Google Cloud Platform (GCP):
1. **Google OAuth 2.0**: Memungkinkan mahasiswa login atau registrasi menggunakan akun Google kampus/pribadi via Socialite (`/auth/google`).
2. **Google Calendar API**:
   - Tiap project baru otomatis membuat kalender Google tersendiri via `CreateProjectGoogleCalendar`.
   - Anggota project otomatis ditambahkan ke Access Control List (ACL) kalender via `AddMemberToProjectCalendar`.
   - Task dengan tenggat waktu (`due_date`) otomatis disinkronkan menjadi Google Calendar Event via `CreateGoogleCalendarEvent`.

---

## 2. Setup Google Cloud Console

### 2.1 Buat Project GCP
1. Buka [Google Cloud Console](https://console.cloud.google.com/).
2. Buat project baru, beri nama (misal: `swap-hub-prod` atau `swap-hub-dev`).
3. Pilih project yang baru dibuat.

### 2.2 Aktifkan API yang Dibutuhkan
1. Navigasi ke **APIs & Services > Library**.
2. Cari dan klik **Google Calendar API**, lalu klik **Enable**.

---

## 3. Konfigurasi Google OAuth 2.0

### 3.1 OAuth Consent Screen
1. Navigasi ke **APIs & Services > OAuth consent screen**.
2. Pilih User Type (**External** atau **Internal** jika menggunakan Google Workspace kampus).
3. Isi informasi aplikasi:
   - **App name**: Swap Hub
   - **User support email**: Email admin/pengembang
   - **Developer contact information**: Email pengembang
4. Scopes yang dibutuhkan:
   - `.../auth/userinfo.email`
   - `.../auth/userinfo.profile`
   - `openid`
5. Simpan dan lanjutkan.

### 3.2 Credentials OAuth Client
1. Navigasi ke **APIs & Services > Credentials**.
2. Klik **Create Credentials > OAuth client ID**.
3. Pilih Application Type: **Web application**.
4. Isi data:
   - **Name**: `Swap Hub Web Client`
   - **Authorized JavaScript origins**:
     - `http://localhost:5541` (pengembangan Docker)
     - `https://your-domain.com` (produksi)
   - **Authorized redirect URIs**:
     - `http://localhost:5541/auth/google/callback` (pengembangan Docker)
     - `https://your-domain.com/auth/google/callback` (produksi)
5. Simpan dan salin **Client ID** serta **Client Secret**.

### 3.3 Konfigurasi Environment (`.env`)
Tambahkan ke file `.env`:
```env
GOOGLE_CLIENT_ID=your-google-client-id.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=your-google-client-secret
GOOGLE_REDIRECT_URI=http://localhost:5541/auth/google/callback
```

---

## 4. Konfigurasi Google Calendar (Service Account)

Spatie Laravel Google Calendar menggunakan Service Account untuk operasi headless kalender otomatis di background worker.

### 4.1 Buat Service Account di GCP
1. Di Google Cloud Console, buka **IAM & Admin > Service Accounts**.
2. Klik **Create Service Account**.
3. Isi nama service account (contoh: `swap-hub-calendar-sync`).
4. Klik **Create and Continue**, lewati opsi permission (tidak perlu role GCP spesifik untuk Calendar API), lalu klik **Done**.

### 4.2 Generate JSON Key
1. Klik service account yang baru dibuat, masuk ke tab **Keys**.
2. Klik **Add Key > Create new key**.
3. Pilih tipe **JSON**, lalu klik **Create**. File kredensial JSON akan terunduh ke komputer Anda.

### 4.3 Penempatan File Kredensial di Swap Hub
1. Buat direktori `storage/app/google-calendar/` jika belum ada:
   ```bash
   mkdir -p storage/app/google-calendar
   ```
2. Pindahkan file JSON ke direktori tersebut dan beri nama standar:
   ```bash
   storage/app/google-calendar/service-account-credentials.json
   ```
3. Pastikan izin akses file sesuai agar PHP-FPM / Docker dapat membacanya:
   ```bash
   chmod 600 storage/app/google-calendar/service-account-credentials.json
   ```
> ⚠️ **PENTING**: File `service-account-credentials.json` sudah di-ignore oleh `.gitignore`. Jangan pernah commit file kredensial ini ke repository publik atau Git.

### 4.4 Konfigurasi Environment (`.env`)
Tambahkan ke file `.env`:
```env
GOOGLE_CALENDAR_AUTH_PROFILE=service_account
GOOGLE_CALENDAR_SERVICE_ACCOUNT_CREDENTIALS=storage/app/google-calendar/service-account-credentials.json
# Opsional: fallback default calendar ID jika task tidak terikat ke project
# GOOGLE_CALENDAR_ID=your-calendar-id@group.calendar.google.com
```

---

## 5. Alur Kerja Sinkronisasi (Lifecycle)

### 5.1 Pembuatan Proyek Baru (`CreateProjectGoogleCalendar`)
- Ketika user membuat proyek (`POST /projects`), controller men-dispatch job `App\Jobs\CreateProjectGoogleCalendar`.
- Service account membuat kalender baru dengan summary `Swap Hub - {Project Title}`.
- ID kalender disimpan ke kolom `projects.google_calendar_id`.
- Kalender otomatis dibagikan (`AclRule`) ke email Google owner project dengan role `owner`.

### 5.2 Penambahan Anggota (`AddMemberToProjectCalendar`)
- Ketika anggota bergabung via `POST /projects/{project}/members` atau aplikasi disetujui di `/projects/{project}/applications/{user}/accept`:
- Job `App\Jobs\AddMemberToProjectCalendar` dijalankan.
- Email anggota didaftarkan ke ACL kalender proyek:
  - Role `contributor` ➔ ACL `reader`
  - Role selain contributor (lead/maintainer) ➔ ACL `writer`

### 5.3 Sinkronisasi Task & Deadline (`CreateGoogleCalendarEvent`)
- Ketika task dibuat pada Kanban Board (`TaskBoard.php` Livewire component):
- Job `App\Jobs\CreateGoogleCalendarEvent` dijalankan.
- Jika proyek belum selesai membuat kalender (kondisi balapan antrean), job akan release ulang ke queue selama 30 detik (hingga 3 percobaan).
- Event dibuat dengan rentang waktu dari `due_date`, dan ID event disimpan di `tasks.google_event_id`.

---

## 6. Verifikasi & Troubleshooting

### Tes Jalur Queue Worker
Sinkronisasi Google Calendar berjalan via queue. Pastikan worker aktif:
```bash
# Di dalam container Docker
docker compose exec app php artisan queue:work --tries=3
```

### Log Debugging
Periksa error Google Calendar di log Laravel:
```bash
docker compose exec app grep -i "google" storage/logs/laravel.log
```

### Masalah Umum:
1. **`Google_Service_Exception 403 (Rate Limit or API Not Enabled)`**:
   - Pastikan Google Calendar API sudah di-enable di GCP Console pada project yang benar.
2. **`Could not find credentials file`**:
   - Periksa path file JSON di `.env`. Di Docker, path relatif `storage/app/...` dievaluasi dari `/var/www`.
3. **User tidak melihat kalender proyek di Google Calendar**:
   - Google Calendar tidak selalu langsung muncul di UI user. User mungkin perlu membuka link penambahan kalender atau memeriksa bagian *Other calendars* di calendar.google.com menggunakan email yang sama dengan email akun Swap Hub mereka.
