@php
    $timezone = Auth::user()->timezone;
    $now = \Carbon\Carbon::now()->toDateString();
    $after7days = \Carbon\Carbon::now()->addDays(7)->toDateString();
@endphp
@if((isset($client_preferences['subscription_mode'])) && ($client_preferences['subscription_mode'] == 1))
<div class="row mb-4">
    <div class="col-12 mb-4">
        @if(!empty($subscription))
            <div class="card subscript-box">
                @if( (empty($subscription->cancelled_at)) || (!empty($subscription->cancelled_at)) && ($subscription->cancelled_at >= $now))
                <div class="row align-items-center mb-2">
                    <div class="col-sm-3 text-center">
                        <div class="gold-icon">
                            <img src="{{$subscription->plan->image['proxy_url'].'100/100'.$subscription->plan->image['image_path']}}" alt="">
                        </div>
                    </div>
                    <div class="col-sm-9 mt-3 mt-sm-0">
                        <div class="row align-items-end border-left-top pt-sm-0 pt-2">
                            <div class="col-12">
                                <div class="d-flex align-items-center justify-content-between">
                                    <h3 class="d-inline-block"><b>{{ $subscription->plan->title }}</b></h3>
                                    <span class="plan-price">${{ $subscription->subscription_amount }} / {{ $subscription->frequency }}</span>
                                </div>
                                <p>{{ $subscription->plan->description }}</p>
                            </div>
                            
                            <div class="col-sm-6 form-group mb-0">
                                @if($subscription->status_id == 2)
                                    <b class="mr-2">
                                    @if(!empty($subscription->cancelled_at))
                                        @if( $subscription->end_date >= $now )
                                            {{ __('Cancels On') }}
                                        @else
                                            {{ __('Cancelled On') }}
                                        @endif
                                    @else
                                        @if( $subscription->end_date >= $now )
                                            {{ __('Upcoming Billing Date') }}
                                        @else
                                            {{ __('Expired On') }}
                                        @endif
                                    @endif
                                    </b>
                                    <span>{{ convertDateTimeInTimeZone($subscription->end_date, $timezone, 'F d, Y') }}</span>
                                @elseif($subscription->status_id == 1)
                                    <b class="mr-2">{{ __("Status") }}</b><span class="text-info">{{ $subscription->status->title }}</span>
                                @elseif($subscription->status_id == 4)
                                    <b class="mr-2">{{ __("Status") }}</b><span class="text-danger">{{ $subscription->status->title }}</span>
                                @endif
                            </div>
                            <div class="col-sm-6 mb-0 text-center text-sm-right">
                                @if($subscription->status_id == 2)
                                    @if( $subscription->end_date >= $now )
                                        @if($subscription->plan->status == 1)
                                            <a class="btn btn-info subscribe_btn" href="javascript:void(0)" data-toggle="modal" data-id="{{ $subscription->plan->slug }}">{{ __("Pay now") }} (${{ $subscription->plan->price }})</a>
                                        @endif
                                        @if(empty($subscription->cancelled_at))
                                            <a class="cancel-subscription-link btn btn-info" href="#cancel-subscription" data-toggle="modal" data-id="{{ $subscription->slug }}">{{ __('Cancel') }}</a>
                                        @endif
                                    @else
                                        @if($subscription->plan->status == 1)
                                            <a class="btn btn-info subscribe_btn" href="javascript:void(0)" data-toggle="modal" data-id="{{ $subscription->plan->slug }}">{{ __("Renew") }} (${{ $subscription->plan->price }})</a>
                                        @endif
                                    @endif
                                @endif
                            </div>

                        </div>
                    </div>
                </div>
                @endif
            </div>
        @endif
    </div>
    
    @if($subscription_plans->isNotEmpty())
        @foreach($subscription_plans as $plan)
            <div class="col-md-4 col-sm-6">
                <div class="pricingtable">
                    <div class="gold-icon position-relative">
                        <img src="{{ $plan->image['proxy_url'].'100/100'.$plan->image['image_path'] }}">
                        <div class="pricingtable-header position-absolute">
                            <div class="price-value"> <b>${{ $plan->price }}</b> <span class="month">{{ $plan->frequency }}</span> </div>
                        </div>
                    </div>
                    <div class="p-2">
                        <h3 class="heading mt-0 mb-2"><b>{{ $plan->title }}</b></h3>
                        <div class="pricing-content">
                            <p>{{ $plan->description }}</p>
                        </div>
                        <ul class="mb-3 pl-1" style="list-style:none">
                            @foreach($plan->features as $feature)
                                <li><i class="fa fa-check"></i> {{ $feature }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="pricingtable-purchase">
                        @if( (isset($subscription->plan->id)) && ($plan->id == $subscription->plan->id) )
                            <button class="btn btn-info black-btn disabled w-100">{{ __('Subscribed') }}</button>
                        @else
                            <button class="btn btn-info w-100 subscribe_btn" data-id="{{ $plan->slug }}">{{ __("Subscribe") }}</button>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</div>

<div class="modal fade" id="cancel-subscription" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="cancel_subscriptionLabel">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header pb-0">
        <h5 class="modal-title" id="cancel_subscriptionLabel">{{ __("Unsubscribe") }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <form id="cancel-subscription-form" method="POST" action="">
        @csrf
        <div class="modal-body">
            <h6 class="m-0">{{ __("Do you really want to cancel this subscription ?") }}</h6>
        </div>
        <div class="modal-footer flex-nowrap justify-content-center align-items-center">
            <button type="submit" class="btn btn-success">Continue</a>
            <button type="button" class="btn btn-info" data-dismiss="modal">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="error_response" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="error_responseLabel">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header pb-0">
        <h5 class="modal-title" id="error_responseLabel">{{ __('Error') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <h6 class="message_body">{{ __('Unknown error occurs') }}</h6>
      </div>
      <div class="modal-footer flex-nowrap justify-content-center align-items-center">
        <button type="button" class="btn btn-info" data-dismiss="modal">Ok</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="subscription_payment" tabindex="-1" aria-labelledby="subscription_paymentLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header border-bottom">
        <h5 class="modal-title text-17 mb-0 mt-0" id="subscription_paymentLabel">{{ __('Subscription') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="" id="subscription_payment_form">
        @csrf
        @method('POST')
        <div>
            <input type="hidden" name="vendor_id" value="{{ $vendor->id }}">
            <input type="hidden" name="email" value="{{ $vendor->email }}">
            <input type="hidden" name="subscription_id" id="subscription_id" value="">
            <input type="hidden" name="subscription_amount" id="subscription_amount" value="">
            <input type="hidden" name="card_last_four_digit" id="card_last_four_digit" value="">
            <input type="hidden" name="card_expiry_month" id="card_expiry_month" value="">
            <input type="hidden" name="card_expiry_year" id="card_expiry_year" value="">
        </div>
        <div class="modal-body pb-0">
            <div class="form-group">
                <h5 class="text-17 mb-2" id="subscription_title"></h5>
                <div class="mb-2"><span id="subscription_price"></span> / <span id="subscription_frequency"></span></div>
            </div>
            <div class="form-group">
                <div class="mt-2">{{ __("Features included") }}:
                    <div class="mt-2" id="features_list"></div>
                </div>
            </div>
            <hr class="mb-1" />
            <div class="payment_response">
                <div class="alert p-0 m-0" role="alert"></div>
            </div>
            <h5 class="text-17 mb-2">{{ __("Debit From") }}</h5>
            <div class="form-group" id="subscription_payment_methods">
            </div>
        </div>
        <div class="modal-footer d-block text-center">
            <div class="row">
                <div class="col-sm-12 p-0 d-flex justify-space-around">
                    <button type="button" class="btn btn-success btn-solid mt-2 subscription_confirm_btn">{{ __('Pay') }}</button>
                    <button type="button" class="btn btn-info btn-solid mt-2" data-dismiss="modal">{{ __("Cancel") }}</button>
                </div>
            </div>
        </div>
      </form>
    </div>
  </div>
</div>

<script type="text/template" id="payment_method_template">
    <% if(payment_options == '') { %>
        <h6>Payment Options Not Avaialable</h6>
    <% }else{ %>
        <% _.each(payment_options, function(payment_option, k){%>
            <% if( (payment_option.slug != 'cash_on_delivery') && (payment_option.slug != 'loyalty_points') ) { %>
                <div class="radio pl-1 mt-2 radio-blue form-check-inline">
                    <input type="radio" name="subscription_payment_method" id="radio-<%= payment_option.slug %>" value="<%= payment_option.slug %>" data-payment_option_id="<%= payment_option.id %>" {{ (isset($preference) && $preference->theme_admin =="light")? "checked" : "" }}>
                    <label for="radio-<%= payment_option.slug %>"> <%= payment_option.title %> </label>
                </div>
                <% if(payment_option.slug == 'stripe') { %>
                    <div class="col-md-12 mt-3 mb-3 stripe_element_wrapper d-none">
                        <div class="form-control">
                            <label class="d-flex flex-row mb-0">
                                <div class="w-100" id="stripe-card-element"></div>
                            </label>
                        </div>
                        <span class="error text-danger" id="stripe_card_error"></span>
                    </div>
                <% } %>
            <% } %>
        <% }); %>
    <% } %>
</script>
@endif