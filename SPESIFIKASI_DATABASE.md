# SPESIFIKASI FILE DATABASE
**Swap Hub - Student Collaboration Platform**

---

## A. Spesifikasi File User

**Nama file:** User  
**Akronim:** tabel_user.MYD  
**Tipe file:** File master  
**Access file:** Random  
**Panjang record:** 1024 bytes  
**Field key:** id  
**Software:** MySQL

| No | Element data | Nama field | Type | Size | Keterangan |
|----|--------------|------------|------|------|------------|
| 1 | Id User | id | Bigint | 20 | Primary key, Auto increment |
| 2 | Name | name | Varchar | 255 | Nama lengkap user |
| 3 | Email | email | Varchar | 255 | Email user (unique) |
| 4 | Email Verified At | email_verified_at | Timestamp | - | Waktu verifikasi email |
| 5 | Password | password | Varchar | 255 | Password terenkripsi |
| 6 | Remember Token | remember_token | Varchar | 100 | Token remember me |
| 7 | Avatar | avatar | Varchar | 255 | URL foto profil |
| 8 | Bio | bio | Text | - | Biografi user |
| 9 | University | university | Varchar | 255 | Nama universitas |
| 10 | Major | major | Varchar | 255 | Jurusan |
| 11 | Graduation Year | graduation_year | Int | 11 | Tahun lulus |
| 12 | Phone | phone | Varchar | 255 | Nomor telepon |
| 13 | Student ID | student_id | Varchar | 255 | NIM/Student ID |
| 14 | Reputation Points | reputation_points | Int | 11 | Poin reputasi (default: 0) |
| 15 | Github Username | github_username | Varchar | 255 | Username GitHub |
| 16 | LinkedIn URL | linkedin_url | Varchar | 255 | URL profil LinkedIn |
| 17 | Portfolio URL | portfolio_url | Varchar | 255 | URL portfolio |
| 18 | Google ID | google_id | Varchar | 255 | Google OAuth ID (unique) |
| 19 | Google Token | google_token | Text | - | Token Google OAuth |
| 20 | Google Refresh Token | google_refresh_token | Text | - | Refresh token Google |
| 21 | Role ID | role_id | Bigint | 20 | Foreign key ke tabel roles |
| 22 | Created At | created_at | Timestamp | - | Waktu pembuatan record |
| 23 | Updated At | updated_at | Timestamp | - | Waktu update record |

**Tabel 1. Spesifikasi File User**

---

## B. Spesifikasi File Roles

**Nama file:** Roles  
**Akronim:** tabel_roles.MYD  
**Tipe file:** File master  
**Access file:** Random  
**Panjang record:** 256 bytes  
**Field key:** id  
**Software:** MySQL

| No | Element data | Nama field | Type | Size | Keterangan |
|----|--------------|------------|------|------|------------|
| 1 | Id Role | id | Bigint | 20 | Primary key, Auto increment |
| 2 | Name | name | Varchar | 255 | Nama role (Admin, Student) |
| 3 | Slug | slug | Varchar | 255 | Slug role (admin, student) - unique |
| 4 | Description | description | Text | - | Deskripsi role |
| 5 | Created At | created_at | Timestamp | - | Waktu pembuatan record |
| 6 | Updated At | updated_at | Timestamp | - | Waktu update record |

**Tabel 2. Spesifikasi File Roles**

---

## C. Spesifikasi File Skills

**Nama file:** Skills  
**Akronim:** tabel_skills.MYD  
**Tipe file:** File master  
**Access file:** Random  
**Panjang record:** 256 bytes  
**Field key:** id  
**Software:** MySQL

| No | Element data | Nama field | Type | Size | Keterangan |
|----|--------------|------------|------|------|------------|
| 1 | Id Skill | id | Bigint | 20 | Primary key, Auto increment |
| 2 | Name | name | Varchar | 255 | Nama skill (unique) |
| 3 | Category | category | Varchar | 255 | Kategori skill |
| 4 | Created At | created_at | Timestamp | - | Waktu pembuatan record |
| 5 | Updated At | updated_at | Timestamp | - | Waktu update record |

**Tabel 3. Spesifikasi File Skills**

---

## D. Spesifikasi File Skill User

**Nama file:** Skill User  
**Akronim:** tabel_skill_user.MYD  
**Tipe file:** File transaksi  
**Access file:** Random  
**Panjang record:** 128 bytes  
**Field key:** id  
**Software:** MySQL

