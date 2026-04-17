@extends('layouts.store', ['title' => 'Login'])

@section('css')
<style type="text/css">
    .main-menu .brand-logo {
        display: inline-block;
        padding-top: 20px;
        padding-bottom: 20px;
    }
</style>
    
@endsection

@section('content')

 <header>
    <div class="mobile-fix-option"></div>
    @if(isset($set_template)  && $set_template->template_id == 1)
        @include('layouts.store/left-sidebar-template-one')
        @elseif(isset($set_template)  && $set_template->template_id == 2)
        @include('layouts.store/left-sidebar')
        @else
        @include('layouts.store/left-sidebar-template-one')
        @endif
</header>
<style type="text/css">
    .productVariants .firstChild{
        min-width: 150px;
        text-align: left !important;
        border-radius: 0% !important;
        margin-right: 10px;
        cursor: default;
        border: none !important;
    }
    .product-right .color-variant li, .productVariants .otherChild{
        height: 35px;
        width: 35px;
        border-radius: 50%;
        margin-right: 10px;
        cursor: pointer;
        border: 1px solid #f7f7f7;
        text-align: center;
    }
    .productVariants .otherSize{
        height: auto !important;
        width: auto !important;
        border: none !important;
        border-radius: 0%;
    }
    .product-right .size-box ul li.active {
        background-color: inherit;
    }
    .login-page .theme-card .theme-form input {
        margin-bottom: 5px;
    }
    .invalid-feedback{
        display: block;
    }
</style>

<section class="section-b-space">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <h3>Login</h3>
                <div class="theme-card">
                    @if(session('preferences')->fb_login == 1 || session('preferences')->twitter_login == 1 || session('preferences')->google_login == 1 || session('preferences')->apple_login == 1)
                        <div class="form-row mb-5">
                            <h3>Social Login</h3>
                            <div class="col-md-12">
                                <div class="social-logins">
                                    @if(session('preferences')->fb_login == 1)
                                        <a href="{{url('auth/facebook')}}"><img src="{{asset('assets/images/social-fb-login.png')}}"></a>
                                    @endif
                                    @if(session('preferences')->twitter_login == 1)
                                        <a href="{{url('auth/twitter')}}"><img src="{{asset('assets/images/twitter-login.png')}}"></a>
                                    @endif
                                    @if(session('preferences')->google_login == 1)
                                        <a href="{{url('auth/google')}}"><img src="{{asset('assets/images/google-login.png')}}"> </a>
                                    @endif
                                    @if(session('preferences')->apple_login == 1)
                                        <img src="{{asset('assets/images/apple-login.png')}}">
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                    <form name="login" id="login" action="{{route('customer.loginData')}}" class="theme-form" method="post"> @csrf
                        <div class="form-row mb-3">
                            <div class="col-md-12 mb-3">
                                <label for="email">Email</label>
                                <input type="email" class="form-control @if(isset($errors) && $errors->has('email')) is-invalid @endif" id="email" placeholder="Email"  name="email" value="{{ old('email')}}" required="">
                                @if($errors->first('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                                @if(\Session::has('err_email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{!! \Session::get('err_email') !!}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="col-md-12">
                                <label for="review">Password</label>
                                <input type="password" class="form-control @if(isset($errors) && $errors->has('password')) is-invalid @endif" id="review" placeholder="Enter your password"  name="password" required="">
                                @if($errors->first('password') || \Session::has('Error'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                                @if(\Session::has('err_password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{!! \Session::get('err_password') !!}</strong>
                                    </span>
                                @endif
                            </div>
                            <input type="hidden" name="device_type" value="web">
                            <input type="hidden" name="device_token" value="web">
                            <button type="submit" class="btn btn-solid mt-3 submitLogin">Login</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-lg-6 right-login">
                <h3>New Customer</h3>
                <div class="theme-card authentication-right">
                    <h6 class="title-font">Create A Account</h6>
                    <p>Sign up for a free account at our store. Registration is quick and easy. It allows you to be
                        able to order from our shop. To start shopping click register.</p>
                        <a href="{{route('customer.register')}}" class="btn btn-solid">Create an Account</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('script')

@endsection
