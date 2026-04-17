@extends('layouts.store', ['title' => 'Home'])
@section('css')
<link rel="stylesheet" href="{{asset('assets/css/intlTelInput.css')}}">
<link href="{{asset('assets/libs/multiselect/multiselect.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/selectize/selectize.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/bootstrap-select/bootstrap-select.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
@if(isset($set_template)  && $set_template->template_id == 1)
@include('layouts.store/left-sidebar-template-one')
@elseif(isset($set_template)  && $set_template->template_id == 2)
@include('layouts.store/left-sidebar')
@elseif(isset($set_template)  && $set_template->template_id == 3)
@include('layouts.store/left-sidebar-default-template')
@else
@include('layouts.store/left-sidebar-template-one')
@endif
<div class="p-md-4 p-xs-2">
<section class="section-b-space new-pages pb-265">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2 class="mb-3">{{$page_detail->translations->first() ? $page_detail->translations->first()->title : $page_detail->primary->title}}</h2>
                <p>{!!$page_detail->translations->first() ? $page_detail->translations->first()->description : $page_detail->primary->description !!}</p>
            </div>
        </div>
        @if($page_detail->primary->type_of_form == 1)
            <form class="vendor-signup" id="vendor_signup_form">@csrf
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="alert alert-success" role="alert" id="success_msg" style="display:none;"></div>
                        <div class="row">
                            <div class="col-12">
                                <h2>{{__('Personal Details.')}}</h2>
                            </div>    
                        </div>
                        <div class="needs-validation vendor-signup">
                            <input type="hidden" name="user_id" value="{{$user ? $user->id : ''}}">
                            <div class="form-row">
                                <div class="col-md-4 mb-3" id="full_nameInput">
                                    <label for="fullname">{{__('Full Name')}}</label>
                                    <input type="text" class="form-control" name="full_name" value="{{$user ? $user->name : ''}}" {{$user ? 'disabled' : ''}}>
                                    <div class="invalid-feedback" id="full_name_error"><strong></strong></div>
                                </div>
                                <div class="col-md-4 mb-3" id="phone_numberInput">
                                    <label for="validationCustom02">{{__('Phone No.')}}</label>
                                    <input type="tel" class="form-control" name="phone_number" value="{{$user ? '+'.$user->dial_code.''.$user->phone_number : ''}}" id="phone" {{$user ? 'disabled' : ''}}>
                                    <div class="invalid-feedback" id="phone_number_error"><strong></strong></div>
                                    <input type="hidden" id="countryData" name="countryData" value="us">
                                    <input type="hidden" id="dialCode" name="dialCode" value="{{$user ? $user->dial_code : ''}}">
                                </div>
                                {{-- <div class="col-md-4 mb-3" id="full_nameInput">
                                    <label for="fullname">{{__('Title')}}</label>
                                    <input type="text" class="form-control" name="title" value="{{$user ? $user->title : ''}}">
                                    <div class="invalid-feedback" id="full_name_error"><strong></strong></div>
                                </div> --}}
                                <div class="col-md-4 mb-3" id="categoryInput">
                                    <label for="category">{{__('Category')}}</label>
                                    <select class="form-control select2-multiple" id="IncludeCategory" name="categories[]" data-toggle="select2" multiple="multiple" data-placeholder="Choose ...">
                                        @foreach($categories as $nm)
                                        <option value="{{$nm->id}}" >{{$nm->translation_one->name}}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback" id="categories_error"><strong></strong></div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-4 mb-3" id="emailInput">
                                    <label for="email">{{__('Email')}}</label>
                                    <input type="text" class="form-control" name="email" value="{{$user ? $user->email :''}}" {{$user ? 'disabled' : ''}}>
                                    <div class="invalid-feedback" id="email_error"><strong></strong></div>
                                </div>
                                @if(!$user)
                                    <div class="col-md-4 mb-3" id="passwordInput">
                                        <label for="password">{{__('Password')}}</label>
                                        <input type="password" class="form-control" name="password" value="" required="">
                                        <div class="invalid-feedback" id="password_error"><strong></strong></div>
                                    </div>
                                     <div class="col-md-4 mb-3" id="confirm_passwordInput">
                                        <label for="confirm_password">{{__('Confirm Password')}}</label>
                                        <input type="password" class="form-control" name="confirm_password" value="" required="">
                                        <div class="invalid-feedback" id="confirm_password_error"><strong></strong></div>
                                    </div>
                                @endif
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <h2>{{__('Store Details.')}}</h2>
                                </div>    
                            </div>
                            <div class="form-row">
                                <div class="col-md-4 mb-3">
                                    <label for="">{{__('Upload Logo')}}</label>
                                    <div class="file file--upload">
                                        <label for="input_file_logo">
                                            <span class="update_pic">
                                                <img src="" id="upload_logo_preview">
                                            </span>
                                            <span class="plus_icon">
                                                <i class="fa fa-plus"></i>
                                            </span>
                                        </label>
                                        <input id="input_file_logo" type="file" name="upload_logo" accept="image/*">
                                    </div>
                                </div>      
                                <div class="col-md-8 mb-3">
                                    <label for="">{{__('Upload Banner')}}</label>
                                    <div class="file file--upload">
                                        <label for="input_file_banner">
                                            <span class="update_pic">
                                                <img src="" id="upload_banner_preview">
                                            </span>
                                            <span class="plus_icon"><i class="fa fa-plus"></i></span>
                                        </label>
                                        <input id="input_file_banner" type="file" name="upload_banner" accept="image/*">
                                    </div>
                                </div>      
                            </div>
                            <div class="form-row">
                                <div class="col-md-12 mb-3" id="nameInput">
                                    <label for="validationCustom01">{{__('Vendor Name')}}</label>
                                    <input type="text" class="form-control" name="name" value="">
                                    <div class="invalid-feedback" id="name_error"><strong></strong></div>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="validationCustom02">{{__('Description')}}</label>
                                    <textarea class="form-control" name="vendor_description" cols="30" rows="3"></textarea>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-6 mb-3" id="addressInput">
                                    <label for="validationCustom01">{{__('Address')}}</label>
                                    <input type="text" class="form-control" name="address" value="" id="address">
                                    <input type="hidden" class="form-control" name="longitude" value="" id="longitude">
                                    <input type="hidden" class="form-control" name="latitude" value="" id="latitude">
                                    <input type="hidden" class="form-control" name="pincode" value="" id="pincode">
                                    <input type="hidden" class="form-control" name="city" value="" id="city">
                                    <input type="hidden" class="form-control" name="state" value="" id="state">
                                    <input type="hidden" class="form-control" name="country" value="" id="country">
                                    <div class="invalid-feedback" id="address_error"><strong></strong></div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="validationCustom02">{{__('Website')}}</label>
                                    <input type="text" class="form-control" name="website" value="">
                                    <div class="valid-feedback"></div>
                                </div>
                            </div>
                            @if($mod_count > 1)
                            @if($client_preferences)
                                <div class="form-row">
                                    @if($client_preferences->dinein_check == 1)
                                        <div class="col-md-2 mb-3">
                                            <label for="">Dine In</label>
                                            <div class="toggle-icon">
                                                <input type="checkbox" id="dine-in" name="dine_in"><label for="dine-in">Toggle</label>
                                            </div>
                                        </div>
                                    @endif
                                    @if($client_preferences->takeaway_check == 1)
                                        <div class="col-md-2 mb-3">
                                            <label for="">Takeaway</label>
                                            <div class="toggle-icon">
                                                <input type="checkbox" id="takeaway" name="takeaway"><label for="takeaway">Toggle</label>
                                            </div>
                                        </div>
                                    @endif
                                    @if($client_preferences->delivery_check == 1)
                                        <div class="col-md-2 mb-3">
                                            <label for="">Delivery</label>
                                            <div class="toggle-icon">
                                                <input type="checkbox" id="delivery" name="delivery"><label for="delivery">Toggle</label>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endif
                            @endif
                            <div class="form-row">
                                @foreach($vendor_registration_documents as $vendor_registration_document)
                                <div class="col-md-6 mb-3" id="{{$vendor_registration_document->primary->slug}}Input">
                                    <label for="">{{$vendor_registration_document->primary ? $vendor_registration_document->primary->name : ''}}</label>
                                    <div class="file file--upload">
                                        <label for="input_file_logo_{{$vendor_registration_document->id}}">
                                            <span class="update_pic pdf-icon">
                                                <img src=""  id="upload_logo_preview_{{$vendor_registration_document->id}}">
                                            </span>
                                            <span class="plus_icon" id="plus_icon_{{$vendor_registration_document->id}}">
                                                <i class="fa fa-plus"></i>
                                            </span>
                                        </label>
                                        @if(strtolower($vendor_registration_document->file_type) == 'image')
                                            <input id="input_file_logo_{{$vendor_registration_document->id}}" type="file" name="{{$vendor_registration_document->primary->slug}}" accept="image/*" data-rel="{{$vendor_registration_document->id}}">
                                        @elseif(strtolower($vendor_registration_document->file_type) == 'text')
                                            <input id="input_file_logo_{{$vendor_registration_document->id}}" type="file" name="{{$vendor_registration_document->primary->slug}}" accept=".xlsx, .xls, .csv, text/html, text/plain" data-rel="{{$vendor_registration_document->id}}">
                                        @else
                                            <input id="input_file_logo_{{$vendor_registration_document->id}}" type="file" name="{{$vendor_registration_document->primary->slug}}" accept=".pdf" data-rel="{{$vendor_registration_document->id}}">
                                        @endif
                                        <div class="invalid-feedback" id="{{$vendor_registration_document->primary->slug}}_error"><strong></strong></div>
                                    </div>
                                </div>      
                                 @endforeach   
                            </div>
                            <div class="form-row">
                                <div class="col-12 checkbox-input">
                                    <input type="checkbox" id="html" name="check_conditions" value="1">
                                    <label for="html">{{__('I accept the')}} <a href="{{url('page/terms-conditions')}}" target="_blank">{{__('Terms And Conditions')}}</a> {{__('and have read the')}} <a href="{{url('page/privacy-policy')}}" target="_blank"> {{__('Privacy Policy.')}}</a></label>
                                    <div class="invalid-feedback" id="check_conditions_error"><strong></strong></div>
                                </div>
                            </div>
                            <button class="btn btn-solid mt-3 w-100" dir="ltr" data-style="expand-right" id="register_btn" type="button">
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" id="register_btn_loader" style="display:none !important;"></span>
                                <span class="ladda-label">{{__('Submit')}}</span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        @endif
    </div>
