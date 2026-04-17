@php
$clientData = \App\Models\Client::select('id', 'logo')->where('id', '>', 0)->first();
$urlImg = $clientData->logo['image_fit'].'150/60'.$clientData->logo['image_path'];
$languageList = \App\Models\ClientLanguage::with('language')->where('is_active', 1)->orderBy('is_primary', 'desc')->get();
$currencyList = \App\Models\ClientCurrency::with('currency')->orderBy('is_primary', 'desc')->get();
@endphp

<style>
    .modal-backdrop {
        z-index: 99;
    }
</style>

<div class="top-header site-topbar">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-6">
                <div class="d-flex align-items-center">    
                    <a class="navbar-brand mr-0 mr-sm-3 d-block d-sm-none" href="{{ route('userHome') }}"><img class="img-fluid" alt="" src="{{$urlImg}}" ></a>
                    @if( (Session::get('preferences')))
                        @if( (isset(Session::get('preferences')->is_hyperlocal)) && (Session::get('preferences')->is_hyperlocal == 1) )
                            <div class="location-bar d-none d-sm-flex align-items-center justify-content-start m-0 p-0 dropdown-toggle order-1" href="#edit-address" data-toggle="modal">
                                <div class="map-icon mr-2"><i class="fa fa-map-marker" aria-hidden="true"></i></div>
                                <div class="homepage-address text-left">
                                    <h2><span data-placement="top">{{session('selectedAddress')}}</span></h2>
                                </div>
                                <div class="down-icon ml-2">
                                    <i class="fa fa-angle-down" aria-hidden="true"></i>
                                </div>
                            </div>
                        @endif
                    @endif
                </div>
            </div>

            <div class="col-6 d-inline d-sm-none text-right pr-0">
                <div class="icon-nav">
                    <ul>
                        <li class="d-inline-block d-lg-none"><div class="toggle-nav p-0 d-inline-block"><i class="fa fa-bars sidebar-bar"></i></div></li>
                    </ul>
                </div>
            </div>

            <div class="col-sm-6 mt-2 mt-sm-0 text-right">
                <div class="d-inline d-sm-none">
                    @if( (Session::get('preferences')))
                        @if( (isset(Session::get('preferences')->is_hyperlocal)) && (Session::get('preferences')->is_hyperlocal == 1) )
                            <div class="location-bar d-flex align-items-center justify-content-start dropdown-toggle" href="#edit-address" data-toggle="modal">
                                <div class="map-icon mr-2"><i class="fa fa-map-marker" aria-hidden="true"></i></div>
                                <div class="homepage-address text-left">
                                    <h2><span data-placement="top">{{session('selectedAddress')}}</span></h2>
                                </div>
                                <div class="down-icon ml-2">
                                    <i class="fa fa-angle-down" aria-hidden="true"></i>
                                </div>
                            </div>
                        @endif
                    @endif
                </div>
                @php  
                $applocale = 'en';
                if(session()->has('applocale')){
                    $applocale = session()->get('applocale');
                }            
                @endphp
                <ul class="header-dropdown d-none d-sm-inline">                    
                    <!-- <li class="mobile-wishlist d-inline d-sm-none">
                        <a href="{{route('user.wishlists')}}">
                            <i class="fa fa-heart" aria-hidden="true"></i>
                        </a>
                    </li> -->
                    <li class="onhover-dropdown change-language slected-language">
                        <a href="javascript:void(0)">{{$applocale}} 
                        <span class="icon-ic_lang align-middle"></span>
                        <span class="language ml-1 align-middle">{{ __('language') }}</span>
                        </a>
                        <ul class="onhover-show-div">
                            @foreach($languageList as $key => $listl)
                                <li class="{{$applocale ==  $listl->language->sort_code ?  'active' : ''}}">
                                    <a href="javascript:void(0)" class="customerLang" langId="{{$listl->language_id}}">{{$listl->language->name}}</a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                    <li class="onhover-dropdown change-currency slected-language">
                        <a href="#">{{session()->get('iso_code')}} <span class="icon-ic_currency align-middle"></span> 
                        <span class="currency ml-1 align-middle">{{ __('currency') }}</span> </a>
                        <ul class="onhover-show-div">
                            @foreach($currencyList as $key => $listc)
                                <li class="{{session()->get('iso_code') ==  $listc->currency->iso_code ?  'active' : ''}}">
                                    <a href="javascript:void(0)" currId="{{$listc->currency_id}}" class="customerCurr " currSymbol="{{$listc->currency->symbol}}">{{$listc->currency->iso_code}}</a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                    <li class="onhover-dropdown mobile-account"> <i class="fa fa-user" aria-hidden="true"></i>
                        {{__('My Account')}}
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
                </ul>
            </div>

        </div>
    </div>

    <div class="mobile-menu main-menu d-block d-sm-none">
        <div class="menu-right">
            <ul class="header-dropdown icon-nav">
                <li class="onhover-div mobile-setting">
                    <div data-toggle="modal" data-target="#setting_modal"><i class="ti-settings"></i></div>
                    <!-- <div class="show-div setting">
                        <h6>{{ __("language") }}</h6>
                        <ul>
                            <li><a href="#">english</a></li>
                            <li><a href="#">{{ __("french") }}</a></li>
                        </ul>
                        <h6>{{ __("currency") }}</h6>
                        <ul class="list-inline">
                            @foreach($currencyList as $key => $listc)
                                <li class="{{session()->get('iso_code') ==  $listc->currency->iso_code ?  'active' : ''}}">
                                    <a href="javascript:void(0)" currId="{{$listc->currency_id}}" class="customerCurr " currSymbol="{{$listc->currency->symbol}}">{{$listc->currency->iso_code}}</a>
                                </li>
                            @endforeach
                        </ul>
                        <h6>Change Theme</h6>
                        @if($client_preference_detail->show_dark_mode == 1)
                        <ul class="list-inline">
                            <li><a class="theme-layout-version" href="javascript:void(0)">Dark</a></li>
                        </ul>
                        @endif
                    </div> -->
                </li>
                
                <li class="onhover-dropdown mobile-account  d-inline d-sm-none"> <i class="fa fa-user" aria-hidden="true"></i>
                    {{__('My Account')}}
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
                
                @if($client_preference_detail->show_wishlist == 1)
                <li class="mobile-wishlist d-inline d-sm-none">
                    <a href="{{route('user.wishlists')}}">
                        <i class="fa fa-heart" aria-hidden="true"></i>
                    </a>
                </li>
                @endif

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
                
                @if($client_preference_detail->cart_enable == 1)
                <li class="onhover-div mobile-cart">
                    <a href="{{route('showCart')}}" style="position: relative">
                        <i class="ti-shopping-cart"></i>
                        <span class="cart_qty_cls" style="display:none"></span>
                    </a>
                    {{--<span class="cart_qty_cls" style="display:none"></span>--}}
                    <ul class="show-div shopping-cart">
                    </ul>
                </li>
                @endif
            </ul>
        </div>
    </div>

  
</div>

<!-- Modal -->
<div class="modal fade mobile-setting" id="setting_modal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="setting-modalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header border-bottom">
        <h5 class="modal-title" id="setting-modalLabel">Language & Currency</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body pt-0">
        <div class="show-div setting">
            <h6 class="mb-1">{{ __("language") }}</h6>
            <ul>
                <li><a href="#">english</a></li>
                <li><a href="#">{{ __("french") }}</a></li>
            </ul>
            <h6 class="mb-1">{{ __("currency") }}</h6>
            <ul class="list-inline">
                @foreach($currencyList as $key => $listc)
                    <li class="{{session()->get('iso_code') ==  $listc->currency->iso_code ?  'active' : ''}}">
                        <a href="javascript:void(0)" currId="{{$listc->currency_id}}" class="customerCurr " currSymbol="{{$listc->currency->symbol}}">{{$listc->currency->iso_code}}</a>
                    </li>
                @endforeach
            </ul>
            <!-- <h6>Change Theme</h6>
            @if($client_preference_detail->show_dark_mode == 1)
            <ul class="list-inline">
                <li><a class="theme-layout-version" href="javascript:void(0)">Dark</a></li>
            </ul>
            @endif -->
        </div>
      </div>
    </div>
  </div>
</div>