@extends('layouts.guest')

@section('title', 'Login')

@section('content')
    <div class="text-center mb-5">
        <h3 class="fw-black text-white display-6">Welcome Back!</h3>
        <p class="text-secondary fw-medium opacity-75">Enter your credentials to access your workspace.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="mb-4">
            <label for="email" class="form-label small fw-bold text-secondary text-uppercase tracking-wider ms-1">Email
                Address</label>
            <div class="input-group">
                <span class="input-group-text bg-transparent border-end-0 text-secondary"
                    style="border-color: rgba(255,255,255,0.1); border-radius: 1rem 0 0 1rem; padding-left: 1.2rem;">
                    <svg style="width: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207">
                        </path>
                    </svg>
                </span>
                <input id="email" class="form-control border-start-0 ps-0" style="border-radius: 0 1rem 1rem 0;"
                    type="email" name="email" :value="old('email')" required autofocus autocomplete="username"
                    placeholder="name@university.edu" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-danger small ms-1" />
        </div>

        <!-- Password -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <label for="password"
                    class="form-label small fw-bold text-secondary text-uppercase tracking-wider ms-1 mb-0">Password</label>
                @if (Route::has('password.request'))
                    <a class="small text-primary text-decoration-none fw-bold hover-opacity"
                        href="{{ route('password.request') }}">
                        Forgot?
                    </a>
                @endif
            </div>

            <div class="input-group">
                <span class="input-group-text bg-transparent border-end-0 text-secondary"
                    style="border-color: rgba(255,255,255,0.1); border-radius: 1rem 0 0 1rem; padding-left: 1.2rem;">
                    <svg style="width: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                        </path>
                    </svg>
                </span>
                <input id="password" class="form-control border-start-0 ps-0" style="border-radius: 0 1rem 1rem 0;"
                    type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-danger small ms-1" />
        </div>

        <!-- Remember Me -->
        <div class="form-check mb-4 ms-1">
            <input id="remember_me" type="checkbox" class="form-check-input bg-transparent border-secondary" name="remember"
                style="cursor: pointer;">
            <label for="remember_me" class="form-check-label small text-secondary fw-medium user-select-none"
                style="cursor: pointer;">Keep me signed in on this device</label>
        </div>

        <div class="d-grid gap-3">
            <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill fw-black shadow-lg">
                Sign In
            </button>

            <!-- Social Login Separator -->
            <div class="d-flex align-items-center my-3">
                <hr class="flex-grow-1 border-white border-opacity-10">
                <span class="px-3 small text-secondary fw-bold text-uppercase opacity-50" style="font-size: 10px;">Or
                    continue with</span>
                <hr class="flex-grow-1 border-white border-opacity-10">
            </div>

            <a href="{{ route('auth.github') }}"
                class="btn btn-dark w-100 py-3 rounded-pill fw-bold d-flex align-items-center justify-content-center gap-2 border border-white border-opacity-10 hover-scale transition"
                style="background: rgba(0,0,0,0.3);">
                <svg style="width: 20px;" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M12 .297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61C4.422 18.07 3.633 17.7 3.633 17.7c-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 22.092 24 17.592 24 12.297c0-6.627-5.373-12-12-12" />
                </svg>
                GitHub
            </a>

            <div class="text-center mt-4 pt-4 border-top border-white border-opacity-5">
                <p class="small text-secondary mb-2">Don't have an account yet?</p>
                <a href="{{ route('register') }}"
                    class="btn btn-outline-light btn-sm rounded-pill px-4 fw-bold tracking-wide transition hover-fill">
                    Create Account
                </a>
            </div>
        </div>
    </form>

    <style>
        .input-group-text {
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            border-right: none !important;
        }

        .input-group:focus-within .input-group-text {
            border-color: var(--primary) !important;
            border-right: none !important;
        }

        .input-group:focus-within {
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.2);
            border-radius: 1rem;
        }

        /* Override form-control box-shadow inside input-group */
        .input-group .form-control:focus {
            box-shadow: none !important;
            border-color: var(--primary) !important;
        }

        .hover-scale:hover {
            transform: translateY(-2px);
        }

        .hover-opacity:hover {
            opacity: 0.8;
        }

        .hover-fill:hover {
            background-color: white;
            color: black;
            border-color: white;
        }
    </style>
@endsection