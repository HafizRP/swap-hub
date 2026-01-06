<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - {{ config('app.name', 'Swap Hub') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            min-height: 100vh;
            background: #ffffff;
        }

        .auth-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            min-height: 100vh;
        }

        /* Left Side - Hero */
        .auth-hero {
            background: linear-gradient(135deg, #0f59bc 0%, #1e40af 100%);
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 3rem;
            color: white;
        }

        .auth-hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><defs><pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse"><path d="M 40 0 L 0 0 0 40" fill="none" stroke="rgba(255,255,255,0.05)" stroke-width="1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
            opacity: 0.3;
        }

        .auth-hero-content {
            position: relative;
            z-index: 1;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 4rem;
        }

        .logo-icon {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .hero-title {
            font-size: 3rem;
            font-weight: 900;
            line-height: 1.1;
            margin-bottom: 1.5rem;
            max-width: 500px;
        }

        .hero-description {
            font-size: 1.125rem;
            line-height: 1.7;
            opacity: 0.9;
            max-width: 450px;
            margin-bottom: 2rem;
        }

        .hero-stats {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 2rem;
        }

        .hero-avatars {
            display: flex;
            margin-left: -0.5rem;
        }

        .hero-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 3px solid #0f59bc;
            margin-left: -12px;
            object-fit: cover;
        }

        .hero-stat-text {
            font-size: 0.875rem;
            font-weight: 600;
            margin-left: 0.5rem;
        }

        /* Right Side - Form */
        .auth-form-container {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            background: #ffffff;
        }

        .auth-form-wrapper {
            width: 100%;
            max-width: 480px;
        }

        .form-header {
            margin-bottom: 2.5rem;
        }

        .form-title {
            font-size: 2rem;
            font-weight: 800;
            color: #1a202c;
            margin-bottom: 0.5rem;
        }

        .form-subtitle {
            color: #718096;
            font-size: 1rem;
        }

        .social-buttons {
            display: grid;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }

        .btn-social {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            padding: 0.875rem 1.5rem;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            background: white;
            color: #1a202c;
            font-weight: 600;
            font-size: 0.9375rem;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
        }

        .btn-social:hover {
            border-color: #cbd5e0;
            background: #f7fafc;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .btn-social svg {
            width: 20px;
            height: 20px;
        }

        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 1.5rem 0;
            color: #a0aec0;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #e2e8f0;
        }

        .divider span {
            padding: 0 1rem;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 0.5rem;
        }

        .form-input {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 0.9375rem;
            transition: all 0.2s;
            background: white;
        }

        .form-input:focus {
            outline: none;
            border-color: #0f59bc;
            box-shadow: 0 0 0 3px rgba(15, 89, 188, 0.1);
        }

        .form-input::placeholder {
            color: #a0aec0;
        }

        .password-group {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .input-with-icon {
            position: relative;
        }

        .input-icon {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #a0aec0;
            cursor: pointer;
            transition: color 0.2s;
        }

        .input-icon:hover {
            color: #0f59bc;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin: 1.5rem 0;
        }

        .checkbox-group input[type="checkbox"] {
            width: 18px;
            height: 18px;
            border: 2px solid #e2e8f0;
            border-radius: 4px;
            cursor: pointer;
        }

        .checkbox-group label {
            font-size: 0.875rem;
            color: #4a5568;
            cursor: pointer;
            user-select: none;
        }

        .checkbox-group a {
            color: #0f59bc;
            text-decoration: none;
            font-weight: 600;
        }

        .checkbox-group a:hover {
            text-decoration: underline;
        }

        .btn-primary {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, #0f59bc 0%, #1e40af 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            margin-top: 0.5rem;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(15, 89, 188, 0.3);
        }

        .form-footer {
            text-align: center;
            margin-top: 1.5rem;
            color: #718096;
            font-size: 0.9375rem;
        }

        .form-footer a {
            color: #0f59bc;
            text-decoration: none;
            font-weight: 600;
        }

        .form-footer a:hover {
            text-decoration: underline;
        }

        .error-message {
            color: #e53e3e;
            font-size: 0.875rem;
            margin-top: 0.375rem;
            display: flex;
            align-items: center;
            gap: 0.375rem;
        }

        .error-message::before {
            content: '⚠';
            font-size: 1rem;
        }

        .form-input.error {
            border-color: #fc8181;
            background: #fff5f5;
        }

        .form-input.error:focus {
            border-color: #e53e3e;
            box-shadow: 0 0 0 3px rgba(229, 62, 62, 0.1);
        }

        .success-message {
            background: #c6f6d5;
            color: #22543d;
            padding: 0.875rem 1rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            font-size: 0.9375rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            border: 1px solid #9ae6b4;
        }

        .success-message::before {
            content: '✓';
            width: 24px;
            height: 24px;
            background: #22543d;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        /* Toast Notifications */
        .toast-container {
            position: fixed;
            top: 1.5rem;
            right: 1.5rem;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            max-width: 400px;
        }

        .toast {
            background: white;
            border-radius: 12px;
            padding: 1rem 1.25rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1), 0 2px 8px rgba(0, 0, 0, 0.06);
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            animation: slideIn 0.3s ease-out;
            border-left: 4px solid;
        }

        .toast.error {
            border-left-color: #e53e3e;
        }

        .toast.success {
            border-left-color: #38a169;
        }

        .toast.warning {
            border-left-color: #d69e2e;
        }

        .toast-icon {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 0.875rem;
            font-weight: 700;
        }

        .toast.error .toast-icon {
            background: #fed7d7;
            color: #e53e3e;
        }

        .toast.success .toast-icon {
            background: #c6f6d5;
            color: #38a169;
        }

        .toast.warning .toast-icon {
            background: #feebc8;
            color: #d69e2e;
        }

        .toast-content {
            flex: 1;
        }

        .toast-title {
            font-weight: 600;
            font-size: 0.9375rem;
            color: #1a202c;
            margin-bottom: 0.125rem;
        }

        .toast-message {
            font-size: 0.875rem;
            color: #718096;
            line-height: 1.4;
        }

        .toast-close {
            background: none;
            border: none;
            color: #a0aec0;
            cursor: pointer;
            padding: 0;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
            transition: all 0.2s;
        }

        .toast-close:hover {
            background: #edf2f7;
            color: #4a5568;
        }

        @keyframes slideIn {
            from {
                transform: translateX(400px);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }

            to {
                transform: translateX(400px);
                opacity: 0;
            }
        }

        .toast.removing {
            animation: slideOut 0.3s ease-out forwards;
        }

        /* Loading State */
        .btn-primary:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .btn-primary.loading {
            position: relative;
            color: transparent;
        }

        .btn-primary.loading::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            top: 50%;
            left: 50%;
            margin-left: -10px;
            margin-top: -10px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Validation Icons */
        .input-validation-icon {
            position: absolute;
            right: 3rem;
            top: 50%;
            transform: translateY(-50%);
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 700;
        }

        .input-validation-icon.valid {
            background: #c6f6d5;
            color: #38a169;
        }

        .input-validation-icon.invalid {
            background: #fed7d7;
            color: #e53e3e;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .auth-container {
                grid-template-columns: 1fr;
            }

            .auth-hero {
                display: none;
            }

            .auth-form-container {
                padding: 2rem 1.5rem;
            }

            .toast-container {
                left: 1rem;
                right: 1rem;
                max-width: none;
            }
        }

        @media (max-width: 640px) {
            .hero-title {
                font-size: 2rem;
            }

            .form-title {
                font-size: 1.75rem;
            }

            .password-group {
                grid-template-columns: 1fr;
            }

            .toast-container {
                top: 1rem;
                left: 1rem;
                right: 1rem;
            }
        }
    </style>
