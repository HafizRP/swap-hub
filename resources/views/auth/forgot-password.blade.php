@extends('layouts.auth')

@section('title', 'Forgot Password')

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

                <h1 class="hero-title">Forgot your password?</h1>
                <p class="hero-description">
                    No worries! Just enter your email address and we'll send you a link to reset your password.
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
                    <h2 class="form-title">Reset Password</h2>
                    <p class="form-subtitle">
                        Enter your email address and we'll send you instructions to reset your password.
                    </p>
                </div>

                <!-- Password Reset Form -->
                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <!-- Email Address -->
                    <div class="form-group">
                        <label for="email" class="form-label">Email Address</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                            class="form-input" placeholder="name@university.edu">
                        @error('email')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn-primary">
                        Send Reset Link
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

                <!-- Additional Help -->
                <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid #e2e8f0;">
                    <div style="background: #eff6ff; border-left: 4px solid #0f59bc; padding: 1rem; border-radius: 8px;">
                        <div style="display: flex; gap: 0.75rem;">
                            <i class="bi bi-info-circle" style="color: #0f59bc; font-size: 1.25rem; flex-shrink: 0;"></i>
                            <div>
                                <h4 style="font-size: 0.9375rem; font-weight: 600; color: #1e40af; margin: 0 0 0.25rem 0;">
                                    Need help?
                                </h4>
                                <p style="font-size: 0.875rem; color: #1e40af; margin: 0; line-height: 1.5;">
                                    If you don't receive an email within a few minutes, check your spam folder or contact
                                    support.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection