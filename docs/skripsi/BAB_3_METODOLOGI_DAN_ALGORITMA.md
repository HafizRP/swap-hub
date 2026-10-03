# BAB III: METODOLOGI PENELITIAN DAN PERANCANGAN SISTEM

Dokumen ini berisi rancangan metodologi, permodelan matematis algoritma, dan arsitektur sistem platform **Swap Hub** untuk penyusunan laporan Skripsi / Tugas Akhir program studi Ilmu Komputer / Teknik Informatika / Sistem Informasi.

---

## 3.1 Landasan Algoritma: Jaccard Similarity Coefficient

Untuk mengatasi permasalahan ketidaksesuaian kualifikasi anggota tim pada proyek kolaboratif mahasiswa, Swap Hub menerapkan **Jaccard Similarity Coefficient** yang diperluas dengan **Skill Coverage** dan **Proficiency Multiplier**.

### 3.1.1 Definisi Matematis Jaccard Index
Koefisien Kesamaan Jaccard ($J$) mengukur derajat kesamaan antara himpunan keahlian mahasiswa ($A$) dan himpunan kebutuhan keahlian proyek ($B$):

$$J(A, B) = \frac{|A \cap B|}{|A \cup B|}$$

Di mana:
- $A$: Himpunan ID keahlian yang dimiliki mahasiswa ($A = \{s_1, s_2, \dots, s_n\}$)
- $B$: Himpunan ID keahlian yang disyaratkan oleh proyek ($B = \{s_1, s_3, \dots, s_m\}$)
- $|A \cap B|$: Jumlah keahlian mahasiswa yang cocok dengan kebutuhan proyek (irisan).
- $|A \cup B|$: Total variasi keahlian unik gabungan antara mahasiswa dan proyek (gabungan).
- Rentang nilai: $0.0 \le J(A, B) \le 1.0$.

### 3.1.2 Perhitungan Cakupan Kebutuhan (Skill Coverage / Recall)
Dalam konteks rekrutmen proyek, pemenuhan seluruh syarat proyek lebih diprioritaskan daripada sekadar rasio irisan. Oleh karena itu, dihitung pula metrik **Coverage**:

$$\text{Coverage}(A, B) = \frac{|A \cap B|}{|B|}$$

Jika proyek membutuhkan 4 keahlian ($|B| = 4$) dan mahasiswa menguasai 3 di antaranya ($|A \cap B| = 3$), maka rasio cakupan adalah $0.75$ ($75\%$).

### 3.1.3 Pembobotan Tingkat Kemahiran (Proficiency Weighting)
Tingkat kemahiran mahasiswa pada relasi `skill_user` dikonversi ke dalam bobot numerik terstandarisasi ($w_p$):

| Tingkat Kemahiran (`proficiency_level`) | Bobot Numerik ($w_p$) |
|----------------------------------------|-----------------------|
| **Expert** (Ahli)                      | $1.00$                |
| **Advanced** (Tingkat Lanjut)          | $0.85$                |
| **Intermediate** (Menengah)            | $0.70$                |
| **Beginner** (Pemula)                  | $0.50$                |

Rata-rata bobot kemahiran untuk keahlian yang cocok ($k \in A \cap B$):

$$\bar{W}_p = \frac{1}{|A \cap B|} \sum_{k \in A \cap B} w_p(k)$$

### 3.1.4 Skor Komposit Keselarasan (Composite Match Score)
Skor akhir kecocokan dihitung secara proporsional dengan formula linier berbobot:

$$S_{\text{match}} = \left( 0.40 \times J(A, B) + 0.40 \times \text{Coverage}(A, B) + 0.20 \times (\text{Coverage}(A, B) \times \bar{W}_p) \right) \times 100\%$$

### 3.1.5 Kategori Rekomendasi Sistem
| Persentase Skor ($S_{\text{match}}$) | Label Rekomendasi | Interpretasi Sistem |
|-------------------------------------|-------------------|---------------------|
| $75\% \le S \le 100\%$              | **Sangat Cocok**  | Mahasiswa sangat direkomendasikan melamar; memenuhi mayoritas kualifikasi inti. |
| $50\% \le S < 75\%$                 | **Cocok**         | Mahasiswa memenuhi syarat dasar dan memiliki sebagian besar skill utama. |
| $25\% \le S < 50\%$                 | **Cukup Cocok**   | Terdapat potensi kolaborasi, namun memerlukan transfer pengetahuan antar anggota. |
| $0\% \le S < 25\%$                  | **Belum Cocok**   | Profil keahlian tidak bersinggungan dengan fokus teknis proyek. |

