@extends('layouts.store', ['title' => 'Product'])
@section('content')




    <!-- Cab Booking Start From Here -->
    <section class="jet-taxi-banner">
        <div class="container">
            <div class="row">
                <div class="col-md-6 mb-md-0 mb-4">
                    <div class="jet-left-content">
                        <h1>Download App, <br class="d-none d-md-block"> Start driving,<br class="d-none d-md-block"> Earn money !</h1>
                        <p>Download the Jet App from playstore, create <br class="d-none d-md-block"> account, use your car and drive by yourself. Get rides and <br class="d-none d-md-block">earn money with Jet.</p>
                        <ul class="d-flex align-items-center">
                            <li class="mr-3">
                                <a href="#"><img src="{{asset('assets/images/iosstore.png')}}" alt=""></a>
                            </li>
                            <li>
                                <a href="#"><img src="{{asset('assets/images/playstore.png')}}" alt=""></a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-6 text-center">
                    <div class="jet-banner-right">
                        <img class="img-fluid" src="{{asset('assets/images/ic_bannerimage@2x.png')}}" alt="">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Cab Content Area Start From Here -->

    <div class="cab-content-area">

    <!-- How It Works Section -->
    <section class="how-it-works py-lg-5 py-4">
        <div class="container">
            <div class="row mb-lg-4 mb-3">
                <div class="col-12 text-center">
                    <h2>How It Works</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6 col-lg-3 mb-lg-4 mb-3">
                    <div class="work-box text-center">
                        <div class="work-icon">
                            <img src="{{asset('assets/images/ic_online.svg')}}" alt="">
                        </div>
                        <h3>Get Online</h3>
                        <p>Turn your availability on and start getting requests from nearby for tasks.</p>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3 mb-lg-4 mb-3">
                    <div class="work-box text-center">
                        <div class="work-icon">
                            <img src="{{asset('assets/images/ic_accept.svg')}}" alt="">
                        </div>
                        <h3>Acccept request</h3>
                        <p>Accept the task request on viewing the task details and head to the pickup location.</p>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3 mb-lg-4 mb-3">
                    <div class="work-box text-center">
                        <div class="work-icon">
                            <img src="{{asset('assets/images/ic_ride.svg')}}" alt="">
                        </div>
                        <h3>Start ride</h3>
                        <p>Once you have reached the pickup location, start the ride after pickup.</p>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3 mb-lg-4 mb-3">
                    <div class="work-box text-center">
                        <div class="work-icon">
                            <img src="{{asset('assets/images/ic_end.svg')}}" alt="">
                        </div>
                        <h3>End ride</h3>
                        <p>End the ride after dropping off the customer. Get the detailed bill on your App.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Enjoy unlimited benifits with Jet App -->
    <section class="jet-app-section pb-5">
        <div class="container">
            <div class="row mb-lg-5 mb-4">
                <div class="col-12 text-center">
                    <h2>Enjoy unlimited benifits with Jet App</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 order-lg-1">
                    <div class="app-img">
                        <img class="img-fluid" src="{{asset('assets/images/ic_phone1@2x.png')}}" alt="">
                    </div>
                </div>
                <div class="col-sm-6 col-lg-4 py-xl-5 text-lg-right d-flex align-items-lg-end justify-content-between flex-column pr-lg-5 order-lg-0">
                    <div class="app-content">
                        <div class="number ml-lg-auto">
                            1
                        </div>
                        <h4>Unlimited ride requets</h4>
                        <p>Get non- Stop ride requests once you register as a driver.</p>
                    </div>
                    <div class="app-content">
                        <div class="number ml-lg-auto">
                            3
                        </div>
                        <h4>No hidden costs</h4>
                        <p>Get non- Stop ride requests once you register as a driver.</p>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-4 py-xl-5 d-flex align-items-start justify-content-between flex-column pl-lg-5 order-lg-2">
                    <div class="app-content">
                        <div class="number">
                            2
                        </div>
                        <h4>Easy to operate</h4>
                        <p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.</p>
                    </div>
                    <div class="app-content">
                        <div class="number">
                            4
                        </div>
                        <h4>Maximum Profits</h4>
                        <p>Get non- Stop ride requests once you register as a driver.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Cab Section -->
    <section class="cab-bottom-banner">
        <img class="img-fluid" src="{{asset('assets/images/ic_bg1@2x.png')}}" alt="">
    </section>
        

    </div>

    


    <script src="{{asset('front-assets/js/popper.min.js')}}"></script>
    <script src="{{asset('front-assets/js/slick.js')}}"></script>
    <script src="{{asset('front-assets/js/menu.js')}}"></script>
    <script src="{{asset('front-assets/js/lazysizes.min.js')}}"></script>
    <script src="{{asset('front-assets/js/bootstrap.js')}}"></script>
    <script src="{{asset('front-assets/js/jquery.elevatezoom.js')}}"></script>
    <script src="{{asset('front-assets/js/underscore.min.js')}}"></script>
    <script src="{{asset('front-assets/js/script.js')}}"></script>
    <script src="{{asset('js/custom.js')}}"></script>
    <script src="{{asset('js/location.js')}}"></script>
    <script src="{{asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{asset('assets/libs/bootstrap-colorpicker/bootstrap-colorpicker.min.js')}}"></script>
    <script src="{{asset('assets/libs/flatpickr/flatpickr.min.js')}}"></script>
    <script src="{{asset('assets/libs/clockpicker/clockpicker.min.js')}}"></script>

    <script src="{{asset('assets/js/pages/form-pickers.init.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.1/bootstrap3-typeahead.min.js"></script>
    
    <script type="text/javascript">
        
    </script>

    <script>
        // Example starter JavaScript for disabling form submissions if there are invalid fields
        (function() {
        'use strict';
        window.addEventListener('load', function() {
            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.getElementsByClassName('needs-validation');
            // Loop over them and prevent submission
            var validation = Array.prototype.filter.call(forms, function(form) {
            form.addEventListener('submit', function(event) {
                if (form.checkValidity() === false) {
                event.preventDefault();
                event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
            });
        }, false);
        })();
    </script>
    <script>
  var loadFile = function(event) {
    var output = document.getElementById('output');
    output.src = URL.createObjectURL(event.target.files[0]);
   };
    </script>
    <script>
  var loadFile = function(event) {
    var banner = document.getElementById('banner');
    banner.src = URL.createObjectURL(event.target.files[0]);
   };

  
    </script>

   
    
@endsection