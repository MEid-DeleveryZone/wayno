@extends('layouts.store', ['title' => __('Login')])

@section('css')
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
<section class="wrapper-main mb-5 py-lg-5">
    <div class="container">
        <div class="row" id="login-section">
            <div class="col-lg-6 mb-lg-0 mb-3 text-center border-right pb-4">
                @if (\Session::has('success'))
                    <div class="alert alert-success">
                        <span>{!! \Session::get('success') !!}</span>
                    </div>
                @endif
                @if (\Session::has('error'))
                    <div class="alert alert-danger">
                        <span>{!! \Session::get('error') !!}</span>
                    </div>
                @endif
                <h3 class="mb-2">{{ __('Login To Your Account') }}</h3>
                @if(session('preferences'))
                @if(session('preferences')->fb_login == 1 || session('preferences')->twitter_login == 1 || session('preferences')->google_login == 1 || session('preferences')->apple_login == 1)
                <ul class="social-links d-flex align-items-center mx-auto mb-4 mt-3">
                    @if(session('preferences')->google_login == 1)
                    <li>
                        <a href="{{url('auth/google')}}">
                            <img src="{{asset('front-assets/images/google.svg')}}" alt="">
                        </a>
                    </li>
                    @endif
                     @if(session('preferences')->fb_login == 1)
                    <li>
                        <a href="{{url('auth/facebook')}}"><img src="{{asset('front-assets/images/facebook.svg')}}" alt=""></a>
                    </li>
                    @endif
                    @if(session('preferences')->twitter_login)
                    <li>
                        <a href="{{url('auth/twitter')}}"><img src="{{asset('front-assets/images/twitter.svg')}}" alt=""></a>
                    </li>
                    @endif
                    @if(session('preferences')->apple_login == 1)
                    <li>
                        <a href="javascript::void(0);">
                            <img src="{{asset('front-assets/images/apple.svg')}}">
                        </a>
                    </li>
                    @endif
                </ul>
                <div class="divider_line m-auto">
                    <span>OR</span>
                </div>
                @endif
                @endif
                <div class="row mt-3 arabic-language">
                    <div class="offset-xl-2 col-xl-8 text-left">
                        {{-- <form name="login" id="login" action="{{route('customer.loginData')}}"  class="px-lg-4" method="post"> 
                            @csrf
                            <div class="form-group">
                                <label for="">{{ __('Email') }}</label>
                                <input type="email" class="form-control @if(isset($errors) && $errors->has('email')) is-invalid @endif" aria-describedby="" placeholder="{{ __('Email') }}" value="{{ old('email')}}" name="email">
                                <input type="hidden" value="" id="access_token" name="access_token">
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
                            <div class="form-group">
                                <label for="">{{ __('Password') }}</label>
                                <input type="password" class="form-control @if(isset($errors) && $errors->has('password')) is-invalid @endif" name="password" placeholder="{{ __('Password') }}">
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
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <input type="hidden" name="device_type" value="web">
                                    <input type="hidden" name="device_token" value="web">
                                    <button type="submit" class="btn btn-solid submitLogin">{{ __('Login') }}</button>
                                </div>
                                <div class="col-md-6 text-md-right">
                                    <a class="forgot_btn" href="{{url('user/forgotPassword')}}">{{ __('Forgot Password?') }}</a>
                                </div>
                            </div>
                        </form> --}}
                        <form id="login-form-new" class="px-lg-4" action="">
                        @csrf
                        <input type="hidden" name="device_type" value="web">
                        <input type="hidden" name="device_token" value="web">
                        <input type="hidden" id="dialCode" name="dialCode" value="{{ old('dialCode') ? old('dialCode') : Session::get('default_country_phonecode','1') }}">
                        <input type="hidden" id="countryData" name="countryData" value="{{ strtolower(Session::get('default_country_code','US')) }}">
                        
                        <div class="login-with-username">
                            <div class="form-group">
                                <input type="text" class="form-control" id="username" placeholder="{{ __('Email or Phone Number') }}" required="" name="username" value="{{ old('username')}}">
                            </div>
                            <div class="form-group" id="password-wrapper" style="display:none; position:relative">
                                <input type="password" class="form-control pr-3" name="password" placeholder="{{ __('Password') }}">
                            </div>
                            <div class="form-group" id="password-wrapper">
                                <a class="font-14" href="javascript:void(0)" id="send_password_reset_link">Forgot?</a>
                            </div>
                            <div class="form-group">
                                <span id="error-msg" class="font-14 text-danger" style="display:none"></span>
                                <span id="success-msg" class="font-14 text-success" style="display:none"></span>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-solid w-100 login_continue_btn" type="submit">Continue</button>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>    
            </div>
            <div class="col-lg-6 text-center">
                <h3 class="mb-md-5 mb-4">{{ __('New Customer') }}</h3>   
                <div class="create_box">
                    <h6>{{ __('Create An Account') }}</h6>
                    <p>{{ __('Sign up for a free account at our store. Registration is quick and easy. It allows you to be able to order from our shop. To start shopping click register.') }}</p>
                    <a href="{{route('customer.register')}}" class="btn btn-solid mt-4">{{ __('Create An Account') }}</a>
                </div>
            </div>
        </div>
        <div class="row justify-content-center" id="verify-phone-section" style="display:none">
            <div class="verify-login-code">
                <form id="verify-otp-form" class="px-lg-4" action="">
                <h3 class="mb-2 text-center">{{ __('Verify OTP') }}</h3>
                <div method="get" class="digit-group otp_inputs d-flex justify-content-between" data-group-name="digits" data-autosubmit="false" autocomplete="off">
                    <input class="form-control" type="text" id="digit-1" name="digit-1" data-next="digit-2" onkeypress="return isNumberKey(event)"/>
                    <input class="form-control" type="text" id="digit-2" name="digit-2" data-next="digit-3" data-previous="digit-1" onkeypress="return isNumberKey(event)"/>
                    <input class="form-control" type="text" id="digit-3" name="digit-3" data-next="digit-4" data-previous="digit-2" onkeypress="return isNumberKey(event)"/>
                    <input class="form-control" type="text" id="digit-4" name="digit-4" data-next="digit-5" data-previous="digit-3" onkeypress="return isNumberKey(event)"/>
                    <input class="form-control" type="text" id="digit-5" name="digit-5" data-next="digit-6" data-previous="digit-4" onkeypress="return isNumberKey(event)"/>
                    <input class="form-control" type="text" id="digit-6" name="digit-6" data-next="digit-7" data-previous="digit-5" onkeypress="return isNumberKey(event)"/>
                </div>
                <span class="invalid_phone_otp_error invalid-feedback2 w-100 d-block text-center text-danger"></span>
                <span id="phone_otp_success_msg" class="font-14 text-success text-center w-100 d-block" style="display:none"></span>
                <div class="row text-center mt-2">
                    <div class="col-12 resend_txt">
                        <a href="javascript:void(0)" class="mb-2 back-login font-12">
                            Change Number
                        </a>
                        <p class="mb-1">{{__('If you didn’t receive a code?')}}</p>
                        <a class="verifyPhone" href="javascript:void(0)"><u>{{__('RESEND')}}</u></a>
                    </div>
                    <div class="col-md-12 mt-3">
                        <button type="submit" class="btn btn-solid" id="verify_phone_token">{{__('VERIFY')}}</button>
                    </div>
                </div>
                </form>
            </div>
        </div>
        </form>
    </div>
