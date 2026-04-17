@extends('layouts.store', ['title' => 'Payment Page'])

@section('css')
<style type="text/css">
    .main-menu .brand-logo {
        display: inline-block;
        padding-top: 20px;
        padding-bottom: 20px;
    }

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

    .iti__flag-container li,
    .flag-container li {
        display: block;
    }

    .iti.iti--allow-dropdown,
    .allow-dropdown {
        position: relative;
        display: inline-block;
        width: 100%;
    }

    .iti.iti--allow-dropdown .phone,
    .flag-container .phone {
        padding: 17px 0 17px 100px !important;
    }

    .social-logins {
        text-align: center;
    }

    .social-logins img {
        width: 100px;
        height: 100px;
        border-radius: 100%;
        margin-right: 20px;
    }

    .register-page .theme-card .theme-form input {
        margin-bottom: 5px;
    }

    .invalid-feedback {
        display: block;
    }
</style>
<link rel="stylesheet" href="{{asset('assets/css/intlTelInput.css')}}">
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

<section class="register-page section-b-space">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h3>Payment</h3>
                <div class="theme-card">
                    <form method="post" class="theme-form" id="paymentFrm" enctype="multipart/form-data" action="{{route('placeorder.makePayment')}}"> @csrf
                    <div class="form-row mb-3">
                    <input type="hidden" required="" name="address_id" value="{{$address_id}}" readonly>
                            <div class="col-md-6">
                                <label for="first_name">First Name</label>
                                <input type="text" class="form-control" id="first_name" placeholder="Name" required="" name="first_name" value="{{$first_name}}" readonly>
                                @if($errors->first('first_name'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('first_name') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <label for="last_name">Last Name</label>
                                <input type="text" class="form-control" id="last_name" placeholder="Last Name" required="" name="last_name" value="{{$last_name}}" readonly>
                                @if($errors->first('last_name'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('last_name') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-row mb-3">
                            <div class="col-md-6">
                                <label for="phone">Phone</label>
                                <input type="text" class="form-control" id="phone" placeholder="Phone" required="" name="phone" value="{{$phone}}" readonly>
                                @if($errors->first('phone'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('phone') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <label for="email">Email</label>
                                <input type="text" class="form-control" id="email" placeholder="Email" required="" name="email" value="{{$email_address}}" readonly>
                                @if($errors->first('email'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-row mb-3">
                            <div class="col-md-6">
                                <label for="amount">Amount</label>
                                <input type="text" class="form-control" id="amount" placeholder="Amount" required="" name="amount" value="{{$total_amount}}" readonly>
                                @if($errors->first('amount'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('amount') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <label for="card_num">Card Number</label>
                                <input type="number" class="form-control" id="card_num" placeholder="Card Number" name="card_num" value="4242424242424242" autocomplete="off" required>
                                @if($errors->first('card_num'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('card_num') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-row mb-3">
                            <div class="col-md-3">
                                <label for="exp_month">Expiry Month</label>
                                <input type="text" name="exp_month" maxlength="2" class="form-control" id="card-expiry-month" placeholder="MM" value="06" required>
                                @if($errors->first('exp_month'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('exp_month') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="col-md-3">
                                <label for="exp_year">Expiry Year</label>
                                <input type="text" name="exp_year" class="form-control" maxlength="4" id="card-expiry-year" placeholder="YYYY" required="" value="2022">
                                @if($errors->first('exp_year'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('exp_year') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <label for="cvc">CVC</label>
                                <input type="text" name="cvc" id="card-cvc" maxlength="3" class="form-control" autocomplete="off" placeholder="CVC" value="123" required>
                                @if($errors->first('cvc'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('cvc') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                       
                        <div class="form-row mb-3">
                            <button type="button" id="payBtn" class="btn btn-solid mt-3">Submit Payment</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@section('script')
<script src="https://js.stripe.com/v2/"></script>

<script type="text/javascript">
    //set your publishable key
    Stripe.setPublishableKey('pk_test_51IhpwhSFHEA938Fwmi5DmvgIhuS7sT0oVrxl0OWeG2FdiOLHUysQex9BH7xokl1En3wTe3JzgYyc1Axf8mlFrAWa00bTN1EPKM');

    //callback to handle the response from stripe
    function stripeResponseHandler(status, response) {
       
        if (response.error) {
            //enable the submit button
            $('#payBtn').removeAttr("disabled");
            //display the errors on the form
            // $('#payment-errors').attr('hidden', 'false');
            $('#payment-errors').addClass('alert alert-danger');
            $("#payment-errors").html(response.error.message);
        } else {
            var form$ = $("#paymentFrm");
            //get token id
            var token = response['id'];
            //insert the token into the form
            form$.append("<input type='hidden' name='stripeToken' value='" + token + "' />");
            //submit form to the server
            form$.get(0).submit();
        }
    }
    $(document).ready(function() {
        //on form submit
        $("#payBtn").click(function() {
            console.log("hogya");
            //disable the submit button to prevent repeated clicks
            $('#payBtn').attr("disabled", "disabled");
            
            //create single-use token to charge the user
            Stripe.createToken({
                number: $('#card_num').val(),
                cvc: $('#card-cvc').val(),
                exp_month: $('#card-expiry-month').val(),
                exp_year: $('#card-expiry-year').val()
            }, stripeResponseHandler);
            //submit from callback
            return false;
        });
    });
</script>
@endsection