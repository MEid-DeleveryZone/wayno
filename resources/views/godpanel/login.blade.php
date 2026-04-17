<!DOCTYPE html>
<html lang="en">
<head>
    @include('layouts.shared.title-meta', ['title' => "Log In"])
    @include('layouts.shared.head-content')
    <script src="{{asset('assets/js/jquery-3.1.1.min.js')}}"></script>
    <script src="{{asset('assets/js/vendor.min.js')}}"></script>
    <script src="{{asset('assets/js/jquery-ui.min.js')}}" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
</head>
<body class="authentication-bg authentication-bg-pattern">
    <div class="account-pages mt-5 mb-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="card bg-pattern">
                        <div class="card-body p-4">
                            <div class="text-center w-75 m-auto">
                                <div class="auth-logo">
                                    <a href="#" class="logo logo-dark text-center">
                                        <span class="logo-lg">
                                            <img src="{{asset('assets/images/logo-dark.png')}}" alt="" height="40">
                                        </span>
                                    </a>
                                    <a href="#" class="logo logo-light text-center">
                                        <span class="logo-lg">
                                            <img src="{{asset('assets/images/logo-light.png')}}" alt="" height="40">
                                        </span>
                                    </a>
                                </div>
                                <h5>God Panel</h5>
                                <p class="text-muted mb-4 mt-3">Enter your email address and password to access admin panel.</p>
                            </div>
                            <form action="{{route('god.login')}}" method="POST" novalidate>
                                @csrf
                                <div class="form-group mb-3">
                                    <label for="emailaddress">Email address</label>
                                    <input class="form-control  @if(isset($errors) && $errors->has('email')) is-invalid @endif" name="email" type="email" id="emailaddress" required="" value="{{ old('email')}}" placeholder="Enter your email" />

                                    @if(isset($errors) && $errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ isset($errors) && $errors->first('email') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="form-group mb-3">
                                    <label for="password">Password</label>
                                    <div class="input-group input-group-merge @if(isset($errors) && $errors->has('password')) is-invalid @endif">
                                        <input class="form-control @if(isset($errors) && $errors->has('password')) is-invalid @endif" name="password" type="password" required="" id="password" placeholder="Enter your password" />
                                        <div class="input-group-append" data-password="false">
                                            <div class="input-group-text">
                                                <span class="password-eye"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-sm-left">
                                        @if (\Session::has('Error'))
                                        <span class="text-danger" role="alert">
                                            <strong>{!! \Session::get('Error') !!}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="checkbox-signin" checked>
                                        <label class="custom-control-label" for="checkbox-signin">Remember me</label>
                                    </div>
                                </div>
                                <div class="form-group mb-0 text-center">
                                    <button class="btn btn-primary btn-block" type="submit"> Log In </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12 text-center"></div>
                    </div>
            </div>
        </div>
    </div>
    <footer class="footer footer-alt">
        <script>
            document.write(new Date().getFullYear())
        </script> &copy; All rights reserved by <a href="https://royoapps.com/" class="text-white-50">Royo Apps</a>
    </footer>
    @include('layouts.shared.footer-script')
    <script src="{{asset('assets/js/app.min.js')}}"></script>
</body>
</html>