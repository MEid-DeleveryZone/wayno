@extends('layouts.store', ['title' => $category->translation_name])

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
                        <div class="common-banner"><img alt="" src="{{$category->image['proxy_url'] . '1920/1080' . $category->image['image_path']}}" class="img-fluid blur-up lazyload"></div>
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
                                                    <div class="category-image"><img alt="" src="{{$cate['icon']['proxy_url'] . '100/80' . $cate['icon']['image_path']}}" ></div>
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
                    <div class="theme-card">
                        <h5 class="title-border">{{__('New Product')}}</h5>
                        <div class="offer-slider">
                            @if(!empty($newProducts) && count($newProducts) > 0)
                                @foreach($newProducts as $newProds)

                                <div>
                                    @foreach($newProds as $new)
                                        <?php $imagePath = '';
                                        foreach ($new['media'] as $k => $v) {
                                            $imagePath = $v['image']['path']['proxy_url'].'300/300'.$v['image']['path']['image_path'];
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
                                        @endforeach
                                    </div>

                                @endforeach
                            @endif
                        </div>
                    </div>
                    <!-- side-bar banner end here -->
                </div>
                <div class="collection-content col">
                    <div class="page-main-content">
                        <div class="row">
                            <div class="col-sm-12 px-0">
                                <div class="collection-product-wrapper">
                                    <div class="product-top-filter mb-4">
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
                                    <div class="displayProducts px-0">
                                        <div class="product-wrapper-grid">
                                            <div class="row margin-res">
                                            @if($listData->isNotEmpty())
                                                @foreach($listData as $key => $data)
                                                    @php
                                                    $imagePath = $data->logo['proxy_url'] .'300/300'. $data->logo['image_path'];
                                                    $imagePath2 = $data->banner['proxy_url'] .'300/300'. $data->banner['image_path'];
                                                    if(empty($data->vendor_templete_id) || ($data->vendor_templete_id == 1)){
                                                        $vendor_url = route('categoryVendorProducts', [$category->slug, $data->slug]);
                                                    }elseif($data->vendor_templete_id == 5){
                                                        $vendor_url = route('vendorCategoryProducts', [$data->slug, $category->slug]);
                                                    }else{
                                                        $vendor_url = route('vendorDetail', $data->slug);
                                                    }
                                                    @endphp
                                                    <div class="col-xl-3 col-6 col-grid-box mt-3">

                                                        <a class="suppliers-box d-block w-100 px-2" href="{{$vendor_url}}">
                                                            <div class="suppliers-img-outer">
                                                                <img class="img-fluid mx-auto" src="{{$imagePath}}" alt="">
                                                            </div>
                                                            <div class="supplier-rating">
                                                                <h6 class="mb-1">{{$data->name}}</h6>
                                                                <p title="{{$data->categoriesList}}" class="vendor-cate border-bottom pb-1 mb-1 ellips">{{$data->categoriesList}}</p>
                                                                <div class="product-timing">
                                                                    <small title="{{$data->address}}" class="ellips d-block"><span class="icon-location2"></span> {{$data->address}}</small>
                                                                    @if(isset($data->timeofLineOfSightDistance))
                                                                        <ul class="timing-box mb-1">
                                                                            <li>
                                                                                <small class="d-block"><span class="icon-location2"></span> {{$data->lineOfSightDistance}}</small>
                                                                            </li>
                                                                            <li>
                                                                                <small class="d-block mx-1"><span class="icon-clock"></span> {{$data->timeofLineOfSightDistance}} {{__('min')}}</small>
                                                                            </li>
                                                                        </ul>
                                                                    @endif
                                                                </div>
                                                                @if($client_preference_detail)
                                                                    @if($client_preference_detail->rating_check == 1)
                                                                        @if($data->vendorRating > 0)
                                                                            <ul class="custom-rating m-0 p-0">
                                                                                <?php
                                                                                for($i=0; $i < 5; $i++){
                                                                                    if($i <= $data->vendorRating){
                                                                                        $starFillClass = 'fa-star';
                                                                                    }else{
                                                                                        $starFillClass = 'fa-star-o';
                                                                                    }
                                                                                ?>
                                                                                    <li><i class="fa {{ $starFillClass }}" aria-hidden="true"></i></li>
                                                                                <?php } ?>
                                                                            </ul>
                                                                        @endif
                                                                    @endif
                                                                @endif
                                                            </div>
                                                        </a>

                                                        <!-- <div class="product-box">
                                                            <div class="img-wrapper">
                                                                <div class="front">
                                                                    <a href="{{$vendor_url}}"><img class="img-fluid blur-up lazyload" alt="" src="{{$imagePath}}" width="300" height="300"></a>
                                                                </div>
                                                            </div>
                                                            <div class="product-detail">
                                                                <div class="inner_spacing">
                                                                    <a href="{{$vendor_url}}">
                                                                        <h3>{{$data->name}}</h3>
                                                                    </a>
                                                                    @if($client_preference_detail)
                                                                        @if($client_preference_detail->rating_check == 1)
                                                                            @if($data->vendorRating > 0)
                                                                                <span class="rating">{{ number_format($data->vendorRating, 1, '.', '') }} <i class="fa fa-star text-white p-0"></i></span>
                                                                            @endif
                                                                        @endif
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div> -->
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="col-xl-12 col-12 mt-4"><h5 class="text-center">{{__('Details Not Available')}}</h5></div>
                                            @endif
                                            </div>
                                        </div>
                                        @if(count($listData))
                                        <div class="pagination pagination-rounded justify-content-end mb-0">
                                            {{ $listData->links() }}
                                        </div>
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
            }
        });
    }

</script>


@endsection
