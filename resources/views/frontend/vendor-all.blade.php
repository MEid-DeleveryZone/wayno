@extends('layouts.store', ['title' => __('All Vendors')])
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
<section class="section-b-space new-pages pb-265">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2 class="mb-3">{{__('All Vendors')}}</h2>
            </div>
        </div>
        <div class="row margin-res">
            @foreach($vendors as $vendor)
                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12 mb-2">
                    <a class="suppliers-box d-block px-2" href="{{route('vendorDetail', $vendor->slug)}}">
                        <div class="suppliers-img-outer">
                            <img class="fluid-img mx-auto" src="{{$vendor->logo['image_fit']}}200/200{{$vendor->logo['image_path']}}" alt="">
                        </div>
                        <div class="supplier-rating">
                            <h6 class="mb-1">{{$vendor->name}}</h6>
                            <p title="{{$vendor->categoriesList}}" class="vendor-cate border-bottom pb-1 mb-1 ellips">{{$vendor->categoriesList}}</p>
                            <div class="product-timing">
                                <small title="{{$vendor->address}}" class="ellips d-block"><i class="fa fa-map-marker"></i> {{$vendor->address}}</small>
                                @if(isset($vendor->timeofLineOfSightDistance))
                                    <ul class="timing-box">
                                        <li>
                                            <small class="d-block"><img class="d-inline-block mr-1" src="{{ asset('front-assets/images/distance.png') }}" alt=""> {{$vendor->lineOfSightDistance}} km</small>
                                        </li>
                                        <li>
                                            <small class="d-block mx-1"><i class="fa fa-clock-o"></i> {{$vendor->timeofLineOfSightDistance}} min</small>
                                        </li>
                                    </ul>
                                @endif
                            </div>
                            @if($client_preference_detail)
                                @if($client_preference_detail->rating_check == 1)
                                    @if($vendor->vendorRating > 0)
                                        <ul class="custom-rating m-0 p-0">
                                            @for($i=0; $i < 5; $i++)
                                                @php 
                                                if($i <= $vendor->vendorRating){
                                                    $starFillClass = 'fa-star';
                                                }else{
                                                    $starFillClass = 'fa-star-o';
                                                }
                                                @endphp
                                                <li><i class="fa {{$starFillClass}}" aria-hidden="true"></i></li>
                                            @endfor
                                        </ul>
                                    @endif
                                @endif
                            @endif
                        </div>
                    </a>
                </div>

            {{-- <div class="col-xl-3 col-6 col-grid-box mt-3">
                <div class="product-box scale-effect text-center">
                    <div class="img-wrapper">
                        <div class="front">
                            <a href="{{route('vendorDetail')}}/{{ $vendor->slug }}">
                                <img class="img-fluid blur-up lazyloaded" src="{{ $vendor->logo['proxy_url'] .'200/200'. $vendor->logo['image_path'] }}" alt="">
                            </a>
                        </div>
                    </div>
                    <div class="product-detail">
                        <div class="inner_spacing text-center">
                            <a href="{{route('vendorDetail')}}/{{ $vendor->slug }}">
                                <h3>{{ $vendor->name }}</h3>
                                @if($client_preference_detail)
                                    @if($client_preference_detail->rating_check == 1)
                                        <div class="custom_rating">
                                            @if($vendor->vendorRating > 0)
                                                <span class="rating">{{$vendor->vendorRating}} <i class="fa fa-star text-white p-0"></i></span>
                                            @endif
                                        </div>
                                    @endif
                                @endif
                            </a>
                        </div>
                    </div>
                </div>
            </div> --}}
            @endforeach
        </div>
    </div>
</section>
@endsection
@section('script')
<script src="{{asset('assets/js/intlTelInput.js')}}"></script>
<script src="{{asset('front-assets/js/jquery.exitintent.js')}}"></script>
<script src="{{asset('front-assets/js/fly-cart.js')}}"></script>
<script type="text/javascript">
    var text_image = "{{url('images/104647.png')}}";
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

        function readURL(input, previewId) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                var extension = getExtension(input.files[0].name);
                reader.onload = function(e) {
                    if (extension == 'pdf') {
                        $(previewId).attr('src', 'https://image.flaticon.com/icons/svg/179/179483.svg');
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
            // $('#plus_icon_'+rel).hide();
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
                        $('html,body').animate({
                            scrollTop: '0px'
                        }, 1000);
                        $('#success_msg').html(data.message).show();
                        setTimeout(function() {
                            $('#success_msg').html('').hide();
                        }, 3000);
                    }
                },
                error: function(response) {
                    that.attr('disabled', false);
                    $('html,body').animate({
                        scrollTop: '0px'
                    }, 1000);
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