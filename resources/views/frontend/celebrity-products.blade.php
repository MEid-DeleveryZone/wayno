@extends('layouts.store', ['title' => 'Celebrity'])
@section('css')
<style type="text/css">
    .main-menu .brand-logo {
        display: inline-block;
        padding-top: 20px;
        padding-bottom: 20px;
    }
    .slick-track{
        margin-left: 0px;
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
<section class="section-b-space ratio_asos">
    <div class="collection-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="top-banner-wrapper text-center">
                        @if(!empty($celebrity->avatar))
                            <div class="common-banner"><img alt="" src="{{$celebrity->avatar['image_fit'] . '1920/1080' . $celebrity->avatar['image_path']}}" class="img-fluid blur-up lazyload"></div>
                        @endif
                        <div class="top-banner-content small-section">
                            <h4>{{ $celebrity->name }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-3 collection-filter">
                    <div class="collection-filter-block">
                        <div class="collection-mobile-back">
                            <span class="filter-back">
                                <i class="fa fa-angle-left" aria-hidden="true"></i>{{__('Back')}}
                            </span>
                        </div>
                        <div class="collection-collapse-block open">
                            @if(!empty($category->brands) && count($category->brands) > 0)
                            <h3 class="collapse-block-title">{{__('Brand')}}</h3>
                            <div class="collection-collapse-block-content">
                                <div class="collection-brand-filter">
                                    @foreach($category->brands as $key => $val)
                                        <div class="custom-control custom-checkbox collection-filter-checkbox">
                                            <input type="checkbox" class="custom-control-input productFilter" fid="{{$val->id}}" used="brands" id="brd{{$val->id}}">
                                            @foreach($val->translation as $k => $v)
                                                <label class="custom-control-label" for="brd{{$val->id}}">{{$v->title}}</label>
                                            @endforeach
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                        @if(!empty($variantSets) && count($variantSets) > 0)
                          @foreach($variantSets as $key => $sets)
                            <div class="collection-collapse-block border-0 open">
                                <h3 class="collapse-block-title">{{$sets->title}}</h3>
                                <div class="collection-collapse-block-content">
                                    <div class="collection-brand-filter">
                                        
                                    @if($sets->type == 2)
                                        @foreach($sets->options as $ok => $opt)
                                            <div class="chiller_cb small_label d-inline-block color-selector">
                                                <?php $checkMark = ($key == 0) ? 'checked' : ''; ?>
                                                <input class="custom-control-input productFilter" type="checkbox" {{$checkMark}} id="Opt{{$key.'-'.$opt->id}}" fid="{{$sets->variant_type_id}}" used="variants" optid="{{$opt->id}}">
                                                <label for="Opt{{$key.'-'.$opt->id}}"></label>
                                                @if(strtoupper($opt->hexacode) == '#FFF' || strtoupper($opt->hexacode) == '#FFFFFF')
                                                    <span style="background: #FFFFFF; border-color:#000;" class="check_icon white_check"></span>
                                                @else
                                                    <span class="check_icon" style="background:{{$opt->hexacode}}; border-color: {{$opt->hexacode}};"></span>
                                                @endif                                        
                                            </div>
                                        @endforeach
                                    @else
                                        @foreach($sets->options as $ok => $opt)
                                            <div class="custom-control custom-checkbox collection-filter-checkbox">
                                                <input type="checkbox" class="custom-control-input productFilter" id="Opt{{$key.'-'.$opt->id}}" fid="{{$sets->variant_type_id}}" type="variants" optid="{{$opt->id}}">
                                                <label class="custom-control-label" for="Opt{{$key.'-'.$opt->id}}">{{$opt->title}}</label>
                                            </div>
                                        @endforeach
                                    @endif
                                    </div>
                                </div>
                            </div>

                          @endforeach
                        @endif
                        <div class="collection-collapse-block border-0 open">
                            <h3 class="collapse-block-title">{{__('Price')}}</h3>
                            <div class="collection-collapse-block-content">
                                <div class="wrapper mt-3">
                                    <div class="range-slider">
                                        <input type="text" class="js-range-slider rangeSliderPrice" value="" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="theme-card">
                        <h5 class="title-border">new product</h5>
                        <div class="offer-slider">
                            @if(!empty($newProducts) && count($newProducts) > 0)
                                @foreach($newProducts as $newProds)
                                    <div>
                                    @foreach($newProds as $new)
                                        <?php $imagePath = '';
                                        foreach ($new['media'] as $k => $v) {
                                            $imagePath = $v['image']['path']['image_fit'].'300/300'.$v['image']['path']['image_path'];
                                        } ?>

                                        <a class="common-product-box scale-effect text-center border-bottom pb-2 mt-2" href="{{route('productDetail', $new['url_slug'])}}">
                                            <div class="img-outer-box position-relative">
                                                <img src="{{$imagePath}}" alt="">
                                            </div>    
                                            <div class="media-body align-self-center">
                                                <div class="inner_spacing px-0">
                                                    <div class="product-description">
                                                        <h3 class="mb-0 mt-2">{{ $new['translation_title'] }}</h3>
                                                        <p>{{$new['vendor']['name']}}</p>
                                                        <p class="pb-1">In {{$new['category_name']}}</p>
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <b>
                                                                @if($new['inquiry_only'] == 0)
                                                                    <?php $multiply = $new['variant_multiplier']; ?>
                                                                    {{ Session::get('currencySymbol').' '.(number_format($new['variant_price'] * $multiply,2))}}
                                                                @endif
                                                            </b>

                                                            @if($client_preference_detail)
                                                                @if($client_preference_detail->rating_check == 1)
                                                                    @if($new['averageRating'] > 0)
                                                                        <div class="rating-box">
                                                                            <i class="fa fa-star" aria-hidden="true"></i>
                                                                            <span>{{ $new['averageRating'] }}</span>
                                                                        </div>
                                                                    @endif
                                                                @endif
                                                            @endif  
                                                        </div>                       
                                                    </div>
                                                </div>
                                            </div>
                                        </a>

                                        <!-- <div class="media">
                                            <a href="{{route('productDetail', $new['url_slug'])}} "><img class="img-fluid blur-up lazyload" style="max-width: 200px;" src="{{$imagePath}}" alt="" ></a>
                                            <div class="media-body align-self-center">
                                                <div class="inner_spacing">
                                                    <a href="{{route('productDetail', $new['url_slug'])}}">
                                                        <h3>{{ $new['translation_title'] }}</h3>
                                                    </a>
                                                    <h6><b>{{$new['vendor']['name']}}</b></h6>
                                                    @if($new['inquiry_only'] == 0)
                                                    <h4 class="mt-1">
                                                        <//?php $multiply = $new['variant_multiplier']; ?>
                                                        {{ Session::get('currencySymbol').' '.(number_format($new['variant_price'] * $multiply,2))}} </h4>
                                                    @endif
                                                    @if($client_preference_detail)
                                                        @if($client_preference_detail->rating_check == 1)
                                                            @if($new['averageRating'] > 0)
                                                                <span class="rating">{{ $new['averageRating'] }} <i class="fa fa-star text-white p-0"></i></span>
                                                            @endif
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                        </div> -->
                                    @endforeach
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
                <div class="collection-content col">
                    <div class="page-main-content">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="collection-product-wrapper">
                                    <div class="product-top-filter">
                                        <div class="row">
                                            <div class="col-xl-12">
                                                <div class="filter-main-btn">
                                                    <span class="filter-btn btn btn-theme">
                                                        <i class="fa fa-filter" aria-hidden="true"></i> {{__('Filter')}}</span>
                                                    </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="product-filter-content border-left">
                                                    <div class="collection-view">
                                                        <ul>
                                                            <li><i class="fa fa-th grid-layout-view"></i></li>
                                                            <li><i class="fa fa-list-ul list-layout-view"></i></li>
                                                        </ul>
                                                    </div>
                                                    {{-- <div class="collection-grid-view">
                                                        <ul>
                                                            <li><img src="{{asset('front-assets/images/icon/2.png')}}" alt="" class="product-2-layout-view"></li>
                                                            <li><img src="{{asset('front-assets/images/icon/3.png')}}" alt="" class="product-3-layout-view"></li>
                                                            <li><img src="{{asset('front-assets/images/icon/4.png')}}" alt="" class="product-4-layout-view"></li>
                                                            <li><img src="{{asset('front-assets/images/icon/6.png')}}" alt="" class="product-6-layout-view"></li>
                                                        </ul>
                                                    </div> --}}
                                                    {{-- <div class="product-page-per-view">
                                                        <?php $pagiNate = (Session::has('cus_paginate')) ? Session::get('cus_paginate') : 8; ?>
                                                        <select class="customerPaginate">
                                                            <option value="8" @if($pagiNate == 8) selected @endif>Show 8 
                                                            </option>
                                                            <option value="12" @if($pagiNate == 12) selected @endif>Show 12 
                                                            </option>
                                                            <option value="24" @if($pagiNate == 24) selected @endif>Show 24
                                                            </option>
                                                            <option value="48" @if($pagiNate == 48) selected @endif>Show 48
                                                            </option>
                                                        </select>
                                                    </div> --}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="displayProducts">
                                        <div class="product-wrapper-grid">
                                            <div class="row margin-res">
                                              @if($celebrity->products->isNotEmpty())
                                                @foreach($celebrity->products as $key => $data)
                                                <?php
                                                $imagePath = $imagePath2 = '';
                                                $mediaCount = count($data->media);
                                                for ($i = 0; $i < $mediaCount && $i < 2; $i++) {
                                                    if($i == 0){
                                                        $imagePath = $data->media[$i]->image->path['image_fit'].'300/300'.$data->media[$i]->image->path['image_path'];
                                                    }
                                                    $imagePath2 = $data->media[$i]->image->path['image_fit'].'300/300'.$data->media[$i]->image->path['image_path'];
                                                } ?>
                                                <div class="col-xl-3 col-6 col-grid-box mt-3">
                                                    <div class="product-box scale-effect">
                                                        <div class="img-wrapper">
                                                            <div class="front">
                                                                <a href="{{route('productDetail', $data->url_slug)}}"><img class="img-fluid blur-up lazyload" src="{{$imagePath}}" alt=""></a>
                                                            </div>
                                                            <div class="cart-info cart-wrap">
                                                                <button data-toggle="modal" data-target="#addtocart" title="Add to cart"><i class="ti-shopping-cart"></i></button> 
                                                                <a href="javascript:void(0)" title="Add to Wishlist" class="addWishList" proSku="{{$data->sku}}"><i class="ti-heart" aria-hidden="true"></i></a>
                                                            </div>
                                                        </div>
                                                        <div class="media-body align-self-center">
                                                            <div class="inner_spacing">
                                                                <a href="{{route('productDetail', $data->url_slug)}}">
                                                                    <h3>{{ $data->translation_title }}</h3>
                                                                </a>
                                                                <h6 class="mt-0"><b>{{$data->vendor->name}}</b></h6>
                                                                <h4 class="mt-1">{{Session::get('currencySymbol').' '.(number_format($data->variant_price * $data->variant_multiplier,2))}}</h4>
                                                                @if($client_preference_detail)
                                                                    @if($client_preference_detail->rating_check == 1)  
                                                                        @if($data->averageRating > 0)
                                                                            <span class="rating">{{ number_format($data->averageRating, 1, '.', '') }} <i class="fa fa-star text-white p-0"></i></span>
                                                                        @endif
                                                                    @endif
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endforeach
                                              @else
                                                <div class="col-xl-12 col-12 mt-4"><h5 class="text-center">{{ __("No Product Found") }}</h5></div>
                                              @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('script')
<script src="{{asset('front-assets/js/rangeSlider.min.js')}}"></script>
<script src="{{asset('front-assets/js/my-sliders.js')}}"></script>
<script>

    $('.js-range-slider').ionRangeSlider({
        type: 'double',
        grid: false,
        min: 0,
        max: 50000,
        from: 0,
        to: 50000,
        prefix: " "
    });

    var ajaxCall = 'ToCancelPrevReq';
    $('.js-range-slider').change(function(){
        filterProducts();
    });

    $('.productFilter').click(function(){
        filterProducts();
    });

    function filterProducts(){
        var brands = [];
        var variants = [];
        var options = [];
        $('.productFilter').each(function () {
            var that = this;
            if(this.checked == true){
                var forCheck = $(that).attr('used');
                if(forCheck == 'brands'){
                    brands.push($(that).attr('fid'));
                }else{
                    variants.push($(that).attr('fid'));
                    options.push($(that).attr('optid'));
                }
            }
        });

    }

</script>


@endsection