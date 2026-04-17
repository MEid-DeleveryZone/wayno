@extends('layouts.store', ['title' => "Search Results"])
@section('css')
<style type="text/css">
    .main-menu .brand-logo {
        display: inline-block;
        padding-top: 20px;
        padding-bottom: 20px;
    }

    .slick-track {
        margin-left: 0px;
    }

    .product-box .product-detail h4,
    .product-box .product-info h4 {
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
<section class="section-b-space ratio_asos">
    <div class="collection-wrapper">
        <div class="container">
            <div class="row">
                <div class="collection-content col">
                    <div class="page-main-content">
                        <div class="row">
                            <div class="collection-product-wrapper w-100">
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
                                                        <option value="8" @if($pagiNate==8) selected @endif>Show 8
                                                        </option>
                                                        <option value="12" @if($pagiNate==12) selected @endif>Show 12
                                                        </option>
                                                        <option value="24" @if($pagiNate==24) selected @endif>Show 24
                                                        </option>
                                                        <option value="48" @if($pagiNate==48) selected @endif>Show 48
                                                        </option>
                                                    </select>
                                                </div> --}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <h4>Showing Results for "{{$keyword}}"</h4>
                                <div class="displayProducts">
                                    <div class="product-wrapper-grid">
                                        <div class="row margin-res">
                                            @if(!empty($listData))
                                            @foreach($listData as $key => $data)
                                            <div class="col-xl-3 col-6 col-grid-box mt-3">
                                                <div class="product-box scale-effect mt-0">
                                                    <div class="img-wrapper">
                                                        <div class="front">
                                                            <a href="{{$data['redirect_url']}}"><img class="img-fluid blur-up lazyload" src="{{$data['image_url']}}" alt=""></a>
                                                        </div>
                                                    </div>
                                                    <div class="product-detail">
                                                        <div class="inner_spacing">
                                                            <a href="{{$data['redirect_url']}}">
                                                                <h3>{{$data['name']}}</h3>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                            @else
                                            <div class="col-xl-12 col-12 mt-4">
                                                <h5 class="text-center">No Product Found</h5>
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
    </div>
    </div>
</section>
@endsection
@section('script')
<script src="{{asset('front-assets/js/rangeSlider.min.js')}}"></script>
<script src="{{asset('front-assets/js/my-sliders.js')}}"></script>
@endsection