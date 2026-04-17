@extends('layouts.store', ['title' => $vendor->name])
@section('css')
<style type="text/css">
   .main-menu .brand-logo {
   display: inline-block;
   padding-top: 20px;
   padding-bottom: 20px;
   }
</style>
<link rel="stylesheet" type="text/css" href="{{asset('front-assets/css/price-range.css')}}">
@endsection
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
    .product-box .product-detail h4, .product-box .product-info h4{
        font-size: 16px;
    }
    select.changeVariant {
        color: #343a40;
        border: 1px solid #bbb;
        border-radius: 5px;
        font-size: 14px;
    }
    .counter-container{
        border: 1px solid var(--theme-deafult);
        border-radius: 5px;
        padding: 2px;
    }
</style>
<!-- section start -->
<section class="section-b-space ratio_asos">
   <div class="collection-wrapper">
      <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="product-banner-img">
                    @if(!empty($vendor->banner))
                        <img alt="" src="{{$vendor->banner['image_fit'] . '1920/1080' . $vendor->banner['image_path']}}">
                    @endif
                </div>
                <div class="product-bottom-bar">
                  <div class="row">
                    <div class="col-md-8 col-lg-5 order-0">
                        <div class="card-box vendor-details-left px-2 py-3">
                            <div class="d-sm-flex">
                                <div class="mr-sm-1 text-center text-sm-left mb-2 mb-sm-0">
                                    <img src="{{$vendor->logo['image_fit'] . '120/120' . $vendor->logo['image_path']}}" class="rounded-circle avatar-lg" alt="profile-image" style="mini-width:120px">
                                </div>
                                <div class="ml-sm-1">
                                    <h3>{{$vendor->name}}</h3>
                                    <ul class="vendor-info">
                                        <li class="d-block food-items">
                                        <i class="icon-ic_eat"></i>
                                            @forelse($listData as $key => $data)
                                                {{ $data->category->translation_one->name . (( $key !=  count($listData)-1 ) ? ',' : '') }}
                                            @empty
                                            @endforelse
                                            {{--<a href="#">Pizza</a>, <a href="#">Fast Food</a>, <a href="#">Beverages</a>--}}
                                        </li>
                                        <li class="d-block vendor-location">
                                            <i class="icon-location"></i> {{$vendor->address}}
                                        </li>
                                        <li class="d-block vendor-timing">
                                            <i class="icon-time"></i>
                                                @if(($vendor->is_vendor_closed == 0) && ($vendor->show_slot == 0))
                                                    {{$vendor->opening_time}} – {{$vendor->closing_time}}
                                                    <span class="badge badge-success">Open</span>
                                                @elseif(($vendor->is_vendor_closed == 0) && ($vendor->show_slot == 1))
                                                    24 x 7 <span class="badge badge-success">Open</span>
                                                @else
                                                    <span class="badge badge-danger">Closed</span>
                                                @endif
                                            </span>
                                            {{-- <span data-toggle="tooltip" data-placement="right" title="Tooltip on right"><i class="fa fa-exclamation-circle" aria-hidden="true"></i></span>
                                            <span class="tooltip-text d-none">Mon-Sun : 11am - 11pm</span>--}}
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-xl-5 order-lg-1 order-2">
                        <div class="vendor-search-bar">
                           <div class="radius-bar w-100">
                              <div class="search_form d-flex align-items-center justify-content-between">
                                 <button class="btn"><i class="fa fa-search" aria-hidden="true"></i></button>
                                 <input class="form-control border-0 typeahead" type="search" placeholder="{{__('Search')}}" id="vendor_search_box">
                              </div>
                              <div class="list-box style-4" style="display:none;" id="search_box_main_div">
                              </div>
                           </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-3 col-xl-2 order-xl-2 order-1">
                        <div class="vendor-reviwes">
                           <div class="row">
                              <div class="col-12 d-flex align-items-center">
                                  @if($vendor->vendorRating > 0)
                                    <div class="rating-text-box ml-sm-auto">
                                        <span>{{ $vendor->vendorRating }}</span>
                                        <i class="fa fa-star" aria-hidden="true"></i>
                                    </div>
                                  @endif
                                 {{--<div class="review-text">
                                    <div class="reviw-number">409</div>
                                    <div class="reviews-text">Delivery Reviews</div>
                                 </div>--}}
                              </div>
                           </div>
                        </div>
                    </div>
                  </div>
               </div>
            </div>
        </div>
        <div class="position-relative">
            <div class="categories-product-list">

                <a id="side_menu_toggle" class="d-md-none d-flex" href="javascript:void(0)">
                   <div class="manu-bars">
                       <span class="bar-line"></span>
                       <span class="bar-line"></span>
                       <span class="bar-line"></span>
                   </div>
                    <span>Menu</span>
                </a>

               <div class="row">
                  <div class="col-md-4"></div>
                  <div class="col-md-8"></div>
                  <div class="col-12">
                    <hr>
                    <div class="row vendor-products-wrapper">
                        <div class="col-sm-4 col-lg-3 border-right">
                            <nav class="scrollspy-menu">
                                <ul>
                                    @forelse($listData as $key => $data)
                                        <li><a href="#{{$data->category->slug}}">{{$data->category->translation_one->name}} ({{$data->products_count}})</a></li>
                                    @empty
                                    @endforelse
                                </ul>
                            </nav>
                        </div>
                        <div class="col-md-8 col-lg-6">
                            @forelse($listData as $key => $data)
                            <section class="scrolling_section" id="{{$data->category->slug}}">
                                @if(!empty($data->products))
                                    <h2 class="category-head mt-0 mb-3">{{$data->category->translation_one->name}} ({{$data->products_count}})</h2>
                                    @forelse($data->products as $prod)
                                    <div class="row cart-box-outer product_row classes_wrapper no-gutters mb-3" data-p_sku="{{ $prod->sku }}" data-slug="{{ $prod->url_slug }}">
                                        <div class="col-2">
                                            <div class="class_img product_image">
                                                <img src="{{ $prod->product_image }}" alt="{{ $prod->translation_title }}">
                                            </div>
                                        </div>
                                        <div class="col-10">
                                            <div class="row price_head pl-2">
                                                <div class="col-sm-12 pl-2">
                                                    <div class="d-flex align-items-start justify-content-between">
                                                        <h5 class="mt-0">
                                                            {{$prod->translation_title}}
                                                        </h5>
                                                        <div class="product_variant_quantity_wrapper">
                                                        @php
                                                            $data = $prod;
                                                            $productVariantInCart = 0;
                                                            $productVariantIdInCart = 0;
                                                            $cartProductId = 0;
                                                            $vendor_id = 0;
                                                            $product_id = 0;
                                                            $variant_price = 0;
                                                            $variant_quantity = $prod->variant_quantity;
                                                            $isAddonExist = 0;
                                                            if(count($data->addOn) > 0){
                                                                $isAddonExist = 1;
                                                            }
                                                        @endphp

                                                        @foreach($data->variant as $var)
                                                            @if(isset($var->checkIfInCart) && (count($var->checkIfInCart) > 0))
                                                                @php
                                                                    $productVariantInCart = 1;
                                                                    $productVariantIdInCart = $var->checkIfInCart['0']['variant_id'];
                                                                    $cartProductId = $var->checkIfInCart['0']['id'];
                                                                    $variant_quantity = $var->checkIfInCart['0']['quantity'];
                                                                    $vendor_id = $data->vendor_id;
                                                                    $product_id = $data->id;
                                                                    $variant_price = $var->price * $data->variant_multiplier;
                                                                @endphp
                                                                @break;
                                                            @endif
                                                        @endforeach

                                                        @if($vendor->is_vendor_closed == 0)
                                                            @if($productVariantInCart > 0)
                                                                <a class="add-cart-btn add_vendor_product" style="display:none;" id="add_button_href{{$cartProductId}}" data-variant_id="{{$productVariantIdInCart}}" data-add_to_cart_url="{{ route('addToCart') }}" data-vendor_id="{{$vendor_id}}" data-product_id="{{$product_id}}" data-addon="{{$isAddonExist}}" href="javascript:void(0)">Add</a>
                                                                <div class="number" id="show_plus_minus{{$cartProductId}}">
                                                                    <span class="minus qty-minus-product" data-parent_div_id="show_plus_minus{{$cartProductId}}" data-id="{{$cartProductId}}" data-base_price="{{$variant_price}}" data-vendor_id="{{$vendor_id}}">
                                                                        <i class="fa fa-minus" aria-hidden="true"></i>
                                                                    </span>
                                                                    <input style="text-align:center;width: 80px;margin:auto;height: 24px;padding-bottom: 3px;" placeholder="1" type="text" value="{{$variant_quantity}}" class="input-number" step="0.01" id="quantity_ondemand_{{$cartProductId}}" readonly>
                                                                    <span class="plus qty-plus-product"  data-id="{{$cartProductId}}" data-base_price="{{$variant_price}}" data-vendor_id="{{$vendor_id}}">
                                                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                                                    </span>
                                                                </div>
                                                            @else
                                                                @if($variant_quantity > 0)
                                                                <a class="add-cart-btn add_vendor_product" id="aadd_button_href{{$data->id}}" data-variant_id="{{$data->variant[0]->id}}" data-add_to_cart_url="{{ route('addToCart') }}" data-vendor_id="{{$data->vendor_id}}" data-product_id="{{$data->id}}" data-addon="{{$isAddonExist}}" href="javascript:void(0)">Add</a>
                                                                <div class="number" style="display:none;" id="ashow_plus_minus{{$data->id}}">
                                                                    <span class="minus qty-minus-product"  data-parent_div_id="show_plus_minus{{$data->id}}" readonly data-id="{{$data->id}}" data-base_price="{{$data->variant_price * $data->variant_multiplier}}" data-vendor_id="{{$data->vendor_id}}">
                                                                        <i class="fa fa-minus" aria-hidden="true"></i>
                                                                    </span>
                                                                    <input style="text-align:center;width: 80px;margin:auto;height: 24px;padding-bottom: 3px;" id="quantity_ondemand_d{{$data->id}}" readonly placeholder="1" type="text" value="1" class="input-number input_qty" step="0.01">
                                                                    <span class="plus qty-plus-product"  data-id="" data-base_price="{{$data->variant_price * $data->variant_multiplier}}" data-vendor_id="{{$data->vendor_id}}">
                                                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                                                    </span>
                                                                </div>
                                                                @else
                                                                <span class="text-danger">Out of stock</span>
                                                                @endif
                                                            @endif
                                                            @if( ($isAddonExist > 0) && ($variant_quantity > 0) )
                                                                <div class="customizable-text">customizable</div>
                                                            @endif
                                                        @endif
                                                        </div>
                                                    </div>
                                                    @if($prod->averageRating > 0)
                                                        <div class="rating-text-box">
                                                            <span>{{$prod->averageRating}} </span>
                                                            <i class="fa fa-star" aria-hidden="true"></i>
                                                        </div>
                                                    @endif
                                                    <p class="mb-1 product_price">
                                                        {{Session::get('currencySymbol').(number_format($prod->variant_price * $prod->variant_multiplier, 2, '.', ''))}}
                                                        @if($prod->variant[0]->compare_at_price > 0 )
                                                            <span class="org_price ml-1 font-14">{{Session::get('currencySymbol').(number_format($prod->variant[0]->compare_at_price * $prod->variant_multiplier, 2, '.', ''))}}</span>
                                                        @endif
                                                    </p>
                                                    <div class="member_no d-block mb-0">
                                                        <span>{!! $prod->translation_description !!}</span>
                                                    </div>
                                                    <div id="product_variant_options_wrapper">
                                                        @if(!empty($prod->variantSet))
                                                            @php
                                                                $selectedVariant = ($productVariantIdInCart > 0) ? $productVariantIdInCart : $prod->variant_id;
                                                            @endphp
                                                            @foreach($prod->variantSet as $key => $variant)
                                                                @if($variant->type == 1 || $variant->type == 2)
                                                                <?php $var_id = $variant->variant_type_id; ?>
                                                                <select name="{{'var_'.$var_id}}" vid="{{$var_id}}" class="changeVariant dataVar{{$var_id}}">
                                                                    <option value="" disabled>{{$variant->title}}</option>
                                                                        @foreach($variant->option2 as $k => $optn)
                                                                            <?php
                                                                                $opt_id = $optn->variant_option_id;
                                                                                $selected = ($selectedVariant == $optn->product_variant_id) ? 'selected' : '';
                                                                            ?>
                                                                            <option value="{{$opt_id}}" {{$selected}}>{{$optn->title}}</option>
                                                                        @endforeach
                                                                </select>
                                                                @else
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                    <div id="variant_response">
                                                        <span class="text-danger mb-2 mt-2 font-14"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @empty
                                    @endforelse
                                @else
                                    <h4 class="mt-3 mb-3 text-center">No product found</h4>
                                @endif
                            </section>
                            @empty
                                <h4 class="mt-3 mb-3 text-center">No product found</h4>
                            @endforelse
                        </div>
                        <div class="col-12 col-lg-3 d-lg-inline-block d-none">
                            <div class="card-box p-0 cart-main-box">
                                <div class="p-2 d-flex align-items-center justify-content-between border-bottom">
                                    <h4 class="right-card-title">Cart</h4>
                                </div>
                                <div class="cart-main-box-inside d-flex align-items-center">
                                    <div class="spinner-box">
                                        <div class="circle-border">
                                            <div class="circle-core"></div>
                                        </div>
                                    </div>
                                    <!-- <div class="p-2 border-top">
                                        <h5>Cottonworth Classic Cuvée 75cl</h5>
                                        <div class="qty-box mt-3 mb-2">
                                            <div class="input-group">
                                                <span class="input-group-prepend">
                                                    <button type="button" class="btn quantity-left-minus" data-type="minus" data-field=""><i class="ti-angle-left"></i>
                                                    </button>
                                                </span>
                                                <input type="text" name="quantity" id="quantity" class="form-control input-qty-number quantity_count" value="1">
                                                <span class="input-group-prepend quant-plus">
                                                    <button type="button" class="btn quantity-right-plus " data-type="plus" data-field="">
                                                        <i class="ti-angle-right"></i>
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="cart-sub-total d-flex align-items-center justify-content-between">
                                        <span>Subtotalll</span>
                                        <span>£ 10.50</span>
                                    </div> -->

                                    <div class="show-div shopping-cart flex-fill" id="header_cart_main_ul_ondemand"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>
