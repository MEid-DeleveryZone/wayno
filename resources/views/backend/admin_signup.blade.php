<!DOCTYPE html>
<html lang="en">
    <head>
 
        @include('layouts.shared.title-meta', ['title' => "Log In"])

        @include('layouts.shared.head-content')
    </head>

    <body class="auth-fluid-pages pb-0">

        <div class="auth-fluid">
            <!--Auth fluid left content -->
            <div class="auth-fluid-form-box">
                <div class="align-items-center d-flex h-100">
                    <div class="card-body">

                        <!-- Logo -->
                        <div class="auth-brand text-center text-lg-left">
                            <div class="auth-logo">
                                <a href="{{route('any', ['dashboard'])}}" class="logo logo-dark text-center">
                                    <span class="logo-lg">
                                        <img src="{{asset('assets/images/logo-dark.png')}}" alt="" height="50">
                                    </span>
                                </a>
            
                                <a href="{{route('any', ['dashboard'])}}" class="logo logo-light text-center">
                                    <span class="logo-lg">
                                        <img src="{{asset('assets/images/logo-light.png')}}" alt="" height="50">
                                    </span>
                                </a>
                            </div>
                        </div>

                        <!-- title-->
                        <h4 class="mt-0">Sign Up</h4>
                        <p class="text-muted mb-4">Enter your email address and password to access account.</p>

                        <!-- form -->
                        <form action="#">
                            <div class="row">
                            <div class="col-md-4 col-sm-6">
                                    <div class="form-group">
                                        <label for="">Upload Logo</label>
                                        <div class="dropify-wrapper has-preview"><div class="dropify-message"><span class="file-icon"> <p>{{ __("Drag and drop a file here or click") }}</p></span><p class="dropify-error">Ooops, something wrong appended.</p></div><div class="dropify-loader" style="display: none;"></div><div class="dropify-errors-container"><ul></ul></div><input type="file" data-plugins="dropify" data-default-file="../assets/images/small/img-2.jpg"><button type="button" class="dropify-clear">{{ __("Remove") }}</button><div class="dropify-preview" style="display: block;"><span class="dropify-render"><img src="../assets/images/small/img-2.jpg"></span><div class="dropify-infos"><div class="dropify-infos-inner"><p class="dropify-filename"><span class="file-icon"></span> <span class="dropify-filename-inner">img-2.jpg</span></p><p class="dropify-infos-message">Drag and drop or click to replace</p></div></div></div></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="emailaddress">Name</label>
                                        <input class="form-control" type="email" id="" required="" placeholder="Enter your name">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="emailaddress">Email address</label>
                                        <input class="form-control" type="email" id="" required="" placeholder="Enter your email">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="emailaddress">Email address</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1">+91</span>
                                            </div>
                                            <input type="text" class="form-control" name="phone_number" id="phone_number" value="" placeholder="Enter mobile number" required="">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <!-- <a href="{{route('second', ['auth', 'recoverpw-2'])}}" class="text-muted float-right"><small>Forgot your password?</small></a> -->
                                        <label for="password">Password</label>
                                        <div class="input-group input-group-merge">
                                            <input type="password" id="password" class="form-control" placeholder="Enter your password">
                                            <div class="input-group-append" data-password="false">
                                                <div class="input-group-text">
                                                    <span class="password-eye"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="company_name" class="control-label">Company Name</label>
                                        <input type="text" class="form-control" name="company_name" id="company_name" value="" placeholder="Enter company name">
                                     </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="company_address" class="control-label">Company Address</label>
                                        <input type="text" class="form-control" id="company_address" name="company_address" value="" placeholder="Enter company address">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="sub_domain" class="control-label">SUB DOMAIN</label>
                                        <div class="domain-outer d-flex align-items-center">
                                            <div class="domain_name">https://</div>
                                            <input type="text" name="sub_domain" pattern="[a-z]+" id="sub_domain" placeholder="Enter Sub domain" class="form-control" value=""><div class="domain_name">.royo.com</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group mb-0 mt-3 text-center">
                                        <button class="btn btn-primary btn-block" type="submit">Log In </button>
                                    </div>
                                </div>
                            </div>                           
                        </form>
                        <!-- end form-->

                        <!-- Footer-->
                        <footer class="footer footer-alt">
                            <p class="text-muted">Don't have an account? <a href="{{route('second', ['auth', 'register-2'])}}" class="text-muted ml-1"><b>Sign Up</b></a></p>
                        </footer>

                    </div> <!-- end .card-body -->
                </div> <!-- end .align-items-center.d-flex.h-100-->
            </div>
            <!-- end auth-fluid-form-box-->

            <!-- Auth fluid right content -->
            <div class="auth-fluid-right text-center">
                <div class="auth-user-testimonial">
                    <h2 class="mb-3 text-white">Lorem ipsum dolor sit amet.</h2>
                    <p class="lead"><i class="mdi mdi-format-quote-open"></i> Lorem ipsum dolor sit amet consectetur adipisicing elit. A provident optio nostrum dolores ipsa odio et harum, unde facere molestiae, asperiores explicabo quidem aspernatur inventore amet ab officiis nesciunt magnam? <i class="mdi mdi-format-quote-close"></i>
                    </p>
                    <h5 class="text-white">
                        - lorem (Admin User)
                    </h5>
                </div> <!-- end auth-user-testimonial-->
            </div>
            <!-- end Auth fluid right content -->
        </div>
        <!-- end auth-fluid-->
        
        @include('layouts.shared.footer-script')
        
    </body>
</html>