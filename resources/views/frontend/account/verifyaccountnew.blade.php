@extends('layouts.store', ['title' => 'Verify Account'])
@section('css')
<link rel="stylesheet" href="{{asset('assets/css/intlTelInput.css')}}">
@endsection
@section('content')
<style type="text/css">
    a.disabled {
      pointer-events: none;
      cursor: default;
    }
    input[type="email"]:disabled,input[type="text"]:disabled,input[type="tel"]:disabled {
      background: #dddddd;
    }
</style>
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
        <script type="text/template" id="email_verified_template">
            <img src="{{asset('front-assets/images/verified.svg')}}" alt="">
            <h3 class="mb-2">{{__('Email Address Verified!')}}</h3>
            <p>{{__('You have successfully verified the')}} <br> {{__('email account.')}}</p>
        </script>
        <script type="text/template" id="phone_verified_template">
            <img src="{{asset('front-assets/images/verified.svg')}}" alt="">
            <h3 class="mb-2">Phone Verified!</h3>
            <p>{{__('You have successfully verified the')}} <br> {{__('Phone.')}}</p>
        </script>
        <div class="row justify-content-center">
            @if($preference->verify_email == 1)
                <div class="col-lg-6 mb-lg-0 mb-3 text-center  pb-4 {{$user->is_phone_verified == 0 && $preference->verify_phone == 1 ? 'border-right' : 'offset-lg-0'}}" id="verify_email_main_div">
                    @if($user->is_email_verified == 0)
                        <img class="h-45" src="{{asset('front-assets/images/email_icon.svg')}}" alt="">
                        <h3 class="mb-2">{{__('Verify Email Address')}}</h3>
                        <p>{{__('Enter the code we just sent you on your email address')}}</p>
                        <div class="row mt-3  justify-content-center">
                            <div class="col-xl-6 text-left">
                                <div class="verify_id input-group mb-3">
                                    <input type="email" id="email" class="form-control" value="{{Auth::user()->email}}" disabled="">
                                    <div class="input-group-append">
                                        <a class="input-group-text" id="edit_email" href="javascript:void(0)">{{__('Edit')}}</a>
                                    </div>
                                    <span class="valid-feedback d-block text-center" role="alert">
                                        <strong class="edit_email_feedback"></strong>
                                    </span>
                                </div>
                                <div method="get" class="digit-group otp_inputs d-flex justify-content-between" data-group-name="digits" data-autosubmit="false" autocomplete="off">
                                    <input class="form-control" type="text" id="digit-1" name="digit-1" data-next="digit-2" onkeypress="return isNumberKey(event)"/>
                                    <input class="form-control" type="text" id="digit-2" name="digit-2" data-next="digit-3" data-previous="digit-1" onkeypress="return isNumberKey(event)"/>
                                    <input class="form-control" type="text" id="digit-3" name="digit-3" data-next="digit-4" data-previous="digit-2" onkeypress="return isNumberKey(event)"/>
                                    <input class="form-control" type="text" id="digit-4" name="digit-4" data-next="digit-5" data-previous="digit-3" onkeypress="return isNumberKey(event)"/>
                                    <input class="form-control" type="text" id="digit-5" name="digit-5" data-next="digit-6" data-previous="digit-4" onkeypress="return isNumberKey(event)"/>
                                    <input class="form-control" type="text" id="digit-6" name="digit-6" data-next="digit-7" data-previous="digit-5" onkeypress="return isNumberKey(event)"/>
                                </div>
                                <span class="invalid-feedback2 invalid_email_otp_error w-100 d-block text-center text-danger"></span>
                                <div class="row text-center mt-2">
                                    <div class="col-12 resend_txt">
                                        <p class="mb-1">{{__('If you didn’t receive a code?')}}</p>
                                        <a class="verifyEmail" href="javascript:void(0)"><u>{{__('RESEND')}}</u></a>
                                    </div>
                                    <div class="col-md-12 mt-3">
                                        <button type="button" class="btn btn-solid" id="verify_email_token">{{__('VERIFY')}}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <img src="{{asset('front-assets/images/verified.svg')}}" alt="">
                        <h3 class="mb-2">{{__('Email Address Verified!')}}</h3>
                        <p>{{__('You have successfully verified the')}} <br> {{__('email account.')}}</p>
                    @endif
                </div>
            @endif
            @if($preference->verify_phone == 1 && $user->is_phone_verified == 0)
            <div class="col-lg-6 text-center {{$user->verify_phone == 1 && $preference->is_phone_verified == 0 ? '' : 'offset-lg-0'}}" id="verify_phone_main_div">
                @if($user->is_phone_verified == 0)
                <img src="{{asset('front-assets/images/phone-otp.svg')}}">
                <h3 class="mb-2">{{__('Verify Phone')}}</h3>
                <p>{{__('Enter the code we just sent you on your email address')}}</p>
                <div class="row mt-3">
                    <div class="offset-xl-3 col-xl-6 text-left">
                        <div class="verify_id input-group mb-3 radius-flag">
                            <input type="tel" class="form-control" id="phone_number" value="{{'+'.Auth::user()->dial_code.Auth::user()->phone_number}}" disabled="">
                            <input type="hidden" id="dial_code" value="{{Auth::user()->dial_code}}">
                            <div class="input-group-append position-absolute position-right">
                                <a class="input-group-text" id="edit_phone" href="javascript:void(0)">{{__('Edit')}}</a>
                            </div>
                            <span class="valid-feedback d-block text-center" role="alert">
                                <strong class="edit_phone_feedback"></strong>
                            </span>
                        </div>
                        <div method="get" class="digit-group otp_inputs d-flex justify-content-between" data-group-name="digits" data-autosubmit="false" autocomplete="off">
                            <input class="form-control" type="text" id="digit-1" name="digit-1" data-next="digit-2" onkeypress="return isNumberKey(event)"/>
                            <input class="form-control" type="text" id="digit-2" name="digit-2" data-next="digit-3" data-previous="digit-1" onkeypress="return isNumberKey(event)"/>
                            <input class="form-control" type="text" id="digit-3" name="digit-3" data-next="digit-4" data-previous="digit-2" onkeypress="return isNumberKey(event)"/>
                            <input class="form-control" type="text" id="digit-4" name="digit-4" data-next="digit-5" data-previous="digit-3" onkeypress="return isNumberKey(event)"/>
                            <input class="form-control" type="text" id="digit-5" name="digit-5" data-next="digit-6" data-previous="digit-4" onkeypress="return isNumberKey(event)"/>
                            <input class="form-control" type="text" id="digit-6" name="digit-6" data-next="digit-7" data-previous="digit-5" onkeypress="return isNumberKey(event)"/>
                        </div>
                        <span class="invalid_phone_otp_error invalid-feedback2 w-100 d-block text-center text-danger"></span>
                        <div class="row text-center mt-2">
                            <div class="col-12 resend_txt">
                                <p class="mb-1">{{__('If you didn’t receive a code?')}}</p>
                                <a class="verifyPhone" href="javascript:void(0)"><u>{{__('RESEND')}}</u></a>
                            </div>
                            <div class="col-md-12 mt-3">
                                <button type="button" class="btn btn-solid" id="verify_phone_token">{{__('VERIFY')}}</button>
                            </div>
                        </div>
                    </div>
                    @else
                    <img src="{{asset('front-assets/images/verified.svg')}}" alt="">
                    <h3 class="mb-2">{{__('Phone Verified!')}}</h3>
                    <p>{{__('You have successfully verified the')}} <br> {{__('Phone.')}}</p>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>
