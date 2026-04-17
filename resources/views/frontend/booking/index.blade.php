@extends('layouts.store', ['title' => 'Product'])
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
    <div id="booking-map" style="width: 100%; height: 100%;"></div>
    <input id="booking-latitude" type="hidden" value="-34">
    <input id="booking-longitude"  type="hidden" value="151">

    <div class="booking-experience ds bc">
        <div class="address-form">

            <div class="loader-outer d-none">
                <div class="spinner-border avatar-lg text-primary m-2" role="status"></div>
            </div>

            <div class="location-box check-pick-first">
                <div class="where-to-go">
                    <div class="title title-36">{{__('Where can we pick you up?')}}</div>
                </div>
            </div>
            <div class="location-box check-dropoff-secpond" style="display:none">
                <ul class="location-inputs position-relative pl-2" id="location_input_main_div">
                    <li class="d-flex dots">
                        <div class="title title-24 position-relative edit-pickup">  {{__('From')}} - <span id="pickup-where-from"></span><i class="fa fa-angle-down" aria-hidden="true"></i></div>
                    </li>
                    <li class="d-flex dots where-to-first">
                        <div class="title title-36 pr-3 position-relative">{{__('Where To?')}}</div>
                    </li>
                    <li class="d-flex dots where-to-second" style="display:none !important;">
                        <div class="title title-24 position-relative edit-dropoff">  {{__('To')}} - <span id="dropoff-where-to"></span><i class="fa fa-angle-down" aria-hidden="true"></i></div>
                        <i class="fa fa-times ml-1 apremove" aria-hidden="true" data-rel=""></i>
                    </li>
                </ul>
                <a class="add-more-location position-relative pl-2" style="display:none" href="javascript:void(0)">{{__('Add Destination')}}</a>
            </div>
            <input type="hidden" name="pickup_location_latitude[]" value="" id="pickup_location_latitude">
            <input type="hidden" name="pickup_location_longitude[]" value="" id="pickup_location_longitude">
            <input type="hidden" name="destination_location_latitude[]" value="" id="destination_location_latitude"/>
            <input type="hidden" name="destination_location_longitude[]" value="" id="destination_location_longitude"/>
            <input type="hidden" name="schedule_date" value="" id="schedule_date"/>
            <div class="location-container style-4">
                <div class="location-search d-flex align-items-center check-pickup">
                    <i class="fa fa-search" aria-hidden="true"></i>
                    <input class="form-control pickup-text pac-target-input" type="text" name="pickup_location_name[]" placeholder="Add A Pick-Up Location" id="pickup_location" autocomplete="off">
                </div>
                <div class="location-search d-flex align-items-center" style="display:none !important;" id="destination_location_add_more">
                </div>
                <div class="location-search d-flex align-items-center check-dropoff" style="display:none !important;">
                    <i class="fa fa-search" aria-hidden="true"></i>
                    <input class="form-control pickup-text" name="destination_location_name[]" type="text" placeholder="{{__('Add A Stop')}}" id="destination_location"/>
                </div>
                <div class="location-search d-flex align-items-center" style="display:none !important;" id="destination_location_add_temp">

                </div>
                <div class="scheduled-ride">
                    <button><i class="fa fa-clock-o" aria-hidden="true"></i> <span class="mx-2 scheduleDateTimeApnd">Now</span> <i class="fa fa-angle-down" aria-hidden="true"></i></button>
                </div>
                @if($wallet_balance < 0)
                <div class="row">
                        <div class="col-md-7">
                            <h6 style="color: red;">{{__('* Please recharge your wallet.')}}
                        </div>
                        <div class="col-md-5 text-md-right text-center">
                            <button type="button" class="btn btn-solid" id="topup_wallet_btn" data-toggle="modal" data-target="#topup_wallet">{{__('Topup Wallet')}}</button>
                        </div>
                    </div>

                @endif
                <div class="loader cab-booking-main-loader"></div>
                <div class="location-list style-4">
                        <a class="select-location row align-items-center" id="get-current-location" href="javascript:void(0)">
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
                    @forelse($user_addresses as $user_address)
                        <!-- <a class="search-location-result position-relative d-block" href="javascript:void(0);" data-address="{{$user_address->address}}" data-latitude="{{$user_address->latitude}}" data-longitude="{{$user_address->longitude}}">
                            <h4 class="mt-0 mb-1"><b>{{$user_address->address}}</b></h4>
                            <p class="ellips mb-0">{{$user_address->city}}, {{$user_address->state}}, {{$user_address->country}}</p>
                        </a> -->
                        <a class="search-location-result position-relative row align-items-center mt-2" href="javascript:void(0);" data-address="{{$user_address->address}}" data-latitude="{{$user_address->latitude}}" data-longitude="{{$user_address->longitude}}">
                            <div class="col-2 text-center pl-3">
                                <div class="round-shape">
                                    <i class="fa fa-map-marker" aria-hidden="true"></i>
                                </div>
                            </div>
                            <div class="col-10 pl-3">
                                <h4 class="mt-0 mb-1"><b>{{$user_address->address}}</b></h4>
                                <div class="current-location ellips mb-2">{{$user_address->city}}, {{$user_address->state}}, {{$user_address->country}}</div>
                                <hr class="m-0">
                            </div>
                        </a>
                    @empty
                    @endforelse
                </div>
                <div class="scheduled-ride-list">
                    <div class="scheduled-ride-list-heading d-flex align-items-center justify-content-between">
                        <h3>Choose Date And Time</h3>
                        <span class="skip-clear">
                            Skip
                        </span>
                    </div>

                    <div class="date-radio-list style-4">

                    </div>

                    <div class="scheduled-footer">

                    </div>
                </div>
                <div class="table-responsive style-4">
                    <div class="cab-button d-flex flex-nowrap align-items-center py-2" id="vendor_main_div"></div>
                </div>
                <div class="vehical-container style-4" id="search_product_main_div"></div>
            </div>
        </div>
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
        <script type="text/template" id="scheduleTime_template">
            <div class="scheduleTime">
                <select class="scheduleHour" onchange="checkScheduleDateTime(this)" ><option value="">HH</option><option value="1">01</option><option value="2">02</option><option value="3">03</option><option value="4">04</option><option value="5">05</option><option value="6">06</option><option value="7">07</option><option value="8">08</option><option value="9">09</option><option value="10">10</option><option value="11">11</option><option value="12">12</option></select>
                <select class="scheduleMinute" onchange="checkScheduleDateTime(this)" ><option value="">MM</option><option value="0">00</option><option value="1">05</option><option value="2">10</option><option value="3">15</option><option value="4">20</option><option value="5">25</option><option value="6">30</option><option value="7">35</option><option value="8">40</option><option value="9">45</option><option value="10">50</option><option value="11">55</option></select>
                <select class="scheduleAmPm" onchange="checkScheduleDateTime(this)" ><option value="">AM/PM</option><option value="am">AM</option><option value="pm">PM</option></select>
            </div>
        </script>
        <script type="text/template" id="destination_location_template">
            <i class="fa fa-search destination-icon" aria-hidden="true"></i>
            <input class="form-control pickup-text" type="text" name="destination_location_name[]" placeholder="{{__('Add A Stop')}}" id="destination_location_<%= random_id %>" data-rel="<%= random_id %>"/>
            <input type="hidden" name="destination_location_latitude[]" value="" id="destination_location_latitude_<%= random_id %>" data-rel="<%= random_id %>"/>
            <input type="hidden" name="destination_location_longitude[]" value="" id="destination_location_longitude_<%= random_id %>" data-rel="<%= random_id %>"/>
        </script>
        <script type="text/template" id="destination_location_template_li">
            <li class="d-flex dots" id="dots_<%= random_id %>">
                <div class="title title-24 position-relative edit-other-stop" id="<%= random_id %>">  {{__('To')}} - <span id="dropoff-where-to-<%= random_id %>"></span><i class="fa fa-angle-down" aria-hidden="true"></i></div>
                <i class="fa fa-times ml-1 apremove" aria-hidden="true" data-rel="<%= random_id %>"></i>
            </li>
        </script>
        <script type="text/template" id="cab_detail_box_template">
            <div class="cab-outer style-4">
                <div class="bg-white p-2">
                    <a class="close-cab-detail-box" href="javascript:void()">✕</a>
                    <div class="cab-image-box w-100 d-flex align-items-center justify-content-center">
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
                        <% if((result.loyalty_amount_saved) && (result.loyalty_amount_saved) > 0 ){ %>
                            <div class="col-6 mb-2">Loyalty</div>
                            <div class="col-6 mb-2 text-right">-{{Session::get('currencySymbol')}} <%= result.loyalty_amount_saved %></div>xx
                        <% } %>
                    </div>
                </div>
                <div class="coupon_box d-flex w-100 py-2 align-items-center justify-content-between">
                    <label class="mb-0 ml-1">
                        <img src="{{asset('assets/images/discount_icon.svg')}}">
                        <span class="code-text">{{__('Select a promo code')}}</span>
                    </label>
                    <a href="javascript:void(0)" class="ml-1" data-product_id="<%= result.id %>"  data-vendor_id="<%= result.vendor_id %>" data-amount="<%= result.tags_price%>" id="promo_code_list_btn_cab_booking">Apply</a>
                    <a class="remove-coupon" href="javascript:void(0)" id="remove_promo_code_cab_booking_btn" data-product_id="<%= result.id %>" data-vendor_id="<%= result.vendor_id %>" data-amount="<%= result.tags_price%>" style="display:none;">Remove</a>
                </div>
                <div class="form-group pmd-textfield pmd-textfield-floating-label" style="display:none;" id="schedule_datetime_main_div">
                    <label class="control-label" for="datetimepicker-default">Select Date and Time</label>
                    <input type="datetime-local" id="schedule_datetime" class="form-control" placeholder="Inline calendar" value="">
                </div>
            </div>
            <span id="show_error_of_booking" class="error"></span>

            <div class="payment-promo-container p-2">
                <h4 class="d-flex align-items-center justify-content-between mb-2"  data-toggle="modal" data-target="#payment_modal">
                    <span id="payment_type">
                        <i class="fa fa-money" aria-hidden="true"></i> Cash
                    </span>
                    <i class="fa fa-angle-down" aria-hidden="true"></i>
                </h4>
                <div class="row">
                    <div class="col-12">
                        <button class="btn btn-solid w-100" id="pickup_now" data-payment_method="1" data-product_id="<%= result.id %>" data-coupon_id ="" data-vendor_id="<%= result.vendor_id %>" data-amount="<%= result.original_tags_price%>" data-image="<%= result.image_url %>" data-rel="pickup_now" data-task_type="now">Pickup</button>
                    </div>
                    <!--<div class="col-6">
                        <button class="btn btn-solid w-100" id="pickup_later" data-payment_method="1" data-product_id="<%= result.id %>" data-coupon_id ="" data-vendor_id="<%= result.vendor_id %>" data-amount="<%= result.original_tags_price%>" data-image="<%= result.image_url %>" data-rel="pickup_later">Pickup Later</button>
                    </div>-->
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
                <div class="w-100 h-100">
                    <img src="<%= product_image %>" alt="">
                </div>
                <div class="cab-location-details" id="searching_main_div">
                    <h4><b>Searching For Nearby Drivers</b></h4>
                    <div class="new-loader"></div>
                </div>
                <div class="cab-location-details" id="driver_details_main_div" style="display:none;">
                   <div class="row align-items-center">

                        <div class="col-4">
                           <div class="taxi-img">
                               <img src="" id="driver_image">
                           </div>
                        </div>

                        <div class="col-8" >
                            <h4 id="driver_name"><b><%= result.user_name %></b></h4>
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
                    <div class="col-6 mb-2 text-right">$<%= result.total_amount %></div>
                </div>
            </div>
        </script>

        <div class="cab-detail-box style-4 d-none" id="cab_detail_box"></div>
            <div class="promo-box style-4 d-none">
                <a class="d-block mt-2 close-promo-code-detail-box" href="javascript:void(0)">✕</a>
                <div class="row" id="cab_booking_promo_code_list_main_div">

                </div>
            </div>
        </div>



