@switch($client_preference_detail->business_type)
    @case('taxi')
        <?php $ordertitle = 'Rides'; ?>
        <?php $hidereturn = 1; ?>
        @break
    @default
    <?php $ordertitle = 'Orders';  ?>
@endswitch
@extends('layouts.store', ['title' => __('My '.$ordertitle)])
@section('css')
<style type="text/css">
    .main-menu .brand-logo {
        display: inline-block;
        padding-top: 20px;
        padding-bottom: 20px;
    }
</style>
@endsection
@section('content')
@php
$timezone = Auth::user()->timezone;
@endphp
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
<style type="text/css">
    .productVariants .firstChild {
        min-width: 150px;
        text-align: left !important;
        border-radius: 0% !important;
        margin-right: 10px;
        cursor: default;
        border: none !important;
    }

    .product-right .color-variant li,
    .productVariants .otherChild {
        height: 35px;
        width: 35px;
        border-radius: 50%;
        margin-right: 10px;
        cursor: pointer;
        border: 1px solid #f7f7f7;
        text-align: center;
    }

    .productVariants .otherSize {
        height: auto !important;
        width: auto !important;
        border: none !important;
        border-radius: 0%;
    }

    .product-right .size-box ul li.active {
        background-color: inherit;
    }

    .login-page .theme-card .theme-form input {
        margin-bottom: 5px;
    }

    .invalid-feedback {
        display: block;
    }
