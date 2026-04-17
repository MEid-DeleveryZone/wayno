@extends('layouts.store', ['title' => 'Booking Details'])
@section('content')

<header>
    <div class="mobile-fix-option"></div>
    @if(isset($set_template)  && $set_template->template_id == 1)
        @include('layouts.store/left-sidebar-template-one')
        @elseif(isset($set_template)  && $set_template->template_id == 2)
        @include('layouts.store/left-sidebar')
        @else
        @include('layouts.store/left-sidebar-template-one')
        @endif
</header>
<section class="cab-booking pt-0">
    <div id="map_canvas" style="width: 100%; height: 100%;"></div>

    <div class="booking-experience ds bc">
        <div class="address-form">


            <div class="table-responsive style-4">
                <div class="cab-button d-flex flex-nowrap align-items-center py-2" id="vendor_main_div"></div>
            </div>
            <div class="vehical-container style-4" style="height:calc(100vh - 397px !important" id="search_product_main_div"></div>
            <script type="text/template" id="vendors_template">
                <% _.each(results, function(result, key){%>
                    <a class="btn btn-solid ml-2 vendor-list" href="javascript:void(0);" data-vendor="<%= result.id %>"><%= result.name %></a>
                <% }); %>
            </script>
            <script type="text/template" id="products_template">
                <% _.each(results, function(result, key){%>
                    <a class="vehical-view-box row align-items-center no-gutters px-2 my-2" href="javascript:void(0)" data-product_id="<%= result.id %>">
                        <div class="col-3 vehicle-icon">
                            <img class='img-fluid' src='<%= result.image_url %>'>
                        </div>
                        <div class="col-9">
                            <div class="row no-gutters">
                                <div class="col-8 vehicle-details">
                                    <h4 class="m-0"><b><%= result.name %></b></h4>
                                </div>
                                <div class="col-4 ride-price pl-2 text-right">
                                    <p class="mb-0"><b>{{Session::get('currencySymbol')}} <%= result.tags_price%></b></p>
                                </div>
                            </div>
                        </div>
                    </a>
                    <hr class="m-0">
                <% }); %>
            </script>
        </div>
        <script type="text/template" id="cab_detail_box_template">
            <div class="cab-outer style-4">
                <div class="bg-white p-2">
                    <a class="close-cab-detail-box" href="javascript:void()">✕</a>
                    <div class="w-100 h-100">
                        <img src="<%= result.image_url %>">
                    </div>
                    <div class="cab-location-details">
                        <h4 class="d-flex align-items-center justify-content-between"><b><%= result.name %></b> <label><sub class="ling-throgh" id
                        ="discount_amount" style="display:none;"></sub> <b id="real_amount">{{Session::get('currencySymbol')}} <%= result.tags_price%></b></label></h4>
                        <p><%= result.description %></p>
                    </div>
                </div>
                <div class="cab-amount-details px-2">
                    <div class="row">
                        <div class="col-6 mb-2">{{__('Distance')}}</div>
                        <div class="col-6 mb-2 text-right" id="distance"></div>
                        <div class="col-6 mb-2">{{__('Duration')}}</div>
                        <div class="col-6 mb-2 text-right" id="duration"></div>
                        <% if(result.loyalty_amount_saved) { %>
                            <div class="col-6 mb-2">Loyalty</div>
                            <div class="col-6 mb-2 text-right">-{{Session::get('currencySymbol')}} <%= result.loyalty_amount_saved %></div>
                        <% } %>
                    </div>
                </div>
                <div class="coupon_box d-flex w-100 py-2 align-items-center justify-content-between">
                    <label class="mb-0 ml-1">
                        <img src="{{asset('assets/images/discount_icon.svg')}}">
                        <span class="code-text">Select a promo code</span>
                    </label>
                    <a href="javascript:void(0)" class="ml-1" data-product_id="<%= result.id %>" data-vendor_id="<%= result.vendor_id %>" data-amount="<%= result.tags_price%>" id="promo_code_list_btn_cab_booking">Apply</a>
                    <a class="remove-coupon" href="javascript:void(0)" id="remove_promo_code_cab_booking_btn" data-product_id="<%= result.id %>" data-vendor_id="<%= result.vendor_id %>" data-amount="<%= result.tags_price%>" style="display:none;">Remove</a>
                </div>
                <div class="form-group pmd-textfield pmd-textfield-floating-label" style="display:none;" id="schedule_datetime_main_div">
                    <label class="control-label" for="datetimepicker-default">Select Date and Time</label>
                    <input type="datetime-local" id="schedule_datetime" class="form-control" placeholder="Inline calendar" value="">
                </div>
            </div>
            <div class="payment-promo-container p-2">
                <h4 class="d-flex align-items-center justify-content-between mb-2">
                    <span>
                        <i class="fa fa-money" aria-hidden="true"></i> Cash
                    </span>
                    <i class="fa fa-angle-down" aria-hidden="true"></i>
                </h4>
                <div class="row">
                    <div class="col-sm-6">
                        <button class="btn btn-solid w-100" id="pickup_now" data-product_id="<%= result.id %>" data-vendor_id="<%= result.vendor_id %>" data-amount="<%= result.original_tags_price%>" data-image="<%= result.image_url %>" data-rel="pickup_now" data-task_type="now">Pickup Now</button>
                    </div>
                    <div class="col-sm-6">
                        <button class="btn btn-solid w-100" id="pickup_later" data-product_id="<%= result.id %>" data-vendor_id="<%= result.vendor_id %>" data-amount="<%= result.original_tags_price%>" data-image="<%= result.image_url %>" data-rel="pickup_later">Pickup Later</button>
                    </div>
                </div>
            </div>
        </script>

        <script type="text/template" id="cab_booking_promo_code_template">
            <% _.each(promo_codes, function(promo_code, key){%>
                <div class="col-12 mt-2">
                    <div class="coupon-code mt-0">
                        <div class="p-2">
                            <img src="<%= promo_code.image.image_fit %>100/35<%= promo_code.image.image_path %>" alt="">
                            <h6 class="mt-0"><%= promo_code.title %></h6>
                        </div>
                        <hr class="m-0">
                        <div class="code-outer p-2 text-uppercase d-flex align-items-center justify-content-between">
                            <label class="m-0"><%= promo_code.name %></label>
                            <a class="btn btn-solid cab_booking_apply_promo_code_btn" data-vendor_id="<%= vendor_id %>" data-coupon_id="<%= promo_code.id %>" data-product_id="<%= product_id %>" data-amount="<%= amount %>" style="cursor: pointer;">Apply</a>
                        </div>
                        <hr class="m-0">
                        <div class="offer-text p-2">
                            <p class="m-0"><%= promo_code.short_desc %></p>
                        </div>
                    </div>
                </div>
            <% }); %>
        </script>

        <script type="text/template" id="order_success_template">
            <div class="bg-white p-2">
                <div class="w-100 h-100 text-center">
                    <img src="<%= product_image %>" alt="">
                </div>
                <div class="cab-location-details mt-2" id="searching_main_div">
                    <% if(result.user_name !== null) { %>
                    <h4><b></b></h4>
                    <% }else { %>
                    <h4><b>Searching For Nearby Drivers</b></h4>
                    <% } %>
                    <div class="new-loader"></div>
                </div>
                <div class="cab-location-details mt-2" id="driver_details_main_div" style="display:none;">
                   <div class="row align-items-center">

                       <div class="col-12 driver-profile-box">
                            <div class="taxi-img">
                               <img src="" id="driver_image">
                           </div>
                            <h4 id="driver_name"><b><%= result.user_name %></b></h4>
                            <ul class="product-rating-star d-flex align-items-center">
                                <li><a href="#"><i class="fa fa-star" aria-hidden="true"></i></a></li>
                                <li><a href="#"><i class="fa fa-star" aria-hidden="true"></i></a></li>
                                <li><a href="#"><i class="fa fa-star" aria-hidden="true"></i></a></li>
                                <li><a href="#"><i class="fa fa-star" aria-hidden="true"></i></a></li>
                                <li><a href="#"><i class="fa fa-star" aria-hidden="true"></i></a></li>
                                <li><span class="rating-count">(<%= result.total_order_by_agent %> )</span></li>
                            </ul>
                            <p class="mb-0" id="driver_phone_number"><%= result.phone_number %></p>
                       </div>
                   </div>
                </div>
            </div>
            <div class="cab-amount-details px-2">
                <div class="row">
                    <div class="col-6 mb-2">ETA</div>
                    <div class="col-6 mb-2 text-right" id="distance">--</div>
                    <div class="col-6 mb-2">Order ID</div>
                    <div class="col-6 mb-2 text-right" id=""><%= result.order_number %></div>
                    <div class="col-6 mb-2">Amount Paid</div>
                    <div class="col-6 mb-2 text-right">$<%= result.payable_amount %></div>
                    <div class="col-6 mb-2">Status</div>
                    <div class="col-6 mb-2 text-right" id="dispatcher_status_show"></div>
                </div>
            </div>
        </script>




        <div class="cab-detail-box style-4 d-none" id="cab_detail_box"></div>
        <div class="promo-box style-4 d-none">
            <a class="d-block mt-2 close-promo-code-detail-box" href="javascript:void(0)">✕</a>
            <div class="row" id="cab_booking_promo_code_list_main_div">

            </div>
        </div>

        <ul class="product_list d-flex align-items-center p-0 flex-wrap m-0" style="display:none !important;" id="rating_of_cab">
            @foreach($vendor->products as $product)
                @if($vendor->vendor_id == $product->vendor_id)
                @php
                $pro_rating = $product->productRating->rating??0;
            @endphp
            <li class="text-center">
                <img src="{{ $product->image['proxy_url'].'74/100'.$product->image['image_path'] }}" alt="">
                 <label class="rating-star add_edit_review" data-id="{{$product->productRating->id??0}}"  data-dispatch_order_id ='' data-order_vendor_product_id="{{$product->id??0}}">
                    <i class="fa fa-star{{ $pro_rating >= 1 ? '' : '-o' }}" ></i>
                    <i class="fa fa-star{{ $pro_rating >= 2 ? '' : '-o' }}" ></i>
                    <i class="fa fa-star{{ $pro_rating >= 3 ? '' : '-o' }}" ></i>
                    <i class="fa fa-star{{ $pro_rating >= 4 ? '' : '-o' }}" ></i>
                    <i class="fa fa-star{{ $pro_rating >= 5 ? '' : '-o' }}" ></i>
                </label>
                 @endif
            @endforeach
        </ul>

    </div>



