@extends('layouts.store', ['title' => 'Register'])

@section('css')
<style type="text/css">
    .main-menu .brand-logo {
        display: inline-block;
        padding-top: 20px;
        padding-bottom: 20px;
    }

    .productVariants .firstChild {
        min-width: 150px;
        text-align: left !important;
        border-radius: 0% !important;
        margin-right: 10px;
        cursor: default;
        border: none !important;
    }

    .product-right .color-variant li,
    .productVariants .otherChild {
        height: 35px;
        width: 35px;
        border-radius: 50%;
        margin-right: 10px;
        cursor: pointer;
        border: 1px solid #f7f7f7;
        text-align: center;
    }

    .productVariants .otherSize {
        height: auto !important;
        width: auto !important;
        border: none !important;
        border-radius: 0%;
    }

    .product-right .size-box ul li.active {
        background-color: inherit;
    }

    .iti__flag-container li,
    .flag-container li {
        display: block;
    }

    .iti.iti--allow-dropdown,
    .allow-dropdown {
        position: relative;
        display: inline-block;
        width: 100%;
    }

    .iti.iti--allow-dropdown .phone,
    .flag-container .phone {
        padding: 17px 0 17px 100px !important;
    }

    .social-logins {
        text-align: center;
    }

    .social-logins img {
        width: 100px;
        height: 100px;
        border-radius: 100%;
        margin-right: 20px;
    }

    .register-page .theme-card .theme-form input {
        margin-bottom: 5px;
    }

    .invalid-feedback {
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
        <div class="row">
            <div class="col-lg-12">
                <h3>create account</h3>
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
                                @if(session('preferences')->apple_login < 5) <a href="{{url('auth/apple')}}"><img src="{{asset('assets/images/apple-login.png')}}"></a>
                                    @endif
                            </div>
                        </div>
                    </div>
                    @endif

                    <form name="register" id="register" action="{{route('customer.register')}}" class="theme-form" method="post"> @csrf
                        <div class="form-row mb-3">
                            <div class="col-md-6">
                                <label for="email">First Name</label>
                                <input type="text" class="form-control" id="name" placeholder="First Name" required="" name="name" value="{{ old('name')}}">
                                @if($errors->first('name'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <label for="review">Phone Number</label>
                                <input type="tel" class="form-control phone" id="phone" placeholder="Phone Number" required="" name="phone_number" value="{{ old('phone_number')}}">
                                @if($errors->first('phone_number'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('phone_number') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-row mb-3">
                            <div class="col-md-6">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" placeholder="Email" required="" name="email" value="{{ old('email')}}">
                                @if($errors->first('email'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <label for="review">Password</label>
                                <input type="password" class="form-control" id="review" placeholder="Enter your password" required="" name="password">
                                @if($errors->first('password'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                                @endif
                            </div>

                        </div>
                        <div class="form-row mb-3">
                            <div class="col-md-6">
                                <label for="refferal_code">Refferal Code</label>
                                <input type="text" class="form-control" id="refferal_code" placeholder="Refferal Code" name="refferal_code" value="{{ old('refferal_code')}}">
                                @if($errors->first('refferal_code'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('refferal_code') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="col-md-6">
                            </div>
                            <input type="hidden" name="device_type" value="web">
                            <input type="hidden" name="device_token" value="web">
                            <input type="hidden" id="countryData" name="countryData" value="us">
                            <button type="submit" class="btn btn-solid mt-3 submitRegister">Create Account</button>
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
        //allowDropdown: true,
        //autoHideDialCode: true,
        // autoPlaceholder: "off",
        // dropdownContainer: document.body,
        // excludeCountries: ["us"],
        // formatOnDisplay: false,
        // geoIpLookup: function(callback) {
        //   $.get("http://ipinfo.io", function() {}, "jsonp").always(function(resp) {
        //     var countryCode = (resp && resp.country) ? resp.country : "";
        //     callback(countryCode);
        //   });
        // },
        hiddenInput: "full_number",
        //initialCountry: "auto",
        // localizedCountries: { 'de': 'Deutschland' },
        //nationalMode: false,
        // onlyCountries: ['us', 'gb', 'ch', 'ca', 'do'],
        //placeholderNumberType: "MOBILE",
        // preferredCountries: ['cn', 'jp'],
        //separateDialCode: true,
        utilsScript: "{{asset('assets/js/utils.js')}}",
    });

    $(document).ready(function() {
        $("#phone").keypress(function(e) {
            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                return false;
            }
            return true;
        });
    });

    $('.iti__country').click(function() {
        var code = $(this).attr('data-country-code');
        $('#countryData').val(code);
    })
</script>
@endsection