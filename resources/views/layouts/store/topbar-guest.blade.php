@php
$clientData = \App\Models\Client::select('id', 'logo')->where('id', '>', 0)->first();

$urlImg = $clientData->logo['proxy_url'].'200/80'.$clientData->logo['image_path'];
$languageList = \App\Models\ClientLanguage::with('language')->where('is_active', 1)->orderBy('is_primary', 'desc')->get();
$currencyList = \App\Models\ClientCurrency::with('currency')->orderBy('is_primary', 'desc')->get();
@endphp
<div class="top-header site-topbar">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7">
            @if($client_preference_detail->show_contact_us == 1)
                {{-- <div class="header-contact">
                    <ul>
                        <li>{{session('client_config') ? session('client_config')->company_name : ''}}</li>
                        <!-- <li><i class="fa fa-phone" aria-hidden="true"></i>{{__('Call Us')}}: {{session('client_config') ? session('client_config')->phone_number : ''}}</li> -->
                    </ul>
                </div> --}}
            @endif
            </div>

                                  

            @php  
            $applocale = 'en';
            if(session()->has('applocale')){
                $applocale = session()->get('applocale');
            }            
            @endphp
       
            <div class="col-lg-5 text-right">
                <ul class="header-dropdown">
                    <li class="onhover-dropdown change-language">
                        <a href="javascript:void(0)">{{$applocale}}
                        <span class="icon-ic_lang align-middle"></span>
                        <span class="language ml-1 align-middle">{{ __("language") }}</span>
                        </a>
                        <ul class="onhover-show-div">
                            @foreach($languageList as $key => $listl)
                                <li class="{{$applocale ==  $listl->language->sort_code ?  'active' : ''}}">
                                    <a href="javascript:void(0)" class="customerLang" langId="{{$listl->language_id}}">{{$listl->language->name}}</a>
                                </li>
                            @endforeach

                            {{-- language switch --}}
                            {{-- <li class={{ $applocale === 'en' ? "active" : "" }} > <a href="/switch/language?lang=en" class="customerLang" langid="1">English</a> </li>
                            <li class={{ $applocale === 'ar' ? "active" : "" }} > <a href="/switch/language?lang=ar" class="customerLang" langid="1">Arabic</a> </li>
                            <li class={{ $applocale === 'fr' ? "active" : "" }} > <a href="/switch/language?lang=fr" class="customerLang" langid="1">French</a> </li>
                            <li class={{ $applocale === 'de' ? "active" : "" }} > <a href="/switch/language?lang=de" class="customerLang" langid="1">Germany</a> </li>
                            <li class={{ $applocale === 'es' ? "active" : "" }} > <a href="/switch/language?lang=es" class="customerLang" langid="1">Spanish</a> </li> --}}                            
                            {{-- language switch --}}
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
                </ul>
            </div>
        </div>
    </div>

    <div class="mobile-menu main-menu d-block d-sm-none">
        <div class="menu-right">
            <ul class="header-dropdown icon-nav">
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
                    </div>
                </li>
                
                <li class="onhover-dropdown mobile-account  d-inline d-sm-none"> <i class="fa fa-user" aria-hidden="true"></i>
                    {{__('My Account')}}
                    <ul class="onhover-show-div">
                        <li>
                            <a href="{{route('customer.login')}}" data-lng="en">{{__('Login')}}</a>
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