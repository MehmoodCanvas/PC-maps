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
                <h3>Login</h3>
                <p>Fill the fields to get into your account</p>
            </div>
            <div class="register_first_wrap_input">
                <label>Email Address</label>
                <input type="text" placeholder="Enter Your Email Address">
            </div>
            <div class="register_first_wrap_input">
                <label>Password</label>
                <input type="password" id="password-field" placeholder="Enter Your Password">
                <span toggle="#password-field" class="fa fa-fw field-icon toggle-password fa-eye"></span>
            </div>
            <div class="register_first_wrap_flex">
                <div class="register_first_wrap_checkbox">
                    <input type="checkbox" id="cbx" style="display: none;">
                    <label for="cbx" class="check">
                        <svg width="18px" height="18px" viewBox="0 0 18 18">
                            <path d="M1,9 L1,3.5 C1,2 2,1 3.5,1 L14.5,1 C16,1 17,2 17,3.5 L17,14.5 C17,16 16,17 14.5,17 L3.5,17 C2,17 1,16 1,14.5 L1,9 Z"></path>
                            <polyline points="1 9 7 14 15 4"></polyline>
                        </svg>
                        Remember Me
                    </label>
                </div>
            </div>
            <div class="register_first_wrap_btn">
                <button type="submit" class="common_btn_dark w-100 justify-content-center">Login Now<i class="fa-solid fa-arrow-right"></i></button>
            </div>
            <div class="register_first_wrap_bottom_text">
                <p>Don't Have An Account? <a href="signup.php">Sign Up Now</a></p>
            </div>
        </form>
    </div>
</section>


@include('front.inc.footer2')
