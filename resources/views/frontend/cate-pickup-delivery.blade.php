@extends('layouts.store', ['title' => 'Product'])
@section('content')
<section class="cab-booking pt-0">
    <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d4850.865733603189!2d76.82393041076074!3d30.716149768967526!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2sin!4v1627015845978!5m2!1sen!2sin" width="100%" height="100vh" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
    <div class="booking-experience ds bc">
        <form class="address-form">
            <div class="location-box">
                <ul class="location-inputs position-relative pl-2">
                    <li class="d-block mb-3 dots">
                        <input class="form-control pickup-text" type="text" placeholder="From CH Devi Lal Centre of Learning" id="from_destination">
                        <i class="fa fa-times ml-1" aria-hidden="true"></i>
                    </li>
                    <li class="d-block mb-3 dots">
                        <input class="form-control pickup-text" type="text" placeholder="To Sector 14" id="to_destination">
                        <i class="fa fa-times ml-1" aria-hidden="true"></i>
                    </li>
                </ul>
                <a class="add-more-location position-relative pl-2" href="javascript:void(0)">Add Destination</a>
            </div>
            <div class="location-list style-4 d-none">
                <a class="search-location-result position-relative d-block" href="#">
                    <h4 class="mt-0 mb-1"><b>Sector 14 first</b></h4>
                    <p class="ellips mb-0">Panchkula, Haryana, India</p>
                </a>
            </div>
            <div class="cab-button d-flex align-items-center py-2">
                <a class="btn btn-solid ml-2" href="#">uber</a>
                <a class="btn btn-solid ml-2" href="#">ola</a>
            </div>
            <div class="vehical-container style-4">
                <a class="vehical-view-box row align-items-center no-gutters px-2" href="#">
                    <div class="col-3 vehicle-icon">
                        <img class="img-fluid" src="https://d1a3f4spazzrp4.cloudfront.net/car-types/haloProductImages/v1.1/Select_v1.png">
                    </div>
                    <div class="col-9">
                        <div class="row no-gutters">
                            <div class="col-8 vehicle-details">
                                <h4 class="m-0"><b>Go Intercity</b></h4>
                                <p class="station-rides ellips">Affordable outstation rides</p>
                                <p class="waiting-time m-0"><span class="mr-1">In 2 mins.</span><span>03:04 pm</span></p>
                            </div>
                            <div class="col-4 ride-price pl-2">
                                <p class="mb-0"><b>₹2,634.37</b></p>
                            </div>
                        </div>
                    </div>
                </a>
                <hr class="m-0">
            </div>
        </form>
    </div>
</section>
@endsection