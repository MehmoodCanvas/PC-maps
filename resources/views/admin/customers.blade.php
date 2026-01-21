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
                                <td><span class="badge bg-primary" style="background: #3b82f6 !important; color: white;">{{$customer->total_orders}}</span></td>
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
