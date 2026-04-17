@extends('layouts.store', ['title' => __('Home')])

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
{{-- <div
    class="offset-top @if((\Request::route()->getName() != 'userHome') || ($client_preference_detail->show_icons == 0)) inner-pages-offset @endif @if($client_preference_detail->hide_nav_bar == 1) set-hide-nav-bar @endif">
</div> --}}

@if(count($banners))
<section class="p-0 small-slider">
    <div class="slide-1 home-slider">
        @foreach($banners as $banner)
        @php
        $url = '';
        if($banner->link == 'category'){
        if($banner->category != null){
        $url = route('categoryDetail', $banner->category->slug);
        }
        }
        else if($banner->link == 'vendor'){
        if($banner->vendor != null){
        $url = route('vendorDetail', $banner->vendor->slug);
        }
        }
        @endphp
        <div>
            @if($url)
            <a href="{{$url}}">
                @endif
                <div class="home text-center">
                    <img src="{{$banner->image['image_fit'] . '1920/1080' . $banner->image['image_path']}}"
                        class="bg-img blur-up lazyload">
                </div>
                @if($url)
            </a>
            @endif
        </div>
        @endforeach
    </div>
</section>
@endif
<script type="text/template" id="vendors_template">
    <% _.each(vendors, function(vendor, k){%>
        <div class="product-box scale-effect">
            <div class="img-wrapper">
                <div class="front">
                    <a href="{{route('vendorDetail')}}/<%= vendor.slug %>">
                        <img class="img-fluid blur-up lazyload m-auto bg-img" alt="" src="<%= vendor.logo.proxy_url %>200/200<%= vendor.logo['image_path'] %>">
                    </a>
                </div>
            </div>
            <div class="product-detail inner_spacing text-center m-0 w-100">
                <a href="{{route('vendorDetail')}}/<%= vendor.slug %>">
                    <h3 class="d-flex justify-content-between p-0">
                        <span><%= vendor.name %></span>
                        @if($client_preference_detail)
                            @if($client_preference_detail->rating_check == 1)
                                <% if(vendor.vendorRating > 0){%>
                                    <span class="rating m-0"><%= vendor.vendorRating %> <i class="fa fa-star text-white p-0"></i></span>
                                <% } %>
                            @endif
                        @endif
                    </h3>
                </a>
                <% if(vendor.timeofLineOfSightDistance != undefined){ %>
                    <h6 class="d-flex justify-content-between">
                        <small><i class="fa fa-map-marker"></i> <%= vendor.lineOfSightDistance %></small>
                        <small><i class="fa fa-clock"></i> <%= vendor.timeofLineOfSightDistance %>min</small>
                    </h6>
                <% } %>
            </div>
        </div>
    <% }); %>
</script>

<script type="text/template" id="banner_template">
    <% _.each(brands, function(brand, k){%>
        <a class="barnd-img-outer" href="<%= brand.redirect_url %>">
            <img src="<%= brand.image.image_fit %>500/500<%= brand.image.image_path %>" alt="">
        </a>
    <% }); %>
</script>

<script type="text/template" id="products_template">
    <% _.each(products, function(product, k){ %>
        <div>
            <a class="card scale-effect text-center" href="{{route('productDetail')}}/<%= product.url_slug %>">
                <label class="product-tag"><%= type %></label>
                <div class="product-image">
                    <img src="<%= product.image_url %>" alt="">
                </div>
                <div class="media-body align-self-center">
                    <div class="inner_spacing px-0">
                        <div class="d-flex align-items-center justify-content-between">
                            <h3 class="m-0"><%= product.title %></h3>
                            @if($client_preference_detail)
                                @if($client_preference_detail->rating_check == 1)
                                    <% if(product.averageRating > 0){%>
                                        <span class="rating"><%= product.averageRating %> <i class="fa fa-star text-white p-0"></i></span>
                                    <% } %>
                                @endif
                            @endif
                        </div>
                        <p><%= product.vendor_name %></p>
                        <h4>
                            <% if(product.inquiry_only == 0) { %>
                                <%= product.price %>
                            <% } %>
                        </h4>
                    </div>
                </div>
            </a>
        </div>
    <% }); %>
</script>

