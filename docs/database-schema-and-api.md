# 🗄️ Database Schema & Route Catalog

Dokumentasi skema database inti, relasi tabel, dan katalog rute lengkap Swap Hub.

---

## 1. Port & Service Environment

| Service | Port Host | Port Container | Keterangan |
|---------|-----------|----------------|------------|
| `app` | `5541` | `80` | Nginx + PHP-FPM 8.4 runtime web |
| `db` | `3306` | `3306` | MariaDB 10.11 storage |
| `redis` | `6379` | `6379` | Cache, Session, dan Queue driver |
| `reverb` | `8080` | `8080` | WebSocket daemon (di-proxy oleh Nginx pada `/app`) |

---

## 2. Skema Database Inti

### `users`
| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | `BIGINT UNSIGNED` (PK) | ID unik user |
| `name` | `VARCHAR(255)` | Nama lengkap |
| `email` | `VARCHAR(255)` (UNIQUE) | Alamat email terdaftar |
| `password` | `VARCHAR(255)` (NULLABLE) | Hash password (null jika murni login OAuth) |
| `role_id` | `BIGINT UNSIGNED` (FK → `roles.id`) | Role sistem (1 = Admin, 2 = Student) |
| `avatar` | `VARCHAR(255)` (NULLABLE) | Path avatar profil |
| `credits` | `INTEGER` (DEFAULT 0) | Saldo kredit mahasiswa |
| `reputation_score` | `INTEGER` (DEFAULT 0) | Total skor reputasi |
| `github_id` | `VARCHAR(255)` (NULLABLE) | ID pengguna GitHub OAuth |
| `google_id` | `VARCHAR(255)` (NULLABLE) | ID pengguna Google OAuth |
| `email_verified_at` | `TIMESTAMP` (NULLABLE) | Timestamp verifikasi email |
| `suspended_at` | `TIMESTAMP` (NULLABLE) | Waktu penangguhan akun oleh admin |
| `suspension_reason` | `VARCHAR(255)` (NULLABLE) | Alasan penangguhan akun |

### `skills` & `user_skills`
- **`skills`**: `id`, `name` (UNIQUE), `category`.
- **`user_skills`** (Pivot): `user_id` (FK), `skill_id` (FK), `proficiency` ('beginner', 'intermediate', 'advanced').

### `projects` & `project_members`
- **`projects`**: `id`, `owner_id` (FK → `users.id`), `title`, `slug`, `description`, `status` ('open', 'in_progress', 'completed', 'archived'), `github_repo_url`, `google_calendar_id`, `start_date`, `end_date`.
- **`project_members`**: `id`, `project_id` (FK), `user_id` (FK), `role` ('lead', 'contributor', dsb.), `status` ('pending', 'active', 'rejected'), `is_validated` (BOOLEAN).

### `tasks`
| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | `BIGINT UNSIGNED` (PK) | ID tugas |
| `project_id` | `BIGINT UNSIGNED` (FK) | Relasi ke proyek |
| `assigned_to` | `BIGINT UNSIGNED` (FK, NULLABLE)| Mahasiswa penanggung jawab |
| `milestone_id` | `BIGINT UNSIGNED` (FK, NULLABLE)| Relasi ke milestone proyek |
| `title` | `VARCHAR(255)` | Judul tugas |
| `description` | `TEXT` (NULLABLE) | Rincian tugas |
| `priority` | `ENUM` ('low', 'medium', 'high')| Prioritas tugas |
| `status` | `ENUM` ('todo', 'in_progress', 'done')| Status kanban |
| `due_date` | `DATE` (NULLABLE) | Batas waktu penyelesaian |
| `google_event_id`| `VARCHAR(255)` (NULLABLE) | ID event Google Calendar |

