@extends('layouts.store', ['title' => 'Home'])
@section('css')
<link rel="stylesheet" href="{{asset('assets/css/intlTelInput.css')}}">
<style>
/* Transport type: keep theme radio + label::before/::after; avoid float (breaks inline layout) */
#vehicle_type_idInput .vehicle-type-picker {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 0.75rem 1.25rem;
}
#vendor_signup_form .required-asterisk{
    color: #dc3545;
    margin-left: 2px;
}
#vendor_signup_form .upload-actions{
    margin-top: 6px;
    display: flex;
    gap: 10px;
    align-items: center;
}
#vendor_signup_form .upload-actions .js-clear-upload{
    border: 0;
    background: transparent;
    padding: 0;
    color: #dc3545;
    font-size: 12px;
    line-height: 1.2;
    cursor: pointer;
    display: none;
}
#vehicle_type_idInput .vehicle-type-option .radio label {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
}
#vehicle_type_idInput .vehicle-type-icon {
    float: none !important;
    display: block;
    max-height: 48px;
    width: auto;
    max-width: 96px;
    object-fit: contain;
    vertical-align: middle;
}
</style>
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
        <form class="vendor-signup" id="vendor_signup_form" method="POST" name="form_data" action="{{route('page.driverSignup')}}" enctype="multipart/form-data">
            @csrf
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="alert alert-success" role="alert" id="success_msg" style="display:none;"></div>
                    <div class="row">
                        <div class="col-12">
                            <h2>{{__('Personal Details.')}}</h2>
                        </div>
                    </div>
                    <div class="needs-validation vendor-signup">
                        <div class="row">
                            {{-- <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="">{{__('UPLOAD PROFILE PHOTO')}}</label>
                                    <div class="file file--upload">
                                        <label for="input_file_logo">
                                            <span class="update_pic">
                                                <img src="" id="upload_logo_preview">
                                            </span>
                                            <span class="plus_icon">
                                                <i class="fa fa-plus"></i>
                                            </span>
                                        </label>
                                        <input id="input_file_logo" type="file" name="upload_photo" accept="image/*">
                                    </div>
                                </div>
                            </div> --}}
                            <div class="col-lg-6">
                                <div class="col-md-12 mb-3" id="nameInput">
                                    <div class="form-group" id="nameInputEdit">
                                        <label for="name" class="control-label">NAME <span class="required-asterisk">*</span></label>
                                        <input type="text" class="form-control" id="name" placeholder="John Doe" name="name" value="">
                                        <span class="invalid-feedback" id="name_error"role="alert">
                                            <strong></strong>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="col-md-12 mb-3" id="phone_numberInput">
                                    <label for="validationCustom02">{{__('CONTACT NUMBER.')}} <span class="required-asterisk">*</span></label>
                                    <input type="tel" class="form-control" name="phone_number" id="phone" value="{{old('full_number')}}">
                                    <span class="invalid-feedback" id="phone_number_error"><strong></strong></span>
                                    <input type="hidden" id="countryCode" name="country" value="{{ old('countryData') ? old('countryData') : 'us'}}">
                                    <input type="hidden" id="dialCode" name="country_code" value="{{ old('dialCode') ? old('dialCode') : Session::get('default_country_phonecode',1) }}">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="col-md-12 mb-3" id="full_nameInput">
                                    <div class="form-group" id="typeInput">
                                        <label for="type" class="control-label">TYPE <span class="required-asterisk">*</span></label>
                                        <select class="form-control" data-style="btn-light" name="type" id="type">
                                            <option value="Freelancer">Freelancer</option>
                                            <option value="Employee">Employee</option>
                                        </select>
                                        <span class="invalid-feedback" role="alert">
                                            <strong></strong>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row ">
                            <div class="col-md-12">
                                <div class="form-group" id="vehicle_type_idInput">
                                    <p class="text-muted mt-3 mb-2">TRANSPORT TYPE <span class="required-asterisk">*</span></p>
                                    <div class="vehicle-type-picker">
                                    @foreach ($transport_types as $item)
                                        <div class="radio radio-blue form-check-inline click cursors vehicle-type-option mb-0" data-vehicle-type-id="{{ $item->id }}">
                                            <input type="radio" id="vehicle_type_{{ $item->id }}" value="{{ $item->id }}" name="vehicle_type_id" act="add" {{ $loop->first ? 'checked' : '' }}>
                                            <label for="vehicle_type_{{ $item->id }}" class="mb-0 cursors">
                                                <img id="vehicle_type_img_{{ $item->id }}" class="vehicle-type-icon" src="{{ $item->resolved_icon }}" alt="{{ $item->name ?? $item->title ?? '' }}" data-default-icon="{{ $item->resolved_icon }}" data-active-icon="{{ $item->resolved_icon_active }}">
                                            </label>
                                        </div>
                                    @endforeach
                                    </div>
                                    <span class="invalid-feedback" role="alert">
                                        <strong></strong>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row ">
                        <div class="col-md-6">
                            <div class="form-group" id="make_modelInputEdit">
                                <label for="make_model" class="control-label">TRANSPORT DETAILS</label>
                                <input type="text" class="form-control" id="make_model" placeholder="Year, Make, Model" name="make_model" value="">
                                <span class="invalid-feedback" role="alert">
                                    <strong></strong>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group" id="emirate_idInput">
                                <label for="make_model" class="control-label">Emirates ID Number <span class="required-asterisk">*</span></label>
                                <input type="text" class="form-control" id="emirate_id" placeholder="784-2000-1234567-1" name="emirate_id" value="">
                                <span class="invalid-feedback" id="emirate_id_error"role="alert">
                                    <strong></strong>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="row ">
                        <div class="col-md-6">
                            <div class="form-group" id="plate_numberInputEdit">
                                <label for="plate_number" class="control-label">LICENCE PLATE</label>
                                <input type="text" class="form-control" id="plate_number" name="plate_number" placeholder="508.KLV" value="">
                                <span class="invalid-feedback" role="alert">
                                    <strong></strong>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group" id="colorInputEdit">
                                <label for="color" class="control-label">COLOR</label>
                                <input type="text" class="form-control" id="color" name="color" placeholder="Color" value="">
                                <span class="invalid-feedback" role="alert">
                                    <strong></strong>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        @foreach($driver_registration_documents as $driver_registration_document)
                        @php
                            $docField = strtolower(str_replace(' ', '_', $driver_registration_document->name));
                        @endphp
                        <div class="col-md-6 mb-3" id="{{ $docField }}Input">
                            <label for="">{{ $driver_registration_document->name ? $driver_registration_document->name : '' }}@if($driver_registration_document->is_required) <span class="required-asterisk">*</span>@endif</label>
                            @if(strtolower($driver_registration_document->file_type) == 'text')
                            <div class="form-group">
                                <input type="text" class="form-control" id="input_file_logo_{{$driver_registration_document->id}}" name="{{ $docField }}" placeholder="Enter Text" value="">
                                <div class="invalid-feedback" id="{{ $docField }}_error"><strong></strong></div>
                            </div>
                            @else
                            <div class="file file--upload">
                                <label for="input_file_logo_{{$driver_registration_document->id}}">
                                    <span class="update_pic pdf-icon">
                                        <img src="" id="upload_logo_preview_{{$driver_registration_document->id}}">
                                    </span>
                                    <span class="plus_icon" id="plus_icon_{{$driver_registration_document->id}}">
                                        <i class="fa fa-plus"></i>
                                    </span>
                                </label>
                                @if(strtolower($driver_registration_document->file_type) == 'image')
                                <input id="input_file_logo_{{$driver_registration_document->id}}" type="file" name="{{ $docField }}" accept="image/*" data-rel="{{$driver_registration_document->id}}">
                                @elseif(strtolower($driver_registration_document->file_type) == 'pdf')
                                <input id="input_file_logo_{{$driver_registration_document->id}}" type="file" name="{{ $docField }}" accept=".pdf" data-rel="{{$driver_registration_document->id}}">
                                @endif
                                <div class="upload-actions">
                                    <button
                                        type="button"
                                        class="js-clear-upload"
                                        data-rel="{{$driver_registration_document->id}}"
                                        data-input-id="input_file_logo_{{$driver_registration_document->id}}"
                                        data-preview-id="upload_logo_preview_{{$driver_registration_document->id}}"
                                        data-plus-id="plus_icon_{{$driver_registration_document->id}}"
                                    >{{ __('Remove') }}</button>
                                </div>
                                <div class="invalid-feedback" id="{{ $docField }}_error"><strong></strong></div>
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                    <p id="data-error" style="color:red;"></p>
                    <p id="data-error1" style="color:green;"></p>

                    <div class="form-row">
                        <div class="col-12 checkbox-input" id="check_conditionsInput">
                            <input type="checkbox" id="html" name="check_conditions" value="1">
                            <label for="html">{{__('I accept the')}} <a href="{{url('page/terms-conditions')}}" target="_blank">{{__('Terms And Conditions')}}</a> {{__('and have read the')}} <a href="{{url('page/privacy-policy')}}" target="_blank"> {{__('Privacy Policy.')}}</a> <span class="required-asterisk">*</span></label>
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
    </div>