---

## 3.2 Metodologi Pengujian Kegunaan: System Usability Scale (SUS)

Pengujian penerimaan sistem menggunakan metode kuesioner **System Usability Scale (SUS)** yang dikembangkan oleh John Brooke (1996). 

### 3.2.1 Butir Pertanyaan Standar SUS (Skala Likert 1 - 5)
1. **Q1:** Saya rasa saya akan sering menggunakan Swap Hub. *(Positif)*
2. **Q2:** Saya merasa sistem ini terlalu rumit untuk digunakan. *(Negatif)*
3. **Q3:** Saya merasa sistem ini mudah digunakan. *(Positif)*
4. **Q4:** Saya membutuhkan bantuan orang teknis untuk bisa menggunakan sistem ini. *(Negatif)*
5. **Q5:** Saya merasa berbagai fungsi dalam sistem ini terintegrasi dengan baik. *(Positif)*
6. **Q6:** Saya merasa banyak hal yang tidak konsisten pada sistem ini. *(Negatif)*
7. **Q7:** Saya rasa kebanyakan orang akan cepat belajar menggunakan sistem ini. *(Positif)*
8. **Q8:** Saya merasa sistem ini membingungkan saat digunakan. *(Negatif)*
9. **Q9:** Saya merasa sangat percaya diri saat menggunakan sistem ini. *(Positif)*
10. **Q10:** Saya perlu membiasakan diri terlebih dahulu sebelum bisa menggunakan sistem ini. *(Negatif)*

### 3.2.2 Rumus Perhitungan Skor SUS
Untuk responden dengan jawaban $x_1, x_2, \dots, x_{10}$:

$$\text{Skor Positif (Ganjil)} = \sum_{i \in \{1, 3, 5, 7, 9\}} (x_i - 1)$$

$$\text{Skor Negatif (Genap)} = \sum_{i \in \{2, 4, 6, 8, 10\}} (5 - x_i)$$

$$\text{Skor Akhir SUS} = (\text{Skor Positif} + \text{Skor Negatif}) \times 2.5$$

Rentang skor: $0 \le \text{SUS} \le 100$. Berdasarkan standar Sauro & Lewis (2016), ambang batas rata-rata industri adalah **68.00**.

---

## 3.3 Arsitektur dan Alur Data Sistem

```text
┌─────────────────────────────────────────────────────────────┐
│                       CLIENT LAYER                          │
│   PWA (Service Worker + Cache) / Desktop Browser / Mobile    │
│   Reactivity: Livewire 3 + Alpine.js + CSS View Transitions │
└───────────────┬─────────────────────────────▲───────────────┘
                │ HTTP Requests (Vite/SPA)    │ WebSocket (/app)
                ▼                             │
┌─────────────────────────────────────────────┴───────────────┐
│                     APPLICATION LAYER                       │
│  Nginx Reverse Proxy (:5541)                                │
│    ├── Laravel 12 HTTP / REST Controllers                   │
│    ├── SkillMatchingService (Jaccard Similarity Engine)     │
│    ├── GitHubWebhookService (HMAC-SHA256 Validator)         │
│    └── Laravel Reverb WebSocket Server (:8080)              │
└───────────────┬─────────────────────────────▲───────────────┘
                │ SQL / Query Builder         │ Pub/Sub & Queues
                ▼                             ▼
┌───────────────────────────────┐  ┌──────────────────────────┐
│        DATA STORAGE           │  │     MESSAGE BROKER       │
│  MariaDB 10.11                │  │  Redis 7 (Alpine)        │
│  - projects & project_skill   │  │  - Cache Session         │
│  - skills & skill_user        │  │  - Broadcasting Bus     │
│  - usability_feedbacks (SUS)  │  │  - Asynchronous Queues  │
└───────────────────────────────┘  └──────────────────────────┘
```
