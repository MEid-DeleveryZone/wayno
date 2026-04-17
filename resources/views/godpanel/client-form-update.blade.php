@extends('layouts.god-vertical', ['title' => 'Client'])
@section('css')
<link href="{{asset('assets/libs/dropzone/dropzone.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/dropify/dropify.min.css')}}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
<style type="text/css">
    .sub-domain-input #sub_domain {
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
        border-right: 0;
    }
    
    .sub-domain-input #inputGroupPrepend2 {
        font-size: 18px;
        padding: 0 30px;
    }
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Create Client</h4>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <?php $disable = $style = ""; ?>
                    <?php $disable = 'disabled';
                    $style = "cursor:not-allowed;"; ?>
                    <form id="UpdateClient" method="post" action="{{route('client.update', $client->id)}}"
                        enctype="multipart/form-data" autocomplete="off">
                        @method('PUT')
                        @csrf
                        <div class=" row">
                           
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="sub_domain" class="control-label">SUB DOMAIN</label>
                                    <div class="input-group">
                                        <div class="sub-domain-input input-group-prepend w-100">
                                            <input type="text" class="form-control" name="sub_domain" id="sub_domain"
                                            value="{{ old('sub_domain', $client->sub_domain ?? '')}}"
                                            placeholder="Enter sub domain">
                                             <span class="input-group-text" id="inputGroupPrepend2"></span>
                                        </div>
                                      </div>
                                    
                                        @if($errors->has('sub_domain'))
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $errors->first('sub_domain') }}</strong>
                                        </span>
                                        @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="custom_domain" class="control-label">CUSTOM DOMAIN</label>
                                    <input type="text" class="form-control" name="custom_domain" id="custom_domain"
                                        value="{{ old('custom_domain', $client->custom_domain ?? '')}}"
                                        placeholder="Enter custom domain">
                                    @if($errors->has('custom_domain'))
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $errors->first('custom_domain') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="languages">Business Type</label>
                                    <select class="form-control" id="business_type" name="business_type">
                                        @foreach($business_types as $business)
                                            <option value="{{$business->slug}}" @if($client->business_type == $business->slug) selected="selected" @endif> {{$business->title}} </option>
                                        @endforeach

                                    

                                    </select>
                                </div>    
                            </div>
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-info waves-effect waves-light">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- default data after on board -->
    <div class="row">
        <div class="col-12">    
                   <div class="card">
                        <div class="card-body"><h3>Import Demo Content [Warning! All data will be lost.]</h3>
                        <form id="update_default_data" method="post" action="{{route('client.migrateDefaultData', $client->id)}}"
                            enctype="multipart/form-data" autocomplete="off">
                            @csrf
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="languages">Business Type </label>
                                    <select class="form-control" id="business_type" name="business_type">
                                        <option value=""></option>
                                        <option value="grub.sql">Grub - Food Delivery</option>
                                        <option value="homeric.sql">Homeric - Home Service </option>
                                        <option value="gokab.sql">GoKab - Cab Booking </option>
                                        <option value="ace.sql">Ace - Super App </option>
                                        <option value="punnet.sql">Punnet - Single Vendor Food Delivery </option>
                                        <option value="suel.sql">Suel - Single Vendor Ecommerce </option>
                                        <option value="voltaic.sql">Voltaic - Ecommerce </option>
                                        <option value="gusto.sql">Gusto - Grocery Delivery </option>
                                        <option value="elixir.sql">Elixir - Pharmacy Delivery </option>
                                        <option value="zest.sql">Zest - Pickup & Delivery </option>
                                    </select>
                                </div>    
                            </div>

                            <div class="col-md-3">
                                <div class="row">
                                    <button type="submit" class="btn btn-info waves-effect waves-light">{{__('Submit')}}</button>
                                </div>
                            </div>
                        </form>
                        </div>
                    </div>
            
        </div>
    </div>
    <!--end default --> 

      <!-- on single vendor panel  -->
      <div class="row">
        <div class="col-12">    
                   <div class="card">
                        <div class="card-body"><h3>{{__('Single Vendor')}}</h3>
                        <form id="update_default_data" method="post" action="{{route('client.update_single_vendor', $client->id)}}"
                            enctype="multipart/form-data" autocomplete="off">
                            @csrf
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="languages">Enable/Disable </label>
                                    <select class="form-control" id="single_vendor" name="single_vendor">
                                        <option value="0" @if($client->single_vendor == 0) selected="selected" @endif >Disable</option>
                                        <option value="1" @if($client->single_vendor == 1) selected="selected" @endif >Enable</option>
                                    </select>
                                </div>    
                            </div>

                            <div class="col-md-3">
                                <div class="row">
                                    <button type="submit" class="btn btn-info waves-effect waves-light">{{__('Submit')}}</button>
                                </div>
                            </div>
                        </form>
                        </div>
                    </div>
            
        </div>
    </div>
    <!--end default --> 

</div>
@endsection
@section('script')
<script src="{{asset('assets/libs/dropzone/dropzone.min.js')}}"></script>
<script src="{{asset('assets/libs/dropify/dropify.min.js')}}"></script>
<script src="{{asset('assets/js/pages/form-fileuploads.init.js')}}"></script>
@endsection