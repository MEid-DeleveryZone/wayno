@extends('layouts.store', ['title' => 'Order Tracking'])

@section('css')
<style>
    .order-details-label { font-weight: 600; color: #2563eb; }
    .order-status-cancelled { background: #e74c3c; color: #fff; border-radius: 6px; padding: 2px 12px; font-size: 16px; font-weight: 600; display: inline-block; }
    .order-status-delivered { background: #22c55e; color: #fff; border-radius: 6px; padding: 2px 12px; font-size: 16px; font-weight: 600; display: inline-block; }
    .order-status-processing { background: #f59e0b; color: #fff; border-radius: 6px; padding: 2px 12px; font-size: 16px; font-weight: 600; display: inline-block; }
    .timeline-box, .order-details-col {
        background: #f5f7fa;
        border-radius: 24px;
        box-shadow: 0 4px 24px rgba(0,0,0,0.08);
        padding: 32px 24px;
        min-height: 100%;
    }
    .order-details-col, .timeline-col {
        width: 48% !important;
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
    }
    .order-details-col h2, .timeline-box h2 {
        color: #2563eb;
        font-weight: 700;
        font-size: 2rem;
        margin-bottom: 24px;
        margin-top: 0;
    }
    .order-tracking-row { gap: 32px !important; justify-content: center; align-items: flex-start; margin-top: 40px; margin-bottom: 40px; }
    .error-container { 
        text-align: center; 
        padding: 60px 20px; 
        background: #fff; 
        border-radius: 24px; 
        box-shadow: 0 4px 24px rgba(0,0,0,0.08); 
        margin: 40px auto; 
        max-width: 600px; 
    }
    .error-icon { 
        font-size: 64px; 
        color: #e74c3c; 
        margin-bottom: 20px; 
    }
    .error-title { 
        font-size: 24px; 
        font-weight: 700; 
        color: #e74c3c; 
        margin-bottom: 16px; 
    }
    .error-message { 
        font-size: 16px; 
        color: #666; 
        margin-bottom: 24px; 
        line-height: 1.5; 
    }
    .back-button { 
        background: #2563eb; 
        color: #fff; 
        padding: 12px 24px; 
        border-radius: 8px; 
        text-decoration: none; 
        display: inline-block; 
        transition: background 0.3s ease; 
    }
    .back-button:hover { 
        background: #1d4ed8; 
        color: #fff; 
        text-decoration: none; 
    }
    .timeline-step { display: flex; align-items: center; margin-bottom: 8px; position: relative; }
    .timeline-step:last-child { margin-bottom: 0; }
    .timeline-icon { 
        width: 48px; 
        height: 48px; 
        margin-right: 12px; 
        border-radius: 8px; 
        display: flex; 
        align-items: center; 
        justify-content: center;
        position: relative;
        z-index: 2;
        border: 2px solid #e5e7eb;
        transition: all 0.3s ease;
    }
    .timeline-icon img {
        max-width: 24px;
        max-height: 24px;
        object-fit: contain;
    }
    .timeline-step.completed .timeline-icon {
        border-color: #22c55e;
    }
    .timeline-step.current .timeline-icon {
        border-color: #2563eb;
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
    }
    .timeline-step.cancelled .timeline-icon {
        border-color: #e74c3c;
    }
    .timeline-label { font-weight: 600; font-size: 14px; }
    .timeline-label.green { color: #22c55e; }
    .timeline-label.blue { color: #2563eb; }
    .timeline-label.red { color: #e74c3c; }
    .timeline-label.cancelled { color: #e74c3c; }
    .timeline-date { font-size: 11px; color: #888; }
    .timeline-description { font-size: 11px; color: #666; margin-top: 2px; }
    .timeline-content {
        width: 80%;
        margin-left: auto;
        margin-right: auto;
        display: flex;
        flex-direction: column;
        align-items: baseline;
        border-radius: 8px;
        padding: 6px 8px;
        margin-bottom: 2px;
        background: transparent;
        transition: background 0.3s;
    }
    .timeline-step.completed .timeline-content {
        background: #d1fadf;
    }
    .timeline-step.current .timeline-content {
        background: #fff;
        box-shadow: 0 2px 8px rgba(37,99,235,0.08);
    }
    .order-status-completed { background: #22c55e; color: #fff; border-radius: 6px; padding: 2px 12px; font-size: 16px; font-weight: 600; display: inline-block; }
    @media (max-width: 991px) {
        .order-tracking-row { flex-direction: column; gap: 24px !important; margin-top: 20px; margin-bottom: 20px; }
        .order-details-col, .timeline-col { width: 100% !important; }
        .error-container { margin: 20px auto; padding: 40px 20px; }
    }
</style>
@endsection

@section('content')
<header>
    <div class="mobile-fix-option"></div>
    @include('layouts.store/left-sidebar-default-template')
</header>

<section class="section-b-space">
    <div class="container py-5">
        @if(isset($error))
            <div class="error-container">
                <div class="error-icon">⚠️</div>
                <h1 class="error-title">{{ $error['title'] ?? 'Order Not Found' }}</h1>
                <p class="error-message">{{ $error['message'] ?? 'The order you are looking for could not be found. Please check your order number and try again.' }}</p>
                <a href="{{ url('/') }}" class="back-button">Back to Home</a>
            </div>
        @else
            <div class="d-flex order-tracking-row">
                <!-- Order Details -->
                <div class="order-details-col">
                    <h2 class="mb-4" style="color: #2563eb; font-weight: 700;">Order Details</h2>
                    <div class="mb-2"><span class="order-details-label">Order Number:</span> {{ $orderData['order_number'] }}</div>
                    <div class="mb-2"><span class="order-details-label">Order Date:</span> {{ $orderData['created_at'] }}</div>
                    <div class="mb-2"><span class="order-details-label">Client:</span> {{ $orderData['user']['name'] }}</div>
                    <div class="mb-2">
                        <span class="order-details-label">Current Status:</span> 
                        @if(strtolower($orderData['current_status']) == 'cancelled')
                            <span class="order-status-cancelled">{{ $orderData['current_status'] }}</span>
                        @elseif(strtolower($orderData['current_status']) == 'delivered')
                            <span class="order-status-delivered">{{ $orderData['current_status'] }}</span>
                        @elseif(strtolower($orderData['current_status']) == 'completed')
                            <span class="order-status-completed">{{ $orderData['current_status'] }}</span>
                        @else
                            <span class="order-status-processing">{{ $orderData['current_status'] }}</span>
                        @endif
                    </div>
                    <div class="mb-2">
                        <span class="order-details-label">Pickup Address:</span><br>
                        {{ $orderData['pickup_address']['name'] }}, {{ $orderData['pickup_address']['phone'] }}<br>
                        {{ $orderData['pickup_address']['address'] }}
                    </div>
                    <div class="mb-2">
                        <span class="order-details-label">Drop-off Address:</span><br>
                        {{ $orderData['dropoff_address']['name'] }}, {{ $orderData['dropoff_address']['phone'] }}<br>
                        {{ $orderData['dropoff_address']['address'] }}
                    </div>
                    @if(!empty($orderData['rider']['name']) || !empty($orderData['rider']['phone_number']))
                    <div class="mb-2">
                        <span class="order-details-label">Rider Details:</span><br>
                        @if(!empty($orderData['rider']['name']))
                            Name: {{ $orderData['rider']['name'] }}<br>
                        @endif
                        @if(!empty($orderData['rider']['phone_number']))
                            Phone: {{ $orderData['rider']['phone_number'] }}
                        @endif
                    </div>
                    @endif
                </div>
                <!-- Timeline -->
                <div class="timeline-col">
                    <div class="timeline-box">
                        <h2 class="mb-4" style="color: #2563eb; font-weight: 700;">Timeline</h2>
                        @foreach($timelineData as $step)
                        <div class="timeline-step {{ $step['is_current'] ? 'current' : ($step['is_completed'] ? 'completed' : '') }} {{ $step['class'] == 'cancelled' ? 'cancelled' : '' }}">
                            <div class="timeline-icon">
                                @if($step['icon'])
                                    <img src="{{ asset($step['icon']) }}" alt="{{ $step['status'] }}">
                                @else
                                    <i class="fa fa-circle" style="color: #ccc;"></i>
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
        @endif
    </div>
</section>
@endsection
