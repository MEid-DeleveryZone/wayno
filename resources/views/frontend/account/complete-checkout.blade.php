<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="_token" content="{{ csrf_token() }}">
    <title>Complete Checkout</title>
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{asset('front-assets/css/font-awesome.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('front-assets/css/themify-icons.css')}}">
    <link href="{{asset('assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" id="bs-default-stylesheet" />
    <link rel="stylesheet" type="text/css" href="{{asset('front-assets/css/custom.css')}}">
</head>
<body>

<section class="section-b-space">
    <div class="container-fluid">
        <div class="payment_response">
            <div class="alert p-0 mt-2" role="alert"></div>
        </div>
        <div class="row">
            <div class="col-lg-12"> 
                <h4 class="processing"><i class="fa fa-spinner fa-pulse mr-2"></i> Please wait until your payment is completed...</h4>
            </div>
        </div>
    </div>
</section>

<script src="{{asset('front-assets/js/jquery-3.3.1.min.js')}}"></script>
<script>
    var place_order_url = "{{route('user.postPaymentPlaceOrder')}}";
    var credit_wallet_url = "{{route('user.postPaymentCreditWallet')}}";
    var paypal_complete_purchase_url = "{{route('payment.paypalCompletePurchase')}}";
    var checkout_success_url = "{{route('payment.getCheckoutSuccess', ':id')}}";
    let path = window.location.pathname;
    let queryString = window.location.search;
    let urlParams = new URLSearchParams(queryString);
    let amount = 0;
    let action = "{{ $action }}";
    let authToken = "{{ $auth_token }}";
    let address_id = "{{ $address_id }}";

    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') }
    });

    if( (urlParams.has('amount')) && (urlParams.has('PayerID')) && (urlParams.has('token')) ){
        amount = urlParams.get('amount');
        paymentSuccessViaPaypal(amount, urlParams.get('token'), urlParams.get('PayerID'), path, address_id);
    }

    function paymentSuccessViaPaypal(amount, token, payer_id, path, addressID=''){
        $.ajax({
            type: "GET",
            dataType: 'json',
            url: paypal_complete_purchase_url,
            data: {'amount': amount, 'token': token, 'PayerID': payer_id},
            success: function (response) {
                if(response.status == "Success"){
                    if(action == "cart"){
                        checkout_success_url = checkout_success_url.replace(":id", response.data);
                        document.location.href = checkout_success_url;
                        // placeOrder(addressID, 3, response.data);
                    }
                    else if(action = "wallet"){
                        creditWallet(amount, 3, response.data);
                    }
                }else{
                    if(action == "cart"){
                        success_error_alert('error', response.message, ".payment_response");
                        $(".processing").hide();
                    }
                    else if(action = "wallet"){
                        success_error_alert('error', response.message, ".payment_response");
                        $(".processing").hide();
                    }
                }
            },
            error: function(error){
                var response = $.parseJSON(error.responseText);
                if(action == "cart"){
                    success_error_alert('error', response.message, ".payment_response");
                }
                else if(action = "wallet"){
                    success_error_alert('error', response.message, ".payment_response");
                }
            }
        });
    }

    function placeOrder(addressID, payment_option_id, transaction_id){
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: place_order_url,
            data: {auth_token: authToken, address_id:addressID, payment_option_id:payment_option_id, transaction_id:transaction_id},
            success: function(response) {
                if (response.status == "Success") {
                    document.location.href = checkout_success_url;
                }else{
                    $(".processing").hide();
                    success_error_alert('error', response.message, ".payment_response");
                }
            },
            error: function(error){
                var response = $.parseJSON(error.responseText);
                success_error_alert('error', response.message, ".payment_response");
            }
        });
    }

    function creditWallet(amount, payment_option_id, transaction_id){
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: credit_wallet_url,
            data: {auth_token: authToken, wallet_amount:amount, payment_option_id:payment_option_id, transaction_id:transaction_id},
            success: function(response) {
                if (response.status == "Success") {
                    document.location.href = checkout_success_url;
                }else{
                    $(".processing").hide();
                    success_error_alert('error', response.message, ".payment_response");
                }
            },
            error: function(error){
                $(".processing").hide();
                var response = $.parseJSON(error.responseText);
                success_error_alert('error', response.message, ".payment_response");
            }
        });
    }

    function success_error_alert(responseClass, message, element){
        $(element).find(".alert").html('');
        if(responseClass == 'success'){
            $(element).find(".alert").html("<div class='alert-success p-1'>"+message+"</div>").show();
        }else if(responseClass == 'error'){
            $(element).find(".alert").html("<div class='alert-danger p-1'>"+message+"</div>").show();
        }
        // setTimeout(function(){
        //     $(element).find(".alert").hide();
        // }, 8000);
    }

</script>
</body>
</html>
