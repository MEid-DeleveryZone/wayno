@extends('layouts.vertical', ['demo' => 'Loyalty', 'title' => 'Accounting - Loyality'])
@section('css')
{{-- <link href="{{asset('assets/libs/datatables/datatables.min.css')}}" rel="stylesheet" type="text/css" /> --}}
@endsection
@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                    </div>
                    <h4 class="page-title">{{ __('Loyality') }}</h4>
                        @php
                            $LoyaltyCards = getNomenclatureName('Loyalty Cards', false);
                            $LoyaltyCards = ($LoyaltyCards === 'Loyalty Cards') ? __('Loyalty Cards') : $LoyaltyCards;
                        @endphp
                </div>
            </div>
        </div>     
        <div class="row">
            <div class="col-12">
                <div class="card widget-inline">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6 col-md-3 mb-3 mb-md-0">
                                <div class="p-2 text-center">
                                    <h3>
                                        <i class="mdi mdi-trophy-variant-outline text-primary mdi-24px"></i>
                                        <span data-plugin="counterup">{{$type_of_loyality_applied_count}}</span>
                                    </h3>
                                    <p class="text-muted font-15 mb-0">{{ __('Type Of') }} {{ $LoyaltyCards }} {{ __('Applied') }}</p>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-3 mb-3 mb-md-0">
                                <div class="p-2 text-center">
                                    <h3>
                                        <i class="fas fa-money-check-alt text-primary"></i>
                                        <span data-plugin="counterup">{{$total_loyalty_earned}}</span>
                                    </h3>
                                        <p class="text-muted font-15 mb-0">{{ __("Total") }} {{$LoyaltyCards}} {{ __('Earned') }}</p>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-3 mb-3 mb-md-0">
                                <div class="p-2 text-center">
                                    <h3>
                                        <i class="fas fa-money-check-alt text-primary"></i>
                                        <span data-plugin="counterup">{{$total_loyalty_spent}}</span>
                                    </h3>
                                    <p class="text-muted font-15 mb-0">{{ __('Total') }} {{$LoyaltyCards}} {{ __('Spent') }}</p>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-3 mb-3 mb-md-0">
                                <div class="p-2 text-center">
                                    <h3>
                                        <i class="mdi mdi-eye-outline text-primary mdi-24px"></i>
                                        <span data-plugin="counterup">0</span> k
                                    </h3>
                                    <p class="text-muted font-15 mb-0">{{ __('Unique Orders') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>    
    </div> 
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body position-relative">
                    <div class="top-input position-absolute">
                        <div class="row">                            
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col">
                                        <input type="text" id="range-datepicker" class="form-control flatpickr-input" placeholder="2018-10-03 to 2018-10-10" readonly="readonly">
                                    </div>
                                    <div class="col">
                                        <select class="form-control" id="loyalty_select_box">
                                            <option value="">{{ __('Select') }} {{$LoyaltyCards}}</option>
                                            @foreach($loyalty_card_details as $loyalty_card_detail)
                                                <option value="{{$loyalty_card_detail->id}}">{{$loyalty_card_detail->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col">
                                        <select class="form-control" id="payment_option_select_box">
                                            <option value="">{{ __('Select Payment Option') }}</option>
                                            @foreach($payment_options as $payment_option)
                                                <option value="{{$payment_option->id}}">{{$payment_option->title}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col">
                                        <button type="button" class="btn btn-danger waves-effect waves-light" id="clear_filter_btn_icon">
                                            <i class="mdi mdi-close"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap table-striped" id="accounting_loyality_datatable" width="100%">
                            <thead>

                               

                                <tr>
                                    <th>{{ __('Order ID') }}</th>
                                    <th>{{ __("Date & Time") }}</th>
                                    <th>{{ __('Customer Name') }}</th>
                                    <th>{{ __('Final Amount') }}</th>
                                    <th>{{ $LoyaltyCards }} {{ __('Used') }}</th>
                                    <th>{{ $LoyaltyCards }} {{ __("Membership") }}</th>
                                    <th>{{ $LoyaltyCards }} {{ __("Earned") }}</th>
                                    <th>{{ __("Payment Method") }}</th>
                                </tr>
                            </thead>
                            <tbody id="accounting_loyality_tbody_list">
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        var table;
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            }
        });

        getOrderList();
        function getOrderList() {
            $(document).ready(function() {
                initDataTable();
                $("#range-datepicker").flatpickr({ 
                    mode: "range",
                    onClose: function(selectedDates, dateStr, instance) {
                        initDataTable();
                    }
                });
                $("#clear_filter_btn_icon").click(function() {
                    $('#range-datepicker').val('');
                    $('#loyalty_select_box').val('');
                    $('#payment_option_select_box').val('');
                    initDataTable();
                });
                $("#loyalty_select_box, #payment_option_select_box").change(function() {
                    initDataTable();
                });
                function initDataTable() {
                    $('#accounting_loyality_datatable').DataTable({
                        "dom": '<"toolbar">Bfrtip',
                        "destroy": true,
                        "scrollX": true,
                        "processing": true,
                        "serverSide": true,
                        "iDisplayLength": 50,
                        language: {
                            search: "",
                            paginate: { previous: "<i class='mdi mdi-chevron-left'>", next: "<i class='mdi mdi-chevron-right'>" },
                            searchPlaceholder: "Search By Order No.,Vendor,Customer Name"
                        },
                        drawCallback: function () {
                            $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
                        },
                        buttons: [{   
                            className:'btn btn-success waves-effect waves-light',
                            text: '<span class="btn-label"><i class="mdi mdi-export-variant"></i></span>Export CSV',
                            action: function ( e, dt, node, config ) {
                                window.location.href = "{{ route('account.loyalty.export') }}";
                            }
                        }],
                        ajax: {
                          url: "{{route('account.loyalty.filter')}}",
                          data: function (d) {
                            d.search = $('input[type="search"]').val();
                            d.date_filter = $('#range-datepicker').val();
                            d.payment_option = $('#payment_option_select_box option:selected').val();
                          }
                        },
                        columns: [
                            {data: 'order_number', name: 'order_number', orderable: false, searchable: false},
                            {data: 'created_date', name: 'name',orderable: false, searchable: false},
                            {data: 'user.name', name: 'Customer Name',orderable: false, searchable: false},
                            {data: 'payable_amount', name: 'payable_amount', orderable: false, searchable: false},
                            {data: 'loyalty_points_used', name: 'loyalty_points_used', orderable: false, searchable: false},
                            {data: 'loyalty_membership', name: 'action', orderable: false, searchable: false},
                            {data: 'loyalty_points_earned', name: 'action', orderable: false, searchable: false},
                            {data: 'payment_option.title', name: 'action', orderable: false, searchable: false},
                        ]
                    });
                }
            });
        }
    });
</script>
@endsection
@section('script')
<script src="{{asset('assets/libs/datatables/datatables.min.js')}}"></script>
@endsection