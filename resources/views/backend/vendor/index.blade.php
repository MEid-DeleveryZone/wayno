@extends('layouts.vertical', ['demo' => 'creative', 'title' => getNomenclatureName('vendors', true)])
@section('css')
    <link href="{{asset('assets/libs/dropzone/dropzone.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/libs/dropify/dropify.min.css')}}" rel="stylesheet" type="text/css" />
    <style type="text/css">
        .pac-container,
        .pac-container .pac-item {
            z-index: 99999 !important;
        }
    </style>
    <link rel="stylesheet" href="{{asset('assets/css/intlTelInput.css')}}">
    <style type="text/css">
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

        .mdi-icons {
            color: #43bee1;
            font-size: 26px;
            vertical-align: middle;
        }
    </style>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <div class="page-title-box">

                    @php
                        $vendors = getNomenclatureName('vendors', true);
                        $newvendors = ($vendors === "vendors") ? __('vendors') : $vendors;
                    @endphp

                    <h4 class="page-title">{{ $newvendors }}</h4>
                </div>
            </div>


            @if(isset($client_preference_detail) && $client_preference_detail->single_vendor == 1)
                @if($total_vendor_count == 0)
                    <div class="col-sm-6 text-sm-right">
                        <button class="btn btn-info waves-effect waves-light text-sm-right openAddModal" userId="0"><i
                                class="mdi mdi-plus-circle mr-1"></i> {{ __('Add') }}
                        </button>
                    </div>
                @endif
            @else
                <div class="col-sm-6 text-sm-right">
                    <button class="btn btn-info waves-effect waves-light text-sm-right openImportModal" userId="0"><i
                            class="mdi mdi-plus-circle mr-1"></i> {{ __('Import') }}
                    </button>
                    <button class="btn btn-info waves-effect waves-light text-sm-right openAddModal" userId="0"><i
                            class="mdi mdi-plus-circle mr-1"></i> {{ __('Add') }}
                    </button>
                </div>
            @endif

        </div>
        <div class="row">
            <div class="col-12">

                <div class="card widget-inline">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6 col-md-3 mb-3 mb-md-0">
                                <div class="text-center">
                                    <h3>
                                        <i class="mdi mdi-storefront text-primary mdi-24px"></i>
                                        <span data-plugin="counterup"
                                            id="total_earnings_by_vendors">{{$active_vendor_count}}</span>
                                    </h3>
                                    <p class="text-muted font-15 mb-0">{{ __('Total') }} {{ $newvendors }}</p>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-3 mb-3 mb-md-0">
                                <div class="text-center">
                                    <h3>
                                        <i class="mdi mdi-store-24-hour text-primary mdi-24px"></i>
                                        <span data-plugin="counterup"
                                            id="total_order_count">{{$available_vendors_count}}</span>
                                    </h3>
                                    <p class="text-muted font-15 mb-0"> {{ __('Open') }}
                                        {{getNomenclatureName('vendors', true)}}</p>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-3 mb-3 mb-md-0">
                                <div class="text-center">
                                    <h3>
                                        <i class="fas fa-money-check-alt text-primary"></i>
                                        <span data-plugin="counterup"
                                            id="total_cash_to_collected">{{$vendors_product_count}}</span>
                                    </h3>
                                    <p class="text-muted font-15 mb-0">{{ __('Total Products') }}</p>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-3 mb-3 mb-md-0">
                                <div class="text-center">
                                    <h3>
                                        <i class="fas fa-money-check-alt text-primary"></i>
                                        <span data-plugin="counterup"
                                            id="total_delivery_fees">{{$vendors_active_order_count}}</span>
                                    </h3>
                                    <p class="text-muted font-15 mb-0">{{ __('Total Active Orders') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 col-lg-12 tab-product pt-0">
                <ul class="nav nav-tabs nav-material" id="top-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="active-vendor" data-toggle="tab" href="#active_vendor" role="tab"
                            aria-selected="false" data-rel="vendor_active_datatable" data-status="1">
                            <i class="icofont icofont-man-in-glasses"></i>{{ __('Active') }}<sup class="total-items"
                                id="active_vendor_count">({{$active_vendor_count}})</sup>
                        </a>
                        <div class="material-border"></div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="awaiting-vendor" data-toggle="tab" href="#awaiting_vendor" role="tab"
                            aria-selected="true" data-rel="awaiting__Approval_vendor_datatble" data-status="0">
                            <i class="icofont icofont-ui-home"></i>{{ __('Awaiting Approval') }}<sup
                                class="total-items">({{$awaiting__Approval_vendor_count}})</sup>
                        </a>
                        <div class="material-border"></div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="block-vendor" data-toggle="tab" href="#block_vendor" role="tab"
                            aria-selected="false" data-rel="blocked_vendor_datatble" data-rel="blocked_vendor_datatble"
                            data-status="2">
                            <i class="icofont icofont-man-in-glasses"></i>{{ __('Blocked') }}<sup
                                class="total-items">({{$blocked_vendor_count}})</sup>
                        </a>
                        <div class="material-border"></div>
                    </li>
                </ul>
                <div class="tab-content nav-material pt-0   " id="top-tabContent">
                    <div class="tab-pane fade past-order show active" id="active_vendor" role="tabpanel"
                        aria-labelledby="active-vendor">
                        <div class="row">
                            <div class="col-12">

                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <form name="saveOrder" id="saveOrder"> @csrf</form>

                                            <table class="table table-centered table-nowrap table-striped"
                                                id="vendor_active_datatable" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th>{{ __('Icon') }}</th>
                                                        <th>{{ __('Name') }}</th>
                                                        <th>{{ __('Status') }}</th>
                                                        <th>{{ __('Address') }}</th>
                                                        <th>{{ __('Offers') }}</th>
                                                        <th class="text-center">{{ __('Can Add') }} <br>
                                                            {{ __('Category') }}</th>
                                                        <th class="text-center">{{ __('Commission') }} <br>
                                                            {{ __('Percentage') }}</th>
                                                        <th class="text-center">{{ __('Products') }}</th>
                                                        <th class="text-center">{{ __('Orders') }}</th>
                                                        <th class="text-center">{{ __('Active') }} <br> {{ __('Orders') }}
                                                        </th>
                                                        <th class="text-center">{{ __('Action') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="post_list"></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row address" id="def" style="display: none;">
                                <input type="text" id="def-address" name="test"
                                    class="autocomplete form-control def_address">
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="awaiting_vendor" role="tabpanel" aria-labelledby="awaiting-vendor">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <form name="saveOrder" id="saveOrder"> @csrf</form>
                                            <table class="table table-centered table-nowrap table-striped"
                                                id="awaiting__Approval_vendor_datatble" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th>{{ __("Icon") }}</th>
                                                        <th>{{ __('Name') }}</th>
                                                        <th>{{ __('Status') }}</th>
                                                        <th>{{ __('Address') }}</th>
                                                        <th>{{ __('Offers') }}</th>
                                                        <th class="text-center">{{ __('Can Add') }} <br>
                                                            {{ __('Category') }}</th>
                                                        <th class="text-center">{{ __('Commission') }} <br>
                                                            {{ __('Percentage') }}</th>
                                                        <th class="text-center">{{ __('Products') }}</th>
                                                        <th class="text-center">{{ __('Orders') }}</th>
                                                        <th class="text-center">{{ __('Active') }} <br> {{ __('Orders') }}
                                                        </th>
                                                        <th class="text-center">{{ __('Action') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="post_list"></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row address" id="def" style="display: none;">
                                <input type="text" id="def-address" name="test"
                                    class="autocomplete form-control def_address">
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade past-order" id="block_vendor" role="tabpanel" aria-labelledby="block-vendor">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-centered table-nowrap table-striped"
                                                id="blocked_vendor_datatble" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th>{{ __('Icon') }}</th>
                                                        <th>{{ __('Name') }}</th>
                                                        <th>{{ __('Status') }}</th>
                                                        <th>{{ __('Address') }}</th>
                                                        <th>{{ __('Offers') }}</th>
                                                        <th class="text-center">{{ __('Can Add') }} <br>
                                                            {{ __('Category') }}</th>
                                                        <th class="text-center">{{ __('Commission') }} <br>
                                                            {{ __('Percentage') }}</th>
                                                        <th class="text-center">{{ __("Products") }}</th>
                                                        <th class="text-center">{{ __('Orders') }}</th>
                                                        <th class="text-center">{{ __("Active") }} <br> {{ __("Orders") }}
                                                        </th>
                                                        <th class="text-center">{{ __('Action') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="post_list"></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row address" id="def" style="display: none;">
                                <input type="text" id="def-address" name="test"
                                    class="autocomplete form-control def_address">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="edit_vendor_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
            aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header border-bottom">
                        <h4 class="modal-title">{{ __('Edit') }} {{getNomenclatureName('vendors', false)}}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <form id="update_vendor_form" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="modal-body" id="editVendorBox">

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-info waves-effect waves-light"
                                id="update_vendor_modal">{{ __('Submit') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @include('backend.vendor.modals')
@endsection
@section('script')
    <script src="{{asset('assets/js/intlTelInput.js')}}"></script>
    <script type="text/javascript">
        var mobile_number = '';
        // $('#add-agent-modal .xyz').val(mobile_number.getSelectedCountryData().dialCode); 
        $('#add-agent-modal .xyz').change(function () {
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
        var input = document.querySelector("#phone");
        window.intlTelInput(input, {
            separateDialCode: true,
            hiddenInput: "contact",
            utilsScript: "{{asset('assets/js/utils.js')}}",
            initialCountry: "{{ Session::get('default_country_code', 'US') }}",
        });
        $(document).ready(function () {
            $("#phone").keypress(function (e) {
                if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                    return false;
                }
                return true;
            });
        });
        $('.iti__country').click(function () {
            var code = $(this).attr('data-country-code');
            $('#countryData').val(code);
            var dial_code = $(this).attr('data-dial-code');
            $('#dialCode').val(dial_code);
        });
        // $('.iti__country').click(function() {
        //     var code = $(this).attr('data-country-code');
        //     document.getElementById('addCountryData').value = code;
        // })
    </script>
    @include('backend.vendor.pagescript')
    <script src="{{asset('js/admin_vendor.js')}}"></script>
    <script type="text/javascript">
        var search_text = "{{getNomenclatureName('vendors', false)}}";
    </script>
@endsection