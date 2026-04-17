@extends('layouts.store', ['title' => 'Address'])

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
            <div class="offset-lg-3 col-lg-6">
                <h3>Create Address</h3>
                <div class="outer-box">
                    @if(isset($address->id) && $address->id > 0)
                    <form action="{{route('address.update', $address->id)}}" class="theme-form" method="post">@csrf
                        @else
                        <form action="{{route('address.store')}}" class="theme-form" method="post">@csrf
                            @endif
                            <div class="form-row mb-0">
                                <div class="col-md-6 mb-2">
                                    <label for="address">Address</label>
                                    <input type="text" class="form-control" value="{{old('address', $address->address)}}" id="address" placeholder="Address" required="" name="address">
                                    @if($errors->first('address'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('address') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label for="street">Street</label>
                                    <input type="text" class="form-control" id="street" placeholder="Street" required="" name="street" value="{{old('street', $address->street)}}">
                                    @if($errors->first('street'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('street') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-row mb-0">
                                <div class="col-md-6 mb-2">
                                    <label for="city">City</label>
                                    <input type="city" class="form-control" id="email" placeholder="City" required="" name="city" value="{{old('city', $address->city)}}">
                                    @if($errors->first('city'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('city') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label for="state">State</label>
                                    <input type="text" class="form-control" id="state" placeholder="State" required="" name="state" value="{{old('state', $address->state)}}">
                                    @if($errors->first('state'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('state') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label for="country">Country</label>
                                    <select name="country" id="country" class="form-control">
                                        @if(isset($address->id) && $address->id > 0)
                                        @foreach($countries as $co)
                                        @if($address->country_id == $co->id)
                                        <option value="{{$co->id}}" selected>{{$co->name}}</option>
                                        @else
                                        <option value="{{$co->id}}">{{$co->name}}</option>
                                        @endif
                                        @endforeach
                                        @else
                                        @foreach($countries as $co)
                                        <option value="{{$co->id}}">{{$co->name}}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label for="pincode">Pincode</label>
                                    <input type="text" class="form-control" id="pincode" placeholder="Pincode" required="" name="pincode" value="{{old('pincode', $address->pincode)}}">
                                    @if($errors->first('pincode'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('pincode') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label for="type">Address Type</label>
                                    <select name="type" id="type" class="form-control">
                                        @if(isset($address->id) && $address->id > 0)
                                        @if($address->type == 1)
                                        <option value="1" selected>Home</option>
                                        <option value="2">Office</option>
                                        @else
                                        <option value="1">Home</option>
                                        <option value="2" selected>Office</option>
                                        @endif
                                        @else
                                        <option value="1" selected>Home</option>
                                        <option value="2">Office</option>
                                        @endif
                                    </select>
                                </div>
                                <div class="col-md-6 mb-2">
                                </div>
                                @if(isset($address->id) && $address->id > 0)
                                <div class="col-md-12 mb-2"><button type="submit" class="btn btn-solid mt-3 w-100">Update Address</button></div>
                                @else
                                <div class="col-md-12 mb-2"><button type="submit" class="btn btn-solid mt-3 w-100">Save Address</button></div>
                                @endif
                            </div>
                        </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('script')
<script src="{{asset('assets/js/intlTelInput.js')}}"></script>
@endsection