@extends('layouts.vertical', ['demo' => 'creative', 'title' => 'Customize'])
@section('css')
<link href="https://itsjavi.com/fontawesome-iconpicker/dist/css/fontawesome-iconpicker.min.css" rel="stylesheet" type="text/css" />
@endsection
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">{{ __("Customize") }}</h4>
            </div>
        </div>
    </div>
    <div class="row mb-2">
        <div class="col-sm-12">
            <div class="text-sm-left">
                @if (\Session::has('success'))
                <div class="alert alert-success">
                    <span>{!! \Session::get('success') !!}</span>
                </div>
                @endif
            </div>
        </div>
    </div>
    <div class="row col-spacing">
        <div class="col-lg-3 col-xl-3 mb-3">
            <form method="POST" class="h-100" action="{{route('configure.update', Auth::user()->code)}}">
                @csrf
                <div class="card-box mb-0 h-100 pb-0">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h4 class="header-title mb-0">{{ __("Admin") }}</h4>
                        <input type="hidden" name="send_to" id="send_to" value="customize">
                        <button class="btn btn-info d-block" type="submit"> {{ __("Save") }} </button>
                    </div>
                    <p class="sub-header">
                        {{ __("Choose between light and dark theme, for the platform.") }}
                    </p>
                    <div class="row">
                        <div class="col-sm-12 mb-2">
                            <label for="primary_color">{{ __("Admin Panel Theme") }}</label> <br />
                            <div class="radio radio-blue form-check-inline">
                                <input type="radio" id="light_theme" value="light" name="theme_admin" {{ (isset($preference) && $preference->theme_admin =="light")? "checked" : "" }}>
                                <label for="light_theme"> {{ __("Light theme") }} </label>
                            </div>
                            <div class="radio form-check-inline">
                                <input type="radio" id="dark_theme" value="dark" name="theme_admin" {{ (isset($preference) &&  $preference->theme_admin =="dark")? "checked" : "" }}>
                                <label for="dark_theme"> {{ __("Dark theme") }} </label>
                            </div>
                            @if($errors->has('theme'))
                            <span class="text-danger" role="alert">
                                <strong>{{ $errors->first('theme') }}</strong>
                            </span>
                            @endif
                        </div>
                        <!-- <div class="col-12 d-flex align-items-center justify-content-between mt-2">
                            <label class="cursor" for="ios_app">iOS App Link</label>
                            <input type="checkbox" id="ios_app" data-plugin="switchery" name="show_contact_us" class="chk_box2" data-color="#43bee1">
                        </div>
                        <div class="col-12 d-flex align-items-center justify-content-between mt-2">
                            <label class="cursor" for="android_app">android App Link</label>
                            <input type="checkbox" id="android_app" data-plugin="switchery" name="show_contact_us" class="chk_box2" data-color="#43bee1">
                        </div> -->
                    </div>
                </div>
            </form>
        </div>
        <div class="col-lg-4 col-xl-3 mb-3">
            <form method="POST" class="h-100" action="{{route('configure.update', Auth::user()->code)}}">
                @csrf
                <div class="card-box mb-0 h-100 pb-0">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h4 class="header-title mb-0">{{ __("Date & Time") }}</h4>
                        <input type="hidden" name="send_to" id="send_to" value="customize">
                        <button class="btn btn-info d-block" type="submit"> {{ __("Save") }} </button>
                    </div>
                    <p class="sub-header">
                        {{ __("View and update the date & time format.") }}
                    </p>
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <div class="form-group mb-2">
                                <label for="date_format">{{ __("Date Format") }}</label>
                                <select class="form-control" id="date_format" name="date_format">
                                    <option value="DD-MM-YYYY" {{ ($preference && $preference->date_format =="DD-MM-YYYY")? "selected" : "" }}>
                                        DD-MM-YYYY</option>
                                    <option value="DD/MM/YYYY" {{ ($preference && $preference->date_format =="DD/MM/YYYY")? "selected" : "" }}>
                                        DD/MM/YYYY</option>
                                    <option value="YYYY-MM-DD" {{ ($preference && $preference->date_format =="YYYY-MM-DD")? "selected" : "" }}>
                                        YYYY-MM-DD</option>
                                </select>
                                @if($errors->has('date_format'))
                                <span class="text-danger" role="alert">
                                    <strong>{{ $errors->first('date_format') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group mb-2">
                                <label for="time_format">{{ __("Time Format") }}</label>
                                <select class="form-control" id="time_format" name="time_format">
                                    <option value="12" {{ ($preference && $preference->time_format =="12")? "selected" : "" }}>12 {{ __("hours") }}
                                    </option>
                                    <option value="24" {{ ($preference && $preference->time_format =="24")? "selected" : "" }}>24 {{ __("hours") }}
                                    </option>
                                </select>
                                @if($errors->has('time_format'))
                                <span class="text-danger" role="alert">
                                    <strong>{{ $errors->first('time_format') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-lg-5 col-xl-6 mb-3">
            <form method="POST" class="h-100" action="{{route('configure.update', Auth::user()->code)}}">
                @csrf
                <div class="card-box mb-0 h-100 pb-0">
                    <div class="d-flex align-items-center justify-content-between">
                        <h4 class="header-title mb-0">{{ __("Localization") }}</h4>
                        <input type="hidden" name="send_to" id="send_to" value="customize">
                        <button class="btn btn-info d-block" type="submit"> {{ __("Save") }} </button>
                    </div>
                    <p class="sub-header">
                        {{ __("Define and update the localization") }}
                    </p>
                    <div class="row col-spacing">
                        <div class="col-sm-4 mb-2">
                            <label for="languages">{{ __("Primary Language") }}</label>
                            <select class="form-control" id="primary_language" name="primary_language">
                                @php
                                   $primary_language_id =  $preference->primarylang ? $preference->primarylang->language_id : '';
                                @endphp
                                @foreach($languages as $lang)
                                    <option {{(isset($preference) && ($lang->id == $primary_language_id))? "selected" : "" }} value="{{$lang->id}}"> {{$lang->name}} </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-8 mb-2">
                            <label for="languages">{{ __("Additional Languages") }}</label>
                            <select class="form-control select2-multiple" id="languages" name="languages[]" data-toggle="select2" multiple="multiple" data-placeholder="Choose ...">
                                @foreach($languages as $lang)
                                @if($lang->id != $primary_language_id)
                                    <option value="{{$lang->id}}" {{ (isset($preference) && in_array($lang->id, $cli_langs))? "selected" : "" }}>{{$lang->nativeName??''}}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-4 mb-2">
                            <label for="primary_currency">{{ __("Primary Currency") }}</label>
                            <select class="form-control" id="primary_currency" name="primary_currency">
                                @foreach($currencies as $currency)
                                <option iso="{{$currency->iso_code.' '.$currency->symbol}}" {{ (isset($preference) && $preference->primary->currency->id == $currency->id) ? "selected" : ""}} value="{{$currency->id}}"> {{$currency->iso_code.' '.$currency->symbol}} </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-8">
                            <label for="currency">{{ __("Additional Currency") }}</label>
                            <select class="form-control select2-multiple" id="currency" name="currency_data[]" data-toggle="select2" multiple="multiple" data-placeholder="Choose ...">
                                @foreach($currencies as $currency)
                                @if($preference->primary->currency->id != $currency->id)
                                <option value="{{$currency->id}}" iso="{{$currency->iso_code}}" {{ (isset($preference) && in_array($currency->id, $cli_currs))? "selected" : "" }}> {{$currency->iso_code}} {{!empty($currency->symbol) ? $currency->symbol : ''}} </option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 mt-2">
                            <div class="row multiplierData">
                                @if($preference->currency)
                                @foreach($preference->currency as $ac)
                                <div class="col-sm-10 offset-sm-4 col-lg-12 offset-lg-0 col-xl-8 offset-xl-4 mb-2" id="addCur-{{$ac->currency->id}}">
                                    <label class="primaryCurText">1 {{$preference->primary->currency->iso_code}} {{!empty($preference->primary->currency->symbol) ? $preference->primary->currency->symbol : ''}} = </label>
                                    <input class="form-control w-50 d-inline-block" type="number" value="{{$ac->doller_compare}}" step=".01" name="multiply_by[]" min="0.01"> {{$ac->currency->iso_code}} {{!empty($ac->currency->symbol) ? $ac->currency->symbol : ''}}
                                    <input type="hidden" name="cuid[]" class="curr_id" value="{{ $ac->currency->id }}">
                                </div>
                                @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-lg-3 col-lg-3 mb-3">
            <form method="POST" class="h-100" action="{{route('client.updateDomain', Auth::user()->code)}}">
                @csrf
                <div class="card-box mb-0 h-100">
                    <div class="d-flex align-items-center justify-content-between">
                        <h4 class="header-title mb-0">{{ __("Custom Domain") }}</h4>
                        <input type="hidden" name="send_to" id="send_to" value="customize">
                        <button class="btn btn-info d-block" type="submit"> {{ __("Save") }} </button>
                    </div>
                    <p class="sub-header">
                        {{ __("Update custom domain here.") }}
                    </p>
                    <label for="custom_domain">*{{__("Make sure you already pointed to IP")}} ({{\env('IP')}}) {{__("from your domain.")}}</label> 
                      
                    <div class="row mb-2">
                        <div class="col-sm-12">
                            <div class="form-group mb-3">
                                <label for="custom_domain">{{ __("Custom Domain") }}</label>

                                <div class="domain-outer d-flex align-items-center">
                                    <div class="domain_name">https://</div>
                                    <input type="text" name="custom_domain" id="custom_domain" placeholder="" class="form-control" value="{{ old('custom_domain', $preference->domain->custom_domain ?? '')}}">
                                </div>

                               
                                @if($errors->has('custom_domain'))
                                <span class="text-danger" role="alert">
                                    <strong>{{ $errors->first('custom_domain') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-lg-3 mb-3">
            <div class="card-box mb-0 h-100 pb-1">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <h4 class="header-title mb-0">{{ __("Social Media") }}</h4>
                    <button class="btn btn-info d-block" id="add_social_media_modal_btn">
                        <i class="mdi mdi-plus-circle mr-1"></i>{{ __("Add") }}
                    </button>
                </div>
                <div class="table-responsive mt-3">
                    <table class="table table-centered table-nowrap table-striped" id="promo-datatable">
                        <thead>
                            <tr>
                                <th>{{ __("Icon") }}</th>
                                <th>{{ __("URL") }}</th>
                                <th>{{ __("Action") }}</th>
                            </tr>
                        </thead>
                        <tbody id="post_list">
                            @forelse($social_media_details as $social_media_detail)
                            <tr>
                                <td>
                                    <i class="fab fa-{{$social_media_detail->icon}}" aria-hidden="true"></i>
                                </td>
                                <td>
                                    <a href="{{$social_media_detail->url}}" target="_blank">{{$social_media_detail->url}}</a>
                                </td>
                                <td>
                                    <div>
                                        <div class="inner-div" style="float: left;">
                                            <a class="action-icon edit_social_media_option_btn" data-social_media_detail_id="{{$social_media_detail->id}}">
                                                <i class="mdi mdi-square-edit-outline"></i>
                                            </a>
                                        </div>
                                        <div class="inner-div">
                                            <button type="button" class="btn btn-primary-outline action-icon delete_social_media_option_btn" data-social_media_detail_id="{{$social_media_detail->id}}">
                                                <i class="mdi mdi-delete"></i>
                                            </button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr align="center">
                                <td colspan="4" style="padding: 20px 0">{{ __("Result not found.") }}</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-lg-3 mb-3">
            <form method="POST" class="h-100" action="{{route('nomenclature.store', Auth::user()->code)}}">
                @csrf
                <div class="card-box mb-0 h-100">
                    <div class="d-flex align-items-center justify-content-between">
                        <h4 class="header-title mb-0">{{ __("Nomenclature") }}</h4>
                        <button class="btn btn-info d-block" type="submit"> {{ __("Save") }} </button>
                    </div>
                    <p class="sub-header">{{ __("View and update the naming") }}</p>
                    <div class="table-responsive">
                        <div class="row mb-2 flex-nowrap">
                            @foreach($client_languages as $k => $client_language)
                            <div class="col-sm-3">
                                <div class="form-group mb-3">
                                    <label for="custom_domain">{{ __("Vendors") }}({{$client_language->langName}})</label>
                                    <input type="hidden" name="language_ids[]" value="{{$client_language->langId}}">
                                    <input type="text" name="names[]" class="form-control" value="{{ App\Models\NomenclatureTranslation::getNameBylanguageId($client_language->langId, 1)}}">
                                    @if($k == 0)
                                        @if($errors->has('names.0'))
                                            <span class="text-danger" role="alert">
                                                <strong>{{ __("The primary language name field is required.") }}</strong>
                                            </span>
                                        @endif
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="row mb-2 flex-nowrap">
                            @foreach($client_languages as $k => $client_language)
                            <div class="col-sm-3">
                                <div class="form-group mb-3">
                                    <label for="custom_domain">{{ __("Loyalty Cards") }}({{$client_language->langName}})</label>
                                    <input type="hidden" name="loyalty_cards_language_ids[]" value="{{$client_language->langId}}">
                                    <input type="text" name="loyalty_cards_names[]" class="form-control" value="{{ App\Models\NomenclatureTranslation::getNameBylanguageId($client_language->langId, 2)}}">
                                    @if($k == 0)
                                        @if($errors->has('loyalty_cards_names.0'))
                                            <span class="text-danger" role="alert">
                                                <strong>{{ __("The primary language name field is required.") }}</strong>
                                            </span>
                                        @endif
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="row mb-2 flex-nowrap">
                            @foreach($client_languages as $k => $client_language)
                            <div class="col-sm-3">
                                <div class="form-group mb-3">
                                    <label for="custom_domain">{{ __("Takeaway") }}({{$client_language->langName}})</label>
                                    <input type="hidden" name="takeaway_language_ids[]" value="{{$client_language->langId}}">
                                    <input type="text" name="takeaway_names[]" class="form-control" value="{{ App\Models\NomenclatureTranslation::getNameBylanguageId($client_language->langId, 3)}}">
                                    @if($k == 0)
                                        @if($errors->has('takeaway_names.0'))
                                            <span class="text-danger" role="alert">
                                                <strong>{{ __("The primary language name field is required.") }}</strong>
                                            </span>
                                        @endif
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="row mb-2 flex-nowrap">
                            @foreach($client_languages as $k => $client_language)
                            <div class="col-sm-3">
                                <div class="form-group mb-3">
                                    <label for="custom_domain">{{ __("Search") }}({{$client_language->langName}})</label>
                                    <input type="hidden" name="search_language_ids[]" value="{{$client_language->langId}}">
                                    <input type="text" name="search_names[]" class="form-control" value="{{ App\Models\NomenclatureTranslation::getNameBylanguageId($client_language->langId, 4)}}">
                                    @if($k == 0)
                                        @if($errors->has('search_names.0'))
                                            <span class="text-danger" role="alert">
                                                <strong>{{ __("The primary language name field is required.") }}</strong>
                                            </span>
                                        @endif
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div id="add_or_edit_social_media_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h4 class="modal-title" id="standard-modalLabel">{{ __("Add Social Media") }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <div id="save_social_media">
                    <input type="hidden" name="social_media_id" value="">
                    <div class="form-group position-relative">
                        <label for="">{{ __("Icon") }}</label>
                        <div class="input-group mb-2">
                            <div class="input-group-prepend">
                                <div class="input-group-text"><i class="fab fa-facebook"></i></div>
                            </div>
                            <select class="form-control" id="social_icons" name="icon">
                                <option value="facebook"> Facebook </option>
                                <option value="github"> Github </option>
                                <option value="reddit"> Reddit </option>
                                <option value="whatsapp"> Whatsapp </option>
                                <option value="instagram"> Instagram </option>
                                <option value="tumblr"> Tumblr </option>
                                <option value="twitch"> Twitch </option>
                                <option value="twitter"> Twitter </option>
                                <option value="pinterest"> Pinterest </option>
                                <option value="youtube"> Youtube </option>
                                <option value="snapchat"> Snapchat </option>
                                <option value="linkedin"> Linkedin-in </option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group position-relative">
                        <label for="">{{ __("URL") }}</label>
                        <input class="form-control" name="url" type="text" placeholder="http://www.google.com">
                        <span class="text-danger error-text social_media_url_err"></span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary submitSaveSocialForm">{{ __("Save") }}</button>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script src="{{asset('assets/js/jscolor.js')}}"></script>
<script src="https://itsjavi.com/fontawesome-iconpicker/dist/js/fontawesome-iconpicker.js"></script>
<script type="text/javascript">
    $('#social_icons').on('change', function() {
        $(".input-group-text").html('<i class="fab fa-'+this.value+'"></i>');
    });

    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            }
        });
        $(document).on("click", ".delete_social_media_option_btn", function() {
            var social_media_detail_id = $(this).data('social_media_detail_id');
            if (confirm('Are you sure?')) {
                $.ajax({
                    type: "POST",
                    dataType: 'json',
                    url: "{{ route('social.media.delete') }}",
                    data: {
                        social_media_detail_id: social_media_detail_id
                    },
                    success: function(response) {
                        if (response.status == "Success") {
                            $.NotificationApp.send("Success", response.message, "top-right", "#5ba035", "success");
                            setTimeout(function() {
                                location.reload()
                            }, 2000);
                        }
                    }
                });
            }
        });
        $(document).on("click", ".edit_social_media_option_btn", function() {
            let social_media_detail_id = $(this).data('social_media_detail_id');
            $('#add_or_edit_social_media_modal input[name=social_media_id]').val(social_media_detail_id);
            $.ajax({
                method: 'GET',
                data: {
                    social_media_detail_id: social_media_detail_id
                },
                url: "{{ route('social.media.edit') }}",
                success: function(response) {
                    if (response.status = 'Success') {
                        $('#add_or_edit_social_media_modal').modal('show');
                        $("#add_or_edit_social_media_modal input[name=url]").val(response.data.url);
                        $("#add_or_edit_social_media_modal .input-group-text").html('<i class="fab fa-'+response.data.icon+'"></i>');
                        $("#add_or_edit_social_media_modal #social_icons").val(response.data.icon);
                        $("#add_or_edit_social_media_modal input[name=social_media_id]").val(response.data.id);
                        $('#add_or_edit_social_media_modal #standard-modalLabel').html('Update Social Media');
                    }
                },
                error: function() {

                }
            });

        });
        $(document).on("click", "#add_social_media_modal_btn", function() {
            $('#add_or_edit_social_media_modal #standard-modalLabel').html('Add Social Media');
            $('#add_or_edit_social_media_modal').modal('show');
        });
        $(document).on('click', '.submitSaveSocialForm', function(e) {
            var social_media_url = $("#add_or_edit_social_media_modal input[name=url]").val();
            var social_media_icon = $("#add_or_edit_social_media_modal #social_icons").val();
            var social_media_id = $("#add_or_edit_social_media_modal input[name=social_media_id]").val();
            if (social_media_id) {
                var post_url = "{{ route('social.media.update') }}";
            } else {
                var post_url = "{{ route('social.media.create') }}";
            }
            $.ajax({
                url: post_url,
                method: 'POST',
                data: {
                    social_media_id: social_media_id,
                    social_media_url: social_media_url,
                    social_media_icon: social_media_icon
                },
                success: function(response) {
                    if (response.status == 'Success') {
                        $('#add_or_edit_social_media_modal').modal('hide');
                        $.NotificationApp.send("Success", response.message, "top-right", "#5ba035", "success");
                        setTimeout(function() {
                            location.reload()
                        }, 2000);
                    } else {
                        $.NotificationApp.send("Error", response.message, "top-right", "#ab0535", "error");
                    }
                },
                error: function(response) {
                    $('.social_media_url_err').html(response.responseJSON.errors.social_media_url[0]);
                }
            });
        });
    });
</script>
<script type="text/javascript">
    var options = {
        zIndex: 9999
    }
    $(document).ready(function() {
        var color1 = new jscolor('#primary_color', options);
        var color2 = new jscolor('#secondary_color', options);
    });

    function generateRandomString(length) {
        var text = "";
        var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
        for (var i = 0; i < length; i++)
            text += possible.charAt(Math.floor(Math.random() * possible.length));
        return text;
    }

    function genrateKeyAndToken() {
        var key = generateRandomString(30);
        var token = generateRandomString(60);
        $('#personal_access_token_v1').val(key);
        $('#personal_access_token_v2').val(token);
    }
    var existCid = [];
    $('#primary_currency').change(function() {
        var pri_curr = $('#primary_currency option:selected').text();
        console.log(pri_curr);
        $(document).find('.primaryCurText').html('1 ' + pri_curr + '  = ');
    });
    $('#currency').change(function() {
        var activeCur = [];
        var pri_curr = $('#primary_currency option:selected').text();
        var cidText = $('#currency').select2('data');
        for (i = 0; i < cidText.length; i++) {
            activeCur.push(cidText[i].id);
        }
        $(".curr_id").each(function() {
            var cv = $(this).val();
            if (existCid.indexOf(cv) === -1) {
                existCid.push(cv);
            }
        });
        for (i = 0; i < existCid.length; i++) {
            if (activeCur.indexOf(existCid[i]) === -1) {
                $('#addCur-' + existCid[i]).remove();
            }
        }
        for (i = 0; i < cidText.length; i++) {
            if (existCid.indexOf(cidText[i].id) === -1) {
                var text = '<div class="col-sm-10 offset-sm-4 col-lg-12 offset-lg-0 col-xl-8 offset-xl-4 mb-2" id="addCur-' + cidText[i].id + '"><label class="primaryCurText">1 ' + pri_curr + '  = </label> <input type="number" name="multiply_by[]" min="0.01" value="0" step=".01">' + cidText[i].text + '<input type="hidden" name="cuid[]" class="curr_id" value="' + cidText[i].id + '"></div>';
                $('.multiplierData').append(text);
            }
        }
    });
</script>
@endsection