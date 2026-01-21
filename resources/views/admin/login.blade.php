<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - PC Maps</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            display: flex;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: #f8fafc;
        }

        /* Left Panel - Branding */
        .brand-panel {
            width: 45%;
            background: #1e3a5f;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 60px;
            position: relative;
        }

        .brand-panel::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            width: 1px;
            background: linear-gradient(to bottom, transparent, rgba(255,255,255,0.1), transparent);
        }

        .brand-logo {
            width: 80px;
            height: 80px;
            background: white;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 32px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        }

        .brand-logo i {
            font-size: 36px;
            color: #1e3a5f;
        }

        .brand-panel h1 {
            color: white;
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 16px;
            text-align: center;
        }

        .brand-panel p {
            color: rgba(255, 255, 255, 0.7);
            font-size: 16px;
            text-align: center;
            max-width: 320px;
            line-height: 1.6;
        }

        /* Right Panel - Login Form */
        .login-panel {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 40px;
        }

        .login-container {
            width: 100%;
            max-width: 400px;
        }

        .login-header {
            margin-bottom: 36px;
        }

        .login-header h2 {
            font-size: 28px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 8px;
        }

        .login-header p {
            color: #64748b;
            font-size: 15px;
        }

        /* Alert */
        .alert {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-left: 4px solid #ef4444;
            border-radius: 8px;
            padding: 14px 16px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .alert i {
            color: #ef4444;
            font-size: 18px;
        }

        .alert span {
            color: #b91c1c;
            font-size: 14px;
            flex: 1;
        }

        .alert .btn-close {
            background: transparent;
            border: none;
            color: #ef4444;
            cursor: pointer;
            padding: 4px;
            border-radius: 4px;
        }

        .alert .btn-close:hover {
            background: rgba(239, 68, 68, 0.1);
        }

        /* Form */
        .form-group {
            margin-bottom: 24px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 600;
            color: #374151;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper i.input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: 16px;
        }

        .form-group input {
            width: 100%;
            padding: 14px 14px 14px 44px;
            background: white;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 15px;
            color: #1f2937;
            font-family: inherit;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-group input::placeholder {
            color: #9ca3af;
        }

        .form-group input:focus {
            outline: none;
            border-color: #1e3a5f;
            box-shadow: 0 0 0 3px rgba(30, 58, 95, 0.1);
        }

        .form-group small.text-danger {
            display: block;
            color: #dc2626;
            font-size: 13px;
            margin-top: 6px;
        }

        /* Password toggle */
        .toggle-password {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: transparent;
            border: none;
            color: #9ca3af;
            cursor: pointer;
            padding: 4px;
        }

        .toggle-password:hover {
            color: #6b7280;
        }

        /* Remember me */
        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 28px;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }

        .remember-me input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: #1e3a5f;
            cursor: pointer;
        }

        .remember-me span {
            font-size: 14px;
            color: #4b5563;
        }

        .forgot-link {
            font-size: 14px;
            color: #1e3a5f;
            text-decoration: none;
            font-weight: 500;
        }

        .forgot-link:hover {
            text-decoration: underline;
        }

        /* Login Button */
        .btn-login {
            width: 100%;
            padding: 14px 24px;
            background: #1e3a5f;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: background 0.2s;
            font-family: inherit;
        }

        .btn-login:hover {
            background: #15293f;
        }

        .btn-login:active {
            background: #0f1f2e;
        }

        .btn-login i {
            font-size: 16px;
        }

        /* Footer */
        .login-footer {
            text-align: center;
            margin-top: 32px;
            padding-top: 24px;
            border-top: 1px solid #e5e7eb;
        }

        .login-footer p {
            color: #6b7280;
            font-size: 13px;
        }

        /* Copyright */
        .copyright {
            position: absolute;
            bottom: 24px;
            color: rgba(255, 255, 255, 0.5);
            font-size: 12px;
        }

        /* Responsive */
        @media (max-width: 900px) {
            .brand-panel {
                display: none;
            }

            .login-panel {
                padding: 24px;
            }
        }

        @media (max-width: 480px) {
            .login-header h2 {
                font-size: 24px;
            }

            .form-options {
                flex-direction: column;
                gap: 12px;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>
    <!-- Left Branding Panel -->
    <div class="brand-panel">
        <div class="brand-logo">
            <i class="fas fa-map-location-dot"></i>
        </div>
        <h1>PC Maps</h1>
        <p>Manage Orders and Customers</p>
        
        <p class="copyright">&copy; 2026 PC Maps. All rights reserved.</p>
    </div>

    <!-- Right Login Panel -->
    <div class="login-panel">
        <div class="login-container">
            <div class="login-header">
                <h2>Welcome back</h2>
                <p>Please enter your credentials to access the admin panel</p>
            </div>

            @if ($message = Session::get('error'))
            <div class="alert" role="alert">
                <i class="fas fa-exclamation-circle"></i>
                <span>{{ $message }}</span>
                <button type="button" class="btn-close" onclick="this.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            @endif

            <form method="POST" action="{{url('/admin/post-login')}}" id="loginForm">
                @csrf
                <div class="form-group">
                    <label for="admin_email">Email Address</label>
                    <div class="input-wrapper">
                        <i class="fas fa-envelope input-icon"></i>
                        <input 
                            type="email" 
                            id="admin_email" 
                            name="admin_email" 
                            placeholder="you@example.com"
                            autocomplete="email"
                            required
                        >
                    </div>
                    @error('admin_email') 
                    <small class="text-danger">{{ $message }}</small> 
                    @enderror
                </div>

                <div class="form-group">
                    <label for="admin_password">Password</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock input-icon"></i>
                        <input 
                            type="password" 
                            id="admin_password" 
                            name="admin_password" 
                            placeholder="Enter your password"
                            autocomplete="current-password"
                            required
                        >
                        <button type="button" class="toggle-password" onclick="togglePassword()">
                            <i class="fas fa-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                    @error('admin_password') 
                    <small class="text-danger">{{ $message }}</small> 
                    @enderror
                </div>

                <div class="form-options">
                    <label class="remember-me">
                        <input type="checkbox" name="remember">
                        <span>Remember me</span>
                    </label>
                </div>

                <button type="submit" class="btn-login" id="submitBtn">
                    <i class="fas fa-sign-in-alt"></i>
                    Sign In
                </button>
            </form>

            <div class="login-footer">
                <p>Authorized personnel only. All access is monitored.</p>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('admin_password');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
