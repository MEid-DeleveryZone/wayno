<div class="row">
    <div class="col-md-12">
        <div class="row mb-2">
            <div class="col-md-6">
                <div class="form-group" id="titleInputEdit">
                    {!! Form::label('title', 'Title',['class' => 'control-label']) !!}
                    {!! Form::text('title', $tc->title, ['class' => 'form-control', 'placeholder' => __('Tax Category Title')]) !!}
                    <span class="invalid-feedback" role="alert">
                        <strong></strong>
                    </span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group" id="codeInputEdit">
                    {!! Form::label('title', 'Code',['class' => 'control-label']) !!}
                    {!! Form::text('code', $tc->code, ['class' => 'form-control', 'placeholder' => __('Tax Category Code')]) !!}

                    <input type="hidden" name="tc_id" id="tc_id" url="{{route('tax.update', $tc->id)}}">

                    <span class="invalid-feedback" role="alert">
                        <strong></strong>
                    </span>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    {!! Form::label('title', 'Description',['class' => 'control-label']) !!}
                    {!! Form::textarea('description', $tc->description, ['class' => 'form-control', 'placeholder' => __('description'), 'rows' => '5']) !!}
                </div>
            </div>

        </div>
    </div>
</div>
