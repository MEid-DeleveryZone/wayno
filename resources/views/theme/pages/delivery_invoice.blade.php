<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Invoice') }}</title>

    <style>
        body {
            background-color: #ffffff;
            font-family: DejaVu Sans, sans-serif;
            font-size: 16px;
            line-height: 1.5;
        }

        .container {
            margin: 0 auto;
            padding: 20px;
        }

        .row {
            margin-left: -10px;
            margin-right: -10px;
        }

        html[dir="rtl"] .row {
            margin-left: -10px;
            margin-right: -10px;
        }

        .row::after {
            content: "";
            display: table;
            clear: both;
        }

        .col-12 {
            width: 100%;
            float: left;
        }

        html[dir="rtl"] .col-12 {
            float: right;
        }

        .col-md-6 {
            width: 50%;
            float: left;
            padding-left: 10px;
            padding-right: 10px;
            box-sizing: border-box;
        }

        html[dir="rtl"] .col-md-6 {
            float: right;
        }

        .logo img {
            height: 50px;
            width: auto;
        }

        h4 {
            font-size: 32px;
            font-weight: 600;
            margin: 0;
            text-align: left;
        }

        html[dir="rtl"] h4 {
            text-align: right;
        }

        .section-title {
            font-weight: bold;
            font-size: 18px;
            margin-bottom: 12px;
            text-align: left;
        }

        html[dir="rtl"] .section-title {
            text-align: right;
        }

        address {
            font-style: normal;
            font-size: 15px;
            text-align: left;
        }

        html[dir="rtl"] address {
            text-align: right;
        }

        .details-line {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            width: 100%;
        }

        .details-line span:first-child {
            text-align: left;
            font-size: 16px;
        }

        .details-line span:last-child {
            text-align: right;
            font-size: 16px;
        }

        html[dir="rtl"] .details-line {
            justify-content: space-between;
        }

        html[dir="rtl"] .details-line span:first-child {
            text-align: right;
            font-size: 15px;
        }

        html[dir="rtl"] .details-line span:last-child {
            text-align: left;
            margin-left: 0;
            margin-right: auto;
            font-size: 18px;
            font-weight: 500;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
            font-size: 15px;
            direction: ltr;
        }

        html[dir="rtl"] table {
            direction: rtl;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            font-size: 16px;
            font-weight: 600;
        }

        td {
            font-size: 15px;
        }

        html[dir="rtl"] th,
        html[dir="rtl"] td {
            text-align: right;
        }

        html[dir="rtl"] table thead tr,
        html[dir="rtl"] table tbody tr {
            direction: rtl;
        }

        .text-right {
            text-align: right;
        }

        html[dir="rtl"] .text-right {
            text-align: left;
        }

        html[dir="rtl"] .text-left {
            text-align: right;
        }

        .total-section {
            font-size: 16px;
            text-align: left;
        }

        html[dir="rtl"] .total-section {
            text-align: right;
        }

        html[dir="rtl"] .total-section-wrapper {
            display: flex;
            justify-content: flex-end;
            margin-top: 20px;
            width: 100%;
        }

        html[dir="ltr"] .total-section-wrapper {
            display: flex;
            justify-content: flex-end;
            margin-top: 20px;
            width: 100%;
        }

        html[dir="rtl"] .total-section {
            text-align: right;
            width: auto;
            min-width: 200px;
        }

        html[dir="ltr"] .total-section {
            text-align: left;
            width: 40%;
        }

        .total-section .details-line {
            font-size: 16px;
        }

        .total-section .details-line b {
            font-size: 16px;
            font-weight: 600;
        }

        .total-section .details-line span {
            font-size: 16px;
        }

    </style>
</head>

@php
$timezone = Auth::user()->timezone;
@endphp

<body>
    <div class="container">
        
        <!-- HEADER -->
        <div class="row">
            <div class="col-12">
                <div style="float:{{ app()->getLocale() == 'ar' ? 'right' : 'left' }};">
                    <img src="data:image/png;base64,{{$urlImg}}" height="50">
                </div>

                <div style="float:{{ app()->getLocale() == 'ar' ? 'left' : 'right' }};">
                    <h4 style="margin: 0;">{{ __('Invoice') }}</h4>
                </div>
            </div>
        </div>

        <br><br>

        <!-- ORDER DETAILS + ADDRESS SIDE BY SIDE -->
        <div class="row">
            <!-- ORDER DETAILS -->
            <div class="col-md-6" style="{{ app()->getLocale() == 'ar' ? 'text-align: right;' : 'text-align: left;' }}">
                <div class="section-title">{{ __('Order Details') }}</div>

                <div class="details-line">
                    @if(app()->getLocale() == 'ar')
                        <span style="text-align: right;">{{ convertDateTimeInTimeZone($order->created_at, $timezone, 'l, F d, Y') }}</span>
                        <span style="text-align: right;">{{ __('Order Date') }}:</span>
                    @else
                        <span>{{ __('Order Date') }}:</span>
                        <span>{{ convertDateTimeInTimeZone($order->created_at, $timezone, 'l, F d, Y') }}</span>
                    @endif
                </div>

                <div class="details-line">
                    @if(app()->getLocale() == 'ar')
                        <span style="background:#28a745;color:#fff;padding:5px;border-radius:3px;">{{ __('Paid') }}</span>
                        <span style="text-align: right;">{{ __('Order Status') }}:</span>
                    @else
                        <span>{{ __('Order Status') }}:</span>
                        <span style="background:#28a745;color:#fff;padding:5px;border-radius:3px;">{{ __('Paid') }}</span>
                    @endif
                </div>

                <div class="details-line">
                    @if(app()->getLocale() == 'ar')
                        <span>{{ $order->order_number }}</span>
                        <span style="text-align: right;">{{ __('Order No.') }}:</span>
                    @else
                        <span>{{ __('Order No.') }}:</span>
                        <span>{{ $order->order_number }}</span>
                    @endif
                </div>
            </div>

            <!-- PICKUP + DROPOFF -->
            <div class="col-md-6">
                <!-- PICKUP -->
                <div class="section-title">{{ __('Pick Up Address') }}</div>
                <address>
                    <div class="details-line">
                        @if(app()->getLocale() == 'ar')
                            <span>{{ $order->pickup_name }}</span>
                            <span style="text-align: right;">{{ __('Name') }}:</span>
                        @else
                            <span>{{ __('Name') }}:</span>
                            <span>{{ $order->pickup_name }}</span>
                        @endif
                    </div>

                    <div class="details-line">
                        @if(app()->getLocale() == 'ar')
                            <span>{{ $order->pickup_address }}</span>
                            <span style="text-align: right;">{{ __('Address') }}:</span>
                        @else
                            <span>{{ __('Address') }}:</span>
                            <span>{{ $order->pickup_address }}</span>
                        @endif
                    </div>

                    <div class="details-line">
                        @if(app()->getLocale() == 'ar')
                            <span>{{ $order->pickup_phone_number }}</span>
                            <span style="text-align: right;">{{ __('Phone') }}:</span>
                        @else
                            <span>{{ __('Phone') }}:</span>
                            <span>{{ $order->pickup_phone_number }}</span>
                        @endif
                    </div>
                </address>

                <!-- DROPOFF -->
                <div class="section-title" style="margin-top:15px;">{{ __('Dropoff Address') }}</div>
                <address>
                    <div class="details-line">
                        @if(app()->getLocale() == 'ar')
                            <span>{{ $order->drop_name }}</span>
                            <span style="text-align: right;">{{ __('Name') }}:</span>
                        @else
                            <span>{{ __('Name') }}:</span>
                            <span>{{ $order->drop_name }}</span>
                        @endif
                    </div>

                    <div class="details-line">
                        @if(app()->getLocale() == 'ar')
                            <span>{{ $order->drop_address }}</span>
                            <span style="text-align: right;">{{ __('Address') }}:</span>
                        @else
                            <span>{{ __('Address') }}:</span>
                            <span>{{ $order->drop_address }}</span>
                        @endif
                    </div>

                    <div class="details-line">
                        @if(app()->getLocale() == 'ar')
                            <span>{{ $order->drop_phone_number }}</span>
                            <span style="text-align: right;">{{ __('Phone') }}:</span>
                        @else
                            <span>{{ __('Phone') }}:</span>
                            <span>{{ $order->drop_phone_number }}</span>
                        @endif
                    </div>
                </address>
            </div>
        </div>

        <!-- ITEM TABLE -->
        <table class="table mt-4 table-centered">
            <thead>
                <tr>
                    @if(app()->getLocale() == 'ar')
                        <th class="text-right">{{ __('Total') }}</th>
                        <th>{{ __('Price') }}</th>
                        <th style="width: 10%">{{ __('Qty') }}</th>
                        <th>{{ __('Item') }}</th>
                        <th>#</th>
                    @else
                        <th>#</th>
                        <th>{{ __('Item') }}</th>
                        <th style="width: 10%">{{ __('Qty') }}</th>
                        <th>{{ __('Price') }}</th>
                        <th class="text-right">{{ __('Total') }}</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @php $taxable_amount = 0; @endphp

                @foreach ($order->vendors as $vendor)
                    @foreach ($vendor->products as $item)
                        @php $taxable_amount += $item->taxable_amount; @endphp
                        <tr>
                            @if(app()->getLocale() == 'ar')
                                <td class="text-right">{{ $item->quantity * $item->price }} {{ $currency }}</td>
                                <td>{{ $item->price }} {{ $currency }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ $item->product_name }}</td>
                                <td>{{ $loop->iteration }}</td>
                            @else
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->product_name }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ $currency }}{{ $item->price }}</td>
                                <td class="text-right">{{ $currency }} {{ $item->quantity * $item->price }}</td>
                            @endif
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>

        <!-- TOTAL SECTION -->
        <div class="total-section-wrapper">
            <div class="total-section">
            @if ($order->loyalty_amount_saved > 0)
                <div class="details-line">
                    @if(app()->getLocale() == 'ar')
                        <span>{{ $order->loyalty_amount_saved }} {{ $currency }}</span>
                        <b style="text-align: right;">{{ __('Loyalty Saving') }}:</b>
                    @else
                        <b>{{ __('Loyalty Saving') }}:</b>
                        <span>{{ $currency }} {{ $order->loyalty_amount_saved }}</span>
                    @endif
                </div>
            @endif

            @if ($order->total_discount > 0)
                <div class="details-line">
                    @if(app()->getLocale() == 'ar')
                        <span>{{ number_format($order->total_discount, 2, '.', '') }} {{ $currency }}</span>
                        <b style="text-align: right;">{{ __('Total Discount') }}:</b>
                    @else
                        <b>{{ __('Total Discount') }}:</b>
                        <span>{{ $currency }} {{ number_format($order->total_discount, 2, '.', '') }}</span>
                    @endif
                </div>
            @endif

            <div class="details-line">
                @if(app()->getLocale() == 'ar')
                    <span>{{ $order->taxable_amount }} {{ $currency }}</span>
                    <b style="text-align: right;">{{ __('VAT') }}:</b>
                @else
                    <b>{{ __('VAT') }}:</b>
                    <span>{{ $currency }} {{ $order->taxable_amount }}</span>
                @endif
            </div>

            <div class="details-line" style="margin-top:10px;">
                @if(app()->getLocale() == 'ar')
                    <span style="font-size: 22px; font-weight: 700;">{{ $order->payable_amount }} {{ $currency }}</span>
                    <b style="font-size: 22px; font-weight: 700; text-align: right;">{{ __('Total') }}:</b>
                @else
                    <b style="font-size: 22px; font-weight: 700;">{{ __('Total') }}:</b>
                    <span style="font-size: 22px; font-weight: 700;">{{ $currency }} {{ $order->payable_amount }}</span>
                @endif
            </div>
        </div>

    </div>
</body>

</html>
