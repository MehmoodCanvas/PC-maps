<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - PC Maps</title>
    <link rel="stylesheet" href="{{asset('assets/front/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="{{asset('assets/admin-theme.css')}}">
    <style>
        body {
            background: linear-gradient(135deg, #0066cc 0%, #003d99 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .login-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            width: 100%;
            max-width: 450px;
        }
        .login-header {
            background: linear-gradient(135deg, #0066cc 0%, #003d99 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }
        .login-header h1 {
            font-size: 28px;
            margin-bottom: 10px;
            font-weight: 700;
        }
        .login-header p {
            font-size: 14px;
            opacity: 0.9;
            margin: 0;
        }
        .login-body {
            padding: 40px 30px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
        }
        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            transition: border-color 0.3s;
        }
        .form-group input:focus {
            outline: none;
            border-color: #0066cc;
            box-shadow: 0 0 0 3px rgba(0, 102, 204, 0.1);
        }
        .login-button {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #0066cc 0%, #003d99 100%);
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: opacity 0.3s;
            margin-top: 10px;
        }
        .login-button:hover {
            opacity: 0.9;
        }
        .alert {
            margin-bottom: 20px;
            border-radius: 6px;
        }
        .text-center {
            text-align: center;
            margin-top: 20px;
            color: #666;
            font-size: 14px;
        }
        .text-center a {
            color: #0066cc;
            text-decoration: none;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1><i class="fas fa-shield-alt"></i> Admin Panel</h1>
            <p>Secure Administrator Login</p>
        </div>
        <div class="login-body">
            @if ($message = Session::get('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            <form method="POST" action="{{url('/admin/post-login')}}">
                @csrf
                <div class="form-group">
                    <label for="admin_email"><i class="fas fa-envelope"></i> Email Address</label>
                    <input type="email" id="admin_email" name="admin_email" placeholder="Enter your email" required>
                    @error('admin_email') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="form-group">
                    <label for="admin_password"><i class="fas fa-lock"></i> Password</label>
                    <input type="password" id="admin_password" name="admin_password" placeholder="Enter your password" required>
                    @error('admin_password') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <button type="submit" class="login-button">
                    <i class="fas fa-sign-in-alt"></i> Sign In
                </button>
            </form>

            <div class="text-center">
                <p style="margin-bottom: 15px; color: #999;">Demo Credentials:</p>
                <small style="color: #999;">Email: admin@admin.com | Password: password</small>
            </div>
        </div>
    </div>

    <script src="{{asset('assets/front/js/bootstrap.min.js')}}"></script>
</body>
</html>
