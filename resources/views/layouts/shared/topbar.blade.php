@php
$languageList = \App\Models\ClientLanguage::with('language')->where('is_active', 1)->orderBy('is_primary', 'desc')->get();
$currencyList = \App\Models\ClientCurrency::with('currency')->orderBy('is_primary', 'desc')->get();
@endphp
<!-- Topbar Start -->
<audio id="orderAudio">
    <source src="{{ asset('assets/sounds/notification.ogg')}}" type="audio/ogg">
    <source src="{{ asset('assets/sounds/notification.mp3')}}" type="audio/mpeg">
    Your browser does not support the audio element.
</audio>
<div class="navbar-custom">

    <div class="container-fluid d-flex align-items-center justify-content-between justify-content-lg-end">

        <ul class="top-site-links d-flex align-items-center p-0 mb-0 mr-xl-2 mr-auto">
            <li class="d_none">
                <div class="logo-box">
                    @php
                    $urlImg = URL::to('/').'/assets/images/users/user-1.jpg';
                    $clientData = \App\Models\Client::select('id', 'logo','custom_domain','code')->with('getPreference')->where('id', '>', 0)->first();
                    if($clientData){
                    $urlImg = $clientData->logo['image_fit'].'200/80'.$clientData->logo['image_path'];
                    }
                    @endphp
                    <a href="{{route('client.dashboard')}}" class="logo logo-dark text-center">
                        <span class="logo-sm">
                            <img src="{{ asset('assets/images/logo-sm.png') }}" alt="" height="50">
                        </span>
                        <span class="logo-lg">
                            <img src="{{ asset('assets/images/logo-dark.png') }}" alt="" height="50">
                        </span>
                    </a>

                    <a href="{{route('client.dashboard')}}" class="logo logo-light text-center">
                        <span class="logo-sm">
                            <img src="{{$urlImg}}" alt="" height="30" style="padding-top: 4px;">
                        </span>
                        <span class="logo-lg">
                            <img src="{{$urlImg}}" alt="" height="50" style="padding-top: 4px;">
                        </span>
                    </a>
                </div>
            </li>
            <li class="mobile-toggle">
                <button id="shortclick" class="button-menu-mobile waves-effect waves-light">
                    <i class="fe-menu"></i>
                </button>
            </li>
            <li class="m-hide"><label class="site-name m-0">{{ucFirst($clientData->custom_domain)}}</label></li>
            <li class="m-hide"><a href="{{route('userHome')}}" target="_blank"><i class="fa fa-globe" aria-hidden="true"></i><span class="align-middle">{{ __("View Website") }}</span></a></li>
            <!-- <li class="m-hide"><a href="#" target="_blank"><i class="fab fa-apple" aria-hidden="true"></i><span class="align-middle">iOS App</span></a></li>
            <li class="m-hide"><a href="#" target="_blank"><i class="fab fa-android" aria-hidden="true"></i><span class="align-middle">Android App</span></a></li> -->

            @if(Auth::user()->is_superadmin == 1)
            <!-- @if($clientData->getPreference->need_delivery_service  == 1 && isset($clientData->getPreference->delivery_service_key_url))
                <li class="m-hide"><a href="{{ $clientData->getPreference->delivery_service_key_url }}" target="_blank"><i class="fa fa-globe" aria-hidden="true"></i><span class="align-middle">{{ __('Last Mile Delivery Dashboard')}}</span></a></li>
            @endif -->
            @if($clientData->getPreference->need_dispacher_ride == 1 && isset($clientData->getPreference->pickup_delivery_service_key_url))
            <li class="m-hide"><a href="{{ $clientData->getPreference->pickup_delivery_service_key_url }}" target="_blank"><i class="fa fa-globe" aria-hidden="true"></i><span class="align-middle">{{ __('Pickup & Delivery  Dashboard')}}</span></a></li>
            @endif
            @endif
        </ul>


        <!-- LOGO -->
        <!-- <div class="logo-box d-inline-block d-lg-none">
            @php
                $urlImg = URL::to('/').'/assets/images/users/user-1.jpg';
                $clientData = \App\Models\Client::select('id', 'logo')->where('id', '>', 0)->first();
                if($clientData){
                    $urlImg = $clientData->logo['image_fit'].'200/80'.$clientData->logo['image_path'];
                }
            @endphp
            <a href="{{route('client.dashboard')}}" class="logo logo-dark text-center">
                <span class="logo-sm">
                    <img src="{{ asset('assets/images/logo-sm.png') }}" alt="" height="50">                    
                </span>
                <span class="logo-lg">
                    <img src="{{ asset('assets/images/logo-dark.png') }}" alt="" height="20">
                </span>
            </a>
            
            <a href="{{route('client.dashboard')}}" class="logo logo-light text-center">
                <span class="logo-sm">
                    <img src="{{$urlImg}}"
                        alt="" height="30" style="padding-top: 4px;">
                </span>
                <span class="logo-lg">
                    <img src="{{$urlImg}}"
                        alt="" height="50" style="padding-top: 4px;">
                </span>
            </a>
        </div> -->


        {{-- ADMIN LANGUAGE SWITCH START --}}

        @php
        $applocale_admin = 'en';
        if(session()->has('applocale_admin')){
        $applocale_admin = session()->get('applocale_admin');
        }
        @endphp

        <ul class="list-unstyled topnav-menu float-right mb-0">
            <li class="dropdown ">
                <a class="nav-link dropdown-toggle waves-effect waves-light" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                    Language
                    {{ $applocale_admin }}
                    <i class="mdi mdi-chevron-down"></i>
                </a>
                <ul class="dropdown-menu">
                    {{-- <a href="/switch/admin/language?lang=en" class="dropdown-item" langid="1">English</a>
                    <a href="/switch/admin/language?lang=es" class="dropdown-item" langid="1">Spanish</a>
                    <a href="/switch/admin/language?lang=ar" class="dropdown-item" langid="1">Arabic</a>
                    <a href="/switch/admin/language?lang=fr" class="dropdown-item" langid="1">French</a>
                    <a href="/switch/admin/language?lang=de" class="dropdown-item" langid="1">German</a> --}}
                    
                    @foreach($languageList as $key => $listl)
                        <li>
                            <a href="/switch/admin/language?lang={{$listl->language->sort_code}}&langid={{$listl->language_id}}" class="customerLang dropdown-item {{$applocale_admin ==  $listl->language->sort_code ?  'active' : ''}}" langid="{{$listl->language_id}}">{{$listl->language->name}}</a>
                        </li>
                    @endforeach
                    
                    <div class="dropdown-divider"></div>
                </ul>
            </li>


            {{-- ADMIN LANGUAGE SWITCH END --}}


            <li class="dropdown d-inline-block d-lg-none">
                <!-- <a class="nav-link dropdown-toggle arrow-none waves-effect waves-light" data-toggle="dropdown" href="#"
                    role="button" aria-haspopup="false" aria-expanded="false">
                    <i class="fe-search noti-icon"></i>
                </a> -->

                <div class="dropdown-menu dropdown-lg dropdown-menu-right p-0">
                    <form class="p-3">
                        <input type="text" class="form-control" placeholder="{{ __("Search") }} ..." aria-label="Recipient's username">
                    </form>
                </div>
            </li>
            {{-- <li class="dropdown d-none d-lg-inline-block">
                <a class="nav-link dropdown-toggle arrow-none waves-effect waves-light" data-toggle="fullscreen"
                    href="#">
                    <i class="fe-bell noti-icon"></i>
                </a>
            </li>
            <li class="dropdown d-lg-inline-block">
                <a class="nav-link dropdown-toggle arrow-none waves-effect waves-light" data-toggle="fullscreen"
                    href="#">
                    <i class="fe-maximize noti-icon"></i>
                </a>
            </li>--}}

            <li class="dropdown notification-list topbar-dropdown">
                <a class="nav-link dropdown-toggle nav-user mr-0 waves-effect waves-light" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">

                    <span class="pro-user-name ml-1">
                        <img src="<?= $favicon ?>" alt="">
                        <!-- <b class="text-capitalize">{{ auth()->user()->name }} <i class="mdi mdi-chevron-down"></i></b> -->
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-right profile-dropdown p-0">

                    <!-- <div class="dropdown-header noti-title">
                        <h6 class="text-overflow m-0">Welcome !</h6>
                    </div> -->

                    <a href="{{route('userHome')}}" class="dropdown-item notify-item">
                        <i class="fe-globe"></i>
                        <span>{{ __("Website") }}</span>
                    </a>
                    @if(Auth::user()->is_superadmin == 1)
                    <a href="{{route('client.profile')}}" class="dropdown-item notify-item">
                        <i class="fe-user"></i>
                        <span>{{ __("My Account") }}</span>
                    </a>
                    @endif
                    <!-- <a href="javascript:void(0)" class="dropdown-item notify-item" data-toggle="modal" data-target="#change_password">
                        <i class="fe-user"></i>
                        <span>{{ __("Change Password") }}</span>
                    </a> -->

                    <a class="dropdown-item notify-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"> <i class="fe-log-out"></i> <span>Logout</span>
                    </a>

                    <form id="logout-form" action="{{route('client.logout')}}" method="POST">
                        @csrf
                    </form>
                </div>
            </li>
        </ul>
    </div>