</section>
</div>
@endsection
@section('script')
<script src="{{ asset('assets/js/jquery.tagsinput-revisited.js') }}"></script>
<link rel="stylesheet" href="{{ asset('assets/css/jquery.tagsinput-revisited.css') }}" />
<script src="{{asset('assets/js/intlTelInput.js')}}"></script>
<script src="{{asset('front-assets/js/jquery.exitintent.js')}}"></script>
<script src="{{asset('front-assets/js/fly-cart.js')}}"></script>
<script type="text/javascript">
    var tagList = "{{$showTag}}";
    tagList = tagList.split(',');

    function makeTag() {
        $('.myTag1').tagsInput({
            'autocomplete': {
                source: tagList,
            }
        });
    }
    var mobile_number = '';
    // $('#add-agent-modal .xyz').val(mobile_number.getSelectedCountryData().dialCode); 
    $('#add-agent-modal .xyz').change(function() {
        var phonevalue = $('.xyz').val();
        $("#countryCode").val(mobile_number.getSelectedCountryData().dialCode);
    });

    function phoneInput() {
        console.log('phone working');
        var input = document.querySelector(".xyz");

        var mobile_number_input = document.querySelector(".xyz");
        mobile_number = window.intlTelInput(mobile_number_input, {
            separateDialCode: true,
            hiddenInput: "full_number",
            utilsScript: "{{ asset('telinput/js/utils.js') }}",
        });
    }
    function syncVehicleTypeIcons() {
        $('#vehicle_type_idInput .vehicle-type-option').each(function() {
            var $img = $(this).find('img.vehicle-type-icon');
            var $inp = $(this).find('input[type="radio"]');
            var def = $img.attr('data-default-icon') || '';
            var active = $img.attr('data-active-icon') || '';
            if ($inp.is(':checked') && active) {
                $img.attr('src', active);
            } else {
                $img.attr('src', def);
            }
        });
    }
    $(document).ready(function() {
        var $first = $('#vehicle_type_idInput .vehicle-type-option').first();
        if ($first.length) {
            $first.find('input[type="radio"]').prop('checked', true);
        }
        syncVehicleTypeIcons();
    });
    $(document).on('click', '#vehicle_type_idInput .vehicle-type-option', function() {
        $(this).find('input[type="radio"]').prop('checked', true);
        syncVehicleTypeIcons();
    });
    /* Get agent by ajax */
    $(".editIcon").click(function(e) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });
        e.preventDefault();
        var uid = $(this).attr('agentId');
        $.ajax({
            type: "get",
            url: "<?php echo url('agent'); ?>" + '/' + uid + '/edit',
            data: '',
            dataType: 'json',
            success: function(data) {
                $('#edit-agent-modal #editCardBox').html(data.html);
                $('#edit-agent-modal').modal({
                    backdrop: 'static',
                    keyboard: false
                });
                makeTag();
                phoneInput();
                //$('.dropify').dropify();
                var imgs = $('#profilePic').attr('showImg');
                $('#profilePic').attr("data-default-file", imgs);
                $('#profilePic').dropify();
                $('').dropify();
            },
            error: function(data) {
                console.log('data2');
            }
        });
    });
    /* add Team using ajax*/
    // $("#add-agent-modal #submitAgent").submit(function(e) {

    // });
    $("#submitAgent").submit(function(e) {
        e.preventDefault();
        // $(document).on('click', '.submitAgentForm', function() { 
        var form = document.getElementById('submitAgent');
        var formData = new FormData(form);
        var urls = "{{URL::route('page.driverSignup')}}";
        saveTeam(urls, formData, inp = '', modal = 'add-agent-modal');
    });
    /* edit Team using ajax*/
    $("#edit-agent-modal #UpdateAgent").submit(function(e) {
        e.preventDefault();
    });
    $(document).on('click', '.submitEditForm', function() {
        var form = document.getElementById('UpdateAgent');
        var formData = new FormData(form);
        var urls = document.getElementById('agent_id').getAttribute('url');
        saveTeam(urls, formData, inp = 'Edit', modal = 'edit-agent-modal');
        console.log(urls);
    });

    function saveTeam(urls, formData, inp = '', modal = '') {
        $.ajax({
            method: 'post',
            headers: {
                Accept: "application/json"
            },
            url: urls,
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                if (response.status == 'success') {
                    $("#" + modal + " .close").click();
                    location.reload();
                } else {
                    $(".show_all_error.invalid-feedback").show();
                    $(".show_all_error.invalid-feedback").text(response.message);
                }
                return response;
            },
            error: function(response) {
                if (response.status === 422) {
                    let errors = response.responseJSON.errors;
                    Object.keys(errors).forEach(function(key) {
                        $("#" + key + "Input" + inp + " input").addClass("is-invalid");
                        $("#" + key + "Input" + inp + " span.invalid-feedback").children("strong").text(errors[key][0]);
                        $("#" + key + "Input span.invalid-feedback").show();
                    });
                } else {
                    $(".show_all_error.invalid-feedback").show();
                    $(".show_all_error.invalid-feedback").text('Something went wrong, Please try Again.');
                }
                return response;
            }
        });
    }
    /* Get agent by ajax */
    $(".submitpayreceive").click(function(e) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });
        e.preventDefault();
        $.ajax({
            type: "post",
            url: "",
            data: '',
            dataType: 'json',
            success: function(data) {
                $('#edit-agent-modal #editCardBox').html(data.html);
                $('#edit-agent-modal').modal({
                    backdrop: 'static',
                    keyboard: false
                });
                makeTag();
                //$('.dropify').dropify();
                var imgs = $('#profilePic').attr('showImg');
                $('#profilePic').attr("data-default-file", imgs);
                $('#profilePic').dropify();
                $('').dropify();
            },
            error: function(data) {
                console.log('data2');
            }
        });
    });
