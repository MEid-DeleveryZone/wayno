@extends('layouts.store', ['title' => 'Loyalty'])
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
<style type="text/css">
</style>

<section class="section-b-space">
    <div class="container">
        <div class="row">
            <div class="col-lg-3">
                <div class="account-sidebar"><a class="popup-btn">{{ __('My Account') }}</a></div>
                @include('layouts.store/profile-sidebar')
            </div>
            <div class="col-lg-9">
                <div class="dashboard-right">
                    <div class="dashboard">
                        <div class="page-title">
                            <h2>{{ __('My Loyalty') }}</h2>
                        </div>
                        <div class="card-box">
                            @if($current_loyalty)
                                <div class="row">
                                    <div class="offset-md-3 col-md-6">
                                        <div class="card-box">
                                            <div class="row align-items-center">
                                                <div class="col-4">
                                                    <div class="medal-img">
                                                        <img src="{{ $current_loyalty->image['proxy_url'] .'120/120'. $current_loyalty->image['image_path'] }}" alt="">
                                                    </div>
                                                </div>
                                                <div class="col-8">
                                                    <h3 class="mt-0"><b>You are on</b></h3>
                                                    <div class="loalty-title">
                                                        {{ $current_loyalty->name }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 text-center">
                                                <div class="card-box earn-points p-2">
                                                    <div class="points-title">
                                                        {{ $loyalty_points_earned }}
                                                    </div>
                                                    <div class="ponits-heading">
                                                        Total Earned Points
                                                    </div>                                                    
                                                </div>
                                            </div>
                                            <div class="col-md-6 text-center">
                                                <div class="card-box spend-points p-2">
                                                    <div class="points-title">
                                                        {{ $loyalty_points_used }}
                                                    </div>
                                                    <div class="ponits-heading">
                                                        Spendable Points
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="text-center">Not Available</div>
                            @endif

                           <div class="row">
                                <div class="offset-lg-1 col-lg-10">
                                    @if($upcoming_loyalty->isNotEmpty())
                                    <div class="row">
                                        <div class="col-12 mt-3">
                                            <h2>Upcoming</h2>
                                        </div>
                                        @foreach($upcoming_loyalty as $loyalty)
                                        <div class="col-md-6 mt-3 text-center">
                                            <div class="card-box">
                                                <div class="point-img-box">
                                                    <img src="{{ $loyalty->image['proxy_url'] .'200/200'. $loyalty->image['image_path'] }}" alt="">
                                                </div>
                                                <h3 class="mb-0 mt-3"><b>{{$loyalty->points_to_reach}} points to {{$loyalty->name}}</b></h3>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                    @endif
                               </div>
                           </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
@section('script')

@endsection