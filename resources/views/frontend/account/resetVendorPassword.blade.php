@extends('layouts.store', ['title' => 'Register'])
@section('css')
<style type="text/css">
    .main-menu .brand-logo {
        display: inline-block;
        padding-top: 20px;
        padding-bottom: 20px;
    }
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
    .iti__flag-container li, .flag-container li{
        display: block;
    }
    .iti.iti--allow-dropdown, .allow-dropdown {
        position: relative;
        display: inline-block;
        width: 100%;
    }
    .iti.iti--allow-dropdown .phone, .flag-container .phone {
        padding: 17px 0 17px 100px !important;
    }
    .social-logins{
        text-align: center;
    }
    .social-logins img{
        width: 100px;
        height: 100px;
        border-radius: 100%;
        margin-right: 20px;
    }
    .register-page .theme-card .theme-form input {
        margin-bottom: 5px;
    }
    .invalid-feedback{
        display: block;
    }
</style>
<link rel="stylesheet" href="{{asset('assets/css/intlTelInput.css')}}">
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
<section class="register-page section-b-space">
    <div class="container">
     <div class="row justify-content-center">
         <div class="col-md-8">
            <div class="card">
                <div class="alert alert-success" role="alert" style="display:none;"></div>
                 <div class="card-header">{{__('Reset Password')}}</div>
                      <div class="card-body">
                        <form method="POST" id="reset_password_form">
                           @csrf
                        <input type="hidden" name="token" value="{{ $token }}">
                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{__('Password')}}</label>
                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" autocomplete="new-password" id="password">
                                <span class="invalid-feedback" role="alert" id="password_err"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{__('Confirm Password')}}</label>
                            <div class="col-md-6">
                                <input type="password" class="form-control" name="password_confirmation" autocomplete="new-password" id="password_confirmation">
                                <span class="invalid-feedback" role="alert" id="password_confirmation_err"></span>
                            </div>
                        </div>
                        <div class="form-group row mb-0">
                           <div class="col-md-6 offset-md-4">
                                <button type="button" class="btn btn-primary" id="reset_password_btn">{{__('Reset Password')}}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</section>
@endsection
@section('script')
<script type="text/javascript">
    var login_url = "{{url('user/login')}}";
    var reset_password_url = "{{route('reset-vendor-password')}}";
</script>
<script src="{{asset('js/forgot_password.js')}}"></script>
@endsection