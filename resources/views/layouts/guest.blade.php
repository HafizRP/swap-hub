<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark" class="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@hasSection('title') @yield('title') - @endif{{ config('app.name', 'Swap Hub') }}</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&family=Plus+Jakarta+Sans:wght@200..800&display=swap"
        rel="stylesheet">

    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --dark-bg: #0a0b1e;
            --card-bg: rgba(255, 255, 255, 0.03);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--dark-bg);
            color: #e2e8f0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow-x: hidden;
        }

        .orbital-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            background: radial-gradient(circle at 10% 20%, rgba(99, 102, 241, 0.15) 0%, transparent 40%),
                radial-gradient(circle at 90% 80%, rgba(16, 185, 129, 0.1) 0%, transparent 40%);
        }

        .guest-card {
            background: var(--card-bg);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 2rem;
            width: 100%;
            max-width: 450px;
            padding: 3rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border: none;
            padding: 0.8rem 2rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-radius: 100px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -5px rgba(99, 102, 241, 0.5);
        }

        .form-control {
            background-color: rgba(255, 255, 255, 0.05) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            color: white !important;
            border-radius: 1rem;
            padding: 0.8rem 1.2rem;
        }

        .form-control:focus {
            background-color: rgba(255, 255, 255, 0.08) !important;
            border-color: var(--primary) !important;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.2) !important;
        }

        .logo-container {
            margin-bottom: 2.5rem;
            text-align: center;
        }

        .fw-black {
            font-weight: 900 !important;
        }
    </style>
</head>

<body class="antialiased">
    <div class="orbital-bg"></div>

    <div class="container d-flex justify-content-center">
        <div class="guest-card">
            <div class="logo-container">
                <a href="/" class="text-decoration-none">
                    <div class="d-inline-flex align-items-center gap-2">
                        <div class="bg-primary bg-opacity-25 p-2 rounded-4"
                            style="border: 1px solid rgba(99, 102, 241, 0.3);">
                            <svg style="width: 40px;" class="text-primary" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                    </div>
                    <h2 class="h4 fw-black text-white mt-3 mb-0">Swap Hub</h2>
                </a>
            </div>

            {{ $slot }}
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>