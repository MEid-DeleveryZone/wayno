@extends('layouts.store', ['title' => (!empty($category->translation) && isset($category->translation[0])) ? $category->translation[0]->name : $category->slug])
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
    .product-box .product-detail h4, .product-box .product-info h4{
        font-size: 16px;
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
@if(!empty($category))
@include('frontend.included_files.categories_breadcrumb')
@endif
<section class="section-b-space ratio_asos">
    <div class="collection-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="top-banner-wrapper text-center">
                        @if(!empty($category->image))
                            <div class="common-banner"><img alt="" src="{{$category->image['image_fit'] . '1920/1080' . $category->image['image_path']}}" class="img-fluid blur-up lazyload"></div>
                        @endif
                        <div class="top-banner-content small-section">
                            <h4>{{ $category->translation_name }}</h4>
                            {{-- @if(!empty($category->childs) && count($category->childs) > 0)
                                <div class="row">
                                    <div class="col-12">
                                        
                                        <div class="slide-6 no-arrow">
                                            @foreach($category->childs->toArray() as $cate)
                                            <div class="category-block">
                                                <a href="{{route('categoryDetail', $cate['slug'])}}">
                                                    <div class="category-image"><img alt="" src="{{$cate['icon']['image_fit'] . '300/300' . $cate['icon']['image_path']}}" ></div>
                                                </a>
                                                <div class="category-details">
                                                    <a href="{{route('categoryDetail', $cate['slug'])}}">
                                                        <h5>{{$cate['translation_name']}}</h5>
                                                    </a>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif --}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-3 collection-filter">
                    <!-- side-bar colleps block stat -->
                    <div class="collection-filter-block">
                        <div class="collection-mobile-back">
                            <span class="filter-back">
                                <i class="fa fa-angle-left" aria-hidden="true"></i> {{__('Back')}}
                            </span>
                        </div>
                        @if(!empty($category->brands) && count($category->brands) > 0)
                        <div class="collection-collapse-block open">
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
                        </div>
                        @endif
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
                    </div>
                    <div class="theme-card">
                        <h5 class="title-border">{{__('New Product')}}</h5>
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
                                            <a href="{{route('productDetail', $new['url_slug'])}} "><img class="img-fluid blur-up lazyload" style="max-width: 200px;" src="{{$imagePath}}" alt=""></a>
                                            <div class="media-body align-self-center">
                                                <div class="inner_spacing">
                                                    <a href="{{route('productDetail', $new['url_slug'])}}">
                                                        <h3 class="d-flex align-items-center justify-content-between">
                                                            <label class="mb-0"><b>{{ $new['translation_title'] }}</b></label>
                                                        </h3>
                                                        <h6 class="mt-0"><b>{{$new['vendor']['name']}}</b></h6>
                                                        @if($client_preference_detail)
                                                            @if($client_preference_detail->rating_check == 1)
                                                                @if($new['averageRating'] > 0)
                                                                    <span class="rating">{{ $new['averageRating'] }} <i class="fa fa-star text-white p-0"></i></span>
                                                                @endif
                                                            @endif
                                                        @endif
                                                        @if($new['inquiry_only'] == 0)
                                                            <h4 class="mt-1">
                                                                <//?php $multiply = $new['variant_multiplier']; ?>
                                                                {{ Session::get('currencySymbol').' '.(number_format($new['variant_price'] * $multiply,2))}}
                                                            </h4>
                                                        @endif
                                                    </a>
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
                                                        <i class="fa fa-filter" aria-hidden="true"></i>{{__('Filter')}}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="product-filter-content">
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
                                              @if($listData->isNotEmpty())
                                                @foreach($listData as $key => $data)
                                                <?php $imagePath = $imagePath2 = '';
                                                $mediaCount = count($data->media);
                                                for ($i = 0; $i < $mediaCount && $i < 2; $i++) { 
                                                    if($i == 0){
                                                        $imagePath = $data->media[$i]->image->path['image_fit'].'300/300'.$data->media[$i]->image->path['image_path'];
                                                    }
                                                    $imagePath2 = $data->media[$i]->image->path['image_fit'].'300/300'.$data->media[$i]->image->path['image_path'];
                                                } ?>
                                                <div class="col-xl-3 col-6 col-grid-box mt-3">
                                                    <a href="{{route('productDetail', $data->url_slug)}}" class="product-box scale-effect mt-0">
                                                        <div class="product-image p-0">
                                                            <img class="img-fluid blur-up lazyload" src="{{$imagePath}}" alt="">
                                                        </div>
                                                        <div class="media-body align-self-center">
                                                            <div class="inner_spacing w-100">
                                                                <h3 class="d-flex align-items-center justify-content-between">
                                                                    <label class="mb-0"><b>{{ $data->translation_title }}</b></label>
                                                                    @if($client_preference_detail)
                                                                        @if($client_preference_detail->rating_check == 1)  
                                                                            @if($data->averageRating > 0)
                                                                                <span class="rating">{{ number_format($data->averageRating, 1, '.', '') }} <i class="fa fa-star text-white p-0"></i></span>
                                                                            @endif
                                                                        @endif
                                                                    @endif
                                                                </h3>
                                                                <h6 class="mt-0 mb-1"><b>{{$data->vendor->name}}</b></h6>
                                                                @if (strlen($data->translation_description) >= 65)
                                                                    <p title="{{$data->translation_description}}">{{ substr($data->translation_description, 0, 64)." ..." }}</p>
                                                                @else
                                                                    <p>{{ $data->translation_description }}</p>
                                                                @endif
                                                                @if($data->inquiry_only == 0)
                                                                    <h4 class="mt-1">{{Session::get('currencySymbol').' '.(number_format($data->variant_price * $data->variant_multiplier,2))}}</h4>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </a>
                                                </div>
                                                @endforeach
                                              @else
                                                <div class="col-xl-12 col-12 mt-4"><h5 class="text-center">{{ __('No Product Found') }}</h5></div>
                                              @endif
                                            </div>
                                        </div>
                                        <div class="pagination pagination-rounded justify-content-end mb-0">
                                            @if(!empty($listData))
                                                {{ $listData->links() }}
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
        var range = $('.rangeSliderPrice').val();

        ajaxCall = $.ajax({
            type: "post",
            dataType: "json",
            url: "{{ route('productFilters', $category->id) }}",
            data: {
                "_token": "{{ csrf_token() }}",
                "brands": brands,
                "variants": variants, 
                "options": options,
                "range": range
            },
            beforeSend : function() {
                if(ajaxCall != 'ToCancelPrevReq' && ajaxCall.readyState < 4) {
                    ajaxCall.abort();
                }
            },
            success: function(response) {
                $('.displayProducts').html(response.html);
            },
            error: function (data) {
                //location.reload();
            },
        });
    }
</script>
@endsection