</section>
<div class="modal fade product-rating" id="product_rating" tabindex="-1" aria-labelledby="product_ratingLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-body">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <div id="review-rating-form-modal">
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('script')

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js" integrity="sha512-qTXRIMyZIFb8iQcfjXWCO8+M5Tbc38Qi5WzdPOYZHIlZpzBHG3L3by84BBBOiRGiEb7KKtAOAs5qYdUiZiQNNQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="{{asset('js/js-toast-master/toast.min.js') }}"></script>
<script src="{{asset('js/cab_booking_details.js') }}"></script>

<script>


var autocomplete_urls = "{{url('looking/vendor/list/14')}}";
var get_product_detail = "{{url('looking/product-detail')}}";
var promo_code_list_url = "{{route('verify.promocode.list')}}";
var get_vehicle_list = "{{url('looking/get-list-of-vehicles')}}";
var cab_booking_create_order = "{{url('looking/create-order')}}";
var live_location = "{{ URL::asset('/images/live_location.gif') }}";
var no_coupon_available_message = "{{__('No Other Coupons Available.')}}";
var order_tracking_details_url = "{{url('looking/order-tracking-details')}}";
var cab_booking_promo_code_remove_url = "{{url('looking/promo-code/remove')}}";
var apply_cab_booking_promocode_coupon_url = "{{ route('verify.cab.booking.promo-code') }}";
var order_place_driver_details_url = "{{$route}}";
var location_icon = "{{asset("demo/images/location.png")}}";
$(document).ready(function (){
    setOrderDetailsPage();
});

$('body').on('click', '.add_edit_review', function (event) {
        event.preventDefault();
        var id = $(this).data('id');
        var dispatch_order_id = $(this).data('dispatch_order_id');
        var order_vendor_product_id = $(this).data('order_vendor_product_id');
        $.get('/rating/get-product-rating?id=' + id +'&order_vendor_product_id=' + order_vendor_product_id, function(markup)
        {
            $('#product_rating').modal('show');
            $('#review-rating-form-modal').html(markup);
            $('#review-upload-form').append('<input type="hidden" name="rating_for_dispatch" value="'+dispatch_order_id+'" id="rating_for_dispatch">');

        });
    });

</script>
@endsection
