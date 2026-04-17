@extends('layouts.store', ['title' => __('Cart')])

@section('css')
<link href="{{asset('assets/libs/dropzone/dropzone.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/dropify/dropify.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/flatpickr/flatpickr.min.css')}}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="{{asset('assets/css/intlTelInput.css')}}">
<script src="https://js.yoco.com/sdk/v1/yoco-sdk-web.js"></script>
<style type="text/css">
    .swal2-title {
        margin: 0px;
        font-size: 26px;
        font-weight: 400;
        margin-bottom: 28px;
    }

    .discard_price {
        text-decoration: line-through;
        color: #6c757d;
    }
    .btn-disabled {
        opacity: 0.5 !important;
        pointer-events: none;
    }
</style>
@endsection

@section('content')
@php
$now = \Carbon\Carbon::now()->format('Y-m-d\TH:i');
$mixum = \Carbon\Carbon::now()->addDays(3)->format('Y-m-d\TH:i');// \Carbon\Carbon::parse($now)->addHour(7);->addDays(5)

if(Auth::user()){
$timezone = Auth::user()->timezone;
$now = convertDateTimeInTimeZone($now, $timezone, 'Y-m-d\TH:i');
$mixum = convertDateTimeInTimeZone($mixum, $timezone, 'Y-m-d\TH:i');

}
@endphp

<header>
    <div class="mobile-fix-option"></div>
    @if(isset($set_template) && $set_template->template_id == 1)
    @include('layouts.store/left-sidebar-template-one')
    @elseif(isset($set_template) && $set_template->template_id == 2)
    @include('layouts.store/left-sidebar')
    @else
    @include('layouts.store/left-sidebar-template-one')
    @endif
</header>
<script type="text/template" id="address_template">
    <div class="col-md-12">
        <div class="delivery_box px-0">
            <label class="radio m-0"><%= address.address %> <%= address.city %><%= address.state %> <%= address.pincode %>
                <input type="radio" checked="checked" name="address_id" value="<%= address.id %>">
                <span class="checkround"></span>
            </label>
        </div>
    </div>
</script>
<script type="text/template" id="empty_cart_template">
    <div class="container">
    <div class="row mt-2 mb-4 mb-lg-5">
        <div class="col-12 text-center">
            <div class="cart_img_outer">
                <img src="{{asset('front-assets/images/empty_cart.png')}}">
            </div>
            <h3>{{__('Your Cart Is Empty!')}}</h3>
            <p>{{__('Add items to it now.')}}</p>
            <a class="btn btn-solid" href="{{url('/')}}">{{__('Continue Shopping')}}</a>
        </div>
    </div>
</div>
</script>
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h3 class="page-title text-uppercase">{{__('Cart')}}</h3>
            </div>
            <div class="cart_response mt-3 mb-3 d-none">
                <div class="alert p-0" role="alert"></div>
            </div>
        </div>
    </div>
</div>

<script type="text/template" id="cart_template">
    <% _.each(cart_details.products, function(product, key){%>
        <div id="thead_<%= product.vendor.id %>">
            <div class="row">
                <div class="col-12">
                    <h5 class="m-0"><b><%= product.vendor.name %></b></h5>
                </div>
                <div class="col-12">
                    <div class="countdownholder alert-danger" id="min_order_validation_error_<%= product.vendor.id %>" style="display:none;">Your cart will be expired in </div>
                </div>
                <% if( product.is_vendor_closed == 1 ) { %>
                    <div class="col-12">
                        <div class="text-danger">
                            <i class="fa fa-exclamation-circle"></i> Vendor is not accepting orders right now.
                        </div>
                    </div>
                <% } %>
                <% if( (product.isDeliverable != undefined) && (product.isDeliverable == 0) ) { %>
                    <div class="col-12">
                        <div class="text-danger">
                            <i class="fa fa-exclamation-circle"></i> Products for this vendor are not deliverable at your area. Please change address or remove product.
                        </div>
                    </div>
                <% } %>
            </div>
        </div>
        <hr class="mt-2">
        <div id="tbody_<%= product.vendor.id %>">
            <% _.each(product.vendor_products, function(vendor_product, vp){%>
                <div class="row align-items-md-center vendor_products_tr" id="tr_vendor_products_<%= vendor_product.id %>">
                    <div class="product-img col-4 col-md-2 pr-0">
                        <% if(vendor_product.pvariant.media_one) { %>
                            <img class="mr-2 w-100" src="<%= vendor_product.pvariant.media_one.pimage.image.path.proxy_url %>100/70<%= vendor_product.pvariant.media_one.pimage.image.path.image_path %>" alt="">
                        <% }else{ %>
                            <img class="mr-2 w-100" src="<%= vendor_product.pvariant.media_second.image.path.proxy_url %>200/200<%= vendor_product.pvariant.media_second.image.path.image_path %>">
                        <% } %>
                    </div>
                    <div class="col-8 col-md-10">
                        <div class="row align-items-md-center">
                            <div class="col-md-3 order-0">
                                <h4 class="mt-0 mb-1"><%= vendor_product.product.translation_one ? vendor_product.product.translation_one.title :  vendor_product.product.sku %></h4>
                                <% _.each(vendor_product.pvariant.vset, function(vset, vs){%>
                                    <% if(vset.variant_detail.trans) { %>
                                        <label><span><b><%= vset.variant_detail.trans.title %>:</b></span> <%= vset.option_data.trans.title %></label>
                                    <% } %>
                                <% }); %>
                            </div>
                            <div class="col-md-2 text-md-center order-1 mb-1 mb-md-0">
                                <div class="items-price">{{Session::get('currencySymbol')}} <%= vendor_product.pvariant.price %></div>
                            </div>
                            <div class="col-8 col-md-4 text-md-center order-3 order-md-2">
                                <div class="number d-flex justify-content-md-center">
                                    <div class="counter-container d-flex align-items-center">
                                        <span class="minus qty-minus" data-id="<%= vendor_product.id %>" data-base_price=" <%= vendor_product.pvariant.price %>" data-vendor_id="<%= vendor_product.vendor_id %>">
                                            <i class="fa fa-minus" aria-hidden="true"></i>
                                        </span>
                                        <input placeholder="1" type="text" value="<%= vendor_product.quantity %>" class="input-number" step="0.01" id="quantity_<%= vendor_product.id %>" readonly>
                                        <span class="plus qty-plus" data-id="<%= vendor_product.id %>" data-base_price=" <%= vendor_product.pvariant.price %>">
                                            <i class="fa fa-plus" aria-hidden="true"></i>
                                        </span>
                                    </div>
                                </div>
                                <% if(cart_details.pharmacy_check == 1){ %>
                                    <% if(vendor_product.product.pharmacy_check == 1){ %>
                                        <button type="button" class="btn btn-solid prescription_btn mt-2" data-product="<%= vendor_product.product.id %>" data-vendor_id="<%= vendor_product.vendor_id %>">Add Prescription</button>
                                    <% } %>
                                <% } %>
                            </div>
                            <div class="col-md-1 text-right text-md-center p-in order-2 order-md-3">
                                <a class="action-icon d-block remove_product_via_cart" data-product="<%= vendor_product.id %>" data-vendor_id="<%= vendor_product.vendor_id %>">
                                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                                </a>
                            </div>
                            <div class="col-4 col-md-2 text-right order-4">
                                <div class="items-price">{{Session::get('currencySymbol')}} <%= vendor_product.pvariant.quantity_price %></div>
                            </div>
                        </div>
                        <% if(vendor_product.addon.length != 0) { %>
                            <hr class="my-2">
                            <div class="row align-items-md-center">
                                <div class="col-12">
                                    <h6 class="m-0 pl-0"><b>{{__('Add Ons')}}</b></h6>
                                </div>
                            </div>
                            <% _.each(vendor_product.addon, function(addon, ad){%>
                            <div class="row">
                                <div class="col-md-3 col-sm-4 items-details text-left">
                                    <p class="p-0 m-0"><%= addon.option.title %></p>
                                </div>
                                <div class="col-md-2 col-sm-4 text-center">
                                    <div class="extra-items-price">{{Session::get('currencySymbol')}} <%= addon.option.price_in_cart %></div>
                                </div>
                                <div class="col-md-7 col-sm-4 text-right">
                                    <div class="extra-items-price">{{Session::get('currencySymbol')}} <%= addon.option.quantity_price %></div>
                                </div>
                            </div>
                            <% }); %>
                        <% } %>
                    </div>
                </div>

                <hr>
            <% }); %>
            <div class="row">
                <div class="col-lg-6 mb-3 mb-lg-0 d-flex align-items-start">
                    @if(!$guest_user)
                        <div class="coupon_box w-100">
                            <img src="{{ asset('assets/images/discount_icon.svg') }}">
                            <label class="mb-0 ml-2">
                                <% if(product.coupon) { %>
                                    <%= product.coupon.promo.name %>
                                <% }else{ %>
                                    <a href="javascript:void(0)" class="promo_code_list_btn ml-1" data-vendor_id="<%= product.vendor.id %>" data-cart_id="<%= cart_details.id %>" data-amount="<%= product.product_total_amount %>">{{__('Select a promo code')}}</a>
                                <% } %>
                            </label>
                        </div>
                        <% if(product.coupon) { %>
                            <label class="p-1 m-0"><a href="javascript:void(0)" class="remove_promo_code_btn ml-1" data-coupon_id="<%= product.coupon ? product.coupon.promo.id : '' %>" data-cart_id="<%= cart_details.id %>">Remove</a></label>
                        <% } %>
                    @endif
                </div>
                <div class="col-lg-6">
                    <div class="row">

                        <div class="col-8 text-lg-right">
                            <% if(product.coupon_amount_used > 0) { %>
                                <p class="total_amt m-0">{{__('Coupon Discount')}} :</p>
                            <% } %>
                            <p class="total_amt m-0">{{__('Delivery Fee')}} :</p>
                        </div>
                        <div class="col-4 text-right">
                            <% if(product.coupon_amount_used > 0) { %>
                                <p class="total_amt m-0">{{Session::get('currencySymbol')}} <%= product.coupon_amount_used %></p>
                            <% } %>
                            <p class="total_amt mb-1 <% if(product.delivery_fee_charges > 0) { %>{{ ((in_array(1, $subscription_features)) ) ? 'discard_price' : '' }}<% } %>">{{Session::get('currencySymbol')}} <%= product.delivery_fee_charges %></p>
                            <p class="total_amt m-0">{{Session::get('currencySymbol')}} <%= product.product_total_amount %></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr>
    <% }); %>
    <div class="row">
        <div class="col-lg-5 col-xl-6"></div>
        <div class="col-lg-7 col-xl-6">
            <div class="row">
                <div class="col-6">{{__('Sub Total')}}</div>
                <div class="col-6 text-right">{{Session::get('currencySymbol')}} <%= cart_details.gross_amount %></div>
            </div>
            <hr class="my-2">
            <div class="row">
                <div class="col-6">{{__('Tax')}}</div>
                <div class="col-6 text-right">{{Session::get('currencySymbol')}} <%= cart_details.total_taxable_amount %></div>
            </div>
            <hr class="my-2">
            <% if(cart_details.total_subscription_discount != undefined) { %>
                <div class="row">
                    <div class="col-6">{{__('Subscription Discount')}}</div>
                    <div class="col-6 text-right">{{Session::get('currencySymbol')}} <%= cart_details.total_subscription_discount %></div>
                </div>
                <hr class="my-2">
            <% } %>
            <% if(cart_details.loyalty_amount > 0) { %>
                <div class="row">
                    <div class="col-6">{{__('Loyalty Amount')}}</div>
                    <div class="col-6 text-right">{{Session::get('currencySymbol')}} <%= cart_details.loyalty_amount %></div>
                </div>
                <hr class="my-2">
            <% } %>
            <% if(cart_details.wallet_amount_used != undefined) { %>
                <div class="row">
                    <div class="col-6">{{__('Wallet Amount')}}</div>
                    <div class="col-6 text-right">{{Session::get('currencySymbol')}} <%= cart_details.wallet_amount_used %></div>
                </div>
                <hr class="my-2">
            <% } %>

            <% if(client_preference_detail.tip_before_order == 1) { %>
            <div class="row">
                <div class="col-12">
                    <div class="mb-2">{{__('Do you want to give a tip?')}}</div>
                    <div class="tip_radio_controls">
                        <% if(cart_details.total_payable_amount > 0) { %>
                            <input type="radio" class="tip_radio" id="control_01" name="select" value="<%= cart_details.tip_5_percent %>">
                            <label class="tip_label" for="control_01">
                                <h5 class="m-0" id="tip_5">{{Session::get('currencySymbol')}} <%= cart_details.tip_5_percent %></h5>
                                <p class="m-0">5%</p>
                            </label>

                            <input type="radio" class="tip_radio" id="control_02" name="select" value="<%= cart_details.tip_10_percent %>" >
                            <label class="tip_label" for="control_02">
                                <h5 class="m-0" id="tip_10">{{Session::get('currencySymbol')}} <%= cart_details.tip_10_percent %></h5>
                                <p class="m-0">10%</p>
                            </label>

                            <input type="radio" class="tip_radio" id="control_03" name="select" value="<%= cart_details.tip_15_percent %>" >
                            <label class="tip_label" for="control_03">
                                <h5 class="m-0" id="tip_15">{{Session::get('currencySymbol')}} <%= cart_details.tip_15_percent %></h5>
                                <p class="m-0">15%</p>
                            </label>

                            <input type="radio" class="tip_radio" id="custom_control" name="select" value="custom" >
                            <label class="tip_label" for="custom_control">
                                <h5 class="m-0">{{__('Custom')}}<br>{{__('Amount')}}</h5>
                            </label>
                        <% } %>
                    </div>
                    <div class="custom_tip mb-1 <% if(cart_details.total_payable_amount > 0) { %> d-none <% } %>">
                        <input class="input-number form-control" name="custom_tip_amount" id="custom_tip_amount" placeholder="Enter Custom Amount" type="number" value="" step="0.1">
                    </div>
                </div>
            </div>
            <hr class="my-2">

            <% } %>


            <div class="row">
                <div class="col-6">
                    <p class="total_amt m-0">{{__('Amount Payable')}}</p>
                </div>
                <div class="col-6 text-right">
                    <p class="total_amt m-0" id="cart_total_payable_amount" data-cart_id="<%= cart_details.id %>">{{Session::get('currencySymbol')}} <%= cart_details.total_payable_amount %></p>
                    <div>
                        <input type="hidden" name="cart_tip_amount" id="cart_tip_amount" value="0">
                        <input type="hidden" name="cart_total_payable_amount" value="<%= cart_details.total_payable_amount %>">
                        <input type="hidden" name="cart_payable_amount_original" id="cart_payable_amount_original" data-curr="{{Session::get('currencySymbol')}}" value="<%= cart_details.total_payable_amount %>">
                    </div>
                </div>
            </div>
            <hr class="my-2">
            <% if(client_preference_detail.off_scheduling_at_cart != 1) { %>
            <div class="row d-flex align-items-center arabic-lng no-gutters mt-2 mb-4" id="dateredio">
                <div class="col-md-5 pr-md-2 mb-2 mb-md-0">
                    <div class="login-form">
                        <ul class="list-inline">
                            <li class="d-inline-block mr-1 " id="tasknow_li">
                                <input type="radio" class="custom-control-input check" id="tasknow" name="task_type" value="now" <%= ((cart_details.schedule_type == 'now' || cart_details.schedule_type == '' || cart_details.schedule_type == null) ? 'checked' : '') %> >
                                <label class="btn btn-solid btn-disabled" for="tasknow" id="tasknow_label">Now</label>
                            </li>
                            <li class="d-inline-block">
                                <input type="radio" class="custom-control-input check" id="taskschedule" name="task_type" value="schedule" <%= ((cart_details.schedule_type == 'schedule') ? 'checked' : '') %> >
                                <label class="btn btn-solid" for="taskschedule" id="taskschedule_label" >Schedule</label>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-7 datenow align-items-center justify-content-between" id="schedule_div" style="<%= ((cart_details.schedule_type == 'now' || cart_details.schedule_type == '' || cart_details.schedule_type == null) ? 'display:none!important' : '') %>">
                        <input type="datetime-local" id="schedule_datetime" class="form-control" placeholder="Inline calendar" value="<%= ((cart_details.schedule_type == 'schedule') ? cart_details.scheduled_date_time : '') %>" min="{{ $now }}" max="{{$mixum}}">
                    <!-- <button type="button" class="btn btn-solid"><i class="fa fa-check" aria-hidden="true"></i></button> -->
                </div>
            </div>
            <% } %>

        </div>
    </div>
</script>

<script type="text/template" id="promo_code_template">
    <% _.each(promo_codes, function(promo_code, key){%>
        <div class="col-lg-6 mt-3">
            <div class="coupon-code mt-0">
                <div class="p-2">
                    <img src="<%= promo_code.image.proxy_url %>100/35<%= promo_code.image.image_path %>" alt="">
                    <h6 class="mt-0"><%= promo_code.title %></h6>
                </div>
                <hr class="m-0">
                <div class="code-outer p-2 text-uppercase d-flex align-items-center justify-content-between">
                    <label class="m-0"><%= promo_code.name %></label>
                    <a class="btn btn-solid apply_promo_code_btn" data-vendor_id="<%= vendor_id %>" data-cart_id="<%= cart_id %>" data-coupon_id="<%= promo_code.id %>" data-amount="<%= amount %>" style="cursor: pointer;">{{__('Apply')}}</a>
                </div>
                <hr class="m-0">
                <div class="offer-text p-2">
                    <p class="m-0"><%= promo_code.short_desc %></p>
                </div>
            </div>
        </div>
    <% }); %>
</script>

<script type="text/template" id="no_promo_code_template">
    <div class="col-12 no-more-coupon text-center">
        <p>{{__('No Other Coupons Available.')}}</p>
    </div>
</script>
<div id="cart_main_page">
    <div class="container">
        @if($cartData)
        <form method="post" action="" id="placeorder_form">
            @csrf
            <div class="card-box">
                <div class="row d-flex justify-space-around">
                    @if(!$guest_user)
                    <div class="col-lg-4 left_box">

                    </div>
                    @endif
                    <div class="{{ $guest_user ? 'col-md-12' : 'col-lg-8' }}">
                        <div class="spinner-box">
                            <div class="circle-border">
                                <div class="circle-core"></div>
                            </div>
                        </div>

                        <div class="cart-page-layout" id="cart_table"></div>

                    </div>
                </div>

                <div class="row mb-md-3">
                    <div class="col-6 mb-2 mb-sm-0">
                        <a class="btn btn-solid" href="{{ url('/') }}">{{__('Continue Shopping')}}</a>
                    </div>
                    <div class="col-6 text-right">
                        <button id="order_placed_btn" class="btn btn-solid d-none" type="button" {{$addresses->count() == 0 ? 'disabled': ''}}>{{__('Place Order')}}</button>
                    </div>
                </div>
            </div>

        </form>
        @else
        <div class="row mt-2 mb-4 mb-lg-5">
            <div class="col-12 text-center">
                <div class="cart_img_outer">
                    <img src="{{asset('front-assets/images/empty_cart.png')}}">
                </div>
                <h3>{{__('Your Cart Is Empty!')}}</h3>
                <p>{{__('Add items to it now.')}}</p>
                <a class="btn btn-solid" href="{{url('/')}}">{{__('Continue Shopping')}}</a>
            </div>
        </div>
        @endif
    </div>

    <div class="other_cart_products"></div>