<script type="text/template" id="trending_vendors_template">
    <% _.each(trending_vendors, function(vendor, k){%>
        <div class="product-box scale-effect">
            <div class="img-wrapper">
                <div class="front">
                    <a href="{{route('vendorDetail')}}/<%= vendor.slug %>">
                        <img class="img-fluid blur-up lazyload m-auto bg-img" alt="" src="<%= vendor.logo.proxy_url %>200/200<%= vendor.logo['image_path'] %>">
                    </a>
                </div>
            </div>
            <div class="product-detail inner_spacing text-center m-0 w-100">
                <a href="{{route('vendorDetail')}}/<%= vendor.slug %>">
                    <h3 class="d-flex justify-content-between p-0">
                        <span><%= vendor.name %></span>
                        @if($client_preference_detail)
                            @if($client_preference_detail->rating_check == 1)
                                <% if(vendor.vendorRating > 0){%>
                                    <span class="rating m-0"><%= vendor.vendorRating %> <i class="fa fa-star text-white p-0"></i></span>
                                <% } %>
                            @endif
                        @endif
                    </h3>
                </a>
                <% if(vendor.timeofLineOfSightDistance != undefined){ %>
                    <h6 class="d-flex justify-content-between">
                        <small><i class="fa fa-map-marker"></i> <%= vendor.lineOfSightDistance %>km</small>
                        <small><i class="fa fa-clock"></i> <%= vendor.timeofLineOfSightDistance %>min</small>
                    </h6>
                <% } %>
            </div>
        </div>
    <% }); %>
</script>

<script type="text/template" id="recent_orders_template">
    <% _.each(recent_orders, function(order, k){ %>
        <% subtotal_order_price = total_order_price = total_tax_order_price = 0; %>
        <% _.each(order.vendors, function(vendor, k){ %>
        <%   product_total_count = product_subtotal_amount = product_taxable_amount = 0; %>
        <div class="order_detail order_detail_data align-items-top pb-3 card-box no-gutters mb-0 mt-3">
            <% if((vendor.delivery_fee > 0) || (order.scheduled_date_time)){ %>
                <div class="progress-order font-12">
                <% if(order.scheduled_date_time){ %>
                        <span class="badge badge-success ml-2">Scheduled</span>
                        <span class="ml-2">Your order will arrive by <%= order.converted_scheduled_date_time %></span>
                    <% } else { %>
                        <span class="ml-2">Your order will arrive by <%= vendor.ETA %></span>
                    <% } %>
                </div>
            <% } %>
            <span class="left_arrow pulse"></span>
            <div class="row">
                <div class="col-5 col-sm-3">
                    <h5 class="m-0">{{__('Order Status')}}</h5>
                    <ul class="status_box mt-3 pl-0">
                    <% if(vendor.order_status){ %>
                        <li>
                        <% if(vendor.order_status == 'placed'){ %>
                                <img src="{{ asset('assets/images/order-icon.svg') }}" alt="">
                        <% }else if(vendor.order_status == 'accepted'){ %>
                                <img src="{{ asset('assets/images/payment_icon.svg') }}" alt="">
                        <% } else if(vendor.order_status == 'processing'){ %>
                                <img src="{{ asset('assets/images/customize_icon.svg') }}" alt="">
                        <% } else if(vendor.order_status == 'out for delivery'){ %>
                                <img src="{{ asset('assets/images/driver_icon.svg') }}" alt="">
                        <% } %>
                            <label class="m-0 in-progress"><%= (vendor.order_status).charAt(0).toUpperCase() + (vendor.order_status).slice(1) %></label>
                        </li>
                    <% } %>

                    <% if(vendor.dispatch_traking_url){ %>
                        <img src="{{ asset('assets/images/order-icon.svg') }}" alt="">
                        <a href="{{route('front.booking.details')}}/<%= order.order_number %>" target="_blank">{{ __('Details') }}</a>
                    <% } %>

                    <% if(vendor.dineInTable){ %>
                        <li>
                            <h5 class="mb-1">{{ __('Dine-in') }}</h5>
                            <h6 class="m-0"><%= vendor.dineInTableName %></h6>
                            <h6 class="m-0">Category : <%=  vendor.dineInTableCategory %></h6>
                            <h6 class="m-0">Capacity : <%= vendor.dineInTableCapacity %></h6>
                        </li>
                    <% } %>

                    </ul>
                </div>
                <div class="col-7 col-sm-4">
                    <ul class="product_list d-flex align-items-center p-0 flex-wrap m-0">
                    <% _.each(vendor.products, function(product, k){ %>
                            <% if(vendor.vendor_id == product.vendor_id){ %>
                                <li class="text-center">
                                    <img src="<%= product.image_url %>" alt="">
                                    <span class="item_no position-absolute">x <%= product.quantity %></span>
                                    <label class="items_price">{{Session::get('currencySymbol')}} <%= product.price  * product.pricedoller_compare %></label>
                                </li>
                                <%
                                    product_total_price = product.price * product.doller_compare;
                                    product_total_count += product.quantity * product_total_price;
                                    product_taxable_amount += product.taxable_amount;
                                    total_tax_order_price += product.taxable_amount;
                                %>
                            <% } %>
                        <% }); %>
                    </ul>
                </div>
                <div class="col-md-5 mt-md-0 mt-sm-2">
                    <ul class="price_box_bottom m-0 p-0">
                        <li class="d-flex align-items-center justify-content-between">
                            <label class="m-0">{{__('Product Total')}}</label>
                            <span>{{Session::get('currencySymbol')}} <%=(vendor.subtotal_amount)%></span>
                        </li>
                        <li class="d-flex align-items-center justify-content-between">
                            <label class="m-0">{{__('Coupon Discount')}}</label>
                            <span>{{Session::get('currencySymbol')}} <%=(vendor.discount_amount)%></span>
                        </li>
                        <li class="d-flex align-items-center justify-content-between">
                            <label class="m-0">{{__('Delivery Fee')}}</label>
                            <span>{{Session::get('currencySymbol')}} <%= (vendor.delivery_fee)%></span>
                        </li>
                        <li class="grand_total d-flex align-items-center justify-content-between">
                            <label class="m-0">{{__('Amount')}}</label>
                            <%
                                product_subtotal_amount = product_total_count - vendor.discount_amount + vendor.delivery_fee;
                                subtotal_order_price += product_subtotal_amount;
                            %>
                            <span>{{Session::get('currencySymbol')}} <%=(vendor.payable_amount)%></span>
                        </li>
                    </ul>
                </div>

            </div>
        </div>

        <% }); %>
    <% }); %>