</style>
<section class="section-b-space order-page">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="text-sm-left" id="wallet_response">
                    @if (\Session::has('success'))
                        <div class="alert alert-success">
                            <span>{!! \Session::get('success') !!}</span>
                        </div>
                        @php
                            \Session::forget('success');
                        @endphp
                    @endif
                    @if (\Session::has('error'))
                        <div class="alert alert-danger">
                            <span>{!! \Session::get('error') !!}</span>
                        </div>
                        @php
                            \Session::forget('error');
                        @endphp
                    @endif
                    <div class="message d-none">
                        <div class="alert p-0"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3">
                <div class="account-sidebar"><a class="popup-btn">{{ __('My Account') }}</a></div>
                <div class="dashboard-left">
                    <div class="collection-mobile-back"><span class="filter-back"><i class="fa fa-angle-left" aria-hidden="true"></i>{{__('Back')}}</span></div>
                    @include('layouts.store/profile-sidebar')
                </div>
            </div>
            <div class="col-lg-9">
                <div class="dashboard-right">
                    <div class="dashboard">
                        <div class="page-title">
                            <h2>{{__($ordertitle)}}</h2>
                        </div>
                        <div class="welcome-msg">
                            <h5>{{__('Here Are All Your Previous '.$ordertitle)}}</h5>
                        </div>
                        <div class="row" id="orders_wrapper">
                            <div class="col-sm-12 col-lg-12 tab-product pt-3">
                                <ul class="nav nav-tabs nav-material" id="top-tab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link {{ ((Request::query('pageType') === null) || (Request::query('pageType') == 'activeOrders')) ? 'active show' : '' }}" id="active-orders-tab" data-toggle="tab" href="#active-orders" role="tab" aria-selected="true"><i class="icofont icofont-ui-home"></i>{{__('Active '.$ordertitle)}}</a>
                                        <div class="material-border"></div>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ (Request::query('pageType') == 'pastOrders') ? 'active show' : '' }}" id="past_order-tab" data-toggle="tab" href="#past_order" role="tab" aria-selected="false"><i class="icofont icofont-man-in-glasses"></i>{{__('Past '.$ordertitle)}}</a>
                                        <div class="material-border"></div>
                                    </li>
                                    @if(isset($hidereturn) && $hidereturn != 1)
                                    <li class="nav-item">
                                        <a class="nav-link {{ (Request::query('pageType') == 'returnOrders') ? 'active show' : '' }}" id="return_order-tab" data-toggle="tab" href="#return_order" role="tab" aria-selected="false"><i class="icofont icofont-man-in-glasses"></i>{{__('Return Requests')}}</a>
                                        <div class="material-border"></div>
                                    </li>
                                    @endif
                                </ul>
                                <div class="tab-content nav-material" id="top-tabContent">
                                    <div class="tab-pane fade {{ ((Request::query('pageType') === null) || (Request::query('pageType') == 'activeOrders')) ? 'active show' : '' }}" id="active-orders" role="tabpanel"
                                        aria-labelledby="active-orders-tab">
                                        <div class="row">
                                            @if($activeOrders->isNotEmpty())
                                            @foreach($activeOrders as $key => $order)
                                            <div class="col-12">
                                                <div class="row no-gutters order_head">
                                                    <div class="col-md-3"><h4>{{__('Order Number')}}</h4></div>
                                                    <div class="col-md-3"><h4>{{__('Date & Time')}}</h4></div>
                                                    <div class="col-md-3"><h4>{{__('Customer Name')}}</h4></div>
                                                    @if($client_preference_detail->business_type != 'taxi')
                                                    <div class="col-md-3"><h4>{{__('Address')}}</h4></div>
                                                    @endif
                                                </div>
                                                <div class="row no-gutters order_data">
                                                    <div class="col-md-3">#{{$order->order_number}}</div>
                                                    <div class="col-md-3">{{convertDateTimeInTimeZone($order->created_at, $timezone, 'l, F d, Y, h:i A')}}</div>
                                                    <div class="col-md-3">
                                                        <a class="text-capitalize" href="#">{{$order->user->name}}</a>
                                                    </div>
                                                    @if($client_preference_detail->business_type != 'taxi')
                                                    <div class="col-md-3">
                                                        <span class="ellipsis" data-toggle="tooltip" data-placement="top" title="">
                                                            @if($order->address)
                                                                {{$order->address->address}}, {{$order->address->street}}, {{$order->address->city}}, {{$order->address->state}}, {{$order->address->country}} {{$order->address->pincode}}
                                                            @else
                                                                NA
                                                            @endif
                                                        </span>
                                                    </div>
                                                    @endif
                                                </div>
                                                <div class="row mt-2">
                                                    <div class="col-md-9 mb-3">
                                                        @php
                                                            $subtotal_order_price = $total_order_price = $total_tax_order_price = 0;
                                                        @endphp
                                                        @foreach($order->vendors as $key => $vendor)
                                                            @php
                                                                $product_total_count = $product_subtotal_amount = $product_taxable_amount = 0;
                                                            @endphp
                                                            <div class="order_detail order_detail_data align-items-top pb-3 card-box no-gutters mb-0">
                                                                @if(($vendor->delivery_fee > 0) || (!empty($order->scheduled_date_time)) || ($order->luxury_option_id > 0))
                                                                    <div class="progress-order font-12">
                                                                        @if($order->luxury_option_id > 0)
                                                                            @php
                                                                                $luxury_option = \App\Models\LuxuryOption::where('id', $order->luxury_option_id)->first();
                                                                                if($luxury_option->title == 'takeaway'){
                                                                                    $luxury_option_name = getNomenclatureName('Takeaway', Session::get('customerLanguage'), false);
                                                                                }elseif($luxury_option->title == 'dine_in'){
                                                                                    $luxury_option_name = 'Dine-In';
                                                                                }else{
                                                                                    $luxury_option_name = 'Delivery';
                                                                                }
                                                                            @endphp
                                                                            <span class="badge badge-info ml-2">{{$luxury_option_name}}</span>
                                                                        @endif
                                                                        @if(!empty($order->scheduled_date_time))
                                                                            <span class="badge badge-success ml-2">Scheduled</span>
                                                                            <span class="ml-2">{{convertDateTimeInTimeZone($order->scheduled_date_time, $timezone, 'M d, Y h:i A')}}</span>
                                                                        @elseif(!empty($vendor->ETA))
                                                                            <span class="ml-2">Your order will arrive by {{$vendor->ETA}}</span>
                                                                        @endif
                                                                    </div>
                                                                @endif
                                                                <span class="left_arrow pulse"></span>
                                                                <div class="row">
                                                                    <div class="col-5 col-sm-3">
                                                                        <h5 class="m-0">{{__('Order Status')}}</h5>
                                                                        <ul class="status_box mt-3 pl-0">
                                                                        @if(!empty($vendor->order_status))
                                                                            <li>
                                                                                @if($vendor->order_status == 'placed')
                                                                                    <img src="{{ asset('assets/images/order-icon.svg') }}" alt="">
                                                                                @elseif($vendor->order_status == 'accepted')
                                                                                    <img src="{{ asset('assets/images/payment_icon.svg') }}" alt="">
                                                                                @elseif($vendor->order_status == 'processing')
                                                                                    <img src="{{ asset('assets/images/customize_icon.svg') }}" alt="">
                                                                                @elseif($vendor->order_status == 'out for delivery')
                                                                                    <img src="{{ asset('assets/images/driver_icon.svg') }}" alt="">
                                                                                @endif
                                                                                <label class="m-0 in-progress">{{ ucfirst($vendor->order_status) }}</label>
                                                                            </li>
                                                                        @endif

                                                                        @if(!empty($vendor->dispatch_traking_url))
                                                                            <img src="{{ asset('assets/images/order-icon.svg') }}" alt="">
                                                                            <a href="{{route('front.booking.details',$order->order_number)}}" target="_blank">{{ __('Details') }}</a>
                                                                        @endif

                                                                        @if($vendor->dineInTable)
                                                                            <li>
                                                                                <h5 class="mb-1">{{ __('Dine-in') }}</h5>
                                                                                <h6 class="m-0">{{ $vendor->dineInTableName }}</h6>
                                                                                <h6 class="m-0">Category : {{ $vendor->dineInTableCategory }}</h6>
                                                                                <h6 class="m-0">Capacity : {{ $vendor->dineInTableCapacity }}</h6>
                                                                            </li>
                                                                        @endif

                                                                        </ul>
                                                                    </div>
                                                                    <div class="col-7 col-sm-4">
                                                                        <ul class="product_list d-flex align-items-center p-0 flex-wrap m-0">
                                                                            @foreach($vendor->products as $product)
                                                                                @if($vendor->vendor_id == $product->vendor_id)
                                                                                    <li class="text-center">
                                                                                        <img src="{{ $product->image_url }}" alt="">
                                                                                        <span class="item_no position-absolute">x{{$product->quantity}}</span>
                                                                                        <label class="items_price">{{Session::get('currencySymbol')}} {{$product->price * $clientCurrency->doller_compare}}</label>
                                                                                    </li>
                                                                                    @php
                                                                                        $product_total_price = $product->price * $clientCurrency->doller_compare;
                                                                                        $product_total_count += $product->quantity * $product_total_price;
                                                                                        $product_taxable_amount += $product->taxable_amount;
                                                                                        $total_tax_order_price += $product->taxable_amount;
                                                                                    @endphp
                                                                                @endif
                                                                            @endforeach
                                                                        </ul>
                                                                    </div>
                                                                    <div class="col-md-5 mt-md-0 mt-sm-2">
                                                                        <ul class="price_box_bottom m-0 p-0">
                                                                            <li class="d-flex align-items-center justify-content-between">
                                                                                <label class="m-0">{{__('Product Total')}}</label>
                                                                                <span>{{Session::get('currencySymbol')}} @money($vendor->subtotal_amount * $clientCurrency->doller_compare)</span>
                                                                            </li>
                                                                            @if($vendor->discount_amount > 0)
                                                                            <li class="d-flex align-items-center justify-content-between">
                                                                                <label class="m-0">{{__('Coupon Discount')}}</label>
                                                                                <span>{{Session::get('currencySymbol')}} @money($vendor->discount_amount * $clientCurrency->doller_compare)</span>
                                                                            </li>
                                                                            @endif
                                                                            @if($vendor->delivery_fee > 0)
                                                                            <li class="d-flex align-items-center justify-content-between">
                                                                                <label class="m-0">{{__('Delivery Fee')}}</label>
                                                                                <span>{{Session::get('currencySymbol')}} @money($vendor->delivery_fee * $clientCurrency->doller_compare)</span>
                                                                            </li>
                                                                            @endif
                                                                            <li class="grand_total d-flex align-items-center justify-content-between">
                                                                                <label class="m-0">{{__('Amount')}}</label>
                                                                                @php
                                                                                    $product_subtotal_amount = $product_total_count - $vendor->discount_amount + $vendor->delivery_fee;
                                                                                    $subtotal_order_price += $product_subtotal_amount;
                                                                                @endphp
                                                                                <span>{{Session::get('currencySymbol')}} @money($vendor->payable_amount * $clientCurrency->doller_compare)</span>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                    <div class="col-md-3 mb-3 pl-lg-0">
                                                        <div class="card-box p-2 mb-0 h-100">
                                                            <ul class="price_box_bottom m-0 pl-0 pt-1">
                                                                <li class="d-flex align-items-center justify-content-between">
                                                                    <label class="m-0">{{__('Sub Total')}}</label>
                                                                    <span>{{Session::get('currencySymbol')}} @money($order->total_amount * $clientCurrency->doller_compare)</span>
                                                                </li>
                                                                @if($order->wallet_amount_used > 0)
                                                                <li class="d-flex align-items-center justify-content-between">
                                                                    <label class="m-0">{{__('Wallet')}}</label>
                                                                    <span>{{Session::get('currencySymbol')}} @money($order->wallet_amount_used * $clientCurrency->doller_compare)</span>
                                                                </li>
                                                                @endif
                                                                @if($order->loyalty_amount_saved > 0)
                                                                <li class="d-flex align-items-center justify-content-between">
                                                                    <label class="m-0">{{__('Loyalty Used')}}</label>
                                                                    <span>{{Session::get('currencySymbol')}} @money($order->loyalty_amount_saved * $clientCurrency->doller_compare)</span>
                                                                </li>
                                                                @endif
                                                                @if($order->taxable_amount > 0)
                                                                <li class="d-flex align-items-center justify-content-between">
                                                                    <label class="m-0">{{__('Tax')}}</label>
                                                                    <span>{{Session::get('currencySymbol')}} @money($order->taxable_amount * $clientCurrency->doller_compare)</span>
                                                                </li>
                                                                @endif
                                                                @if($order->tip_amount > 0)
                                                                <li class="d-flex align-items-center justify-content-between">
                                                                    <label class="m-0">{{__('Tip Amount')}}</label>
                                                                    <span>{{Session::get('currencySymbol')}} @money($order->tip_amount * $clientCurrency->doller_compare)</span>
                                                                </li>
                                                                @endif
                                                                @if($order->subscription_discount > 0)
                                                                <li class="d-flex align-items-center justify-content-between">
                                                                    <label class="m-0">{{__('Subscription Discount')}}</label>
                                                                    <span>{{Session::get('currencySymbol')}} @money($order->subscription_discount * $clientCurrency->doller_compare)</span>
                                                                </li>
                                                                @endif
                                                                @if($order->total_discount_calculate > 0)
                                                                <li class="d-flex align-items-center justify-content-between">
                                                                    <label class="m-0">{{__('Discount')}}</label>
                                                                    <span>{{Session::get('currencySymbol')}} @money($order->total_discount_calculate * $clientCurrency->doller_compare)</span>
                                                                </li>
                                                                @endif
                                                                @if($order->total_delivery_fee > 0)
                                                                <li class="d-flex align-items-center justify-content-between">
                                                                    <label class="m-0">{{__('Delivery Fee')}}</label>
                                                                    <span>{{Session::get('currencySymbol')}} @money($order->total_delivery_fee * $clientCurrency->doller_compare)</span>
                                                                </li>
                                                                @endif
                                                                <li class="grand_total d-flex align-items-center justify-content-between">
                                                                    <label class="m-0">{{__('Total Payable')}}</label>
                                                                    <span>{{Session::get('currencySymbol')}} @money($order->payable_amount - $order->total_discount_calculate * $clientCurrency->doller_compare)</span>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                            @else
                                                <div class="col-12">
                                                    <div class="no-gutters order_head">
                                                        <h4 class="text-center">{{__('No Active Order Found')}}</h4>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                        {{ $activeOrders->appends(['pageType' => 'activeOrders'])->links() }}
                                    </div>
                                    <div class="tab-pane fade past-order {{ (Request::query('pageType') == 'pastOrders') ? 'active show' : '' }}" id="past_order" role="tabpanel"
                                        aria-labelledby="past_order-tab">
                                        <div class="row">
                                            @if($pastOrders->isNotEmpty())
                                            @foreach($pastOrders as $key => $order)
                                            <div class="col-12">
                                                <div class="row no-gutters order_head">
                                                    <div class="col-md-3"><h4>{{__('Order Number')}}</h4></div>
                                                    <div class="col-md-3"><h4>{{__('Date & Time')}}</h4></div>
                                                    <div class="col-md-3"><h4>{{__('Customer Name')}}</h4></div>
                                                    @if($client_preference_detail->business_type != 'taxi')
                                                    <div class="col-md-3"><h4>{{__('Address')}}</h4></div>
                                                    @endif
                                                </div>
                                                <div class="row no-gutters order_data">
                                                    <div class="col-md-3">#{{$order->order_number}}</div>
                                                    <div class="col-md-3">{{$order->created_at->format('D M d, Y h:m A')}}</div>
                                                    <div class="col-md-3">
                                                        <a class="text-capitalize" href="#">{{$order->user->name}}</a>
                                                    </div>
                                                    @if($client_preference_detail->business_type != 'taxi')
                                                    <div class="col-md-3">
                                                        <span class="ellipsis" data-toggle="tooltip" data-placement="top" title="">
                                                            @if($order->address)
                                                                {{$order->address->address}}, {{$order->address->street}}, {{$order->address->city}}, {{$order->address->state}}, {{$order->address->country}} {{$order->address->pincode}}
                                                            @else
                                                                NA
                                                            @endif
                                                        </span>
                                                    </div>
                                                    @endif
                                                </div>
                                                <div class="row mt-2">
                                                    <div class="col-md-9 mb-3">
                                                        @php
                                                            $subtotal_order_price = $total_order_price = $total_tax_order_price = 0;
                                                        @endphp
                                                        @foreach($order->vendors as $key => $vendor)
                                                            @php
                                                                $product_total_count = $product_subtotal_amount = $product_taxable_amount = 0;
                                                            @endphp
                                                            <div class="order_detail order_detail_data align-items-top pb-3 card-box no-gutters mb-0">
                                                                <span class="left_arrow pulse"></span>
                                                                <div class="row">
                                                                    <div class="col-5 col-sm-3">
                                                                        <h5 class="m-0">{{__('Order Status')}}</h5>
                                                                        <ul class="status_box mt-3 pl-0">
                                                                        @if(!empty($vendor->order_status))
                                                                            <li>
                                                                                <img src="{{ asset('assets/images/driver_icon.svg') }}" alt="">
                                                                                <label class="m-0 in-progress">{{ ucfirst($vendor->order_status) }}</label>
                                                                            </li>
                                                                        @endif




                                                                        @if(!empty($vendor->dispatch_traking_url))
                                                                        <img src="{{ asset('assets/images/order-icon.svg') }}" alt="">
                                                                        <a href="{{route('front.booking.details',$order->order_number)}}" target="_blank">{{ __('Details') }}</a>
                                                                        @endif
                                                                            @if($vendor->dineInTable)
                                                                                <li>
                                                                                    <h5 class="mb-1">{{ __('Dine-in') }}</h5>
                                                                                    <h6 class="m-0">{{ $vendor->dineInTableName }}</h6>
                                                                                    <h6 class="m-0">Category : {{ $vendor->dineInTableCategory }}</h6>
                                                                                    <h6 class="m-0">Capacity : {{ $vendor->dineInTableCapacity }}</h6>
                                                                                </li>
                                                                            @endif
                                                                        </ul>
                                                                    </div>
                                                                    <div class="col-7 col-sm-4">
                                                                        <ul class="product_list d-flex align-items-center p-0 flex-wrap m-0">
                                                                            @foreach($vendor->products as $product)
                                                                                @if($vendor->vendor_id == $product->vendor_id)
                                                                                @php
                                                                                $pro_rating = $product->productRating->rating??0;
                                                                            @endphp
                                                                            <li class="text-center">
                                                                                <img src="{{ $product->image_url }}" alt="">
                                                                                <span class="item_no position-absolute">x{{$product->quantity}}</span>
                                                                                <label class="items_price">{{Session::get('currencySymbol')}} {{$product->price * $clientCurrency->doller_compare}}</label>
                                                                                <label class="rating-star add_edit_review" data-id="{{$product->productRating->id??0}}"  data-order_vendor_product_id="{{$product->id??0}}">
                                                                                    <i class="fa fa-star{{ $pro_rating >= 1 ? '' : '-o' }}" ></i>
                                                                                    <i class="fa fa-star{{ $pro_rating >= 2 ? '' : '-o' }}" ></i>
                                                                                    <i class="fa fa-star{{ $pro_rating >= 3 ? '' : '-o' }}" ></i>
                                                                                    <i class="fa fa-star{{ $pro_rating >= 4 ? '' : '-o' }}" ></i>
                                                                                    <i class="fa fa-star{{ $pro_rating >= 5 ? '' : '-o' }}" ></i>
                                                                                </label>
                                                                                    @php
                                                                                        $product_total_price = $product->price * $clientCurrency->doller_compare;
                                                                                        $product_total_count += $product->quantity * $product_total_price;
                                                                                        $product_taxable_amount += $product->taxable_amount;
                                                                                        $total_tax_order_price += $product->taxable_amount;
                                                                                    @endphp
                                                                                @endif
                                                                            </li>
                                                                            @endforeach
                                                                        </ul>
                                                                    </div>
                                                                    <div class="col-md-5 mt-md-0 mt-sm-2">
                                                                        <ul class="price_box_bottom m-0 p-0">
                                                                            <li class="d-flex align-items-center justify-content-between">
                                                                                <label class="m-0">{{__('Product Total')}}</label>
                                                                                <span>{{Session::get('currencySymbol')}} @money($product_total_count * $clientCurrency->doller_compare)</span>
                                                                            </li>
                                                                            @if($vendor->discount_amount > 0)
                                                                            <li class="d-flex align-items-center justify-content-between">
                                                                                <label class="m-0">{{__('Coupon Discount')}}</label>
                                                                                <span>{{Session::get('currencySymbol')}} @money($vendor->discount_amount * $clientCurrency->doller_compare)</span>
                                                                            </li>
                                                                            @endif
                                                                            @if($vendor->delivery_fee > 0)
                                                                            <li class="d-flex align-items-center justify-content-between">
                                                                                <label class="m-0">{{__('Delivery Fee')}}</label>
                                                                                <span>{{Session::get('currencySymbol')}} @money($vendor->delivery_fee * $clientCurrency->doller_compare)</span>
                                                                            </li>
                                                                            @endif
                                                                            <li class="grand_total d-flex align-items-center justify-content-between">
                                                                                <label class="m-0">{{__('Amount')}}</label>
                                                                                @php
                                                                                    $product_subtotal_amount = $product_total_count - $vendor->discount_amount + $vendor->delivery_fee;
                                                                                    $subtotal_order_price += $product_subtotal_amount;
                                                                                @endphp
                                                                                <span>{{Session::get('currencySymbol')}} @money($product_subtotal_amount * $clientCurrency->doller_compare)</span>
                                                                            </li>
                                                                            @if(isset($hidereturn) && $hidereturn != 1)
                                                                            <button class="return-order-product btn btn-solid" data-id="{{$order->id??0}}"  data-vendor_id="{{$vendor->vendor_id??0}}"><td class="text-center" colspan="3">{{__('Return')}}</button>
                                                                            @endif
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                    <div class="col-md-3 mb-3 pl-lg-0">
                                                        <div class="card-box p-2 mb-0 h-100">
                                                            <ul class="price_box_bottom m-0 pl-0 pt-1">
                                                                <li class="d-flex align-items-center justify-content-between">
                                                                    <label class="m-0">{{__('Sub Total')}}</label>
                                                                    <span>{{Session::get('currencySymbol')}} @money($order->total_amount + $order->total_delivery_fee * $clientCurrency->doller_compare)</span>
                                                                </li>
                                                                @if($order->wallet_amount_used > 0)
                                                                <li class="d-flex align-items-center justify-content-between">
                                                                    <label class="m-0">{{__('Wallet')}}</label>
                                                                    <span>{{Session::get('currencySymbol')}} @money($order->wallet_amount_used * $clientCurrency->doller_compare)</span>
                                                                </li>
                                                                @endif
                                                                @if($order->loyalty_amount_saved > 0)
                                                                <li class="d-flex align-items-center justify-content-between">
                                                                    <label class="m-0">{{__('Loyalty Used')}}</label>
                                                                    <span>{{Session::get('currencySymbol')}} @money($order->loyalty_amount_saved * $clientCurrency->doller_compare)</span>
                                                                </li>
                                                                @endif
                                                                @if($order->taxable_amount > 0)
                                                                <li class="d-flex align-items-center justify-content-between">
                                                                    <label class="m-0">{{__('Tax')}}</label>
                                                                    <span>{{Session::get('currencySymbol')}} @money($order->taxable_amount * $clientCurrency->doller_compare)</span>
                                                                </li>
                                                                @endif
                                                                @if($order->tip_amount > 0)
                                                                <li class="d-flex align-items-center justify-content-between">
                                                                    <label class="m-0">{{__('Tip Amount')}}</label>
                                                                    <span>{{Session::get('currencySymbol')}} @money($order->tip_amount * $clientCurrency->doller_compare)</span>
                                                                </li>
                                                                @endif
                                                                @if($order->subscription_discount > 0)
                                                                <li class="d-flex align-items-center justify-content-between">
                                                                    <label class="m-0">{{__('Subscription Discount')}}</label>
                                                                    <span>{{Session::get('currencySymbol')}} @money($order->subscription_discount * $clientCurrency->doller_compare)</span>
                                                                </li>
                                                                @endif
                                                                @if($order->total_discount_calculate > 0)
                                                                <li class="d-flex align-items-center justify-content-between">
                                                                    <label class="m-0">{{__('Discount')}}</label>
                                                                    <span>{{Session::get('currencySymbol')}} @money($order->total_discount_calculate * $clientCurrency->doller_compare)</span>
                                                                </li>
                                                                @endif
                                                                @if($order->total_delivery_fee > 0)
                                                                <li class="d-flex align-items-center justify-content-between">
                                                                    <label class="m-0">{{__('Delivery Fee')}}</label>
                                                                    <span>{{Session::get('currencySymbol')}} @money($order->total_delivery_fee * $clientCurrency->doller_compare)</span>
                                                                </li>
                                                                @endif
                                                                <li class="grand_total d-flex align-items-center justify-content-between">
                                                                    <label class="m-0">{{__('Total Payable')}}</label>
                                                                    <span>{{Session::get('currencySymbol')}} @money($order->payable_amount-$order->total_discount_calculate * $clientCurrency->doller_compare)</span>
                                                                </li>
                                                            </ul>

                                                            @if($client_preference_detail->tip_after_order == 1 && $order->tip_amount <= 0 && $payments > 0 && 1==2)
                                                            <hr>
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <div class="mb-2">{{__('Do you want to give a tip?')}}</div>
                                                                    <div class="tip_radio_controls">
                                                                        @if($order->payable_amount > 0)
                                                                            <input type="radio" class="tip_radio" id="control_01" name="select{{$order->order_number}}" value="{{$order->payable_amount*0.05}}">
                                                                            <label class="tip_label" for="control_01">
                                                                                <h5 class="m-0" id="tip_5">{{Session::get('currencySymbol')}} {{$order->payable_amount*0.05}}</h5>
                                                                                <p class="m-0">5%</p>
                                                                            </label>

                                                                            <input type="radio" class="tip_radio" id="control_02" name="select{{$order->order_number}}" value="{{$order->payable_amount*0.10}}" >
                                                                            <label class="tip_label" for="control_02">
                                                                                <h5 class="m-0" id="tip_10">{{Session::get('currencySymbol')}} {{$order->payable_amount*0.10}}</h5>
                                                                                <p class="m-0">10%</p>
                                                                            </label>

                                                                            <input type="radio" class="tip_radio" id="control_03" name="select{{$order->order_number}}" value="{{$order->payable_amount*0.15}}" >
                                                                            <label class="tip_label" for="control_03">
                                                                                <h5 class="m-0" id="tip_15">{{Session::get('currencySymbol')}} {{$order->payable_amount*0.15}}</h5>
                                                                                <p class="m-0">15%</p>
                                                                            </label>

                                                                            <input type="radio" class="tip_radio" id="custom_control{{$order->order_number}}" name="select{{$order->order_number}}" value="custom" >
                                                                            <label class="tip_label" for="custom_control{{$order->order_number}}">
                                                                                <h5 class="m-0">{{__('Custom')}}<br>{{__('Amount')}}</h5>
                                                                            </label>
                                                                        @else
                                                                            <input type="hidden" class="tip_radio" id="custom_control{{$order->order_number}}" name="select{{$order->order_number}}" value="custom" checked>

                                                                        @endif
                                                                    </div>
                                                                    <div class="custom_tip mb-1 @if($order->payable_amount  > 0)  d-none @endif">
                                                                        <input class="input-number form-control" name="custom_tip_amount{{$order->order_number}}" id="custom_tip_amount{{$order->order_number}}" placeholder="Enter Custom Amount" type="number" value="" step="0.1">
                                                                    </div>
                                                                    <div class="col-md-6 text-md-right text-center">
                                                                        <button type="button" class="btn btn-solid topup_wallet_btn_tip topup_wallet_btn_for_tip"  data-order_number={{$order->order_number}} data-payableamount={{$order->payable_amount}} >{{__('Submit')}}</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <hr class="my-2">
                                                            @endif

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                            @else
                                                <div class="col-12">
                                                    <div class="no-gutters order_head">
                                                        <h4 class="text-center">{{__('No Past Order Found')}}</h4>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                        {{ $pastOrders->appends(['pageType' => 'pastOrders'])->links() }}
                                    </div>
                                    <div class="tab-pane fade return-order {{ (Request::query('pageType') == 'returnOrders') ? 'active show' : '' }}" id="return_order" role="tabpanel"
                                    aria-labelledby="return_order-tab">
                                    <div class="row">
                                        @if($returnOrders->isNotEmpty())
                                        @foreach($returnOrders as $key => $order)
                                        @if($order->orderStatusVendor->isNotEmpty())
                                        <div class="col-12">
                                            <div class="row no-gutters order_head">
                                                <div class="col-md-3"><h4>{{__('Order Number')}}</h4></div>
                                                <div class="col-md-3"><h4>{{__('Date & Time')}}</h4></div>
                                                <div class="col-md-3"><h4>{{__('Customer Name')}}</h4></div>
                                                @if($client_preference_detail->business_type != 'taxi')
                                                <div class="col-md-3"><h4>{{__('Address')}}</h4></div>
                                                @endif
                                            </div>
                                            <div class="row no-gutters order_data">
                                                <div class="col-md-3">#{{$order->order_number}}</div>
                                                <div class="col-md-3">{{$order->created_at->format('D M d, Y h:m A')}}</div>
                                                <div class="col-md-3">
                                                    <a class="text-capitalize" href="#">{{$order->user->name}}</a>
                                                </div>
                                                @if($client_preference_detail->business_type != 'taxi')
                                                <div class="col-md-3">
                                                    <span class="ellipsis" data-toggle="tooltip" data-placement="top" title="">
                                                        @if($order->address)
                                                            {{$order->address->address}}, {{$order->address->street}}, {{$order->address->city}}, {{$order->address->state}}, {{$order->address->country}} {{$order->address->pincode}}
                                                        @else
                                                            NA
                                                        @endif
                                                    </span>
                                                </div>
                                                @endif
                                            </div>
                                            <div class="row mt-2">
                                                <div class="col-md-9 mb-3">
                                                    @php
                                                        $subtotal_order_price = $total_order_price = $total_tax_order_price = 0;
                                                    @endphp
                                                    @foreach($order->vendors as $key => $vendor)
                                                        @php
                                                            $product_total_count = $product_subtotal_amount = $product_taxable_amount = 0;
                                                        @endphp
                                                        <div class="order_detail order_detail_data align-items-top pb-3 card-box no-gutters mb-0">
                                                            <span class="left_arrow pulse"></span>
                                                            <div class="row">
                                                                <div class="col-5 col-sm-3">
                                                                    <h5 class="m-0"></h5>
                                                                    <ul class="status_box mt-3 pl-0">
                                                                        @if($vendor->dineInTable)
                                                                            <li>
                                                                                <h5 class="mb-1">{{ __('Dine-in') }}</h5>
                                                                                <h6 class="m-0">{{ $vendor->dineInTableName }}</h6>
                                                                                <h6 class="m-0">Category : {{ $vendor->dineInTableCategory }}</h6>
                                                                                <h6 class="m-0">Capacity : {{ $vendor->dineInTableCapacity }}</h6>
                                                                            </li>
                                                                        @endif
                                                                    </ul>
                                                                </div>
                                                                <div class="col-7 col-sm-4">
                                                                    <ul class="product_list d-flex align-items-center p-0 flex-wrap m-0">
                                                                        @foreach($vendor->products as $product)
                                                                            @if($vendor->vendor_id == $product->vendor_id)
                                                                                @php
                                                                                    $pro_rating = $product->productRating->rating??0;
                                                                                @endphp
                                                                                <li class="text-center">
                                                                                    <img src="{{ $product->image_url }}" alt="">
                                                                                    <span class="item_no position-absolute">x{{$product->quantity}}</span>
                                                                                    <label class="items_price">{{Session::get('currencySymbol')}} {{$product->price * $clientCurrency->doller_compare}}</label>
                                                                                    <label class="rating-star add_edit_review" data-id="{{$product->productRating->id??0}}"  data-order_vendor_product_id="{{$product->id??0}}">
                                                                                        <i class="fa fa-star{{ $pro_rating >= 1 ? '' : '-o' }}" ></i>
                                                                                        <i class="fa fa-star{{ $pro_rating >= 2 ? '' : '-o' }}" ></i>
                                                                                        <i class="fa fa-star{{ $pro_rating >= 3 ? '' : '-o' }}" ></i>
                                                                                        <i class="fa fa-star{{ $pro_rating >= 4 ? '' : '-o' }}" ></i>
                                                                                        <i class="fa fa-star{{ $pro_rating >= 5 ? '' : '-o' }}" ></i>
                                                                                    </label>
                                                                                   {{ __($product->productReturn->status??'') }}
                                                                                </li>
                                                                                @php
                                                                                $product_total_price = $product->price * $clientCurrency->doller_compare;
                                                                                    $product_total_count += $product->quantity * $product_total_price;
                                                                                    $product_taxable_amount += $product->taxable_amount;
                                                                                    $total_tax_order_price += $product->taxable_amount;
                                                                                @endphp
                                                                            @endif
                                                                        @endforeach
                                                                    </ul>
                                                                </div>
                                                                <div class="col-md-5 mt-md-0 mt-sm-2">
                                                                    <ul class="price_box_bottom m-0 p-0">
                                                                        <li class="d-flex align-items-center justify-content-between">
                                                                            <label class="m-0">{{__('Product Total')}}</label>
                                                                            <span>{{Session::get('currencySymbol')}} @money($vendor->subtotal_amount * $clientCurrency->doller_compare)</span>
                                                                        </li>
                                                                        @if($vendor->discount_amount > 0)
                                                                        <li class="d-flex align-items-center justify-content-between">
                                                                            <label class="m-0">{{__('Coupon Discount')}}</label>
                                                                            <span>{{Session::get('currencySymbol')}} @money($vendor->discount_amount * $clientCurrency->doller_compare)</span>
                                                                        </li>
                                                                        @endif
                                                                        @if($vendor->delivery_fee > 0)
                                                                        <li class="d-flex align-items-center justify-content-between">
                                                                            <label class="m-0">{{__('Delivery Fee')}}</label>
                                                                            <span>{{Session::get('currencySymbol')}} @money($vendor->delivery_fee * $clientCurrency->doller_compare)</span>
                                                                        </li>
                                                                        @endif
                                                                        <li class="grand_total d-flex align-items-center justify-content-between">
                                                                            <label class="m-0">{{__('Amount')}}</label>
                                                                            @php
                                                                                $product_subtotal_amount = $product_total_count - $vendor->discount_amount + $vendor->delivery_fee;
                                                                                $subtotal_order_price += $product_subtotal_amount;
                                                                                $total_order_price += $product_subtotal_amount + $total_tax_order_price;
                                                                            @endphp
                                                                            <span>{{Session::get('currencySymbol')}} @money($vendor->payable_amount * $clientCurrency->doller_compare)</span>
                                                                        </li>


                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                                <div class="col-md-3 mb-3 pl-lg-0">
                                                    <div class="card-box p-2 mb-0 h-100">
                                                        <ul class="price_box_bottom m-0 pl-0 pt-1">
                                                            <li class="d-flex align-items-center justify-content-between">
                                                                <label class="m-0">{{('Sub Total')}}</label>
                                                                <span>{{Session::get('currencySymbol')}} @money($order->total_amount + $order->total_delivery_fee * $clientCurrency->doller_compare)</span>
                                                            </li>
                                                            @if($order->wallet_amount_used > 0)
                                                            <li class="d-flex align-items-center justify-content-between">
                                                                <label class="m-0">{{__('Wallet')}}</label>
                                                                <span>{{Session::get('currencySymbol')}} @money($order->wallet_amount_used * $clientCurrency->doller_compare)</span>
                                                            </li>
                                                            @endif
                                                            @if($order->loyalty_amount_saved > 0)
                                                            <li class="d-flex align-items-center justify-content-between">
                                                                <label class="m-0">{{__('Loyalty Used')}}</label>
                                                                <span>{{Session::get('currencySymbol')}} @money($order->loyalty_amount_saved * $clientCurrency->doller_compare)</span>
                                                            </li>
                                                            @endif
                                                            @if($order->taxable_amount > 0)
                                                            <li class="d-flex align-items-center justify-content-between">
                                                                <label class="m-0">{{__('Tax')}}</label>
                                                                <span>{{Session::get('currencySymbol')}} @money($order->taxable_amount * $clientCurrency->doller_compare)</span>
                                                            </li>
                                                            @endif
                                                            @if($order->tip_amount > 0)
                                                            <li class="d-flex align-items-center justify-content-between">
                                                                <label class="m-0">{{__('Tip Amount')}}</label>
                                                                <span>{{Session::get('currencySymbol')}} @money($order->tip_amount * $clientCurrency->doller_compare)</span>
                                                            </li>
                                                            @endif
                                                            @if($order->subscription_discount > 0)
                                                            <li class="d-flex align-items-center justify-content-between">
                                                                <label class="m-0">{{__('Subscription Discount')}}</label>
                                                                <span>{{Session::get('currencySymbol')}} @money($order->subscription_discount * $clientCurrency->doller_compare)</span>
                                                            </li>
                                                            @endif
                                                            @if($order->total_delivery_fee > 0)
                                                            <li class="d-flex align-items-center justify-content-between">
                                                                <label class="m-0">{{__('Delivery Fee')}}</label>
                                                                <span>{{Session::get('currencySymbol')}} @money($order->total_delivery_fee * $clientCurrency->doller_compare)</span>
                                                            </li>
                                                            @endif
                                                            <li class="grand_total d-flex align-items-center justify-content-between">
                                                                <label class="m-0">{{__('Total Payable')}}</label>
                                                                <span>{{Session::get('currencySymbol')}} @money($order->payable_amount * $clientCurrency->doller_compare)</span>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        @endforeach
                                        @else
                                            <div class="col-12">
                                                <div class="no-gutters order_head">
                                                    <h4 class="text-center">{{__('No Return Requests')}}</h4>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    {{ $returnOrders->appends(['pageType' => 'returnOrders'])->links() }}
                                </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-account box-info">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<div class="modal fade product-rating" id="product_rating" tabindex="-1" aria-labelledby="product_ratingLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <div id="review-rating-form-modal">
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade return-order" id="return_order_model" tabindex="-1" aria-labelledby="return_orderLabel"  aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                    </button>
                <div id="return-order-form-modal"></div>
            </div>
        </div>
    </div>
