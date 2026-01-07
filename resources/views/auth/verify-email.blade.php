@extends('layouts.auth')

@section('title', 'Verify Email')

@section('content')
    <div class="auth-container">
        <!-- Left Side - Hero -->
        <div class="auth-hero">
            <div class="auth-hero-content">
                <div class="logo">
                    <img src="{{ asset('icon-dark.png') }}" alt="Swap Hub" style="width: 40px; height: 40px;"
                        class="object-fit-contain">
                    <span>Swap Hub</span>
                </div>

                <h1 class="hero-title">Check your email!</h1>
                <p class="hero-description">
                    We've sent a verification link to your email address. Click the link to verify your account and get
                    started.
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

        <!-- Right Side - Content -->
        <div class="auth-form-container">
            <div class="auth-form-wrapper">
                <div class="form-header">
                    <div
                        style="width: 80px; height: 80px; background: linear-gradient(135deg, #0f59bc 0%, #1e40af 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
                        <i class="bi bi-envelope-check" style="font-size: 2.5rem; color: white;"></i>
                    </div>
                    <h2 class="form-title" style="text-align: center;">Verify Your Email</h2>
                    <p class="form-subtitle" style="text-align: center;">
                        Thanks for signing up! Before getting started, could you verify your email address by clicking on
                        the link we just emailed to you?
                    </p>
                </div>

                @if (session('status') == 'verification-link-sent')
                    <div
                        style="background: #d1fae5; border-left: 4px solid #10b981; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
                        <div style="display: flex; gap: 0.75rem; align-items: start;">
                            <i class="bi bi-check-circle-fill" style="color: #10b981; font-size: 1.25rem; flex-shrink: 0;"></i>
                            <div>
                                <h4 style="font-size: 0.9375rem; font-weight: 600; color: #065f46; margin: 0 0 0.25rem 0;">
                                    Email Sent!
                                </h4>
                                <p style="font-size: 0.875rem; color: #065f46; margin: 0; line-height: 1.5;">
                                    A new verification link has been sent to your email address.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Resend Verification Email -->
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="btn-primary">
                        <i class="bi bi-envelope-fill" style="margin-right: 0.5rem;"></i>
                        Resend Verification Email
                    </button>
                </form>

                <!-- Additional Info -->
                <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid #e2e8f0;">
                    <div
                        style="background: #eff6ff; border-left: 4px solid #0f59bc; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
                        <div style="display: flex; gap: 0.75rem;">
                            <i class="bi bi-info-circle" style="color: #0f59bc; font-size: 1.25rem; flex-shrink: 0;"></i>
                            <div>
                                <h4 style="font-size: 0.9375rem; font-weight: 600; color: #1e40af; margin: 0 0 0.25rem 0;">
                                    Didn't receive the email?
                                </h4>
                                <ul
                                    style="margin: 0; padding-left: 1.25rem; font-size: 0.875rem; color: #1e40af; line-height: 1.8;">
                                    <li>Check your spam or junk folder</li>
                                    <li>Make sure you entered the correct email</li>
                                    <li>Click the button above to resend</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Logout -->
                    <form method="POST" action="{{ route('logout') }}" style="text-align: center;">
                        @csrf
                        <button type="submit"
                            style="background: none; border: none; color: #0f59bc; font-size: 0.9375rem; font-weight: 600; cursor: pointer; text-decoration: none; padding: 0;">
                            <i class="bi bi-box-arrow-right" style="margin-right: 0.375rem;"></i>
                            Log out
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection