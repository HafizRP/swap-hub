<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Swap Hub') }} | Kolaborasi Mahasiswa #1</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --primary-color: #0d6efd;
            --primary-dark: #0a58ca;
            --text-main: #1e293b;
            --text-secondary: #64748b;
            --bg-light: #f8fafc;
        }

        body {
            font-family: 'Inter', sans-serif;
            color: var(--text-main);
            background-color: #ffffff;
            -webkit-font-smoothing: antialiased;
        }

        .navbar-brand {
            font-weight: 800;
            font-size: 1.5rem;
            color: var(--text-main);
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            border-radius: 8px;
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
        }

        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            border-radius: 8px;
        }

        .btn-light {
            background-color: #f1f5f9;
            border: 1px solid #e2e8f0;
            color: var(--text-main);
            font-weight: 600;
        }

        .hero-section {
            padding: 5rem 0;
            overflow: hidden;
        }

        .hero-title {
            font-weight: 800;
            font-size: 3.5rem;
            line-height: 1.1;
            letter-spacing: -0.02em;
            color: #0f172a;
        }

        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }
        }

        .hero-image-container {
            position: relative;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.15);
        }

        .feature-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 2rem;
            transition: all 0.3s ease;
            height: 100%;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.05);
            border-color: var(--primary-color);
        }

        .icon-square {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            background-color: #eff6ff;
            color: var(--primary-color);
            margin-bottom: 1.5rem;
        }

        .step-circle {
            width: 40px;
            height: 40px;
            background-color: var(--primary-color);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            margin-right: 1rem;
            flex-shrink: 0;
        }

        .cta-box {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            border-radius: 24px;
            padding: 5rem 2rem;
            color: white;
            text-align: center;
        }

        .footer-link {
            color: var(--text-secondary);
            text-decoration: none;
            margin-bottom: 0.5rem;
            display: block;
            font-size: 0.9rem;
        }

        .footer-link:hover {
            color: var(--primary-color);
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top bg-white border-bottom border-light">
        <div class="container py-2">
            <a class="navbar-brand d-flex align-items-center gap-2" href="#">
                <i class="bi bi-grid-1x2-fill text-primary"></i>
                Swap Hub
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <!-- Links could go here -->
                </ul>
                <div class="d-flex gap-2">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn btn-outline-primary">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-light px-4">Masuk</a>
                        <a href="{{ route('register') }}" class="btn btn-primary px-4">Daftar</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section mt-5 pt-5 bg-white">
        <div class="container pt-5">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <span class="badge bg-primary bg-opacity-10 text-primary mb-3 px-3 py-2 rounded-pill fw-semibold">
                        <i class="bi bi-stars me-1"></i> PLATFORM KOLABORASI MAHASISWA #1
                    </span>
                    <h1 class="hero-title mb-4">
                        Temukan partner proyek.<br>
                        <span class="text-primary">Bangun portofolio</span><br>
                        sejak kuliah.
                    </h1>
                    <p class="lead text-secondary mb-5" style="font-weight: 400; font-size: 1.15rem;">
                        Platform kolaborasi mahasiswa untuk pengalaman nyata. Hubungkan keahlianmu dengan rekan tim yang
                        tepat dan validasi skillmu sekarang.
                    </p>
                    <div class="d-flex flex-wrap gap-3 mb-5">
                        <a href="{{ route('register') }}" class="btn btn-primary btn-lg px-4 fs-6">Mulai Sekarang</a>
                        <a href="#how-it-works" class="btn btn-light btn-lg px-4 fs-6">Pelajari Lebih Lanjut</a>
                    </div>

                    <div class="d-flex align-items-center gap-3">
                        <div class="d-flex ms-2">
                            <img src="https://ui-avatars.com/api/?name=Alex&background=random"
                                class="rounded-circle border border-2 border-white" width="36"
                                style="margin-left: -10px;">
                            <img src="https://ui-avatars.com/api/?name=Sarah&background=random"
                                class="rounded-circle border border-2 border-white" width="36"
                                style="margin-left: -10px;">
                            <img src="https://ui-avatars.com/api/?name=John&background=random"
                                class="rounded-circle border border-2 border-white" width="36"
                                style="margin-left: -10px;">
                            <div class="rounded-circle bg-light border border-2 border-white d-flex align-items-center justify-content-center text-secondary small fw-bold"
                                style="width: 36px; height: 36px; margin-left: -10px;">+2k</div>
                        </div>
                        <span class="text-secondary small fw-medium">Bergabung dengan 2,000+ mahasiswa</span>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="hero-image-container">
                        <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-1.2.1&auto=format&fit=crop&w=1351&q=80"
                            class="img-fluid w-100 object-fit-cover" style="height: 500px;" alt="Student Collaboration">
                        <div
                            class="position-absolute bottom-0 start-0 p-4 w-100 bg-dark bg-opacity-50 backdrop-blur-md">
                            <div class="text-white">
                                <h5 class="fw-bold mb-1">Kolaborasi Tanpa Batas</h5>
                                <p class="small mb-0 opacity-75">Bangun tim impianmu dari berbagai universitas.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5 bg-white">
        <div class="container py-5">
            <div class="text-center mb-5">
                <h2 class="fw-bold display-6 mb-3">Kenapa Swap Hub?</h2>
                <p class="text-secondary mx-auto" style="max-width: 600px;">
                    Kami menyediakan ekosistem lengkap untuk membantumu berkembang dari mahasiswa menjadi profesional
                    siap kerja.
                </p>
            </div>

            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="feature-card">
                        <div class="icon-square">
                            <i class="bi bi-person-plus-fill fs-4"></i>
                        </div>
                        <h4 class="h5 fw-bold mb-3">Project Matchmaking</h4>
                        <p class="text-secondary small mb-0">Temukan rekan tim dengan skill yang saling melengkapi untuk
                            proyek akademis maupun kompetisi.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="feature-card">
                        <div class="icon-square">
                            <i class="bi bi-person-vcard-fill fs-4"></i>
                        </div>
                        <h4 class="h5 fw-bold mb-3">Live Resume</h4>
                        <p class="text-secondary small mb-0">Portofolio terupdate otomatis dari setiap proyek yang kamu
                            selesaikan di platform.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="feature-card">
                        <div class="icon-square">
                            <i class="bi bi-chat-dots-fill fs-4"></i>
                        </div>
                        <h4 class="h5 fw-bold mb-3">Kolaborasi & Chat</h4>
                        <p class="text-secondary small mb-0">Alat komunikasi terintegrasi dengan manajemen tugas untuk
                            kerja tim yang efisien.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="feature-card">
                        <div class="icon-square">
                            <i class="bi bi-patch-check-fill fs-4"></i>
                        </div>
                        <h4 class="h5 fw-bold mb-3">Review & Validasi</h4>
                        <p class="text-secondary small mb-0">Dapatkan validasi skill nyata dari review rekan setim,
                            mentor, dan dosen pembimbing.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section id="how-it-works" class="py-5 bg-light">
        <div class="container py-5">
            <h2 class="fw-bold display-6 mb-5">Cara Kerja Swap Hub</h2>

            <div class="row align-items-center g-5">
                <div class="col-lg-5">
                    <div class="d-flex mb-4">
                        <div class="step-circle">1</div>
                        <div>
                            <h5 class="fw-bold">Buat Profil</h5>
                            <p class="text-secondary small">Lengkapi profilmu dengan skill, minat, dan pengalaman
                                akademis untuk menarik partner yang tepat.</p>
                        </div>
                    </div>
                    <div class="d-flex mb-4">
                        <div class="step-circle bg-white text-primary border">2</div>
                        <div>
                            <h5 class="fw-bold">Gabung / Buat Proyek</h5>
                            <p class="text-secondary small">Cari proyek yang sesuai minatmu atau inisiasi ide barumu dan
                                rekrut anggota tim.</p>
                        </div>
                    </div>
                    <div class="d-flex mb-4">
                        <div class="step-circle bg-white text-primary border">3</div>
                        <div>
                            <h5 class="fw-bold">Bangun Portofolio Otomatis</h5>
                            <p class="text-secondary small">Setiap proyek yang terselesaikan otomatis tercatat di
                                profilmu sebagai bukti pengalaman nyata.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <!-- Mockup UI -->
                    <div class="bg-white p-4 rounded-4 shadow-sm border">
                        <div class="d-flex align-items-center gap-3 mb-4 border-bottom pb-4">
                            <img src="https://ui-avatars.com/api/?name=Rina+S&background=ffd700&color=fff"
                                class="rounded-circle" width="56">
                            <div>
                                <h6 class="fw-bold mb-0">Rina S.</h6>
                                <span class="badge bg-primary bg-opacity-10 text-primary small">UI/UX Designer</span>
                            </div>
                        </div>
                        <h6 class="text-uppercase small text-secondary fw-bold mb-3">Project History</h6>
                        <div class="d-flex flex-column gap-2">
                            <div class="bg-light p-3 rounded-3 d-flex align-items-center gap-3">
                                <i class="bi bi-check-circle-fill text-success"></i>
                                <span class="fw-medium small">E-Commerce App Redesign</span>
                            </div>
                            <div class="bg-light p-3 rounded-3 d-flex align-items-center gap-3">
                                <i class="bi bi-check-circle-fill text-success"></i>
                                <span class="fw-medium small">Smart City IoT System</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-5 bg-white">
        <div class="container py-5">
            <div class="cta-box position-relative overflow-hidden">
                <div class="position-relative z-1">
                    <h2 class="display-5 fw-bold mb-3">Siap Membangun Masa Depanmu?</h2>
                    <p class="mb-5 text-white-50 fs-5">Bergabunglah dengan ribuan mahasiswa lainnya dan mulai bangun
                        portofolio profesionalmu hari ini.</p>
                    <div class="d-flex justify-content-center gap-3">
                        <a href="{{ route('register') }}" class="btn btn-light px-4 py-2 fw-bold">Daftar Gratis</a>
                        <a href="{{ route('projects.index') }}" class="btn btn-outline-light px-4 py-2 fw-bold">Lihat
                            Proyek</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-5 bg-white border-top">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-4">
                    <a class="d-flex align-items-center gap-2 mb-3 text-decoration-none text-dark" href="#">
                        <i class="bi bi-grid-1x2-fill text-primary"></i>
                        <span class="fw-bold fs-5">Swap Hub</span>
                    </a>
                    <p class="text-secondary small">Platform kolaborasi #1 untuk mahasiswa Indonesia. Bangun karirmu
                        dari sekarang.</p>
                    <div class="d-flex gap-3 mt-4">
                        <a href="#" class="text-secondary"><i class="bi bi-twitter"></i></a>
                        <a href="#" class="text-secondary"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="text-secondary"><i class="bi bi-linkedin"></i></a>
                    </div>
                </div>
                <div class="col-lg-2">
                    <h6 class="fw-bold mb-3">Platform</h6>
                    <a href="#" class="footer-link">Cara Kerja</a>
                    <a href="#" class="footer-link">Cari Proyek</a>
                    <a href="#" class="footer-link">Cari Talent</a>
                    <a href="#" class="footer-link">Harga</a>
                </div>
                <div class="col-lg-2">
                    <h6 class="fw-bold mb-3">Perusahaan</h6>
                    <a href="#" class="footer-link">Tentang Kami</a>
                    <a href="#" class="footer-link">Karir</a>
                    <a href="#" class="footer-link">Blog</a>
                    <a href="#" class="footer-link">Kontak</a>
                </div>
                <div class="col-lg-2">
                    <h6 class="fw-bold mb-3">Legal</h6>
                    <a href="#" class="footer-link">Kebijakan Privasi</a>
                    <a href="#" class="footer-link">Syarat & Ketentuan</a>
                    <a href="#" class="footer-link">Panduan Komunitas</a>
                </div>
            </div>
            <div class="border-top mt-5 pt-4 d-flex justify-content-between text-secondary small">
                <p>&copy; 2024 Swap Hub. All rights reserved.</p>
                <div class="d-flex gap-3">
                    <a href="#" class="text-decoration-none text-secondary">English</a>
                    <a href="#" class="text-decoration-none text-secondary fw-bold">Indonesia</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>