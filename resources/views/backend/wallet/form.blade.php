<input type="hidden" name="lc_id" id="lc_id" url="{{route('wallet.update', $lc->id)}}">

<div class="row mb-2">
    <div class="col-md-6">
        <div class="form-group" id="nameInput">
            {!! Form::label('title', 'Amount',['class' => 'control-label']) !!}
            {!! Form::text('amount', null, ['class' => 'form-control', 'placeholder' => 'Amount' , 'onkeypress' => 'return isNumberKey(event);']) !!}
            <span class="invalid-feedback" role="alert">
                <strong></strong>
            </span>
        </div>
    </div>
</div>