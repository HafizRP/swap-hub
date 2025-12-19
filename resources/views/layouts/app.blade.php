<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark" class="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@hasSection('title') @yield('title') - @endif{{ config('app.name', 'Swap Hub') }}</title>

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
            color: #e2e8f0;
        }

        .navbar {
            background-color: rgba(11, 17, 32, 0.8) !important;
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .card {
            background-color: #1e293b;
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 1.5rem;
        }

        .btn-primary {
            background-color: #4f46e5;
            border: none;
            border-radius: 0.75rem;
            padding: 0.6rem 1.5rem;
            font-weight: 700;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            background-color: #4338ca;
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.4);
        }
    </style>

    <!-- Scripts -->
    @vite(['resources/js/app.js'])
</head>

<body class="antialiased">
    <div class="min-vh-100">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            <header class="py-4 border-bottom border-white-5">
                <div class="container">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main class="py-5">
            <div class="container">
                {{ $slot }}
            </div>
        </main>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>