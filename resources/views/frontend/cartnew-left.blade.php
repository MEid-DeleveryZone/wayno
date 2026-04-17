@php
    if($action == 'takeaway'){
        $label = getNomenclatureName('Takeaway', true);
    }elseif($action == 'dine_in'){
        $label = 'Dine-In';
    }else{
        $label = 'Delivery';
    }
@endphp
<div class="row align-items-center mb-3">
    <div class="col-lg-6">
        <h4 class="page-title m-0">{{ __($label.' Address') }} </h4>
    </div>
    <div class="col-lg-6 mt-2 mt-lg-0 text-center" id="add_new_address_btn">
        <a class="add-address w-100 mx-auto" href="javascript:void(0)">
            <i class="fa fa-plus mr-1" aria-hidden="true"></i>{{__('Add New Address')}}
        </a>
    </div>
</div>
@if($action != 'delivery')
    @if(isset($vendor_details['vendor_address']))
        <div>
            <input type="hidden" id="latitude" value="{{ $vendor_details['vendor_address']->latitude }}">
            <input type="hidden" id="longitude" value="{{ $vendor_details['vendor_address']->longitude }}">
        </div>
        <label>{{$vendor_details['vendor_address']->address}}</label>
        <div class="row mt-3 mb-3" id="address_template_main_div">
            <div class="col-12">
                <div id="vendor-address-map-container" style="height: 200px;">
                    <div id="vendor-address-map" style="height:100%"></div>
                </div>
            </div>
        </div>
    @endif
    @if(isset($vendor_details['vendor_tables']))
        <div class="vendor_tables">
            <h4>Book a table</h4>
            @if($vendor_details['vendor_tables']->isNotEmpty())
                <select name="vendor_table" id="vendor_table" data-id="{{ $vendor_details['vendor_address']->id }}" class="form-control">
                    @foreach($vendor_details['vendor_tables'] as $k => $table)
                        <option value="{{$table->id}}" {{ ($cart_dinein_table_id == $table->id) ? 'selected' : '' }}>Category : {{ $table->category->title }} | Table : {{ $table->table_number }} | Seat Capacity : {{ $table->seating_number }}</option>
                    @endforeach
                </select>
            @else
                <div class="table-not-found">
                    <p>{{__('Table not available.')}}</p>
                </div>
            @endif
        </div>
    @endif
@else
    <div class="row mb-4" id="address_template_main_div">
        @forelse($addresses as $k => $address)
        <div class="col-md-12">
            <div class="delivery_box px-0">
                <label class="radio m-0">{{ ($address->house_number ?? false) ? $address->house_number."," : '' }} {{$address->address}}, {{$address->state}} {{$address->pincode}}
                    @if($address->is_primary)
                    <input type="radio" name="address_id" value="{{$address->id}}" checked="checked">
                    @else
                    <input type="radio" name="address_id" value="{{$address->id}}" {{$k == 0? 'checked="checked""' : '' }}>
                    @endif
                    <span class="checkround"></span>
                </label>
            </div>
        </div>
        @empty
        <div class="col-12 address-no-found">
            <p>{{__('Address not available.')}}</p>
        </div>
        @endforelse
        <!-- <div class="col-12 mt-4 text-center" id="add_new_address_btn">
            <a class="btn btn-solid w-100 mx-auto mb-4">
                <i class="fa fa-plus mr-1" aria-hidden="true"></i>{{__('Add New Address')}}
            </a>
        </div> -->
    </div>
    <div class="row">
        <div class="col-md-12" id="add_new_address_form" style="display:none;">
            <div class="theme-card w-100">
                <div class="form-row no-gutters">
                    <div class="col-12">
                        <label for="type">{{__('Address Type')}}</label>
                    </div>
                    <div class="col-md-3">
                        <div class="delivery_box pt-0 pl-0  pb-3">
                            <label class="radio m-0">{{__('Home')}}
                                <input type="radio" checked="checked" name="address_type" value="1">
                                <span class="checkround"></span>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="delivery_box pt-0 pl-0  pb-3">
                            <label class="radio m-0">{{__('Office')}}
                                <input type="radio" name="address_type" value="2">
                                <span class="checkround"></span>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="delivery_box pt-0 pl-0  pb-3">
                            <label class="radio m-0">{{__('Others')}}
                                <input type="radio" name="address_type" value="3">
                                <span class="checkround"></span>
                            </label>
                        </div>
                    </div>
                </div>
                @php
                $default_latitude = "";
                $default_longitude = "";
                $default_location_name = "";
                $default_country = "";
       //         if((isset($preferences->is_hyperlocal)) && ($preferences->is_hyperlocal == 1)){
     //               $default_latitude = $preferences->Default_latitude??"";
    //              $default_longitude = $preferences->Default_longitude??"";
     //               $default_location_name = $preferences->Default_location_name??"";
                    $default_country = $preferences->client_detail->country_id??"";
      //          }
                @endphp
                <input type="hidden" id="latitude" value="{{$default_latitude}}">
                <input type="hidden" id="longitude" value="{{$default_longitude}}">
                <div class="form-row">
                    <div class="col-md-12 mb-3">
                        <label for="address">{{__('Address')}}</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="address" placeholder="{{__('Address')}}" aria-label="Recipient's Address" aria-describedby="button-addon2" value="{{ $default_location_name }}">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary showMapHeader" type="button" id="button-addon2">
                                    <i class="fa fa-map-marker" aria-hidden="true"></i>
                                </button>
                            </div>
                        </div>
                        <span class="text-danger" id="address_error"></span>
                    </div>
                </div>
                <div class="form-row mb-3">
                    <div class="col-md-6 mb-3">
                        <label for="house_number">{{ __('House/Apartment/Flat number') }}</label>
                        <input type="text" class="form-control" id="house_number" placeholder="{{ __('House / Apartment/ Flat number') }}" name="house_number" >
                        <span class="text-danger" id="house_number_error"></span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="street">{{ __('Street') }}</label>
                        <input type="text" class="form-control" id="street" placeholder="{{ __('Street') }}" name="street" >
                        <span class="text-danger" id="street_error"></span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="city">{{__('City')}}</label>
                        <input type="text" class="form-control" id="city" placeholder="{{__('City')}}" value="">
                        <span class="text-danger" id="city_error"></span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="state">{{__('State')}}</label>
                        <input type="text" class="form-control" id="state" placeholder="{{__('State')}}" value="">
                        <span class="text-danger" id="state_error"></span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="country">{{__('Country')}}</label>
                        <select name="country" id="country" class="form-control">
                            @foreach($countries as $co)
                            <option value="{{$co->id}}" {{ ($co->id == $default_country)?"selected":"" }}>{{$co->name}}</option>
                            @endforeach
                        </select>
                        <span class="text-danger" id="country_error"></span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="pincode">{{__('Pincode')}}</label>
                        <input type="text" class="form-control" id="pincode" placeholder="{{__('Pincode')}}" value="">
                        <span class="text-danger" id="pincode_error"></span>
                    </div>
                    <div class="col-md-12 mt-3">
                        <button type="button" class="btn btn-solid" id="save_address">{{__('Save Address')}}</button>
                        <button type="button" class="btn btn-solid black-btn" id="cancel_save_address_btn">{{__('Cancel')}}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif