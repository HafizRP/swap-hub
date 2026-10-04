# 🎓 Campus Collaboration & Gamification Features

Dokumentasi lengkap mengenai modul ekosistem kampus pada Swap Hub, mencakup sistem kredit mahasiswa, bounty review kode, ruang belajar virtual, kurikulum mata kuliah, matchmaking tim cerdas, milestone roadmap, serta portofolio mahasiswa terverifikasi.

---

## 1. Ikhtisar Fitur Kampus

Ekosistem kampus dirancang untuk menjembatani kolaborasi akademik dan pembentukan portofolio profesional mahasiswa selama masa studi:

| Modul | Livewire / Controller | Endpoint Rute | Service Terkait |
|---|---|---|---|
| **Credit Ledger** | `App\Livewire\Campus\CreditLedger` | `/campus/credits` | `CreditLedgerService` |
| **Code Review Bounty** | `App\Livewire\Campus\CodeReviewBounty` | `/campus/code-reviews` | `CodeReviewService` |
| **Study Desk** | `App\Livewire\Campus\StudyDesk` | `/campus/study-desk` | `StudyDeskService` |
| **Course Tagging** | `App\Livewire\Campus\CourseTagging` | `/campus/courses` | Model `Course` |
| **Team Matchmaker** | `App\Livewire\Campus\TeamMatchmaker` | `/projects/{project}/matchmaker` | `TeamMatchmakerService` |
| **Milestone Roadmap** | `App\Livewire\Campus\MilestoneRoadmap` | `/projects/{project}/milestones` | Model `ProjectMilestone` |
| **Student Portfolio** | `App\Livewire\Campus\StudentPortfolio` | `/portfolio/{user}` | `StudentPortfolioService` |
| **Skill Swap Engine** | `App\Http\Controllers\SkillSwapController`| `/skills/swap` | Model `SkillSwapRequest` |

---

## 2. Credit Ledger (Sistem Kredit & Transaksi)

Sistem gamifikasi Swap Hub berbasis token kredit reputasi yang tersimpan di kolom `users.credits` dan diaudit secara rinci di tabel `credit_transactions`.

### 2.1 Mekanisme Transaksi Atomik
Semua mutasi kredit ditangani oleh `App\Services\CreditLedgerService` di dalam `DB::transaction`:
- **`awardCredits(User $user, int $amount, string $reason, ?Model $reference = null)`**: Menambah saldo kredit user dan mencatat transaksi tipe `award`.
- **`spendCredits(User $user, int $amount, string $reason, ?Model $reference = null)`**: Memvalidasi saldo mencukupi, memotong saldo, dan mencatat transaksi tipe `spend`. Melemparkan exception jika saldo kurang.
- **`transferCredits(User $from, User $to, int $amount, string $reason)`**: Transfer kredit P2P antar mahasiswa dengan pencatatan ganda (`transfer_out` dan `transfer_in`).

---

## 3. Code Review Bounty (Peer Review Berbayar Kredit)

Mahasiswa dapat mengajukan permintaan ulasan kode (Pull Request / repositori) dan menawarkan imbalan kredit reputasi bagi rekan yang memberikan ulasan berkualitas.

### 3.1 Siklus Hidup Review Kode
1. **Pengajuan (`createRequest`)**:
   - Pemohon memasukkan judul, repositori/PR URL, deskripsi, dan alokasi `bounty_credits`.
   - Jika bounty > 0, kredit pemohon otomatis ditahan (*escrowed*) lewat `spendCredits`.
   - Status: `open`.
2. **Pengiriman Ulasan (`submitReview`)**:
   - Mahasiswa lain membaca kode dan mengirimkan umpan balik ulasan teknis.
   - Status permintaan bergeser ke: `in_review`.
3. **Penerimaan Ulasan (`acceptReview`)**:
   - Pemohon menerima ulasan yang memuaskan.
   - Bounty kredit dicairkan dan dihadiahkan kepada reviewer via `awardCredits`.
   - Status pengajuan menjadi `completed` dan submission menjadi `accepted`.

---

## 4. Study Desk (Sesi Belajar Virtual Jitsi Meet)

Fasilitas penjadwalan kelompok belajar daring atau asistensi tugas kuliah tanpa dependensi server video conference berbayar.

