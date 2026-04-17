@extends('layouts.vertical', ['demo' => 'creative', 'title' => 'Styling - Web Styling'])
@section('css')
    <link href="{{ asset('assets/libs/dropzone/dropzone.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/dropify/dropify.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/spectrum/1.8.1/spectrum.min.css">
@endsection
@section('content')

    <div class="row">
        <div class="col-12">
            <div class="col-sm-8">
                <div class="text-sm-left">
                    @if (\Session::has('success'))
                        <div class="alert alert-success">
                            <span>{!! \Session::get('success') !!}</span>
                        </div>
                    @endif
                    @if (\Session::has('error_delete'))
                        <div class="alert alert-danger">
                            <span>{!! \Session::get('error_delete') !!}</span>
                        </div>
                    @endif
                </div>
            </div>
            <div class="page-title-box">
                <h4 class="page-title">{{ __('Web Styling') }}</h4>
            </div>
        </div>
    </div>

    <form id="favicon-form" method="post" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">{{ __('Home Page Style') }}</h4>
                        <div class="row">
                            @foreach ($homepage_style_options as $homepage_style)
                                <div class="col-sm-6">
                                    <div class="card mb-0">
                                        <div class="card-body p-2">
                                            <div class="row">
                                                <div class="col-sm-12 custom-control custom-radio radio_new p-0">
                                                    <input type="radio"
                                                        {{ $homepage_style->is_selected == 1 ? 'checked' : '' }}
                                                        value="{{ $homepage_style->id }}"
                                                        onchange="submitHomePageForm(this.id)"
                                                        id="{{ $homepage_style->id }}" name="home_styles"
                                                        class="custom-control-input " }}>
                                                    <label class="custom-control-label" for="{{ $homepage_style->id }}">
                                                        <img class="card-img-top img-fluid"
                                                            src="{{ url('images/' . $homepage_style->image) }}"
                                                            alt="Card image cap">
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="card card-box">
                    <div class="row">
                        <div class="col-5">
                            <h4 class="header-title">{{ __('Favicon') }}</h4>
                            <div class="mb-0">
                                <label>{{ __('Upload Favicon') }}</label>
                                <input type="file" accept="image/*"
                                    data-default-file="{{ $client_preferences->favicon ? $client_preferences->favicon['proxy_url'] . '600/400' . $client_preferences->favicon['image_path'] : '' }}"
                                    data-plugins="dropify" name="favicon" class="dropify" id="image" />
                                <label class="logo-size d-block text-right mt-1">{{ __('Icon Size') }} 32x32</label>
                                <span class="invalid-feedback" role="alert">
                                    <strong></strong>
                                </span>
                            </div>
                        </div>
                        <div class="col-7">
                            <h4 class="header-title">{{ __('Color') }}</h4>
                            <div class="form-group">
                                <label for="primary_color">{{ __('Primary Color') }}</label>
                                <input type="text" id="primary_color_option" name="primary_color" class="form-control"
                                    value="{{ old('primary_color', $client_preferences->web_color ?? 'cccccc') }}">
                            </div>
                            <div class="form-group mb-0">
                                <label>{{ __('Top Header Color') }}</label>
                                <input type="text" id="site_top_header_color" name="site_top_header_color"
                                    class="form-control"
                                    value="{{ old('site_top_header_color', $client_preferences->site_top_header_color ?? '#4c4c4c') }}">
                            </div>
                        </div>
                    </div>
                </div>


            </div>
            <div class="col-md-6 col-xl-3">
                @if ($client_preference_detail->business_type != 'taxi')

                    <div class="card card-box">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h4 class="header-title mb-0">{{ __('Age Restriction Popup') }}</h4>
                            <div class="mb-0">
                                <input type="checkbox" id="age_restriction" data-plugin="switchery" name="age_restriction"
                                    class="chk_box1" data-color="#43bee1"
                                    {{ $client_preferences->age_restriction == 1 ? 'checked' : '' }}>
                            </div>
                        </div>
                        <label for="">{{ __('Title') }}</label>
                        <input type="text" class="form-control" id="age_restriction_title" name="age_restriction_title"
                            value="{{ old('age_restriction_title', $client_preferences->age_restriction_title ?? '') }}">
                    </div>

                @endif
                <div class="card card-box">
                    <ul class="pl-0 mb-0">
                        <li class="d-flex flex-column justify-content-start mt-2">
                            <h4 class="header-title mb-2">{{ __('Show Dark Mode') }}</h4>
                            <div class="form-group">
                                <ul class="list-inline">
                                    <li class="d-inline-block ml-3 mr-2">
                                        <input type="radio" class="custom-control-input check"
                                            onchange="submitDarkMmode('0')" id="option1" name="show_dark_mode"
                                            {{ $client_preferences->show_dark_mode == 0 ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="option1">{{ __('Day') }}</label>
                                    </li>
                                    <li class="d-inline-block ml-3 mr-2 mb-2 mb-lg-0">
                                        <input type="radio" class="custom-control-input check"
                                            onchange="submitDarkMmode('1')" id="option2" name="show_dark_mode"
                                            {{ $client_preferences->show_dark_mode == 1 ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="option2">{{ __('Night') }}</label>
                                    </li>
                                    <li class="d-inline-block ml-3">
                                        <input type="radio" class="custom-control-input check"
                                            onchange="submitDarkMmode('2')" id="option3" name="show_dark_mode"
                                            {{ $client_preferences->show_dark_mode == 2 ? 'checked' : '' }}>
                                        <label class="custom-control-label"
                                            for="option3">{{ __('Day with Toggle') }}</label>
                                    </li>
                                </ul>
                            </div>
                            <!-- <div class="mb-0">
                                <input type="checkbox" id="show_dark_mode" data-plugin="switchery" name="show_dark_mode" class="chk_box2" data-color="#43bee1" {{ $client_preferences->show_dark_mode == 1 ? 'checked' : '' }}>
                            </div> -->
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="card card-box">
                    <ul class="pl-0 mb-0">

                        @if ($client_preference_detail->business_type != 'taxi')
                            <li class="d-flex align-items-center justify-content-between">
                                <h4 class="header-title mb-2">{{ __('Show Wishlist Icon') }}</h4>
                                <div class="mb-0">
                                    <input type="checkbox" id="show_wishlist" data-plugin="switchery" name="show_wishlist"
                                        class="chk_box2" data-color="#43bee1"
                                        {{ $client_preferences->show_wishlist == 1 ? 'checked' : '' }}>
                                </div>
                            </li>
                            <li class="d-flex align-items-center justify-content-between mt-2">
                                <h4 class="header-title mb-2">{{ __('Show Ratings') }}</h4>
                                <div class="mb-0">
                                    <input type="checkbox" id="rating_enable" data-plugin="switchery" name="rating_enable"
                                        class="chk_box2" data-color="#43bee1"
                                        {{ $client_preferences->rating_check == 1 ? 'checked' : '' }}>
                                </div>
                            </li>
                            <li class="d-flex align-items-center justify-content-between mt-2">
                                <h4 class="header-title mb-2">{{ __('Show Cart Icon') }}</h4>
                                <div class="mb-0">
                                    <input type="checkbox" id="cart_enable" data-plugin="switchery" name="cart_enable"
                                        class="chk_box1" data-color="#43bee1"
                                        {{ $client_preferences->cart_enable == 1 ? 'checked' : '' }}>
                                </div>
                            </li>
                        @endif

                        <li class="d-flex align-items-center justify-content-between mt-2">
                            <h4 class="header-title mb-2">{{ __('Show Contact Us') }}</h4>
                            <div class="mb-0">
                                <input type="checkbox" id="show_contact_us" data-plugin="switchery" name="show_contact_us"
                                    class="chk_box2" data-color="#43bee1"
                                    {{ $client_preferences->show_contact_us == 1 ? 'checked' : '' }}>
                            </div>
                        </li>
                        @if ($client_preference_detail->business_type != 'taxi')
                            <li class="d-flex align-items-center justify-content-between mt-2">
                                <h4 class="header-title mb-2">{{ __('Show Icons in navigation') }}</h4>
                                <div class="mb-0">
                                    <input type="checkbox" id="show_icons" data-plugin="switchery" name="show_icons"
                                        class="chk_box2" data-color="#43bee1"
                                        {{ $client_preferences->show_icons == 1 ? 'checked' : '' }}>
                                </div>
                            </li>
                        @endif
                        <li class="d-flex align-items-center justify-content-between mt-2">
                            <h4 class="header-title mb-2">{{ __('Show Payment Icons') }}</h4>
                            <div class="mb-0">
                                <input type="checkbox" id="show_payment_icons" data-plugin="switchery"
                                    name="show_payment_icons" class="chk_box2" data-color="#43bee1"
                                    {{ $client_preferences->show_payment_icons == 1 ? 'checked' : '' }}>
                            </div>
                        </li>
                        @if ($client_preference_detail->business_type != 'taxi')
                            <li class="d-flex align-items-center justify-content-between mt-2">
                                <h4 class="header-title mb-2">{{ __('Hide Nav Bar') }}</h4>
                                <div class="mb-0">
                                    <input type="checkbox" id="hide_nav_bar" data-plugin="switchery" name="hide_nav_bar"
                                        class="chk_box2" data-color="#43bee1"
                                        {{ $client_preferences->hide_nav_bar == 1 ? 'checked' : '' }}>
                                </div>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>


    </form>




    <div class="col-lg-3 col-md-6 mb-3">
        <form method="POST" action="{{ route('web.styling.update_contact_up') }}">
            @csrf
            <div class="row h-100">
                <div class="col-12">
                    <div class="card-box h-100">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <h4 class="header-title mb-0">{{ __('Contact Us') }}</h4>
                            <button class="btn btn-info d-block" type="submit"> {{ __('Save') }} </button>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-12 mt-3 ">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group mb-0">
                                                    <label for="contact_address">{{ __('Company Address') }}</label>
                                                    <div class="input-group">
                                                        <input type="text" name="contact_address" id="contact_address"
                                                            class="form-control"
                                                            value="{{ old('contact_address', $clientContact->contact_address ?? '') }}">
                                                    </div>
                                                    @if ($errors->has('contact_address'))
                                                        <span class="text-danger" role="alert">
                                                            <strong>{{ $errors->first('contact_address') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="form-group mt-3 mb-0">
                                                    <label for="contact_phone_number">{{ __('Contact Number') }}</label>
                                                    <input type="text" name="contact_phone_number" id="contact_phone_number"
                                                        placeholder="" class="form-control"
                                                        value="{{ old('contact_phone_number', $clientContact->contact_phone_number ?? '') }}">
                                                    @if ($errors->has('contact_phone_number'))
                                                        <span class="text-danger" role="alert">
                                                            <strong>{{ $errors->first('contact_phone_number') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="form-group mt-3 mb-0">
                                                    <label for="contact_email">{{ __('Contact Email') }}</label>
                                                    <input type="text" name="contact_email" id="contact_email"
                                                        placeholder="" class="form-control"
                                                        value="{{ old('contact_email', $clientContact->contact_email ?? '') }}">
                                                    @if ($errors->has('contact_email'))
                                                        <span class="text-danger" role="alert">
                                                            <strong>{{ $errors->first('contact_email') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- cab booking template -->
    <form id="favicon-form-pickup" method="post" enctype="multipart/form-data">
        <div class="row">
            <div class="col-xl-8" ondrop="drop(event)" ondragover="allowDrop(event)">
                <div class="card-box home-options-list">
                    <div class="row mb-2">
                        <div class="col-sm-8">
                            <h4 class="page-title mt-0">{{ __('Home Page') }}</h4>
                            <p class="sub-header">
                                {{ __('Drag & drop to edit different sections.') }}
                            </p>
                        </div>
                        {{-- <div class="col-sm-4 text-right">
                    <button class="btn btn-info waves-effect waves-light text-sm-right" id="add_pickup_delivery_section_button"   data-toggle="modal" data-target="#add_pickup_delivery_section">Add</button>
                </div> --}}
                        <div class="col-sm-4 text-right">
                            <button class="btn btn-info waves-effect waves-light text-sm-right"
                                id="save_home_page_pickup">{{ __('Save') }}</button>
                        </div>
                    </div>

                    <div class="custom-dd-empty dd" id="pickup_datatable">
                        <ol class="dd-list p-0" id="pickup_ol">
                            @foreach ($cab_booking_layouts as $key => $home_page_label)
                                <li class="dd-item dd3-item d-flex align-items-center on_click{{ $home_page_label->slug }}"
                                    data-id="1" data-row-id="{{ $home_page_label->id }}">
                                    <a herf="#" class="dd-handle dd3-handle d-block mr-auto">
                                        {{ $home_page_label->title }}
                                    </a>

                                    <div class="language-inputs style-4">
                                        <div class="row no-gutters flex-nowrap align-items-center my-2">
                                            @foreach ($langs as $lang)
                                                @php
                                                    $exist = 0;
                                                    $value = '';
                                                @endphp
                                                <div class="col-2 pl-1">
                                                    <input class="form-control" type="hidden"
                                                        value="{{ $home_page_label->id }}" name="home_labels[]">
                                                    <input class="form-control" type="hidden"
                                                        value="{{ $lang->langId }}" name="languages[]">
                                                    @foreach ($home_page_label->translations as $translation)
                                                        @if ($translation->language_id == $lang->langId)
                                                            @php
                                                                $exist = 1;
                                                                $value = $translation->title;
                                                            @endphp
                                                        @endif
                                                    @endforeach
                                                    <input class="form-control" value="{{ $exist == 1 ? $value : '' }}"
                                                        type="text" name="names[]" placeholder="{{ $lang->langName }}">
                                                </div>
                                            @endforeach



                                        </div>
                                    </div>
                                    @if ($home_page_label->slug == 'pickup_delivery')
                                        <div class="col-2 pl-1">
                                            <select class="form-control select2-multiple" required id="categories"
                                                name="categories[{{ $key }}][check]" data-toggle="select2"
                                                data-placeholder="Choose ...">

                                                {{-- <select class="form-control w-100"> --}}
                                                @foreach ($all_pickup_category as $category)
                                                    <option value="{{ $category->id }}" @if (isset($home_page_label->pickupCategories->first()->categoryDetail) && !empty($home_page_label->pickupCategories->first()) && $home_page_label->pickupCategories->first()->categoryDetail->id == $category->id)
                                                        selected="selected"
                                                @endif>{{ $category->translation_one->name ?? '' }}
                                                </option>
                                    @endforeach
                                    </select>
                    </div>
                    @endif
                    <div class="mb-0 ml-3">
                        <input class="form-control" type="hidden" value="{{ $home_page_label->id }}"
                            name="pickup_labels[]">

                        <input type="checkbox" {{ $home_page_label->is_active == 1 ? 'checked' : '' }}
                            id="{{ $home_page_label->slug }}" data-plugin="switchery"
                            name="is_active[{{ $key }}][check]" class="chk_box2" data-color="#43bee1">
                    </div>
                    @if ($home_page_label->slug == 'pickup_delivery')
                        <a class="action-icon openBannerModal" userId="{{ $home_page_label->id }}"
                            data-row-id="{{ $home_page_label->id }}" href="javascript:void(0);">
                            <i class="mdi mdi-pencil"></i>
                        </a>
                    @endif
                    @if ($home_page_label->slug == 'dynamic_page')
                        <a class="action-icon edit_dynamic_page" data-row-id="{{ $home_page_label->id }}"
                            href="javascript:void(0);">
                            <i class="mdi mdi-pencil"></i>
                        </a>
                    @endif
                    <a class="action-icon deletePickupSectionx"
                        href="{{ route('pickup.delete.section', $home_page_label->id) }}"
                        onclick="return confirm('Are you sure you want to delete this section?');"
                        dataid="{{ $home_page_label->id }}" href="javascript:void(0);">
                        <i class="mdi mdi-delete"></i>
                    </a>

                    </li>
                    @endforeach
                    </ol>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card-box home-options-list">
                <div class="row mb-2">
                    <div class="col-sm-8">
                        <h4 class="page-title mt-0">{{ __('Home Page Sections') }}</h4>
                        <p class="sub-header">
                            {{ __('Drag & drop to home page sections') }}
                        </p>
                    </div>

                </div>

                <div class="custom-dd-empty dd" id="homepage_datatablex">
                    <ol class="dd-list p-0" id="homepage_ol">
                        @foreach ($home_page_labels as $home_page_label)
                            <li class="dd-item dd3-item d-flex align-items-center" id="drag{{ $home_page_label->id }}"
                                data-id="1" data-row-id="{{ $home_page_label->id }}" draggable="true"
                                ondragstart="drag(event)">
                                <a herf="#" class="dd-handle dd3-handle d-block mr-auto">
                                    @if ($home_page_label->slug == 'vendors')

                                        @php
                                            $vendorLable = getNomenclatureName('Vendors', true);
                                            $vendorLable = $vendorLable === 'Vendors' ? __('Vendors') : $vendorLable;
                                        @endphp

                                        {{ $vendorLable }}
                                    @else
                                        {{ $home_page_label->title }}
                                    @endif
                                </a>



                            </li>
                        @endforeach
                    </ol>
                </div>
            </div>
        </div>
        </div>

    </form>



    <!-- html Modal -->
    <div class="modal fade" id="edit_dynamic_html" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="edit_dynamic_htmllabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header pb-0">
                    <h5 class="modal-title" id="edit_dynamic_htmllabel">{{ __('Edit Section') }}</h5>
                    <button type="button" class="close right-top" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body py-0 px-2" id="edit_dynamic_html_desc">



                </div>
            </div>
        </div>
    </div>
    <!-- end modal for add section -->

    <!-- cab banner Modal -->
    <div id="edit-form" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header border-bottom">
                    <h4 class="modal-title">{{ __('Edit Background Image') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form id="save_edit_banner_form" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body" id="editCardBox">

                    </div>
                    <div class="modal-footer">
                        <button type="button"
                            class="btn btn-info waves-effect waves-light submitEditForm">{{ __('Submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- end modal for add section -->


    <!-- end cab booking template -->



@endsection

@section('script')
    <script src="{{ asset('assets/js/jscolor.js') }}"></script>
    <script src="{{ asset('assets/ck_editor/ckeditor.js') }}"></script>
    <script src="{{ asset('assets/ck_editor/samples/js/sample.js') }}"></script>
    <!-- allow drop html -->
    <script>
        function allowDrop(ev) {
            console.log('allowDrop');
            ev.preventDefault();
        }

        function drag(ev) {
            console.log('drag');
            var attod = $(ev.target).attr('data-row-id');
            ev.dataTransfer.setData("row_id", attod);
        }

        function drop(ev) {
            console.log('drop');
            ev.preventDefault();
            var row_id = ev.dataTransfer.getData("row_id");

            submitDataWithNewSection(row_id);
            console.log(row_id);
            //ev.target.appendChild(document.getElementById(row-id));
        }

        function submitDataWithNewSection(row_id) {
            console.log('ajax');
            var data_uri = "{{ route('pickup.append.section') }}";
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('input[name="_token"]').val()
                }
            });
            $.ajax({
                type: "post",
                headers: {
                    Accept: "application/json"
                },
                url: data_uri,
                data: {
                    row_id: row_id
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status == 'success') {
                        $.NotificationApp.send("Success", response.message, "top-right", "#5ba035", "success");
                        var r = document.querySelector(':root');
                        r.style.setProperty('--theme-deafult', 'lightblue');
                        location.reload();
                    }
                }
            });
        }
    </script>
    <!-- end allow html -->
    <script>
        $(document).on('click', '.edit_dynamic_page', function() {
            event.preventDefault();
            var id = $(this).data('row-id');
            $.get('/client/web-styling/get-html-data-in-modal?id=' + id, function(markup) {
                $('#edit_dynamic_html').modal('show');
                $('#edit_dynamic_html_desc').html(markup);
                $('#layout_id').val(id);
            });

        });


        $(document).on('click', '.deletePickupSection', function() {
            var did = $(this).attr('dataid');
            if (confirm("Are you sure? You want to delete this section.")) {
                $('#pickupDeleteForm' + did).submit();
            }
            return false;
        });
    </script>
    <script type="text/javascript">
        var options = {
            zIndex: 9999
        }
        $(document).ready(function() {
            var color1 = new jscolor('#primary_color_option', options);
        });

        $(document).ready(function() {
            var color1 = new jscolor('#site_top_header_color', options);
        });

        $("#primary_color_option, #site_top_header_color").change(function() {
            submitData();
        });

        $("#show_contact_us").change(function() {
            submitData();
        });
        $("#show_icons").change(function() {
            submitData();
        });
        $("#show_wishlist").change(function() {
            submitData();
        });
        $("#show_payment_icons").change(function() {
            submitData();
        });
        $("#hide_nav_bar").change(function() {
            submitData();
        });
        $("#save_home_page").click(function(event) {
            event.preventDefault();
            submitData();
        });
        $("#save_home_page_pickup").click(function(event) {
            event.preventDefault();
            submitDataNewPickup();
        });
        $("#cart_enable").change(function() {
            submitData();
        });
        $("#rating_enable").change(function() {
            submitData();
        });
        $("#age_restriction").change(function() {
            submitData();
        });
        $("#age_restriction_title").keyup(function() {
            submitData();
        });
        $("#image").change(function() {
            submitData();
        });


        function submitDataNewPickup() {
            var form = document.getElementById('favicon-form-pickup');
            for (instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
            }
            var formData = new FormData(form);
            var data_uri = "{{ route('styling.updateWebStylesNew') }}";
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('input[name="_token"]').val()
                }
            });


            $.ajax({
                type: "post",
                url: data_uri,
                data: formData,
                contentType: false,
                processData: false,
                headers: {
                    Accept: "application/json"
                },
                success: function(response) {
                    if (response.status == 'success') {
                        console.log(response.message);
                        $.NotificationApp.send("Success", response.message, "top-right", "#5ba035", "success");
                        var r = document.querySelector(':root');
                        r.style.setProperty('--theme-deafult', 'lightblue');
                    }
                }
            });
        }
        // $('#submit_new_pickup_section').on('click',function(e){
        //     $(this).closest("form").submit();
        // });
        function submitDarkMmode(id) {
            var data_uri = "{{ route('styling.updateDarkMode') }}";
            console.log(id);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('input[name="_token"]').val()
                }
            });
            $.ajax({
                type: "post",
                headers: {
                    Accept: "application/json"
                },
                url: data_uri,
                data: {
                    show_dark_mode: id
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status == 'success') {
                        console.log(response.message);
                        $.NotificationApp.send("Success", response.message, "top-right", "#5ba035", "success");
                    }
                }
            });
        }

        function submitData() {
            var form = document.getElementById('favicon-form');
            for (instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
            }
            var formData = new FormData(form);
            var data_uri = "{{ route('styling.updateWebStyles') }}";
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('input[name="_token"]').val()
                }
            });


            $.ajax({
                type: "post",
                url: data_uri,
                data: formData,
                contentType: false,
                processData: false,
                headers: {
                    Accept: "application/json"
                },
                success: function(response) {
                    if (response.status == 'success') {
                        console.log(response.message);
                        $.NotificationApp.send("Success", response.message, "top-right", "#5ba035", "success");
                        var r = document.querySelector(':root');
                        r.style.setProperty('--theme-deafult', 'lightblue');
                    }
                }
            });
        }
        $("#homepage_datatable ol").sortable({
            placeholder: "ui-state-highlight",
            update: function(event, ui) {
                var post_order_ids = new Array();
                $('#homepage_ol li').each(function() {
                    post_order_ids.push($(this).data("row-id"));
                });
                console.log(post_order_ids);
                saveOrder(post_order_ids);
            }
        });

        $("#pickup_datatable ol").sortable({
            placeholder: "ui-state-highlight",
            update: function(event, ui) {
                var post_order_ids = new Array();
                $('#pickup_ol li').each(function() {
                    post_order_ids.push($(this).data("row-id"));
                });
                saveOrderPickup(post_order_ids);

            }
        });

        function saveOrder(orderVal) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('input[name="_token"]').val()
                }
            });
            $.ajax({
                type: "post",
                dataType: "json",
                url: "{{ url('client/homepagelabel/saveOrder') }}",
                data: {
                    order: orderVal
                },
                success: function(response) {
                    if (response.status == 'success') {
                        $.NotificationApp.send("Success", response.message, "top-right", "#5ba035", "success");
                    }
                },
            });
        }

        function saveOrderPickup(orderVal) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('input[name="_token"]').val()
                }
            });
            $.ajax({
                type: "post",
                dataType: "json",
                url: "{{ url('client/pickuplabel/saveOrder') }}",
                data: {
                    order: orderVal
                },
                success: function(response) {
                    if (response.status == 'success') {
                        $.NotificationApp.send("Success", response.message, "top-right", "#5ba035", "success");
                    }
                },
            });
        }

        function submitHomePageForm(id) {
            var data_uri = "{{ route('web.styling.updateHomePageStyle') }}";
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('input[name="_token"]').val()
                }
            });
            let home_styles = id;
            $.ajax({
                type: "post",
                headers: {
                    Accept: "application/json"
                },
                url: data_uri,
                data: {
                    home_styles: home_styles
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status == 'success') {
                        console.log(response.message);
                        $.NotificationApp.send("Success", response.message, "top-right", "#5ba035", "success");
                    }
                }
            });
        }




        /////////// ************* edit banner image ***************************
        $(".openBannerModal").click(function(e) {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                }
            });
            e.preventDefault();

            var uri = "{{ route('get-image-data-in-modal') }}";

            var uid = $(this).attr('userId');


            $.ajax({
                type: "get",
                url: uri,
                data: {
                    id: uid
                },
                dataType: 'json',
                beforeSend: function() {
                    $(".loader_box").show();
                },
                success: function(data) {
                    if (uid > 0) {
                        $('#edit-form #editCardBox').html(data.html);
                        $('#edit-form').modal({
                            backdrop: 'static',
                            keyboard: false
                        });

                    }
                    // var now = new Date();
                    // runPicker();
                    $('.dropify').dropify();
                },
                error: function(data) {
                    console.log('data2');
                },
                complete: function() {
                    $('.loader_box').hide();
                }
            });
        });



        $(document).on('click', '.submitEditForm', function(e) {
            e.preventDefault();
            var form = document.getElementById('save_edit_banner_form');
            var formData = new FormData(form);
            var url = "{{ route('update-image-data-in-modal') }}";
            saveData(formData, 'edit', url);

        });

        function saveData(formData, type, banner_uri) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "post",
                headers: {
                    Accept: "application/json"
                },
                url: banner_uri,
                data: formData,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $(".loader_box").show();
                },
                success: function(response) {
                    console.log("----", response);
                    if (response.status == 'success') {
                        $(".modal .close").click();
                        location.reload();
                    } else {
                        $(".show_all_error.invalid-feedback").show();
                        $(".show_all_error.invalid-feedback").text(response.message);
                    }
                    return response;
                },
                error: function(response) {
                    console.log("====", response)
                    if (response.status === 422) {
                        let errors = response.responseJSON.errors;
                        Object.keys(errors).forEach(function(key) {
                            $("#" + key + "Input input").addClass("is-invalid");
                            $("#" + key + "Input span.invalid-feedback").children("strong").text(errors[
                                key][0]);
                            $("#" + key + "Input span.invalid-feedback").show();
                        });
                    } else {
                        $(".show_all_error.invalid-feedback").show();
                        $(".show_all_error.invalid-feedback").text('Something went wrong, Please try Again.');
                    }
                    return response;
                },
                complete: function() {
                    $('.loader_box').hide();
                }
            });
        }
    </script>

@endsection
