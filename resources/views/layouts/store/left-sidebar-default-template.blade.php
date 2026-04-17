@php
$clientData = \App\Models\Client::select('id', 'logo')->where('id', '>', 0)->first();
$urlImg = $clientData ? $clientData->logo['image_fit'].'150/92'.$clientData->logo['image_path'] : " ";
$languageList = \App\Models\ClientLanguage::with('language')->where('is_active', 1)->orderBy('is_primary',
'desc')->get();
$currencyList = \App\Models\ClientCurrency::with('currency')->orderBy('is_primary', 'desc')->get();
@endphp
<header>
    <div class="mobile-fix-option"></div>
    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top px-4 py-2">
        <a class="navbar-brand" href="{{ route('userHome') }}">
            <img class="img-fluid" alt="" src="{{$urlImg}}">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ml-auto">
                {{-- <li class="nav-item pr-50">
                    <a class="nav-link text-uppercase customnav-active" href="#home">Home</a>
                </li>
                <li class="nav-item pr-50">
                    <a class="nav-link text-uppercase" href="#banner">About Us</a>
                </li>
                <li class="nav-item pr-50">
                    <a class="nav-link text-uppercase" href="#motto">Our Motto</a>
                </li>
                <li class="nav-item pr-50">
                    <a class="nav-link text-uppercase" href="#journey">Our Journey</a>
                </li>
                <li class="nav-item pr-50">
                    <a class="nav-link text-uppercase" href="#faq">FAQ</a>
                </li> --}}
                <li class="nav-item">
                    <a class="btn" href="{{ url('') }}" style="{{ request()->is('/') ? 'color:blue;' : '' }}">Home</a>
                </li>
                <li class="nav-item">
                    <a class="btn" href="{{ route('courier') }}" style="{{ request()->routeIs('courier') ? 'color:blue;' : '' }}">Courier</a>
                </li>
                {{-- <li class="nav-item">
                    <a class="btn" href="{{ route('recovery') }}" style="{{ request()->routeIs('recovery') ? 'color:blue;' : '' }}">Recovery & Pickup</a>
                </li> --}}
                <li class="nav-item">
                    <a class="btn" href="{{ route('extrapage', ['slug' => 'driver-registration']) }}" style="{{ request()->routeIs('extrapage') && request()->route('slug') === 'driver-registration' ? 'color:blue;' : '' }}">
                        Rider Registration
                    </a>
                </li>
                <li class="nav-item">
                    <a style="background-color: #0954E4;border-color: #0954E4;" class="btn btn-success rounded" href="{{ route('redirect.store') }}" target="_blank">Download App</a>
                </li>
            </ul>
        </div>
    </nav>
</header>

