<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile - Admin Panel</title>
    <link rel="stylesheet" href="{{asset('assets/front/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="{{asset('assets/admin-theme.css')}}" />
    <style>
        /* Custom profile styles */
        .profile-info-card {
            background: linear-gradient(135deg, #3b82f6 0%, #1e293b 100%);
            color: white;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 30px;
            display: grid;
            grid-template-columns: auto 1fr;
            gap: 25px;
            align-items: center;
        }
        .profile-info-card .icon {
            font-size: 48px;
            opacity: 0.8;
        }
        .profile-info-card .info {
            flex: 1;
        }
        .profile-info-card .info h3 {
            margin: 0 0 5px 0;
            font-size: 18px;
            font-weight: 600;
        }
        .profile-info-card .info p {
            margin: 5px 0;
            font-size: 13px;
            opacity: 0.9;
        }
        .info-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 15px;
        }
        .info-item {
            padding: 15px;
            background: #f9f9f9;
            border-radius: 6px;
        }
        .info-item label {
            font-size: 12px;
            color: #999;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
            display: block;
        }
        .info-item .value {
            font-size: 16px;
            color: #333;
            font-weight: 500;
        }
        .form-section {
            margin-bottom: 40px;
            padding: 20px;
            background: #f8fafc;
            border-radius: 8px;
            border-left: 4px solid #3b82f6;
        }
        .form-section h3 {
            color: #1e293b;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 20px;
        }
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        .form-row.full {
            grid-template-columns: 1fr;
        }
        .button-group {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
        }
        @media (max-width: 768px) {
            .admin-sidebar {
                width: 200px;
            }
            .admin-main {
                margin-left: 200px;
            }
            .form-row {
                grid-template-columns: 1fr;
            }
            .info-row {
                grid-template-columns: 1fr;
            }
            .profile-info-card {
                grid-template-columns: 1fr;
            }
        }
    </style>
    
</head>
<body>
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div class="logo">
                <i class="fas fa-shield-alt"></i> Admin
            </div>
            <ul class="nav-menu">
                <li><a href="{{url('/admin/dashboard')}}" class="{{Request::is('admin/dashboard') ? 'active' : ''}}"><i class="fas fa-chart-line"></i> Dashboard</a></li>
                <li><a href="{{url('/admin/customers')}}" class="{{Request::is('admin/customers') ? 'active' : ''}}"><i class="fas fa-users"></i> Customers</a></li>
                <li><a href="{{url('/admin/orders')}}" class="{{Request::is('admin/orders') ? 'active' : ''}}"><i class="fas fa-shopping-cart"></i> Orders</a></li>
                <li><a href="{{url('/admin/pricing')}}" class="{{Request::is('admin/pricing') ? 'active' : ''}}"><i class="fas fa-dollar-sign"></i> Pricing</a></li>
                <li><a href="{{url('/admin/profile')}}" class="{{Request::is('admin/profile') ? 'active' : ''}}"><i class="fas fa-user-circle"></i> Profile</a></li>
                <li style="border-top: 2px solid rgba(255,255,255,0.1); padding-top: 10px; margin-top: 20px;">
                    <a href="{{url('/admin/logout')}}"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </li>
            </ul>
        </aside>

        <!-- Main Content -->
        <div class="admin-main">
            <!-- Header -->
            <header class="admin-header">
                <h1><i class="fas fa-user-circle"></i> Admin Profile</h1>
                <div class="admin-user-menu">
                    <span><i class="fas fa-user-circle"></i> {{$admin->admin_name}}</span>
                    <a href="{{url('/admin/logout')}}">Logout</a>
                </div>
            </header>

            <!-- Content -->
            <main class="admin-content">
                @if ($message = Session::get('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i> {{ $message }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                @if ($message = Session::get('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                <!-- Profile Information Card -->
                <div class="profile-info-card">
                    <div class="icon">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <div class="info">
                        <h3>{{$admin->admin_name}}</h3>
                        <p><i class="fas fa-envelope"></i> {{$admin->admin_email}}</p>
                        <p><i class="fas fa-phone"></i> {{$admin->admin_phone ?? 'Not provided'}}</p>
                        <p><i class="fas fa-shield-alt"></i> Role: <strong>{{$admin->admin_role}}</strong></p>
                    </div>
                </div>

                <div class="content-section">
                    <h2><i class="fas fa-cog"></i> Profile Settings</h2>

                    <!-- Profile Information Form -->
                    <div class="form-section">
                        <h3><i class="fas fa-user"></i> Basic Information</h3>

                        <form method="POST" action="{{url('/admin/profile')}}">
                            @csrf

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="admin_name"><i class="fas fa-user"></i> Full Name</label>
                                    <input type="text" id="admin_name" name="admin_name" value="{{old('admin_name', $admin->admin_name)}}" required>
                                    @error('admin_name') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>

                                <div class="form-group">
                                    <label for="admin_email"><i class="fas fa-envelope"></i> Email Address</label>
                                    <input type="email" id="admin_email" name="admin_email" value="{{old('admin_email', $admin->admin_email)}}" required>
                                    @error('admin_email') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>

                            <div class="form-row full">
                                <div class="form-group">
                                    <label for="admin_phone"><i class="fas fa-phone"></i> Phone Number</label>
                                    <input type="text" id="admin_phone" name="admin_phone" value="{{old('admin_phone', $admin->admin_phone)}}">
                                    @error('admin_phone') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>

                            <div class="button-group">
                                <button type="reset" class="btn btn-secondary">Reset</button>
                                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update Profile</button>
                            </div>
                        </form>
                    </div>

                    <!-- Change Password Form -->
                    <div class="form-section">
                        <h3><i class="fas fa-lock"></i> Change Password</h3>

                        <form method="POST" action="{{url('/admin/change-password')}}">
                            @csrf

                            <div class="form-row full">
                                <div class="form-group">
                                    <label for="current_password"><i class="fas fa-key"></i> Current Password</label>
                                    <input type="password" id="current_password" name="current_password" required>
                                    @error('current_password') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="new_password"><i class="fas fa-lock"></i> New Password</label>
                                    <input type="password" id="new_password" name="new_password" required>
                                    @error('new_password') <small class="text-danger">{{ $message }}</small> @enderror
                                    <small>Minimum 6 characters</small>
                                </div>

                                <div class="form-group">
                                    <label for="new_password_confirmation"><i class="fas fa-lock"></i> Confirm Password</label>
                                    <input type="password" id="new_password_confirmation" name="new_password_confirmation" required>
                                    <small>Must match the new password</small>
                                </div>
                            </div>

                            <div class="button-group">
                                <button type="submit" class="btn btn-primary"><i class="fas fa-key"></i> Change Password</button>
                            </div>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="{{asset('assets/front/js/bootstrap.min.js')}}"></script>
</body>
</html>
