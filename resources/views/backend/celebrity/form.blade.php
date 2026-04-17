<input type="hidden" name="lc_id" id="lc_id" url="{{route('celebrity.update', $lc->id)}}">
<div class="row">
    <div class="col-md-6" id="imageInput">
        <label>{{ __('Upload image') }}</label>
        <input type="file" accept="image/*" data-plugins="dropify" name="image" class="dropify" data-default-file="{{$lc->avatar['proxy_url'].'600/400'.$lc->avatar['image_path']}}" />        
        <label class="logo-size d-block text-right mt-1">{{ __('Image Size') }} 150x150</label>
        <span class="invalid-feedback" role="alert">
            <strong></strong>
        </span>
    </div>
</div>
<div class="row mb-2">
    <div class="col-md-6">
        <div class="form-group" id="nameInput">
            {!! Form::label('title', __('Name'),['class' => 'control-label']) !!}
            {!! Form::text('name', $lc->name, ['class' => 'form-control', 'placeholder' => 'Name']) !!}
            <span class="invalid-feedback" role="alert">
                <strong></strong>
            </span>
        </div>
    </div>
    <div class="col-md-6" id="slugInput">
        <div class="form-group">
            {!! Form::label('title', __('Slug'),['class' => 'control-label']) !!} 
            {!! Form::text('slug', $lc->slug, ['class'=>'form-control', 'required' => 'required', 'onkeypress' => "return alphaNumeric(event)", 'id' => 'slug']) !!}
            <span class="invalid-feedback" role="alert">
                <strong></strong>
            </span>
        </div>
    </div>
    <div class="col-md-12" id="country_list">
        <div class="form-group">
            {!! Form::label('title', __('Country'),['class' => 'control-label']) !!}
            <select class="form-control" id="countries" name="countries" data-placeholder="Choose ...">
                @foreach($countries as $ck => $cval)
                @if($cval->id == $lc->country_id)
                <option value="{{$cval->id}}" selected> {{$cval->name}}</option>
                @else
                <option value="{{$cval->id}}"> {{$cval->name}}</option>
                @endif
                @endforeach
            </select>
        </div>
    </div>

    <!-- <div class="col-md-6">
        <div class="form-group" id="emailInput">
            {!! Form::label('title', 'Email *',['class' => 'control-label']) !!}
            {!! Form::text('email', $lc->email, ['class' => 'form-control']) !!}
            <span class="invalid-feedback" role="alert">
                <strong></strong>
            </span>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group" id="phone_numberInput">
            {!! Form::label('title', 'Phone number',['class' => 'control-label']) !!}
            {!! Form::text('phone_number', $lc->phone_number, ['class' => 'form-control', 'placeholder' => 'Phone Number' , 'onkeypress' => 'return isNumberKey(event);']) !!}
            <span class="invalid-feedback" role="alert">
                <strong></strong>
            </span>
        </div>
    </div> -->
    <div class="col-md-12">
        <div class="form-group" id="addressInput">
            {!! Form::label('title', __('Description'),['class' => 'control-label']) !!}
            <!-- {!! Form::text('desctiption', $lc->desctiption, ['class' => 'form-control']) !!} -->
            <textarea class='form-control' rows="3" name="description">{{$lc->description}}</textarea>
            <span class="invalid-feedback" role="alert">
                <strong></strong>
            </span>
        </div>
    </div>

    <!-- <div class="col-md-6" id="product_list">
        <div class="form-group">
            {!! Form::label('title', 'Brands',['class' => 'control-label']) !!}
            <select class="form-control select2-multiple" id="brands" name="brands[]" data-toggle="select2" multiple="multiple" data-placeholder="Choose ...">
                @foreach($brands as $nm)
                @if(in_array($nm->id, $pros))
                <option value="{{$nm->id}}" selected>{{$nm->title}}</option>
                @else
                <option value="{{$nm->id}}">{{$nm->title}}</option>
                @endif
                @endforeach
            </select>
        </div>
    </div> -->

</div>