<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Swap Hub') }} | Build Your Student Portfolio</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background-color: #0b1120;
        }

        .glass {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .gradient-text {
            background: linear-gradient(to right, #6366f1, #a855f7, #ec4899);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-glow {
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100%;
            height: 600px;
            background: radial-gradient(circle at 50% 0%, rgba(99, 102, 241, 0.15) 0%, transparent 70%);
            pointer-events: none;
        }

        .nav-container {
            background-color: rgba(11, 17, 32, 0.5);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .feature-card {
            transition: all 0.3s;
            border-radius: 2rem;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .feature-card:hover {
            background: rgba(255, 255, 255, 0.05);
            transform: translateY(-10px);
        }

        .btn-cta {
            padding: 1.25rem 2.5rem;
            border-radius: 1.25rem;
            font-weight: 900;
            transition: all 0.3s;
        }

        .btn-cta-primary {
            background-color: #4f46e5;
            border: none;
            box-shadow: 0 20px 40px -10px rgba(79, 70, 229, 0.4);
        }

        .btn-cta-primary:hover {
            background-color: #4338ca;
            transform: translateY(-3px);
            box-shadow: 0 30px 50px -10px rgba(79, 70, 229, 0.5);
        }
    </style>
</head>

<body class="antialiased text-light">
    <div class="hero-glow"></div>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top nav-container py-3">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="/">
                <div class="bg-primary rounded-3 p-1 d-flex align-items-center justify-content-center shadow-lg shadow-primary-500/20"
                    style="width: 40px; height: 40px;">
                    <svg class="text-white" style="width: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <span class="fw-black tracking-tighter">SWAP HUB</span>
            </a>

            <div class="ms-auto d-flex align-items-center gap-4">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}"
                            class="btn btn-link text-decoration-none text-light fw-bold">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-link text-decoration-none text-light fw-bold">Login</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-light rounded-pill px-4 fw-black">Get Started</a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </nav>

    <main>
        <!-- Hero Section -->
        <section class="container text-center pt-5 mt-5">
            <div class="row justify-content-center pt-5">
                <div class="col-lg-10 pt-5">
                    <div class="d-inline-flex align-items-center gap-2 px-3 py-2 rounded-pill glass mb-4">
                        <span class="badge rounded-circle bg-success p-1 animate-pulse"
                            style="width: 8px; height: 8px;"></span>
                        <span class="small fw-black text-uppercase tracking-widest opacity-50">Exclusive for University
                            Students</span>
                    </div>
                    <h1 class="display-1 fw-black text-white mb-4">
                        The End of <span class="gradient-text">Free-Riders.</span><br>
                        The Rise of Talent.
                    </h1>
                    <p class="lead text-secondary mx-auto mb-5 px-lg-5 fs-4">
                        Bridges the gap between theory and practice through skill bartering and transparent contribution
                        validation.
                    </p>
                    <div class="d-flex flex-column flex-sm-row justify-content-center gap-3">
                        <a href="{{ route('register') }}" class="btn btn-primary btn-cta btn-cta-primary">
                            Build Your Portfolio
                        </a>
                        <a href="#features" class="btn glass btn-cta text-white">
                            Explore Ecosystem
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Grid -->
        <section id="features" class="container py-5 mt-5">
            <div class="row g-4 mt-5">
                <!-- Group Chat -->
                <div class="col-md-6 col-lg-4">
                    <div class="p-5 h-100 feature-card glass">
                        <div class="bg-primary bg-opacity-10 rounded-4 p-3 d-inline-block mb-4">
                            <svg class="text-primary" style="width: 24px;" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="h4 fw-black text-white mb-3">Instant Squads</h3>
                        <p class="small text-secondary fw-medium lh-lg">Form teams based on real verified skills.
                            Centralized project coordination in one hub.</p>
                    </div>
                </div>



                <!-- GitHub Integrations -->
                <div class="col-md-6 col-lg-4">
                    <div class="p-5 h-100 feature-card glass">
                        <div class="bg-info bg-opacity-10 rounded-4 p-3 d-inline-block mb-4">
                            <svg class="text-info" style="width: 24px;" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 .297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61C4.422 18.07 3.633 17.7 3.633 17.7c-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 22.092 24 17.592 24 12.297c0-6.627-5.373-12-12-12" />
                            </svg>
                        </div>
                        <h3 class="h4 fw-black text-white mb-3">GitHub Pulse</h3>
                        <p class="small text-secondary fw-medium lh-lg">Real-time contribution monitoring that
                            eliminates free-riders through transparency.</p>
                    </div>
                </div>

                <!-- Reputation -->
                <div class="col-md-6 col-lg-4">
                    <div class="p-5 h-100 feature-card glass">
                        <div class="bg-success bg-opacity-10 rounded-4 p-3 d-inline-block mb-4">
                            <svg class="text-success" style="width: 24px;" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="h4 fw-black text-white mb-3">Proof of Work</h3>
                        <p class="small text-secondary fw-medium lh-lg">Objective validation from peers automatically
                            recorded as a credible portfolio.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Final CTA -->
        <section class="container py-5 mt-5 text-center px-4">
            <div class="glass rounded-5 p-5 p-lg-5 overflow-hidden position-relative">
                <div class="position-relative z-1 py-5">
                    <h2 class="display-4 fw-black text-white mb-4">Ready to sync with the best?</h2>
                    <p class="lead text-secondary mb-5">Join 500+ students from top universities building the future
                        together.</p>
                    <a href="{{ route('register') }}" class="btn btn-light rounded-4 px-5 py-3 fw-black fs-5">Get My
                        Free Account</a>
                </div>
                <div class="position-absolute bottom-0 start-50 translate-middle-x bg-primary rounded-circle opacity-10 blur-5"
                    style="width: 500px; height: 500px; filter: blur(100px); transform: translate(-50%, 50%) !important;">
                </div>
            </div>
        </section>
    </main>

    <footer class="container py-5 mt-5 border-top border-white-5">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-4 text-secondary">
            <p class="small fw-black text-uppercase tracking-widest mb-0">© 2025 Swap Hub Collaborative Ecosystem</p>
            <div class="d-flex gap-4">
                <a href="#"
                    class="small fw-black text-uppercase text-decoration-none text-secondary hover-white tracking-widest">Privacy</a>
                <a href="#"
                    class="small fw-black text-uppercase text-decoration-none text-secondary hover-white tracking-widest">Twitter</a>
                <a href="#"
                    class="small fw-black text-uppercase text-decoration-none text-secondary hover-white tracking-widest">GitHub</a>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>