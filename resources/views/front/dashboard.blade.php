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
                        <img src="{{asset('assets/front/images/d-user-icon.png')}}" class="img-fluid" alt="">
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
                <div class="tab-pane fade show active" id="v-pills-Management" role="tabpanel" aria-labelledby="v-pills-Management-tab">
                    <div class="d_content_main_wrap">
                        <div class="d_content_main_heading">
                            <h6>Orders</h6>
                        </div>
                        <div class="d_content_main_box_table_m">
                            <table class="table" id="myTable">
                                <thead>
                                    <tr>
                                        <th scope="col">Profile</th>
                                        <th scope="col">Designation</th>
                                        <th scope="col">Date</th>
                                        <th scope="col">Stars</th>
                                        <th scope="col">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th scope="row">Elsa Robert</th>
                                        <td>House Cleaning</td>
                                        <td>mm/dd/yyyy</td>
                                        <td>
                                            <ul>
                                                <li><i class="fa-solid fa-star"></i></li>
                                                <li><i class="fa-solid fa-star"></i></li>
                                                <li><i class="fa-solid fa-star"></i></li>
                                                <li><i class="fa-solid fa-star"></i></li>
                                                <li><i class="fa-solid fa-star"></i></li>
                                            </ul>
                                        </td>
                                        <td><a href="#!" class="yellowp">Pending</a></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Will Smith</th>
                                        <td>Associate Engineer</td>
                                        <td>mm/dd/yyyy</td>
                                        <td>
                                            <ul>
                                                <li><i class="fa-solid fa-star"></i></li>
                                                <li><i class="fa-solid fa-star"></i></li>
                                                <li><i class="fa-solid fa-star"></i></li>
                                                <li><i class="fa-solid fa-star"></i></li>
                                                <li><i class="fa-solid fa-star"></i></li>
                                            </ul>
                                        </td>
                                        <td><a href="#!" class="yellowp">Pending</a></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">George Andrew</th>
                                        <td>Plumber</td>
                                        <td>mm/dd/yyyy</td>
                                        <td>
                                            <ul>
                                                <li><i class="fa-solid fa-star"></i></li>
                                                <li><i class="fa-solid fa-star"></i></li>
                                                <li><i class="fa-solid fa-star"></i></li>
                                                <li><i class="fa-solid fa-star"></i></li>
                                                <li><i class="fa-solid fa-star"></i></li>
                                            </ul>
                                        </td>
                                        <td><a href="#!" class="yellowp">Pending</a></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">John Albert</th>
                                        <td>Design</td>
                                        <td>mm/dd/yyyy</td>
                                        <td>
                                            <ul>
                                                <li><i class="fa-solid fa-star"></i></li>
                                                <li><i class="fa-solid fa-star"></i></li>
                                                <li><i class="fa-solid fa-star"></i></li>
                                                <li><i class="fa-solid fa-star"></i></li>
                                                <li><i class="fa-solid fa-star"></i></li>
                                            </ul>
                                        </td>
                                        <td><a href="#!" class="greenp">Delivered</a></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Harry Lington</th>
                                        <td>Administrative</td>
                                        <td>mm/dd/yyyy</td>
                                        <td>
                                            <ul>
                                                <li><i class="fa-solid fa-star"></i></li>
                                                <li><i class="fa-solid fa-star"></i></li>
                                                <li><i class="fa-solid fa-star"></i></li>
                                                <li><i class="fa-solid fa-star"></i></li>
                                                <li><i class="fa-solid fa-star"></i></li>
                                            </ul>
                                        </td>
                                        <td><a href="#!" class="yellowp">Pending</a></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Will Smith</th>
                                        <td>Handyman</td>
                                        <td>mm/dd/yyyy</td>
                                        <td>
                                            <ul>
                                                <li><i class="fa-solid fa-star"></i></li>
                                                <li><i class="fa-solid fa-star"></i></li>
                                                <li><i class="fa-solid fa-star"></i></li>
                                                <li><i class="fa-solid fa-star"></i></li>
                                                <li><i class="fa-solid fa-star"></i></li>
                                            </ul>
                                        </td>
                                        <td><a href="#!" class="yellowp">Pending</a></td>
                                    </tr>
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
                                        <th scope="col">Widhth</th>
                                        <th scope="col">Height</th>
                                        <th scope="col">Price</th>
                                        <th scope="col">Action</th>

                            
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($maps as $map)
                                        
                                    <tr>
                                        <th scope="row"><img src="{{$map->map_data}}" alt=""></th>
                                        <td>{{$map->map_width}}</td>
                                        <td>{{$map->map_height}}</td>
                                        <td>${{$map->map_price}}</td>
                                        <td><a href="{{url('checkout?id='.$map->map_id)}}">Buy Map</a></td>
                                       
                                    </tr>
                                    @endforeach

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
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 col-xxl-6">
                                    <div class="d_content_main_form_inputs">
                                        <label for="">First Name</label>
                                        <input type="text" placeholder="Enter First Name">
                                    </div>
                                </div>
                                <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 col-xxl-6">
                                    <div class="d_content_main_form_inputs">
                                        <label for="">Last Name</label>
                                        <input type="email" placeholder="Enter Last Name">
                                    </div>
                                </div>
                                <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 col-xxl-6">
                                    <div class="d_content_main_form_inputs">
                                        <label for="">Email Address</label>
                                        <input type="email" placeholder="Enter Email Address">
                                    </div>
                                </div>
                                <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 col-xxl-6">
                                    <div class="d_content_main_form_inputs">
                                        <label for="">Phone No</label>
                                        <input type="tel" placeholder="Enter Phone No">
                                    </div>
                                </div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 col-xxl-12">
                                    <div class="d_content_main_form_inputs">
                                        <button class="common_btn_dark">Submit <i class="fa-solid fa-arrow-right"></i></button>
                                    </div>
                                </div>
                            </div>
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
    let table = new DataTable('#myTable', {
        // options
    });
</script>
<script>

    let table1= new DataTable('#myTable1', {
        // options
    });

</script>

<script>
    $(".dashboard-main-th-user-bell").click(function(){
    $(".navDropNoti").slideToggle();
    });
    $(".dashboard-main-th-user-f").click(function(){
    $(".d-logout-Drop").slideToggle();
    });
</script>

</body>
</html>