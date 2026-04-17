@extends('layouts.store', ['title' => 'Booking'])
@section('content')
<section class="cab-booking pt-0 d-none">
        <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d4850.865733603189!2d76.82393041076074!3d30.716149768967526!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2sin!4v1627015845978!5m2!1sen!2sin" width="100%" height="100vh" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
        
        <div class="booking-experience ds bc 12" style="top: 80px;">
            <div class="address-form">

                <div class="loader-outer d-none">
                    <div class="spinner-border avatar-lg text-primary m-2" role="status"></div>
                </div>

                <div class="location-box">
                    <div class="where-to-go">
                        <div class="title title-36">Where can we pick you up?</div>
                    </div>
                   </div>

                <div class="location-container">
                    <div class="location-search d-flex align-items-center">
                        <i class="fa fa-search" aria-hidden="true"></i>
                        <input placeholder="Add a pickup location" value="" class="form-control">
                    </div>
                    <div class="scheduled-ride">
                        <button><i class="fa fa-clock-o" aria-hidden="true"></i> <span class="mx-2">Now</span> <i class="fa fa-angle-down" aria-hidden="true"></i></button>
                    </div>
                    <div class="list-container style-4">
                        <a class="select-location row align-items-center" href="#">
                            <div class="col-2 text-center pl-4">
                                <div class="round-shape active-location">
                                    <i class="fa fa-crosshairs" aria-hidden="true"></i>
                                </div>
                            </div>
                            <div class="col-10 pl-3">
                                <h4><b>Allow location Access</b></h4>
                                <div class="current-location ellips text-color mb-2">Your current location</div>
                                <hr class="m-0">
                            </div>
                        </a>
                        <a class="select-location row align-items-center" href="#">
                            <div class="col-2 text-center pl-4">
                                <div class="round-shape">
                                    <i class="fa fa-map-marker" aria-hidden="true"></i>
                                </div>
                            </div>
                            <div class="col-10 pl-3">
                                <h4><b>CTU Depot-I</b></h4>
                                <div class="current-location ellips mb-2">Industrial Area Phase I, Chandigarh</div>
                                <hr class="m-0">
                            </div>
                        </a>
                        <a class="select-location row align-items-center" href="#">
                            <div class="col-2 text-center pl-4">
                                <div class="round-shape">
                                    <i class="fa fa-map-marker" aria-hidden="true"></i>
                                </div>
                            </div>
                            <div class="col-10 pl-3">
                                <h4><b>CTU Depot-I</b></h4>
                                <div class="current-location ellips mb-2">Industrial Area Phase I, Chandigarh Industrial Area Phase I, Chandigarh</div>
                                <hr class="m-0">
                            </div>
                        </a>
                    </div>
                </div>

                <div class="location-list style-4 d-none">
                    <a class="search-location-result position-relative d-block" href="#">
                        <h4 class="mt-0 mb-1"><b>Sector 14 first</b></h4>
                        <p class="ellips mb-0">Panchkula, Haryana, India</p>
                    </a>
                    <a class="search-location-result position-relative d-block" href="#">
                        <h4 class="mt-0 mb-1"><b>Haryana General Store</b></h4>
                        <p class="ellips mb-0">Sector 14, BEL Colony, Budanpur, Panchkula, Haryana, India</p>
                    </a>
                    <a class="search-location-result position-relative d-block" href="#">
                        <h4 class="mt-0 mb-1"><b>Sector 14</b></h4>
                        <p class="ellips mb-0">BEL Colony, Sector 14, Panchkula, Haryana, India</p>
                    </a>
                    <a class="search-location-result position-relative d-block" href="#">
                        <h4 class="mt-0 mb-1"><b>Haryana General Store</b></h4>
                        <p class="ellips mb-0">Sector 14, BEL Colony, Budanpur, Panchkula, Haryana, India</p>
                    </a>
                    <a class="search-location-result position-relative d-block" href="#">
                        <h4 class="mt-0 mb-1"><b>Sector 14</b></h4>
                        <p class="ellips mb-0">Panchkula, Haryana, India</p>
                    </a>
                    <a class="search-location-result position-relative d-block" href="#">
                        <h4 class="mt-0 mb-1"><b>Haryana General Store</b></h4>
                        <p class="ellips mb-0">Sector 14, BEL Colony, Budanpur, Panchkula, Haryana, India</p>
                    </a>
                    <a class="search-location-result position-relative d-block" href="#">
                        <h4 class="mt-0 mb-1"><b>Sector 14</b></h4>
                        <p class="ellips mb-0">BEL Colony, Sector 14, Panchkula, Haryana, India</p>
                    </a>
                    <a class="search-location-result position-relative d-block" href="#">
                        <h4 class="mt-0 mb-1"><b>Haryana General Store</b></h4>
                        <p class="ellips mb-0">Sector 14, BEL Colony, Budanpur, Panchkula, Haryana, India</p>
                    </a>
                    <a class="search-location-result position-relative d-block" href="#">
                        <h4 class="mt-0 mb-1"><b>Sector 14</b></h4>
                        <p class="ellips mb-0">Panchkula, Haryana, India</p>
                    </a>
                    <a class="search-location-result position-relative d-block" href="#">
                        <h4 class="mt-0 mb-1"><b>Haryana General Store</b></h4>
                        <p class="ellips mb-0">Sector 14, BEL Colony, Budanpur, Panchkula, Haryana, India</p>
                    </a>
                    <a class="search-location-result position-relative d-block" href="#">
                        <h4 class="mt-0 mb-1"><b>Sector 14</b></h4>
                        <p class="ellips mb-0">BEL Colony, Sector 14, Panchkula, Haryana, India</p>
                    </a>
                    <a class="search-location-result position-relative d-block" href="#">
                        <h4 class="mt-0 mb-1"><b>Haryana General Store last</b></h4>
                        <p class="ellips mb-0">Sector 14, BEL Colony, Budanpur, Panchkula, Haryana, India</p>
                    </a>
                </div>
                
                <div class="cab-button d-none align-items-center py-2">
                    <a class="btn btn-solid ml-2" href="#">uber</a>
                    <a class="btn btn-solid ml-2" href="#">ola</a>
                </div>
                
                <div class="vehical-container style-4 d-none">
                    <a class="vehical-view-box row align-items-center no-gutters px-2" href="#">
                        <div class="col-3 vehicle-icon">
                            <img class="img-fluid" src="https://d1a3f4spazzrp4.cloudfront.net/car-types/haloProductImages/v1.1/Select_v1.png" class="j0 i2">
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
                    <a class="vehical-view-box row align-items-center no-gutters px-2" href="#">
                        <div class="col-3 vehicle-icon">
                            <img class="img-fluid" src="https://d1a3f4spazzrp4.cloudfront.net/car-types/haloProductImages/v1.1/Select_v1.png" class="j0 i2">
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
                    <a class="vehical-view-box row align-items-center no-gutters px-2" href="#">
                        <div class="col-3 vehicle-icon">
                            <img class="img-fluid" src="https://d1a3f4spazzrp4.cloudfront.net/car-types/haloProductImages/v1.1/Select_v1.png" class="j0 i2">
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
                    <a class="vehical-view-box row align-items-center no-gutters px-2" href="#">
                        <div class="col-3 vehicle-icon">
                            <img class="img-fluid" src="https://d1a3f4spazzrp4.cloudfront.net/car-types/haloProductImages/v1.1/Select_v1.png" class="j0 i2">
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
                    <a class="vehical-view-box row align-items-center no-gutters px-2" href="#">
                        <div class="col-3 vehicle-icon">
                            <img class="img-fluid" src="https://d1a3f4spazzrp4.cloudfront.net/car-types/haloProductImages/v1.1/Select_v1.png" class="j0 i2">
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
                    <a class="vehical-view-box row align-items-center no-gutters px-2" href="#">
                        <div class="col-3 vehicle-icon">
                            <img class="img-fluid" src="https://d1a3f4spazzrp4.cloudfront.net/car-types/haloProductImages/v1.1/Select_v1.png" class="j0 i2">
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
                    <a class="vehical-view-box row align-items-center no-gutters px-2" href="#">
                        <div class="col-3 vehicle-icon">
                            <img class="img-fluid" src="https://d1a3f4spazzrp4.cloudfront.net/car-types/haloProductImages/v1.1/Select_v1.png" class="j0 i2">
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
                </div>

                <div class="cab-detail-box style-4 d-none">
                    <div class="cab-outer style-4 d-none">
                        <div class="bg-white p-2">
                            <a href="#">✕</a>
                            <div class="w-100 h-100">
                                <img src="https://d1a3f4spazzrp4.cloudfront.net/car-types/haloProductImages/IntercityXL.png" alt="">
                            </div>
                            <div class="cab-location-details">
                                <h4 class="d-flex align-items-center justify-content-between"><b>XL Intercity</b> <b>₹3,206.50</b></h4>
                                <p class="mb-0">In 3 mins.</p>
                                <p class="mb-0">4 Seats.</p>
                                <p> Outstation rides in spacious SUVs</p>
                            </div>
                        </div>
                        <div class="cab-amount-details px-2">
                            <div class="row">
                                <div class="col-6 mb-2">Distance</div>
                                <div class="col-6 mb-2 text-right" id="distance">20.25 kms</div>
                                <div class="col-6 mb-2">Duration</div>
                                <div class="col-6 mb-2 text-right" id="">10.25 mins</div>
                                <div class="col-6 mb-2">Delivery fee</div>
                                <div class="col-6 mb-2 text-right">$114.02</div>
                                <div class="col-6 mb-2">Loyalty</div>
                                <div class="col-6 mb-2 text-right">-$102.95</div>
                            </div>
                        </div>
                        <div class="coupon_box d-flex w-100 py-2 align-items-center">
                            <img src="http://local.myorder.com/assets/images/discount_icon.svg">
                            <label class="mb-0 ml-1">                                
                                <a href="javascript:void(0)" class="promo_code_list_btn ml-1" data-vendor_id="2" data-cart_id="4" data-amount="4.00">Select a promo code</a>
                            </label>
                        </div>
                    </div>

                    <div class="cab-outer style-4">
                        <div class="bg-white p-2">
                            <a href="#">✕</a>
                            <div class="w-100 h-100">
                                <img src="https://d1a3f4spazzrp4.cloudfront.net/car-types/haloProductImages/IntercityXL.png" alt="">
                            </div>
                            <div class="cab-location-details">
                                <h4><b>Searching for neardy drivers</b></h4>
                                <p class="mb-0">Processing......</p>
                            </div>
                            <div class="cab-location-details">
                               <div class="row align-items-center">
                                   <div class="col-8">
                                        <h4><b>Pankaj Rana</b></h4>
                                        <p class="mb-0">+918521513254</p>
                                   </div>
                                   <div class="col-4">
                                       <div class="taxi-img">
                                           <img src="https://staticimg.vicky.in/cache/images/cars/hyundai/santro/hyundai_santro_4-100x100.jpg" alt="">
                                       </div>
                                   </div>
                               </div>
                            </div>
                        </div>
                        <div class="cab-amount-details px-2">
                            <div class="row">
                                <div class="col-6 mb-2">ETA</div>
                                <div class="col-6 mb-2 text-right" id="distance">--</div>
                                <div class="col-6 mb-2">Order ID</div>
                                <div class="col-6 mb-2 text-right" id="">71583514</div>
                                <div class="col-6 mb-2">Amount Paid</div>
                                <div class="col-6 mb-2 text-right">$114.02</div>
                            </div>
                        </div>
                    </div>

                    <div class="payment-promo-container p-2">
                        <h4 class="d-flex align-items-center justify-content-between mb-2" data-toggle="modal" data-target="#payment_modal"><span><i class="fa fa-money" aria-hidden="true"></i> Cash</span> <i class="fa fa-angle-down" aria-hidden="true"></i></h4>
                        <button class="btn btn-solid w-100">Request XL Intercity</button>
                    </div>                
                </div> 
                
                <div class="promo-box style-4 d-none">
                    <a class="d-block mt-2" href="#">✕</a>
                    <div class="row" id="promo_code_list_main_div">
        
                        <div class="col-12 mt-2">
                            <div class="coupon-code mt-0">
                                <div class="p-2">
                                    <img src="https://imgproxy.royoorders.com/insecure/fill/100/35/sm/0/plain/https://s3.us-west-2.amazonaws.com/royoorders2.0-assets/promocode/jyevATxvaAFDVYlbg8QE2QBDBmuBYY6qrxuw8Rsl.png" alt="">
                                    <h6 class="mt-0">Converse20</h6>
                                </div>
                                <hr class="m-0">
                                <div class="code-outer p-2 text-uppercase d-flex align-items-center justify-content-between">
                                    <label class="m-0">CONV20</label>
                                    <a class="btn btn-solid apply_promo_code_btn" data-vendor_id="17" data-cart_id="4" data-coupon_id="3" data-amount="86.36" style="cursor: pointer;">Apply</a>
                                </div>
                                <hr class="m-0">
                                <div class="offer-text p-2">
                                    <p class="m-0">20% off on all Converse products with free Delivery.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mt-2">
                            <div class="coupon-code mt-0">
                                <div class="p-2">
                                    <img src="https://imgproxy.royoorders.com/insecure/fill/100/35/sm/0/plain/https://s3.us-west-2.amazonaws.com/royoorders2.0-assets/promocode/jyevATxvaAFDVYlbg8QE2QBDBmuBYY6qrxuw8Rsl.png" alt="">
                                    <h6 class="mt-0">Converse20</h6>
                                </div>
                                <hr class="m-0">
                                <div class="code-outer p-2 text-uppercase d-flex align-items-center justify-content-between">
                                    <label class="m-0">CONV20</label>
                                    <a class="btn btn-solid apply_promo_code_btn" data-vendor_id="17" data-cart_id="4" data-coupon_id="3" data-amount="86.36" style="cursor: pointer;">Apply</a>
                                </div>
                                <hr class="m-0">
                                <div class="offer-text p-2">
                                    <p class="m-0">20% off on all Converse products with free Delivery.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mt-2">
                            <div class="coupon-code mt-0">
                                <div class="p-2">
                                    <img src="https://imgproxy.royoorders.com/insecure/fill/100/35/sm/0/plain/https://s3.us-west-2.amazonaws.com/royoorders2.0-assets/promocode/jyevATxvaAFDVYlbg8QE2QBDBmuBYY6qrxuw8Rsl.png" alt="">
                                    <h6 class="mt-0">Converse20</h6>
                                </div>
                                <hr class="m-0">
                                <div class="code-outer p-2 text-uppercase d-flex align-items-center justify-content-between">
                                    <label class="m-0">CONV20</label>
                                    <a class="btn btn-solid apply_promo_code_btn" data-vendor_id="17" data-cart_id="4" data-coupon_id="3" data-amount="86.36" style="cursor: pointer;">Apply</a>
                                </div>
                                <hr class="m-0">
                                <div class="offer-text p-2">
                                    <p class="m-0">20% off on all Converse products with free Delivery.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mt-2">
                            <div class="coupon-code mt-0">
                                <div class="p-2">
                                    <img src="https://imgproxy.royoorders.com/insecure/fill/100/35/sm/0/plain/https://s3.us-west-2.amazonaws.com/royoorders2.0-assets/promocode/jyevATxvaAFDVYlbg8QE2QBDBmuBYY6qrxuw8Rsl.png" alt="">
                                    <h6 class="mt-0">Converse20</h6>
                                </div>
                                <hr class="m-0">
                                <div class="code-outer p-2 text-uppercase d-flex align-items-center justify-content-between">
                                    <label class="m-0">CONV20</label>
                                    <a class="btn btn-solid apply_promo_code_btn" data-vendor_id="17" data-cart_id="4" data-coupon_id="3" data-amount="86.36" style="cursor: pointer;">Apply</a>
                                </div>
                                <hr class="m-0">
                                <div class="offer-text p-2">
                                    <p class="m-0">20% off on all Converse products with free Delivery.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mt-2">
                            <div class="coupon-code mt-0">
                                <div class="p-2">
                                    <img src="https://imgproxy.royoorders.com/insecure/fill/100/35/sm/0/plain/https://s3.us-west-2.amazonaws.com/royoorders2.0-assets/promocode/jyevATxvaAFDVYlbg8QE2QBDBmuBYY6qrxuw8Rsl.png" alt="">
                                    <h6 class="mt-0">Converse20</h6>
                                </div>
                                <hr class="m-0">
                                <div class="code-outer p-2 text-uppercase d-flex align-items-center justify-content-between">
                                    <label class="m-0">CONV20</label>
                                    <a class="btn btn-solid apply_promo_code_btn" data-vendor_id="17" data-cart_id="4" data-coupon_id="3" data-amount="86.36" style="cursor: pointer;">Apply</a>
                                </div>
                                <hr class="m-0">
                                <div class="offer-text p-2">
                                    <p class="m-0">20% off on all Converse products with free Delivery.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mt-2">
                            <div class="coupon-code mt-0">
                                <div class="p-2">
                                    <img src="https://imgproxy.royoorders.com/insecure/fill/100/35/sm/0/plain/https://s3.us-west-2.amazonaws.com/royoorders2.0-assets/promocode/jyevATxvaAFDVYlbg8QE2QBDBmuBYY6qrxuw8Rsl.png" alt="">
                                    <h6 class="mt-0">Converse20</h6>
                                </div>
                                <hr class="m-0">
                                <div class="code-outer p-2 text-uppercase d-flex align-items-center justify-content-between">
                                    <label class="m-0">CONV20</label>
                                    <a class="btn btn-solid apply_promo_code_btn" data-vendor_id="17" data-cart_id="4" data-coupon_id="3" data-amount="86.36" style="cursor: pointer;">Apply</a>
                                </div>
                                <hr class="m-0">
                                <div class="offer-text p-2">
                                    <p class="m-0">20% off on all Converse products with free Delivery.</p>
                                </div>
                            </div>
                        </div>

                    </div>    
                </div>

            </div>
        </div>

        <!-- Screen Number Two -->
        <div class="booking-experience ds bc" style="top: 80px; left:680px">
            <div class="address-form">
                <div class="location-box">
                    <ul class="location-inputs position-relative pl-2">
                        <li class="d-flex mb-2 dots">
                            <div class="title title-36 pr-3 position-relative">Where from?</div>
                        </li>
                        <li class="d-flex mb-2 dots">
                            <div class="title title-24 down-arrow pr-3 position-relative">To Phase 7</div>
                        </li>
                    </ul>
                    <a class="add-more-location position-relative pl-2" href="javascript:void(0)">Add Destination</a>
                </div>

                <div class="location-container">
                    <div class="location-search d-flex align-items-center">
                        <i class="fa fa-search" aria-hidden="true"></i>
                        <input placeholder="Add a pickup location" value="" class="form-control">
                    </div>
                    <div class="scheduled-ride">
                        <button><i class="fa fa-clock-o" aria-hidden="true"></i> <span class="mx-2">Now</span> <i class="fa fa-angle-down" aria-hidden="true"></i></button>
                    </div>
                    <div class="list-container style-4">
                        <a class="select-location row align-items-center" href="#">
                            <div class="col-2 text-center pl-4">
                                <div class="round-shape active-location">
                                    <i class="fa fa-crosshairs" aria-hidden="true"></i>
                                </div>
                            </div>
                            <div class="col-10 pl-3">
                                <h4><b>Allow location Access</b></h4>
                                <div class="current-location ellips text-color mb-2">Your current location</div>
                                <hr class="m-0">
                            </div>
                        </a>
                        <a class="select-location row align-items-center" href="#">
                            <div class="col-2 text-center pl-4">
                                <div class="round-shape">
                                    <i class="fa fa-map-marker" aria-hidden="true"></i>
                                </div>
                            </div>
                            <div class="col-10 pl-3">
                                <h4><b>CTU Depot-I</b></h4>
                                <div class="current-location ellips mb-2">Industrial Area Phase I, Chandigarh</div>
                                <hr class="m-0">
                            </div>
                        </a>
                        <a class="select-location row align-items-center" href="#">
                            <div class="col-2 text-center pl-4">
                                <div class="round-shape">
                                    <i class="fa fa-map-marker" aria-hidden="true"></i>
                                </div>
                            </div>
                            <div class="col-10 pl-3">
                                <h4><b>CTU Depot-I</b></h4>
                                <div class="current-location ellips mb-2">Industrial Area Phase I, Chandigarh Industrial Area Phase I, Chandigarh</div>
                                <hr class="m-0">
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Screen Number Three -->
        <div class="booking-experience ds bc" style="top: 80px; left:1090px">
            <div class="address-form">
                <div class="location-box">
                    <ul class="location-inputs position-relative pl-2">
                        <li class="d-flex mb-2 dots">
                            <div class="title title-24 down-arrow pr-3 position-relative">To Phase 7</div>
                        </li>
                        <li class="d-flex mb-2 dots">
                            <div class="title title-36 pr-3 position-relative">Where To?</div>
                        </li>
                    </ul>
                    <a class="add-more-location position-relative pl-2" href="javascript:void(0)">Add Destination</a>
                </div>

                <div class="location-container">
                    <div class="location-search d-flex align-items-center">
                        <i class="fa fa-search" aria-hidden="true"></i>
                        <input placeholder="Add a pickup location" value="" class="form-control">
                    </div>
                    <div class="scheduled-ride">
                        <button><i class="fa fa-clock-o" aria-hidden="true"></i> <span class="mx-2">Now</span> <i class="fa fa-angle-down" aria-hidden="true"></i></button>
                    </div>
                    <div class="list-container style-4">
                        <a class="select-location row align-items-center" href="#">
                            <div class="col-2 text-center pl-4">
                                <div class="round-shape active-location">
                                    <i class="fa fa-crosshairs" aria-hidden="true"></i>
                                </div>
                            </div>
                            <div class="col-10 pl-3">
                                <h4><b>Allow location Access</b></h4>
                                <div class="current-location ellips text-color mb-2">Your current location</div>
                                <hr class="m-0">
                            </div>
                        </a>
                        <a class="select-location row align-items-center" href="#">
                            <div class="col-2 text-center pl-4">
                                <div class="round-shape">
                                    <i class="fa fa-map-marker" aria-hidden="true"></i>
                                </div>
                            </div>
                            <div class="col-10 pl-3">
                                <h4><b>CTU Depot-I</b></h4>
                                <div class="current-location ellips mb-2">Industrial Area Phase I, Chandigarh</div>
                                <hr class="m-0">
                            </div>
                        </a>
                        <a class="select-location row align-items-center" href="#">
                            <div class="col-2 text-center pl-4">
                                <div class="round-shape">
                                    <i class="fa fa-map-marker" aria-hidden="true"></i>
                                </div>
                            </div>
                            <div class="col-10 pl-3">
                                <h4><b>CTU Depot-I</b></h4>
                                <div class="current-location ellips mb-2">Industrial Area Phase I, Chandigarh Industrial Area Phase I, Chandigarh</div>
                                <hr class="m-0">
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>        

        <!-- Screen Number Four -->
        <div class="booking-experience ds bc" style="top: 80px; left:1500px">
            <div class="address-form">
                <div class="location-box">
                    <ul class="location-inputs position-relative pl-2">
                        <li class="d-flex mb-2 dots">
                            <div class="title title-24 down-arrow pr-3 position-relative">From 1540, Phase 5</div>
                        </li>
                        <li class="d-flex mb-2 dots">
                            <div class="title title-24 down-arrow pr-3 position-relative">To Phase 7</div>
                        </li>
                    </ul>
                    <a class="add-more-location position-relative pl-2" href="javascript:void(0)">Add Destination</a>
                </div>

                <div class="location-container">                       
                    <div class="cab-list-container style-4">
                        <div class="scheduled-ride">
                            <button><i class="fa fa-clock-o" aria-hidden="true"></i> <span class="mx-2">Now</span> <i class="fa fa-angle-down" aria-hidden="true"></i></button>
                        </div>    
                        <div class="loader"></div>
                        <a class="select-location row align-items-center" href="#">
                            <div class="col-2 text-center pl-4">
                                <div class="round-shape">
                                    <i class="fa fa-map-marker" aria-hidden="true"></i>
                                </div>
                            </div>
                            <div class="col-10 pl-3">
                                <h4><b>CTU Depot-I</b></h4>
                                <div class="current-location ellips mb-2">Industrial Area Phase I, Chandigarh</div>
                                <hr class="m-0">
                            </div>
                        </a>
                        <a class="select-location row align-items-center" href="#">
                            <div class="col-2 text-center pl-4">
                                <div class="round-shape">
                                    <i class="fa fa-map-marker" aria-hidden="true"></i>
                                </div>
                            </div>
                            <div class="col-10 pl-3">
                                <h4><b>CTU Depot-I</b></h4>
                                <div class="current-location ellips mb-2">Industrial Area Phase I, Chandigarh Industrial Area Phase I, Chandigarh</div>
                                <hr class="m-0">
                            </div>
                        </a>
                        <a class="select-location row align-items-center" href="#">
                            <div class="col-2 text-center pl-4">
                                <div class="round-shape">
                                    <i class="fa fa-map-marker" aria-hidden="true"></i>
                                </div>
                            </div>
                            <div class="col-10 pl-3">
                                <h4><b>CTU Depot-I</b></h4>
                                <div class="current-location ellips mb-2">Industrial Area Phase I, Chandigarh</div>
                                <hr class="m-0">
                            </div>
                        </a>
                        <a class="select-location row align-items-center" href="#">
                            <div class="col-2 text-center pl-4">
                                <div class="round-shape">
                                    <i class="fa fa-map-marker" aria-hidden="true"></i>
                                </div>
                            </div>
                            <div class="col-10 pl-3">
                                <h4><b>CTU Depot-I</b></h4>
                                <div class="current-location ellips mb-2">Industrial Area Phase I, Chandigarh Industrial Area Phase I, Chandigarh</div>
                                <hr class="m-0">
                            </div>
                        </a>
                        <a class="select-location row align-items-center" href="#">
                            <div class="col-2 text-center pl-4">
                                <div class="round-shape">
                                    <i class="fa fa-map-marker" aria-hidden="true"></i>
                                </div>
                            </div>
                            <div class="col-10 pl-3">
                                <h4><b>CTU Depot-I</b></h4>
                                <div class="current-location ellips mb-2">Industrial Area Phase I, Chandigarh</div>
                                <hr class="m-0">
                            </div>
                        </a>
                        <a class="select-location row align-items-center" href="#">
                            <div class="col-2 text-center pl-4">
                                <div class="round-shape">
                                    <i class="fa fa-map-marker" aria-hidden="true"></i>
                                </div>
                            </div>
                            <div class="col-10 pl-3">
                                <h4><b>CTU Depot-I</b></h4>
                                <div class="current-location ellips mb-2">Industrial Area Phase I, Chandigarh Industrial Area Phase I, Chandigarh</div>
                                <hr class="m-0">
                            </div>
                        </a>
                        <a class="select-location row align-items-center" href="#">
                            <div class="col-2 text-center pl-4">
                                <div class="round-shape">
                                    <i class="fa fa-map-marker" aria-hidden="true"></i>
                                </div>
                            </div>
                            <div class="col-10 pl-3">
                                <h4><b>CTU Depot-I</b></h4>
                                <div class="current-location ellips mb-2">Industrial Area Phase I, Chandigarh</div>
                                <hr class="m-0">
                            </div>
                        </a>
                        <a class="select-location row align-items-center" href="#">
                            <div class="col-2 text-center pl-4">
                                <div class="round-shape">
                                    <i class="fa fa-map-marker" aria-hidden="true"></i>
                                </div>
                            </div>
                            <div class="col-10 pl-3">
                                <h4><b>CTU Depot-I</b></h4>
                                <div class="current-location ellips mb-2">Industrial Area Phase I, Chandigarh Industrial Area Phase I, Chandigarh</div>
                                <hr class="m-0">
                            </div>
                        </a>
                        <a class="select-location row align-items-center" href="#">
                            <div class="col-2 text-center pl-4">
                                <div class="round-shape">
                                    <i class="fa fa-map-marker" aria-hidden="true"></i>
                                </div>
                            </div>
                            <div class="col-10 pl-3">
                                <h4><b>CTU Depot-I</b></h4>
                                <div class="current-location ellips mb-2">Industrial Area Phase I, Chandigarh</div>
                                <hr class="m-0">
                            </div>
                        </a>
                        <a class="select-location row align-items-center" href="#">
                            <div class="col-2 text-center pl-4">
                                <div class="round-shape">
                                    <i class="fa fa-map-marker" aria-hidden="true"></i>
                                </div>
                            </div>
                            <div class="col-10 pl-3">
                                <h4><b>CTU Depot-I</b></h4>
                                <div class="current-location ellips mb-2">Industrial Area Phase I, Chandigarh Industrial Area Phase I, Chandigarh</div>
                                <hr class="m-0">
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </section>


    <!-- Cab Booking Start From Here -->
    <section class="cab-banner-area p-0">
        <div class="container p-64">
            <div class="row">
                <div class="col-md-6">
                    <div class="card-box mb-0">
                        <h1>Request a ride now</h1>
                        <form action="" class="cab-booking-form">

                            <div class="cab-input">
                                <div class="form-group mb-1 position-relative">
                                    <input class="form-control" type="text" placeholder="Enter pickup location">
                                    <a class="location-btn" href="#">
                                        <img src="{{asset('front-assets/images/arrow.svg')}}" alt="">
                                    </a>
                                </div>
                                <div class="form-group mb-0">
                                    <input class="form-control" type="text" placeholder="Enter pickup location">
                                </div>
                                <div class="input-line"></div>
                            </div>

                            <div class="cab-footer">
                                <button class="btn btn-solid new-btn request-btn">Request now</button>
                                <button class="btn btn-solid new-btn schedule-btn">Schedule for later</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Cab Content Area Start From Here -->

    <div class="cab-content-area">

        <!-- Royo Business Start From Here -->
        <section class="royo-business p-0">
            <div class="container p-64">
                <div class="row">
                    <div class="col-12">
                        <h2 class="title-36">Royo for Business</h2>
                        <div class="description-text">
                            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Commodi, labore!</p>
                        </div>
                        <a class="btn btn-solid new-btn d-inline-block" href="#">See how</a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Royo Business Start From Here -->
        <section class="royo-rental p-0">
            <div class="container">                
               
                <div class="row align-items-center p-64">
                    <div class="col-sm-6">
                        <div class="cab-img-box">
                            <img class="img-fluid" src="https://www.uber-assets.com/image/upload/f_auto,q_auto:eco,c_fill,w_1116,h_744/v1624484990/assets/fa/f20c42-425a-4243-866b-b480d3bd68b4/original/gettyimages-1139275491-2048x2048_With-Mask.png" alt="">
                        </div>
                    </div>
                    <div class="offset-md-1 col-sm-6 col-md-5 pl-lg-4">
                        <div class="">
                            <h2 class="title-52">Royo for Business</h2>
                            <div class="description-text">
                                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Rem nisi officiis numquam!</p>
                            </div>
                            <a class="learn-more bottom-line" href="#">Learn more</a>
                        </div>
                    </div>
                </div>

                <div class="row align-items-center p-64">
                    <div class="col-sm-6 order-md-1">
                        <div class="cab-img-box">
                            <img class="img-fluid" src="https://www.uber-assets.com/image/upload/f_auto,q_auto:eco,c_fill,w_558,h_372/v1623719981/assets/4d/b05e4c-7340-40c4-a3e9-da0de41f14fc/original/rentals-iindia.jpg" alt="">
                        </div>
                    </div>
                    <div class="col-sm-6 order-md-0">
                        <div class="pr-lg-5 mr-lg-5">
                            <h2 class="title-52">Royo Intercity </h2>
                            <div class="description-text">
                                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Rem nisi officiis numquam!</p>
                            </div>
                            <a class="learn-more" href="#">Learn more</a>
                        </div>
                    </div>
                </div>
                
            </div>
        </section>

        <!-- Focused On Safety Start From Here -->
        <section class="focused-on-safety p-0">
            <div class="container p-64">
                <div class="row mb-4 pb-2">
                    <div class="col-12">
                        <div class="title-36">Focused on safety, wherever you go</div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="safety-box">
                            <div class="safety-img">
                                <img class="img-fluid" src="https://www.uber-assets.com/image/upload/f_auto,q_auto:eco,c_fill,w_558,h_372/v1613520218/assets/3e/e98625-31e6-4536-8646-976a1ee3f210/original/Safety_Home_Img2x.png" alt="">
                            </div>
                            <div class="safety-content">
                                <h3 class="mt-0">Our commitment to your safety</h3>
                                <div class="safety-text">
                                    <p>With every safety feature and every standard in our Community Guidelines, we're committed to helping to create a safe environment for our users.</p>
                                </div>
                                <div class="safety-links">
                                    <a class="bottom-line" href="#">
                                        <span>Read about our Community Guidelines</span>
                                    </a>
                                    <a class="bottom-line" href="#">
                                        <span>See all safety features</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="safety-box">
                            <div class="safety-img">
                                <img class="img-fluid" src="https://www.uber-assets.com/image/upload/f_auto,q_auto:eco,c_fill,w_558,h_372/v1613520218/assets/3e/e98625-31e6-4536-8646-976a1ee3f210/original/Safety_Home_Img2x.png" alt="">
                            </div>
                            <div class="safety-content">
                                <h3 class="mt-0">Setting 10,000+ cities in motion</h3>
                                <div class="safety-text">
                                    <p>With every safety feature and every standard in our Community Guidelines, we're committed to helping to create a safe environment for our users.</p>
                                </div>
                                <div class="safety-links">
                                    <a class="bottom-line" href="#">
                                        <span>View all cities</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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