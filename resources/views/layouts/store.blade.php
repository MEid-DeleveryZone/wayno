@php
$set_template = \App\Models\WebStylingOption::where('web_styling_id',1)->where('is_selected',1)->first();
$set_common_business_type = $client_preference_detail->business_type??'';
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
  @include('layouts.store.title-meta', ['title' => $title])
  @include('layouts.store.head-content', ["demo" => "creative"])
  <style>
    :root {
      --theme-deafult: <?=($client_preference_detail) ? $client_preference_detail->web_color: '#ff4c3b'?>;
      --top-header-color: <?=($client_preference_detail) ? $client_preference_detail->site_top_header_color: '#4c4c4c'?>;
    }

    a {
      color: <?=($client_preference_detail) ? $client_preference_detail->web_color: '#ff4c3b'?>;
    }
  </style>
  @if(isset($set_template) && $set_template->template_id == 1)
  <link rel="stylesheet" type="text/css" href="{{asset('front-assets/css/custom-template-one.css')}}">
  @endif
  <link rel="stylesheet" type="text/css" href="{{asset('css/waitMe.min.css')}}">
  <!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
  new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
  j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
  'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
  })(window,document,'script','dataLayer','GTM-W568J2WK');</script>
  <!-- End Google Tag Manager -->
  <!-- Meta Pixel Code -->
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window, document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '1470985167747298');
fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=1470985167747298&ev=PageView&noscript=1"
/></noscript>
<!-- End Meta Pixel Code -->
</head>
@php
$dark_mode = '';
if($client_preference_detail->show_dark_mode == 0){
$dark_mode = '';
}
else if($client_preference_detail->show_dark_mode == 1){
$dark_mode = 'dark';
}
else if($client_preference_detail->show_dark_mode == 2){
if(session()->has('config_theme')){
$dark_mode = session()->get('config_theme');
}
else{
$dark_mode = '';
}
}
@endphp

@if($set_common_business_type == 'taxi')
<style type="text/css">
  .cabbooking-loader {
    width: 30px;
    height: 30px;
    animation: loading 1s infinite ease-out;
    margin: auto;
    border-radius: 50%;
    background-color: red;
  }

  @keyframes loading {
    0% {
      transform: scale(1);
    }

    100% {
      transform: scale(8);
      opacity: 0;
    }
  }

  .site-topbar,
  .main-menu.d-block {
    display: none !important;
  }

  .cab-booking-header img.img-fluid {
    height: 50px;
  }

  .cab-booking-header {
    display: block !important;
  }

  .container .main-menu .d-block {
    display: none;
  }
</style>
@else
<style>
  .cab-booking-header {
    display: none;
  }
</style>
@endif



<body class="{{$dark_mode}}{{ Request::is('category/cabservice') ? 'cab-booking-body' : '' }}"
  dir="{{session()->get('locale') == 'ar' ? 'rtl' : ''}}">
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-W568J2WK"
  height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
  <!-- End Google Tag Manager (noscript) -->
  <div class="bg-dark text-light pl-2 py-1 d-none d-sm-block app-link-box">
    <a href="" class="app-link text-light"></a>
  </div>

  @yield('content')


  @if(isset($set_template) && $set_template->template_id == 1)
    @include('layouts.store/footer-content-template-one')
  @elseif(isset($set_template) && $set_template->template_id == 2)
    @include('layouts.store/footer-content')
  @else
    @include('layouts.store/footer-content')
  @endif

  @if(isset($set_template) && $set_template->template_id == 3)
  @include('layouts.store/default-footer')
  @else
  @include('layouts.store/footer')
  @endif
  
  <div class="loader_box" style="display: none;">
    <div class="spinner-border text-danger m-2 showLoader" role="status"></div>
  </div>
  <div class="spinner-overlay">
    <div class="page-spinner">
      <div class="circle-border">
        <div class="circle-core"></div>
      </div>
    </div>
  </div>
  @yield('script')
  @if($client_preference_detail->hide_nav_bar == 1 || $set_common_business_type == 'taxi')
  <script>
    $('.main-menu').addClass('d-none').removeClass('d-block');
    $('.menu-navigation').addClass('d-none').removeClass('d-block');
  </script>
  @endif

  @if(isset($set_template) && $set_template->template_id == 1)
  <script src="{{asset('front-assets/js/custom-template-one.js')}}"></script>
  @endif
  <script src="{{asset('assets/js/waitMe.min.js')}}"></script>
  <script>
    function startLoader(element,loader_text) {


    // check if the element is not specified
    if(typeof element == 'undefined') {
        console.log(element);
        element = "body";
    }

    // set the wait me loader
    $(element).waitMe({
        effect : 'bounce', //bounce ,rotateplane
        text : loader_text,
        bg : 'rgb(2, 2, 2, 0.7)', // 'rgb(2, 2, 2, 0.7)
        //color : 'rgb(66,35,53)',
        color : 'rgb(242, 242, 242) !important',// change color if want any color for loader
        sizeW : '150px',
        sizeH : '150px',
        source : ''
    });
}

/**
* Start the loader on the particular element
*/
function stopLoader(element) {
    // check if the element is not specified
    if(typeof element == 'undefined') {
        element = 'body';
    }

    // close the loader
    $(element).waitMe("hide");
}
  </script>

  <script>
    const userAgent = navigator.userAgent;
    if(/android/i.test(userAgent)){
      $(".app-link-box").removeClass('d-none');
      $("a.app-link").attr("href", "{{$client_preference_detail->android_app_link}}")
      $(".app-link").html("Get the App");
    }
    else if(/iPad|iPhone|iPod/i.test(userAgent)){
      $(".app-link-box").removeClass('d-none');
      $("a.app-link").attr("href", "{{$client_preference_detail->ios_link}}")
      $(".app-link").html("Get the App");
    }else{
      $(".app-link-box").hide();
    }
  </script>
</body>

</html>