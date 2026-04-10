<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Dashboard - PC Maps</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta name="robots" content="noindex" />
    <link rel="icon" href="{{asset('assets/front/images/favicon.ico')}}">
    <link rel="stylesheet" href="{{asset('assets/front/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #f0f2f5;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* ===== SIDEBAR ===== */
        .dash-sidebar {
            position: fixed;
            top: 0; left: 0;
            width: 260px;
            height: 100vh;
            background: linear-gradient(180deg, #0f1724 0%, #1a2332 50%, #0d1520 100%);
            z-index: 1000;
            display: flex;
            flex-direction: column;
            transition: transform 0.3s ease;
        }
        .dash-sidebar-logo {
            padding: 28px 24px;
            border-bottom: 1px solid rgba(255,255,255,0.06);
        }
        .dash-sidebar-logo img {
            max-width: 140px;
            height: auto;
        }
        .dash-sidebar-nav {
            flex: 1;
            padding: 16px 12px;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }
        .dash-nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 13px 16px;
            border-radius: 10px;
            color: rgba(255,255,255,0.55);
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
            text-decoration: none;
        }
        .dash-nav-item:hover {
            background: rgba(255,255,255,0.06);
            color: rgba(255,255,255,0.85);
        }
        .dash-nav-item.active {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: #fff;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
        }
        .dash-nav-item i {
            width: 20px;
            text-align: center;
            font-size: 15px;
        }
        .dash-nav-divider {
            height: 1px;
            background: rgba(255,255,255,0.06);
            margin: 12px 4px;
        }
        .dash-sidebar-bottom {
            padding: 16px 12px;
            border-top: 1px solid rgba(255,255,255,0.06);
        }
        .dash-nav-item.logout-btn {
            color: rgba(255,100,100,0.7);
        }
        .dash-nav-item.logout-btn:hover {
            background: rgba(255,100,100,0.1);
            color: #ff6b6b;
        }

        /* ===== MAIN CONTENT ===== */
        .dash-main {
            margin-left: 260px;
            min-height: 100vh;
        }

        /* ===== TOP BAR ===== */
        .dash-topbar {
            background: #fff;
            padding: 16px 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #e5e7eb;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .dash-topbar-title {
            font-size: 18px;
            font-weight: 700;
            color: #1a1a2e;
        }
        .dash-user-menu {
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            position: relative;
        }
        .dash-user-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #e5e7eb;
        }
        .dash-user-info {
            text-align: right;
        }
        .dash-user-name {
            font-size: 14px;
            font-weight: 600;
            color: #1a1a2e;
        }
        .dash-user-role {
            font-size: 11px;
            color: #6b7280;
        }
        .dash-user-dropdown {
            position: absolute;
            top: 50px;
            right: 0;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.12);
            border: 1px solid #e5e7eb;
            width: 180px;
            display: none;
            overflow: hidden;
            z-index: 200;
        }
        .dash-user-dropdown.show { display: block; }
        .dash-user-dropdown a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 16px;
            color: #374151;
            font-size: 13px;
            font-weight: 500;
            transition: all 0.2s;
            text-decoration: none;
        }
        .dash-user-dropdown a:hover {
            background: #f3f4f6;
            color: #1a1a2e;
        }
        .dash-user-dropdown a.logout-link {
            color: #ef4444;
            border-top: 1px solid #f3f4f6;
        }
        .dash-user-dropdown a.logout-link:hover {
            background: #fef2f2;
        }

        /* ===== CONTENT AREA ===== */
        .dash-content {
            padding: 28px 32px;
        }

        /* ===== WELCOME HERO ===== */
        .dash-welcome {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            border-radius: 16px;
            padding: 32px;
            margin-bottom: 28px;
            position: relative;
            overflow: hidden;
        }
        .dash-welcome::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -30%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(59,130,246,0.15) 0%, transparent 70%);
            border-radius: 50%;
        }
        .dash-welcome::after {
            content: '';
            position: absolute;
            bottom: -60%;
            left: 10%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(6,177,215,0.1) 0%, transparent 70%);
            border-radius: 50%;
        }
        .dash-welcome h2 {
            font-size: 26px;
            font-weight: 700;
            color: #fff;
            margin-bottom: 6px;
            position: relative;
            z-index: 1;
        }
        .dash-welcome p {
            font-size: 14px;
            color: rgba(255,255,255,0.6);
            margin-bottom: 20px;
            position: relative;
            z-index: 1;
        }
        .dash-welcome-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: #fff;
            padding: 12px 24px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            position: relative;
            z-index: 1;
            box-shadow: 0 4px 15px rgba(59,130,246,0.3);
        }
        .dash-welcome-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(59,130,246,0.4);
            color: #fff;
        }

        /* ===== STAT CARDS ===== */
        .dash-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 28px;
        }
        .dash-stat-card {
            background: #fff;
            border-radius: 14px;
            padding: 22px;
            border: 1px solid #e5e7eb;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        .dash-stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
        }
        .dash-stat-card::after {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
        }
        .dash-stat-card.blue::after { background: linear-gradient(90deg, #3b82f6, #60a5fa); }
        .dash-stat-card.green::after { background: linear-gradient(90deg, #10b981, #34d399); }
        .dash-stat-card.amber::after { background: linear-gradient(90deg, #f59e0b, #fbbf24); }
        .dash-stat-card.purple::after { background: linear-gradient(90deg, #8b5cf6, #a78bfa); }
        .dash-stat-icon {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            margin-bottom: 14px;
        }
        .dash-stat-icon.blue { background: rgba(59,130,246,0.1); color: #3b82f6; }
        .dash-stat-icon.green { background: rgba(16,185,129,0.1); color: #10b981; }
        .dash-stat-icon.amber { background: rgba(245,158,11,0.1); color: #f59e0b; }
        .dash-stat-icon.purple { background: rgba(139,92,246,0.1); color: #8b5cf6; }
        .dash-stat-value {
            font-size: 28px;
            font-weight: 800;
            color: #1a1a2e;
            line-height: 1;
            margin-bottom: 4px;
        }
        .dash-stat-label {
            font-size: 12px;
            font-weight: 500;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* ===== TABLE CARD ===== */
        .dash-card {
            background: #fff;
            border-radius: 14px;
            border: 1px solid #e5e7eb;
            overflow: hidden;
            margin-bottom: 24px;
        }
        .dash-card-header {
            padding: 20px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #f3f4f6;
        }
        .dash-card-title {
            font-size: 16px;
            font-weight: 700;
            color: #1a1a2e;
        }
        .dash-card-action {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: #fff;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
        }
        .dash-card-action:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(59,130,246,0.3);
            color: #fff;
        }
        .dash-card-body { padding: 0; }
        .dash-card-body.padded { padding: 24px; }

        /* ===== TABLE ===== */
        .dash-table {
            width: 100%;
            border-collapse: collapse;
        }
        .dash-table thead th {
            background: #f8fafc;
            padding: 12px 20px;
            font-size: 11px;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }
        .dash-table tbody tr {
            transition: background 0.15s;
        }
        .dash-table tbody tr:hover {
            background: #f8fafc;
        }
        .dash-table tbody td {
            padding: 16px 20px;
            font-size: 14px;
            color: #374151;
            border-bottom: 1px solid #f3f4f6;
            vertical-align: middle;
        }
        .dash-table .map-thumb {
            width: 60px;
            height: 45px;
            object-fit: cover;
            border-radius: 6px;
            border: 1px solid #e5e7eb;
        }
        .dash-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .dash-badge.pending {
            background: #fef3c7;
            color: #92400e;
        }
        .dash-badge.completed {
            background: #d1fae5;
            color: #065f46;
        }
        .dash-badge.approved {
            background: #dbeafe;
            color: #1e40af;
        }
        .dash-badge.cancelled {
            background: #fee2e2;
            color: #991b1b;
        }
        .dash-badge::before {
            content: '';
            width: 6px;
            height: 6px;
            border-radius: 50%;
        }
        .dash-badge.pending::before { background: #f59e0b; }
        .dash-badge.completed::before { background: #10b981; }
        .dash-badge.approved::before { background: #3b82f6; }
        .dash-badge.cancelled::before { background: #ef4444; }
        .dash-table-empty {
            text-align: center;
            padding: 50px 20px !important;
            color: #9ca3af;
        }
        .dash-table-empty i {
            font-size: 40px;
            margin-bottom: 12px;
            display: block;
            color: #d1d5db;
        }
        .dash-table-empty a {
            color: #3b82f6;
            font-weight: 600;
            text-decoration: none;
        }
        .dash-table-empty a:hover { text-decoration: underline; }
        .buy-btn {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: linear-gradient(135deg, #10b981, #059669);
            color: #fff;
            padding: 6px 14px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
        }
        .buy-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 3px 10px rgba(16,185,129,0.3);
            color: #fff;
        }

        /* ===== SETTINGS FORM ===== */
        .settings-section {
            background: #f8fafc;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 24px;
            border: 1px solid #e5e7eb;
        }
        .settings-section h3 {
            font-size: 16px;
            font-weight: 700;
            color: #1a1a2e;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .settings-section h3 i {
            color: #3b82f6;
        }
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }
        .form-row.single { grid-template-columns: 1fr; }
        .form-field {
            margin-bottom: 16px;
        }
        .form-field label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 6px;
        }
        .form-field input[type="text"],
        .form-field input[type="email"],
        .form-field input[type="tel"],
        .form-field input[type="file"] {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #d1d5db;
            border-radius: 10px;
            font-size: 14px;
            color: #1a1a2e;
            background: #fff;
            transition: all 0.2s;
            font-family: 'Inter', sans-serif;
        }
        .form-field input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59,130,246,0.1);
            outline: none;
        }
        .form-field small {
            display: block;
            font-size: 11px;
            color: #9ca3af;
            margin-top: 4px;
        }
        .form-field .text-danger {
            font-size: 12px;
            color: #ef4444;
        }
        .profile-avatar-section {
            display: flex;
            align-items: center;
            gap: 24px;
            margin-bottom: 16px;
        }
        .profile-avatar-lg {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #e5e7eb;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }
        .btn-primary-dash {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: #fff;
            padding: 12px 24px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            font-family: 'Inter', sans-serif;
        }
        .btn-primary-dash:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(59,130,246,0.3);
            color: #fff;
        }
        .btn-secondary-dash {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #f3f4f6;
            color: #374151;
            padding: 10px 18px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            border: 1px solid #d1d5db;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            font-family: 'Inter', sans-serif;
        }
        .btn-secondary-dash:hover {
            background: #e5e7eb;
            color: #1a1a2e;
        }

        /* ===== TAB SYSTEM (hidden, JS-driven) ===== */
        .dash-tab-content { display: none; animation: fadeIn 0.3s ease; }
        .dash-tab-content.active { display: block; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ===== ALERTS ===== */
        .dash-alert {
            padding: 14px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
            font-weight: 500;
        }
        .dash-alert.success {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }
        .dash-alert.error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }
        .dash-alert-close {
            margin-left: auto;
            background: none;
            border: none;
            color: inherit;
            cursor: pointer;
            font-size: 18px;
            opacity: 0.6;
        }
        .dash-alert-close:hover { opacity: 1; }

        /* ===== MOBILE TOGGLE ===== */
        .dash-mobile-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 20px;
            color: #1a1a2e;
            cursor: pointer;
            padding: 4px;
        }
        .dash-sidebar-overlay {
            display: none;
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.5);
            z-index: 999;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .dash-sidebar {
                transform: translateX(-100%);
            }
            .dash-sidebar.open {
                transform: translateX(0);
            }
            .dash-sidebar-overlay.show { display: block; }
            .dash-main { margin-left: 0; }
            .dash-mobile-toggle { display: block; }
            .dash-topbar { padding: 14px 16px; }
            .dash-content { padding: 16px; }
            .dash-welcome { padding: 24px; }
            .dash-welcome h2 { font-size: 20px; }
            .dash-stats { grid-template-columns: 1fr 1fr; gap: 10px; }
            .dash-stat-value { font-size: 22px; }
            .form-row { grid-template-columns: 1fr; }
            .profile-avatar-section { flex-direction: column; text-align: center; }
            .dash-table { font-size: 12px; }
            .dash-table thead th, .dash-table tbody td { padding: 10px 12px; }
        }
    </style>
</head>
<body>
    <!-- Sidebar Overlay (mobile) -->
    <div class="dash-sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Sidebar -->
    <aside class="dash-sidebar" id="dashSidebar">
        <div class="dash-sidebar-logo">
            <img src="{{asset('assets/front/images/logo.png')}}" alt="PC Maps">
        </div>
        <nav class="dash-sidebar-nav">
            <button class="dash-nav-item active" data-tab="orders">
                <i class="fa-solid fa-shopping-bag"></i> Orders
            </button>
            <button class="dash-nav-item" data-tab="maps">
                <i class="fa-solid fa-map"></i> My Maps
            </button>
            <button class="dash-nav-item" data-tab="settings">
                <i class="fa-solid fa-gear"></i> Settings
            </button>
            <div class="dash-nav-divider"></div>
            <a href="{{url('create-map')}}" class="dash-nav-item">
                <i class="fa-solid fa-plus-circle"></i> Create New Map
            </a>
        </nav>
        <div class="dash-sidebar-bottom">
            <a href="{{url('logout')}}" class="dash-nav-item logout-btn">
                <i class="fa-solid fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="dash-main">
        <!-- Top Bar -->
        <header class="dash-topbar">
            <div style="display:flex;align-items:center;gap:12px;">
                <button class="dash-mobile-toggle" id="menuToggle">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <span class="dash-topbar-title" id="topbarTitle">Orders</span>
            </div>
            <div class="dash-user-menu" id="userMenu">
                @if($user->customer_profile_picture && Storage::exists('public/' . $user->customer_profile_picture))
                    <img src="{{asset('storage/' . $user->customer_profile_picture)}}" class="dash-user-avatar" alt="Profile">
                @else
                    <img src="{{asset('assets/front/images/d-user-icon.png')}}" class="dash-user-avatar" alt="Profile">
                @endif
                <div class="dash-user-info">
                    <div class="dash-user-name">{{Auth::guard('customer')->user()->customer_name}}</div>
                    <div class="dash-user-role">Customer</div>
                </div>
                <i class="fa-solid fa-chevron-down" style="font-size:10px;color:#9ca3af;"></i>
                <div class="dash-user-dropdown" id="userDropdown">
                    <a href="#" onclick="switchTab('settings');return false;"><i class="fa-solid fa-user-circle"></i> My Profile</a>
                    <a href="{{url('logout')}}" class="logout-link"><i class="fa-solid fa-sign-out-alt"></i> Logout</a>
                </div>
            </div>
        </header>

        <!-- Content Area -->
        <div class="dash-content">
            @if ($message = Session::get('success'))
            <div class="dash-alert success">
                <i class="fa-solid fa-check-circle"></i> {{ $message }}
                <button class="dash-alert-close" onclick="this.parentElement.remove()">&times;</button>
            </div>
            @endif
            @if ($message = Session::get('error'))
            <div class="dash-alert error">
                <i class="fa-solid fa-exclamation-circle"></i> {{ $message }}
                <button class="dash-alert-close" onclick="this.parentElement.remove()">&times;</button>
            </div>
            @endif

            <!-- Welcome Hero -->
            <div class="dash-welcome">
                <h2>Welcome back, {{Auth::guard('customer')->user()->customer_name}}!</h2>
                <p>Create stunning custom maps for laser engraving. Design, customize, and order your personalized map today.</p>
                <a href="{{url('create-map')}}" class="dash-welcome-btn">
                    <i class="fa-solid fa-plus"></i> Create New Map
                </a>
            </div>

            <!-- Stat Cards -->
            <div class="dash-stats">
                <div class="dash-stat-card blue">
                    <div class="dash-stat-icon blue"><i class="fa-solid fa-shopping-bag"></i></div>
                    <div class="dash-stat-value">{{count($orders)}}</div>
                    <div class="dash-stat-label">Total Orders</div>
                </div>
                <div class="dash-stat-card green">
                    <div class="dash-stat-icon green"><i class="fa-solid fa-map"></i></div>
                    <div class="dash-stat-value">{{count($maps)}}</div>
                    <div class="dash-stat-label">Total Maps</div>
                </div>
                <div class="dash-stat-card amber">
                    <div class="dash-stat-icon amber"><i class="fa-solid fa-clock"></i></div>
                    <div class="dash-stat-value">{{$orders->where('order_status', 'Pending')->count()}}</div>
                    <div class="dash-stat-label">Pending Orders</div>
                </div>
                <div class="dash-stat-card purple">
                    <div class="dash-stat-icon purple"><i class="fa-solid fa-check-circle"></i></div>
                    <div class="dash-stat-value">{{$orders->where('order_status', 'Completed')->count()}}</div>
                    <div class="dash-stat-label">Completed</div>
                </div>
            </div>

            <!-- ===== ORDERS TAB ===== -->
            <div class="dash-tab-content active" id="tab-orders">
                <div class="dash-card">
                    <div class="dash-card-header">
                        <span class="dash-card-title"><i class="fa-solid fa-receipt" style="color:#3b82f6;margin-right:6px;"></i> Your Orders</span>
                    </div>
                    <div class="dash-card-body">
                        <table class="dash-table">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Map Preview</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($orders as $order)
                                <tr>
                                    <td style="font-weight:600;">#{{$order->order_invoice_id}}</td>
                                    <td><img src="{{$order->map_data}}" alt="Map" class="map-thumb"></td>
                                    <td style="font-weight:700;color:#1a1a2e;">${{number_format($order->order_total_amount, 2)}}</td>
                                    <td>
                                        @php
                                            $status = strtolower($order->order_status);
                                            $badgeClass = 'pending';
                                            if($status === 'completed') $badgeClass = 'completed';
                                            elseif($status === 'approved') $badgeClass = 'approved';
                                            elseif($status === 'cancelled') $badgeClass = 'cancelled';
                                        @endphp
                                        <span class="dash-badge {{$badgeClass}}">{{ucfirst($order->order_status)}}</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="dash-table-empty">
                                        <i class="fa-solid fa-shopping-bag"></i>
                                        No orders yet.<br>
                                        <a href="{{url('create-map')}}">Create a map and place your first order</a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- ===== MY MAPS TAB ===== -->
            <div class="dash-tab-content" id="tab-maps">
                <div class="dash-card">
                    <div class="dash-card-header">
                        <span class="dash-card-title"><i class="fa-solid fa-map" style="color:#10b981;margin-right:6px;"></i> Your Maps</span>
                        <a href="{{url('create-map')}}" class="dash-card-action">
                            <i class="fa-solid fa-plus"></i> Create New
                        </a>
                    </div>
                    <div class="dash-card-body">
                        <table class="dash-table">
                            <thead>
                                <tr>
                                    <th>Preview</th>
                                    <th>Width</th>
                                    <th>Height</th>
                                    <th>Price</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($maps as $map)
                                <tr>
                                    <td><img src="{{$map->map_data}}" class="map-thumb" alt="Map"></td>
                                    <td>{{$map->map_width}}"</td>
                                    <td>{{$map->map_height}}"</td>
                                    <td style="font-weight:700;color:#1a1a2e;">${{number_format($map->map_price, 2)}}</td>
                                    <td>
                                        <a href="{{url('checkout?id='.$map->map_id)}}" class="buy-btn">
                                            <i class="fa-solid fa-cart-shopping"></i> Buy Now
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="dash-table-empty">
                                        <i class="fa-solid fa-map"></i>
                                        No maps created yet.<br>
                                        <a href="{{url('create-map')}}">Create your first custom map</a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- ===== SETTINGS TAB ===== -->
            <div class="dash-tab-content" id="tab-settings">
                <!-- Profile Picture -->
                <div class="dash-card">
                    <div class="dash-card-header">
                        <span class="dash-card-title"><i class="fa-solid fa-camera" style="color:#8b5cf6;margin-right:6px;"></i> Profile Picture</span>
                    </div>
                    <div class="dash-card-body padded">
                        <form method="POST" action="{{url('/upload-profile-picture')}}" enctype="multipart/form-data">
                            @csrf
                            <div class="profile-avatar-section">
                                @if($user->customer_profile_picture && Storage::exists('public/' . $user->customer_profile_picture))
                                    <img id="profilePreview" src="{{asset('storage/' . $user->customer_profile_picture)}}" alt="Profile" class="profile-avatar-lg">
                                @else
                                    <img id="profilePreview" src="{{asset('assets/front/images/d-user-icon.png')}}" alt="Profile" class="profile-avatar-lg">
                                @endif
                                <div>
                                    <div class="form-field" style="margin-bottom:8px;">
                                        <input type="file" id="customer_profile_picture" name="customer_profile_picture" accept="image/*" onchange="previewImage(event)">
                                    </div>
                                    <small style="color:#9ca3af;font-size:11px;">Max 2MB. Formats: JPEG, PNG, JPG, GIF</small>
                                    @error('customer_profile_picture') <div class="text-danger" style="font-size:12px;color:#ef4444;">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <button type="submit" class="btn-secondary-dash">
                                <i class="fa-solid fa-upload"></i> Upload Picture
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Profile Information -->
                <div class="dash-card">
                    <div class="dash-card-header">
                        <span class="dash-card-title"><i class="fa-solid fa-user" style="color:#3b82f6;margin-right:6px;"></i> Profile Information</span>
                    </div>
                    <div class="dash-card-body padded">
                        <form method="POST" action="{{url('/update-profile')}}">
                            @csrf
                            <div class="form-row">
                                <div class="form-field">
                                    <label for="customer_name">Full Name</label>
                                    <input type="text" id="customer_name" name="customer_name" value="{{$user->customer_name}}" required>
                                    @error('customer_name') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-field">
                                    <label for="customer_email">Email Address</label>
                                    <input type="email" id="customer_email" name="customer_email" value="{{$user->customer_email}}" required>
                                    @error('customer_email') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="form-row single">
                                <div class="form-field">
                                    <label for="customer_phone_number">Phone Number</label>
                                    <input type="tel" id="customer_phone_number" name="customer_phone_number" value="{{$user->customer_phone_number ?? ''}}">
                                    @error('customer_phone_number') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <button type="submit" class="btn-primary-dash">
                                <i class="fa-solid fa-save"></i> Save Changes
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script src="{{asset('assets/front/js/jquery-3.6.3.min.js')}}"></script>
<script src="{{asset('assets/front/js/bootstrap.min.js')}}"></script>
<script>
    // Tab switching
    function switchTab(tabName) {
        document.querySelectorAll('.dash-tab-content').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('.dash-nav-item[data-tab]').forEach(n => n.classList.remove('active'));
        document.getElementById('tab-' + tabName).classList.add('active');
        document.querySelector('.dash-nav-item[data-tab="' + tabName + '"]').classList.add('active');
        // Update topbar title
        var titles = { orders: 'Orders', maps: 'My Maps', settings: 'Settings' };
        document.getElementById('topbarTitle').textContent = titles[tabName] || tabName;
        // Close mobile sidebar
        document.getElementById('dashSidebar').classList.remove('open');
        document.getElementById('sidebarOverlay').classList.remove('show');
    }

    document.querySelectorAll('.dash-nav-item[data-tab]').forEach(function(btn) {
        btn.addEventListener('click', function() {
            switchTab(this.getAttribute('data-tab'));
        });
    });

    // User dropdown toggle
    document.getElementById('userMenu').addEventListener('click', function(e) {
        e.stopPropagation();
        document.getElementById('userDropdown').classList.toggle('show');
    });
    document.addEventListener('click', function() {
        document.getElementById('userDropdown').classList.remove('show');
    });

    // Mobile sidebar toggle
    document.getElementById('menuToggle').addEventListener('click', function() {
        document.getElementById('dashSidebar').classList.toggle('open');
        document.getElementById('sidebarOverlay').classList.toggle('show');
    });
    document.getElementById('sidebarOverlay').addEventListener('click', function() {
        document.getElementById('dashSidebar').classList.remove('open');
        this.classList.remove('show');
    });

    // Profile image preview
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function() {
            document.getElementById('profilePreview').src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
</body>
</html>