<script type="text/template" id="header_cart_template_ondemand">
    <ul class="pl-2 pr-2 pb-2 pt-0">
    <% _.each(cart_details.products, function(product, key){%>
        <li class="p-0">
            <h6 class="d-flex justify-content-center badge badge-light font-14"><b><%= product.vendor.name %></b></h6>
        </li>

        <% if( (product.isDeliverable != undefined) && (product.isDeliverable == 0) ) { %>
            <li class="border_0">
                <th colspan="7">
                    <div class="text-danger">
                        Products for this vendor are not deliverable at your area. Please change address or remove product.
                    </div>
                </th>
            </li>
            <% } %>
        <% _.each(product.vendor_products, function(vendor_product, vp){%>
            <li class="p-0" id="cart_product_<%= vendor_product.id %>" data-qty="<%= vendor_product.quantity %>">
                <div class='media-body'>
                    <h6 class="d-flex align-items-center justify-content-between m-0">
                        <span class="ellips"><%= vendor_product.quantity %>x <%= vendor_product.product.translation_one ? vendor_product.product.translation_one.title :  vendor_product.product.sku %></span>
                        <span>
                            {{Session::get('currencySymbol')}} <%= vendor_product.pvariant.price %>
                            <a class="action-icon remove_product_via_cart text-danger" data-product="<%= vendor_product.id %>" data-vendor_id="<%= vendor_product.vendor_id %>">
                                <i class="fa fa-trash-o" aria-hidden="true"></i>
                            </a>
                        </span>
                    </h6>
                </div>
            </li>

            <% if(vendor_product.addon.length != 0) { %>
                <div class="row align-items-md-center">
                    <div class="col-12">
                        <h6 class="m-0 font-12"><b>{{__('Add Ons')}}</b></h6>
                    </div>
                </div>
                <% _.each(vendor_product.addon, function(addon, ad){%>
                <div class="row mb-1">
                    <div class="col-md-6 col-sm-4 items-details text-left">
                        <p class="m-0 font-14 p-0"><%= vendor_product.quantity %>x <%= addon.option.title %></p>
                    </div>
                    <div class="col-md-3 col-sm-4 text-center">
                        <div class="extra-items-price font-14">{{Session::get('currencySymbol')}} <%= addon.option.price_in_cart %></div>
                    </div>
                    <div class="col-md-3 col-sm-4 text-right">
                        <div class="extra-items-price font-14 mr-3">{{Session::get('currencySymbol')}} <%= addon.option.quantity_price %></div>
                    </div>
                </div>
                <% }); %>
            <% } %>

        <% }); %>
        <% if(product.delivery_fee_charges > 0) { %>
            <div class="row justify-content-between">
                <div class="col-md-6 col-sm-6 text-left">
                    <h6 class="m-0 font-14"><b>{{__('Delivery fee')}}</b></h6>
                </div>
                <div class="col-md-6 col-sm-6 text-right">
                    <div class="extra-items-price font-14 mr-3">{{Session::get('currencySymbol')}} <%= product.delivery_fee_charges %></div>
                </div>
            </div>
        <% } %>
        <hr class="my-2">
    <% }); %>

    <h5 class="d-flex align-items-center justify-content-between pb-2">{{__('PRICE DETAILS')}} </h5>
    <li class="p-0">
        <div class='media-body'>
            <h6 class="d-flex align-items-center justify-content-between">
                <span class="ellips">{{__('Price')}}</span>
                <span>{{Session::get('currencySymbol')}} <%= cart_details.gross_amount %></span>
            </h6>
        </div>
    </li>

    <li class="p-0">
        <div class='media-body'>
            <h6 class="d-flex align-items-center justify-content-between">
                <span class="ellips">{{__('Tax')}}</span>
                <span>{{Session::get('currencySymbol')}} <%= cart_details.total_taxable_amount %></span>
            </h6>
        </div>
    </li>

    <% if(cart_details.total_subscription_discount != undefined) { %>
     <li class="p-0">
        <div class='media-body'>
            <h6 class="d-flex align-items-center justify-content-between">
                <span class="ellips">{{__('Subscription Discount')}}</span>
                <span>{{Session::get('currencySymbol')}} <%= cart_details.total_subscription_discount %></span>
            </h6>
        </div>
    </li>
    <% } %>

    <% if(cart_details.loyalty_amount > 0) { %>
    <li class="p-0">
        <div class='media-body'>
            <h6 class="d-flex align-items-center justify-content-between">
                <span class="ellips">{{__('Loyalty Amount')}} </span>
                <span>{{Session::get('currencySymbol')}} <%= cart_details.loyalty_amount %></span>
            </h6>
        </div>
    </li>
    <% } %>

    <% if(cart_details.wallet_amount_used > 0) { %>
    <li class="p-0">
        <div class='media-body'>
            <h6 class="d-flex align-items-center justify-content-between">
                <span class="ellips">{{__('Wallet Amount')}} </span>
                <span>{{Session::get('currencySymbol')}} <%= cart_details.wallet_amount_used %></span>
            </h6>
        </div>
    </li>
    <% } %>
    </ul>
    <div class="cart-sub-total d-flex align-items-center justify-content-between">
        <span>{{__('Total')}}</span>
        <span>{{Session::get('currencySymbol')}} <%= cart_details.total_payable_amount %></span>
    </div>
    <a class="checkout-btn text-center d-block" href="{{route('showCart')}}">Checkout</a>