| No | Element data | Nama field | Type | Size | Keterangan |
|----|--------------|------------|------|------|------------|
| 1 | Id | id | Bigint | 20 | Primary key, Auto increment |
| 2 | User ID | user_id | Bigint | 20 | Foreign key ke tabel users |
| 3 | Skill ID | skill_id | Bigint | 20 | Foreign key ke tabel skills |
| 4 | Proficiency Level | proficiency_level | Enum | - | beginner, intermediate, advanced, expert |
| 5 | Years of Experience | years_of_experience | Int | 11 | Tahun pengalaman |
| 6 | Created At | created_at | Timestamp | - | Waktu pembuatan record |
| 7 | Updated At | updated_at | Timestamp | - | Waktu update record |

**Tabel 4. Spesifikasi File Skill User**

---

## E. Spesifikasi File Projects

**Nama file:** Projects  
**Akronim:** tabel_projects.MYD  
**Tipe file:** File master  
**Access file:** Random  
**Panjang record:** 768 bytes  
**Field key:** id  
**Software:** MySQL

| No | Element data | Nama field | Type | Size | Keterangan |
|----|--------------|------------|------|------|------------|
| 1 | Id Project | id | Bigint | 20 | Primary key, Auto increment |
| 2 | Owner ID | owner_id | Bigint | 20 | Foreign key ke tabel users |
| 3 | Title | title | Varchar | 255 | Judul project |
| 4 | Description | description | Text | - | Deskripsi project |
| 5 | Category | category | Varchar | 255 | Kategori project |
| 6 | Skills Needed | skills_needed | Varchar | 255 | Skill yang dibutuhkan |
| 7 | Skills Offered | skills_offered | Varchar | 255 | Skill yang ditawarkan |
| 8 | Difficulty | difficulty | Enum | - | beginner, intermediate, advanced |
| 9 | Status | status | Enum | - | active, completed, cancelled |
| 10 | Max Members | max_members | Int | 11 | Maksimal anggota (default: 5) |
| 11 | Github Repo URL | github_repo_url | Varchar | 255 | URL repository GitHub |
| 12 | Github Repo Name | github_repo_name | Varchar | 255 | Nama repository |
| 13 | Github Webhook ID | github_webhook_id | Varchar | 255 | ID webhook GitHub |
| 14 | Created At | created_at | Timestamp | - | Waktu pembuatan record |
| 15 | Updated At | updated_at | Timestamp | - | Waktu update record |

**Tabel 5. Spesifikasi File Projects**

---

## F. Spesifikasi File Project Members

**Nama file:** Project Members  
**Akronim:** tabel_project_members.MYD  
**Tipe file:** File transaksi  
**Access file:** Random  
**Panjang record:** 512 bytes  
**Field key:** id  
**Software:** MySQL

| No | Element data | Nama field | Type | Size | Keterangan |
|----|--------------|------------|------|------|------------|
| 1 | Id | id | Bigint | 20 | Primary key, Auto increment |
| 2 | Project ID | project_id | Bigint | 20 | Foreign key ke tabel projects |
| 3 | User ID | user_id | Bigint | 20 | Foreign key ke tabel users |
| 4 | Role | role | Varchar | 255 | Role dalam project (default: member) |
| 5 | Status | status | Enum | - | pending, active, rejected |
| 6 | Is Validated | is_validated | Boolean | 1 | Status validasi kontribusi |
| 7 | Joined At | joined_at | Timestamp | - | Waktu bergabung |
| 8 | Application Message | application_message | Text | - | Pesan aplikasi |
| 9 | Rejection Reason | rejection_reason | Text | - | Alasan penolakan |
| 10 | Created At | created_at | Timestamp | - | Waktu pembuatan record |
| 11 | Updated At | updated_at | Timestamp | - | Waktu update record |

**Tabel 6. Spesifikasi File Project Members**

---

## G. Spesifikasi File Skill Swap Requests

**Nama file:** Skill Swap Requests  
**Akronim:** tabel_skill_swap_requests.MYD  
**Tipe file:** File transaksi  
**Access file:** Random  
**Panjang record:** 384 bytes  
**Field key:** id  
**Software:** MySQL

