@extends('layouts.vertical', ['title' => 'Order Detail'])
@section('css')
<!-- <style>
td { white-space:pre-line; word-break:break-all}
</style> -->
@endsection
@section('content')
@php
$timezone = Auth::user()->timezone;
@endphp
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">{{ __("Order Detail") }}</h4>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 mb-3">
                <div class="card mb-0 h-100">
                    <div class="card-body">
                        <h4 class="header-title mb-3">{{__('Track Order')}}</h4>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-4">
                                    <h5 class="mt-0">{{__('Order ID')}}:</h5>
                                    <p>#{{$order->order_number}}</p>
                                </div>
                            </div>
                            @if(isset($order->vendors) && isset($order->vendors->first()->dispatch_traking_url) && $order->vendors->first()->dispatch_traking_url !=null)
                            <div class="col-lg-6">
                                <div class="mb-4">
                                    <h5 class="mt-0">{{ __("Tracking ID") }}:</h5>
                                    <p>
                                        @php
                                        $track = explode('/',$order->vendors->first()->dispatch_traking_url);
                                        $track_code = end($track);
                                        @endphp
                                        <a href="{{$order->vendors->first()->dispatch_traking_url}}" target="_blank">#{{ $track_code }}</a>
                                    </p>
                                </div>
                            </div>
                            @endif
                        </div>
                        


                        <!-- Enhanced Timeline Section -->
                        @if(isset($trackingData) && $trackingData)
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="mb-0">{{__('Order Timeline')}}</h6>
                                    <button type="button" class="btn btn-sm btn-outline-info" onclick="refreshTrackingData()">
                                        <i class="mdi mdi-refresh"></i> {{__('Refresh Timeline')}}
                                    </button>
                                </div>

                                <!-- Enhanced Timeline -->
                                @if(isset($trackingData['timeline_data']) && count($trackingData['timeline_data']) > 0)
                                <div class="card">
                                    <div class="card-body p-3">
                                        <h6 class="card-title text-warning mb-3">
                                            <i class="mdi mdi-timeline"></i> {{__('Order Status Timeline')}}
                                        </h6>
                                        <div class="enhanced-timeline">
                                            @foreach($trackingData['timeline_data'] as $step)
                                            <div class="timeline-step {{ $step['is_current'] ? 'current' : ($step['is_completed'] ? 'completed' : '') }} {{ $step['class'] == 'cancelled' ? 'cancelled' : '' }}">
                                                <div class="timeline-icon">
                                                    @if($step['icon'])
                                                        <img src="{{ asset($step['icon']) }}" alt="{{ $step['status'] }}" class="timeline-icon-img">
                                                    @else
                                                        <i class="fa fa-circle"></i>
                                                    @endif
                                                </div>
                                                <div class="timeline-content">
                                                    <div class="timeline-label {{ $step['class'] }}">{{ $step['status'] }}</div>
                                                    <div class="timeline-date">{{ $step['date'] ?? 'Pending' }}</div>
                                                    @if($step['description'])
                                                        <div class="timeline-description">{{ $step['description'] }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                @else
                                <div class="card">
                                    <div class="card-body p-3 text-center text-muted">
                                        <i class="mdi mdi-timeline-outline fa-2x mb-2"></i>
                                        <p class="mb-0">{{__('No timeline data available')}}</p>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-lg-8 mb-3">
                <div class="card mb-0 h-100">
                    <div class="card-body">
                        <h4 class="header-title mb-3">{{ __("Items from Order") }} #{{$order->order_number}}</h4>
                        @if($order->special_instruction != null)
                        <p class="header-title mb-3">{{ __("Special instruction from customer") }}: {{$order->special_instruction}}</p>
                        @endif
                        <div class="table-responsive">
                            <table class="table table-bordered table-centered mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>{{ __("Product Name") }}</th>
                                        <th>{{ __("Product") }}</th>
                                        <th>{{ __("Quantity") }}</th>
                                        <th>{{ __("Price") }}</th>

                                        <th>{{ __("Total") }}</th>
                                    </tr>
                                </thead>
                                @foreach($order->vendors as $vendor)
                                <tbody>
                                    @php
                                    $sub_total = 0;
                                    $taxable_amount = 0;
                                    @endphp
                                    @foreach($vendor->products as $product)
                                    @if($product->order_id == $order->id)
                                    @php
                                    $taxable_amount += $product->taxable_amount;
                                    $sub_total += $product->quantity * $product->price;
                                    @endphp
                                    <tr>
                                        <th scope="row">{{$product->product_name}}
                                            <p>
                                                @if(isset($product->scheduled_date_time)) {{convertDateTimeInTimeZone($product->scheduled_date_time, $timezone, 'l, F d, Y, H:i A')}} @endif
                                            <p>
                                                @foreach($product->prescription as $pres)
                                                <br><a target="_blank" href="{{ ($pres) ? $pres->prescription['proxy_url'].'74/100'.$pres->prescription['image_path'] : ''}}">{{($product->prescription) ? 'Prescription' : ''}}</a>
                                                @endforeach
                                        </th>
                                        <td>
                                            <img src="{{$product->image_path['proxy_url'].'32/32'.$product->image_path['image_path']}}" alt="product-img" height="32">
                                        </td>
                                        <td>{{ $product->quantity }}</td>
                                        <td>{{$CurremcySymbel}} @money($product->price)</td>

                                        <td>{{$CurremcySymbel}} @money($product->quantity * $product->price)</td>
                                    </tr>
                                    @endif
                                    @endforeach
                                    <tr>
                                        <th scope="row" colspan="4" class="text-end">{{__('Delivery Fee')}} :</th>
                                        <td>{{$CurremcySymbel}} @money($vendor->delivery_fee)</td>
                                    </tr>
                                    <tr>
                                        <th scope="row" colspan="4" class="text-end">{{ __("Sub Total") }} :</th>
                                        <td>
                                            <div class="fw-bold">{{$CurremcySymbel}} @money($sub_total)</div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row" colspan="4" class="text-end">{{__('Total Discount')}} :</th>
                                        <td>{{$CurremcySymbel}} @money($vendor->discount_amount)</td>
                                    </tr>

                                    <tr>
                                        <th scope="row" colspan="4" class="text-end">{{ __("Estimated Tax") }} :</th>
                                        {{-- <td>{{$CurremcySymbel}} @money($taxable_amount)</td> --}}
                                        <td>{{$CurremcySymbel}} @money( $order->taxable_amount)</td>
                                    </tr>
                                    <tr>
                                        <th scope="row" colspan="4" class="text-end">{{ __("Reject Reason") }} :</th>
                                        <td style="width:200px;">{{$vendor->reject_reason}} <br> {{$vendor->comment}}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row" colspan="4" class="text-end">{{ __("Total") }} :</th>
                                        <td>
                                            {{-- <div class="fw-bold">{{$CurremcySymbel}} @money($vendor->payable_amount+$taxable_amount)</div> --}}
                                            <div class="fw-bold">{{$CurremcySymbel}} @money($order->payable_amount)</div>
                                        </td>
                                    </tr>
                                </tbody>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="row">
            @if($order->address)
            <div class="col-lg-6 mb-3">
                <div class="card mb-0 h-100">
                    <div class="card-body">
                        <h4 class="header-title mb-3">{{ __("Shipping Information") }}</h4>
                        <h5 class="font-family-primary fw-semibold">{{$order->user->name}}</h5>
                        <p class="mb-2"><span class="fw-semibold me-2">{{ __("Email") }}:</span> {{ $order->user->email ? $order->user->email : ''}}</p>
                        <p class="mb-2"><span class="fw-semibold me-2">{{ __("Mobile") }}:</span> {{ $order->address->house_number ? $order->address->house_number."," : ''}} {{$order->user->phone_number}}</p>
                        <p class="mb-2"><span class="fw-semibold me-2">{{ __("Address") }}:</span> {{ $order->address ? $order->address->address : ''}}</p>
                        @if(isset($order->address) && !empty($order->address->street))
                        <p class="mb-2"><span class="fw-semibold me-2">{{__('Street')}}:</span> {{ $order->address ? $order->address->street : ''}}</p>
                        @endif
                        <p class="mb-2"><span class="fw-semibold me-2">{{__('City')}}:</span> {{ $order->address ? $order->address->city : ''}}</p>
                        <p class="mb-2"><span class="fw-semibold me-2">{{ __("State") }}:</span> {{ $order->address ? $order->address->state : ''}}</p>
                        <p class="mb-0"><span class="fw-semibold me-2">{{ __("Zip Code") }}:</span>  {{ $order->address ? $order->address->pincode : ''}}</p>

                    </div>
                </div>
            </div>
            @endif

            <div class="col-lg-6 mb-3">
                <div class="card mb-0 h-100">
                    <div class="card-body">
                        <h4 class="header-title mb-3">{{ __('Payment Information') }}</h4>
                        <p class="mb-2"><span class="fw-semibold me-2">{{ __('Payment By') }} :</span> {{ $order->paymentOption  ? $order->paymentOption->title : ''}}</p>
                        @if($order->payment)
                        <p class="mb-2"><span class="fw-semibold me-2">{{ __('Transaction Id') }} :</span> {{ $order->payment  ? $order->payment->transaction_id : ''}}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>



    </div>
</div>
<div id="delivery_info_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h4 class="modal-title">{{ __("Delivery Info") }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body" id="AddCardBox">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info waves-effect waves-light submitAddForm">{{ __("Submit") }}</button>
            </div>
        </div>
    </div>
</div>
@endsection


@section('script')
<style>

/* Enhanced Timeline Styles */
.enhanced-timeline {
    position: relative;
    padding-left: 20px;
}

.enhanced-timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e9ecef;
}