</script>
<script type="text/template" id="empty_cart_template">
    <div class="row">
        <div class="col-12 text-center pb-3">
            <img class="w-50 pt-3 pb-1" src="{{ asset('front-assets/images/ic_emptycart.svg') }}" alt="">
            <h5>Your cart is empty<br/>{{ __("Add an item to begin") }}</h5>
        </div>
    </div>
</script>
<script type="text/template" id="variant_image_template">
    <img src="<%= media.image_fit %>300/300<%= media.image_path %>" alt="">
</script>
<script type="text/template" id="variant_template">
    <% if(variant.product.inquiry_only == 0) { %>
        <%= variant.productPrice %>
        <% if(variant.compare_at_price > 0 ) { %>
            <span class="org_price ml-1 font-14">{{Session::get('currencySymbol')}} <%= variant.compare_at_price %></span>
        <% } %>
    <% } %>
</script>
<script type="text/template" id="variant_quantity_template">
    <% if(variant.quantity > 0){ %>
        <% if(variant.check_if_in_cart != '') { %>
            <a class="add-cart-btn add_vendor_product" style="display:none;" id="add_button_href<%= variant.check_if_in_cart.id %>" data-variant_id="<%= variant.id %>" data-add_to_cart_url="{{ route('addToCart') }}" data-vendor_id="<%= variant.check_if_in_cart.vendor_id %>" data-product_id="<%= variant.product_id %>" href="javascript:void(0)">Add</a>
            <div class="number" id="show_plus_minus<%= variant.check_if_in_cart.id %>">
                <span class="minus qty-minus-product"  data-parent_div_id="show_plus_minus<%= variant.check_if_in_cart.id %>" data-id="<%= variant.check_if_in_cart.id %>" data-base_price="<%= variant.price * variant.variant_multiplier %>" data-vendor_id="<%= variant.check_if_in_cart.vendor_id %>">
                    <i class="fa fa-minus" aria-hidden="true"></i>
                </span>
                <input style="text-align:center;width: 80px;margin:auto;height: 24px;padding-bottom: 3px;" placeholder="1" type="text" value="<%= variant.check_if_in_cart.quantity %>" class="input-number" step="0.01" id="quantity_ondemand_<%= variant.check_if_in_cart.id %>" readonly>
                <span class="plus qty-plus-product"  data-id="<%= variant.check_if_in_cart.id %>" data-base_price="<%= variant.price * variant.variant_multiplier %>" data-vendor_id="<%= variant.check_if_in_cart.vendor_id %>">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                </span>
            </div>
        <% }else{ %>
            <a class="add-cart-btn add_vendor_product" id="aadd_button_href<%= variant.product_id %>" data-variant_id="<%= variant.id %>" data-add_to_cart_url="{{ route('addToCart') }}" data-vendor_id="<%= variant.product.vendor_id %>" data-product_id="<%= variant.product_id %>" data-addon="<%= variant.isAddonExist %>" href="javascript:void(0)">Add</a>
            <div class="number" style="display:none;" id="ashow_plus_minus<%= variant.product_id %>">
                <span class="minus qty-minus-product"  data-parent_div_id="show_plus_minus<%= variant.product_id %>" readonly data-id="<%= variant.product_id %>" data-base_price="<%= variant.price * variant.variant_multiplier %>" data-vendor_id="<%= variant.product.vendor_id %>">
                    <i class="fa fa-minus" aria-hidden="true"></i>
                </span>
                <input style="text-align:center;width: 80px;margin:auto;height: 24px;padding-bottom: 3px;" id="quantity_ondemand_d<%= variant.product_id %>" readonly placeholder="1" type="text" value="1" class="input-number input_qty" step="0.01">
                <span class="plus qty-plus-product"  data-id="" data-base_price="<%= variant.price * variant.variant_multiplier %>" data-vendor_id="<%= variant.product.vendor_id %>">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                </span>
            </div>
        <% } %>
        <% if(variant.isAddonExist > 0){ %>
            <div class="customizable-text">customizable</div>
        <% } %>
    <% }else{ %>
        <span class="text-danger">Out of stock</span>
    <% } %>
