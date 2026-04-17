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
    @include('layouts.store/left-sidebar-template-one')
</header>
<!-- <div class="offset-top @if((\Request::route()->getName() != 'userHome') || ($client_preference_detail->show_icons == 0)) inner-pages-offset @endif @if($client_preference_detail->hide_nav_bar == 1) set-hide-nav-bar @endif"></div> -->
<!-- Button trigger modal -->
<button type="button" class="btn btn-primary d-none" data-toggle="modal" data-target="#login_modal">
  Launch demo modal
</button>

@if(count($banners))
<section class="home-slider-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="home-banner-slider">
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
                    @if($url)
                    <a class="banner-img-outer" href="{{$url}}">
                        @endif
                            <img src="{{$banner->image['image_fit'] . '1920/1080' . $banner->image['image_path']}}">
                        @if($url)
                    </a>
                    @endif
                    @endforeach

                </div>
            </div>
        </div>
    </div>
</section>
@endif

<div class="home-content-area">

</div>


<script type="text/template" id="vendors_template">
    <% _.each(vendors, function(vendor, k){%>

        <div>
            <a class="suppliers-box d-block px-2" href="{{route('vendorDetail')}}/<%= vendor.slug %>">
                <div class="suppliers-img-outer">
                    <img class="fluid-img mx-auto" src="<%= vendor.logo.image_fit %>200/200<%= vendor.logo['image_path'] %>" alt="">
                </div>
                <div class="supplier-rating">
                    <h6 class="mb-1"><%= vendor.name %></h6>
                    <p title="<%= vendor.categoriesList %>" class="vendor-cate border-bottom pb-1 mb-1 ellips"><%= vendor.categoriesList %></p>
                    <div class="product-timing">
                        <small title="<%= vendor.address %>" class="ellips d-block"><span class="icon-location2"></span> <%= vendor.address %></small>
                        <% if(vendor.timeofLineOfSightDistance != undefined){ %>
                            <ul class="timing-box mb-1">
                                <li>
                                    <small class="d-block"><span class="icon-location2"></span> <%= vendor.lineOfSightDistance %></small>
                                </li>
                                <li>
                                    <small class="d-block mx-1"><span class="icon-clock"></span> <%= vendor.timeofLineOfSightDistance %> min</small>
                                </li>
                            </ul>
                        <% } %>
                    </div>
                    @if($client_preference_detail)
                        @if($client_preference_detail->rating_check == 1)
                            <% if(vendor.vendorRating > 0){%>
                                <ul class="custom-rating m-0 p-0">
                                    <% for(var i=0; i < 5; i++){ %>
                                        <% if(i <= vendor.vendorRating){
                                            var starFillClass = 'fa-star';
                                        }else{
                                            var starFillClass = 'fa-star-o';
                                        } %>
                                        <li><i class="fa <%= starFillClass %>" aria-hidden="true"></i></li>
                                    <% } %>
                                </ul>
                            <% } %>
                        @endif
                    @endif
                </div>
            </a>
        </div>

    <% }); %>
</script>

<script type="text/template" id="banner_template">
    <% _.each(brands, function(brand, k){%>
        <div>
            <a class="brand-box d-block black-box" href="<%= brand.redirect_url %>">
                <div class="brand-ing">
                    <img src="<%= brand.image.image_fit %>500/500<%= brand.image.image_path %>" alt="">
                </div>
                <h6><%= brand.translation_title %></h6>
            </a>
        </div>
    <% }); %>
</script>