</div>


<!-- tip after order complete -->
@include('frontend.modals.tip_after_order')

<!-- end tip order after complete -->



@endsection
@section('script')
<script src="{{asset('js/tip_after_order.js')}}"></script>
<script src="https://js.stripe.com/v3/"></script>

<script src="{{asset('js/payment.js')}}"></script>
<script type="text/javascript">
 $(document).delegate(".topup_wallet_btn_tip", "click", function () {
     $('#topup_wallet').modal('show');
     var payable_amount = $(this).attr('data-payableamount');
     var order_number = $(this).attr('data-order_number');
     var input_name = "select"+order_number;
     var custom_tip_amount = "custom_tip_amount"+order_number;

     var select_tip =  $('input[name="' + input_name + '"]:checked').val();
     console.log(custom_tip_amount);
     console.log(select_tip);

     if(select_tip != 'custom' && select_tip != undefined){
         $('.wallet_balance').html(select_tip);
        var tip_amount = select_tip;
    }
    else{
        $('.wallet_balance').html($('input[name="' + custom_tip_amount + '"]').val());
        var tip_amount = $('input[name="' + custom_tip_amount + '"]').val();
    }

     $("#wallet_amount").val(tip_amount);
     $("#cart_tip_amount").val(tip_amount);
     $("#order_number").val(order_number);


    });
    var ajaxCall = 'ToCancelPrevReq';
    var credit_tip_url = "{{route('user.tip_after_order')}}";
    var payment_stripe_url = "{{route('payment.stripe')}}";
    var payment_paypal_url = "{{route('payment.paypalPurchase')}}";
    var wallet_payment_options_url = "{{route('wallet.payment.option.list')}}";
    var payment_success_paypal_url = "{{route('payment.paypalCompletePurchase')}}";
    var payment_paystack_url = "{{route('payment.paystackPurchase')}}";
    var payment_success_paystack_url = "{{route('payment.paystackCompletePurchase')}}";
    var payment_payfast_url = "{{route('payment.payfastPurchase')}}";
    var amount_required_error_msg = "{{__('Please enter amount.') }}";
    var payment_method_required_error_msg = "{{__('Please select payment method.')}}";

