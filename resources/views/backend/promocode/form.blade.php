<div class="row">
    <div class="col-md-12">
        @if(isset($client_languages) && count($client_languages) > 0)
        <div class="row mb-3">
            <div class="col-md-12">
                <div class="form-group">
                    {!! Form::label('language_id', __('Select Language'),['class' => 'control-label']) !!}
                    <select class="form-control" id="promo_language_selector" name="language_id">
                       @foreach($client_languages as $client_language)
                        <option value="{{$client_language->langId}}" @if($client_language->is_primary == 1) selected @endif>{{$client_language->langName}}</option>
                       @endforeach
                    </select>
                </div>
            </div>
        </div>
        @endif
        <div class="row">
            <div class="col-md-6" id="imageInput">
                <label>{{ __("Upload PromoCode image") }}</label>
                @php
                    $defaultImage = '';
                    if(isset($promo->id) && $promo->primary) {
                        // Get image from primary translation (accessor will return array)
                        $imageData = $promo->primary->image;
                        if(is_array($imageData) && isset($imageData['proxy_url']) && isset($imageData['image_path'])) {
                            $defaultImage = $imageData['proxy_url'].'600/400'.$imageData['image_path'];
                        }
                    }
                @endphp
                @if(isset($promo->id) && $defaultImage)
                    <input type="file" accept="image/*" data-plugins="dropify" name="image" class="dropify" data-default-file="{{$defaultImage}}" id="promo_image_field" />
                @else
                    <input data-default-file="" type="file" data-plugins="dropify" name="image" accept="image/*" class="dropify" id="promo_image_field"/>
                @endif
                <label class="logo-size d-block text-right mt-1">{{ __("Image Size") }} 100x100</label>
                <span class="invalid-feedback" role="alert">
                    <strong></strong>
                </span>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group" id="nameInput">
                    @php
                        $titleValue = (isset($promo->id) && $promo->primary) ? $promo->primary->title : '';
                    @endphp
                    {!! Form::label('title', __('Title'),['class' => 'control-label']) !!}
                    {!! Form::text('title', $titleValue, ['class' => 'form-control', 'id' => 'promo_title_field', 'placeholder'=>'Enter Title']) !!}
                    <span class="invalid-feedback" role="alert">
                        <strong></strong>
                    </span>
                </div>
                <?php $action = '';
                if(isset($promo->id) && $promo->id > 0){
                    $action = 'Edit';
                }
                ?>
            </div>
            <div class="col-md-12">
                <div class="form-group" id="short_descInput">
                    @php
                        $shortDescValue = (isset($promo->id) && $promo->primary) ? $promo->primary->short_desc : '';
                    @endphp
                    {!! Form::label('short_desc', __('Short Description'),['class' => 'control-label']) !!}
                    {!! Form::textarea('short_desc', $shortDescValue, ['class' => 'form-control', 'id' => 'promo_short_desc_field', 'placeholder'=>'Enter Short Description', 'rows' => 3]) !!}
                    <span class="invalid-feedback" role="alert">
                        <strong></strong>
                    </span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group" id="nameInput">
                    {!! Form::label('title', __('Promocode'),['class' => 'control-label']) !!}
                    {!! Form::text('name', $promo->name, ['class' => 'form-control', 'placeholder'=>'Enter promocode']) !!}
                    <span class="invalid-feedback" role="alert">
                        <strong></strong>
                    </span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::label('title', __('Promo Type'),['class' => 'control-label']) !!}
                    <select class="selectize-select form-control promoTypeField" name="promo_type_id">
                        @foreach($promoTypes as $key => $types)
                            <option value="{{$types->id}}" @if(isset($promo->id) && $promo->id > 0 && $types->id == $promo->promo_type_id) selected @endif >{{$types->title}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="col-md-6">
                <?php 
                $pricevalue = (isset($promo->id) && $promo->id > 0 && $promo->promo_type_id == 1) ? (int)$promo->amount : $promo->amount; ?>
                <div class="form-group" id="amountInput">
                    {!! Form::label('title', __('Amount'),['class' => 'control-label']) !!}
                    {!! Form::number('amount', $pricevalue, ['class' => 'form-control amountInputField', 'id' => 'amountInputField', 'placeholder'=>'Enter total amount', 'max' => "10000", 'min' => "1", "onKeyPress" => "return check(event,value)", "onInput" => "checkLength()"]) !!}
                    <span class="invalid-feedback" role="alert">
                        <strong></strong>
                    </span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="">
                    <div class="form-group" id="expiry_dateInput">
                        @php
                        $minDate = Date('Y-m-d');
                        @endphp
                        {!! Form::label('expiry_date', __('Expiry Date'),['class' => 'control-label']) !!}
                        {!! Form::text('expiry_date', $promo->expiry_date, ['class' => 'form-control downside datetime-datepicker', 'id' => 'start-datepicker', 'min' => $minDate]) !!}
                        <span class="invalid-feedback" role="alert">
                            <input type="hidden" name="promo_id" value="{{isset($promo->id) ? $promo->id : ''}}">
                            <strong></strong>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4  @if($client_preference_detail->business_type == 'taxi') d-none @endif">
                <div class="form-group">
                    {!! Form::label('title', __('Allow Free Delivery'),['class' => 'control-label']) !!}
                    <div>
                        <input type="checkbox" data-plugin="switchery" name="allow_free_delivery" class="form-control switch1{{$action}}" data-color="#43bee1" @if(isset($promo->id) && $promo->id > 0 && $promo->allow_free_delivery == 1) checked @endif>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    {!! Form::label('title', __('First Order Only'),['class' => 'control-label']) !!}
                    <div>
                        <input type="checkbox" data-plugin="switchery" name="first_order_only" class="form-control switch2{{$action}}" data-color="#43bee1" @if(isset($promo->id) && $promo->id > 0 && $promo->first_order_only == 1) checked @endif>
                    </div>
                </div>
            </div>
            <input type="hidden" id="promocode_id" url="{{ (isset($promo->id) && $promo->id > 0) ? route('promocode.update', $promo->id) : route('promocode.store') }}" data-promo-id="{{ isset($promo->id) ? $promo->id : '' }}">
            <div class="col-md-4">
                <div class="form-group">
                    {!! Form::label('title', __('Paid By'),['class' => 'control-label']) !!}
                    <div>
                        @if(Auth::user()->is_superadmin == 1)
                        <div class="radio radio-info form-check-inline">
                            <input type="radio" id="inlineRadio1" checked="" value="1" name="radioInline" @if(isset($promo->id) && $promo->id > 0 && $promo->paid_by_vendor_admin == 1) checked @endif>
                            <label for="inlineRadio1"> {{ __("Admin") }}</label>
                        </div>
                        @endif
                        <div class="radio form-check-inline">
                            <input type="radio" id="inlineRadio2" value="0" name="radioInline" @if(isset($promo->id) && $promo->id > 0 && $promo->paid_by_vendor_admin == 0) checked @endif >
                            <label for="inlineRadio2"> {{ __("Vendor") }}</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group" id="minimum_spendInput">
                    {!! Form::label('minimum_spend', __('Minimum Amount'),['class' => 'control-label']) !!}
                    {!! Form::text('minimum_spend', $promo->minimum_spend, ['class' => 'form-control']) !!}
                    <span class="invalid-feedback" role="alert">
                        <strong></strong>
                    </span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group" id="maximum_spendInput">
                    {!! Form::label('maximum_spend', __('Maximum Amount'),['class' => 'control-label']) !!}
                    {!! Form::text('maximum_spend', $promo->maximum_spend, ['class' => 'form-control']) !!}
                    <span class="invalid-feedback" role="alert">
                        <strong></strong>
                    </span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group" id="limit_per_userInput">
                    {!! Form::label('limit_per_user', __('Limit Per User'),['class' => 'control-label']) !!}
                    {!! Form::text('limit_per_user', $promo->limit_per_user, ['class' => 'form-control']) !!}
                    <span class="invalid-feedback" role="alert">
                        <strong></strong>
                    </span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group" id="limit_totalInput">
                    {!! Form::label('limit_total', __('Total Limit'),['class' => 'control-label']) !!}
                    {!! Form::text('limit_total', $promo->limit_total, ['class' => 'form-control']) !!}
                    <span class="invalid-feedback" role="alert">
                        <strong></strong>
                    </span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::label('title', __('Apply Restriction On'),['class' => 'control-label']) !!}
                    <select class="selectize-select form-control inlineRadioOptions" name="restriction_on" for="{{(isset($promo->id) && $promo->id > 0) ? 'edit' : 'add'}}">
                        <option value='0' @if($promo->restriction_on == 0) selected @endif>Products</option>
                        <option value='1' @if($promo->restriction_on == 1) selected @endif>Vendors</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::label('title', __('Restriction Type'),['class' => 'control-label']) !!}
                    <select class="selectize-select form-control" name="restriction_type">
                        <option value='include' @if($promo->restriction_type == 1) selected @endif>Include</option>
                        <option value='exclude' @if($promo->restriction_type == 1) selected @endif>Exclude</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6" style="{{($promo->restriction_on == 0) ? '' : 'display: none;'}}" id="productsList">
                <div class="form-group">
                    {!! Form::label('title', __('Products'),['class' => 'control-label']) !!}
                    <select class="form-control select2-multiple" id="IncludeProduct" name="productList[]" data-toggle="select2" multiple="multiple" data-placeholder="Choose ...">
                        @foreach($products as $sk)
                        <option value="{{$sk->id}}" @if($promo->restriction_on == 0 && in_array($sk->id, $dataIds)) selected @endif>{{$sk->sku}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-6" style="{{($promo->restriction_on == 1) ? '' : 'display: none;'}}" id="vendorsList">
                <div class="form-group">
                    {!! Form::label('title', __('Vendors'),['class' => 'control-label']) !!}
                    <select class="form-control select2-multiple" id="IncludeVendor" name="vendorList[]" data-toggle="select2" multiple="multiple" data-placeholder="Choose ...">
                        @foreach($vendors as $nm)
                        <option value="{{$nm->id}}" @if($promo->restriction_on == 1 && in_array($nm->id, $dataIds)) selected @endif>{{$nm->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>