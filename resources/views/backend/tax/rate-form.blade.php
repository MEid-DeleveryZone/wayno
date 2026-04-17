<div class="row">
    <div class="col-md-12">
        <div class="row mb-2">
            <div class="col-md-6">
                <div class="form-group" id="identifierInput">
                    {!! Form::label('title', 'Identifier',['class' => 'control-label']) !!}
                    {!! Form::text('identifier', $taxRate->identifier, ['class' => 'form-control', 'placeholder' => 'Tax Identifier']) !!}
                    <span class="invalid-feedback" role="alert">
                        <strong></strong>
                    </span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group" id="categoryInput">
                    {!! Form::label('title', 'Tax Category',['class' => 'control-label']) !!}
                    <select class="form-control select2-multiple" id="category" name="category[]" data-toggle="select2" multiple="multiple" data-placeholder="Choose ...">
                        @foreach($taxCates as $cat)
                            <option value="{{$cat->id}}" @if(in_array($cat->id, $category)) selected @endif>{{ $cat->title }}</option>
                        @endforeach
                    </select>
                    <span class="invalid-feedback" role="alert">
                        <strong></strong>
                    </span>
                </div>
            </div>
          {{-- <div class="col-md-6">
                <div class="form-group" id="countryInput">
                    {!! Form::label('title', 'Country',['class' => 'control-label']) !!}
                    {!! Form::text('country', $taxRate->country, ['class' => 'form-control', 'placeholder' => 'Country']) !!}
                    <span class="invalid-feedback" role="alert">
                        <strong></strong>
                    </span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group" id="stateInput">
                    {!! Form::label('title', 'State',['class' => 'control-label']) !!}
                    {!! Form::text('state', $taxRate->state, ['class' => 'form-control', 'placeholder' => 'State']) !!}
                    <span class="invalid-feedback" role="alert">
                        <strong></strong>
                    </span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group" id="postal_typeInput">
                    {!! Form::label('title', 'Applied On',['class' => 'control-label']) !!}
                    <select class="form-control selectize-select postalSelect" name="postal_type" for="edit">
                        <option value="0" @if($taxRate->is_zip == 0) selected @endif>No Postal Code</option>
                        <option value="1" @if($taxRate->is_zip == 1) selected @endif>Single Postal Code</option>
                        <option value="2" @if($taxRate->is_zip == 2) selected @endif>Postal Code Length</option>
                    </select>
                    <span class="invalid-feedback" role="alert">
                        <strong></strong>
                    </span>
                </div>
            </div>--}}
            <div class="col-md-6">
                <div class="form-group" id="tax_rateInput">
                    {!! Form::label('title', 'Tax Rate',['class' => 'control-label']) !!}
                    {!! Form::text('tax_rate', $taxRate->tax_rate, ['class' => 'form-control', 'placeholder' => 'Tax Rate', 'onkeypress' => 'return isNumberKey(event)']) !!}
                    <span class="invalid-feedback" role="alert">
                        <strong></strong>
                    </span>
                    <input type="hidden" name="tr_id" id="tr_id" url="{{route('taxRate.update', $taxRate->id)}}">
                </div>
            </div>
        </div>
        <div class="row" id="singlePostal-edit" style="@if($taxRate->is_zip != 1) display: none; @endif">
            <div class="col-md-6">
                <div class="form-group" id="postal_codeInput">
                    {!! Form::label('title', 'Postal Code',['class' => 'control-label']) !!}
                    {!! Form::text('postal_code', $taxRate->zip_code, ['class' => 'form-control', 'placeholder' => 'Tax Identifier', 'onkeypress' => 'return isNumberKey(event)']) !!}
                    <span class="invalid-feedback" role="alert">
                        <strong></strong>
                    </span>
                </div>
            </div>
        </div>
        <div class="row" id="multiPostal-edit" style="@if($taxRate->is_zip != 2) display: none; @endif">
            <div class="col-md-6">
                <div class="form-group" id="postal_code_startInput">
                    {!! Form::label('title', 'Postal Code Start',['class' => 'control-label']) !!}
                    {!! Form::text('postal_code_start', $taxRate->zip_from, ['class' => 'form-control', 'placeholder' => 'Tax Identifier', 'onkeypress' => 'return isNumberKey(event)']) !!}
                    <span class="invalid-feedback" role="alert">
                        <strong></strong>
                    </span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group" id="postal_code_endInput">
                    {!! Form::label('title', 'Postal Code End',['class' => 'control-label']) !!}
                    {!! Form::text('postal_code_end', $taxRate->zip_to, ['class' => 'form-control', 'placeholder' => 'Tax Identifier', 'onkeypress' => 'return isNumberKey(event)']) !!}
                    <span class="invalid-feedback" role="alert">
                        <strong></strong>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>