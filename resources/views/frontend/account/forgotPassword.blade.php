@extends('layouts.store', ['title' => 'Forgot Password'])
@section('css')
<style type="text/css">
    .main-menu .brand-logo {
        display: inline-block;
        padding-top: 20px;
        padding-bottom: 20px;
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
<section class="register-page section-b-space fh">
    <div class="container">
        <div class="row">
            <div class="offset-lg-3 col-lg-6">
                <h3>{{__('Enter Email Address')}}</h3>
                <div class="card mt-4">
                <div class="alert alert-success" role="alert" style="display:none;">fdgdfg</div>
                    <form name="register" id="register" action="" class="theme-form" method="post">
                        <div class="form-row mb-3">
                            <div class="col-md-12">
                                <div class="input-group d-md-flex d-block text-center">
                                    <input type="email" class="form-control text-left mb-0" id="email" placeholder="{{__('Enter Email')}}" required="" name="email" value="">
                                    <button class="btn btn-solid mx-auto mt-3 mt-md-0" type="button" id="send_password_reset_link">{{__('Send Password Reset Link')}}</button>
                                </div>
                            </div>
                            <div class="px-3">
                                 <span class="invalid-feedback" role="alert" id="email_validation_error"></span>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('script')
<script type="text/javascript">
    var forgot_password_url = "{{route('customer.forgotPass')}}";
</script>
<script src="{{asset('js/forgot_password.js')}}"></script>
@endsection