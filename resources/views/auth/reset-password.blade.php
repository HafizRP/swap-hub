@extends('layouts.auth')

@section('title', 'Reset Password')

@section('content')
    <div class="auth-container">
        <!-- Left Side - Hero -->
        <div class="auth-hero">
            <div class="auth-hero-content">
                <div class="logo">
                    <div class="logo-icon">
                        <i class="bi bi-lightning-charge-fill"></i>
                    </div>
                    <span>Swap Hub</span>
                </div>

                <h1 class="hero-title">Create a new password.</h1>
                <p class="hero-description">
                    Choose a strong password to keep your account secure. Make sure it's at least 8 characters long.
                </p>

                <div class="hero-stats">
                    <div class="hero-avatars">
                        <img src="https://ui-avatars.com/api/?name=Alex+Johnson&background=3b82f6&color=fff" alt="User"
                            class="hero-avatar">
                        <img src="https://ui-avatars.com/api/?name=Sarah+Smith&background=1e40af&color=fff" alt="User"
                            class="hero-avatar">
                        <img src="https://ui-avatars.com/api/?name=Mike+Chen&background=3b82f6&color=fff" alt="User"
                            class="hero-avatar">
                    </div>
                    <span class="hero-stat-text">Join 10,000+ students</span>
                </div>
            </div>
        </div>

        <!-- Right Side - Form -->
        <div class="auth-form-container">
            <div class="auth-form-wrapper">
                <div class="form-header">
                    <h2 class="form-title">Set New Password</h2>
                    <p class="form-subtitle">
                        Enter your new password below. Make sure it's strong and memorable.
                    </p>
                </div>

                <!-- Password Reset Form -->
                <form method="POST" action="{{ route('password.store') }}">
                    @csrf

                    <!-- Password Reset Token -->
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <!-- Email Address -->
                    <div class="form-group">
                        <label for="email" class="form-label">Email Address</label>
                        <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required
                            autofocus autocomplete="username" class="form-input" placeholder="name@university.edu">
                        @error('email')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password Fields -->
                    <div class="password-group">
                        <div class="form-group">
                            <label for="password" class="form-label">New Password</label>
                            <div class="input-with-icon">
                                <input id="password" type="password" name="password" required autocomplete="new-password"
                                    class="form-input" placeholder="8+ characters">
                                <i class="bi bi-eye input-icon" id="togglePassword"></i>
                            </div>
                            @error('password')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <div class="input-with-icon">
                                <input id="password_confirmation" type="password" name="password_confirmation" required
                                    autocomplete="new-password" class="form-input" placeholder="Re-enter password">
                                <i class="bi bi-eye input-icon" id="togglePasswordConfirm"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Password Requirements -->
                    <div style="background: #f7fafc; border-radius: 8px; padding: 1rem; margin-bottom: 1.5rem;">
                        <p style="font-size: 0.875rem; font-weight: 600; color: #4a5568; margin: 0 0 0.5rem 0;">
                            Password must contain:
                        </p>
                        <ul style="margin: 0; padding-left: 1.5rem; font-size: 0.875rem; color: #718096; line-height: 1.8;">
                            <li>At least 8 characters</li>
                            <li>Mix of letters and numbers recommended</li>
                            <li>Special characters for extra security</li>
                        </ul>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn-primary">
                        Reset Password
                    </button>

                    <!-- Back to Login -->
                    <div class="form-footer">
                        <a href="{{ route('login') }}"
                            style="display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                            <i class="bi bi-arrow-left"></i>
                            Back to login
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Toggle password visibility
        document.getElementById('togglePassword')?.addEventListener('click', function () {
            const password = document.getElementById('password');
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.classList.toggle('bi-eye');
            this.classList.toggle('bi-eye-slash');
        });

        document.getElementById('togglePasswordConfirm')?.addEventListener('click', function () {
            const password = document.getElementById('password_confirmation');
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.classList.toggle('bi-eye');
            this.classList.toggle('bi-eye-slash');
        });
    </script>
@endsection