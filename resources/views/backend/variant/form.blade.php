<div class="row">
    <div class="col-md-12">
        

        <div class="row">
            <div class="col-md-3">
                <input type="file" accept="image/*" data-plugins="dropify" name="icon" class="dropify" data-default-file="{{ !empty($banner->icon) ? env('IMG_URL').'storage/app/public/'.$banner->icon : '' }}" />
                <p class="text-muted text-center mt-2 mb-0">Upload Category Icon</p>
            </div>
            <div class="col-md-6"> <!--  Storage::disk('s3')->url($client->logo)  -->                 
                <input type="file" accept="image/*" data-plugins="dropify" name="image" class="dropify" data-default-file="{{ !empty($banner->image) ? env('IMG_URL').'storage/app/public/'.$banner->image : '' }}" />
                <p class="text-muted text-center mt-2 mb-0">Upload Category image</p>
            </div>
        </div>

        <div class="row">
            <h3>Admin Default Language</h3>
            <div class="col-md-4">
                <div class="form-group" id="nameInput">
                    {!! Form::label('title', 'Name',['class' => 'control-label']) !!}
                    {!! Form::text('name[]', null, ['class' => 'form-control']) !!}
                    <span class="invalid-feedback" role="alert">
                        <strong></strong>
                    </span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    {!! Form::label('title', 'Slug',['class' => 'control-label']) !!} 
                    {!! Form::text('slug[]', null, ['class' => 'form-control']) !!}
                    <span class="invalid-feedback" role="alert">
                        <strong></strong>
                    </span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    {!! Form::label('title', 'Meta Title',['class' => 'control-label']) !!} 
                    {!! Form::text('meta_title[]', null, ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::label('title', 'Meta Description',['class' => 'control-label']) !!} 
                    {!! Form::textarea('meta_description[]', null, ['class'=>'form-control']) !!}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::label('title', 'Meta Keywords',['class' => 'control-label']) !!} 
                    {!! Form::textarea('meta_keywords[]', null, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
        <div class="row">
            @foreach($languages as langs)
            <h3>{{$langs->language->name}} Language</h3>
            <div class="col-md-4">
                <div class="form-group" id="nameInput">
                    {!! Form::label('title', 'Name',['class' => 'control-label']) !!}
                    {!! Form::text('name[]', null, ['class' => 'form-control']) !!}
                    <span class="invalid-feedback" role="alert">
                        <strong></strong>
                    </span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    {!! Form::label('title', 'Slug',['class' => 'control-label']) !!} 
                    {!! Form::text('slug[]', null, ['class' => 'form-control']) !!}
                    <span class="invalid-feedback" role="alert">
                        <strong></strong>
                    </span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    {!! Form::label('title', 'Meta Title',['class' => 'control-label']) !!} 
                    {!! Form::text('meta_title[]', null, ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::label('title', 'Meta Description',['class' => 'control-label']) !!} 
                    {!! Form::textarea('meta_description[]', null, ['class'=>'form-control']) !!}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::label('title', 'Meta Keywords',['class' => 'control-label']) !!} 
                    {!! Form::textarea('meta_keywords[]', null, ['class' => 'form-control']) !!}
                </div>
            </div>
            @endforeach
        </div>
        
    </div>
</div>