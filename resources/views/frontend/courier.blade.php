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
      margin: 12%;
  }

  .carousel-item .description {
      display: flex;
      flex-direction: column;
      justify-content: center;
      text-align: left;
  }
</style>

<div class="p-md-4 p-xs-2">
  <section class="image-description">
    <div class="row">
      <div class="col-sm-6">
        <img src="{{asset('images/courier-title-banner.png')}}" alt="Order Summary" style="max-width: 100%;">
      </div>
      <div class="col-sm-6" style="padding-top: 15%;">
        <h2 class="gradient-text" style="font-size: 75px">Courier</h2>
        <p>Got a package that needs to get somewhere fast, anywhere across the UAE? Our streamlined courier service
          ensures your items are delivered efficiently, whether it's across town or to another emirate.</p>
      </div>
    </div>
  </section>
</div>

<div class="container-fluid">
  <h2 class="gradient-text" style="font-size: 48px;margin:5%;text-align:center">How it works?</h2>
  <div id="carouselExample" class="carousel slide" data-ride="carousel" data-interval="9000">
      <div class="carousel-inner row w-100 mx-auto" role="listbox">
          <div class="carousel-item col-12 active">
              <div class="row">
                  <div class="grow col-6">
                      <img class="img-fluid mx-auto d-block" src="{{asset('images/courier/step1.png')}}?text=1" alt="slide 1">
                  </div>
                  <div class="col-6 description">
                      <h3 class="gradient-text">1. Accessing the home screen</h3>
                      <p>Launch wayno mobile application on your device. Upon opening, you will land on the home page of the application. From the home page, locate and tap on the "Courier" option.</p>
                  </div>
              </div>
          </div>
          <div class="carousel-item col-12">
              <div class="row">
                  <div class="grow col-6">
                      <img class="img-fluid mx-auto d-block" src="{{asset('images/courier/step2.png')}}" alt="slide 2">
                  </div>
                  <div class="col-6 description">
                      <h3 class="gradient-text">2. Selecting Courier Service</h3>
                      <p>After selecting courier option in the home page, You will be prompted to select both the pickup and drop-off locations.</p>
                  </div>
              </div>
          </div>
          <div class="carousel-item col-12">
              <div class="row">
                  <div class="grow col-6">
                      <img class="img-fluid mx-auto d-block" src="{{asset('images/courier/step3.png')}}" alt="slide 3">
                  </div>
                  <div class="col-6 description">
                      <h3 class="gradient-text">3. Filling Pickup Details</h3>
                      <p>Fill in the necessary details such as street name, tag name, etc.</p>
                  </div>
              </div>
          </div>
          <div class="carousel-item col-12">
              <div class="row">
                  <div class="grow col-6">
                      <img class="img-fluid mx-auto d-block" src="{{asset('images/courier/step4.png')}}" alt="slide 4">
                  </div>
                  <div class="col-6 description">
                      <h3 class="gradient-text">4. Using Map for Pickup Location</h3>
                      <p>Optionally, you can use the map feature to pinpoint your pickup location accurately.</p>
                  </div>
              </div>
          </div>
          <div class="carousel-item col-12">
              <div class="row">
                  <div class="grow col-6">
                      <img class="img-fluid mx-auto d-block" src="{{asset('images/courier/step5.png')}}" alt="slide 4">
                  </div>
                  <div class="col-6 description">
                      <h3 class="gradient-text">5. Providing Dropoff Address</h3>
                      <p>Input the complete address where the package needs to be delivered.</p>
                  </div>
              </div>
          </div>
          <div class="carousel-item col-12">
              <div class="row">
                  <div class="grow col-6">
                      <img class="img-fluid mx-auto d-block" src="{{asset('images/courier/step6.png')}}" alt="slide 4">
                  </div>
                  <div class="col-6 description">
                      <h3 class="gradient-text">6. Choosing Courier Size</h3>
                      <p>Choose from three different courier sizes available in the app, each with specific dimensions suitable for varying weights.</p>
                  </div>
              </div>
          </div>
          <div class="carousel-item col-12">
              <div class="row">
                  <div class="grow col-6">
                      <img class="img-fluid mx-auto d-block" src="{{asset('images/courier/step7.png')}}" alt="slide 4">
                  </div>
                  <div class="col-6 description">
                      <h3 class="gradient-text">7. Order Summary and Verification</h3>
                      <p>On the summary screen, verify the entered pickup, dropoff locations along with the selected courier size. Provide your contact details to ensure smooth communication regarding the delivery.</p>
                  </div>
              </div>
          </div>
          <div class="carousel-item col-12">
              <div class="row">
                  <div class="grow col-6">
                      <img class="img-fluid mx-auto d-block" src="{{asset('images/courier/step8.png')}}" alt="slide 4">
                  </div>
                  <div class="col-6 description">
                      <h3 class="gradient-text">8. Completing the Order</h3>
                      <p>Proceed to confirm and place your courier order through the app. Once the order is successfully placed, you will receive a confirmation and can view detailed order information.</p>
                  </div>
              </div>
          </div>
          <div class="carousel-item col-12">
              <div class="row">
                  <div class="grow col-6">
                      <img class="img-fluid mx-auto d-block" src="{{asset('images/courier/step9.png')}}" alt="slide 4">
                  </div>
                  <div class="col-6 description">
                      <h3 class="gradient-text">9. Tracking the Order</h3>
                      <p>Navigate to the order tracking screen within the app and track the real-time status of your courier delivery from pickup to dropoff.</p>
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
    <!-- Image banner -->
    <img src="{{asset('images/delivery-footer.png')}}" alt="Banner Image" class="img-fluid">
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