</head>

<body>
    <!-- Toast Container -->
    <div class="toast-container" id="toastContainer"></div>

    @yield('content')

    <script>
        // Toast Notification System
        class ToastManager {
            constructor() {
                this.container = document.getElementById('toastContainer');
            }

            show(message, type = 'info', title = null, duration = 5000) {
                const toast = document.createElement('div');
                toast.className = `toast ${type}`;

                const icons = {
                    error: '✕',
                    success: '✓',
                    warning: '⚠',
                    info: 'ℹ'
                };

                const titles = {
                    error: title || 'Error',
                    success: title || 'Success',
                    warning: title || 'Warning',
                    info: title || 'Info'
                };

                toast.innerHTML = `
                    <div class="toast-icon">${icons[type]}</div>
                    <div class="toast-content">
                        <div class="toast-title">${titles[type]}</div>
                        <div class="toast-message">${message}</div>
                    </div>
                    <button class="toast-close" onclick="this.closest('.toast').remove()">✕</button>
                `;

                this.container.appendChild(toast);

                if (duration > 0) {
                    setTimeout(() => {
                        toast.classList.add('removing');
                        setTimeout(() => toast.remove(), 300);
                    }, duration);
                }

                return toast;
            }

            error(message, title = null) {
                return this.show(message, 'error', title);
            }

            success(message, title = null) {
                return this.show(message, 'success', title);
            }

            warning(message, title = null) {
                return this.show(message, 'warning', title);
            }
        }

        const toast = new ToastManager();

        // Show Laravel validation errors as toasts
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                toast.error('{{ $error }}');
            @endforeach
        @endif

        // Show success messages
        @if (session('status'))
            toast.success('{{ session('status') }}');
        @endif

        @if (session('success'))
            toast.success('{{ session('success') }}');
        @endif

        // Form validation helper
        function validateInput(input) {
            const value = input.value.trim();
            const type = input.type;
            let isValid = true;

            if (input.required && !value) {
                isValid = false;
            }

            if (type === 'email' && value) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                isValid = emailRegex.test(value);
            }

            if (type === 'password' && value && input.name === 'password') {
                isValid = value.length >= 8;
            }

            if (input.name === 'password_confirmation') {
                const password = document.getElementById('password');
                isValid = password && value === password.value;
            }

            // Update visual state
            if (value) {
                if (isValid) {
                    input.classList.remove('error');
                    input.classList.add('valid');
                } else {
                    input.classList.add('error');
                    input.classList.remove('valid');
                }
            } else {
                input.classList.remove('error', 'valid');
            }

            return isValid;
        }

        // Auto-validate on blur
        document.addEventListener('DOMContentLoaded', function () {
            const inputs = document.querySelectorAll('.form-input');
            inputs.forEach(input => {
                input.addEventListener('blur', () => validateInput(input));
                input.addEventListener('input', () => {
                    if (input.classList.contains('error')) {
                        validateInput(input);
                    }
                });
            });

            // Form submit with loading state
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function (e) {
                    const submitBtn = form.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        submitBtn.classList.add('loading');
                        submitBtn.disabled = true;
                    }
                });
            });
        });
    </script>
</body>

</html>