### `conversations`, `messages`, `message_attachments`
- **`conversations`**: `id`, `type` ('direct', 'project'), `project_id` (FK, NULLABLE), `name` (NULLABLE).
- **`conversation_participants`**: `conversation_id` (FK), `user_id` (FK).
- **`messages`**: `id`, `conversation_id` (FK), `user_id` (FK), `body` (TEXT), `created_at`.
- **`message_attachments`**: `id`, `message_id` (FK), `file_path`, `file_name`, `file_size`, `mime_type`.

### Modul Kampus, Gamifikasi & Audit
- **`admin_audit_logs`**: `id`, `admin_id` (FK → `users.id`), `action`, `target_type`, `target_id`, `details` (JSON), `ip_address`, `created_at`.
- **`credit_transactions`**: `id`, `user_id` (FK), `amount` (signed int), `type` ('award', 'spend', 'transfer_in', 'transfer_out'), `reason`, `reference_type`, `reference_id`.
- **`code_review_requests`**: `id`, `user_id` (FK), `title`, `description`, `repository_url`, `pr_url`, `bounty_credits`, `status` ('open', 'in_review', 'completed', 'cancelled').
- **`code_review_submissions`**: `id`, `code_review_request_id` (FK), `reviewer_id` (FK), `feedback`, `status` ('pending', 'accepted', 'rejected').
- **`study_sessions`**: `id`, `host_id` (FK), `title`, `description`, `meeting_url`, `status` ('scheduled', 'completed', 'cancelled'), `starts_at`, `ends_at`.
- **`study_session_participants`**: `study_session_id` (FK), `user_id` (FK).
- **`courses`**: `id`, `code` (UNIQUE), `name`, `department`, `semester`.
- **`course_user`**: `course_id` (FK), `user_id` (FK).
- **`project_milestones`**: `id`, `project_id` (FK), `title`, `description`, `due_date`, `status` ('pending', 'in_progress', 'completed').
- **`badges` & `user_badges`**: Gamifikasi pencapaian mahasiswa.
- **`skill_swap_requests`**: `id`, `requester_id` (FK), `provider_id` (FK), `offered_skill_id` (FK), `requested_skill_id` (FK), `status` ('pending', 'accepted', 'completed', 'cancelled').
- **`github_activities`**: `id`, `user_id` (FK), `project_id` (FK), `event_type`, `commit_hash`, `message`, `points`.

---

## 3. Katalog Rute Aplikasi

### 3.1 Rute Publik & Webhook
| Metode | Path | Controller / Livewire | Keterangan |
|---|---|---|---|
| `GET` | `/` | View `welcome` | Landing page |
| `GET` | `/healthz` | `HealthController@liveness` | Health liveness probe |
| `GET` | `/readyz` | `HealthController@readiness` | Readiness DB + Redis probe |
| `POST`| `/webhooks/github` | `GitHubWebhookController@handle` | Ingestion webhook commit/push GitHub |

### 3.2 Autentikasi OAuth
| Metode | Path | Controller | Keterangan |
|---|---|---|---|
| `GET` | `/auth/github` | `GitHubAuthController@redirect` | Inisiasi OAuth GitHub |
| `GET` | `/auth/github/callback` | `GitHubAuthController@callback` | Callback OAuth GitHub |
| `GET` | `/auth/google` | `GoogleAuthController@redirect` | Inisiasi OAuth Google |
| `GET` | `/auth/google/callback` | `GoogleAuthController@callback` | Callback OAuth Google |