.timeline-step {
    position: relative;
    padding: 20px 0;
    margin-left: 30px;
}

.timeline-step::before {
    content: '';
    position: absolute;
    left: -23px;
    top: 24px;
    width: 14px;
    height: 14px;
    border-radius: 50%;
    background: #fff;
    border: 2px solid #e9ecef;
    z-index: 2;
}

.timeline-step.completed::before {
    background: #28a745;
    border-color: #28a745;
}

.timeline-step.current::before {
    background: #007bff;
    border-color: #007bff;
    box-shadow: 0 0 0 4px rgba(0, 123, 255, 0.1);
}

.timeline-step.cancelled::before {
    background: #dc3545;
    border-color: #dc3545;
}

.timeline-icon {
    position: absolute;
    left: -30px;
    top: 20px;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #fff;
    border-radius: 50%;
    z-index: 3;
}

.timeline-icon-img {
    width: 20px;
    height: 20px;
    object-fit: contain;
}

.timeline-step.completed .timeline-icon {
    color: #28a745;
}

.timeline-step.current .timeline-icon {
    color: #007bff;
}

.timeline-step.cancelled .timeline-icon {
    color: #dc3545;
}

.timeline-content {
    margin-left: 10px;
}

.timeline-label {
    font-weight: 600;
    font-size: 14px;
    margin-bottom: 4px;
}

