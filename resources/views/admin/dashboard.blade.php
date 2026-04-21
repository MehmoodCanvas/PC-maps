<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - PC Maps</title>
    <link rel="stylesheet" href="{{asset('assets/front/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.css" />
    <link rel="stylesheet" href="{{asset('assets/admin-theme.css')}}" />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            background: #f5f7fa;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }
        .admin-wrapper {
            display: flex;
            min-height: 100vh;
        }
        .admin-sidebar {
            width: 260px;
            background: #1e293b;
            color: white;
            padding: 30px 20px;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            box-shadow: 2px 0 8px rgba(0,0,0,0.15);
        }
        .admin-sidebar .logo {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 40px;
            text-align: center;
            padding: 15px 0;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            letter-spacing: 0.5px;
        }
        .admin-sidebar .nav-menu {
            list-style: none;
        }
        .admin-sidebar .nav-menu li {
            margin-bottom: 8px;
        }
        .admin-sidebar .nav-menu a {
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            padding: 12px 15px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.3s;
            font-size: 14px;
            font-weight: 500;
        }
        .admin-sidebar .nav-menu a:hover {
            background: rgba(59, 130, 246, 0.2);
            color: #3b82f6;
        }
        .admin-sidebar .nav-menu a.active {
            background: #3b82f6;
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
            padding: 25px 30px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .admin-header h1 {
            font-size: 24px;
            font-weight: 600;
            color: #1e293b;
            margin: 0;
        }
        .admin-user-menu {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .admin-user-menu span {
            color: #64748b;
            font-size: 14px;
        }
        .admin-user-menu a {
            color: #3b82f6;
            text-decoration: none;
            font-weight: 500;
            padding: 8px 16px;
            border-radius: 6px;
            transition: background 0.3s;
            font-size: 14px;
        }
        .admin-user-menu a:hover {
            background: #f1f5f9;
        }
        .admin-content {
            flex: 1;
            padding: 30px;
        }
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            border-top: 4px solid #3b82f6;
            transition: all 0.3s;
        }
        .stat-card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            transform: translateY(-2px);
        }
        .stat-card.success {
            border-top-color: #10b981;
        }
        .stat-card.warning {
            border-top-color: #f59e0b;
        }
        .stat-card.danger {
            border-top-color: #ef4444;
        }
        .stat-card h3 {
            color: #64748b;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .stat-card .value {
            font-size: 32px;
            font-weight: 700;
            color: #1e293b;
        }
        .stat-card.success .value {
            color: #10b981;
        }
        .stat-card.warning .value {
            color: #f59e0b;
        }
        .stat-card.danger .value {
            color: #ef4444;
        }
        .stat-card .icon {
            float: right;
            font-size: 40px;
            opacity: 0.1;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .content-section {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .content-section h2 {
            color: #1e293b;
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e2e8f0;
        }
        .table {
            margin-bottom: 0;
        }
        .table thead th {
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            color: #1e293b;
            font-weight: 600;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .btn {
            padding: 8px 15px;
            font-size: 12px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 500;
        }
        .btn-sm {
            padding: 4px 8px;
            font-size: 11px;
        }
        .btn-primary {
            background: #3b82f6;
            color: white;
            border: none;
        }
        .btn-primary:hover {
            background: #2563eb;
            color: white;
        }
        .btn-success {
            background: #10b981;
            color: white;
            border: none;
        }
        .btn-danger {
            background: #dc3545;
            color: white;
            border: none;
        }
        .badge {
            padding: 6px 10px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
        }
        .badge.bg-success {
            background: #d4edda;
            color: #155724;
        }
        .badge.bg-warning {
            background: #fff3cd;
            color: #856404;
        }
        .badge.bg-danger {
            background: #f8d7da;
            color: #721c24;
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
                <h1><i class="fas fa-chart-line"></i> Dashboard</h1>
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

                <!-- Stats -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <i class="fas fa-users icon"></i>
                        <h3>Total Customers</h3>
                        <div class="value">{{$totalCustomers}}</div>
                    </div>
                    <div class="stat-card success">
                        <i class="fas fa-shopping-cart icon"></i>
                        <h3>Total Orders</h3>
                        <div class="value">{{$totalOrders}}</div>
                    </div>
                    <div class="stat-card warning">
                        <i class="fas fa-clock icon"></i>
                        <h3>Pending Orders</h3>
                        <div class="value">{{$pendingOrders}}</div>
                    </div>
                    <div class="stat-card danger">
                        <i class="fas fa-dollar-sign icon"></i>
                        <h3>Total Revenue</h3>
                        <div class="value">${{number_format($totalRevenue, 2)}}</div>
                    </div>
                </div>

                <!-- Order Status Breakdown -->
                <div class="content-section">
                    <h2><i class="fas fa-pie-chart"></i> Order Status Breakdown</h2>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px;">
                        @foreach($orderStatusBreakdown as $status)
                        <div style="background: #f8f9fa; padding: 15px; border-radius: 6px; text-align: center;">
                            <div style="font-size: 24px; font-weight: 700; color: #3b82f6;">{{$status->count}}</div>
                            <div style="color: #666; font-size: 13px;">{{$status->order_status}}</div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Recent Orders -->
                <div class="content-section">
                    <h2><i class="fas fa-list"></i> Recent Orders</h2>
                    <table class="table" id="recentOrdersTable">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                 <th>Customer</th>
                                 <th>Location</th>
                                 <th>Map Size</th>
                                 <th>Amount</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentOrders as $order)
                            <tr>
                                <td><strong>{{$order->order_invoice_id}}</strong></td>
                                 <td>{{$order->customer_name}}</td>
                                 <td>
                                     <div style="font-size: 0.8rem;">
                                         @if($order->map_lat && $order->map_lng)
                                             <a href="https://www.google.com/maps?q={{$order->map_lat}},{{$order->map_lng}}" target="_blank">
                                                 <i class="fas fa-map-marker-alt text-danger"></i> {{round($order->map_lat, 3)}}, {{round($order->map_lng, 3)}}
                                             </a>
                                         @else
                                             <span class="text-muted">N/A</span>
                                         @endif
                                     </div>
                                 </td>
                                 <td>{{$order->map_width}}" × {{$order->map_height}}"</td>
                                <td>${{number_format($order->order_total_amount, 2)}}</td>
                                <td>
                                    @if($order->order_status === 'Completed')
                                    <span class="badge bg-success">{{$order->order_status}}</span>
                                    @elseif($order->order_status === 'Approved')
                                    <span class="badge" style="background: #d1ecf1; color: #0c5460;">{{$order->order_status}}</span>
                                    @elseif($order->order_status === 'Pending')
                                    <span class="badge bg-warning">{{$order->order_status}}</span>
                                    @else
                                    <span class="badge bg-danger">{{$order->order_status}}</span>
                                    @endif
                                </td>
                                <td>{{\Carbon\Carbon::parse($order->created_at)->format('M d, Y')}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>

    <script src="{{asset('assets/front/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('assets/front/js/jquery-3.6.3.min.js')}}"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
    <script>
        new DataTable('#recentOrdersTable', {
            columnDefs: [
                { targets: 0, searchable: true, orderable: true },
                { targets: 1, searchable: true, orderable: true },
                { targets: 2, searchable: false, orderable: false },
                { targets: 3, searchable: false, orderable: false },
                { targets: 4, type: 'num-fmt', searchable: false, orderable: true },
                { targets: 5, searchable: true, orderable: false },
                { targets: 6, type: 'date', searchable: false, orderable: true }
            ],
            order: [[6, 'desc']],
            pageLength: 10
        });
    </script>
</body>
</html>
