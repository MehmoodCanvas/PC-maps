<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>PC Maps</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0,user-scalable=no">
    <meta name="robots" content="noindex" />
	<link rel="icon" href="{{asset('assets/front/images/favicon.ico')}}">
    <link rel="stylesheet" href="{{asset('assets/front/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/front/css/swiper-bundle.min.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/front/css/all.css')}}">
    <link rel="stylesheet" href="{{asset('assets/front/css/main.css')}}">
    <link rel="stylesheet" href="{{asset('assets/front/css/responsive.css')}}">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        .profile-picture-preview {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 10px;
            border: 2px solid #ddd;
        }
        .profile-upload-section {
            margin-bottom: 30px;
            padding: 20px;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
        }
    </style>
</head>
<body class="d_body">
    <header class="header_main_wrapper">
        <div class="header_main_wrap_f">
            <div class="header_main_wrap_logo">
                <img src="{{asset('assets/front/images/logo.png')}}" class="img-fluid" alt="">
            </div>
            <div class="dashboard-main-th-userm-f">
                <div class="dashboard-main-th-user-f">
                    <div class="dashboard-main-th-user-img">
                        @if($user->customer_profile_picture && Storage::exists('public/' . $user->customer_profile_picture))
                            <img src="{{asset('storage/' . $user->customer_profile_picture)}}" class="img-fluid rounded-circle" alt="Profile" style="object-fit: cover; width: 50px; height: 50px;">
                        @else
                            <img src="{{asset('assets/front/images/d-user-icon.png')}}" class="img-fluid" alt="">
                        @endif
                    </div>
                    <div class="dashboard-main-th-user-name">
                        <p>{{Auth::guard('customer')->user()->customer_name}} <i class="fa-solid fa-chevron-down"></i></p>
                    </div>
                    <div class="d-logout-Drop">
                        <a href="{{url('logout')}}" data-bs-toggle="modal" data-bs-target="#logOut"><img src="{{asset("assets/front/images/logout-ico.png")}}" class="img-fluid" alt=""> Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <section class="dashboard_main_wrap">
        <div class="dashboard_side_bar">
            <div class="d-flex align-items-start">
                <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <button class="nav-link active" id="v-pills-Management-tab" data-bs-toggle="pill" data-bs-target="#v-pills-Management" type="button" role="tab" aria-controls="v-pills-Management" aria-selected="true"><img src="{{asset('assets/front/images/d_ico3.png')}}" class="img-fluid" alt="">Orders</button>
                    <button class="nav-link" id="v-pills-Jobs-tab" data-bs-toggle="pill" data-bs-target="#v-pills-Jobs" type="button" role="tab" aria-controls="v-pills-Jobs" aria-selected="false"><img src="{{asset('assets/front/images/d_ico1.png')}}" class="img-fluid" alt="">My Maps</button>
                    <button class="nav-link" id="v-pills-settings-tab" data-bs-toggle="pill" data-bs-target="#v-pills-settings" type="button" role="tab" aria-controls="v-pills-settings" aria-selected="false"><img src="{{asset('assets/front/images/d_ico5.png')}}" class="img-fluid" alt=""> Settings</button>
                </div>
            </div>
        </div>
        <div class="dashboard_main_box">
            <div class="tab-content" id="v-pills-tabContent">
                @if ($message = Session::get('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success!</strong> {{ $message }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
                @if ($message = Session::get('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error!</strong> {{ $message }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
                <div class="tab-pane fade show active" id="v-pills-Management" role="tabpanel" aria-labelledby="v-pills-Management-tab">
                    <div class="d_content_main_wrap">
                        <div class="d_content_main_heading">
                            <h6>Orders</h6>
                        </div>
                        <div class="d_content_main_box_table_m">
                            <table class="table" id="myTable">
                                <thead>
                                    <tr>
                                        <th scope="col">Order Id</th>
                                        <th scope="col">Map</th>
                                        <th scope="col">Amount</th>
                                        <th scope="col">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($orders as $order)
                                    <tr>
                                        <td>{{$order->order_invoice_id}}</td>
                                        <td><img src="{{$order->map_data}}" alt="{{$order->order_invoice_id}}" class="img-fluid" style="max-height: 80px;"></td>
                                        <td>${{$order->order_total_amount}}</td>
                                        <td>
                                            @php
                                                $statusClass = 'warning';
                                                if($order->order_status === 'completed' || $order->order_status === 'Completed') {
                                                    $statusClass = 'success';
                                                } elseif($order->order_status === 'pending' || $order->order_status === 'Pending') {
                                                    $statusClass = 'warning';
                                                } elseif($order->order_status === 'cancelled' || $order->order_status === 'Cancelled') {
                                                    $statusClass = 'danger';
                                                }
                                            @endphp
                                            <span class="badge bg-{{$statusClass}}">{{ucfirst($order->order_status)}}</span>
                                        </td>
                                    </tr>     
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4">No orders found. <a href="{{url('create-map')}}">Create a map and make your first order</a></td>
                                    </tr>
                                    @endforelse

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="v-pills-Jobs" role="tabpanel" aria-labelledby="v-pills-Jobs-tab">
                    <div class="d_content_main_wrap">
                        <div class="d_content_main_heading">
                            <h6>My Maps</h6>
                            <a href="{{url('create-map')}}" class="common_btn_dark">Add New <i class="fa-solid fa-arrow-right"></i></a>
                        </div>
                        <div class="d_content_main_box_table_m">
                            <table class="table" id="myTable1">
                                <thead>
                                    <tr>
                                        <th scope="col">Map</th>
                                        <th scope="col">Width</th>
                                        <th scope="col">Height</th>
                                        <th scope="col">Price</th>
                                        <th scope="col">Action</th>
                            
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($maps as $map)
                                        
                                    <tr>
                                        <td><img src="{{$map->map_data}}" class="img-fluid" alt="" style="max-height: 80px;"></td>
                                        <td>{{$map->map_width}} in</td>
                                        <td>{{$map->map_height}} in</td>
                                        <td>${{$map->map_price}}</td>
                                        <td><a href="{{url('checkout?id='.$map->map_id)}}" class="btn btn-sm btn-primary">Buy Map</a></td>
                                       
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">No maps found. <a href="{{url('create-map')}}">Create your first map</a></td>
                                    </tr>
                                    @endforelse

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="v-pills-settings" role="tabpanel" aria-labelledby="v-pills-settings-tab">
                    <div class="d_content_main_wrap">
                        <div class="d_content_main_heading">
                            <h6>Settings</h6>
                        </div>
                        <div class="d_content_main_form_wrap">
                            <!-- Profile Picture Upload Section -->
                            <div class="profile-upload-section">
                                <h5 class="mb-4">Profile Picture</h5>
                                <div class="row">
                                    <div class="col-12 col-md-4 mb-3">
                                        <div style="text-align: center;">
                                            @if($user->customer_profile_picture && Storage::exists('public/' . $user->customer_profile_picture))
                                                <img id="profilePreview" src="{{asset('storage/' . $user->customer_profile_picture)}}" alt="Profile Picture" class="profile-picture-preview">
                                            @else
                                                <img id="profilePreview" src="{{asset('assets/front/images/d-user-icon.png')}}" alt="Profile Picture" class="profile-picture-preview">
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-8">
                                        <form method="POST" action="{{url('/upload-profile-picture')}}" enctype="multipart/form-data">
                                            @csrf
                                            <div class="d_content_main_form_inputs">
                                                <label for="customer_profile_picture">Upload New Picture</label>
                                                <input type="file" id="customer_profile_picture" name="customer_profile_picture" accept="image/*" onchange="previewImage(event)">
                                                <small class="text-muted d-block mt-2">Max file size: 2MB. Formats: JPEG, PNG, JPG, GIF</small>
                                                @error('customer_profile_picture') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                            <button type="submit" class="common_btn_dark mt-3">Upload Picture <i class="fa-solid fa-arrow-right"></i></button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Profile Information Form -->
                            <h5 class="mb-4">Profile Information</h5>
                            <form method="POST" action="{{url('/update-profile')}}">
                                @csrf
                                <div class="row">
                                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 col-xxl-6">
                                        <div class="d_content_main_form_inputs">
                                            <label for="customer_name">Full Name</label>
                                            <input type="text" id="customer_name" name="customer_name" placeholder="Enter Full Name" value="{{$user->customer_name}}" required>
                                            @error('customer_name') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 col-xxl-6">
                                        <div class="d_content_main_form_inputs">
                                            <label for="customer_email">Email Address</label>
                                            <input type="email" id="customer_email" name="customer_email" placeholder="Enter Email Address" value="{{$user->customer_email}}" required>
                                            @error('customer_email') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 col-xxl-6">
                                        <div class="d_content_main_form_inputs">
                                            <label for="customer_phone_number">Phone Number</label>
                                            <input type="tel" id="customer_phone_number" name="customer_phone_number" placeholder="Enter Phone Number" value="{{$user->customer_phone_number ?? ''}}">
                                            @error('customer_phone_number') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 col-xxl-12">
                                        <div class="d_content_main_form_inputs">
                                            <button type="submit" class="common_btn_dark">Update Profile <i class="fa-solid fa-arrow-right"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="footer-wrapper">
    
    </footer>


<script src="{{asset('assets/front/js/bootstrap.min.js')}}"></script>
<script src="{{asset('assets/front/js/jquery-3.6.3.min.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
<script src="{{asset('assets/front/js/swiper-bundle.min.js')}}"></script>
<script src="{{asset('assets/front/js/custom.js')}}"></script>

<script>
    // Preview image before upload
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function() {
            const preview = document.getElementById('profilePreview');
            preview.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }

    // Initialize DataTables with proper column definitions
    let table = new DataTable('#myTable', {
        columnDefs: [
            { targets: 0, type: 'string', searchable: true, orderable: true },
            { targets: 1, orderable: false, searchable: false },
            { targets: 2, type: 'num-fmt', searchable: true, orderable: true },
            { targets: 3, searchable: true, orderable: false }
        ],
        order: [[0, 'desc']],
        dom: 'frtip'
    });

    let table1 = new DataTable('#myTable1', {
        columnDefs: [
            { targets: 0, orderable: false, searchable: false },
            { targets: 1, type: 'num-fmt', searchable: false, orderable: true },
            { targets: 2, type: 'num-fmt', searchable: false, orderable: true },
            { targets: 3, type: 'num-fmt', searchable: true, orderable: true },
            { targets: 4, orderable: false, searchable: false }
        ],
        order: [[0, 'desc']],
        dom: 'frtip'
    });

    $(".dashboard-main-th-user-bell").click(function(){
        $(".navDropNoti").slideToggle();
    });
    $(".dashboard-main-th-user-f").click(function(){
        $(".d-logout-Drop").slideToggle();
    });
</script>

</body>
</html>