| No | Element data | Nama field | Type | Size | Keterangan |
|----|--------------|------------|------|------|------------|
| 1 | Id | id | Bigint | 20 | Primary key, Auto increment |
| 2 | Requester ID | requester_id | Bigint | 20 | Foreign key ke tabel users |
| 3 | Provider ID | provider_id | Bigint | 20 | Foreign key ke tabel users |
| 4 | Skill Offered ID | skill_offered_id | Bigint | 20 | Foreign key ke tabel skills |
| 5 | Skill Requested ID | skill_requested_id | Bigint | 20 | Foreign key ke tabel skills |
| 6 | Message | message | Text | - | Pesan request |
| 7 | Status | status | Enum | - | pending, accepted, rejected, completed |
| 8 | Created At | created_at | Timestamp | - | Waktu pembuatan record |
| 9 | Updated At | updated_at | Timestamp | - | Waktu update record |

**Tabel 7. Spesifikasi File Skill Swap Requests**

---

## H. Spesifikasi File Conversations

**Nama file:** Conversations  
**Akronim:** tabel_conversations.MYD  
**Tipe file:** File master  
**Access file:** Random  
**Panjang record:** 128 bytes  
**Field key:** id  
**Software:** MySQL

| No | Element data | Nama field | Type | Size | Keterangan |
|----|--------------|------------|------|------|------------|
| 1 | Id | id | Bigint | 20 | Primary key, Auto increment |
| 2 | Type | type | Enum | - | direct, project |
| 3 | Project ID | project_id | Bigint | 20 | Foreign key ke tabel projects |
| 4 | Created At | created_at | Timestamp | - | Waktu pembuatan record |
| 5 | Updated At | updated_at | Timestamp | - | Waktu update record |

**Tabel 8. Spesifikasi File Conversations**

---

## I. Spesifikasi File Conversation User

**Nama file:** Conversation User  
**Akronim:** tabel_conversation_user.MYD  
**Tipe file:** File transaksi  
**Access file:** Random  
**Panjang record:** 64 bytes  
**Field key:** id  
**Software:** MySQL

| No | Element data | Nama field | Type | Size | Keterangan |
|----|--------------|------------|------|------|------------|
| 1 | Id | id | Bigint | 20 | Primary key, Auto increment |
| 2 | Conversation ID | conversation_id | Bigint | 20 | Foreign key ke tabel conversations |
| 3 | User ID | user_id | Bigint | 20 | Foreign key ke tabel users |
| 4 | Created At | created_at | Timestamp | - | Waktu pembuatan record |
| 5 | Updated At | updated_at | Timestamp | - | Waktu update record |

**Tabel 9. Spesifikasi File Conversation User**

---

## J. Spesifikasi File Messages

**Nama file:** Messages  
**Akronim:** tabel_messages.MYD  
**Tipe file:** File transaksi  
**Access file:** Random  
**Panjang record:** 512 bytes  
**Field key:** id  
**Software:** MySQL

| No | Element data | Nama field | Type | Size | Keterangan |
|----|--------------|------------|------|------|------------|
| 1 | Id | id | Bigint | 20 | Primary key, Auto increment |
| 2 | Conversation ID | conversation_id | Bigint | 20 | Foreign key ke tabel conversations |
| 3 | User ID | user_id | Bigint | 20 | Foreign key ke tabel users |
| 4 | Message | message | Text | - | Isi pesan |
| 5 | Attachments | attachments | JSON | - | Metadata attachment (deprecated) |
| 6 | Is Read | is_read | Boolean | 1 | Status baca pesan |
| 7 | Created At | created_at | Timestamp | - | Waktu pembuatan record |
| 8 | Updated At | updated_at | Timestamp | - | Waktu update record |

**Tabel 10. Spesifikasi File Messages**

---

## K. Spesifikasi File Message Attachments

**Nama file:** Message Attachments  
**Akronim:** tabel_message_attachments.MYD  
**Tipe file:** File transaksi  
**Access file:** Random  
**Panjang record:** 384 bytes  
**Field key:** id  
**Software:** MySQL

| No | Element data | Nama field | Type | Size | Keterangan |
|----|--------------|------------|------|------|------------|
| 1 | Id | id | Bigint | 20 | Primary key, Auto increment |
| 2 | Message ID | message_id | Bigint | 20 | Foreign key ke tabel messages |
| 3 | File Name | file_name | Varchar | 255 | Nama file asli |
| 4 | File Path | file_path | Varchar | 255 | Path penyimpanan file |
| 5 | File Type | file_type | Varchar | 255 | MIME type file |
| 6 | File Size | file_size | Bigint | 20 | Ukuran file (bytes) |
| 7 | Created At | created_at | Timestamp | - | Waktu pembuatan record |
| 8 | Updated At | updated_at | Timestamp | - | Waktu update record |

