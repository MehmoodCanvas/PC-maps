<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pricing Settings - Admin Panel</title>
    <link rel="stylesheet" href="{{asset('public/assets/front/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="{{asset('public/assets/admin-theme.css')}}" />
    <style>
        .content-section {
            max-width: 800px;
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
        .pricing-info {
            background: #eff6ff;
            border-left: 4px solid #3b82f6;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 13px;
            color: #1e40af;
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
                        <i class="fas fa-info-circle"></i> <strong>Pricing Formula:</strong> Total = (Length × Width × Map Multiplier) + Addons + Frame Cost<br>
                        Frame Cost = Perimeter (2 × (Width + Height)) × Cost Per Inch × Style Multiplier
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

                        <!-- Map Pricing Section -->
                        <div class="form-section">
                            <h3><i class="fas fa-map"></i> Map Base Pricing</h3>
                            <div class="form-group">
                                <label for="map_multiplier">Map Multiplier</label>
                                <input type="number" id="map_multiplier" name="map_multiplier" step="0.01" min="0.01" value="{{$pricingSettings['map_multiplier'] ?? 0.8}}" required>
                                <small>Multiplier for (Length × Width) to determine base map cost (e.g. 0.8)</small>
                            </div>
                        </div>

                        <!-- Frame Pricing Section -->
                        <div class="form-section" style="border-left-color: #f59e0b;">
                            <h3><i class="fas fa-image"></i> Frame Pricing</h3>
                            <div class="pricing-info" style="background: #fffbeb; border-left-color: #f59e0b; color: #92400e;">
                                <i class="fas fa-info-circle"></i> <strong>Frame Cost Formula:</strong> Perimeter (2 × (Width + Height)) × Cost Per Inch × Style Multiplier
                            </div>

                            <div class="form-group">
                                <label for="frame_cost_per_inch"><i class="fas fa-ruler"></i> Frame Cost Per Inch ($)</label>
                                <input type="number" id="frame_cost_per_inch" name="frame_cost_per_inch" step="0.01" min="0" value="{{$pricingSettings['frame_cost_per_inch']}}" required>
                                <small>Base cost per inch of frame perimeter</small>
                            </div>

                            <hr style="border-color: #e5e7eb; margin: 20px 0;">
                            <h4 style="font-size: 14px; color: #374151; margin-bottom: 15px;"><i class="fas fa-palette"></i> Frame Style Multipliers (From public/frames)</h4>
                            <p style="font-size: 12px; color: #6b7280; margin-bottom: 15px;">These multipliers are applied to the base cost per inch. For example, 1.0 = base price, 1.5 = 50% more, 2.0 = double.</p>

                            @foreach($frameMultipliers as $frameFile => $multiplier)
                            <div class="form-group">
                                <label for="frame_{{str_replace(' ', '_', $frameFile)}}">{{str_replace(' background removed.png', '', $frameFile)}} Multiplier</label>
                                <input type="number" id="frame_{{str_replace(' ', '_', $frameFile)}}" name="frame_multipliers[{{$frameFile}}]" step="0.01" min="0" value="{{ $multiplier }}" required>
                                <small>Multiplier for frame file: {{ $frameFile }}</small>
                            </div>
                            @endforeach

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

    <script src="{{asset('public/assets/front/js/bootstrap.min.js')}}"></script>
</body>
</html>
