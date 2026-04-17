<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="{{asset('assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css"
        id="bs-default-stylesheet" />
</head>

@php
$timezone = Auth::user()->timezone;
@endphp

<body style="background-color: #ffffff">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="float-left">
                    <div class="auth-logo">
                        <div class="logo logo-dark">
                            <span class="logo-lg">
                                <img src="{{$urlImg}}" alt="" height="50">
                            </span>
                        </div>
                    </div>
                </div>
                <div class="float-right">
                    <h4 class="m-0 d-print-none">Invoice</h4>
                </div>
            </div>
            <br>
            <br>

            <div class="row">
                <div class="col-md-4 offset-md-2">
                    <div class="mt-3 float-right">
                        <p class="m-b-10 ">
                            <strong>Order Date : </strong>
                            <span class="">
                                {{convertDateTimeInTimeZone($order->created_at, $timezone, 'l, F d, Y')}}
                            </span>
                        </p>
                        <p class="m-b-10">
                            <strong>Order Status : </strong>
                            <span class="float-right">
                                <span class="badge badge-success">Paid</span>
                            </span>
                        </p>
                        <p class="m-b-10"><strong>Order No. : </strong> 
                            <span class="float-right">{{$order->order_number}} </span>
                        </p>
                    </div>
                </div><!-- end col -->
            </div>
            <!-- end row -->

            <div class="row mt-3">
                <div class="col-sm-6">
                    <h6>Pick Up Address</h6>
                    <address>
                        <abbr title="Name">Name:</abbr> {{$order->pickup_name}} <br>
                        {{$order->pickup_address}} <br>
                        <abbr title="Phone">Phone:</abbr> {{$order->pickup_phone_number}}
                    </address>
                </div> <!-- end col -->

                <div class="col-sm-6">
                    <h6>Dropoff Address</h6>
                    <address>
                        <abbr title="Name">Name:</abbr> {{$order->drop_name}} <br>
                        {{$order->drop_address}} <br>
                        <abbr title="Phone">Phone:</abbr> {{$order->drop_phone_number}} 
                    </address>
                </div> <!-- end col -->
            </div>
            <!-- end row -->

            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table mt-4 table-centered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Item</th>
                                    <th style="width: 10%">Qty</th>
                                    <th>Price</th>
                                    <th class="text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $taxable_amount = 0;
                                @endphp
                                @foreach ($order->vendors as $vendor)
                                    @foreach ($vendor->products as $item)
                                        @php
                                        $taxable_amount += $item->taxable_amount;
                                        @endphp
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>{{$item->product_name}}</td>
                                            <td>{{$item->quantity}}</td>
                                            <td>{{$currency}}{{$item->price}}</td>
                                            <td class="text-right">{{$currency}} {{$item->quantity * $item->price}}</td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div> <!-- end table-responsive -->
                </div> <!-- end col -->
            </div>
            <!-- end row -->

            <div class="row">
                <hr>
                <div class="col-sm-6 offset-sm-6">
                    <div class="">
                        <p>
                            <b>Service Fee: </b>
                            <span class="float-right"> {{$currency}} {{$order->service_fee}}</span>
                        </p>
                        @if($order->type != 'delivery')
                            <p>
                                <b>Discount:</b>
                                <span class="float-right">{{$currency}} {{$order->total_discount}}</span>
                            </p>
                        @endif
                        @if ($order->loyalty_amount_saved > 0)
                            <p>
                                <b>Loyalty Saving:</b>
                                <span class="float-right">{{$currency}} {{$order->loyalty_amount_saved}}</span>
                            </p>
                        @endif
                        <p>
                            <b>Tax:</b>
                            <span class="float-right">{{$currency}} {{$order->taxable_amount}}</span>
                        </p>
                        <p>
                        <h3>
                            <b>Total:</b>
                            <span class="float-right">{{$currency}} {{$order->payable_amount}}</span>
                        </h3>
                        </p>
                    </div>
                    <div class="clearfix"></div>
                </div> <!-- end col -->
            </div>
            <!-- end row -->
        </div>
        <!-- end row -->

    </div> <!-- container -->
</body>

</html>