</section> 
@endsection
@section('script')
<script src="{{asset('assets/js/intlTelInput.js')}}"></script>
<script>
    var edit_text = "{{__('Edit')}}";
    var resend_text = "{{__('RESEND')}}";
    var sending_text = "{{__('SENDING...')}}";
    var save_and_send = "{!! __('Save & Send.') !!}";
    var input = document.querySelector("#phone_number");
    window.intlTelInput(input, {
        separateDialCode: true,
        hiddenInput: "full_number",
        utilsScript: "{{asset('assets/js/utils.js')}}",
        initialCountry: "{{ Session::get('default_country_code','US') }}",
    });
    $(document).ready(function() {
        $("#phone_number").keypress(function(e) {
            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                return false;
            }
            return true;
        });
    });
    $('.iti__country').click(function() {
        var code = $(this).attr('data-country-code');
        $('#countryData').val(code);
        var dial_code = $(this).attr('data-dial-code');
        $('#dial_code').val(dial_code);
    });
</script>
<script type="text/javascript">
    function isNumberKey(evt){
        var charCode = (evt.which) ? evt.which : evt.keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;
        return true;
    }
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
        }
    });
    var ajaxCall = 'ToCancelPrevReq';
    $('#edit_email').click(function() {
        if ($(this).text() == edit_text){
            $(this).text(save_and_send)
            $('#email').focus();
        }else{
           $(this).text(edit_text);
           verifyUser('email');
        }
        $('#email').prop('disabled', function(i, v) { return !v; });
    });
    $('#edit_phone').click(function() {
        if ($(this).text() == "Edit"){
            $(this).text(save_and_send)
            $('#phone_number').focus();
        }else{
           $(this).text(edit_text);
           verifyUser('phone');
        }
        $('#phone_number').prop('disabled', function(i, v) { return !v; });
    });
    $('.verifyEmail').click(function() {
        verifyUser('email');
    });
    $('.verifyPhone').click(function() {
        verifyUser('phone');
    });
    function verifyUser($type = 'email') {
        $(".invalid_email_otp_error").html('');
        if($type == 'email'){
            var email = $('#email').val();
            var phone = $('#phone_number').val();
            var dial_code = $('#dial_code').val();
            $('.verifyEmail').addClass('disabled').html(sending_text);
        }else if ($type == 'phone') {
            var email = $('#email').val();
            var phone = $('#phone_number').val();
            var dial_code = $('#dial_code').val();
            $('.verifyPhone').addClass('disabled').html(sending_text);
        }
        ajaxCall = $.ajax({
            type: "post",
            dataType: "json",
            url: "{{ route('email.send', Auth::user()->id) }}",
            data: {"_token": "{{ csrf_token() }}",type: $type,phone:phone,email:email, dial_code:dial_code},
            success: function(response) {
                if($type == 'email'){
                    $('.verifyEmail').removeClass('disabled').html(resend_text);
                }else{
                    $('.verifyPhone').removeClass('disabled').html(resend_text);
                }
                if($type == 'email'){
                    $('.edit_email_feedback').html(response.message);
                }else{
                    $('.edit_phone_feedback').html(response.message);
                }
                setTimeout(function(){$('.edit_email_feedback, .edit_phone_feedback').html(''); }, 5000);
            }
        });
    }
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
                if(next.length) {
                    $(next).select();
                } else {
                    if(parent.data('autosubmit')) {
                        parent.submit();
                    }
                }
            }
        });
    });
    $("#verify_phone_token").click(function(event) {
        var verifyToken = '';
        $('.digit-group').find('input').each(function() {
            if($(this).val()){
               verifyToken +=  $(this).val();
            }
        });
        var dial_code =  $('#dial_code').val();
        var phone_number =  $('#phone_number').val();
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "{{ route('user.verifyToken') }}",
            data: {'verifyToken':verifyToken, 'type': 'phone', phone_number:phone_number, dial_code:dial_code},
            success: function(response) {
                $("#verify_phone_main_div").html('');
                let phone_verified_template = _.template($('#phone_verified_template').html());
                $("#verify_phone_main_div").append(phone_verified_template());
                setTimeout(function(){location.reload(); }, 2000);
            },
            error: function(data) {
                $(".invalid_phone_otp_error").html(data.responseJSON.error);
            },
        });
    });
    $("#verify_email_token").click(function(event) {
        var verifyToken = '';
        var email = $('#email').val();
        $('.digit-group').find('input').each(function() {
            if($(this).val()){
               verifyToken +=  $(this).val();
            }
        });
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "{{ route('user.verifyToken') }}",
            data: {verifyToken:verifyToken, type: 'email', email:email},
            success: function(response) {
                $("#verify_email_main_div").html('');
                let email_verified_template = _.template($('#email_verified_template').html());
                $("#verify_email_main_div").append(email_verified_template());
                setTimeout(function(){location.reload(); }, 2000);
            },
            error: function(data) {
                $(".invalid_email_otp_error").html(data.responseJSON.error);
            },
        });
    });
</script>
@endsection