</section>

<!-- Payment Modal -->
<div class="modal fade payment-modal payment-modal-width" id="payment_modal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="payment_modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header pb-0">
                <h5 class="modal-title" id="payment_modalLabel">Select Payment Method</h5>
                <button type="button" class="close right-top" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0">
                <h4 class="d-flex align-items-center justify-content-between mb-2 mt-3 px-3 select_cab_payment_method" data-payment_method="1"><span><i class="fa fa-money mr-3" aria-hidden="true"></i> Cash</span></h4>
                <h4 class="d-flex align-items-center justify-content-between mb-2 mt-3 px-3 select_cab_payment_method" data-payment_method="2"><span><i class="fa fa-money mr-3" aria-hidden="true"></i> Wallet/Card</span></h4>

                {{-- <h4 class="payment-button"  data-toggle="modal" data-target="#select_payment_option" aria-label="Close">Select Payment Method</h4> --}}
            </div>
        </div>
    </div>
</div>

<!-- Select Payment Option -->
<div class="modal fade select-payment-option payment-modal-width" id="select_payment_option" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="select_payment_optionLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="select_payment_optionLabel">Choose payment method</h5>
                <button type="button" class="close right-top" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h4 class="d-flex  justify-content-between mb-2 mt-3 select_cab_payment_methodx"><span ><i class="fa fa-money mr-3" aria-hidden="true"></i> Cash</span></h4>
            </div>
        </div>
    </div>