</div>
<script type="text/template" id="other_cart_products_template">
    <div class="container mt-3 mb-5">
        <% if(cart_details.upSell_products != ''){ %>
            <h3 class="mb-2 mt-4">{{__('Frequently bought together')}}</h3>
            <div class="row">
                <div class="col-12 p-0">
                    <div class="product-4 product-m no-arrow">
                        <% _.each(cart_details.upSell_products, function(product, key){%>

                            <a class="common-product-box scale-effect text-center" href="{{route('productDetail')}}/<%= product.url_slug %>">
                                <div class="img-outer-box position-relative">
                                    <img src="<%= product.product_media.image.path.proxy_url %>600/800<%= product.product_media.image.path.image_path %>" alt="">
                                </div>
                                <div class="media-body align-self-center">
                                    <div class="inner_spacing px-0">
                                        <div class="product-description">
                                            <h3 class="m-0"><%= product.translation_title %></h3>
                                            <p><%= product.vendor_name %></p>
                                            <p class="border-bottom pb-1">In <%= product.category_name %></p>
                                            <div class="d-flex align-items-center justify-content-between">
                                                <b><% if(product.inquiry_only == 0) { %>
                                                    {{ Session::get('currencySymbol') }}<%= product.variant_price %>
                                                <% } %></b>

                                                @if($client_preference_detail)
                                                    @if($client_preference_detail->rating_check == 1)
                                                        <% if(product.averageRating > 0){%>
                                                            <div class="rating-box">
                                                                <i class="fa fa-star" aria-hidden="true"></i>
                                                                <span><%= product.averageRating %></span>
                                                            </div>
                                                        <% } %>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>

                        <% }); %>
                    </div>
                </div>
            </div>
        <% } %>

        <% if(cart_details.crossSell_products != ''){ %>
            <h3 class="mb-2 mt-3">{{__('You might be interested in')}}</h3>
            <div class="row">
                <div class="col-12 p-0">
                    <div class="product-4 product-m no-arrow">
                        <% _.each(cart_details.crossSell_products, function(product, key){%>

                            <a class="common-product-box scale-effect text-center" href="{{route('productDetail')}}/<%= product.url_slug %>">
                                <div class="img-outer-box position-relative">
                                    <img src="<%= product.product_media.image.path.proxy_url %>600/800<%= product.product_media.image.path.image_path %>" alt="">
                                </div>
                                <div class="media-body align-self-center">
                                    <div class="inner_spacing px-0">
                                        <div class="product-description">
                                            <h3 class="m-0"><%= product.translation_title %></h3>
                                            <p><%= product.vendor_name %></p>
                                            <p class="border-bottom pb-1">In <%= product.category_name %></p>
                                            <div class="d-flex align-items-center justify-content-between">
                                                <b><% if(product.inquiry_only == 0) { %>
                                                    {{ Session::get('currencySymbol') }}<%= product.variant_price %>
                                                <% } %></b>

                                                @if($client_preference_detail)
                                                    @if($client_preference_detail->rating_check == 1)
                                                        <% if(product.averageRating > 0){%>
                                                            <div class="rating-box">
                                                                <i class="fa fa-star" aria-hidden="true"></i>
                                                                <span><%= product.averageRating %></span>
                                                            </div>
                                                        <% } %>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>

                        <% }); %>
                    </div>
                </div>
            </div>
        <% } %>
    </div>