</script>
<script type="text/template" id="addon_template">
    <% if(addOnData != ''){ %>
        <% if(addOnData.product_image){ %>
            <div class="d-flex" style="max-height:200px">
                <img class="w-100" src="<%= addOnData.product_image %>" alt=""  style="object-fit:cover">
            </div>
        <% } %>
        <div class="modal-header">
            <div class="d-flex flex-column">
                <h5 class="modal-title" id="product_addonLabel"><%= addOnData.translation_title %></h5>
                <% if(addOnData.averageRating > 0){ %>
                <div class="rating-text-box justify-content-start" style="width: max-content;">
                    <span><%= addOnData.averageRating %></span>
                    <i class="fa fa-star" aria-hidden="true"></i>
                </div>
                <% } %>
                <span><small><%= addOnData.translation_description %></small></span>
            </div>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body p-0">
            <% _.each(addOnData.add_on, function(addon, key1){ %>
                <div class="border-product border-top">
                    <div class="addon-product" style="padding: 16px;">
                        <h4 addon_id="<%= addon.addon_id %>" class="header-title productAddonSet mb-0"><%= addon.title %></h4>
                        <div class="addonSetMinMax mb-2">
                            <%
                                var min_select = '';
                                if(addon.min_select > 0){
                                    min_select = 'Minimun ' + addon.min_select;
                                }
                                var max_select = '';
                                if(addon.max_select > 0){
                                    max_select = 'Maximum ' + addon.max_select;
                                }
                                if( (min_select != '') && (max_select != '') ){
                                    min_select = min_select + ' and ';
                                }
                            %>
                            <% if( (min_select != '') || (max_select != '') ) { %>
                                <small><%=min_select + max_select %> Selections allowed</small>
                            <% } %>
                        </div>
                        <div class="productAddonSetOptions" data-min="<%= addon.min_select %>" data-max="<%= addon.max_select %>" data-addonset-title="<%= addon.title %>">
                            <% _.each(addon.setoptions, function(option, key2){ %>
                                <div class="checkbox-success d-flex mb-1">
                                    <label class="pr-2 mb-0 flex-fill font-14" for="inlineCheckbox_<%= key1 %>_<%= key2 %>">
                                        <%= option.title %>
                                    </label>
                                    <div>
                                        <span class="addon_price mr-1 font-14">$<%= option.price %></span>
                                        <input type="checkbox" id="inlineCheckbox_<%= key1 %>_<%= key2 %>" class="product_addon_option" name="addonData[<%= key1 %>][]" addonId="<%= addon.addon_id %>" addonOptId="<%= option.id %>" addonPrice="<%= option.price %>">
                                    </div>
                                </div>
                            <% }); %>
                        </div>
                    </div>
                </div>
            <% }); %>
            <div class="addon_response text-danger font-14 d-none" style="padding:0 16px"></div>
        </div>
        <div class="modal-footer flex-nowrap align-items-center">
            <div class="counter-container d-flex align-items-center">
                <span class="minus qty-action" >
                    <i class="fa fa-minus" aria-hidden="true"></i>
                </span>
                <input style="text-align:center; width:60px; height:24px; padding-bottom: 3px; border:none" placeholder="1" type="text" value="1" class="addon-input-number" step="1" readonly>
                <span class="plus qty-action" >
                    <i class="fa fa-plus" aria-hidden="true"></i>
                </span>
            </div>
            <input type="hidden" id="addonVariantPriceVal" value="<%= addOnData.variant_price %>">
            <a class="btn btn-solid add-cart-btn flex-fill add_vendor_addon_product" id="add_vendor_addon_product" href="javascript:void(0)" data-variant_id="<%= addOnData.variant[0].id %>" data-add_to_cart_url="{{ route('addToCart') }}" data-vendor_id="<%= addOnData.vendor_id %>" data-product_id="<%= addOnData.id %>">Add {{ Session::get('currencySymbol') }}<span class="addon_variant_price"><%= addOnData.variant_price %></span></a>
        </div>
    <% } %>