</div>
<!-- topup wallet -->
<div class="modal fade" id="topup_wallet" tabindex="-1" aria-labelledby="topup_walletLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header border-bottom">
        <h5 class="modal-title text-17 mb-0 mt-0" id="topup_walletLabel">{{__('Available Balance')}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="" id="wallet_topup_form">
        @csrf
        @method('POST')
        <div class="modal-body pb-0">
            <div class="form-group">
                <div class="text-36">{{Session::get('currencySymbol')}}<span class="wallet_balance">@money(Auth::user()->balanceFloat * (isset($clientCurrency->doller_compare)?$clientCurrency->doller_compare:1))</span></div>
            </div>
            <div class="form-group">
                <h5 class="text-17 mb-2">{{__('Topup Wallet')}}</h5>
            </div>
            <div class="form-group">
                <label for="wallet_amount">{{__('Amount')}}</label>
                <input class="form-control" name="wallet_amount" id="wallet_amount" type="text" placeholder="Enter Amount">
                <span class="error-msg" id="wallet_amount_error"></span>
            </div>
            <div class="form-group">
                <div><label for="custom_amount">{{__('Recommended')}}</label></div>
                <button type="button" class="btn btn-solid mb-2 custom_amount">+10</button>
                <button type="button" class="btn btn-solid mb-2 custom_amount">+20</button>
                <button type="button" class="btn btn-solid mb-2 custom_amount">+50</button>
            </div>
            <hr class="mt-0 mb-1" />
            <div class="payment_response">
                <div class="alert p-0 m-0" role="alert"></div>
            </div>
            <h5 class="text-17 mb-2">{{__('Debit From')}}</h5>
            <div class="form-group" id="wallet_payment_methods">
            </div>
            <span class="error-msg" id="wallet_payment_methods_error"></span>
        </div>
        <div class="modal-footer d-block text-center">
            <div class="row">
                <div class="col-sm-12 p-0 d-flex justify-space-around">
                    <button type="button" class="btn btn-block btn-solid mr-1 mt-2 topup_wallet_confirm">{{__('Topup Wallet')}}</button>
                    <button type="button" class="btn btn-block btn-solid ml-1 mt-2" data-dismiss="modal">{{__('Cancel')}}</button>
                </div>
            </div>
        </div>
      </form>
    </div>
  </div>
</div>


<script type="text/template" id="payment_method_template">
    <% if(payment_options == '') { %>
        <h6>{{__('Payment Options Not Avaialable')}}</h6>
    <% }else{ %>
        <% _.each(payment_options, function(payment_option, k){%>
            <% if( (payment_option.slug != 'cash_on_delivery') && (payment_option.slug != 'loyalty_points') ) { %>
                <label class="radio mt-2">
                    <%= payment_option.title %>
                    <input type="radio" name="wallet_payment_method" id="radio-<%= payment_option.slug %>" value="<%= payment_option.slug %>" data-payment_option_id="<%= payment_option.id %>">
                    <span class="checkround"></span>
                </label>
                <% if(payment_option.slug == 'stripe') { %>
                    <div class="col-md-12 mt-3 mb-3 stripe_element_wrapper d-none">
                        <div class="form-control">
                            <label class="d-flex flex-row pt-1 pb-1 mb-0">
                                <div id="stripe-card-element"></div>
                            </label>
                        </div>
                        <span class="error text-danger" id="stripe_card_error"></span>
                    </div>
                <% } %>
            <% } %>
        <% }); %>
    <% } %>
</script>

<!-- end topup wallet -->

@endsection

@section('script')


<script src="https://js.stripe.com/v3/"></script>
<script type="text/javascript">
    var ajaxCall = 'ToCancelPrevReq';
    var credit_wallet_url = "{{route('user.creditWallet')}}";
    var payment_stripe_url = "{{route('payment.stripe')}}";
    var payment_paypal_url = "{{route('payment.paypalPurchase')}}";
    var wallet_payment_options_url = "{{route('wallet.payment.option.list')}}";
    var payment_success_paypal_url = "{{route('payment.paypalCompletePurchase')}}";
    var payment_paystack_url = "{{route('payment.paystackPurchase')}}";
    var payment_success_paystack_url = "{{route('payment.paystackCompletePurchase')}}";
    var payment_payfast_url = "{{route('payment.payfastPurchase')}}";
    var cabbookingwallet = 1;
    var amount_required_error_msg = "{{__('Please enter amount.') }}";
    var payment_method_required_error_msg = "{{__('Please select payment method.')}}";

    $('#wallet_amount').keypress(function(event) {
        if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
            event.preventDefault();
        }
    });
    $('.verifyEmail').click(function() {
        verifyUser('email');
    });
    $('.verifyPhone').click(function() {
        verifyUser('phone');
    });
    function verifyUser($type = 'email') {
        ajaxCall = $.ajax({
            type: "post",
            dataType: "json",
            url: "{{ route('verifyInformation', Auth::user()->id) }}",
            data: {
                "_token": "{{ csrf_token() }}",
                "type": $type,
            },
            beforeSend: function() {
                if (ajaxCall != 'ToCancelPrevReq' && ajaxCall.readyState < 4) {
                    ajaxCall.abort();
                }
            },
            success: function(response) {
                var res = response.result;
            },
            error: function(data) {},
        });
    }
    $(document).delegate(".custom_amount", "click", function(){
        let wallet_amount = $("#wallet_amount").val();
        let amount = $(this).text();
        if(wallet_amount == ''){ wallet_amount = 0; }
        let new_amount = parseInt(amount) + parseInt(wallet_amount);
        $("#wallet_amount").val(new_amount);
    });

    $(document).on('change', '#wallet_payment_methods input[name="wallet_payment_method"]', function() {
        $('#wallet_payment_methods_error').html('');
        var method = $(this).val();
        if(method == 'stripe'){
            $("#wallet_payment_methods .stripe_element_wrapper").removeClass('d-none');
        }else{
            $("#wallet_payment_methods .stripe_element_wrapper").addClass('d-none');
        }
    });