</script>
<div class="modal fade refferal_modal" id="refferal-modal" tabindex="-1" aria-labelledby="refferal-modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h5 class="modal-title" id="refferal-modalLabel">{{__('Apply Coupon Code')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body mt-0 pb-0 pt-4">
                <div class="row validate_promo_div">
                    <div class="col-md-4">
                        <div class="form-group" >
                            <input class="form-control manual_promocode_input" name="name" type="text" placeholder="{{ __('Enter a promocode')}}" >
                            <button class="btn btn-solid apply_promo_code_btn" data-vendor_id="" data-cart_id=""
                            data-coupon_id="" data-amount="" style="display:none">Apply</button>
                            <span class="invalid-feedback manual_promocode" role="alert">

                            </span>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-solid validate_promo_code_btn" data-vendor_id="" data-cart_id=""
                            data-coupon_id="" data-amount="" style="cursor: pointer;">Apply</button>
                    </div>
                </div>
                <div class="coupon-box">
                    <div class="row" id="promo_code_list_main_div">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade remove-item-modal" id="remove_item_modal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="remove_itemLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header pb-0">
                <h5 class="modal-title" id="remove_itemLabel">{{__('Remove Item')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="vendor_id" value="">
                <input type="hidden" id="cartproduct_id" value="">
                <h6 class="m-0">{{__('Are You Sure You Want To Remove This Item?')}}</h6>
            </div>
            <div class="modal-footer flex-nowrap justify-content-center align-items-center">
                <button type="button" class="btn btn-solid black-btn" data-dismiss="modal">{{__('Cancel')}}</button>
                <button type="button" class="btn btn-solid" id="remove_product_button">{{__('Remove')}}</button>
            </div>
        </div>
    </div>
</div>
<script type="text/template" id="payment_method_template">
    <% _.each(payment_options, function(payment_option, k){%>
        <a class="nav-link <%= payment_option.slug == 'cash_on_delivery' ? 'active': ''%>" id="v-pills-<%= payment_option.slug %>-tab" data-toggle="pill" href="#v-pills-<%= payment_option.slug %>" role="tab" aria-controls="v-pills-wallet" aria-selected="true" data-payment_option_id="<%= payment_option.id %>"><%= payment_option.title %></a>
    <% }); %>
</script>
<script type="text/template" id="payment_method_tab_pane_template">
    <% if(payment_options == '') { %>
        <h6>{{__('Payment Options Not Avaialable')}}</h6>
    <% }else{ %>
        <div class="modal-body pb-0">
            <div class="payment_response">
                <div class="alert p-0 m-0" role="alert"></div>
            </div>
            <h5 class="text-17 mb-2">{{__('Debit From')}}</h5>
            <form method="POST" id="cart_payment_form">
                @csrf
                @method('POST')
                <% _.each(payment_options, function(payment_option, k){%>
                    <div class="" id="" role="tabpanel">
                        <label class="radio mt-2">
                            <%= payment_option.title %>
                            <input type="radio" name="cart_payment_method" id="radio-<%= payment_option.slug %>" value="<%= payment_option.id %>" data-payment_option_id="<%= payment_option.id %>">
                            <span class="checkround"></span>
                        </label>
                        <% if(payment_option.slug == 'stripe') { %>
                            <div class="col-md-12 mt-3 mb-3 stripe_element_wrapper d-none">
                                <div class="form-control">
                                    <label class="d-flex flex-row pt-1 pb-1 mb-0">
                                        <div id="stripe-card-element"></div>
                                    </label>
                                </div>
                                <span class="error text-danger" id="stripe_card_error"></span>
                            </div>
                        <% } %>
                        <% if(payment_option.slug == 'yoco') { %>
                            <div class="col-md-12 mt-3 mb-3 yoco_element_wrapper d-none">
                                <div class="form-control">
                                    <div id="yoco-card-frame">
                                    <!-- Yoco Inline form will be added here -->
                                    </div>
                                </div>
                                <span class="error text-danger" id="yoco_card_error"></span>
                            </div>
                        <% } %>
                    </div>
                <% }); %>
            </form>
        </div>
        <div class="modal-footer d-block text-center">
            <div class="row">
                <div class="col-sm-12 p-0 d-flex flex-fill">
                    <button type="button" class="btn btn-solid ml-1 proceed_to_pay">{{__('Place Order')}}</button>
                </div>
            </div>
        </div>
    <% } %>
</script>

<div class="modal fade" id="proceed_to_pay_modal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="pay-billLabel">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h5 class="modal-title" id="pay-billLabel">{{__('Total Amount')}}: <span id="total_amt"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="v_pills_tabContent"></div>
        </div>
    </div>
</div>
<!-- <script type="text/template" id="payment_method_tab_pane_template">
    <% _.each(payment_options, function(payment_option, k){%>
        <div class="tab-pane fade <%= payment_option.slug == 'cash_on_delivery' ? 'active show': ''%>" id="v-pills-<%= payment_option.slug %>" role="tabpanel" aria-labelledby="v-pills-<%= payment_option.slug %>-tab">
            <form method="POST" id="<%= payment_option.slug %>-payment-form">
            @csrf
            @method('POST')
                <div class="payment_response mb-3">
                    <div class="alert p-0" role="alert"></div>
                </div>
                <div class="form_fields">
                    <div class="row">
                        <div class="col-md-12">
                            <% if(payment_option.slug == 'stripe') { %>
                                <div class="form-control">
                                    <label class="d-flex flex-row pt-1 pb-1 mb-0">
                                        <div id="stripe-card-element"></div>
                                    </label>
                                </div>
                                <span class="error text-danger" id="stripe_card_error"></span>
                            <% } %>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12 text-md-right">
                            <button type="button" class="btn btn-solid" data-dismiss="modal">{{ __('Cancel') }}</button>
                            <button type="button" class="btn btn-solid ml-1 proceed_to_pay">{{__('Place Order')}}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    <% }); %>
</script>
<div class="modal fade" id="proceed_to_pay_modal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="pay-billLabel">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body p-0">
                <div class="row no-gutters">
                    <div class="col-4">
                        <div class="nav flex-column nav-pills" id="v_pills_tab" role="tablist" aria-orientation="vertical"></div>
                    </div>
                    <div class="col-8">
                        <div class="tab-content-box px-3">
                            <div class="d-flex align-items-center justify-content-between pt-3">
                                <h5 class="modal-title" id="pay-billLabel">{{__('Total Amount')}}: <span id="total_amt"></span></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class="tab-content h-100" id="v_pills_tabContent">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> -->

<!-- Modal -->
<div class="modal fade login-modal" id="login_modal" tabindex="-1" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <form id="login-form-new" action="">
                    @csrf
                    <input type="hidden" name="device_type" value="web">
                    <input type="hidden" name="device_token" value="web">
                    <input type="hidden" id="dialCode" name="dialCode" value="{{ old('dialCode') ? old('dialCode') : Session::get('default_country_phonecode','1') }}">
                    <input type="hidden" id="countryData" name="countryData" value="{{ strtolower(Session::get('default_country_code','US')) }}">

                    <div class="login-with-username">
                        <div class="modal-header px-0 pt-0">
                            <h5 class="modal-title">{{ __('Log in') }}</h5>
                            <button type="button" class="close m-0 p-0" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" id="username" placeholder="{{ __('Email or Phone Number') }}" required="" name="username" value="{{ old('username')}}">
                        </div>
                        <div class="form-group" id="password-wrapper" style="display:none; position:relative">
                            <input type="password" class="form-control pr-3" name="password" placeholder="{{ __('Password') }}">
                            <a class="font-14" href="javascript:void(0)" id="send_password_reset_link" style="position:absolute; right:10px; top:7px;">Forgot?</a>
                        </div>
                        <div class="form-group">
                            <span id="error-msg" class="font-14 text-danger" style="display:none"></span>
                            <span id="success-msg" class="font-14 text-success" style="display:none"></span>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-solid w-100 login_continue_btn" type="button">Continue</button>
                        </div>

                        <div class="divider-line"><span>or</span></div>
                        {{-- <button class="login-button email-btn">
                        <i class="fa fa-envelope" aria-hidden="true"></i>
                        <span>Continue with Email</span>
                    </button> --}}

                        @if(session('preferences'))
                        @if(session('preferences')->fb_login == 1 || session('preferences')->twitter_login == 1 ||
                        session('preferences')->google_login == 1 || session('preferences')->apple_login == 1)
                        @if(session('preferences')->google_login == 1)
                        <a class="login-button" href="{{url('auth/google')}}">
                            <i class="fa fa-google" aria-hidden="true"></i>
                            <span>Continue with gmail</span>
                        </a>
                        @endif
                        @if(session('preferences')->fb_login == 1)
                        <a class="login-button" href="{{url('auth/facebook')}}">
                            <i class="fa fa-facebook" aria-hidden="true"></i>
                            <span>Continue with facebook</span>
                        </a>
                        @endif
                        @if(session('preferences')->twitter_login)
                        <a class="login-button" href="{{url('auth/twitter')}}">
                            <i class="fa fa-twitter" aria-hidden="true"></i>
                            <span>Continue with twitter</span>
                        </a>
                        @endif
                        @if(session('preferences')->apple_login == 1)
                        <a class="login-button" href="javascript::void(0);">
                            <i class="fa fa-apple" aria-hidden="true"></i>
                            <span>Continue with apple</span>
                        </a>
                        @endif
                        @endif
                        @endif

                        <div class="divider-line mb-2"></div>
                        <p class="new-user mb-0">New to Royo? <a href="{{route('customer.register')}}">Create an
                                account</a></p>
                    </div>
                    {{-- <div class="login-with-mail">
                  <div class="modal-header px-0 pt-0">
                      <button type="button" class="close m-0 p-0 back-login">
                          <i class="fa fa-arrow-left" aria-hidden="true"></i>
                      </button>
                      <h5 class="modal-title">Log in</h5>
                      <button type="button" class="close m-0 p-0" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                      </button>
                  </div>
                  <form id="email-login-form" action="">
                      <div class="mail-icon text-center">
                          <img alt="image" src="https://b.zmtcdn.com/Zwebmolecules/73b3ee9d469601551f2a0952581510831595917292.png" class="img-fluid">
                      </div>
                      <div class="form-group">
                          <input class="from-control" type="text" placeholder="Email">
                          <a class="forgot_btn font-14" href="{{url('user/forgotPassword')}}">{{ __('Forgot Password?') }}</a>
            </div>
            <div class="form-group">
                <button class="btn btn-solid w-100" type="submit">Login</button>
            </div>
            </form>
        </div> --}}
        <div class="verify-login-code" style="display:none">
            <div class="modal-header px-0 pt-0">
                <button type="button" class="close m-0 p-0 back-login">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i>
                </button>
                <h5 class="modal-title">Verify OTP</h5>
                <button type="button" class="close m-0 p-0" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div method="get" class="digit-group otp_inputs d-flex justify-content-between" data-group-name="digits" data-autosubmit="false" autocomplete="off">
                <input class="form-control" type="text" id="digit-1" name="digit-1" data-next="digit-2" onkeypress="return isNumberKey(event)" />
                <input class="form-control" type="text" id="digit-2" name="digit-2" data-next="digit-3" data-previous="digit-1" onkeypress="return isNumberKey(event)" />
                <input class="form-control" type="text" id="digit-3" name="digit-3" data-next="digit-4" data-previous="digit-2" onkeypress="return isNumberKey(event)" />
                <input class="form-control" type="text" id="digit-4" name="digit-4" data-next="digit-5" data-previous="digit-3" onkeypress="return isNumberKey(event)" />
                <input class="form-control" type="text" id="digit-5" name="digit-5" data-next="digit-6" data-previous="digit-4" onkeypress="return isNumberKey(event)" />
                <input class="form-control" type="text" id="digit-6" name="digit-6" data-next="digit-7" data-previous="digit-5" onkeypress="return isNumberKey(event)" />
            </div>
            <span class="invalid_phone_otp_error invalid-feedback2 w-100 d-block text-center text-danger"></span>
            <span id="phone_otp_success_msg" class="font-14 text-success text-center w-100 d-block" style="display:none"></span>
            <div class="row text-center mt-2">
                <div class="col-12 resend_txt">
                    <p class="mb-1">{{__('If you didn’t receive a code?')}}</p>
                    <a class="verifyPhone" href="javascript:void(0)"><u>{{__('RESEND')}}</u></a>
                </div>
                <div class="col-md-12 mt-3">
                    <button type="button" class="btn btn-solid" id="verify_phone_token">{{__('VERIFY')}}</button>
                </div>
            </div>
        </div>
        </form>
    </div>
</div>
</div>
</div>

<div id="prescription_form" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h4 class="modal-title">{{__('Add Prescription')}}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form id="save_prescription_form" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-body" id="AddCardBox">
                    <div class="row">
                        <div class="col-sm-6" id="imageInput">
                            <input type="hidden" id="vendor_idd" name="vendor_idd" value="" />
                            <input type="hidden" id="product_id" name="product_id" value="" />
                            <input data-default-file="" accept="image/*" type="file" data-plugins="dropify" name="prescriptions[]" class="dropify" multiple />
                            <p class="text-muted text-center mt-2 mb-0">{{__('Upload Prescription')}}</p>
                            <span class="invalid-feedback" role="alert">
                                <strong></strong>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info waves-effect waves-light submitPrescriptionForm">{{__('Submit')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade pick-address" id="pick_address" tabindex="-1" aria-labelledby="pick-addressLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog  modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h5 class="modal-title" id="pick-addressLabel">{{ __('Select Location') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0">
                <div class="row">
                    <div class="col-md-12">
                        <div id="address-map-container" class="w-100" style="height: 500px; min-width: 500px;">
                            <div id="pick-address-map" class="h-100"></div>
                        </div>
                        <div class="pick_address p-2 mb-2 position-relative">
                            <div class="text-center">
                                <button type="button" class="btn btn-solid ml-auto pick_address_confirm w-100" data-dismiss="modal">{{ __('Ok') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<?php ?>
@endsection

@section('script')
<script src="https://cdn.socket.io/4.1.2/socket.io.min.js" integrity="sha384-toS6mmwu70G0fw54EGlWWeA4z3dyJ+dlXBtSURSKN4vyRFOcxd3Bzjj/AoOwY+Rg" crossorigin="anonymous">
</script>
<script src="https://js.yoco.com/sdk/v1/yoco-sdk-web.js"></script>
<script src="https://js.stripe.com/v3/"></script>

<script src="{{asset('assets/js/intlTelInput.js')}}"></script>
<script>
    // Replace the supplied `publicKey` with your own.
    // Ensure that in production you use a production public_key.
    var yoco_public_key="<?php echo $public_key_yoco; ?>";
    console.log(yoco_public_key);
    var sdk = new window.YocoSDK({
        publicKey: yoco_public_key
    });
    var inline='';



</script>
<script type="text/javascript">
   var guest_cart = {{ $guest_user ? 1 : 0 }};
    var base_url = "{{url('/')}}";
    var place_order_url = "{{route('user.placeorder')}}";
    var payment_stripe_url = "{{route('payment.stripe')}}";
    var user_store_address_url = "{{route('address.store')}}";
    var promo_code_remove_url = "{{ route('remove.promocode') }}";
    var payment_paypal_url = "{{route('payment.paypalPurchase')}}";
    var payment_paystack_url = "{{route('payment.paystackPurchase')}}";
    var payment_success_paystack_url = "{{route('payment.paystackCompletePurchase')}}";
    var payment_payfast_url = "{{route('payment.payfastPurchase')}}";
    var payment_mobbex_url = "{{route('payment.mobbexPurchase')}}";
    var payment_yoco_url = "{{route('payment.yocoPurchase')}}";
    var payment_paylink_url = "{{route('payment.paylinkPurchase')}}";
    var update_qty_url = "{{ url('product/updateCartQuantity') }}";
    var promocode_list_url = "{{ route('verify.promocode.list') }}";
    var payment_option_list_url = "{{route('payment.option.list')}}";
    var apply_promocode_coupon_url = "{{ route('verify.promocode') }}";
    var payment_success_paypal_url = "{{route('payment.paypalCompletePurchase')}}";
    var update_cart_schedule = "{{route('cart.updateSchedule')}}";
    var login_via_username_url = "{{route('customer.loginViaUsername')}}";
    var forgot_password_url = "{{route('customer.forgotPass')}}";
    var order_success_return_url = "{{route('order.return.success')}}";
    var my_orders_url = "{{route('user.orders')}}";
    var validate_promocode_coupon_url = "{{ route('verify.promocode.validate_code') }}";

    var latitude = "{{ session()->has('latitude') ? session()->get('latitude') : 0 }}";
    var longitude = "{{ session()->has('longitude') ? session()->get('longitude') : 0 }}";

    if(!latitude){
        @if(!empty($client_preference_detail->Default_latitude))
            latitude = "{{$client_preference_detail->Default_latitude}}";
        @endif
    }

    if(!longitude){
        @if(!empty($client_preference_detail->Default_longitude))
            longitude = "{{$client_preference_detail->Default_longitude}}";
        @endif
    }

    $(document).on('click', '.showMapHeader', function() {
        var lats = document.getElementById('latitude').value;
        var lngs = document.getElementById('longitude').value;
         if(lats==''){
            lats=latitude;
        }
        if(lngs==''){
            lngs=longitude;
        }
        var infowindow = new google.maps.InfoWindow();
        var geocoder = new google.maps.Geocoder();
        var myLatlng = new google.maps.LatLng(lats, lngs);
        var mapProp = {
            center: myLatlng,
            zoom: 13,
            mapTypeId: google.maps.MapTypeId.ROADMAP

        };
        var map = new google.maps.Map(document.getElementById("pick-address-map"), mapProp);
        var marker = new google.maps.Marker({
            position: myLatlng,
            map: map,
            draggable: true
        });
        // marker drag event

         google.maps.event.addListener(marker, 'dragend', function() {
            geocoder.geocode({
            'latLng': marker.getPosition()
            }, function(results, status) {

            if (status == google.maps.GeocoderStatus.OK) {
                if (results[0]) {
                        document.getElementById('latitude').value = marker.getPosition().lat();
                        document.getElementById('longitude').value = marker.getPosition().lng();
                        document.getElementById('address').value= results[0].formatted_address;

                    infowindow.setContent(results[0].formatted_address);

                    infowindow.open(map, marker);
                }
            }
            });
        });
        {{-- google.maps.event.addListener(marker, 'drag', function(event) {
            document.getElementById('latitude').value = event.latLng.lat();
            document.getElementById('longitude').value = event.latLng.lng();
        });
        //marker drag event end
        google.maps.event.addListener(marker, 'dragend', function(event) {
            document.getElementById('latitude').value = event.latLng.lat();
            document.getElementById('longitude').value = event.latLng.lng();
        }); --}}
        $('#pick_address').modal('show');
    });

    $(document).delegate("#vendor_table", "change", function() {
        var table = $(this).val();
        var vendor = $(this).attr('data-id');
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: "{{ route('addVendorTableToCart') }}",
            data: {
                table: table,
                vendor: vendor
            },
            success: function(response) {
                if (response.status == "Success") {} else {
                    alert(response.message);
                }
            },
            error: function(error) {
                var response = $.parseJSON(error.responseText);
                success_error_alert('error', response.message, ".payment_response");
            }
        });
    });

    $(document).delegate('#cart_payment_form input[name="cart_payment_method"]', 'change', function() {
        var method = $(this).attr('id');
        if (method.replace('radio-', '') == 'stripe') {
            $("#cart_payment_form .stripe_element_wrapper").removeClass('d-none');
        } else {
            $("#cart_payment_form .stripe_element_wrapper").addClass('d-none');
        }
        if (method.replace('radio-', '') == 'yoco') {
            $("#cart_payment_form .yoco_element_wrapper").removeClass('d-none');
            // Create a new dropin form instance

            var yoco_amount_payable = $("input[name='cart_total_payable_amount']").val();
        console.log( yoco_amount_payable*100);
            inline = sdk.inline({
                layout: 'field',
                amountInCents:  yoco_amount_payable*100,
                currency: 'ZAR'
            });
            // this ID matches the id of the element we created earlier.
            inline.mount('#yoco-card-frame');
        } else {
            $("#cart_payment_form .yoco_element_wrapper").addClass('d-none');
        }
    });

    $(document).delegate('#login_modal', 'shown.bs.modal', function() {
        $('.login-with-mail').hide();
        $('.verify-login-code').hide();
    });

    $('.email-btn').click(function() {
        $('.login-with-mail').show();
        $('.login-with-username').hide();
    });
    $('.back-login').click(function() {
        $('.login-with-mail').hide();
        $('.verify-login-code').hide();
        $('.login-with-username').show();
    });

    var reset = function() {
        var input = document.querySelector("#username"),
            errorMsg = document.querySelector("#error-msg");
        input.classList.remove("is-invalid");
        errorMsg.innerHTML = "";
        errorMsg.style.display = 'none';
        $("#password-wrapper").hide();
        $("#password-wrapper input").removeAttr("required");
        $("#password-wrapper input").val('');
    };

    // here, the index maps to the error code returned from getValidationError - see readme
    var errorMap = ["Invalid phone number", "Invalid country code", "Phone number too short", "Phone number too long",
        "Invalid phone number"
    ];

    var iti = '';
    var phn_filter = /^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\./0-9]*$/;
    var email_filter =
        /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

    $(document).delegate('input[name="password"]', 'input', function() {
        $(this).parent('#password-wrapper').show();
        $("#error-msg").hide();
    });

    $(document).delegate("#username", "input", function(e) {
        var uname = $.trim($(this).val());
        if (phn_filter.test(uname)) {
            // get country flags when input is a number
            assignPhoneInput();
            $("#password-wrapper").hide();
            $("#password-wrapper input").removeAttr("required");
            $("#password-wrapper input").val('');
        } else {
            // destroy country flags when input is a string
            if (iti != '') {
                iti.destroy();
                iti = '';
                $(this).css('padding-left', '6px');
            }
        }
        $(this).focus();
        $(this).removeClass("is-invalid");
        $("#error-msg").hide();
    });

    function assignPhoneInput() {
        var input = document.querySelector("#username");
        var country = $('#countryData').val();
        if (iti != '') {
            iti.destroy();
            iti = '';
        }
        iti = intlTelInput(input, {
            initialCountry: country,
            separateDialCode: true,
            hiddenInput: "full_number",
            utilsScript: "{{asset('assets/js/utils.js')}}",
        });
        $("input[name='full_number']").val(iti.getNumber());
    }

    $(document).delegate(".login_continue_btn, .verifyPhone", "click", function(e) {
        var uname = $.trim($("#username").val());
        var error = 0;
        var phone = $("input[name='full_number']").val();
        if (uname != '') {
            if (phn_filter.test(uname)) {
                reset();
                if (!iti.isValidNumber()) {
                    $("#username").addClass("is-invalid");
                    var errorCode = iti.getValidationError();
                    $("#error-msg").html(errorMap[errorCode]);
                    $("#error-msg").show();
                    error = 1;
                } else {
                    $("#username").removeClass("is-invalid");
                    $("#error-msg").hide();
                }
            } else {
                if (email_filter.test(uname)) {
                    $("#username").removeClass("is-invalid");
                    $("#error-msg").hide();
                    $("#password-wrapper").show();
                    $("#password-wrapper input").attr("required", true);
                    if ($("#password-wrapper input").val() == '') {
                        error = 1;
                        $("#error-msg").show();
                        $("#error-msg").html('Password field is required');
                    }
                } else {
                    error = 1;
                    $("#username").addClass("is-invalid");
                    $("#error-msg").show();
                    $("#error-msg").html('Invalid Email or Phone Number');
                }
            }
        } else {
            error = 1;
            $("#username").addClass("is-invalid");
            $("#error-msg").show();
            $("#error-msg").html('Email or Phone Number Required');
        }
        if (!error) {
            var form_inputs = $("#login-form-new").serializeArray();
            $.each(form_inputs, function(i, input) {
                if (input.name == 'full_number') {
                    input.value = phone;
                }
            });
            $.ajax({
                data: form_inputs,
                type: "POST",
                dataType: 'json',
                url: login_via_username_url,
                success: function(response) {
                    if (response.status == "Success") {
                        var data = response.data;
                        if (data.is_phone != undefined && data.is_phone == 1) {
                            $('.login-with-username').hide();
                            $('.login-with-mail').hide();
                            $('.verify-login-code').show();
                            $('.otp_inputs input').val('');
                            $('#phone_otp_success_msg').html(response.message).show();
                            setTimeout(function() {
                                $('#phone_otp_success_msg').html('').hide();
                            }, 5000);
                        } else if (data.is_email != undefined && data.is_email == 1) {
                            window.location.reload();
                        } else {
                            $("#error-msg").html('Something went wrong');
                            $("#error-msg").show();
                        }
                    }
                },
                error: function(error) {
                    var response = $.parseJSON(error.responseText);
                    // let error_messages = response.message;
                    $("#error-msg").html(response.message);
                    $("#error-msg").show();
                }
            });
        }
    });

    // $(document).ready(function() {
    //     $("#username").keypress(function(e) {
    //         if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
    //             return false;
    //         }
    //         return true;
    //     });
    // });

    $(document).delegate('.iti__country', 'click', function() {
        var code = $(this).attr('data-country-code');
        $('#countryData').val(code);
        var dial_code = $(this).attr('data-dial-code');
        $('#dialCode').val(dial_code);
    });

    $("#verify_phone_token").click(function(event) {
        var verifyToken = '';
        $('.digit-group').find('input').each(function() {
            if ($(this).val()) {
                verifyToken += $(this).val();
            }
        });
        var form_inputs = $("#login-form-new").serializeArray();
        form_inputs.push({
            name: 'verifyToken',
            value: verifyToken
        });

        $.ajax({
            type: "POST",
            dataType: "json",
            url: "{{ route('customer.verifyPhoneLoginOtp') }}",
            data: form_inputs,
            success: function(response) {
                if (response.status == 'Success') {
                    window.location.reload();
                } else {
                    $(".invalid_phone_otp_error").html(response.message);
                    setTimeout(function() {
                        $('.invalid_phone_otp_error').html('').hide();
                    }, 5000);
                }
            },
            error: function(data) {
                $(".invalid_phone_otp_error").html(data.responseJSON.message);
                setTimeout(function() {
                    $('.invalid_phone_otp_error').html('').hide();
                }, 5000);
            },
        });
    });

    $('.digit-group').find('input').each(function() {
        $(this).attr('maxlength', 1);
        $(this).on('keyup', function(e) {
            var parent = $($(this).parent());
            if (e.keyCode === 8 || e.keyCode === 37) {
                var prev = parent.find('input#' + $(this).data('previous'));
                if (prev.length) {
                    $(prev).select();
                }
            } else if ((e.keyCode >= 48 && e.keyCode <= 57) || (e.keyCode >= 65 && e.keyCode <= 90) || (e
                    .keyCode >= 96 && e.keyCode <= 105) || e.keyCode === 39) {
                var next = parent.find('input#' + $(this).data('next'));
                if ((next.length) && ($(this).val() != '')) {
                    $(next).select();
                } else {
                    if (parent.data('autosubmit')) {
                        parent.submit();
                    }
                }
            }
        });
    });

    $('#send_password_reset_link').click(function() {
        var that = $(this);
        var email = $('#username').val();
        $('.invalid-feedback').html('');
        $.ajax({
            type: "POST",
            dataType: "json",
            data: {
                "email": email
            },
            url: forgot_password_url,
            success: function(res) {
                if (res.status == "Success") {
                    $('#success-msg').html(res.message).show();
                    setTimeout(function() {
                        $('#success-msg').html('').hide();
                    }, 5000);
                }
            },
            error: function(error) {
                var response = $.parseJSON(error.responseText);
                let error_messages = response.errors;
                $.each(error_messages, function(key, error_message) {
                    $('#error-msg').html(error_message[0]).show();
                });
            }
        });
    });

    function isNumberKey(evt) {
        var charCode = (evt.which) ? evt.which : evt.keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;
        return true;
    }
</script>
<script src="{{asset('js/payment.js')}}"></script>

@endsection