**Tabel 11. Spesifikasi File Message Attachments**

---

## L. Spesifikasi File Tasks

**Nama file:** Tasks  
**Akronim:** tabel_tasks.MYD  
**Tipe file:** File transaksi  
**Access file:** Random  
**Panjang record:** 512 bytes  
**Field key:** id  
**Software:** MySQL

| No | Element data | Nama field | Type | Size | Keterangan |
|----|--------------|------------|------|------|------------|
| 1 | Id | id | Bigint | 20 | Primary key, Auto increment |
| 2 | Project ID | project_id | Bigint | 20 | Foreign key ke tabel projects |
| 3 | Assigned To | assigned_to | Bigint | 20 | Foreign key ke tabel users |
| 4 | Title | title | Varchar | 255 | Judul task |
| 5 | Description | description | Text | - | Deskripsi task |
| 6 | Status | status | Enum | - | pending, in_progress, completed |
| 7 | Priority | priority | Enum | - | low, medium, high |
| 8 | Due Date | due_date | Date | - | Deadline task |
| 9 | Created At | created_at | Timestamp | - | Waktu pembuatan record |
| 10 | Updated At | updated_at | Timestamp | - | Waktu update record |

**Tabel 12. Spesifikasi File Tasks**

---

## M. Spesifikasi File Github Activities

**Nama file:** Github Activities  
**Akronim:** tabel_github_activities.MYD  
**Tipe file:** File transaksi  
**Access file:** Random  
**Panjang record:** 512 bytes  
**Field key:** id  
**Software:** MySQL

| No | Element data | Nama field | Type | Size | Keterangan |
|----|--------------|------------|------|------|------------|
| 1 | Id | id | Bigint | 20 | Primary key, Auto increment |
| 2 | Project ID | project_id | Bigint | 20 | Foreign key ke tabel projects |
| 3 | User ID | user_id | Bigint | 20 | Foreign key ke tabel users |
| 4 | Commit SHA | commit_sha | Varchar | 255 | Hash commit Git |
| 5 | Commit Message | commit_message | Text | - | Pesan commit |
| 6 | Commit URL | commit_url | Varchar | 255 | URL commit di GitHub |
| 7 | Created At | created_at | Timestamp | - | Waktu commit |
| 8 | Updated At | updated_at | Timestamp | - | Waktu update record |

**Tabel 13. Spesifikasi File Github Activities**

---

## N. Spesifikasi File Cache

**Nama file:** Cache  
**Akronim:** tabel_cache.MYD  
**Tipe file:** File sistem  
**Access file:** Random  
**Panjang record:** Variable  
**Field key:** key  
**Software:** MySQL

| No | Element data | Nama field | Type | Size | Keterangan |
|----|--------------|------------|------|------|------------|
| 1 | Key | key | Varchar | 255 | Primary key, cache key |
| 2 | Value | value | Mediumtext | - | Nilai cache |
| 3 | Expiration | expiration | Int | 11 | Timestamp kadaluarsa |

**Tabel 14. Spesifikasi File Cache**

---

## O. Spesifikasi File Jobs

**Nama file:** Jobs  
**Akronim:** tabel_jobs.MYD  
**Tipe file:** File sistem  
**Access file:** Random  
**Panjang record:** Variable  
**Field key:** id  
**Software:** MySQL

| No | Element data | Nama field | Type | Size | Keterangan |
|----|--------------|------------|------|------|------------|
| 1 | Id | id | Bigint | 20 | Primary key, Auto increment |
| 2 | Queue | queue | Varchar | 255 | Nama queue |
| 3 | Payload | payload | Longtext | - | Data job |
| 4 | Attempts | attempts | Tinyint | 3 | Jumlah percobaan |
| 5 | Reserved At | reserved_at | Int | 10 | Timestamp reserved |
| 6 | Available At | available_at | Int | 10 | Timestamp available |
| 7 | Created At | created_at | Int | 10 | Timestamp pembuatan |

**Tabel 15. Spesifikasi File Jobs**

---

**Total Tabel:** 15 tabel utama  
**Database Engine:** InnoDB  
**Character Set:** utf8mb4  
**Collation:** utf8mb4_unicode_ci

---

**Dibuat:** 6 Januari 2026  
**Versi:** 1.0.0
