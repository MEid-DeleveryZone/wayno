@extends('layouts.store', ['title' => __('Home')])
@section('content')
@include('layouts.store/left-sidebar-default-template')

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
    .pr-50{
        padding-right: 50px;
    }
    .pt-70{
        padding-top: 70px;
    }
    .customnav-active{
        font-weight: bold !important; 
        color: rgb(9, 84, 228) !important;
    }
</style>
<style>
    /*hover effect*/
    .grow img{
        transition: 1s ease;
    }
  
    .grow img:hover{
        -webkit-transform: scale(1.2);
        -ms-transform: scale(1.2);
        transform: scale(1.2);
        transition: 1s ease;
    }
    /*hover effect*/
    .gradient-text{
        font-weight: 900;
      background: linear-gradient(to right, #04286D, #084DD3);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .carousel-item {
        display: none;
        transition: none;
    }
    .carousel-item.active {
        display: block;
    }

    .carousel-item img {
        max-width: 50%;
        height: auto;
    }

    .carousel-item .description {
        display: flex;
        flex-direction: column;
        justify-content: center;
        text-align: left;
    }
</style>
<div class="p-md-4 p-xs-2">
    <section id="banner" class="pt-70 mb-4">
        <div class="banner">
            <h1 class="gradient-text" style="font-size: 75px;text-align: center;margin:5%">Recovery & Pickup</h1>
            <!-- Image banner -->
            <img src="{{asset('images/recovery-title-banner.png')}}" alt="Banner Image" class="img-fluid">
            <p style="text-align: center;">Need a tow truck in a pinch?  Our app connects you with reliable recovery services, getting you back on the road quickly and safely. No more waiting by the side of the road – a few taps and help is on its way.</p>
        </div>
    </section>
</div>
<div class="container-fluid">
    <h2 class="gradient-text" style="font-size: 48px;margin:5%;text-align:center">How it works?</h2>
    <div id="carouselExample" class="carousel slide" data-ride="carousel" data-interval="3000">
        <div class="carousel-inner row w-100 mx-auto" role="listbox">
            <div class="carousel-item col-12 active">
                <div class="row">
                    <div class="grow col-6">
                        <img class="img-fluid mx-auto d-block" src="{{asset('images/recovery/step1.png')}}?text=1" alt="slide 1">
                    </div>
                    <div class="col-6 description">
                        <h3 class="gradient-text">1. Accessing the Home Screen</h3>
                        <p>Launch wayno mobile application on your device. Upon opening, you will land on the home page of the application. From the home page, locate and tap on the "Recovery & pickup" option.</p>
                    </div>
                </div>
            </div>
            <div class="carousel-item col-12">
                <div class="row">
                    <div class="grow col-6">
                        <img class="img-fluid mx-auto d-block" src="{{asset('images/recovery/step2.png')}}" alt="slide 2">
                    </div>
                    <div class="col-6 description">
                        <h3 class="gradient-text">2. Selecting Pickup Location</h3>
                        <p>You will be directed to the pickup details screen featuring a map. Use the map interface to pinpoint the exact pickup location.</p>
                    </div>
                </div>
            </div>
            <div class="carousel-item col-12">
                <div class="row">
                    <div class="grow col-6">
                        <img class="img-fluid mx-auto d-block" src="{{asset('images/recovery/step3.png')}}" alt="slide 3">
                    </div>
                    <div class="col-6 description">
                        <h3 class="gradient-text">3. Selecting Dropoff Location</h3>
                        <p>select the dropoff location using the map provided.</p>
                    </div>
                </div>
            </div>
            <div class="carousel-item col-12">
                <div class="row">
                    <div class="grow col-6">
                        <img class="img-fluid mx-auto d-block" src="{{asset('images/recovery/step4.png')}}" alt="slide 4">
                    </div>
                    <div class="col-6 description">
                        <h3 class="gradient-text">4. Choosing Vehicle Category</h3>
                        <p>You will see different vehicle categories available such as Standard Vehicle Recovery, VIP Vehicle Recovery, Pickup Truck Services, Heavy Pickup Services, etc. Select the type of vehicle service that meets your requirements.</p>
                    </div>
                </div>
            </div>
            <div class="carousel-item col-12">
                <div class="row">
                    <div class="grow col-6">
                        <img class="img-fluid mx-auto d-block" src="{{asset('images/recovery/step5.png')}}" alt="slide 4">
                    </div>
                    <div class="col-6 description">
                        <h3 class="gradient-text">5.Verification and Order Placement</h3>
                        <p>Before placing the order, verify the selected pickup and dropoff locations. View estimated time of arrival (ETA) for the assistance vehicle to reach the pickup location, as well as the distance. Set the preferred date and time for the service. Enter pickup and dropoff contact details, along with the vehicle number. Complete the process by proceeding to payment for the selected service.</p>
                    </div>
                </div>
            </div>
            <div class="carousel-item col-12">
                <div class="row">
                    <div class="grow col-6">
                        <img class="img-fluid mx-auto d-block" src="{{asset('images/recovery/step6.png')}}" alt="slide 4">
                    </div>
                    <div class="col-6 description">
                        <h3 class="gradient-text">6. Tracking the Assistance Vehicle</h3>
                        <p>After payment confirmation, navigate to the tracking screen within the app. Track the status of the assistance vehicle en route to the pickup location. If needed, utilize the app to contact both the assistant and customer support for any inquiries or updates.</p>
                    </div>
                </div>
            </div>
            <!-- Add more carousel items as needed -->
        </div>
        <a class="carousel-control-prev" href="#carouselExample" role="button" data-slide="prev">
            <i class="fa fa-chevron-left fa-lg text-muted"></i>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next text-faded" href="#carouselExample" role="button" data-slide="next">
            <i class="fa fa-chevron-right fa-lg text-muted"></i>
            <span class="sr-only">Next</span>
        </a>
    </div>
</div>
<section id="banner" class="pt-70 mb-4">
    <div class="banner">
        <h2 style="text-align: center;">Service Vehicle Guide</h2>
        <!-- Image banner -->
        <img src="{{asset('images/recovery-footer.png')}}" alt="Banner Image" class="img-fluid">
    </div>
</section>
@endsection
@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-beta/js/bootstrap.min.js"></script>
<script>
    $('#carouselExample').on('slide.bs.carousel', function (e) {
        var $e = $(e.relatedTarget);
        var idx = $e.index();
        var itemsPerSlide = 1;
        var totalItems = $('.carousel-item').length;

        if (idx >= totalItems-(itemsPerSlide-1)) {
            var it = itemsPerSlide - (totalItems - idx);
            for (var i=0; i<it; i++) {
                // append slides to end
                if (e.direction=="left") {
                    $('.carousel-item').eq(i).appendTo('.carousel-inner');
                } else {
                    $('.carousel-item').eq(0).appendTo('.carousel-inner');
                }
            }
        }
    });
</script>
@endsection
