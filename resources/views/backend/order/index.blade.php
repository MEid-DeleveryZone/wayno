@extends('layouts.vertical', ['title' => 'Orders'])
@section('content')
    <style type="text/css">
        .ellipsis {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        body {
            font-size: 0.75rem;
        }

        .order_data>div,
        .order_head h4 {
            padding: 0 !important;
        }

        .order-page .card-box {
            padding: 20px 20px 5px !important;
        }

        .progress-order {
            width: calc(100% + 48px);
            margin: -24px 0 20px;
            background: #00000012;
            color: var(--theme-deafult);
            position: relative;
            left: -24px;
            font-weight: 600;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
            padding: 5px 0;
        }
    </style>

    <script type="text/template" id="order_page_template">
    <div class="row">
        <% _.each(orders, function(order, k){%>
            <% if(order.vendors.length !== 0) { %>
                <div class="col-xl-6"  id="full-order-div<%= k %>">
                    <div class="row no-gutters order_head">
                        <div class="col-md-3"><h4>{{ __("Order ID") }}</h4></div>
                        <div class="col-md-3"><h4>{{ __("Date & Time") }}</h4></div>
                        <div class="col-md-3"><h4>{{ __("Customer") }}</h4></div>
                        <div class="col-md-3"><h4>{{ __("Address") }}</h4></div>
                    </div>
                    <div class="row no-gutters order_data mb-lg-2">
                        <div class="col-md-3"><h6 class="m-0">#<%= order.order_number %></h6></div>
                        <div class="col-md-3"><%= order.created_date %></div>
                        <div class="col-md-3">
                            <a class="text-capitalize" href="#"><%= order.user.name %></a>
                        </div>

                        <% if(order.delivery_address !== null) { %>
                        <% var deliveryData = JSON.parse(order.delivery_address); %>
                        <div class="col-md-3">
                            <p class="ellipsis mb-0" data-toggle="tooltip" data-placement="top" title="<%= order.delivery_address %>">
                                <%= deliveryData.address %>
                            </p>
                        </div>
                        <% } %>
                    </div>
                    <div class="row">
                        <div class="col-md-9">
                            <% _.each(order.vendors, function(vendor, ve){%>
                                <div class="row  <%= ve ==0 ? 'mt-0' : 'mt-2'%>" id="single-order-div<%= k %><%= ve %>">
                                    <div class="col-12 order-hover-btn">

                                        <div id="update-single-status">
                                            <% if(vendor.order_status_option_id == 1) { %>
                                                <% if(vendor.web_hook_code && vendor.dispatch_traking_url) { %>
                                                    <!-- Order already created in dispatcher - Accept button disabled -->
                                                    <button class="btn-info" disabled style="opacity: 0.6; cursor: not-allowed;">{{ __('Accept') }}</button>
                                                <% } else { %>
                                                    <button class="update-status btn-info" data-full_div="#full-order-div<%= k %>"  data-single_div="#single-order-div<%= k %><%= ve %>" data-count="<%= ve %>" data-order_id="<%= order.id %>"  data-vendor_id="<%= vendor.vendor_id %>"  data-status_option_id="2" data-order_vendor_id="<%= vendor.order_vendor_id %>">{{ __('Accept') }}</button>
                                                <% } %>
                                                <!--<button class="update-status btn-danger" id="reject" data-full_div="#full-order-div<%= k %>"  data-single_div="#single-order-div<%= k %><%= ve %>"  data-count="<%= ve %>"   data-order_id="<%= order.id %>"  data-vendor_id="<%= vendor.vendor_id %>" data-status_option_id="3" data-order_vendor_id="<%= vendor.order_vendor_id %>">{{ __('Reject') }}</button>-->
                                            <% } else if(vendor.order_status_option_id == 2) { %>
                                                <button class="update-status btn-warning" disabled data-full_div="#full-order-div<%= k %>"  data-single_div="#single-order-div<%= k %><%= ve %>"  data-count="<%= ve %>"  data-order_id="<%= order.id %>"  data-vendor_id="<%= vendor.vendor_id %>"  data-status_option_id="4" data-order_vendor_id="<%= vendor.order_vendor_id %>">{{ __('Processing') }}</button>
                                            <% } else if(vendor.order_status_option_id == 4) { %>
                                                    <button class="update-status btn-success" disabled data-full_div="#full-order-div<%= k %>"  data-single_div="#single-order-div<%= k %><%= ve %>"  data-count="<%= ve %>"  data-order_id="<%= order.id %>"  data-vendor_id="<%= vendor.vendor_id %>"  data-status_option_id="5" data-order_vendor_id="<%= vendor.order_vendor_id %>">{{ __('Out For Delivery') }}</button>
                                            <% } else if(vendor.order_status_option_id == 5) { %>
                                                <button class="update-status btn-info" disabled data-full_div="#full-order-div<%= k %>"  data-single_div="#single-order-div<%= k %><%= ve %>"  data-count="<%= ve %>"  data-order_id="<%= order.id %>"  data-vendor_id="<%= vendor.vendor_id %>"  data-status_option_id="6" data-order_vendor_id="<%= vendor.order_vendor_id %>">{{ __('Delivered') }}</button>
                                            <% } else { %>

                                            <% } %>
                                            <% if((vendor.order_status_option_id == 1) || ((vendor.order_status_option_id != 6) && (vendor.order_status_option_id != 3))) { %>
                                                <button class="update-status btn-danger" id="reject" data-full_div="#full-order-div<%= k %>"  data-single_div="#single-order-div<%= k %><%= ve %>"  data-count="<%= ve %>"   data-order_id="<%= order.id %>"  data-vendor_id="<%= vendor.vendor_id %>" data-status_option_id="3" data-order_vendor_id="<%= vendor.order_vendor_id %>">{{ __('Cancel') }}</button>
                                            <% } %>
                                        </div>

                                        <a href="<%= vendor.vendor_detail_url %>" class="row order_detail order_detail_data align-items-top pb-1 mb-0 card-box no-gutters h-100" target="_blank">
                                            <!--<% if(order.scheduled_date_time || (order.luxury_option_name != '')) { %>
                                            <div class="col-sm-12">
                                                <div class="progress-order font-12">
                                                    <% if(order.luxury_option_name != '') { %>
                                                        <span class="badge badge-info ml-2"><%= order.luxury_option_name %></span>
                                                    <% } %>
                                                    <% if(order.scheduled_date_time) { %>
                                                        <span class="badge badge-success ml-2">Scheduled</span>
                                                        <span class="ml-2"><%= order.scheduled_date_time %></span>
                                                    <% } %>
                                                </div>
                                            </div>
                                            <% } %>-->
                                            <% if(order.product_sla || (order.product_sla != '')) { %>
                                            <div class="col-sm-12">
                                                <div class="progress-order font-12">
                                                    <% if(order.product_sla) { %>
                                                        <span class="badge badge-success ml-2">SLA</span>
                                                        <span class="ml-2"><%= order.product_sla %></span>
                                                    <% } %>
                                                </div>
                                            </div>
                                            <% } %>
                                            <span class="left_arrow pulse">
                                            </span>
                                            <div class="col-5 col-sm-3">
                                                <h5 class="m-0"><%= vendor.vendor_name %></h5>
                                                <ul class="status_box mt-3 pl-0">
                                                    <li>
                                                        <img src="{{ asset('assets/images/order-icon.svg') }}" alt="">
                                                        <label class="m-0 in-progress" style="white-space: nowrap;">
    <%= vendor.order_status_option_id == 3 && vendor.cancelled_by == 2 ? vendor.order_status + ' by Customer' : vendor.order_status %>

                                                        </label>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="col-7 col-sm-6">
                                                <div class="row no-gutters product_list align-items-center flex-wrap">
                                                    <% _.each(vendor.products, function(product, pr){%>
                                                        <div class="col-4 text-center mb-2">
                                                            <div class="list-img">
                                                                <img src="<%= product.image_path.proxy_url %>74/100<%= product.image_path.image_path %>">
                                                                <span class="item_no position-absolute">x<%= product.quantity %></span>
                                                            </div>
                                                            <!-- <h6 class="mx-1 mb-0 mt-1 ellips">Vendor Name</h6>    -->
                                                            <label class="items_price">{{$CurremcySymbel}} <%= product.price %></label>
                                                        </div>
                                                    <% }); %>
                                                </div>
                                            </div>
                                            <div class="col-md-3 mt-md-0 mt-sm-2">
                                                <ul class="price_box_bottom m-0 p-0">
                                                    <% if(vendor.subtotal_amount > 0 || vendor.subtotal_amount < 0) { %>
                                                    <li class="d-flex align-items-center justify-content-between">
                                                        <label class="m-0">{{ __('Total') }}</label>
                                                        <span>{{$CurremcySymbel}} <%= vendor.subtotal_amount %></span>
                                                    </li>
                                                    <% } %>
                                                    <% if(vendor.discount_amount > 0 || vendor.discount_amount < 0) { %>
                                                    <li class="d-flex align-items-center justify-content-between">
                                                        <label class="m-0">{{ __('Promocode') }}</label>
                                                        <span>{{$CurremcySymbel}} <%= vendor.discount_amount %></span>
                                                    </li>
                                                    <% } %>
                                                    <% if(vendor.delivery_fee > 0 || vendor.delivery_fee < 0) { %>
                                                    <li class="d-flex align-items-center justify-content-between">
                                                        <label class="m-0">{{ __('Delivery') }}</label>
                                                        <% if(vendor.delivery_fee !== null) { %>
                                                        <span>{{$CurremcySymbel}} <%= vendor.delivery_fee %></span>
                                                        <% }else { %>
                                                            <span>{{$CurremcySymbel}} 0.00</span>
                                                        <% } %>
                                                    </li>
                                                    <% } %>

                                                    <li class="grand_total d-flex align-items-center justify-content-between">
                                                        <label class="m-0">{{ __('Amount') }}</label>
                                                        <span>{{$CurremcySymbel}} <%= vendor.payable_amount %></span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            <% }); %>
                        </div>
                        <div class="col-md-3 pl-0">
                            <div class="card-box p-2 mb-0 w-100 h-100">
                                <ul class="price_box_bottom m-0 pl-0 pt-1">
                                    <li class="d-flex align-items-center justify-content-between">
                                        <label class="m-0">{{ __('Total') }}</label>
                                        <span>{{$CurremcySymbel}} <%= order.total_amount %></span>
                                    </li>
                                    <% if(order.loyalty_amount_saved > 0 || order.loyalty_amount_saved < 0) { %>
                                    <li class="d-flex align-items-center justify-content-between">
                                        <label class="m-0">{{ __('Loyalty Used') }}</label>
                                        <span>{{$CurremcySymbel}} <%= order.loyalty_amount_saved %></span>
                                    </li>
                                    <% } %>

                                    <% if(order.taxable_amount > 0 || order.taxable_amount < 0) { %>
                                    <li class="d-flex align-items-center justify-content-between">
                                        <label class="m-0">{{ __('Tax') }}</label>
                                        <span>{{$CurremcySymbel}} <%= order.taxable_amount %></span>
                                    </li>
                                    <% } %>
                                    <% if(order.total_delivery_fee > 0 || order.total_delivery_fee < 0) { %>
                                    <li class="d-flex align-items-center justify-content-between">
                                        <label class="m-0">{{__('Delivery Fee')}}</label>
                                        <span>{{$CurremcySymbel}} <%= order.total_delivery_fee %></span>
                                    </li>
                                    <% } %>
                                    <% if(order.wallet_amount_used > 0 || order.wallet_amount_used < 0) { %>
                                    <li class="d-flex align-items-center justify-content-between">
                                        <label class="m-0">{{__('Wallet Amount Used')}}</label>
                                        <span>{{$CurremcySymbel}} <%= order.wallet_amount_used %></span>
                                    </li>
                                    <% } %>
                                    <% if(order.total_discount_calculate > 0 || order.total_discount_calculate < 0) { %>
                                    <li class="d-flex align-items-center justify-content-between">
                                        <label class="m-0">{{__('Total Discount')}}</label>
                                        <span>{{$CurremcySymbel}} <%= order.total_discount_calculate %></span>
                                    </li>
                                    <% } %>
                                    <li class="grand_total d-flex align-items-center justify-content-between">
                                        <label class="m-0">{{ __('Payable') }} </label>
                                             <span>{{$CurremcySymbel}} <%= order.payable_amount %></span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            <% } %>
        <% }); %>
    </div>
    <% if(next_page_url) { %>
        <div class="row mt-4 mb-4">
            <div class="col-md-4 offset-md-4 text-center">
                <button class="ladda-button btn btn-primary load-more-btn" dir="ltr" data-style="expand-left" data-url="<%= next_page_url%>" data-rel="<%= filter_order_status %>">
                    <span class="ladda-label">{{ __('Load More') }}</span>
                    <span class="ladda-spinner"></span>
                    <div class="ladda-progress" style="width: 0px;"></div>
                </button>
            </div>
        </div>
    <% } %>
</script>
    <div class="container-fluid order-page">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="page-title">{{ __('Orders') }}</h4>
                    <a class="return-btn" href="{{ route('backend.order.returns', ['Pending']) }}">
                        <b>{{ __('Return Request') }} <sup class="total-items">({{ $return_requests }})</sup>
                            <i class="fa fa-arrow-circle-right ml-1" aria-hidden="true"></i>
                        </b>
                    </a>
                </div>
            </div>
            <div class="offset-md-9 col-md-3 offset-lg-10 col-lg-2 mb-2">
                <input type="search" class="form-control form-control-sm" placeholder="{{ __('Search By Order ID') }}"
                    id="search_via_keyword">
            </div>
        </div>
    </div>
    <script type="text/template" id="no_order_template">
    <div class="error-msg"><p>{{ __('You have not any order yet now.') }}</p></div>
    </script>
    <div class="loader" id="order_list_order">
        <div class="spinner-border avatar-lg text-primary m-2" role="status"></div>
    </div>
    <div class="row">
        <div class="col-sm-12 col-lg-12 tab-product pt-0">
            <ul class="nav nav-tabs nav-material" id="top-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="pending_order-tab" data-toggle="tab" href="#pending_orders"
                        role="tab" aria-selected="false" data-rel="pending_orders">
                        <i class="icofont icofont-man-in-glasses"></i>{{ __('Pending Orders') }} <sup class="total-items"
                            id="pending-orders">({{ $pending_order_count }})</sup>
                    </a>
                    <div class="material-border"></div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="active_orders_tab" data-toggle="tab" href="#active_orders" role="tab"
                        aria-selected="true" data-rel="active_orders">
                        <i class="icofont icofont-ui-home"></i>{{ __('Active Orders') }} <sup class="total-items"
                            id="active-orders">({{ $active_order_count }})</sup>
                    </a>
                    <div class="material-border"></div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="orders_history_tab" data-toggle="tab" href="#orders_history" role="tab"
                        aria-selected="false" data-rel="orders_history">
                        <i class="icofont icofont-man-in-glasses"></i>{{ __('Orders History') }} <sup class="total-items"
                            id="history-orders">({{ $past_order_count }})</sup>
                    </a>
                    <div class="material-border"></div>
                </li>
            </ul>
            <div class="tab-content nav-material  order_data_box scroll-style" id="top-tabContent">
                <div class="tab-pane fade past-order show active" id="pending_orders" role="tabpanel"
                    aria-labelledby="pending_order-tab"></div>
                <div class="tab-pane fade" id="active_orders" role="tabpanel" aria-labelledby="active_orders_tab"></div>
                <div class="tab-pane fade past-order" id="orders_history" role="tabpanel"
                    aria-labelledby="orders_history_tab">
                    <div class="error-msg">
                        <p>{{ __('You have not any order yet now.') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>


    <div id="addRejectmodal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header border-bottom">
                    <h4 class="modal-title">{{ __('Reject Reason') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="loader" id="order_reject_loader" style="display: none">
                    <div class="spinner-border avatar-lg text-primary m-2" role="status"></div>
                </div>
                <form id="addRejectForm" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body" id="AddRejectBox">
                        <p id="error-case" style="color:red;"></p>
                        <div class="form-group" id="countryInput">
                            <label style="font-size:medium;">Select reason for rejecting the order.</label>
                            <select class="selectize-select form-control" name="reject_reason" id="reject_reason">
                                <option value="">Select Rejecting Reason</option>
                                @foreach ($rejecting_reasons as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button"
                            class="btn btn-info waves-effect waves-light addrejectSubmit">{{ __('Submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('js/validate.min.js') }}"></script>
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            }
        });

        function init(filter_order_status, url, search_keyword = "", isOnload = false) {
            $.ajax({
                url: url,
                type: "POST",
                dataType: "JSON",
                data: {
                    filter_order_status: filter_order_status,
                    search_keyword: search_keyword
                },
                success: function(response) {
                    $('#order_list_order').hide();
                    if (response.status == 'Success') {
                        if (!isOnload) {
                            $(".tab-pane").html('');
                        }
                        if (response.data.orders.data.length != 0) {
                            let order_page_template = _.template($('#order_page_template').html());
                            $("#" + filter_order_status).append(order_page_template({
                                orders: response.data.orders.data,
                                next_page_url: response.data.orders.next_page_url,
                                filter_order_status: filter_order_status
                            }));
                        } else {
                            let no_order_template = _.template($('#no_order_template').html());
                            $("#" + filter_order_status).html(no_order_template({}));
                        }
                        $("#active-orders").html("(" + response.data.active_orders + ")");
                        $("#pending-orders").html("(" + response.data.pending_orders + ")");
                        $("#history-orders").html("(" + response.data.orders_history + ")");
                    }
                },
                error: function(data) {

                },
            });
        }
        $(document).ready(function() {
            setTimeout(function() {
                $("#pending_order-tab").trigger('click');
            }, 500);
            $(document).on("click", ".load-more-btn", function() {
                $('#order_list_order').show();
                var url = $(this).data('url');
                var rel = $(this).data('rel');
                $("#search_via_keyword").val("");
                init(rel, url, '', true);
                $(this).remove();
            });
            $(".nav-link").click(function() {
                $('#order_list_order').show();
                var rel = $(this).data('rel');
                var url = "{{ route('orders.filter') }}";
                $("#search_via_keyword").val("");
                // $(".tab-pane").html('');
                init(rel, url, '', false);
            });

            $("#search_via_keyword").on("keyup blur", function(e) {
                $('#order_list_order').show();
                var rel = $("#top-tab li a.active").data('rel');
                var url = "{{ route('orders.filter') }}";
                var search_keyword = $(this).val();
                // $(".tab-pane").html('');
                init(rel, url, search_keyword, false);
            })


            function openRejectModal(order_id, vendor_id, status_option_id, order_vendor_id) {

                $('#addRejectmodal').modal({
                    backdrop: 'static',
                    keyboard: false,

                });
                $('.addrejectSubmit').on('click', function(e) {
                    e.preventDefault();

                    const submitButton = $(this); // Reference to the button
                    const loader = $('#order_reject_loader'); // Reference to the loader
                    
                    $('#addRejectForm').validate({
                        rules: {
                            reject_reason: {
                                required: true,
                            },
                        },
                        errorElement: 'span',
                        errorPlacement: function(error, element) {
                            error.addClass('invalid-feedback');
                            element.closest('.form-group').append(error);
                        },
                        highlight: function(element, errorClass, validClass) {
                            $(element).addClass('is-invalid');
                        },
                        unhighlight: function(element, errorClass, validClass) {
                            $(element).removeClass('is-invalid');
                        }
                    });

                    if ($("#addRejectForm").valid()) {
                        // Disable the button and show loader
                        submitButton.prop('disabled', true).text('Processing...');
                        loader.show();

                        $.ajax({
                            url: "{{ route('order.changeStatus') }}",
                            type: "POST",
                            data: {
                                order_id: order_id,
                                vendor_id: vendor_id,
                                reject_reason: $('#addRejectForm #reject_reason').val(),
                                "_token": "{{ csrf_token() }}",
                                status_option_id: status_option_id,
                                order_vendor_id: order_vendor_id,
                            },
                            success: function(response) {
                                if (response.status == 'success') {
                                    location.reload();
                                } else if (response.status == 'error') {
                                    $('#error-case').empty();
                                    $('#error-case').append(response.message);
                                }
                            },
                            complete: function() {
                                // Re-enable the button and hide loader
                                submitButton.prop('disabled', false).text('Submit');
                                loader.hide();
                            },
                            error: function(response) {
                                if (response.status == 'error') {
                                    $('#error-case').empty();
                                    $('#error-case').append(response.message);
                                }
                                // Re-enable the button even if there's an error
                                submitButton.prop('disabled', false).text('Submit');
                                loader.hide();
                            }
                        });
                    }
                });

            }





            // update status
            $(document).on("click", ".update-status", function() {

                let that = $(this);
                var count = that.data("count");
                var full_div = that.data("full_div");
                var single_div = that.data("single_div");
                var status_option_id = that.data("status_option_id");
                var status_option_id_next = status_option_id + 1;
                var order_vendor_id = that.data("order_vendor_id");
                var order_id = that.data("order_id");
                var vendor_id = that.data("vendor_id");
                var count = that.data("count");
                if (status_option_id == 3) {
                    return openRejectModal(order_id, vendor_id, status_option_id, order_vendor_id);
                } else {
                    if (confirm("Are you Sure?")) {
                        $.ajax({
                            url: "{{ route('order.changeStatus') }}",
                            type: "POST",
                            data: {
                                order_id: order_id,
                                vendor_id: vendor_id,
                                "_token": "{{ csrf_token() }}",
                                status_option_id: status_option_id,
                                order_vendor_id: order_vendor_id,
                            },
                            success: function(response) {

                                if (status_option_id == 4 || status_option_id == 5) {
                                    if (status_option_id == 4)
                                        var next_status = 'Out For Delivery';
                                    else
                                        var next_status = 'Delivered';
                                    that.replaceWith(
                                        "<button class='update-status btn-warning' data-full_div='" +
                                        full_div + "' data-single_div='" + single_div +
                                        "'  data-count='" + count + "'  data-order_id='" +
                                        order_id + "'  data-vendor_id='" + vendor_id +
                                        "'  data-status_option_id='" +
                                        status_option_id_next + "' data-order_vendor_id=" +
                                        order_vendor_id + ">" + next_status + "</button>");
                                    return false;
                                } else {

                                    if (count == 0) {
                                        $(full_div).slideUp(1000, function() {
                                            $(this).remove();
                                        });

                                    } else {
                                        $(single_div).slideUp(1000, function() {
                                            $(this).remove();
                                        });

                                    }
                                }
                                if (status_option_id == 2)
                                    $.NotificationApp.send("Success", response.message,
                                        "top-right", "#5ba035", "success");
                                // location.reload();
                            },
                        });
                    }
                }
            });
        });
    </script>
@endsection
