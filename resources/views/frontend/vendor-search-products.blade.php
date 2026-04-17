@if($listData->isNotEmpty())
<div class="col-sm-4 col-lg-3 border-right">
    <nav class="scrollspy-menu">
        <ul>
            @forelse($listData as $key => $data)
                <li @if($key == 0) class="active" @endif><a href="#{{$data->category->slug}}">{{$data->category->translation_one->name}} ({{$data->products_count}})</a></li>
            @empty
            @endforelse
        </ul>
    </nav>
</div>
<div class="col-md-8 col-lg-6">
    @forelse($listData as $key => $data)
    <section class="scrolling_section" id="{{$data->category->slug}}">
        <h2 class="category-head mt-0 mb-3">{{$data->category->translation_one->name}} ({{$data->products_count}})</h2>
        @if(!empty($data->products))
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
                                    $variant_quantity = 0;
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

                                @if($productVariantInCart > 0)
                                    <a class="add-cart-btn add_vendor_product" style="display:none;" id="add_button_href{{$cartProductId}}" data-variant_id="{{$productVariantIdInCart}}" data-add_to_cart_url="{{ route('addToCart') }}" data-vendor_id="{{$vendor_id}}" data-product_id="{{$product_id}}" data-addon="{{$isAddonExist}}" href="javascript:void(0)">Add</a>
                                    <div class="number" id="show_plus_minus{{$cartProductId}}">
                                        <span class="minus qty-minus-ondemand" data-parent_div_id="show_plus_minus{{$cartProductId}}" data-id="{{$cartProductId}}" data-base_price="{{$variant_price}}" data-vendor_id="{{$vendor_id}}">
                                            <i class="fa fa-minus" aria-hidden="true"></i>
                                        </span>
                                        <input style="text-align:center;width: 80px;margin:auto;height: 24px;padding-bottom: 3px;" placeholder="1" type="text" value="{{$variant_quantity}}" class="input-number" step="0.01" id="quantity_ondemand_{{$cartProductId}}" readonly>
                                        <span class="plus qty-plus-ondemand"  data-id="{{$cartProductId}}" data-base_price="{{$variant_price}}" data-vendor_id="{{$vendor_id}}">
                                            <i class="fa fa-plus" aria-hidden="true"></i>
                                        </span>
                                    </div>
                                @else
                                    <a class="add-cart-btn add_vendor_product" id="aadd_button_href{{$data->id}}" data-variant_id="{{$data->variant[0]->id}}" data-add_to_cart_url="{{ route('addToCart') }}" data-vendor_id="{{$data->vendor_id}}" data-product_id="{{$data->id}}" data-addon="{{$isAddonExist}}" href="javascript:void(0)">Add</a>
                                    <div class="number" style="display:none;" id="ashow_plus_minus{{$data->id}}">
                                        <span class="minus qty-minus-ondemand"  data-parent_div_id="show_plus_minus{{$data->id}}" readonly data-id="{{$data->id}}" data-base_price="{{$data->variant_price * $data->variant_multiplier}}" data-vendor_id="{{$data->vendor_id}}">
                                            <i class="fa fa-minus" aria-hidden="true"></i>
                                        </span>
                                        <input style="text-align:center;width: 80px;margin:auto;height: 24px;padding-bottom: 3px;" id="quantity_ondemand_d{{$data->id}}" readonly placeholder="1" type="text" value="1" class="input-number input_qty" step="0.01">
                                        <span class="plus qty-plus-ondemand"  data-id="" data-base_price="{{$data->variant_price * $data->variant_multiplier}}" data-vendor_id="{{$data->vendor_id}}">
                                            <i class="fa fa-plus" aria-hidden="true"></i>
                                        </span>
                                    </div>
                                
                                @endif
                                @if($isAddonExist > 0)
                                    <div class="customizable-text">customizable</div>
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
                                        $selectedVariant = $productVariantIdInCart;
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
        @endif
    </section>
    @empty
    @endforelse
</div>
@else
<div class="col-12 col-lg-9 d-md-inline-block">
    <h4 class="mt-3 mb-3 text-center">{{ __("No result found") }}</h4>
</div>
@endif
<div class="col-12 col-lg-3 d-lg-inline-block d-none">
    <div class="card-box p-0 cart-main-box">                                
        <div class="p-2 d-flex align-items-center justify-content-between border-bottom">
            <h4 class="right-card-title">Cart</h4>
        </div>
        <div class="cart-main-box-inside d-flex align-items-center">
            <div class="spinner-box" style="display:none">
                <div class="circle-border">
                    <div class="circle-core"></div>
                </div>
            </div>
            <div class="show-div shopping-cart flex-fill" id="header_cart_main_ul_ondemand"></div>
        </div>
    </div>
</div>