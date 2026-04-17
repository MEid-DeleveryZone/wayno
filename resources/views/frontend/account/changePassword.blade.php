@extends('layouts.store', ['title' => __('Change Password')])
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
            <div class="col-md-6 col-xl-4">
                <h3>{{__('Change Password')}}</h3>
                  <div class="outer-box"> 
                    <form name="register" id="register" action="{{route('user.submitChangePassword')}}" class="theme-form" method="post"> @csrf
                        <div class="form-row mb-2">
                            <div class="col-md-12 mb-3">
                                <label for="review">{{__('Password')}}</label>
                                <input type="password" class="form-control mb-0" id="review" placeholder="{{__('Password')}}" name="new_password">
                                @if($errors->has('new_password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('new_password') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="review">{{__('Confirm Password')}}</label>
                                <input type="password" class="form-control mb-0" id="review" placeholder="{{__('Confirm Password')}}" name="confirm_password">
                                @if($errors->first('confirm_password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('confirm_password') }}</strong>
                                    </span>
                                @endif
                                @if(\Session::has('err_cf'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{!! \Session::get('err_cf') !!}</strong>
                                    </span>
                                @endif
                            </div>
                            <input type="hidden" name="device_type" value="web">
                            <input type="hidden" name="device_token" value="web">
                            <input type="hidden" id="countryData" name="countryData" value="us">
                            <div class="col-md-12"><button type="submit" class="btn btn-solid submitRegister w-100">{{__('Submit')}}</button></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('script')
<script src="{{asset('assets/js/intlTelInput.js')}}"></script>
<script>
    var input = document.querySelector("#phone");
    window.intlTelInput(input, {
        separateDialCode: true,
        hiddenInput: "full_number",
        utilsScript: "{{asset('assets/js/utils.js')}}",
        initialCountry: "{{ Session::get('default_country_code','US') }}",
    });
    $(document).ready(function () {
        $("#phone").keypress(function (e) {
            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                return false;
            }
            return true;
        });
    });
    $('.iti__country').click(function(){
        var code = $(this).attr('data-country-code');
        $('#countryData').val(code);
    })
</script>
@endsection