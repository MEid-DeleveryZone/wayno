@extends('layouts.store', ['title' => 'Checkout'])
@section('content')
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
<section class="section-b-space light-layout">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="success-text">
                	<i class="fa fa-check-circle" aria-hidden="true"></i>
                    <h2>{{__('Thank You')}}</h2>
                    <p>{{__('Payment is successfully processsed and your order is on the way')}}</p>
                    @if(($order->payment_method != 1) && ($order->payment_method != 2))
                    	<p>{{__('Transaction ID')}}: {{$order->payment ? $order->payment->transaction_id : ''}}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
<section class="section-b-space">
    <div class="container position-relative">
        <div class="error_msg">{{__('You have earned')}} {{ (int)$order->loyalty_points_earned }} {{__('points with this order.')}}</div>
        <div class="row">
            <div class="col-lg-6">
                <div class="product-order">
                    <h3>{{__('Your Order Details')}}</h3>
                    @foreach($order->products as $product)
                        @php
                            $image = $product->media ? $product->media->first()->image['path']['proxy_url'].'74/100'.$product->media->first()->image['path']['image_path']:$product->image['proxy_url'].'74/100'.$product->image['image_path'];
                        @endphp
	                    <div class="row product-order-detail">
	                        <div class="col-3">
	                        	<img src="{{ $image }}" class="img-fluid blur-up lazyloaded">
	                        </div>
	                        <div class="col-3 order_detail">
	                            <div>
	                                <h4>{{__('Product Name')}}</h4>
	                                <h5>{{$product->pvariant->translation_one->title}}</h5>
                                    @foreach($product->pvariant->vset as $vset)
                                        <label><span>{{$vset->optionData->trans->title}}:</span>{{$vset->variantDetail->trans->title}}</label>
                                    @endforeach
	                            </div>
	                        </div>
	                        <div class="col-3 order_detail">
	                            <div>
	                                <h4>{{__('Quantity')}}</h4>
	                                <h5>{{$product->quantity}}</h5>
	                            </div>
	                        </div>
	                        <div class="col-3 order_detail">
	                            <div>
	                                <h4>{{__('Price')}}</h4>
	                                <h5>{{Session::get('currencySymbol')}} @money($product->price * $clientCurrency->doller_compare)</h5>
	                            </div>
	                        </div>
	                    </div>
                    @endforeach
                    <div class="total-sec">
                        <ul>
                            <li>{{__('Subtotal')}}<span>{{Session::get('currencySymbol')}} @money($order->total_amount * $clientCurrency->doller_compare)</span></li>
                            @if($order->taxable_amount > 0)
                                <li>{{__('Tax')}} <span>{{Session::get('currencySymbol')}} @money($order->taxable_amount * $clientCurrency->doller_compare)</span></li>
                            @endif
                            @if($order->total_delivery_fee > 0)
                                <li>{{__('Delivery Fee')}} <span>{{Session::get('currencySymbol')}} @money($order->total_delivery_fee * $clientCurrency->doller_compare)</span></li>
                            @endif
                            @if($order->tip_amount > 0)
                                <li>{{__('Tip Amount')}} <span>{{Session::get('currencySymbol')}} @money($order->tip_amount * $clientCurrency->doller_compare)</span></li>
                            @endif
                            @if($order->subscription_discount > 0)
                                <li>{{__('Subscription Discount')}} <span>{{Session::get('currencySymbol')}} @money($order->subscription_discount * $clientCurrency->doller_compare)</span></li>
                            @endif
                            @if($order->loyalty_amount_saved > 0)
                                <li>{{__('Loyalty Amount')}} <span>{{Session::get('currencySymbol')}} @money($order->loyalty_amount_saved * $clientCurrency->doller_compare)</span></li>
                            @endif
                            @if($order->wallet_amount_used > 0)
                                <li>{{__('Wallet Amount')}} <span>{{Session::get('currencySymbol')}} @money($order->wallet_amount_used * $clientCurrency->doller_compare)</span></li>
                            @endif
                        </ul>
                    </div>
                    <div class="final-total">
                        <h3>{{__('Total')}} <span>{{Session::get('currencySymbol')}} @money($order->payable_amount * $clientCurrency->doller_compare)</span></h3>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="row order-success-sec">
                    <div class="col-sm-6">
                        <h4>{{__('Summery')}}</h4>
                        <ul class="order-detail">
                            <li>{{__('Order ID')}}: {{$order->order_number}}</li>
                            <li>{{__('Order Date')}}: {{ date('F d, Y', strtotime($order->created_at)) }}</li>
                            <li>{{__('Order Total')}}: {{Session::get('currencySymbol')}} @money($order->payable_amount * $clientCurrency->doller_compare)</li>
                        </ul>
                    </div>
                    <div class="col-sm-6">
                        <h4>{{__('Shipping Address')}}</h4>
                        <ul class="order-detail">
                            <li>{{$order->address ? $order->address->address : ''}}</li>
                        </ul>
                    </div>
                    <div class="col-sm-12 payment-mode">
                        <h4>{{__('Payment Method')}}</h4>
                        <p>{{__($order->paymentOption->title)}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('script')
<!-- <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script> -->

    <script src="https://cdn.socket.io/4.1.2/socket.io.min.js" integrity="sha384-toS6mmwu70G0fw54EGlWWeA4z3dyJ+dlXBtSURSKN4vyRFOcxd3Bzjj/AoOwY+Rg" crossorigin="anonymous"></script>

    <script>
        var url = window.location.href;
        var arr = url.split("/");
        var result = arr[2];
        $(function(){
            let ip_address = result;
            let socket_port = "3100";
            let socket = io(ip_address + ':' + socket_port);
            let message = "jhlh";
            socket.emit('sendChatToServer', message);
        });
    </script>
@endsection