<script type="text/template" id="products_template">
    <% _.each(products, function(product, k){ %>
        <a class="common-product-box scale-effect text-center" href="{{route('productDetail')}}/<%= product.url_slug %>">
            <div class="img-outer-box position-relative">
                <img src="<%= product.image_url %>" alt="">
            </div>
            <div class="media-body align-self-center">
                <div class="inner_spacing px-0">
                    <div class="product-description">
                        <h3 class="m-0"><%= product.title %></h3>
                        <p><%= product.vendor_name %></p>
                        <p class="border-bottom pb-1">In <%= product.category %></p>
                        <div class="d-flex align-items-center justify-content-between">
                            <b><% if(product.inquiry_only == 0) { %>
                                <%= product.price %>
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
</script>

<script type="text/template" id="trending_vendors_template">
    <% _.each(trending_vendors, function(vendor, k){%>

        <div>
            <a class="suppliers-box d-block px-2" href="{{route('vendorDetail')}}/<%= vendor.slug %>">
                <div class="suppliers-img-outer">
                    <img class="fluid-img mx-auto" src="<%= vendor.logo.image_fit %>200/200<%= vendor.logo['image_path'] %>" alt="">
                </div>
                <div class="supplier-rating">
                    <h6 class="mb-1"><%= vendor.name %></h6>
                    <p title="<%= vendor.categoriesList %>" class="vendor-cate border-bottom pb-1 mb-1 ellips"><%= vendor.categoriesList %></p>
                    <div class="product-timing">
                        <small title="<%= vendor.address %>" class="ellips d-block"><i class="fa fa-map-marker"></i> <%= vendor.address %></small>
                        <% if(vendor.timeofLineOfSightDistance != undefined){ %>
                            <ul class="timing-box mb-1">
                                <li>
                                    <small class="d-block"><img class="d-inline-block mr-1" src="{{ asset('front-assets/images/distance.png') }}" alt=""> <%= vendor.lineOfSightDistance %></small>
                                </li>
                                <li>
                                    <small class="d-block mx-1"><i class="fa fa-clock-o"></i> <%= vendor.timeofLineOfSightDistance %> min</small>
                                </li>
                            </ul>
                        <% } %>
                    </div>
                    @if($client_preference_detail)
                        @if($client_preference_detail->rating_check == 1)
                            <% if(vendor.vendorRating > 0){%>
                                <ul class="custom-rating m-0 p-0">
                                    <% for(var i=0; i < 5; i++){ %>
                                        <% if(i <= vendor.vendorRating){
                                            var starFillClass = 'fa-star';
                                        }else{
                                            var starFillClass = 'fa-star-o';
                                        } %>
                                        <li><i class="fa <%= starFillClass %>" aria-hidden="true"></i></li>
                                    <% } %>
                                </ul>
                            <% } %>
                        @endif
                    @endif
                </div>
            </a>
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

<section class="section-b-space p-t-0 pt-3 pt-md-4 ratio_asos d-none pb-0" id="our_vendor_main_div">
    <div class="vendors">
        @foreach($homePageLabels as $key => $homePageLabel)
        @if($homePageLabel->slug == 'pickup_delivery')
                @if(isset($homePageLabel->pickupCategories) && count($homePageLabel->pickupCategories))
                  @include('frontend.booking.cabbooking-single-module')
                @endif
        @elseif($homePageLabel->slug == 'dynamic_page')
                @include('frontend.included_files.dynamic_page')
        @elseif($homePageLabel->slug == 'brands')
        <section class="popular-brands left-shape position-relative">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-2 cw top-heading pr-0 text-center text-lg-left mb-3 mb-lg-0">
                        <h2 class="h2-heading">{{ $homePageLabel->slug == 'brands' ? getNomenclatureName('brands', true) :  __($homePageLabel->title) }}</h2>
                        <!-- <p>Check out the favorites among people.</p> -->
                    </div>
                    <div class="col-lg-10 cw">
                        <div class="brand-slider render_{{$homePageLabel->slug }}"  id="{{$homePageLabel->slug.$key}}">

                        </div>

                    </div>
                </div>
            </div>
        </section>
        @elseif($homePageLabel->slug == 'vendors')
        <section class="suppliers-section pt-0 mb-2">
        <div class="container">
            <div class="row">
                <div class="col-12 top-heading d-flex align-items-center justify-content-between  mb-2">
                    <h2 class="h2-heading">{{ $homePageLabel->slug == 'vendors' ? getNomenclatureName('vendors', true) :  __($homePageLabel->title) }}</h2>
                    <a class="btn btn-solid" href="{{route('vendor.all')}}">See all</a>
                </div>
                <div class="col-12 px-0">
                    <div class="suppliers-slider render_{{$homePageLabel->slug}}" id="{{$homePageLabel->slug.$key}}">


                    </div>
                </div>
            </div>
        </div>
       </section>
       @elseif($homePageLabel->slug == 'trending_vendors')
        <section class="suppliers-section pt-0 mt-4">
        <div class="container">
            <div class="row">
                <div class="col-12 top-heading d-flex align-items-center justify-content-between  mb-3">
                    <h2 class="h2-heading">{{ $homePageLabel->slug == 'trending_vendors' ? __('trending')." ".getNomenclatureName('vendors', true) :  __($homePageLabel->title) }}</h2>
                </div>
                <div class="col-12 px-0">
                    <div class="suppliers-slider render_{{$homePageLabel->slug}}" id="{{$homePageLabel->slug.$key}}">


                    </div>
                </div>
            </div>
        </div>
       </section>
        @else
        <div class="container render_full_{{$homePageLabel->slug}}" id="{{$homePageLabel->slug.$key}}">
            <div class="row">
                <div class="col-12 top-heading d-flex align-items-center justify-content-between  mb-0">
                    <h2 class="h2-heading">
                    @php
                    if($homePageLabel->slug == 'vendors'){
                        echo getNomenclatureName('vendors', true);
                    } elseif($homePageLabel->slug == 'recent_orders'){
                        echo (!empty($homePageLabel->translations->first()->title)) ? $homePageLabel->translations->first()->title : __("Your Recent Orders");
                    } else {
                        echo (!empty($homePageLabel->translations->first()->title)) ? $homePageLabel->translations->first()->title : __($homePageLabel->title);
                    }
                    @endphp

                    </h2>
                    @if($homePageLabel->slug == 'vendors')
                    <a class="btn btn-solid" href="{{route('vendor.all')}}">{{__('View More')}}</a>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    @if($homePageLabel->slug == 'vendors' || $homePageLabel->slug == 'trending_vendors')
                    <div class="product-5 product-m no-arrow render_{{$homePageLabel->slug}}" id="{{$homePageLabel->slug.$key}}"></div>
                    @elseif($homePageLabel->slug == 'recent_orders')
                    <div class="recent-orders product-m no-arrow render_{{$homePageLabel->slug}}" id="{{$homePageLabel->slug.$key}}"></div>
                    @else
                    <div class="product-4 product-m no-arrow render_{{$homePageLabel->slug }}" id="{{$homePageLabel->slug.$key}}"></div>
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
                <p class="mb-0 mt-3">{{ $client_preference_detail ? $client_preference_detail->age_restriction_title : 'Are you 18 or older?' }}</p>
                <p class="mb-0">Are you sure you want to continue?</p>
            </div>
            <div class="modal-footer d-block">
                <div class="row no-gutters">
                    <div class="col-6 pr-1">
                        <button type="button" class="btn btn-solid w-100 age_restriction_yes" data-dismiss="modal">Yes</button>
                    </div>
                    <div class="col-6 pl-1">
                        <button type="button" class="btn btn-solid w-100 age_restriction_no" data-dismiss="modal">No</button>
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
            // $(".hide_div").hide();
        });
    });
    // $(".mobile-back").on("click", function() {
    //     $(".sm-horizontal").css("right", "-410px");
    // });
</script>
@endsection