</script>
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
<div class="modal fade product-addon-modal" id="product_addon_modal" tabindex="-1" aria-labelledby="product_addonLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

        </div>
    </div>
</div>
@endsection
@section('script')
<script src="{{asset('front-assets/js/rangeSlider.min.js')}}"></script>
<script src="{{asset('front-assets/js/my-sliders.js')}}"></script>
<script>

    var get_product_addon_url = "{{ route('vendorProductAddons') }}"

    jQuery(window).scroll(function() {
        var scroll = jQuery(window).scrollTop();
        if(scroll >= 900) {
            jQuery(".categories-product-list").addClass("fixed-bar");
        } else {
            jQuery(".categories-product-list").removeClass("fixed-bar");
        }
    });

    var addonids = [];
    var addonoptids = [];
    var showChar = 140;
	var ellipsestext = "...";
	var moretext = "Read more";
	var lesstext = "Read less";
	$('.price_head .member_no span').each(function() {
		var content = $(this).html();
		if(content.length > showChar) {

			var firstContent = content.substr(0, showChar);
			var lastContent = content.substr(showChar, content.length - showChar);

			var html = firstContent + '<span class="moreellipses">' + ellipsestext+ '&nbsp;</span><span class="morecontent"><span>' + lastContent + '</span>&nbsp;&nbsp;<a href="" class="morelink">' + moretext + '</a></span>';

			$(this).html(html);
		}

	});

	$(".morelink").click(function(){
		if($(this).hasClass("less")) {
			$(this).removeClass("less");
			$(this).html(moretext);
		} else {
			$(this).addClass("less");
			$(this).html(lesstext);
		}
		$(this).parent().prev().toggle();
		$(this).prev().toggle();
		return false;
	});

