@extends('layouts.store', ['title' => 'Searched Items'])

@section('css')
<style type="text/css">
    .main-menu .brand-logo {
        display: inline-block;
        padding-top: 20px;
        padding-bottom: 20px;
    }

    #product {
        margin-left: 100px !important;
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


<div class="container">
    <div class="row">
        <h3>Search Result for {{$search}} are:</h3>
    </div>
</div>

<div class="container">
    @if($search==NULL)
    <!-- <h5>No Result found</h5> -->
    @else
    <!--Section For Vendors -->
    @if(!empty($vendors) && count($vendors) > 0)
    <section class="section-b-space">
        <div class="container">

            <div class="row">
                <div class="col-12 product-related">
                    <h2>Vendors</h2>
                </div>
            </div>
            <div class="row search-product">
                @foreach($vendors as $vendor)
                <div class="col-xl-2 col-md-4 col-sm-6">
                    <div class="">
                        <div class="">
                            <div class="product-box">
                                <div class="img-wrapper">
                                    <div class="front">
                                        <a href="{{route('vendorDetail', $vendor['id'])}}">
                                            <img src="{{$vendor['logo']['proxy_url']}}200/200{{$vendor['logo']['image_path']}}">
                                        </a>
                                        <div class="back">
                                            <a href="{{route('vendorDetail', $vendor['id'])}}">
                                                <img src="{{$vendor['logo']['proxy_url']}}200/200{{$vendor['logo']['image_path']}}" alt="">
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="product-detail">
                                    <div class="rating"><i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i>
                                    </div>
                                    <a href="#">
                                        <h6>{{$vendor['name']}}</h6>
                                    </a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif
    <!--Section For Categories -->
    @if(!empty($categories) && count($categories) > 0)
    <section class="section-b-space">
        <div class="container">
            <div class="row">
                <div class="col-12 product-related">
                    <h2>Categories</h2>
                </div>
            </div>
            <div class="row search-product">
                @foreach($categories as $category)
                <div class="col-xl-2 col-md-4 col-sm-6">
                    <div class="product-box">
                        <div class="img-wrapper">
                            <div class="front">
                                <a href="{{route('categoryDetail', $category['id'])}}">
                                    <div class="category-image"> <img src="{{$category['icon']['proxy_url']}}200/200{{$category['icon']['image_path']}}"></div>
                                </a>
                                <div class="back">
                                    <a href="{{route('categoryDetail', $category['id'])}}">
                                        <div class="category-image"> <img src="{{$category['icon']['proxy_url']}}200/200{{$category['icon']['image_path']}}"></div>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="product-detail">
                            <div class="rating"><i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i>
                            </div>
                            <a href="#">
                                <h5>{{$category['slug']}}</h5>
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif
    <!--Section For Products -->
    @if(!empty($products) && count($products) > 0)
    <section class="section-b-space">
        <div class="container">
            <div class="row">
                <div class="col-12 product-related">
                    <h2>products</h2>
                </div>
            </div>
            <div class="row search-product">
                @foreach($products as $product)
                <div class="col-xl-2 col-md-4 col-sm-6">
                    <div class="product-box">
                        <div class="img-wrapper">
                            <?php $imagePath = '';
                            foreach ($product['media'] as $k => $v) {
                                $imagePath = $v['image']['path']['proxy_url'] . '200/200' . $v['image']['path']['image_path'];
                            } ?>
                            <div class="img-wrapper">
                                <div class="front">
                                    <a href="{{route('productDetail', $product->url_slug)}}"><img src="{{$imagePath}}" alt=""></a>
                                </div>
                                <div class="back">
                                    <a href="{{route('productDetail', $product->url_slug)}}"><img src="{{$imagePath}}" alt=""></a>
                                </div>
                            </div>
                        </div>
                        <div class="product-detail">
                            <div class="rating"><i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i>
                            </div>
                            <a href="#">
                                <h5>{{$product->url_slug}}</h5>
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif
    @endif
</div>

@endsection