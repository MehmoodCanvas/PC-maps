
@include('front.inc.header2')

<section class="register_first_wrapper">
    <div class="register_left_col">
        <div class="register_first_wrap_img">
            <img src="assets/front/images/register_img.jpg" class="img-fluid" alt="">
        </div>
    </div>
    <div class="register_right_col">
        <form action="" class="w-100">
            <div class="register_first_wrap_text">
                <img src="assets/front/images/logo.png" class="img-fluid" alt="">
                <h3>Sign Up</h3>
                <p>Create Your Account PcMaps</p>
            </div>
            <div class="register_first_wrap_input">
                <label>User Name</label>
                <input type="text" placeholder="Enter Your Username">
            </div>
            <div class="register_first_wrap_input">
                <label>Email Address</label>
                <input type="text" placeholder="Enter Your Email Address">
            </div>
            <div class="register_first_wrap_input">
                <label>Phone Number</label>
                <input type="text" placeholder="Enter Your Phone Number">
            </div>
            <div class="register_first_wrap_input">
                <label>Password</label>
                <input type="password" id="password-field" placeholder="Enter Your Password">
                <span toggle="#password-field" class="fa fa-fw field-icon toggle-password fa-eye"></span>
            </div>
            <div class="register_first_wrap_input">
                <label>Confirm Password</label>
                <input type="password" id="password-field2" placeholder="Enter Your Confirm Password">
                <span toggle="#password-field2" class="fa fa-fw field-icon toggle-password fa-eye"></span>
            </div>
            <div class="register_first_wrap_btn">
                <button type="submit" class="common_btn_dark w-100 justify-content-center">Sign Up Now<i class="fa-solid fa-arrow-right"></i></button>
            </div>
            <div class="register_first_wrap_bottom_text">
                <p>Already have an account? <a href="login.php">Login</a></p>
            </div>
        </form>
    </div>
</section>


@include('front.inc.footer2')