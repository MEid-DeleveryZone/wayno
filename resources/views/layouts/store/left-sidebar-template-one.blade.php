@php
$clientData = \App\Models\Client::select('id', 'logo')->where('id', '>', 0)->first();
$urlImg =  $clientData ? $clientData->logo['image_fit'].'150/92'.$clientData->logo['image_path'] : " ";
$languageList = \App\Models\ClientLanguage::with('language')->where('is_active', 1)->orderBy('is_primary', 'desc')->get();
$currencyList = \App\Models\ClientCurrency::with('currency')->orderBy('is_primary', 'desc')->get();
@endphp


<style>
    .cab-booking-header{
         display: none;
     }
 </style>

<header class="site-header @if($client_preference_detail->business_type == 'taxi') taxi-header @endif">
  
   @if(Auth::check())
   @include('layouts.store/topbar-auth-template-one')
   @else
   @include('layouts.store/topbar-guest-template-one')
   @endif
        <!-- Start Cab Booking Header From Here -->
        <div class="cab-booking-header">
            <div class="container">
                <div class="row">
                    <div class="col-2">
                         <a class="navbar-brand mr-0" href="{{ route('userHome') }}"><img class="img-fluid" alt="" src="{{$urlImg}}" ></a> 
                    </div>
                    <div class="top-header bg-transparent col-10 d-flex align-items-center justify-content-end">
                        <ul class="header-dropdown">
                            <li class="onhover-dropdown change-language">
                                <a href="javascript:void(0)">{{session()->get('locale')}} 
                                <span class="icon-ic_lang align-middle"></span>
                                <span class="language ml-1 align-middle">{{ __("language") }}</span>
                                </a>
                                <ul class="onhover-show-div">
                                    @foreach($languageList as $key => $listl)
                                        <li class="{{session()->get('locale') ==  $listl->language->sort_code ?  'active' : ''}}">
                                            <a href="javascript:void(0)" class="customerLang" langId="{{$listl->language_id}}">{{$listl->language->name}}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                            <li class="onhover-dropdown change-currency">
                                <a href="javascript:void(0)">{{session()->get('iso_code')}}
                                <span class="icon-ic_currency align-middle"></span>
                                <span class="currency ml-1 align-middle">{{ __("currency") }}</span>
                                </a>
                                <ul class="onhover-show-div">
                                    @foreach($currencyList as $key => $listc)
                                    <li class="{{session()->get('iso_code') ==  $listc->currency->iso_code ?  'active' : ''}}">
                                        <a href="javascript:void(0)" currId="{{$listc->currency_id}}" class="customerCurr" currSymbol="{{$listc->currency->symbol}}">
                                            {{$listc->currency->iso_code}}
                                        </a>
                                    </li>
                                    @endforeach
                                </ul>
                            </li>
                            @if(Auth::guest())
                            <li class="onhover-dropdown mobile-account">
                                <i class="fa fa-user" aria-hidden="true"></i>{{__('Account')}}
                                <ul class="onhover-show-div">
                                    <li>
                                        <a href="{{route('customer.login')}}" data-lng="en">{{__('Login')}}</a>
                                    </li>
                                    <li>
                                        <a href="{{route('customer.register')}}" data-lng="es">{{__('Register')}}</a>
                                    </li>
                                </ul>
                            </li>
                            @else
                            <li class="onhover-dropdown mobile-account">
                                <i class="fa fa-user" aria-hidden="true"></i>{{__('Account')}}
                                <ul class="onhover-show-div">
                                    @if(Auth::user()->is_superadmin == 1 || Auth::user()->is_admin == 1)
                                    <li>
                                        <a href="{{route('client.dashboard')}}" data-lng="en">{{__('Control Panel')}}</a>
                                    </li>
                                    @endif
                                    <li>
                                        <a href="{{route('user.profile')}}" data-lng="en">{{__('Profile')}}</a>
                                    </li>
                                    <li>
                                        <a href="{{route('user.logout')}}" data-lng="es">{{__('Logout')}}</a>
                                    </li>
                                </ul>
                            </li>

                           
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Cab Booking Header From Here -->

    <div class="main-menu">
        <div class="container d-block">
            <div class="row align-items-center pt-2 position-initial">
                <div class="col-lg-12">
                    <div class="row mobile-header align-items-center">
                        <div class="col-6 d-flex align-items-center justify-content-sm-between"> 
                            <a class="navbar-brand mr-3 d-none d-sm-inline-block" href="{{ route('userHome') }}"><img class="img-fluid" alt="" src="{{$urlImg}}" ></a>
                            @if($mod_count > 1)
                                <ul class="nav nav-tabs navigation-tab nav-material tab-icons mr-md-3 vendor_mods" id="top-tab" role="tablist">
                                    @if($client_preference_detail->delivery_check == 1)
                                    <li class="navigation-tab-item" role="presentation">
                                        <a class="nav-link {{ ($mod_count == 1 || (Session::get('vendorType') == 'delivery') || (Session::get('vendorType') == '')) ? 'active' : ''}}" id="delivery_tab" data-toggle="tab" href="#delivery_tab" role="tab" aria-controls="profile" aria-selected="false">{{ __('Delivery') }}</a>
                                    </li>
                                    @endif
                                    @if($client_preference_detail->dinein_check == 1)
                                    <li class="navigation-tab-item" role="presentation">
                                        <a class="nav-link {{ ($mod_count == 1 || (Session::get('vendorType') == 'dine_in')) ? 'active' : ''}}" id="dinein_tab" data-toggle="tab" href="#dinein_tab" role="tab" aria-controls="dinein_tab" aria-selected="false">{{ __('Dine-In') }}</a>
                                    </li>
                                    @endif
                                    @if($client_preference_detail->takeaway_check == 1)
                                    <li class="navigation-tab-item" role="presentation">


                                        @php
                                        $Takeaway = getNomenclatureName('Takeaway', true);
                                        $Takeaway = ($Takeaway === 'Takeaway') ? __('Takeaway') : $Takeaway;
                                        @endphp
    
    
    
                                        <a class="nav-link {{ ($mod_count == 1 || (Session::get('vendorType') == 'takeaway')) ? 'active' : ''}}" id="takeaway_tab" data-toggle="tab" href="#takeaway_tab" role="tab" aria-controls="takeaway_tab" aria-selected="false">{{ $Takeaway }}</a>
                                    </li>
                                    @endif
                                    <div class="navigation-tab-overlay"></div>
                                </ul>
                            @endif 
                        </div>
                        <div class="col-6 my-sm-2 my-md-0 ipad-view">                       
                            <div class="search_bar menu-right d-sm-flex d-block align-items-center justify-content-end w-100 ">
                                @if( (Session::get('preferences')))
                                    @if( (isset(Session::get('preferences')->is_hyperlocal)) && (Session::get('preferences')->is_hyperlocal == 1) )
                                        <div class="location-bar d-none align-items-center justify-content-start ml-md-2 my-2 my-lg-0 dropdown-toggle" href="#edit-address" data-toggle="modal">
                                            <div class="map-icon mr-md-1"><i class="fa fa-map-marker" aria-hidden="true"></i></div>
                                            <div class="homepage-address text-left">
                                                <h2><span data-placement="top" data-toggle="tooltip" title="{{session('selectedAddress')}}">{{session('selectedAddress')}}</span></h2>
                                            </div>
                                            <div class="down-icon">
                                                <i class="fa fa-angle-down" aria-hidden="true"></i>
                                            </div>
                                        </div>
                                    @endif
                                @endif  
                                <div class="radius-bar d-xl-inline mr-2">
                                    <div class="search_form d-flex align-items-center justify-content-between">
                                        <button class="btn"><i class="fa fa-search" aria-hidden="true"></i></button>
                                        @php
                                            $searchPlaceholder = getNomenclatureName('Search product, vendor, item', true);
                                            $searchPlaceholder = ($searchPlaceholder === 'Search product, vendor, item') ? __('Search product, vendor, item') : $searchPlaceholder;  
                                        @endphp
                                        <input class="form-control border-0 typeahead" type="search" placeholder="{{ $searchPlaceholder }}" id="main_search_box">
                                    </div>
                                    <div class="list-box style-4" style="display:none;" id="search_box_main_div">
                                        
                                    </div>
                                </div>
                                <script type="text/template" id="search_box_main_div_template">
                                    <a class="text-right d-block mr-2 mb-3" id="search_viewall" href="#">{{ __("View All") }}</a>
                                    <div class="row mx-0">
                                        <% _.each(results, function(result, k){ %>
                                            <a class="col-md-4 text-center list-items mb-2" href="<%= result.redirect_url %>">
                                            <img src="<%= result.image_url%>" alt="">
                                            <span><%= result.name %></span>
                                            </a>
                                        <% }); %>
                                    </div>
                                </script>
                                @if(auth()->user())
                                @if($client_preference_detail->show_wishlist == 1)
                                    <div class="icon-nav mx-2 d-none d-sm-block">
                                        <a class="fav-button" href="{{route('user.wishlists')}}">
                                            <i class="fa fa-heart" aria-hidden="true"></i>
                                        </a>
                                    </div>
                                @endif
                                @endif
                                <div class="icon-nav">
                                    <form name="filterData" id="filterData" action="{{route('changePrimaryData')}}">
                                        @csrf
                                        <input type="hidden" id="cliLang" name="cliLang" value="{{session('customerLanguage')}}">
                                        <input type="hidden" id="cliCur" name="cliCur" value="{{session('customerCurrency')}}">
                                    </form>
                                    <ul class="d-flex align-items-center">
                                        <li class="mr-2 pl-0 d-ipad">
                                            <span class="mobile-search-btn"><i class="fa fa-search" aria-hidden="true"></i></span>
                                        </li>
                                        <li class="onhover-div pl-0 shake-effect">
                                            @if($client_preference_detail)
                                                @if($client_preference_detail->cart_enable == 1)
                                                    <a class="btn btn-solid " href="{{route('showCart')}}">
                                                        <i class="fa fa-shopping-cart mr-1 " aria-hidden="true"></i> <span>{{__('Cart')}} •</span> <span id="cart_qty_span"></span> 
                                                    </a>
                                                @endif
                                            @endif
                                            <script type="text/template" id="header_cart_template">
                                                <% _.each(cart_details.products, function(product, key){%>
                                                    <% _.each(product.vendor_products, function(vendor_product, vp){%>
                                                        <li id="cart_product_<%= vendor_product.id %>" data-qty="<%= vendor_product.quantity %>">
                                                            <a class='media' href='<%= show_cart_url %>'>
                                                                <% if(vendor_product.pvariant.media_one) { %>
                                                                    <img class='mr-2' src="<%= vendor_product.pvariant.media_one.pimage.image.path.proxy_url %>200/200<%= vendor_product.pvariant.media_one.pimage.image.path.image_path %>">
                                                                <% }else{ %>
                                                                    <img class='mr-2' src="<%= vendor_product.pvariant.media_second.image.path.proxy_url %>200/200<%= vendor_product.pvariant.media_second.image.path.image_path %>">
                                                                <% } %>
                                                                <div class='media-body'>                                                                
                                                                    <h4><%= vendor_product.product.translation_one ? vendor_product.product.translation_one.title :  vendor_product.product.sku %></h4>
                                                                    <h4>
                                                                        <span><%= vendor_product.quantity %> x <%= vendor_product.pvariant.price %></span>
                                                                    </h4>
                                                                </div>
                                                            </a>
                                                            <div class='close-circle'>
                                                                <a href="javascript::void(0);" data-product="<%= vendor_product.id %>" class='remove-product'>
                                                                    <i class='fa fa-times' aria-hidden='true'></i>
                                                                </a>
                                                            </div>
                                                        </li>
                                                    <% }); %>
                                                <% }); %>
                                                <li><div class='total'><h5>{{__('Subtotal')}} : <span id='totalCart'><%= cart_details.gross_amount %></span></h5></div></li>
                                                <li><div class='buttons'><a href="<%= show_cart_url %>" class='view-cart'>{{__('View Cart')}}</a>
                                            </script>
                                            <ul class="show-div shopping-cart " id="header_cart_main_ul"></ul>
                                        </li>
                                        <li class="mobile-menu-btn d-sm-inline-block d-none"><div class="toggle-nav p-0 d-inline-block"><i class="fa fa-bars sidebar-bar"></i></div></li>
                                    </ul>
                                </div>
                                
                                <div class="icon-nav d-sm-none d-none">
                                    <ul>
                                        <li class="onhover-div mobile-search">
                                            <a href="javascript:void(0);" id="mobile_search_box_btn"><i class="ti-search"></i></a>
                                            <div id="search-overlay" class="search-overlay">
                                                <div> <span class="closebtn" onclick="closeSearch()" title="Close Overlay">×</span>
                                                    <div class="overlay-content">
                                                        <div class="container">
                                                            <div class="row">
                                                                <div class="col-xl-12">
                                                                    <form>
                                                                        <div class="form-group">
                                                                            <input type="text" class="form-control" id="exampleInputPassword1" placeholder="Search a Product">
                                                                        </div>
                                                                        <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="onhover-div mobile-setting">
                                            <div data-toggle="modal" data-target="#staticBackdrop"><i class="ti-settings"></i></div>
                                            <div class="show-div setting">
                                                <h6>language</h6>
                                                <ul>
                                                    <li><a href="#">english</a></li>
                                                    <li><a href="#">french</a></li>
                                                </ul>
                                                <h6>currency</h6>
                                                <ul class="list-inline">
                                                    <li><a href="#">euro</a></li>
                                                    <li><a href="#">rupees</a></li>
                                                    <li><a href="#">pound</a></li>
                                                    <li><a href="#">doller</a></li>
                                                </ul>
                                                <h6>Change Theme</h6>
                                                @if($client_preference_detail->show_dark_mode == 1)
                                                <ul class="list-inline">
                                                    <li><a class="theme-layout-version" href="javascript:void(0)">Dark</a></li>
                                                </ul>
                                                @endif
                                            </div>
                                        </li>
                                        <li class="onhover-div mobile-cart">
                                            <a href="{{route('showCart')}}" style="position: relative">
                                                <i class="ti-shopping-cart"></i>
                                                <span class="cart_qty_cls" style="display:none"></span>
                                            </a>
                                            {{--<span class="cart_qty_cls" style="display:none"></span>--}}
                                            <ul class="show-div shopping-cart">
                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>                                                 
                </div>
                <div class="col-lg-5 col-9 order-lg-2 order-1 position-initial"> 
                                 
                    <!-- <div class="search_bar menu-right d-flex align-items-center justify-content-between justify-content-lg-between w-100 ">
                        @if( (Session::get('preferences')))
                            @if( (isset(Session::get('preferences')->is_hyperlocal)) && (Session::get('preferences')->is_hyperlocal == 1) )
                                <div class="location-bar d-lg-none d-flex align-items-center justify-content-start ml-md-2 my-2 my-lg-0 dropdown-toggle" href="#edit-address" data-toggle="modal">
                                    <div class="map-icon mr-1"><i class="fa fa-map-marker" aria-hidden="true"></i></div>
                                    <div class="homepage-address text-left">
                                        <h2><span data-placement="top" data-toggle="tooltip" title="{{session('selectedAddress')}}">{{session('selectedAddress')}}</span></h2>
                                    </div>
                                    <div class="down-icon">
                                        <i class="fa fa-angle-down" aria-hidden="true"></i>
                                    </div>
                                </div>
                            @endif
                        @endif  
                        <div class="radius-bar d-none d-lg-inline">
                            <div class="search_form d-flex align-items-center justify-content-between">
                                <button class="btn"><i class="fa fa-search" aria-hidden="true"></i></button>
                                <input class="form-control border-0 typeahead" type="search" placeholder="{{getNomenclatureName('Search', true)}}" id="main_search_box">
                            </div>
                            <div class="list-box style-4" style="display:none;" id="search_box_main_div">
                                
                            </div>
                        </div>
                        <script type="text/template" id="search_box_main_div_template">
                            <a class="text-right d-block mr-2 mb-3" id="search_viewall" href="#">View All</a>
                            <div class="row mx-0">
                                <% _.each(results, function(result, k){ %>
                                    <a class="col-md-4 text-center list-items mb-2" href="<%= result.redirect_url %>">
                                    <img src="<%= result.image_url%>" alt="">
                                    <span><%= result.name %></span>
                                    </a>
                                <% }); %>
                            </div>
                        </script>
                        @if(auth()->user())
                        @if($client_preference_detail->show_wishlist == 1)
                            <div class="icon-nav mx-2 d-none d-sm-block">
                                <a href="{{route('user.wishlists')}}">
                                    <i class="fa fa-heart" aria-hidden="true"></i>
                                </a>
                            </div>
                        @endif
                        @endif
                        <div class="icon-nav">
                            <form name="filterData" id="filterData" action="{{route('changePrimaryData')}}">
                                @csrf
                                <input type="hidden" id="cliLang" name="cliLang" value="{{session('customerLanguage')}}">
                                <input type="hidden" id="cliCur" name="cliCur" value="{{session('customerCurrency')}}">
                            </form>
                            <ul>
                                <li class="onhover-div pl-0 shake-effect">
                                    @if($client_preference_detail)
                                        @if($client_preference_detail->cart_enable == 1)
                                            <a class="btn btn-solid " href="{{route('showCart')}}">
                                                <i class="fa fa-shopping-cart mr-1 " aria-hidden="true"></i> <span>{{__('Cart')}} •</span> <span id="cart_qty_span"></span> 
                                            </a>
                                        @endif
                                    @endif
                                    <script type="text/template" id="header_cart_template">
                                        <% _.each(cart_details.products, function(product, key){%>
                                            <% _.each(product.vendor_products, function(vendor_product, vp){%>
                                                <li id="cart_product_<%= vendor_product.id %>" data-qty="<%= vendor_product.quantity %>">
                                                    <a class='media' href='<%= show_cart_url %>'>
                                                        <% if(vendor_product.pvariant.media_one) { %>
                                                            <img class='mr-2' src="<%= vendor_product.pvariant.media_one.pimage.image.path.proxy_url %>200/200<%= vendor_product.pvariant.media_one.pimage.image.path.image_path %>">
                                                        <% }else{ %>
                                                            <img class='mr-2' src="<%= vendor_product.pvariant.media_second.image.path.proxy_url %>200/200<%= vendor_product.pvariant.media_second.image.path.image_path %>">
                                                        <% } %>
                                                        <div class='media-body'>                                                                
                                                            <h4><%= vendor_product.product.translation_one ? vendor_product.product.translation_one.title :  vendor_product.product.sku %></h4>
                                                            <h4>
                                                                <span><%= vendor_product.quantity %> x <%= vendor_product.pvariant.price %></span>
                                                            </h4>
                                                        </div>
                                                    </a>
                                                    <div class='close-circle'>
                                                        <a href="javascript::void(0);" data-product="<%= vendor_product.id %>" class='remove-product'>
                                                            <i class='fa fa-times' aria-hidden='true'></i>
                                                        </a>
                                                    </div>
                                                </li>
                                            <% }); %>
                                        <% }); %>
                                        <li><div class='total'><h5>{{__('Subtotal')}} : <span id='totalCart'><%= cart_details.gross_amount %></span></h5></div></li>
                                        <li><div class='buttons'><a href="<%= show_cart_url %>" class='view-cart'>{{__('View Cart')}}</a>
                                    </script>
                                    <ul class="show-div shopping-cart " id="header_cart_main_ul"></ul>
                                </li>
                                <li class="d-inline-block d-lg-none"><div class="toggle-nav p-0 d-inline-block"><i class="fa fa-bars sidebar-bar"></i></div></li>
                            </ul>
                        </div>
                        
                        <div class="icon-nav d-sm-none d-none">
                            <ul>
                                <li class="onhover-div mobile-search">
                                    <a href="javascript:void(0);" id="mobile_search_box_btn"><i class="ti-search"></i></a>
                                    <div id="search-overlay" class="search-overlay">
                                        <div> <span class="closebtn" onclick="closeSearch()" title="Close Overlay">×</span>
                                            <div class="overlay-content">
                                                <div class="container">
                                                    <div class="row">
                                                        <div class="col-xl-12">
                                                            <form>
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control" id="exampleInputPassword1" placeholder="Search a Product">
                                                                </div>
                                                                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="onhover-div mobile-setting">
                                    <div><i class="ti-settings"></i></div>
                                    <div class="show-div setting">
                                        <h6>language</h6>
                                        <ul>
                                            <li><a href="#">english</a></li>
                                            <li><a href="#">french</a></li>
                                        </ul>
                                        <h6>currency</h6>
                                        <ul class="list-inline">
                                            <li><a href="#">euro</a></li>
                                            <li><a href="#">rupees</a></li>
                                            <li><a href="#">pound</a></li>
                                            <li><a href="#">doller</a></li>
                                        </ul>
                                        <h6>Change Theme</h6>
                                        @if($client_preference_detail->show_dark_mode == 1)
                                        <ul class="list-inline">
                                            <li><a class="theme-layout-version" href="javascript:void(0)">Dark</a></li>
                                        </ul>
                                        @endif
                                    </div>
                                </li>
                                <li class="onhover-div mobile-cart">
                                    <a href="{{route('showCart')}}" style="position: relative">
                                        <i class="ti-shopping-cart"></i>
                                        <span class="cart_qty_cls" style="display:none"></span>
                                    </a>
                                    {{--<span class="cart_qty_cls" style="display:none"></span>--}}
                                    <ul class="show-div shopping-cart">
                                    </ul>
                                </li>
                            </ul>
                        </div>

                    </div> -->
                </div>
            </div>
        </div>
    </div>

    {{--@if(count($navCategories) > 0)--}}
        <div class="menu-navigation">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        
                        <ul id="main-menu" class="sm pixelstrap sm-horizontal">
                            <li>
                                <div class="mobile-back text-end">{{__('Back')}}<i class="fa fa-angle-right ps-2" aria-hidden="true"></i></div>
                            </li>
                            @foreach($navCategories as $cate)
                                @if($cate['name'])
                                <li>                                    
                                    <a href="{{route('categoryDetail', $cate['slug'])}}">
                                        @if($client_preference_detail->show_icons == 1 && \Request::route()->getName() == 'userHome')
                                        <div class="nav-cate-img">
                                            <img src="{{$cate['icon']['image_fit']}}200/200{{$cate['icon']['image_path']}}" alt="">
                                        </div>
                                        @endif
                                        {{$cate['name']}}</a>
                                    @if(!empty($cate['children']))                                        
                                        <ul>
                                            @foreach($cate['children'] as $childs)
                                            <li>
                                                <a href="{{route('categoryDetail', $childs['slug'])}}"><span class="new-tag">{{$childs['name']}}</span></a>
                                                @if(!empty($childs['children']))
                                                <ul>
                                                    @foreach($childs['children'] as $chld)
                                                    <li><a href="{{route('categoryDetail', $chld['slug'])}}">{{$chld['name']}}</a></li>
                                                    @endforeach
                                                </ul>
                                                @endif
                                            </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    {{--@endif--}}

   


