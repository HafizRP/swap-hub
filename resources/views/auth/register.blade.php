@extends('layouts.guest')

@section('title', 'Register')

@section('content')
    <div class="text-center mb-5">
        <div class="mb-3">
            <span class="badge rounded-pill px-3 py-2"
                style="background: rgba(16, 185, 129, 0.1); color: #10b981; font-size: 11px; font-weight: 700; letter-spacing: 0.5px;">
                CREATE ACCOUNT
            </span>
        </div>
        <h3 class="fw-black text-white display-6 mb-2">Join Swap Hub</h3>
        <p class="text-secondary fw-medium opacity-75 mb-0">Start collaborating with talented students today.</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="mb-4">
            <label for="name" class="form-label small fw-bold text-secondary text-uppercase tracking-wider ms-1 mb-2">Full
                Name</label>
            <div class="input-group input-group-enhanced">
                <span class="input-group-text bg-transparent border-end-0 text-secondary"
                    style="border-color: rgba(255,255,255,0.1); border-radius: 1rem 0 0 1rem; padding-left: 1.2rem;">
                    <svg style="width: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </span>
                <input id="name" class="form-control border-start-0 ps-0" style="border-radius: 0 1rem 1rem 0;" type="text"
                    name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="John Doe" />
            </div>
            <x-input-error :messages="$errors->get('name')" class="mt-2 text-danger small ms-1" />
        </div>

        <!-- Email Address -->
        <div class="mb-4">
            <label for="email" class="form-label small fw-bold text-secondary text-uppercase tracking-wider ms-1 mb-2">Email
                Address</label>
            <div class="input-group input-group-enhanced">
                <span class="input-group-text bg-transparent border-end-0 text-secondary"
                    style="border-color: rgba(255,255,255,0.1); border-radius: 1rem 0 0 1rem; padding-left: 1.2rem;">
                    <svg style="width: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207">
                        </path>
                    </svg>
                </span>
                <input id="email" class="form-control border-start-0 ps-0" style="border-radius: 0 1rem 1rem 0;"
                    type="email" name="email" :value="old('email')" required autocomplete="username"
                    placeholder="name@university.edu" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-danger small ms-1" />
        </div>

        <!-- Password -->
        <div class="mb-4">
            <label for="password"
                class="form-label small fw-bold text-secondary text-uppercase tracking-wider ms-1 mb-2">Password</label>
            <div class="input-group input-group-enhanced">
                <span class="input-group-text bg-transparent border-end-0 text-secondary"
                    style="border-color: rgba(255,255,255,0.1); border-radius: 1rem 0 0 1rem; padding-left: 1.2rem;">
                    <svg style="width: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                        </path>
                    </svg>
                </span>
                <input id="password" class="form-control border-start-0 border-end-0 ps-0" style="border-radius: 0;"
                    type="password" name="password" required autocomplete="new-password" placeholder="••••••••" />
                <button type="button" class="input-group-text bg-transparent border-start-0 text-secondary toggle-password"
                    data-target="password"
                    style="border-color: rgba(255,255,255,0.1); border-radius: 0 1rem 1rem 0; padding-right: 1.2rem; cursor: pointer;"
                    tabindex="-1">
                    <svg class="eye-open" style="width: 20px; display: none;" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                        </path>
                    </svg>
                    <svg class="eye-closed" style="width: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21">
                        </path>
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-danger small ms-1" />
        </div>

        <!-- Confirm Password -->
        <div class="mb-5">
            <label for="password_confirmation"
                class="form-label small fw-bold text-secondary text-uppercase tracking-wider ms-1 mb-2">Confirm
                Password</label>
            <div class="input-group input-group-enhanced">
                <span class="input-group-text bg-transparent border-end-0 text-secondary"
                    style="border-color: rgba(255,255,255,0.1); border-radius: 1rem 0 0 1rem; padding-left: 1.2rem;">
                    <svg style="width: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </span>
                <input id="password_confirmation" class="form-control border-start-0 border-end-0 ps-0"
                    style="border-radius: 0;" type="password" name="password_confirmation" required
                    autocomplete="new-password" placeholder="••••••••" />
                <button type="button" class="input-group-text bg-transparent border-start-0 text-secondary toggle-password"
                    data-target="password_confirmation"
                    style="border-color: rgba(255,255,255,0.1); border-radius: 0 1rem 1rem 0; padding-right: 1.2rem; cursor: pointer;"
                    tabindex="-1">
                    <svg class="eye-open" style="width: 20px; display: none;" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                        </path>
                    </svg>
                    <svg class="eye-closed" style="width: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21">
                        </path>
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-danger small ms-1" />
        </div>

        <div class="d-grid gap-3">
            <button type="submit"
                class="btn btn-primary w-100 py-3 rounded-pill fw-black shadow-lg position-relative overflow-hidden">
                <span class="position-relative z-1">Create Account</span>
                <div class="btn-shimmer"></div>
            </button>

            <!-- Social Signup Separator -->
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
                <span>GitHub</span>
            </a>

            <div class="text-center mt-4 pt-4 border-top border-white border-opacity-5">
                <p class="small text-secondary mb-3 opacity-75">Already have an account?</p>
                <a href="{{ route('login') }}"
                    class="btn btn-outline-light btn-sm rounded-pill px-4 py-2 fw-bold tracking-wide transition hover-fill">
                    Sign In
                </a>
            </div>
        </div>
    </form>

    <style>
        .input-group-text {
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            transition: all 0.3s ease;
        }

        .input-group-enhanced:focus-within .input-group-text {
            border-color: var(--primary) !important;
        }

        .input-group-enhanced:focus-within {
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.15);
            border-radius: 1rem;
        }

        .input-group-enhanced .form-control:focus {
            box-shadow: none !important;
            border-color: var(--primary) !important;
        }

        .hover-scale {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .hover-scale:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
        }

        .hover-fill {
            transition: all 0.3s ease;
        }

        .hover-fill:hover {
            background-color: white;
            color: #0a0b1e;
            border-color: white;
            transform: translateY(-1px);
        }

        .toggle-password {
            transition: opacity 0.2s;
        }

        .toggle-password:hover {
            opacity: 0.7;
        }

        .btn-shimmer::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .btn-primary:hover .btn-shimmer::before {
            left: 100%;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggleBtns = document.querySelectorAll('.toggle-password');

            toggleBtns.forEach(btn => {
                btn.addEventListener('click', function (e) {
                    e.preventDefault();
                    const targetId = this.getAttribute('data-target');
                    const passwordInput = document.getElementById(targetId);
                    const eyeOpen = this.querySelector('.eye-open');
                    const eyeClosed = this.querySelector('.eye-closed');

                    const type = passwordInput.type === 'password' ? 'text' : 'password';
                    passwordInput.type = type;

                    if (type === 'text') {
                        eyeOpen.style.display = 'block';
                        eyeClosed.style.display = 'none';
                    } else {
                        eyeOpen.style.display = 'none';
                        eyeClosed.style.display = 'block';
                    }
                });
            });

            // Add input animation
            const inputs = document.querySelectorAll('.form-control');
            inputs.forEach(input => {
                input.addEventListener('focus', function () {
                    this.parentElement.classList.add('input-focused');
                });
                input.addEventListener('blur', function () {
                    this.parentElement.classList.remove('input-focused');
                });
            });
        });
    </script>
@endsection