</div>

<!-- Change Password Modal -->
<div class="modal fade" id="change_password" tabindex="-1" aria-labelledby="change_passwordLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <form method="post" action="{{route('client.password.update')}}">
                    @csrf

                    <h4 class="header-title">{{ __("Change Password") }}</h4>
                    <p class="sub-header">
                        {{-- <code>Organization details</code>/Change Password. --}}
                    </p>
                    <div class="row">
                        <div class="col">
                            <div class="form-group mb-2">
                                <label for="old_password">{{ __("Old Password") }}</label>
                                <div class="input-group input-group-merge ">
                                    <input class="form-control " name="old_password" type="password" required="" id="old_password" placeholder={{ __("Enter your old password") }}>
                                    <div class="input-group-append" data-password="false">
                                        <div class="input-group-text">
                                            <span class="password-eye"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if($errors->has('old_password'))
                            <span class="text-danger" role="alert">
                                <strong>{{ $errors->first('old_password') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="form-group mb-2">
                                <label for="password">{{ __("New Password") }}</label>
                                <div class="input-group input-group-merge ">
                                    <input class="form-control " name="password" type="password" required="" id="password" placeholder={{__("Enter your password")}}>
                                    <div class="input-group-append" data-password="false">
                                        <div class="input-group-text">
                                            <span class="password-eye"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if($errors->has('password'))
                            <span class="text-danger" role="alert">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                            @endif
                        </div>

                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group mb-2">
                                <label for="confirm_password">{{ __("Confirm Password") }}</label>
                                <div class="input-group input-group-merge ">
                                    <input class="form-control " name="password_confirmation" type="password" required="" id="confirm_password" placeholder={{ __("Enter your confirm password") }}>
                                    <div class="input-group-append" data-password="false">
                                        <div class="input-group-text">
                                            <span class="password-eye"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if($errors->has('password_confirmation'))
                            <span class="text-danger" role="alert">
                                <strong>{{ $errors->first('password_confirmation') }}</strong>
                            </span>
                            @endif
                        </div>

                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="form-group mb-0 text-cente2">
                                <button class="btn btn-info btn-block w-100" type="submit"> {{ __("Update") }} </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>