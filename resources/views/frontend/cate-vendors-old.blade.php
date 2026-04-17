@extends('layouts.store', ['title' => 'Vendor'])

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
    @if(isset($set_template)  && $set_template->template_id == 1)
        @include('layouts.store/left-sidebar-template-one')
        @elseif(isset($set_template)  && $set_template->template_id == 2)
        @include('layouts.store/left-sidebar')
        @else
        @include('layouts.store/left-sidebar-template-one')
        @endif
</header>
<style type="text/css">
    .productVariants .firstChild{
        min-width: 150px;
        text-align: left !important;
        border-radius: 0% !important;
        margin-right: 10px;
        cursor: default;
        border: none !important;
    }
    .product-right .color-variant li, .productVariants .otherChild{
        height: 35px;
        width: 35px;
        border-radius: 50%;
        margin-right: 10px;
        cursor: pointer;
        border: 1px solid #f7f7f7;
        text-align: center;
    }
    .productVariants .otherSize{
        height: auto !important;
        width: auto !important;
        border: none !important;
        border-radius: 0%;
    }
    .product-right .size-box ul li.active {
        background-color: inherit;
        }
</style>
@if(!empty($category->childs))
<section class="section-b-space border-section border-top-0">
    <div class="row">
        <div class="col-12">
            
            <div class="slide-6 no-arrow">
                @foreach($category->childs->toArray() as $cate)
                <div class="category-block">
                    <a href="{{route('categoryDetail', $cate['id'])}}">
                        <div class="category-image"><img alt="" src="{{$cate['icon']['proxy_url'] . '100/80' . $cate['icon']['image_path']}}" ></div>
                    </a>
                    <div class="category-details">
                        <a href="{{route('categoryDetail', $cate['id'])}}">
                            <h5>{{ (!empty($cate->translation) && isset($cate->translation[0])) ? $cate->translation[0]->title : ''}} </h5>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif
<section class="ratio_asos game-product section-b-space">
    <div class="container">

        <div class="row">
            <div class="col">
                <div class="product-5 product-m no-arrow">
                    @if(!empty($listData))
                        @foreach($listData as $key => $data)
                        <div class="product-box">
                            <div class="img-wrapper">
                                <div class="front">
                                    <a href="{{route('vendorDetail', $data->id)}}"><img class="img-fluid blur-up lazyload" alt="" src="{{$data->logo['proxy_url'] .'300/300'. $data->logo['image_path']}}" ></a>
                                </div>                                
                            </div>
                            <div class="product-detail">
                                @if($client_preference_detail)
                                    @if($client_preference_detail->rating_check == 1)  
                                    <div class="rating">
                                        @for($i = 1; $i < 6; $i++)
                                            <i class="fa fa-star"></i>
                                        @endfor
                                    </div>
                                    @endif
                                @endif
                                <a href="{{route('vendorDetail', $data->id)}}">
                                    <h6>{{$data->name}}</h6>
                                </a>
                            </div>
                        </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@section('script')

@endsection