</script>
<script type="text/javascript">
    var text_image = "{{url('images/104647.png')}}";
    var pdf_image = "{{url('images/pdf.webp')}}";

    $(document).ready(function() {
        makeTag();
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

        function readURL(input, previewId) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                var extension = getExtension(input.files[0].name);
                reader.onload = function(e) {
                    if (extension == 'pdf') {
                        $(previewId).attr('src', pdf_image);
                    } else if (extension == 'csv') {
                        $(previewId).attr('src', text_image);
                    } else if (extension == 'txt') {
                        $(previewId).attr('src', text_image);
                    } else if (extension == 'xls') {
                        $(previewId).attr('src', text_image);
                    } else if (extension == 'xlsx') {
                        $(previewId).attr('src', text_image);
                    } else {
                        $(previewId).attr('src', e.target.result);
                    }
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        $(document).on('change', '[id^=input_file_logo_]', function(event) {
            var rel = $(this).data('rel');
            // Show remove button and hide plus icon when selected
            if (rel !== undefined) {
                $('#plus_icon_' + rel).hide();
                $('.js-clear-upload[data-rel="' + rel + '"]').show();
            }
            readURL(this, '#upload_logo_preview_' + rel);
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
                url: "{{ route('page.driverSignup') }}",
                headers: {
                    Accept: "application/json"
                },
                success: function(data) {
                    $('#register_btn_loader').hide();
                    that.attr('disabled', false);
                    if (data.status == 200) {
                        $('input[type=file]').val('');
                        $("#vendor_signup_form")[0].reset();
                        $('#vendor_signup_form img').attr('src', '');
                        $('#vendor_signup_form .form-control, #vendor_signup_form input, #vendor_signup_form select').removeClass('is-invalid');
                        $('#vendor_signup_form .invalid-feedback strong').text('');
                        $('#vendor_signup_form .invalid-feedback').hide();

                        $('html,body').animate({
                            scrollTop: '0px'
                        }, 1000);
                        $('#success_msg').html(data.message).show();
                        setTimeout(function() {
                            $('#success_msg').html('').hide();
                        }, 3000);
                    }

                    console.log(data);
                    if (data.status == 200) {
                        $("#data-error1").empty();
                        $("#data-error1").html(data.message);
                    } else {
                        $("#data-error").empty();
                        $("#data-error").html(data.message);
                    }
                },
                error: function(response) {
                    $("#data-error").empty();
                    // console.log(data);
                    $("#data-error").append(response.data);
                    that.attr('disabled', false);
                    $('html,body').animate({
                        scrollTop: '0px'
                    }, 1000);
                    $('#register_btn_loader').hide();
                    if (response.status === 422) {
                        let errors = response.responseJSON.errors;
                        Object.keys(errors).forEach(function(key) {
                            // Support inputs/selects/textareas and span/div invalid-feedback
                            var $container = $("#" + key + "Input");
                            $container.find("input, select, textarea").addClass("is-invalid");

                            var $directError = $("#" + key + "_error");
                            if ($directError.length) {
                                $directError.children("strong").text(errors[key][0]);
                                $directError.show();
                            }

                            var $containerFeedback = $container.find(".invalid-feedback");
                            if ($containerFeedback.length) {
                                // If the container feedback has a <strong>, populate it too
                                $containerFeedback.children("strong").text(errors[key][0]);
                                $containerFeedback.show();
                            }
                        });
                    } else {

                        $(".show_all_error.invalid-feedback").show();
                        $(".show_all_error.invalid-feedback").text('Something went wrong, Please try Again.');
                    }
                }

            });
        });

        // Clear invalid highlight as soon as user fixes fields
        $(document).on('input change', '#vendor_signup_form input, #vendor_signup_form select, #vendor_signup_form textarea', function() {
            var name = $(this).attr('name') || '';
            if (!name) return;
            name = name.replace('[]', '');

            $(this).removeClass('is-invalid');
            $("#" + name + "_error").children("strong").text('');
            $("#" + name + "_error").hide();
            $("#" + name + "Input .invalid-feedback").children("strong").text('');
            $("#" + name + "Input .invalid-feedback").hide();
            $("#" + name + "Input input, #" + name + "Input select, #" + name + "Input textarea").removeClass('is-invalid');
        });

        // Allow clearing an already selected/uploaded file
        $(document).on('click', '.js-clear-upload', function() {
            var inputId = $(this).data('input-id');
            var previewId = $(this).data('preview-id');
            var plusId = $(this).data('plus-id');
            if (!inputId) return;

            var $input = $('#' + inputId);
            $input.val('');
            if (previewId) {
                $('#' + previewId).attr('src', '');
            }
            if (plusId) {
                $('#' + plusId).show();
            }
            $(this).hide();

            // also clear any validation state for this field
            var name = $input.attr('name') || '';
            if (name) {
                name = name.replace('[]', '');
                $("#" + name + "_error").children("strong").text('').end().hide();
                $("#" + name + "Input .invalid-feedback").children("strong").text('').end().hide();
                $("#" + name + "Input input, #" + name + "Input select, #" + name + "Input textarea").removeClass('is-invalid');
            }
        });
    });
</script>

@endsection