</section> 
@endsection
@section('script')
{{-- <script src="https://www.gstatic.com/firebasejs/5.5.9/firebase.js"></script> --}}
<script src="{{asset('assets/js/intlTelInput.js')}}"></script>
<script>
    var login_via_username_url = "{{route('customer.loginViaUsername')}}";
    var forgot_password_url = "{{route('customer.forgotPass')}}";
    // var firebaseConfig = {
    //     apiKey: "AIzaSyBppfct1EwlyUSAT9QKbiuo4e6HiMvV4Fs",
    //     authDomain: "royo-apps-1624361718359.firebaseapp.com",
    //     databaseURL: "https://royo-apps-1624361718359-default-rtdb.firebaseio.com",
    //     projectId: "royo-apps-1624361718359",
    //     storageBucket: "royo-apps-1624361718359.appspot.com",
    //     messagingSenderId: "1030919748357",
    //     appId: "1:1030919748357:web:9c29df0aca70b4f508156c",
    //     measurementId: "G-EFBPR3ZDKE"
    // };
    // firebase.initializeApp(firebaseConfig);
    // const messaging = firebase.messaging();
    // messaging
    // .requestPermission()
    // .then(function () {
    //     return messaging.getToken()
    // })
    // .then(function(token) {
    //     $("#access_token").val(token);
    //     console.log(token);
    // })
    // .catch(function (err) {
    // });

    $('.email-btn').click(function(){
        $('.login-with-mail').show();
        $('.login-with-username').hide();
    });
    $('.back-login').click(function(){
        $('#login-section').show();
        $('#verify-phone-section').hide();
    });

    var reset = function() {
        var input = document.querySelector("#username"),
        errorMsg = document.querySelector("#error-msg");
        input.classList.remove("is-invalid");
        errorMsg.innerHTML = "";
        errorMsg.style.display = 'none';
        $("#password-wrapper").hide();
        $("#password-wrapper input").removeAttr("required");
        $("#password-wrapper input").val('');
    };

    // here, the index maps to the error code returned from getValidationError - see readme
    var errorMap = ["Invalid phone number", "Invalid country code", "Phone number too short", "Phone number too long", "Invalid phone number"];

    var iti = '';
    var phn_filter = /^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\./0-9]*$/;
    var email_filter = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

    $(document).delegate('input[name="password"]', 'input', function() {
        $(this).parent('#password-wrapper').show();
        $("#error-msg").hide();
    });

    $(document).delegate("#username", "input", function(e){
        var uname = $.trim($(this).val());
        if(phn_filter.test(uname)){
            // get country flags when input is a number
            assignPhoneInput();
            $("#password-wrapper").hide();
            $("#password-wrapper input").removeAttr("required");
            $("#password-wrapper input").val('');
        }else{
            // destroy country flags when input is a string
            if(iti != ''){
                iti.destroy();
                iti = '';
                $(this).css('padding-left', '6px');
            }
        }
        $(this).focus();
        $(this).removeClass("is-invalid");
        $("#error-msg").hide();
    });

    function assignPhoneInput(){
        var input = document.querySelector("#username");
        var country = $('#countryData').val();
        if(iti != ''){
            iti.destroy();
            iti = '';
        }
        iti = intlTelInput(input, {
            initialCountry: country,
            separateDialCode: true,
            hiddenInput: "full_number",
            utilsScript: "{{asset('assets/js/utils.js')}}",
        });
        $("input[name='full_number']").val(iti.getNumber());
    }

    $(document).delegate(".verifyPhone", "click", function(){
        $("#login-form-new").submit();
    });

    $(document).delegate("#login-form-new", "submit", function(e){
    // $(document).delegate(".login_continue_btn, .verifyPhone", "click", function(e){
        e.preventDefault();
        var uname = $.trim($("#username").val());
        var error = 0;
        var phone = $("input[name='full_number']").val();
        if(uname != ''){
            if(phn_filter.test(uname)){
                reset();
                if (!iti.isValidNumber()) {
                    $("#username").addClass("is-invalid");
                    var errorCode = iti.getValidationError();
                    $("#error-msg").html(errorMap[errorCode]);
                    $("#error-msg").show();
                    error = 1;
                }else{
                    $("#username").removeClass("is-invalid");
                    $("#error-msg").hide();
                }
            }
            else{
                if(email_filter.test(uname)){
                    $("#username").removeClass("is-invalid");
                    $("#error-msg").hide();
                    if($("#password-wrapper").is(":visible")){
                        if($("#password-wrapper input").val() == ''){
                            error = 1;
                            $("#error-msg").show();
                            $("#error-msg").html('Password field is required');
                        }
                    }else{
                        error = 1;
                        $("#password-wrapper").show();
                        $("#password-wrapper input").attr("required", true);
                        $("#password-wrapper input").trigger("focus");
                    }
                }else{
                    error = 1;
                    $("#username").addClass("is-invalid");
                    $("#error-msg").show();
                    $("#error-msg").html('Invalid Email or Phone Number');
                }
            }
        }
        else{
            error = 1;
            $("#username").addClass("is-invalid");
            $("#error-msg").show();
            $("#error-msg").html('Email or Phone Number Required');
        }
        if(!error){
            var form_inputs = $("#login-form-new").serializeArray();
            $.each(form_inputs, function(i, input) {
                if(input.name == 'full_number'){
                    input.value = phone;
                }
            });
            $.ajax({
                data: form_inputs,
                type: "POST",
                dataType: 'json',
                url: login_via_username_url,
                success: function (response) {
                    if (response.status == "Success") {
                        var data = response.data;
                        if(data.is_phone != undefined && data.is_phone == 1){
                            $('#login-section').hide();
                            $('#verify-phone-section').show();
                            $('.otp_inputs input').val('');
                            $('#phone_otp_success_msg').html(response.message).show();
                            setTimeout(function(){ 
                                $('#phone_otp_success_msg').html('').hide();
                            }, 5000);
                        }
                        else if(data.is_email != undefined && data.is_email == 1){
                            window.location.href = response.data.redirect_to;
                        }else{
                            $("#error-msg").html('Something went wrong');
                            $("#error-msg").show();
                        }
                    }
                }, error: function (error) {
                    var response = $.parseJSON(error.responseText);
                    // let error_messages = response.message;
                    $("#error-msg").html(response.message);
                    $("#error-msg").show();
                }
            });
        }
    });

    // $(document).ready(function() {
    //     $("#username").keypress(function(e) {
    //         if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
    //             return false;
    //         }
    //         return true;
    //     });
    // });

    $(document).delegate('.iti__country','click', function() {
        var code = $(this).attr('data-country-code');
        $('#countryData').val(code);
        var dial_code = $(this).attr('data-dial-code');
        $('#dialCode').val(dial_code);
    });

    // $("#verify_phone_token").click(function(event) {
    $(document).delegate("#verify-otp-form", "submit", function(e){
        e.preventDefault();
        var verifyToken = '';
        $('.digit-group').find('input').each(function() {
            if($(this).val()){
               verifyToken +=  $(this).val();
            }
        });
        var form_inputs = $("#login-form-new").serializeArray();
        form_inputs.push({name : 'verifyToken', value : verifyToken});
        
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "{{ route('customer.verifyPhoneLoginOtp') }}",
            data: form_inputs,
            success: function(response) {
                if(response.status == 'Success'){
                    window.location.href = response.data.redirect_to;
                }else{
                    $(".invalid_phone_otp_error").html(response.message);
                    setTimeout(function(){ 
                		$('.invalid_phone_otp_error').html('').hide();
                	}, 5000);
                }
            },
            error: function(data) {
                $(".invalid_phone_otp_error").html(data.responseJSON.message);
                setTimeout(function(){ 
                    $('.invalid_phone_otp_error').html('').hide();
                }, 5000);
            },
        });
    });

    $('.digit-group').find('input').each(function() {
        $(this).attr('maxlength', 1);
        $(this).on('keyup', function(e) {
            var parent = $($(this).parent());
            if(e.keyCode === 8 || e.keyCode === 37) {
                var prev = parent.find('input#' + $(this).data('previous'));
                if(prev.length) {
                    $(prev).select();
                }
            } else if((e.keyCode >= 48 && e.keyCode <= 57) || (e.keyCode >= 65 && e.keyCode <= 90) || (e.keyCode >= 96 && e.keyCode <= 105) || e.keyCode === 39) {
                var next = parent.find('input#' + $(this).data('next'));
                if( (next.length) && ($(this).val() != '') ) {
                    $(next).select();
                } else {
                    if(parent.data('autosubmit')) {
                        parent.submit();
                    }
                }
            }
        });
    });

    $('#send_password_reset_link').click(function(){
        var that = $(this);
        var email = $('#username').val();
        $('.invalid-feedback').html('');
        $.ajax({
            type: "POST",
            dataType: "json",
            data: {"email": email},
            url: forgot_password_url,
            success: function(res) {
                if(res.status == "Success"){
                    $('#success-msg').html(res.message).show();
                	setTimeout(function(){ 
                		$('#success-msg').html('').hide();
                	}, 5000);
                }
            },
            error:function(error){
            	var response = $.parseJSON(error.responseText);
                let error_messages = response.errors;
                $.each(error_messages, function(key, error_message) {
                    $('#error-msg').html(error_message[0]).show();
                });
            }
        });
    });

    function isNumberKey(evt){
        var charCode = (evt.which) ? evt.which : evt.keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;
        return true;
    }
</script>
@endsection