</script>
<script>

    var base_url = "{{url('/')}}";
    var place_order_url = "{{route('user.placeorder')}}";
    var payment_stripe_url = "{{route('payment.stripe')}}";
    var user_store_address_url = "{{route('address.store')}}";
    var promo_code_remove_url = "{{ route('remove.promocode') }}";
    var payment_paypal_url = "{{route('payment.paypalPurchase')}}";
    var update_qty_url = "{{ url('product/updateCartQuantity') }}";
    var promocode_list_url = "{{ route('verify.promocode.list') }}";
    var payment_option_list_url = "{{route('payment.option.list')}}";
    var apply_promocode_coupon_url = "{{ route('verify.promocode') }}";
    var payment_success_paypal_url = "{{route('payment.paypalCompletePurchase')}}";
    var getTimeSlotsForOndemand = "{{route('getTimeSlotsForOndemand')}}";
    var update_cart_schedule = "{{route('cart.updateSchedule')}}";
    var showCart = "{{route('showCart')}}";
    var update_addons_in_cart = "{{route('addToCartAddons')}}";
    var vendor_products_page_search_url = "{{ route('vendorProductsSearchResults') }}";
    var addonids = [];
    var addonoptids = [];
    var ajaxCall = 'ToCancelPrevReq';

    $(document).delegate('.changeVariant', 'change', function() {
        var variants = [];
        var options = [];
        var product_variant_url = "{{ route('productVariant', ':sku') }}";
        var sku = $(this).parents('.product_row').attr('data-p_sku');
        var that = this;
        $(that).parents('.product_row').find('.changeVariant').each(function() {
            if (this.val != '') {
                variants.push($(this).attr('vid'));
                options.push($(this).val());
            }
        });
        // console.log(variants);
        // console.log(options);
        // return 0;
        ajaxCall = $.ajax({
            type: "post",
            dataType: "json",
            url: product_variant_url.replace(":sku", sku),
            data: {
                "_token": "{{ csrf_token() }}",
                "variants": variants,
                "options": options,
            },
            beforeSend: function() {
                if (ajaxCall != 'ToCancelPrevReq' && ajaxCall.readyState < 4) {
                    ajaxCall.abort();
                }
            },
            success: function(response) {
                if(response.status == 'Success'){
                    $(that).parents('.product_row').find("#variant_response span").html('');
                    if(response.variant != ''){

                        $(that).parents('.product_row').find(".add-cart-btn").attr('data-variant_id', response.variant.id);

                        $(that).parents('.product_row').find('.product_price').html('');
                        let variant_template = _.template($('#variant_template').html());
                        $(that).parents('.product_row').find('.product_price').append(variant_template({variant:response.variant}));

                        $(that).parents('.product_row').find('.product_variant_quantity_wrapper').html('');
                        let variant_quantity_template = _.template($('#variant_quantity_template').html());
                        $(that).parents('.product_row').find('.product_variant_quantity_wrapper').append(variant_quantity_template({variant:response.variant}));

                        let variant_image_template = _.template($('#variant_image_template').html());

                        $(that).parents('.product_row').find('.product_image').html('');
                        $(that).parents('.product_row').find('.product_image').append(variant_image_template({media:response.variant}));
                    }
                }else{
                    $(that).parents('.product_row').find("#variant_response span").html(response.message);
                    $(that).parents('.product_row').find(".add-cart-btn").hide();
                    $(that).parents('.product_row').find(".product_variant_quantity_wrapper .text-danger").remove();
                }
            },
            error: function(data) {

            },
        });
    });

    $(document).delegate("#vendor_search_box", "input", function () {
        let keyword = $(this).val();
        vendorProductsSearchResults(keyword);
    });
    function vendorProductsSearchResults(keyword){
        // if (keyword.length > 2 || keyword.length == 0) {
            ajaxCall = $.ajax({
                type: "post",
                dataType: 'json',
                url: vendor_products_page_search_url,
                data: { keyword: keyword, vendor: "{{$vendor->id}}", vendor_category: "{{$vendor_category ?? ''}}" },
                beforeSend: function() {
                    if (ajaxCall != 'ToCancelPrevReq' && ajaxCall.readyState < 4) {
                        ajaxCall.abort();
                    }
                },
                success: function (response) {
                    if (response.status == 'Success') {
                        var cart_html = $('.vendor-products-wrapper #header_cart_main_ul_ondemand').html();
                        $('.vendor-products-wrapper').html(response.html);
                        $('.vendor-products-wrapper #header_cart_main_ul_ondemand').html(cart_html);
                    }
                }
            });
        // }
    }

</script>
@endsection
