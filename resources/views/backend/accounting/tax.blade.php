@extends('layouts.vertical', ['demo' => 'Taxes', 'title' => 'Accounting - Taxes'])
@section('css')
<link href="{{asset('assets/libs/dropzone/dropzone.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/dropify/dropify.min.css')}}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">{{ __("Taxes") }}</h4>
                </div>
            </div>
        </div>     
        <div class="row">
            <div class="col-12">
                <div class="card widget-inline">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6 mb-3 mb-sm-0">
                                <div class="p-2 text-center">
                                    <h3>
                                        <i class="mdi mdi-cart-plus text-primary mdi-24px"></i>
                                        <span data-plugin="counterup" id="type_of_taxes_applied_count">{{$type_of_taxes_applied_count}}</span>
                                    </h3>
                                    <p class="text-muted font-15 mb-0">{{ __('Type Of Taxes Applied') }}</p>
                                </div>
                            </div>
                            <div class="col-sm-6 mb-3 mb-sm-0">
                                <div class="p-2 text-center">
                                    <h3><i class="fas fa-money-check-alt text-success"></i> <span data-plugin="counterup" id="total_tax_collected">{{$total_tax_collected}}</span></h3>
                                    <p class="text-muted font-15 mb-0">{{ __("Total Tax Collected") }}</p>
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
                                        <select class="form-control" id="tax_type_select_box">
                                            <option value="">{{ __("Select Tax Type") }}</option>
                                            @foreach($tax_category_options as $tax_category_option)
                                                <option value="{{$tax_category_option->id}}">{{$tax_category_option->title}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col">
                                        <select class="form-control" id="payment_option_select_box">
                                            <option value="">{{ __("Select Payment Method") }}</option>
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
                        <table class="table table-centered table-nowrap table-striped" id="accounting_tax_datatable" width='100%'>
                            <thead>
                                <tr>
                                    <th>{{ __("Order ID") }}</th>
                                    <th>{{ __("Date & Time") }}</th>
                                    <th>{{ __("Customer Name") }}</th>
                                    <th>{{ __("Final Amount") }}</th>
                                    <th>{{ __("Tax Amount") }}</th>
                                    <th>{{ __("Tax Types") }}</th>
                                    <th>{{ __("Payment Method") }}</th>
                                </tr>
                            </thead>
                            <tbody id="accounting_vendor_tbody_list">
                                
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
                $("#tax_type_select_box, #payment_option_select_box").change(function() {
                    initDataTable();
                }); 
                $("#clear_filter_btn_icon").click(function() {
                    $('#range-datepicker').val('');
                    $('#tax_type_select_box').val('')
                    $('input[type="search"]').val('');
                    $('#payment_option_select_box').val('')
                    initDataTable();
                });
                function initDataTable() {
                    $('#accounting_tax_datatable').DataTable({
                        "dom": '<"toolbar">Bfrtip',
                        "destroy": true,
                        "scrollX": true,
                        "processing": true,
                        "serverSide": true,
                        "iDisplayLength": 50,
                        language: {
                            search: "",
                            paginate: { previous: "<i class='mdi mdi-chevron-left'>", next: "<i class='mdi mdi-chevron-right'>" },
                            searchPlaceholder: "Search By Order ID"
                        },
                        drawCallback: function () {
                            $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
                        },
                        buttons: [{   
                            className:'btn btn-success waves-effect waves-light',
                            text: '<span class="btn-label"><i class="mdi mdi-export-variant"></i></span>Export CSV',
                            action: function ( e, dt, node, config ) {
                                window.location.href = "{{ route('account.tax.export') }}";
                            }
                        }],
                        ajax: {
                          url: "{{route('account.tax.filter')}}",
                          data: function (d) {
                            d.search = $('input[type="search"]').val();
                            d.date_filter = $('#range-datepicker').val();
                            d.payment_option = $('#payment_option_select_box option:selected').val();
                            d.tax_type_filter = $('#tax_type_select_box option:selected').val();
                          }
                        },
                        columns: [
                            {data: 'order_number', name: 'order_number', orderable: false, searchable: false},
                            {data: 'created_date', name: 'created_date', orderable: false, searchable: false},
                            {data: 'customer_name', name: 'customer_name', orderable: false, searchable: false},
                            {data: 'payable_amount', name: 'payable_amount', orderable: false, searchable: false},
                            {data: 'taxable_amount', name: 'taxable_amount', orderable: false, searchable: false},
                            {data: 'tax_types', name: 'tax_types', orderable: false, searchable: false},
                            {data: 'payment_method', name: 'payment_method', orderable: false, searchable: false},
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