</script>

<section class="section-b-space p-t-0 pt-3 pt-md-5 ratio_asos d-none pb-0" id="our_vendor_main_div">
    <div class="vendors">
        @foreach($homePageLabels as $key => $homePageLabel)
        @if($homePageLabel->slug == 'pickup_delivery')
        @if(isset($homePageLabel->pickupCategories) && count($homePageLabel->pickupCategories))
        @include('frontend.booking.cabbooking-single-module')
        @endif
        @elseif($homePageLabel->slug == 'dynamic_page')
        @include('frontend.included_files.dynamic_page')
        @else
        <div class="container render_full_{{$homePageLabel->slug}}" id="{{$homePageLabel->slug.$key}}">
            <div class="row">
                <div class="col-12">
                    @if($homePageLabel->slug == 'vendors' || $homePageLabel->slug == 'trending_vendors')
                    <div class="product-5 product-m no-arrow render_{{$homePageLabel->slug}}"
                        id="{{$homePageLabel->slug.$key}}"></div>
                    @elseif($homePageLabel->slug == 'recent_orders')
                    <div class="recent-orders product-m no-arrow render_{{$homePageLabel->slug}}"
                        id="{{$homePageLabel->slug.$key}}"></div>
                    @else
                    <div class="product-4 product-m no-arrow render_{{$homePageLabel->slug }}"
                        id="{{$homePageLabel->slug.$key}}"></div>
                    @endif
                </div>
            </div>
        </div>
        @endif
        @endforeach
    </div>
</section>
<div class="modal fade" id="age_restriction" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center">
                <img src="{{asset('assets/images/18.png')}}" alt="">
                <p class="mb-0 mt-3">{{ $client_preference_detail ? $client_preference_detail->age_restriction_title :
                    'Are you 18 or older?' }}</p>
                <p class="mb-0">Are you sure you want to continue?</p>
            </div>
            <div class="modal-footer d-block">
                <div class="row no-gutters">
                    <div class="col-6 pr-1">
                        <button type="button" class="btn btn-solid w-100 age_restriction_yes"
                            data-dismiss="modal">Yes</button>
                    </div>
                    <div class="col-6 pl-1">
                        <button type="button" class="btn btn-solid w-100 age_restriction_no"
                            data-dismiss="modal">No</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script src="{{asset('front-assets/js/jquery.exitintent.js')}}"></script>
<script src="{{asset('front-assets/js/fly-cart.js')}}"></script>
<script>
    $(document).ready(function() {
        $("#doneeee").click(function(){
            console.log("nejhbfe");
        });
    });
</script>
<script src="https://js.pusher.com/beams/1.0/push-notifications-cdn.js"></script>
<script>
    const beamsClient = new PusherPushNotifications.Client({
      instanceId: '8b6a7b0a-0d24-46da-831c-de6b4e156bad',
    });
  
    beamsClient.start()
      .then(() => beamsClient.addDeviceInterest('hello'))
      .then(() => console.log('Successfully registered and subscribed!'))
      .catch(console.error);
</script>
@endsection