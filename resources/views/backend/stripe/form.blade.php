@extends('layouts.vertical', ['demo' => 'creative', 'title' => 'Customers'])

@section('css')
<link href="{{asset('assets/libs/dropzone/dropzone.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/dropify/dropify.min.css')}}" rel="stylesheet" type="text/css" />
@endsection

@section('content')

<!-- Start Content-->
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Customers</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-sm-8">
                            <div class="text-sm-left">
                                @if (\Session::has('success'))
                                <div class="alert alert-success">
                                    <span>{!! \Session::get('success') !!}</span>
                                </div>
                                @endif
                                @if (\Session::has('error_delete'))
                                <div class="alert alert-danger">
                                    <span>{!! \Session::get('error_delete') !!}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-4 text-right">
                            <button class="btn btn-info waves-effect waves-light text-sm-right openBannerModal" userId="0"><i class="mdi mdi-plus-circle mr-1"></i> Add
                            </button>
                        </div>
                    </div>

                    <form method="post" id="paymentFrm" enctype="multipart/form-data" action="{{route('stripe.makePayment')}}">
                        @csrf
                        <div class="form-group">
                            <input type="text" name="name" class="form-control" placeholder="Name" required>
                        </div>

                        <div class="form-group">
                            <input type="email" name="email" class="form-control" placeholder="email@you.com" required />
                        </div>

                        <div class="form-group">
                            <input type="number" name="card_num" id="card_num" class="form-control" placeholder="Card Number" autocomplete="off" required>
                        </div>


                        <div class="row">
                            4242424242424242
                            <div class="col-sm-8">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <input type="text" name="exp_month" maxlength="2" class="form-control" id="card-expiry-month" placeholder="MM" required>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <input type="text" name="exp_year" class="form-control" maxlength="4" id="card-expiry-year" placeholder="YYYY" required="" value="2022">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <input type="text" name="cvc" id="card-cvc" maxlength="3" class="form-control" autocomplete="off" placeholder="CVC" required>
                                </div>
                            </div>
                        </div>




                        <div class="form-group text-right">
                            <button class="btn btn-secondary" type="reset">Reset</button>
                            <button type="button" id="payBtn" class="btn btn-success">Submit Payment</button>
                        </div>
                    </form>
                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col -->
    </div>
</div>

@include('backend.banner.modals')
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

@include('backend.banner.pagescript')

@endsection