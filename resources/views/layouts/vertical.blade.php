<!DOCTYPE html>
    <html lang="en">

    <head>
        @include('layouts.shared.title-meta', ['title' => $title])
        @include('layouts.shared.head-content', ["demo" => "creative"])
        
        @yield('css')

        <link rel="stylesheet" type="text/css" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" />

        
        <script src="{{asset('assets/js/jquery-3.1.1.min.js')}}"></script>
        <script src="{{asset('assets/js/jquery-ui.min.js')}}" ></script>
   
        <script src="{{asset('assets/js/vendor.min.js')}}"></script>    
        
        
       
       <style type="text/css">
            .loader_box {
                position: fixed;
                width: 100%;
                height: 100%;
                background: #00000075;
                top: 0;
                z-index:99999;
                left: 0;
            }
            .spinner-border{
                position: absolute;
                top: 50%;
                left: 0;
                right: 0;
                margin: 0 auto !important;
                display: block;
            }
           
    .bg-danger-light{
        background-color: #ffbfbf !important;
    }
       </style>

    </head>
    @php 
        $classBody1 = 'light';
        $theme1 = \App\Models\ClientPreference::where(['id' => 1])->first('theme_admin');
        if($theme1 && ($theme1->theme_admin == 'dark' || $theme1->theme_admin == 'Dark')){
            $classBody1 = 'dark';
        } 

        $ll = session()->get('applocale_admin');
        @endphp

    <body class="{{$classBody1}}" @yield('body-extra') @if( isset($ll) && $ll=='ar' ) dir="rtl" @endif>
        <!-- Begin page -->
        <div id="wrapper">
            @include('layouts.shared/topbar')
            @include('layouts.shared/left-sidebar')

        <!-- Start Page Content here -->

        <div class="content-page">                                    
            <div class="content">
               <!-- @php 
                    $style = "";
                    if(session('preferences.twilio_status') != 'invalid_key'){
                        $style = "display:none;";
                    }
                @endphp -->

                <div class="row displaySettingsError" style="{{$style}}">
                    <div class="col-12">
                        <div class="alert alert-danger excetion_keys" role="alert">
                           <!-- @if(session('preferences.twilio_status') == 'invalid_key')
                            <span><i class="mdi mdi-block-helper mr-2"></i> <strong>Twilio</strong> key is not valid</span> <br/>
                            @endif -->
                        </div>
                    </div>
                </div>
                @yield('content')
            </div>
            
        @include('layouts.shared/footer')
        <div class="loader_box" style="display: none;">
            <div class="spinner-border text-danger m-2 showLoader" role="status" ></div>
        </div>

        </div>
        
        <!-- End Page content -->
    </div>

    @include('layouts.shared/right-sidebar')
    @include('layouts.shared/footer-script')

    @yield('script')
    <script src="{{asset('assets/js/app.min.js')}}"></script>
    </body>
</html>