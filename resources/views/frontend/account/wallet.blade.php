@extends('layouts.store', ['title' => 'My Wallet'])
@section('css')
<style type="text/css">
    .main-menu .brand-logo {
        display: inline-block;
        padding-top: 20px;
        padding-bottom: 20px;
    }
</style>
@endsection
@section('content')
@php
$timezone = Auth::user()->timezone;
@endphp
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
    .productVariants .firstChild {
        min-width: 150px;
        text-align: left !important;
        border-radius: 0% !important;
        margin-right: 10px;
        cursor: default;
        border: none !important;
    }
    .product-right .color-variant li,
    .productVariants .otherChild {
        height: 35px;
        width: 35px;
        border-radius: 50%;
        margin-right: 10px;
        cursor: pointer;
        border: 1px solid #f7f7f7;
        text-align: center;
    }
    .productVariants .otherSize {
        height: auto !important;
        width: auto !important;
        border: none !important;
        border-radius: 0%;
    }
    .product-right .size-box ul li.active {
        background-color: inherit;
    }
    .login-page .theme-card .theme-form input {
        margin-bottom: 5px;
    }
    .invalid-feedback {
        display: block;
    }
    .box-info table tr:first-child td {
        padding-top: .85rem;
    }
</style>
<section class="section-b-space">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="text-sm-left" id="wallet_response">
                    @if (\Session::has('success'))
                        <div class="alert alert-success">
                            <span>{!! \Session::get('success') !!}</span>
                        </div>
                        @php
                            \Session::forget('success');
                        @endphp
                    @endif
                    @if (\Session::has('error'))
                        <div class="alert alert-danger">
                            <span>{!! \Session::get('error') !!}</span>
                        </div>
                        @php
                            \Session::forget('error');
                        @endphp
                    @endif
                    <div class="message d-none">
                        <div class="alert p-0"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3 profile-sidebar">
                <div class="account-sidebar"><a class="popup-btn">{{__('My Account')}}</a></div>
                <div class="dashboard-left">
                    <div class="collection-mobile-back">
                        <span class="filter-back">
                            <i class="fa fa-angle-left" aria-hidden="true"></i>{{__('Back')}}
                        </span>
                        </div>
                    @include('layouts.store/profile-sidebar')
                </div>
            </div>
            <div class="col-lg-9">
                <div class="dashboard-right">
                    <div class="dashboard">
                        <div class="page-title">
                            <h3 class="mt-0">{{__('My Wallet')}}</h3>
                        </div>
                        <div class="box-account box-info">
                            <div class="card-box mb-0">
                                <div class="row align-items-center">
                                    <div class="col-md-6 text-md-left text-center mb-md-0 mb-4">
                                        <h5 class="text-17 mb-2 mt-0">{{__('Available Balance')}}</h5>
                                        <div class="text-36">{{Session::get('currencySymbol')}} <span class="wallet_balance">@money(Auth::user()->balanceFloat * $clientCurrency->doller_compare)</span></div>
                                    </div>
                                    <div class="col-md-6 text-md-right text-center">
                                        <button type="button" class="btn btn-solid" id="topup_wallet_btn" data-toggle="modal" data-target="#topup_wallet">{{__('Topup Wallet')}}</button>
                                        <!-- <button type="button" class="btn btn-solid" data-toggle="modal" data-target="#add-money">{{__('Payout')}}</button> -->
                                    </div>
                                </div>
                            </div>
                            <h6>{{__('Transaction History')}}</h6>
                            <div class="card-box" id="wallet_transactions_history">
                                <div class="table-responsive table-responsive-xs">
                                  <table class="table wallet-transactions border">
                                    <thead>
                                        <tr class="table-head">
                                            <th>{{__('Date')}}</th>
                                            <th>{{__('Description')}}</th>
                                            <th class="text-right" style="white-space:nowrap"><span class="text-success">{{__('Credit')}}</span> / <span class="text-danger">{{__('Debit')}}</span></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($user_transactions as $ut)
                                        <?php $reason = json_decode($ut->meta) ?>
                                        @php
                                        $amount = ($ut->amount / 100) * $clientCurrency->doller_compare;
                                        @endphp
                                          <tr>
                                              <td>{{convertDateTimeInTimeZone($ut->created_at, $timezone, 'l, F d, Y, H:i A')}}</td>
                                              <td  class="name_">{!!$reason[0]!!}</td>
                                              <td class="text-right {{ ($ut->type == 'deposit') ? 'text-success' : (($ut->type == 'withdraw') ? 'text-danger' : '') }}"><b>{{Session::get('currencySymbol')}} @money(sprintf("%.2f",$amount))</b></td>
                                          </tr>
                                        @endforeach
                                    </tbody>
                                  </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<div class="modal fade wallet_money" id="add-money" tabindex="-1" aria-labelledby="add-moneyLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header border-bottom">
        <h5 class="modal-title" id="add-moneyLabel">{{__('Pay-Out')}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="">
            <div class="form-group">
                <label for="">{{__('Account Number')}}</label>
                <input class="form-control" type="text" placeholder="Account Number">
            </div>
            <div class="form-group">
                <label for="">{{__('Account Name')}}</label>
                <input class="form-control" type="text" placeholder="Account Name">
            </div>
            <div class="form-group">
                <label for="">{{__('Bank Name')}}</label>
                <input class="form-control" type="text" placeholder="Bank Name">
            </div>
            <div class="form-group">
                <label for="">{{__('IFSC Code')}}</label>
                <input class="form-control" type="text" placeholder="IFSC Code">
            </div>
            <button type="button" class="btn btn-solid w-100 mt-2" data-dismiss="modal">{{__('Close')}}</button>
        </form>
      </div>
    </div>
  </div>
</div>
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
                <div class="text-36">{{Session::get('currencySymbol')}} <span class="wallet_balance">@money(Auth::user()->balanceFloat * $clientCurrency->doller_compare)</span></div>
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
@endsection
