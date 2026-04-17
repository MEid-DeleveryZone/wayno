@extends('layouts.vertical', ['demo' => 'creative', 'title' => 'Customers'])
@section('css')
<link href="{{asset('assets/libs/dropzone/dropzone.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/dropify/dropify.min.css')}}" rel="stylesheet" type="text/css" />
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
                <h4 class="page-title">{{ __("Customers") }}</h4>
            </div>
        </div>
        <div class="col-sm-6 text-right">
            <button class="btn btn-info waves-effect waves-light text-sm-right addUserModal" userId="0"><i class="mdi mdi-plus-circle mr-1"></i> {{ __("Add") }}
            </button>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card-box">
                <div class="row">
                    <div class="col-sm-4 col-md-4 mb-3 mb-md-0">
                        <div class="text-center">
                            <h3>
                                <i class="fa fa-user text-primary mdi-24px"></i>
                                <span data-plugin="counterup" id="total_vendor">{{$active_users}}</span>
                            </h3>
                            <p class="text-muted font-15 mb-0">{{ __("Active User Count") }}</p>
                        </div>
                    </div>
                    <div class="col-sm-4 col-md-4 mb-3 mb-md-0">
                        <div class="text-center">
                            <h3>
                                <i class="fas fa-user-clock text-primary mdi-24px"></i>
                                <span data-plugin="counterup" id="total_product">{{ $inactive_users }}</span>
                            </h3>
                            <p class="text-muted font-15 mb-0">{{ __("Inactive User Count") }}</p>
                        </div>
                    </div>
                    <div class="col-sm-4 col-md-4 mb-3 mb-md-0">
                        <div class="text-center">
                            <h3>
                                <i class="mdi mdi-login text-primary mdi-24px"></i>
                                <span data-plugin="counterup" id="total_product">{{ $social_logins }}</span>
                            </h3>
                            <p class="text-muted font-15 mb-0">{{ __("Social Login Count") }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row main-customer-page">
        <div class="col-12">
            <div class="card-box">
                <div class="row mb-2">
                    <div class="col-sm-12">
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
                </div>

                <div class="table-responsive">
                    <table class="table table-centered table-nowrap table-striped" id="user_datatable" width="100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ __('Image')}}</th>
                                <th>{{ __('Name')}}</th>
                                <th>{{ __('Login Type') }}</th>
                                <th>{{ __('Email/Auth-id')}}</th>
                                <th>{{ __('Phone')}}</th>
                                <th>{{ __("Email OTP") }}</th>
                                <th>{{ __("Phone OTP") }}</th>
                                <th>{{ __('Wallet')}}</th>
                                <th>{{ __('Orders')}}</th>
                                <th>{{ __('Active Orders') }}</th>
                                <th>{{ __('Status')}}</th>
                                <th>{{ __('Action')}}</th>
                            </tr>
                        </thead>
                        <tbody id="post_list">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@include('backend.users.modals')
<script type="text/javascript">
    $(document).ready(function() {
        var table;
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            }
        });
        initDataTable();
        $(document).on("click", ".delete-vendor", function() {
            var destroy_url = $(this).data('destroy_url');
            var id = $(this).data('rel');
            if (confirm('Are you sure?')) {
                $.ajax({
                    type: "POST",
                    dataType: 'json',
                    url: destroy_url,
                    data: {
                        '_method': 'DELETE'
                    },
                    success: function(response) {
                        if (response.status == "Success") {
                            $.NotificationApp.send("Success", response.message, "top-right", "#5ba035", "success");
                            window.location.reload();
                        }
                    }
                });
            }
        });

        function initDataTable() {
            try {
                $('#user_datatable').DataTable({
                    "dom": '<"toolbar">Bfrtip',
                    "destroy": true,
                    "scrollX": true,
                    "processing": true,
                    "serverSide": true,
                    "iDisplayLength": 10,
                    language: {
                        search: "",
                        paginate: {
                            previous: "<i class='mdi mdi-chevron-left'>",
                            next: "<i class='mdi mdi-chevron-right'>"
                        },
                        searchPlaceholder: "Search By Name, Email, Phone Number"
                    },
                    drawCallback: function() {
                        $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
                    },
                    buttons: [{
                        className: 'btn btn-success waves-effect waves-light',
                        text: '<span class="btn-label"><i class="mdi mdi-export-variant"></i></span>Export CSV',
                        action: function(e, dt, node, config) {
                            window.location.href = "{{ route('customer.export') }}";
                        }
                    }],
                    ajax: {
                        url: "{{route('user.filterdata')}}",
                        data: function(d) {
                            d.search = $('input[type="search"]').val();
                            d.date_filter = $('#range-datepicker').val();
                            d.payment_option = $('#payment_option_select_box option:selected').val();
                            d.tax_type_filter = $('#tax_type_select_box option:selected').val();
                        }
                    },
                    "initComplete": function(settings, json) {
                        var elems = Array.prototype.slice.call(document.querySelectorAll('.chk_box'));
                        elems.forEach(function(html) {
                            var switchery = new Switchery(html);
                        });
                        $('.dataTables_filter input[type="search"]').css({
                            'width': '280px',
                            'display': 'inline-block'
                        });
                        $("#user_datatable_wrapper").find($(".dt-buttons.btn-group.flex-wrap")).css({
                            'right': '320px'
                        });
                    },
                    columns: [{
                            data: 'id',
                            name: 'id',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'image_url',
                            name: 'image_url',
                            orderable: false,
                            searchable: false,
                            "mRender": function(data, type, full) {
                                return "<img src='" + full.image_url + "' class='rounded-circle' alt='" + full.id + "' >";
                            }
                        },
                        {
                            data: 'name',
                            name: 'name',
                            orderable: false,
                            searchable: false,
                            "mRender": function(data, type, full) {
                                return "<a href='" + full.edit_url + "'>" + full.name + "</a> ";
                            }
                        },
                        {
                            data: 'login_type',
                            name: 'login_type',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'login_type_value',
                            name: 'login_type_value',
                            orderable: false,
                            searchable: false,
                            "mRender": function(data, type, full) {
                                if (full.is_email_verified == 1) {
                                    return "<i class='mdi mdi-email-check mr-1 mdi-icons'></i>" + full.login_type_value;
                                } else {
                                    return "<i class='mdi mdi-email-remove mr-1 mdi-icons'></i>" + full.login_type_value;
                                }
                            }
                        },
                        {
                            data: 'is_phone_verified',
                            name: 'is_phone_verified',
                            orderable: false,
                            searchable: true,
                            "mRender": function(data, type, full) {
                                if(full.dial_code && full.phone_number){
                                    var dialcode = full.dial_code;
                                    full.phone_number = '+'+ dialcode + full.phone_number;
                                }else{
                                    full.phone_number =  full.phone_number;
                                }
                                
                                if (full.is_phone_verified == 1) {
                                    if (full.phone_number) {
                                        return "<i class='mdi mdi-phone-check mr-1 mdi-icons'></i>" + full.phone_number;
                                    } else {
                                        return "";
                                    }
                                } else {
                                    if (full.phone_number) {
                                        return full.phone_number;
                                    } else {
                                        return "";
                                    }
                                }
                            }
                        },
                        {
                            data: 'email_token',
                            name: 'email_token',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'phone_token',
                            name: 'phone_token',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'balanceFloat',
                            name: 'balanceFloat',
                            orderable: false,
                            searchable: false,
                            "mRender": function(data, type, full) {
                                return "<a href='javascript:void(0)' class='customer_wallet_link' data-id='" + full.wallet.id + "'>" + data + "</a>";
                            }
                        },
                        {
                            data: 'orders_count',
                            name: 'orders_count',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'currently_working_orders_count',
                            name: 'currently_working_orders_count',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'status',
                            name: 'status',
                            orderable: false,
                            searchable: false,
                            "mRender": function(data, type, full) {
                                if (full.status == 1) {
                                    return "<input type='checkbox' data-id='" + full.id + "' id='cur_" + full.id + "' data-plugin='switchery' name='userAccountStatus' class='chk_box' data-color='#43bee1' checked>";
                                } else {
                                    return "<input type='checkbox' data-id='" + full.id + "' id='cur_" + full.id + "' data-plugin='switchery' name='userAccountStatus' class='chk_box' data-color='#43bee1'>";
                                }
                            }
                        },
                        {
                            data: 'is_superadmin',
                            name: 'is_superadmin',
                            orderable: false,
                            searchable: false,
                            "mRender": function(data, type, full) {
                                if (full.is_superadmin == 1) {
                                    return "<div class='form-ul'><div class='inner-div'><a href='" + full.edit_url + "' class='action-icon editIconBtn'><i class='mdi mdi-square-edit-outline'></i></a><a href='" + full.delete_url + "' class='action-icon'><i class='mdi mdi-delete' title='Delete user'></i></a></div></div>";
                                }
                            }
                        },
                    ]
                });
            } finally {
                var elems = Array.prototype.slice.call(document.querySelectorAll('.chk_box'));
                // console.log(elems);
                elems.forEach(function(html) {
                    var switchery = new Switchery(html);
                });
            }
        }
    });

    $(document).delegate(".customer_wallet_link", "click", function() {
        let id = $(this).attr("data-id");
        $('#customer-wallet-transactions-modal').modal('show');
        $('#customer_wallet_transactions_datatable').DataTable({
            "dom": '<"toolbar">Bfrtip',
            "destroy": true,
            "scrollX": true,
            "processing": true,
            "serverSide": true,
            "iDisplayLength": 10,
            language: {
                search: "",
                paginate: {
                    previous: "<i class='mdi mdi-chevron-left'>",
                    next: "<i class='mdi mdi-chevron-right'>"
                },
                searchPlaceholder: "Search By Date, Description, Amount"
            },
            drawCallback: function() {
                $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
            },
            buttons: [],
            ajax: {
                url: "{{route('customer.filterWalletTransactions')}}",
                data: function(d) {
                    d.search = $('input[type="search"]').val();
                    d.walletId = id;
                }
            },
            "initComplete": function(settings, json) {

            },
            columnDefs: [{
                targets: [1, 3],
                className: "text-nowrap",
            }],
            columns: [{
                    data: 'serial',
                    name: 'serial',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'date',
                    name: 'date',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'description',
                    name: 'description',
                    orderable: false,
                    searchable: false,
                    "mRender": function(data, type, full) {
                        return '<span>' + data + '</span>';
                    }
                },
                {
                    data: 'amount',
                    name: 'amount',
                    orderable: false,
                    searchable: false,
                    "mRender": function(data, type, full) {
                        return '<span class="text-right ' + ((full.type == 'deposit') ? 'text-success' : ((full.type == 'withdraw') ? 'text-danger' : '')) + '">' + data + '</span>';
                    }
                },
            ]
        });
    });
</script>
@endsection
@section('script')
<script src="{{asset('assets/js/intlTelInput.js')}}"></script>
<script type="text/javascript">
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
    var input = document.querySelector("#phone");
    window.intlTelInput(input, {
        separateDialCode: true,
        hiddenInput: "contact",
        utilsScript: "{{asset('assets/js/utils.js')}}",
        initialCountry: "{{ Session::get('default_country_code','US') }}",
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
        var dial_code = $(this).attr('data-dial-code');
        $('#dialCode').val(dial_code);
    });
    // $('.iti__country').click(function() {
    //     var code = $(this).attr('data-country-code');
    //     document.getElementById('addCountryData').value = code;
    // })
</script>
@include('backend.users.pagescript')
@endsection