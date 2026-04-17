<div class="left-side-menu">
    <div class="h-100" data-simplebar>
        <div class="user-box text-center">
            <img src="{{asset('assets/images/users/user-1.jpg')}}" alt="user-img" title="Mat Helme"
                class="rounded-circle avatar-md">
            <div class="dropdown">
                <a href="javascript: void(0);" class="text-dark dropdown-toggle h5 mt-2 mb-1 d-block"
                    data-toggle="dropdown">User</a>
                <div class="dropdown-menu user-pro-dropdown">
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i class="fe-user mr-1"></i>
                        <span>My Account</span>
                    </a>
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i class="fe-log-out mr-1"></i>
                        <span>Logout</span>
                    </a>
                </div>
            </div>
            <p class="text-muted">Admin Head</p>
        </div>
        <div id="sidebar-menu">
            <ul id="side-menu">
                <li>
                    <a href="{{route('client.index')}}">
                        <i data-feather="users"></i>
                        <span> Clients </span>
                    </a>
                </li>
                <li>
                    <a href="{{route('language.index')}}">
                        <i data-feather="layout" class="icon-dual"></i>
                        <span> Language </span>
                    </a>
                </li>
                <li>
                    <a href="{{route('currency.index')}}">
                        <i data-feather="dollar-sign" class="icon-dual"></i>
                        <span> Currency </span>
                    </a>
                </li>
                <li>
                    <a href="{{route('map.index')}}">
                        <i data-feather="map" class="icon-dual"></i>
                        <span> Map Providers </span>
                    </a>
                </li>
                <li>
                    <a href="{{route('sms.index')}}">
                        <i data-feather="message-square" class="icon-dual"></i>
                        <span> SMS Providers </span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
