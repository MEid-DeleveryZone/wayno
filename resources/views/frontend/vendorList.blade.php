@extends('layouts.store', ['title' => 'Product'])

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

<section class="portfolio-section portfolio-padding grid-portfolio ratio2_3">
    <div class="container">
        <!--<div align="center" id="form1">
            <button class="filter-button project_button active" data-filter="all">All</button>
            <button class="filter-button project_button" data-filter="fashion">Fashion</button>
            <button class="filter-button project_button" data-filter="bags">Bags</button>
            <button class="filter-button project_button" data-filter="shoes">Shoes</button>
            <button class="filter-button project_button" data-filter="watch">Watch</button>
        </div> -->
        <div class="row  zoom-gallery">
            <div class="isotopeSelector filter fashion col-lg-3 col-sm-6">
                <div class="overlay">
                    <div class="border-portfolio">
                        <a href="../assets/images/portfolio/grid/1.jpg">
                            <div class="overlay-background">
                                <i class="fa fa-plus" aria-hidden="true"></i>
                            </div>
                            <img src="../assets/images/portfolio/grid/1.jpg"
                                class="img-fluid blur-up lazyload bg-img">
                        </a>
                    </div>
                </div>
            </div>
            <div class="isotopeSelector filter shoes col-lg-3 col-sm-6">
                <div class="overlay">
                    <div class="border-portfolio">
                        <a href="../assets/images/portfolio/grid/2.jpg">
                            <div class="overlay-background">
                                <i class="fa fa-plus" aria-hidden="true"></i>
                            </div>
                            <img src="../assets/images/portfolio/grid/2.jpg"
                                class="img-fluid blur-up lazyload bg-img">
                        </a>
                    </div>
                </div>
            </div>
            <div class="isotopeSelector filter bags col-lg-3 col-sm-6">
                <div class="overlay">
                    <div class="border-portfolio">
                        <a href="../assets/images/portfolio/grid/3.jpg">
                            <div class="overlay-background">
                                <i class="fa fa-plus" aria-hidden="true"></i>
                            </div>
                            <img src="../assets/images/portfolio/grid/3.jpg"
                                class="img-fluid blur-up lazyload bg-img">
                        </a>
                    </div>
                </div>
            </div>
            <div class="isotopeSelector filter bags col-lg-3 col-sm-6">
                <div class="overlay">
                    <div class="border-portfolio">
                        <a href="../assets/images/portfolio/grid/4.jpg">
                            <div class="overlay-background">
                                <i class="fa fa-plus" aria-hidden="true"></i>
                            </div>
                            <img src="../assets/images/portfolio/grid/4.jpg"
                                class="img-fluid blur-up lazyload bg-img">
                        </a>
                    </div>
                </div>
            </div>
            <div class="isotopeSelector filter bags col-lg-3 col-sm-6">
                <div class="overlay">
                    <div class="border-portfolio">
                        <a href="../assets/images/portfolio/grid/5.jpg">
                            <div class="overlay-background">
                                <i class="fa fa-plus" aria-hidden="true"></i>
                            </div>
                            <img src="../assets/images/portfolio/grid/5.jpg"
                                class="img-fluid blur-up lazyload bg-img">
                        </a>
                    </div>
                </div>
            </div>
            <div class="isotopeSelector filter watch col-lg-3 col-sm-6">
                <div class="overlay">
                    <div class="border-portfolio">
                        <a href="../assets/images/portfolio/grid/6.jpg">
                            <div class="overlay-background">
                                <i class="fa fa-plus" aria-hidden="true"></i>
                            </div>
                            <img src="../assets/images/portfolio/grid/6.jpg"
                                class="img-fluid blur-up lazyload bg-img">
                        </a>
                    </div>
                </div>
            </div>
            <div class="isotopeSelector filter bags col-lg-3 col-sm-6">
                <div class="overlay">
                    <div class="border-portfolio">
                        <a href="../assets/images/portfolio/grid/7.jpg">
                            <div class="overlay-background">
                                <i class="fa fa-plus" aria-hidden="true"></i>
                            </div>
                            <img src="../assets/images/portfolio/grid/7.jpg"
                                class="img-fluid blur-up lazyload bg-img">
                        </a>
                    </div>
                </div>
            </div>
            <div class="isotopeSelector filter bags col-lg-3 col-sm-6">
                <div class="overlay">
                    <div class="border-portfolio">
                        <a href="../assets/images/portfolio/grid/8.jpg">
                            <div class="overlay-background">
                                <i class="fa fa-plus" aria-hidden="true"></i>
                            </div>
                            <img src="../assets/images/portfolio/grid/8.jpg"
                                class="img-fluid blur-up lazyload bg-img">
                        </a>
                    </div>
                </div>
            </div>
            <div class="isotopeSelector filter fashion col-lg-3 col-sm-6">
                <div class="overlay">
                    <div class="border-portfolio">
                        <a href="../assets/images/portfolio/grid/9.jpg">
                            <div class="overlay-background">
                                <i class="fa fa-plus" aria-hidden="true"></i>
                            </div>
                            <img src="../assets/images/portfolio/grid/9.jpg"
                                class="img-fluid blur-up lazyload bg-img">
                        </a>
                    </div>
                </div>
            </div>
            <div class="isotopeSelector filter shoes col-lg-3 col-sm-6">
                <div class="overlay">
                    <div class="border-portfolio">
                        <a href="../assets/images/portfolio/grid/10.jpg">
                            <div class="overlay-background">
                                <i class="fa fa-plus" aria-hidden="true"></i>
                            </div>
                            <img src="../assets/images/portfolio/grid/10.jpg"
                                class="img-fluid blur-up lazyload bg-img">
                        </a>
                    </div>
                </div>
            </div>
            <div class="isotopeSelector filter bags col-lg-3 col-sm-6">
                <div class="overlay">
                    <div class="border-portfolio">
                        <a href="../assets/images/portfolio/grid/11.jpg">
                            <div class="overlay-background">
                                <i class="fa fa-plus" aria-hidden="true"></i>
                            </div>
                            <img src="../assets/images/portfolio/grid/11.jpg"
                                class="img-fluid blur-up lazyload bg-img">
                        </a>
                    </div>
                </div>
            </div>
            <div class="isotopeSelector filter fashion col-lg-3 col-sm-6">
                <div class="overlay">
                    <div class="border-portfolio">
                        <a href="../assets/images/portfolio/grid/12.jpg">
                            <div class="overlay-background">
                                <i class="fa fa-plus" aria-hidden="true"></i>
                            </div>
                            <img src="../assets/images/portfolio/grid/12.jpg"
                                class="img-fluid blur-up lazyload bg-img">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('script')

@endsection
