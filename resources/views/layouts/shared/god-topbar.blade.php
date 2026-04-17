<!-- Topbar Start -->
<div class="navbar-custom">
    <div class="container-fluid">
        <ul class="list-unstyled topnav-menu float-right mb-0">

            <li class="d-none d-lg-block">
                <form class="app-search">
                    <div class="app-search-box dropdown">
                    </div>
                </form>
            </li>
    
            <li class="dropdown d-inline-block d-lg-none">
                <a class="nav-link dropdown-toggle arrow-none waves-effect waves-light" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                    <i class="fe-search noti-icon"></i>
                </a>
                <div class="dropdown-menu dropdown-lg dropdown-menu-right p-0">
                    <form class="p-3">
                        <input type="text" class="form-control" placeholder="Search ..." aria-label="Recipient's username">
                    </form>
                </div>
            </li>

            <li class="dropdown d-none d-lg-inline-block">
                <a class="nav-link dropdown-toggle arrow-none waves-effect waves-light" data-toggle="fullscreen" href="#">
                    <i class="fe-maximize noti-icon"></i>
                </a>
            </li>

            <li class="dropdown notification-list topbar-dropdown">
                <a class="nav-link dropdown-toggle nav-user mr-0 waves-effect waves-light" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                    {{-- <img src="{{ isset(Auth::user()->logo) ? asset('clients/'.Auth::user()->logo.'') : asset('assets/images/users/user-1.jpg') }}" alt="user-image" class="rounded-circle"> --}}
                    <span class="pro-user-name ml-1">
                        {{ auth()->user()->company_name ?? auth()->user()->name  }} <i class="mdi mdi-chevron-down"></i> 
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                    
                    <div class="dropdown-header noti-title">
                        <h6 class="text-overflow m-0">Welcome !</h6>
                    </div>

                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i class="fe-user"></i>
                        <span>My Account</span>
                    </a>
    
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item notify-item" href="{{ route('god.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"> <i class="fe-log-out"></i><span>Logout</span>
                    </a>

                <form id="logout-form" action="{{ route('god.logout') }}" method="POST" >
                    @csrf
                </form>
    
                </div>
            </li>
        </ul>


    
        <!-- LOGO -->
        <div class="logo-box d-none d-lg-block">
            <a href="{{route('client.index')}}" class="logo logo-dark text-center">
                <span class="logo-sm">
                    <img src="{{asset('assets/images/logo-dark.png')}}"alt="" height="50">
                    <!-- <span class="logo-lg-text-light">UBold</span> -->
                </span>
                <span class="logo-lg">
                    <img src="{{asset('assets/images/logo-dark.png')}}"alt="" height="20">
                    <!-- <span class="logo-lg-text-light">U</span>
                    $urlImg = \Storage::disk('s3')->url('assets/client_00000125/agents5fc76c71abdb3.png/A9B2zHkr5thbcyTKHivaYm4kNYrSXOiov6USdFpV.png'); 
                    -->
                </span>
            </a>

            @php
                
             $urlImg = asset('assets/images/logo-dark.png');
             //$image = \Phumbor::url($urlImg)->fitIn(90,50);

            @endphp
    
            <a href="{{route('client.index')}}" class="logo logo-light text-center">
                <span class="logo-sm">
                    <img src="{{$urlImg}}"alt="" height="30" style="padding-top: 4px;">
                </span>
                <span class="logo-lg">
                    <img src="{{$urlImg}}"alt="" height="50" style="padding-top: 4px;">
                </span>
            </a>
        </div>
    
        <ul class="list-unstyled topnav-menu topnav-menu-left m-0">
            <li>
                <button class="button-menu-mobile waves-effect waves-light">
                    <i class="fe-menu"></i>
                </button>
            </li>

            <li>
                <a class="navbar-toggle nav-link" data-toggle="collapse" data-target="#topnav-menu-content">
                    <div class="lines">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </a>
            </li>   
            
        
        </ul>
    </div>
</div>
