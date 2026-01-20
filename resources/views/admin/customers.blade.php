<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customers - Admin Panel</title>
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
            padding: 25px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        .content-section h2 {
            color: #333;
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
        }
        .table thead th {
            background: #f8f9fa;
            border-bottom: 2px solid #ddd;
            color: #333;
            font-weight: 600;
        }
        .btn {
            padding: 8px 15px;
            font-size: 12px;
            border-radius: 4px;
            text-decoration: none;
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
                <h1><i class="fas fa-users"></i> Customers</h1>
                <div class="admin-user-menu">
                    <span><i class="fas fa-user-circle"></i> Admin</span>
                    <a href="{{url('/admin/logout')}}">Logout</a>
                </div>
            </header>

            <!-- Content -->
            <main class="admin-content">
                <div class="content-section">
                    <table class="table" id="customersTable">
                        <thead>
                            <tr>
                                <th>Customer ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Total Orders</th>
                                <th>Joined Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($customers as $customer)
                            <tr>
                                <td>#{{$customer->customer_id}}</td>
                                <td>{{$customer->customer_name}}</td>
                                <td>{{$customer->customer_email}}</td>
                                <td>{{$customer->customer_phone_number ?? 'N/A'}}</td>
                                <td><span class="badge bg-primary" style="background: #0066cc !important; color: white;">{{$customer->total_orders}}</span></td>
                                <td>{{\Carbon\Carbon::parse($customer->created_at)->format('M d, Y')}}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">No customers found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    @if($customers->hasPages())
                    <div style="margin-top: 20px;">
                        {{$customers->links()}}
                    </div>
                    @endif
                </div>
            </main>
        </div>
    </div>

    <script src="{{asset('assets/front/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('assets/front/js/jquery-3.6.3.min.js')}}"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
    <script>
        new DataTable('#customersTable', {
            columnDefs: [
                { targets: 0, searchable: false, orderable: true },
                { targets: 1, searchable: true, orderable: true },
                { targets: 2, searchable: true, orderable: true },
                { targets: 3, searchable: false, orderable: false },
                { targets: 4, type: 'num-fmt', searchable: false, orderable: true },
                { targets: 5, type: 'date', searchable: false, orderable: true }
            ],
            order: [[0, 'desc']],
            pageLength: 25
        });
    </script>
</body>
</html>