### 3.3 Dashboard Mahasiswa & Fitur Utama (`auth`, `verified`)
| Metode | Path | Nama Rute | Keterangan |
|---|---|---|---|
| `GET` | `/dashboard` | `dashboard` | Dashboard utama |
| `GET` | `/profile` | `profile.edit` | Form edit profil |
| `GET` | `/profile/{user}` | `profile.show` | Profil mahasiswa publik |
| `GET` | `/profile/{user}/resume`| `profile.resume` | Download PDF resume |
| `GET` | `/portfolio/{user}` | `portfolio.show` | Portofolio terverifikasi (Livewire) |
| `GET` | `/projects` | `projects.index` | Daftar proyek kolaborasi |
| `POST`| `/projects` | `projects.store` | Buat proyek baru |
| `GET` | `/projects/{project}` | `projects.show` | Detail proyek |
| `GET` | `/projects/{project}/workspace` | `projects.workspace` | Kanban board & file workspace |
| `GET` | `/projects/{project}/matchmaker`| `projects.matchmaker` | Matchmaker anggota tim (Livewire) |
| `GET` | `/projects/{project}/milestones`| `projects.milestones` | Milestone roadmap proyek (Livewire) |
| `GET` | `/chat/{conversation?}` | `chat` | SPA Real-time Chat (Livewire) |
| `GET` | `/skills/swap` | `skills.swap.index` | Daftar skill swap |
| `GET` | `/campus/credits` | `campus.credits` | Mutasi kredit mahasiswa (Livewire) |
| `GET` | `/campus/code-reviews` | `campus.code-reviews` | Bounty code review (Livewire) |
| `GET` | `/campus/study-desk` | `campus.study-desk` | Sesi belajar Jitsi Meet (Livewire) |
| `GET` | `/campus/courses` | `campus.courses` | Tagging mata kuliah (Livewire) |

### 3.4 Area Administrator (`auth`, `admin`)
| Metode | Path | Nama Rute | Keterangan |
|---|---|---|---|
| `GET` | `/admin` | `admin.dashboard` | Dashboard analytics admin |
| `GET` | `/admin/users` | `admin.users.index` | Manajemen pengguna |
| `POST`| `/admin/users/{user}/toggle-role` | `admin.users.toggle-role`| Toggle role student/admin |
| `POST`| `/admin/users/{user}/toggle-suspension` | `admin.users.toggle-suspension`| Tangguhkan / aktifkan kembali akun |
| `GET` | `/admin/projects` | `admin.projects.index`| Moderasi proyek |
| `POST`| `/admin/projects/{project}/archive` | `admin.projects.archive` | Arsipkan proyek |
| `GET` | `/admin/credits` | `admin.credits.index` | Audit mutasi kredit global |
| `POST`| `/admin/credits/adjust` | `admin.credits.adjust` | Penyesuaian manual kredit mahasiswa |
| `GET` | `/admin/code-reviews` | `admin.code-reviews.index` | Moderasi bounty review kode |
| `POST`| `/admin/code-reviews/{codeReview}/cancel` | `admin.code-reviews.cancel` | Batalkan request review & refund bounty |
| `GET` | `/admin/swaps` | `admin.swaps.index` | Moderasi pertukaran skill 1-on-1 |
| `POST`| `/admin/swaps/{skillSwap}/complete` | `admin.swaps.complete` | Selesaikan swap secara administratif |
| `POST`| `/admin/swaps/{skillSwap}/cancel` | `admin.swaps.cancel` | Batalkan swap secara administratif |
| `GET` | `/admin/skills` | `admin.skills.index` | Master data katalog keahlian |
| `POST`| `/admin/skills` | `admin.skills.store` | Tambah keahlian baru |
| `DELETE`| `/admin/skills/{skill}` | `admin.skills.destroy` | Hapus keahlian |
| `GET` | `/admin/courses` | `admin.courses.index` | Master data mata kuliah |
| `POST`| `/admin/courses` | `admin.courses.store` | Tambah mata kuliah resmi |
| `DELETE`| `/admin/courses/{course}` | `admin.courses.destroy` | Hapus mata kuliah |
| `GET` | `/admin/badges` | `admin.badges.index` | Master data lencana penghargaan |
| `POST`| `/admin/badges` | `admin.badges.store` | Buat lencana baru |
| `POST`| `/admin/badges/assign` | `admin.badges.assign` | Sematkan lencana ke mahasiswa |
| `GET` | `/admin/audit-logs` | `admin.audit-logs.index` | Audit trail aktivitas administrator |
| `GET` | `/admin/system-health` | `admin.health.index` | Livewire monitoring latency & status |