</script>

<script type="text/javascript">
    var ajaxCall = 'ToCancelPrevReq';
    $('.verifyEmail').click(function() {
        verifyUser('email');
    });
    $('.verifyPhone').click(function() {
        verifyUser('phone');
    });
    function verifyUser($type = 'email') {
        ajaxCall = $.ajax({
            type: "post",
            dataType: "json",
            url: "{{ route('verifyInformation', Auth::user()->id) }}",
            data: {
                "_token": "{{ csrf_token() }}",
                "type": $type,
            },
            beforeSend: function() {
                if (ajaxCall != 'ToCancelPrevReq' && ajaxCall.readyState < 4) {
                    ajaxCall.abort();
                }
            },
            success: function(response) {
                var res = response.result;

            },
            error: function(data) {

            },
        });
    }
    $('body').on('click', '.add_edit_review', function (event) {
        event.preventDefault();
        var id = $(this).data('id');
        var order_vendor_product_id = $(this).data('order_vendor_product_id');
        $.get('/rating/get-product-rating?id=' + id +'&order_vendor_product_id=' + order_vendor_product_id, function(markup)
        {
            $('#product_rating').modal('show');
            $('#review-rating-form-modal').html(markup);
        });
    });
    $('body').on('click', '.return-order-product', function (event) {
        event.preventDefault();
        var id = $(this).data('id');
        var vendor_id = $(this).data('vendor_id');
        $.get('/return-order/get-order-data-in-model?id=' + id +'&vendor_id=' + vendor_id, function(markup)
                {
                    $('#return_order_model').modal('show');
                    $('#return-order-form-modal').html(markup);
                });
    });
    $(document).delegate("#orders_wrapper .nav-tabs .nav-link", "click", function(){
        let id = $(this).attr('id');
        const params = window.location.search;
        if(params != ''){
            if(id == 'active-orders-tab'){
                window.location.href = window.location.pathname + '?pageType=activeOrders';
            }
            else if(id == 'past_order-tab'){
                window.location.href = window.location.pathname + '?pageType=pastOrders';
            }
        }
    });
</script>
@endsection