</script>
<script>
    var loadFile = function(event) {
        var output = document.getElementById('output');
        output.src = URL.createObjectURL(event.target.files[0]);
    };
</script>
<script src="{{asset('js/payment.js')}}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js" integrity="sha512-qTXRIMyZIFb8iQcfjXWCO8+M5Tbc38Qi5WzdPOYZHIlZpzBHG3L3by84BBBOiRGiEb7KKtAOAs5qYdUiZiQNNQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="{{asset('js/cab_booking.js')}}"></script>
<script>
var category_id = "{{ $category->id??'' }}";

var routeset = "{{route('pickup-delivery-route',':category_id')}}";

var autocomplete_urls = routeset.replace(":category_id", category_id);
var wallet_balance = {{ $wallet_balance}}
var get_product_detail = "{{url('looking/product-detail')}}";
var promo_code_list_url = "{{route('verify.promocode.list')}}";
var get_vehicle_list = "{{url('looking/get-list-of-vehicles')}}";
var cab_booking_create_order = "{{url('looking/create-order')}}";
var live_location = "{{ URL::asset('/images/live_location.gif') }}";
var no_coupon_available_message = "{{__('No Other Coupons Available.')}}";
var order_tracking_details_url = "{{url('looking/order-tracking-details')}}";
var cab_booking_promo_code_remove_url = "{{url('looking/promo-code/remove')}}";
var apply_cab_booking_promocode_coupon_url = "{{ route('verify.cab.booking.promo-code') }}";

</script>

@endsection