.timeline-label.green {
    color: #28a745;
}

.timeline-label.blue {
    color: #007bff;
}

.timeline-label.red {
    color: #dc3545;
}

.timeline-label.cancelled {
    color: #dc3545;
}

.timeline-date {
    font-size: 12px;
    color: #6c757d;
    margin-bottom: 2px;
}

.timeline-description {
    font-size: 11px;
    color: #6c757d;
    font-style: italic;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .enhanced-timeline {
        padding-left: 15px;
    }
    
    .timeline-step {
        margin-left: 20px;
    }
    
    .timeline-icon {
        left: -25px;
        width: 25px;
        height: 25px;
    }
    
    .timeline-icon-img {
        width: 16px;
        height: 16px;
    }
}

/* Enhanced Tracking Cards */
.enhanced-tracking-card {
    border: 1px solid #e9ecef;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.enhanced-tracking-card:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    transform: translateY(-2px);
}

.enhanced-tracking-card .card-title {
    font-size: 14px;
    font-weight: 600;
}

.enhanced-tracking-card .card-body {
    padding: 1rem;
}

/* Status indicators */
.status-indicator {
    display: inline-block;
    width: 8px;
    height: 8px;
    border-radius: 50%;
    margin-right: 8px;
}

.status-indicator.active {
    background-color: #28a745;
}

.status-indicator.pending {
    background-color: #ffc107;
}

.status-indicator.inactive {
    background-color: #6c757d;
}

/* Icon styling */
.timeline-icon i {
    font-size: 14px;
}

/* Card hover effects */
.card:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

/* Loading animation */
.loading-spinner {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>

<script>


// Function to refresh enhanced tracking data
function refreshTrackingData() {
    const refreshBtn = event.target.closest('button');
    const originalText = refreshBtn.innerHTML;
    refreshBtn.innerHTML = '<i class="mdi mdi-loading mdi-spin"></i> Refreshing...';
    refreshBtn.disabled = true;
    
    // Reload the page to get updated tracking data
    setTimeout(() => {
        location.reload();
    }, 1000);
}
</script>
@endsection
