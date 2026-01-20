<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pricing Settings - Admin Panel</title>
    <link rel="stylesheet" href="{{asset('assets/front/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="{{asset('assets/admin-theme.css')}}" />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            background: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .admin-wrapper {
            display: flex;
            min-height: 100vh;
        }
        .admin-sidebar {
            width: 260px;
            background: linear-gradient(135deg, #0066cc 0%, #003d99 100%);
            color: white;
            padding: 30px 20px;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
        }
        .admin-sidebar .logo {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 40px;
            text-align: center;
            padding: 15px 0;
            border-bottom: 2px solid rgba(255,255,255,0.1);
        }
        .admin-sidebar .nav-menu {
            list-style: none;
        }
        .admin-sidebar .nav-menu li {
            margin-bottom: 10px;
        }
        .admin-sidebar .nav-menu a {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            padding: 12px 15px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.3s;
            font-size: 14px;
            font-weight: 500;
        }
        .admin-sidebar .nav-menu a:hover,
        .admin-sidebar .nav-menu a.active {
            background: rgba(255,255,255,0.15);
            color: white;
        }
        .admin-sidebar .nav-menu i {
            width: 20px;
            text-align: center;
        }
        .admin-main {
            margin-left: 260px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        .admin-header {
            background: white;
            padding: 20px 30px;
            border-bottom: 1px solid #e0e0e0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .admin-header h1 {
            font-size: 24px;
            font-weight: 600;
            color: #333;
            margin: 0;
        }
        .admin-user-menu {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .admin-user-menu a {
            color: #0066cc;
            text-decoration: none;
            font-weight: 500;
        }
        .admin-content {
            flex: 1;
            padding: 30px;
        }
        .content-section {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            max-width: 800px;
        }
        .content-section h2 {
            color: #333;
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
        }
        .form-section {
            margin-bottom: 40px;
        }
        .form-section h3 {
            color: #555;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 15px;
            padding-left: 15px;
            border-left: 4px solid #0066cc;
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
            padding: 10px;
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
        .form-group small {
            display: block;
            margin-top: 6px;
            color: #999;
            font-size: 12px;
        }
        .button-group {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #f0f0f0;
        }
        .btn {
            padding: 10px 25px;
            font-size: 14px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
        }
        .btn-primary {
            background: #0066cc;
            color: white;
        }
        .btn-primary:hover {
            background: #0052a3;
        }
        .btn-secondary {
            background: #f0f0f0;
            color: #333;
        }
        .btn-secondary:hover {
            background: #e0e0e0;
        }
        .alert {
            margin-bottom: 20px;
        }
        .pricing-info {
            background: #f0f7ff;
            border-left: 4px solid #0066cc;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 13px;
            color: #0066cc;
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
                <h1><i class="fas fa-dollar-sign"></i> Pricing Settings</h1>
                <div class="admin-user-menu">
                    <span><i class="fas fa-user-circle"></i> Admin</span>
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

                <div class="content-section">
                    <h2><i class="fas fa-cog"></i> Configure Pricing</h2>

                    <div class="pricing-info">
                        <i class="fas fa-info-circle"></i> <strong>Pricing Formula:</strong> Total = (Frame Price × Base Multiplier) + Addons<br>
                        Frame Price = ((Height × Width × Width Multiplier) × DPI Multiplier + Base Addition) × Scale Multiplier + Base Price
                    </div>

                    <form method="POST" action="{{url('/admin/pricing')}}">
                        @csrf

                        <!-- Addon Pricing Section -->
                        <div class="form-section">
                            <h3><i class="fas fa-plus"></i> Add-on Pricing</h3>

                            <div class="form-group">
                                <label for="text_addon"><i class="fas fa-text-width"></i> Text Add-on Price ($)</label>
                                <input type="number" id="text_addon" name="text_addon" step="0.01" value="{{$pricingSettings['text_addon']}}" required>
                                <small>Price for adding text elements to maps</small>
                            </div>

                            <div class="form-group">
                                <label for="compass_addon"><i class="fas fa-compass"></i> Compass Add-on Price ($)</label>
                                <input type="number" id="compass_addon" name="compass_addon" step="0.01" value="{{$pricingSettings['compass_addon']}}" required>
                                <small>Price for adding compass element to maps</small>
                            </div>

                            <div class="form-group">
                                <label for="addons_addon"><i class="fas fa-gift"></i> Premium Add-ons Price ($)</label>
                                <input type="number" id="addons_addon" name="addons_addon" step="0.01" value="{{$pricingSettings['addons_addon']}}" required>
                                <small>Price for additional premium features</small>
                            </div>
                        </div>

                        <!-- Base Pricing Section -->
                        <div class="form-section">
                            <h3><i class="fas fa-layer-group"></i> Base Pricing Configuration</h3>

                            <div class="form-group">
                                <label for="base_price"><i class="fas fa-dollar-sign"></i> Base Price ($)</label>
                                <input type="number" id="base_price" name="base_price" step="0.01" value="{{$pricingSettings['base_price']}}" required>
                                <small>Fixed base price component</small>
                            </div>

                            <div class="form-group">
                                <label for="base_addition"><i class="fas fa-plus-circle"></i> Base Addition ($)</label>
                                <input type="number" id="base_addition" name="base_addition" step="0.01" value="{{$pricingSettings['base_addition']}}" required>
                                <small>Addition to frame calculation before multipliers</small>
                            </div>

                            <div class="form-group">
                                <label for="base_multiplier">Base Multiplier</label>
                                <input type="number" id="base_multiplier" name="base_multiplier" step="0.01" min="0.01" value="{{$pricingSettings['base_multiplier']}}" required>
                                <small>Final multiplier applied to frame price (typically 0.70)</small>
                            </div>
                        </div>

                        <!-- Calculation Multipliers -->
                        <div class="form-section">
                            <h3><i class="fas fa-calculator"></i> Calculation Multipliers</h3>

                            <div class="form-group">
                                <label for="width_multiplier">Width Multiplier</label>
                                <input type="number" id="width_multiplier" name="width_multiplier" step="0.01" min="0.01" value="{{$pricingSettings['width_multiplier']}}" required>
                                <small>Multiplier for map width in frame calculation</small>
                            </div>

                            <div class="form-group">
                                <label for="dpi_multiplier">DPI Multiplier</label>
                                <input type="number" id="dpi_multiplier" name="dpi_multiplier" step="0.01" min="0.01" value="{{$pricingSettings['dpi_multiplier']}}" required>
                                <small>Multiplier for DPI-related calculations</small>
                            </div>

                            <div class="form-group">
                                <label for="scale_multiplier">Scale Multiplier</label>
                                <input type="number" id="scale_multiplier" name="scale_multiplier" step="0.01" min="0.01" value="{{$pricingSettings['scale_multiplier']}}" required>
                                <small>Final scale multiplier applied to frame</small>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="button-group">
                            <button type="reset" class="btn btn-secondary">Reset</button>
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Settings</button>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>

    <script src="{{asset('assets/front/js/bootstrap.min.js')}}"></script>
</body>
</html>