</header>
<div class="offset-top @if((\Request::route()->getName() != 'userHome') || ($client_preference_detail->show_icons == 0)) inner-pages-offset @endif @if($client_preference_detail->hide_nav_bar == 1) set-hide-nav-bar @endif"></div>
<script type="text/template" id="nav_categories_template">
    <li>
        <div class="mobile-back text-end">Back<i class="fa fa-angle-right ps-2" aria-hidden="true"></i></div>
    </li>
    <% _.each(nav_categories, function(category, key){ %>
        <li>
            <a href="{{route('categoryDetail')}}/<%= category.slug %>">
                @if($client_preference_detail->show_icons == 1  && \Request::route()->getName() == 'userHome')
                    <div class="nav-cate-img">
                        <img src="<%= category.icon.image_fit %>200/200<%= category.icon.image_path %>" alt=""> 
                    </div>
                @endif
                <%= category.name %>
            </a>
            <% if(category.children) { %>
                <ul>
                <% _.each(category.children, function(childs, key1){ %>
                    <li>
                        <a href="{{route('categoryDetail')}}/<%= childs.slug %>"><span class="new-tag"><%= childs.name %></span></a>
                        <% if(childs.children) { %>
                        <ul>
                            <% _.each(childs.children, function(chld, key2){ %>
                                <li><a href="{{route('categoryDetail')}}/<%= chld.slug %>"><%= chld.name %></a></li>
                            <% }); %>
                        </ul>
                        <% } %>
                    </li>
                <% }); %>
                </ul>
            <% } %>
        </li>
    <% }); %>
</script>
<div class="modal fade edit_address" id="edit-address" tabindex="-1" aria-labelledby="edit-addressLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body p-0">
        <div id="address-map-container">
            <div id="address-map"></div>
        </div>
        <div class="delivery_address p-2 mb-2 position-relative">
            <button type="button" class="close edit-close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <div class="form-group">
                <label class="delivery-head mb-2">{{__('SELECT YOUR LOCATION')}}</label>
                <div class="address-input-field d-flex align-items-center justify-content-between">
                    <i class="fa fa-map-marker" aria-hidden="true"></i>
                    <input class="form-control border-0 map-input" type="text" name="address-input" id="address-input" value="{{session('selectedAddress')}}">
                    <input type="hidden" name="address_latitude" id="address-latitude" value="{{session('latitude')}}" />
                    <input type="hidden" name="address_longitude" id="address-longitude" value="{{session('longitude')}}" />
                    <input type="hidden" name="address_place_id" id="address-place-id" value="{{session('selectedPlaceId')}}" />
                </div>
            </div>
            <div class="text-center">
                <button type="button" class="btn btn-solid ml-auto confirm_address_btn w-100">{{__('Confirm And Proceed')}}</button>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade remove-cart-modal" id="remove_cart_modal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="remove_cartLabel" style="background-color: rgba(0,0,0,0.8);">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header pb-0">
        <h5 class="modal-title" id="remove_cartLabel">{{__('Remove Cart')}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <h6 class="m-0">{{__('This change will remove all your cart products. Do you really want to continue ?')}}</h6>
      </div>
      <div class="modal-footer flex-nowrap justify-content-center align-items-center">
        <button type="button" class="btn btn-solid black-btn" data-dismiss="modal">{{__('Cancel')}}</button>
        <button type="button" class="btn btn-solid" id="remove_cart_button" data-cart_id="">{{__('Remove')}}</button>
      </div>
    </div>
  </div>
</div>
