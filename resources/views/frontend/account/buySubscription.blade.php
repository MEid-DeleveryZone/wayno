@extends('layouts.store', ['title' => 'Buy Subscription'])
@section('css')
<link href="{{asset('assets/libs/dropzone/dropzone.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/dropify/dropify.min.css')}}" rel="stylesheet" type="text/css" />
<style type="text/css">
    .main-menu .brand-logo {
        display: inline-block;
        padding-top: 20px;
        padding-bottom: 20px;
    }
</style>
@endsection
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
<style type="text/css">
    .invalid-feedback {
        display: block;
    }
</style>
<section class="section-b-space">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="text-sm-left">
                    @if (\Session::has('success'))
                        <div class="alert alert-success">
                            <span>{!! \Session::get('success') !!}</span>
                        </div>
                    @endif
                    @if ( ($errors) && (count($errors) > 0) )
                        <div class="alert alert-danger">
                            <ul class="m-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="payment_response">
                        <div class="alert p-0 m-0" role="alert"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3">
                <div class="account-sidebar"><a class="popup-btn">my account</a></div>
                @include('layouts.store/profile-sidebar')
            </div>
            <div class="col-lg-9">
                <div class="dashboard-right">
                    <div class="dashboard">
                        <div class="page-title">
                            <h2>Buy Subscription</h2>
                        </div>
                    </div>
                </div>
                
                <div class="row mt-4" id="subscription_payment_methods">
                @if($payment_options == '')
                    <div class="col-md-12">
                        <h6>Payment Methods Not Avaialable</h6>
                    </div>
                @else
                    <div class="col-md-12">
                        <h5><b>Plan :</b> {{ $subscription->title }}</h5>
                        <h5><b>Price :</b> ${{ $subscription->price }}</h5>
                    </div>
                    @foreach($payment_options as $payment_option)
                        @if( ($payment_option->slug != 'cash_on_delivery') && ($payment_option->slug != 'loyalty_points') )
                        <div class="col-md-12">
                            <label class="radio mt-2">
                                {{ $payment_option->title }}
                                <input type="radio" name="subscription_payment_method" id="radio-{{ $payment_option->slug }}" value="{{ $payment_option->slug }}">
                                <span class="checkround"></span>
                            </label>
                            @if($payment_option->slug == 'stripe')
                                <div class="col-md-12 mt-3 mb-3 stripe_element_wrapper d-none">
                                    <div class="form-control">
                                        <label class="d-flex flex-row mb-0">
                                            <div id="stripe-card-element"></div>
                                        </label>
                                    </div>
                                    <span class="error text-danger" id="stripe_card_error"></span>
                                </div>
                            @endif
                        </div>
                        @endif
                    @endforeach
                    <div class="col-md-12">
                        <button type="button" class="btn btn-solid mt-2 buy_subscription">Buy Now</button>
                        <button type="button" class="btn btn-solid mt-2 black-btn">Cancel</button>
                    </div>
                @endif
                </div>
            </div>
        </div>
    </div>
</section>
<div class="modal fade" id="confirm-buy-subscription" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="confirm_buy_subscriptionLabel">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header pb-0">
        <h5 class="modal-title" id="confirm_buy_subscriptionLabel">Confirm Subscription</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <h6 class="m-0">Do you really want to buy this subscription ?</h6>
      </div>
      <div class="modal-footer flex-nowrap justify-content-center align-items-center">
        <button type="button" class="btn btn-solid" id="continue_buy_subscription_btn" data-id="{{ $subscription->slug }}">Continue</button>
        <button type="button" class="btn btn-solid black-btn" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>
@endsection
@section('script')
<script src="{{asset('assets/libs/dropzone/dropzone.min.js')}}"></script>
<script src="{{asset('assets/libs/dropify/dropify.min.js')}}"></script>
<script src="https://js.stripe.com/v3/"></script>
<script type="text/javascript">
    var payment_option_list_url = "{{route('payment.option.list')}}";

    $(document).on('change', '#subscription_payment_methods input[name="subscription_payment_method"]', function() {
        var method = $(this).val();
        if(method == 'stripe'){
            $("#subscription_payment_methods .stripe_element_wrapper").removeClass('d-none');
        }else{
            $("#subscription_payment_methods .stripe_element_wrapper").addClass('d-none');
        }
    });
</script>
@endsection