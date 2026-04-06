<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders Management - Admin Panel</title>
    <link rel="stylesheet" href="{{asset('assets/front/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.css" />
    <link rel="stylesheet" href="{{asset('assets/admin-theme.css')}}" />
    <style>
       
        .order-status-form {
            display: flex;
            gap: 5px;
        }
        .order-status-form select {
            padding: 4px 8px;
            border-radius: 4px;
            border: 1px solid #e2e8f0;
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
                <h1><i class="fas fa-shopping-cart"></i> Orders Management</h1>
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
                    <table class="table" id="ordersTable">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Map Size</th>
                                <th>Frame</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Action</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                            <tr>
                                <td><strong>{{$order->order_invoice_id}}</strong></td>
                                <td>
                                    <div>{{$order->customer_name}}</div>
                                    <small style="color: #999;">{{$order->customer_email}}</small>
                                </td>
                                <td>{{$order->map_width}}" × {{$order->map_height}}"</td>
                                <td>
                                    @if($order->map_frame && $order->map_frame !== 'none')
                                        <span class="badge bg-secondary">{{str_replace(' background removed.png', '', $order->map_frame)}}</span>
                                    @else
                                        <small style="color: #ccc;">None</small>
                                    @endif
                                </td>
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
                                <td>
                                    <form method="POST" action="{{url('/admin/orders/'.$order->order_id.'/status')}}" class="status-form">
                                        @csrf
                                        <select name="order_status" class="form-control" style="width: 120px; padding: 4px 8px; font-size: 12px;">
                                            <option value="Pending" {{$order->order_status === 'Pending' ? 'selected' : ''}}>Pending</option>
                                            <option value="Approved" {{$order->order_status === 'Approved' ? 'selected' : ''}}>Approved</option>
                                            <option value="Completed" {{$order->order_status === 'Completed' ? 'selected' : ''}}>Completed</option>
                                            <option value="Cancelled" {{$order->order_status === 'Cancelled' ? 'selected' : ''}}>Cancelled</option>
                                        </select>
                                        <button type="submit" class="btn btn-primary">Update</button>
                                    </form>
                                </td>
                                <td>{{\Carbon\Carbon::parse($order->created_at)->format('M d, Y')}}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">No orders found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    @if($orders->hasPages())
                    <div style="margin-top: 20px;">
                        {{$orders->links()}}
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
        new DataTable('#ordersTable', {
            columnDefs: [
                { targets: 0, searchable: true, orderable: true },
                { targets: 1, searchable: true, orderable: true },
                { targets: 2, searchable: false, orderable: false },
                { targets: 3, type: 'num-fmt', searchable: false, orderable: true },
                { targets: 4, searchable: true, orderable: false },
                { targets: 5, orderable: false, searchable: false },
                { targets: 6, type: 'date', searchable: false, orderable: true }
            ],
            order: [[6, 'desc']],
            pageLength: 25
        });
    </script>
</body>
</html>