### 4.1 Fitur Utama
- **Auto-Generated Meeting URL**: Saat membuat sesi baru, URL ruang rapat Jitsi Meet dihasilkan secara otomatis (`https://meet.jit.si/swaphub-{slug}-{hash}`).
- **Peserta & Kalender**: Menghubungkan partisipan terdaftar ke jadwal mulai dan selesai (`starts_at`, `ends_at`).
- **Reward Partisipasi (`completeSession`)**: Saat sesi selesai, host dan peserta yang hadir menerima reward kredit otomatis atas partisipasi aktif belajar bersama.

---

## 5. Course Tagging (Integrasi Mata Kuliah)

Menghubungkan proyek dan pertukaran keahlian dengan mata kuliah riil di kampus (misal: *Pemrograman Web*, *Kecerdasan Buatan*, *Sistem Basis Data*).

### 5.1 Fungsionalitas
- Katalog mata kuliah per jurusan (`department`) dan semester (`semester`).
- Mahasiswa dapat menandai mata kuliah yang sedang atau pernah diambil ke profil mereka.
- Project owner dapat menautkan proyek kolaborasi dengan kode mata kuliah tertentu untuk mempermudah pencarian rekan satu kelas/tugas besar.

---

## 6. Team Matchmaker (Algoritma Pencocokan Anggota)

Membantu pemilik proyek menemukan anggota tim ideal berdasarkan kecocokan skill teknis yang dibutuhkan.

### 6.1 Algoritma Pencocokan (`TeamMatchmakerService`)
- Membandingkan daftar `requiredSkills` proyek dengan `skills` mahasiswa:
  $$\text{Score} = \left( \frac{|\text{Matching Skills}|}{|\text{Project Required Skills}|} \right) \times 100\%$$
- Menghasilkan daftar keahlian yang cocok (`matching_skills`) dan keahlian yang belum terpenuhi dalam tim (`missing_skills`).
- **Rekomendasi Anggota**: Menjalankan query untuk mencari mahasiswa (non-anggota) dengan jumlah kecocokan keahlian tertinggi guna diajak bergabung.

---

## 7. Milestone Roadmap (Manajemen Rencana Proyek)

Visualisasi tahapan dan target waktu penyelesaian proyek tim.

### 7.1 Tampilan & Alur
- **Dual View**: Tersedia mode tampilan **Kanban** (kolom status: *pending*, *in_progress*, *completed*) dan mode **Timeline** terurut tanggal jatuh tempo (`due_date`).
- **Task Association**: Tugas-tugas pada Kanban board (`tasks` table) dapat ditautkan langsung ke milestone tertentu (`tasks.milestone_id`).

---

## 8. Student Portfolio & Validasi Reputasi

Menghasilkan portofolio mahasiswa yang terverifikasi dan dapat dipertanggungjawabkan untuk keperluan magang maupun melamar pekerjaan.

### 8.1 Komponen Portofolio (`/portfolio/{user}`)
1. **Verified Projects**: Riwayat proyek yang kontribusinya telah divalidasi oleh pemilik proyek (`is_validated = true`).
2. **Badges**: Lencana prestasi yang diperoleh user (misal: *Top Reviewer*, *Master Swapper*).
3. **Validated Skills**: Daftar keahlian yang dimiliki beserta reputasi endorsement.
4. **GitHub Activity**: Total kontribusi commit/PR yang tercatat via GitHub Webhook.
5. **Resume PDF Export**: Mahasiswa dapat mengunduh resume terformat secara instan melalui rute `/profile/{user}/resume`.

---

## 9. Skill Swap (Pertukaran Keahlian 1-on-1)

Mekanisme pertukaran keahlian antar dua mahasiswa (misal: Mahasiswa A mengajar Laravel kepada Mahasiswa B, dan sebaliknya Mahasiswa B mengajar UI/UX Design Figma).

### 9.1 Alur Status
- `pending`: Permintaan diajukan oleh pemohon (`requester_id`) ke penyedia keahlian (`provider_id`).
- `accepted`: Tawaran pertukaran disetujui.
- `completed`: Kedua belah pihak menyelesaikan sesi pertukaran ilmu.
- `cancelled`: Permintaan dibatalkan sebelum diterima.
