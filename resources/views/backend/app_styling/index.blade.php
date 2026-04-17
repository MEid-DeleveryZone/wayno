@extends('layouts.vertical', ['demo' => 'creative', 'title' => 'Styling - App Styling'])

@section('css')
<link href="{{asset('assets/libs/dropzone/dropzone.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/dropify/dropify.min.css')}}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/spectrum/1.8.1/spectrum.min.css">

@endsection

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">{{ __("App Styling") }}</h4>
        </div>
    </div>
</div>
<!-- end page title -->
<div class="style-cols">
    <div class="row">
        <div class="col-lg-12 col-xl-6">
            <div class="row">
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">{{ __("Font Styles") }}</h4>
                            <div class="mb-3">
                                <label class="form-label">{{ __("Selecting regular font") }}</label>
                                <select class="form-control" name="fonts" onchange="submitRegularFontForm()" id="save_regular_fonts">
                                    @foreach($regular_font_options as $regular_font)
                                    <option value="{{$regular_font->id}}" {{$regular_font->is_selected == 1 ? 'selected' : ''}}>{{$regular_font->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">{{ __("Selecting medium font") }}</label>
                                <select class="form-control" name="fonts" onchange="submitMediumFontForm()" id="save_medium_fonts">
                                    @foreach($medium_font_options as $medium_font)
                                    <option value="{{$medium_font->id}}" {{$medium_font->is_selected == 1 ? 'selected' : ''}}>{{$medium_font->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-0">
                                <label class="form-label">{{ __("Selecting bold font") }}</label>
                                <select class="form-control" name="fonts" onchange="submitBoldFontForm()" id="save_bold_fonts">
                                    @foreach($bold_font_options as $bold_font)
                                    <option value="{{$bold_font->id}}" {{$bold_font->is_selected == 1 ? 'selected' : ''}}>{{$bold_font->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">{{ __("Color Picker") }}</h4>
                            <div class="form-group mb-3">
                                <label for="primary_color">{{ __("Primary Color") }}</label>
                                <input type="text" id="primary_color_option" onchange="submitPrimaryColorForm()" name="primary_color" class="form-control" value="{{ old('primary_color', $primary_color_options->name ?? 'cccccc')}}">
                            </div>
                            <div class="form-group mb-3">
                                <label for="secondary_color">{{ __("Secondary Color") }}</label>
                                <input type="text" id="secondary_color_option" onchange="submitSecondaryColorForm()" name="secondary_color" class="form-control" value="{{ old('secondary_color', $secondary_color_options->name ?? 'cccccc')}}">
                            </div>
                            <div class="form-group mb-0">
                                <label for="tertiary_color">{{ __("Tertiary Color") }}</label>
                                <input type="text" id="tertiary_color_option" onchange="submitTertiaryColorForm()" name="tertiary_color" class="form-control" value="{{ old('tertiary_color', $tertiary_color_options->name ?? 'cccccc')}}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title mb-2">{{ __("Tab Bar Style") }}</h4>
                            <div class="row">
                                @foreach($tab_style_options as $tab_style)
                                <div class="col-12">
                                    <div class="card mb-0">
                                        <div class="card-body px-2 pt-0">
                                            <div class="row">
                                                <div class="col-sm-12 custom-control custom-radio radio_new p-0">
                                                    <input type="radio" {{$tab_style->is_selected == 1 ? 'checked' : ''}} onchange="submitTabBarForm(this.id)" value="{{$tab_style->id}}" id="{{$tab_style->id}}" name="tab_bars" class="custom-control-input tab_bar_options" }}>
                                                    <label class="custom-control-label w-100" for="{{$tab_style->id}}">
                                                        <img class="card-img-top img-fluid" src="{{url('images/'.$tab_style->image)}}" alt="Card image cap">
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
                <div class="col-lg-4">
                    <div class="card card-box">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h4 class="header-title mb-0">{{ __("Signup Tagline") }}</h4>
                        </div>
                        <input type="text" class="form-control" data-id="{{ $signup_tag_line_text->id??'' }}" id="signup_tagline" name="signup_tagline" value="{{ $signup_tag_line_text->name??'' }}">
                    </div>
                </div>
                <div class="col-lg-3">
                    <form action="{{ route('styling.addTutorials') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="card card-box">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h4 class="header-title mb-0">{{ __('Upload Tutorials') }}</h4>
                            </div>
                            <input type="file" accept="image/*" data-plugins="dropify" name="file_name" class="dropify" data-default-file="" />
                            {{-- <label class="logo-size text-right w-100">{{ __("Logo Size") }} 170x96</label> --}}
                            <button type="submit" class="btn btn-info waves-effect waves-light mt-1">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if(!empty($dynamicTutorials) && count($dynamicTutorials)>0)
    <div class="row">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-body" id="homepage_tutorial_dropzone">
                    <h4 class="header-title">{{ __("Tutorial images") }}</h4>
                    <div class="row tutorial_main_div">
                        @foreach($dynamicTutorials as $dynamicTutorial)
                            <div class="col-sm-6 col-md-4 col-lg-3 tutorial_inner_div" data-id="{{$dynamicTutorial->id}}" data-sort="{{$dynamicTutorial->sort}}">
                                <div class="card mb-0">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-12 custom-control custom-radio radio_new p-0">
                                                <label class="custom-control-label" for="">
                                                    <img class="card-img-top img-fluid" src="{{$dynamicTutorial->file_name['proxy_url'] . '215/400' . $dynamicTutorial->file_name['image_path']}}" alt="Image">
                                                </label>
                                                <form action="{{ route('styling.deleteTutorials',$dynamicTutorial->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger waves-effect waves-light mt-1" onclick="return confirm('Are you sure? You want to delete this tutorial.')" >Delete</button>
                                                </form>
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
    </div>
    @endif

    <div class="row">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">{{ __("Home Page Style") }}</h4>
                    <div class="row">
                        @foreach($homepage_style_options as $homepage_style)
                                <div class="col-sm-6 col-md-4 col-lg-3">
                                    <div class="card mb-0">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-12 custom-control custom-radio radio_new p-0">
                                                    <input type="radio" {{$homepage_style->is_selected == 1 ? 'checked' : ''}} value="{{$homepage_style->id}}" onchange="submitHomePageForm(this.id)" id="{{$homepage_style->id}}" name="home_styles" class="custom-control-input " }}>
                                                    <label class="custom-control-label" for="{{$homepage_style->id}}">
                                                        <img class="card-img-top img-fluid" src="{{url('images/'.$homepage_style->image)}}" alt="Card image cap">
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
    </div>
    
</div>

@endsection

@section('script')
<script src="{{asset('assets/js/jscolor.js')}}"></script>
<script type="text/javascript">
    var options = {
        zIndex: 9999
    }
    $(document).ready(function() {
        var color1 = new jscolor('#primary_color_option', options);
        var color3 = new jscolor('#tertiary_color_option', options);
        var color2 = new jscolor('#secondary_color_option', options);
    });

    function submitHomePageForm(id) {
        var data_uri = "{{route('styling.updateHomePage')}}";
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

    function submitTabBarForm(id) {
        var data_uri = "{{route('styling.updateTabBar')}}";
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            }
        });
        let tab_bars = id;
        $.ajax({
            type: "post",
            headers: {
                Accept: "application/json"
            },
            url: data_uri,
            data: {
                tab_bars: tab_bars
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

    function submitRegularFontForm() {
        var data_uri = "{{route('styling.updateFont')}}";
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            }
        });
        let fonts = $('#save_regular_fonts').val();
        $.ajax({
            type: "post",
            headers: {
                Accept: "application/json"
            },
            url: data_uri,
            data: {
                fonts: fonts
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

    function submitMediumFontForm() {
        var data_uri = "{{route('styling.updateFont')}}";
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            }
        });
        let fonts = $('#save_medium_fonts').val();
        $.ajax({
            type: "post",
            headers: {
                Accept: "application/json"
            },
            url: data_uri,
            data: {
                fonts: fonts
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

    function submitBoldFontForm() {
        var data_uri = "{{route('styling.updateFont')}}";
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            }
        });
        let fonts = $('#save_bold_fonts').val();
        $.ajax({
            type: "post",
            headers: {
                Accept: "application/json"
            },
            url: data_uri,
            data: {
                fonts: fonts
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

    function submitPrimaryColorForm() {
        var data_uri = "{{route('styling.updateColor')}}";
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            }
        });
        let primary_color = $('#primary_color_option').val();
        $.ajax({
            type: "post",
            headers: {
                Accept: "application/json"
            },
            url: data_uri,
            data: {
                primary_color: primary_color,
                color_type: 'Primary'
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

    function submitSecondaryColorForm() {
        var data_uri = "{{route('styling.updateColor')}}";
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            }
        });
        let secondary_color = $('#secondary_color_option').val();
        $.ajax({
            type: "post",
            headers: {
                Accept: "application/json"
            },
            url: data_uri,
            data: {
                secondary_color: secondary_color,
                color_type: 'Secondary'
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

    function submitTertiaryColorForm() {
        var data_uri = "{{route('styling.updateColor')}}";
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            }
        });
        let tertiary_color = $('#tertiary_color_option').val();
        $.ajax({
            type: "post",
            headers: {
                Accept: "application/json"
            },
            url: data_uri,
            data: {
                tertiary_color: tertiary_color,
                color_type: 'Tertiary'
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

    $("#signup_tagline").on('blur',function() {
        var updated_text = $(this).val();
        var id = $(this).data('id');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            }
        });
        $.ajax({
            type: "post",
            url: "{{route('styling.updateSignupTagLine')}}",
            data: { updated_text : updated_text, id : id },
            dataType: 'json',
            headers: {
                Accept: "application/json"
            },
            success: function(response) {
                if (response.status == 'success') {
                    console.log(response.message);
                    $.NotificationApp.send("Success", response.message, "top-right", "#5ba035", "success");
                }
            }
        });
    });

    $("#homepage_tutorial_dropzone .tutorial_main_div").sortable({
        axis: 'x',
        placeholder: "ui-state-highlight",
        update: function(event, ui) {
            var post_order_ids = new Array();
            $('#homepage_tutorial_dropzone .tutorial_inner_div').each(function() {
                post_order_ids.push({"row_id" : $(this).data("id"), "sort" : $(this).data("sort")});
            });
            saveTutorialOrder(post_order_ids);
        }
    });

    function saveTutorialOrder(orderVal) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            }
        });
        $.ajax({
            type: "post",
            dataType: "json",
            url: "{{ url('client/app_styling/saveOrderTutorials') }}",
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
</script>

@endsection