</section>
</div>
@endsection
@section('script')
<script src="{{asset('assets/js/intlTelInput.js')}}"></script>
<script src="{{asset('front-assets/js/jquery.exitintent.js')}}"></script>
<script src="{{asset('front-assets/js/fly-cart.js')}}"></script>
<script src="{{asset('assets/libs/selectize/selectize.min.js')}}"></script>
<script src="{{asset('assets/libs/mohithg-switchery/mohithg-switchery.min.js')}}"></script>
<script src="{{asset('assets/libs/multiselect/multiselect.min.js')}}"></script>
<script src="{{asset('assets/libs/select2/select2.min.js')}}"></script>
<script src="{{asset('assets/libs/bootstrap-select/bootstrap-select.min.js')}}"></script>
<script type="text/javascript">
    var text_image = "{{url('images/104647.png')}}";
    $(document).ready(function() {
        // Initialize the intlTelInput
        var input = document.querySelector("#phone");
        var iti = window.intlTelInput(input, {
            separateDialCode: true,
            hiddenInput: "full_number",
            utilsScript: "{{asset('assets/js/utils.js')}}",
            initialCountry: "{{ Session::get('default_country_code', 'us') }}",
        });

        // Set the dial code on page load
        var initialCountryCode = "{{ Session::get('default_country_code', 'us') }}";

        // If a user exists, set the country based on the user's dial code
        @if($user)
            iti.setCountry(initialCountryCode);
            $('#dialCode').val(iti.getSelectedCountryData().dialCode);
        @else
            iti.setCountry(initialCountryCode);
            var dialCode = iti.getSelectedCountryData().dialCode;
            $('#dialCode').val(dialCode);
        @endif

        // Update the dial code when the country is manually changed by the user
        input.addEventListener("countrychange", function() {
            var selectedDialCode = iti.getSelectedCountryData().dialCode;
            $('#dialCode').val(selectedDialCode);
        });
    });

    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });
        function getExtension(filename) {
            return filename.split('.').pop().toLowerCase();
        }
        $("#phone").keypress(function(e) {
            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                return false;
            }
            return true;
        });
        $('#vendor_signup_form .select2-multiple').select2();
        function readURL(input, previewId) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                var extension = getExtension(input.files[0].name);
                reader.onload = function(e) {
                    if(extension == 'pdf'){
                        $(previewId).attr('src','https://image.flaticon.com/icons/svg/179/179483.svg');
                    }else if(extension == 'csv'){
                        $(previewId).attr('src',text_image);
                    }else if(extension == 'txt'){
                        $(previewId).attr('src',text_image);
                    }else if(extension == 'xls'){
                        $(previewId).attr('src',text_image);
                    }else if(extension == 'xlsx'){
                        $(previewId).attr('src',text_image);
                    }else{
                        $(previewId).attr('src',e.target.result);
                    }
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        $(document).on('change', '[id^=input_file_logo_]', function(event){
            var rel = $(this).data('rel');
            // $('#plus_icon_'+rel).hide();
            readURL(this, '#upload_logo_preview_'+rel);
        });
        $("#input_file_logo").change(function() {
            readURL(this, '#upload_logo_preview');
        });
        $("#input_file_banner").change(function() {
            readURL(this, '#upload_banner_preview');
        }); 
        var input = document.querySelector("#phone");
        window.intlTelInput(input, {
            separateDialCode: true,
            hiddenInput: "full_number",
            utilsScript: "{{asset('assets/js/utils.js')}}",
            initialCountry: "{{ Session::get('default_country_code','US') }}",
        });
        function initialize() {
            var input = document.getElementById('address');
            var autocomplete = new google.maps.places.Autocomplete(input);
            google.maps.event.addListener(autocomplete, 'place_changed', function() {
                var place = autocomplete.getPlace();
                document.getElementById('longitude').value = place.geometry.location.lng();
                document.getElementById('latitude').value = place.geometry.location.lat();
                for (let i = 1; i < place.address_components.length; i++) {
                    let mapAddress = place.address_components[i];
                    if (mapAddress.long_name != '') {
                        let streetAddress = '';
                        if (mapAddress.types[0] == "street_number") {
                            streetAddress += mapAddress.long_name;
                        }
                        if (mapAddress.types[0] == "route") {
                            streetAddress += mapAddress.short_name;
                        }
                        if ($('#street').length > 0) {
                            document.getElementById('street').value = streetAddress;
                        }
                        if (mapAddress.types[0] == "locality") {
                            document.getElementById('city').value = mapAddress.long_name;
                        }
                        if (mapAddress.types[0] == "administrative_area_level_1") {
                            document.getElementById('state').value = mapAddress.long_name;
                        }
                        if (mapAddress.types[0] == "postal_code") {
                            document.getElementById('pincode').value = mapAddress.long_name;
                        } else {
                            document.getElementById('pincode').value = '';
                        }
                        if (mapAddress.types[0] == "country") {
                            var country = document.getElementById('country');
                            for (let i = 0; i < country.options.length; i++) {
                                if (country.options[i].text.toUpperCase() == mapAddress.long_name.toUpperCase()) {
                                    country.value = country.options[i].value;
                                    break;
                                }
                            }
                        }
                    }
                }
            });
        }
        $('.iti__country').click(function() {
            var code = $(this).attr('data-country-code');
            $('#countryData').val(code);
            var dial_code = $(this).attr('data-dial-code');
            $('#dialCode').val(dial_code);
        });
        $('#register_btn').click(function() {
            var that = $(this);
            $(this).attr('disabled', true);
            $('#register_btn_loader').show();
            $('.form-control').removeClass("is-invalid");
            $('.invalid-feedback').children("strong").html('');
            var form = document.getElementById('vendor_signup_form');
            var formData = new FormData(form);
            $.ajax({
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                url: "{{ route('vendor.register') }}",
                headers: {
                    Accept: "application/json"
                },
                success: function(data) {
                    $('#register_btn_loader').hide();
                    that.attr('disabled', false);
                    if (data.status == 'success') {
                        $('input[type=file]').val('');
                        $("#vendor_signup_form")[0].reset();
                        $('#vendor_signup_form img').attr('src', '');
                        $('#IncludeCategory').val(null).trigger('change');
                        $('html,body').animate({scrollTop: '0px'}, 1000);
                        $('#success_msg').html(data.message).show();
                        setTimeout(function() {
                            $('#success_msg').html('').hide();
                        }, 3000);
                    }
                },
                error: function(response) {
                    that.attr('disabled', false);
                    $('html,body').animate({scrollTop: '0px'}, 1000);
                    $('#register_btn_loader').hide();
                    if (response.status === 422) {
                        let errors = response.responseJSON.errors;
                        Object.keys(errors).forEach(function(key) {
                            $("#" + key + "Input input").addClass("is-invalid");
                            $("#" + key + "_error").children("strong").text(errors[key][0]).show();
                            $("#" + key + "Input div.invalid-feedback").show();
                        });
                    } else {
                        $(".show_all_error.invalid-feedback").show();
                        $(".show_all_error.invalid-feedback").text('Something went wrong, Please try Again.');
                    }
                }
            });
        });
    });
</script>
@endsection