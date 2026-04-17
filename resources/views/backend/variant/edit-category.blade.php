<div class="row">
    <div class="col-md-12">
        

        <div class="row mb-2">
            <div class="col-md-3">
                <input type="file" accept="image/*" data-plugins="dropify" name="icon" class="dropify" data-default-file="{{ !empty($category->icon) ? env('IMG_URL').'storage/app/public/'.$category->icon : '' }}" />
                <p class="text-muted text-center mt-2 mb-0">Upload Category Icon</p>
            </div> <div class="col-md-2"></div>
            <div class="col-md-6"> <!--  Storage::disk('s3')->url($client->logo)  -->                 
                <input type="file" accept="image/*" data-plugins="dropify" name="image" class="dropify" data-default-file="{{ !empty($category->image) ? env('IMG_URL').'storage/app/public/'.$category->image : '' }}" />
                <p class="text-muted text-center mt-2 mb-0">Upload Category image</p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-5">
                <div class="form-group" id="slugInput">
                    {!! Form::label('title', 'Slug',['class' => 'control-label']) !!} 
                    {!! Form::text('slug', $category->slug, ['class'=>'form-control']) !!}
                    <span class="invalid-feedback" role="alert">
                        <strong></strong>
                    </span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    {!! Form::label('title', 'Visible In Menus',['class' => 'control-label']) !!} 
                    <div>
                        @if($category->is_visible == '1')
                            <input type="checkbox" data-plugin="switchery" name="is_visible" class="form-control switch2Edit" data-color="#43bee1" checked='checked'>
                        @else
                            <input type="checkbox" data-plugin="switchery" name="is_visible" class="form-control switch2Edit" data-color="#43bee1">
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    {!! Form::label('title', 'Select Parent Category',['class' => 'control-label']) !!}
                    <select class="selectize-select1 form-control" id="cateSelectBox" name="parent_cate">
                        <option value="">Select</option>
                        @foreach($parCategory as $pc)
                            <option value="{{$pc->id}}" {{ ($pc->id == $category->parent_id) ? 'selected' : '' }} > {{$pc->name}}</option>
                        @endforeach
                    </select>
                    <span class="invalid-feedback" role="alert">
                        <strong></strong>
                    </span>
                </div>
            </div>
            <div class="col-md-5">
                <div class="form-group">
                    {!! Form::label('title', 'Type',['class' => 'control-label']) !!}
                    <select class="selectize-select form-control" id="typeSelectBox" name="type">
                        <option value="product" @if($category->type == 'product') selected @endif >Product</option>
                        <option value="service" @if($category->type == 'service') selected @endif >Service</option>
                        <option value="ride" @if($category->type == 'ride') selected @endif >Ride</option>
                    </select>
                    <span class="invalid-feedback" role="alert">
                        <strong></strong>
                    </span>
                    <input type="hidden" id="cateId" url="{{route('category.update', $category->id)}}">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    {!! Form::label('title', 'Can Add Products',['class' => 'control-label']) !!} 
                    <div>
                        @if($category->can_add_products == '1')
                            <input type="checkbox" data-plugin="switchery" class="form-control switch1Edit" data-color="#43bee1" name="can_add_products" checked='checked'>
                        @else
                            <input type="checkbox" data-plugin="switchery" class="form-control switch1Edit" data-color="#43bee1" name="can_add_products">
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    {!! Form::label('title', 'Display Mode',['class' => 'control-label']) !!}
                    <select class="selectize-select form-control" name="display_mode">
                        <option value="1" @if($category->display_mode == '1') selected @endif >Show Product only</option>
                        <option value="2" @if($category->display_mode == '2') selected @endif >Show Product With Description</option>
                    </select>
                    <span class="invalid-feedback" role="alert">
                        <strong></strong>
                    </span>
                </div>
            </div>
        </div>

        @foreach($languages as $langs)
            <div class="row rowYK" style="border: 2px dashed #d2d0cd;">
                <h4 class="col-md-12"> {{ ($langs->langId == 1) ? 'Default Language(English)' : $langs->langName.' Language' }} </h4>
                <div class="col-md-6">
                    <div class="form-group" id="{{ ($langs->langId == 1) ? 'nameInput' : 'nameotherInput' }}">
                        {!! Form::label('title', 'Name',['class' => 'control-label']) !!}
                        {!! Form::text('name[]', $langs->cateName, ['class' => 'form-control']) !!}
                        <span class="invalid-feedback" role="alert">
                            <strong></strong>
                        </span>
                    </div>
                </div>
                {!! Form::hidden('language_id[]', $langs->langId) !!}
                {!! Form::hidden('trans_id[]', $langs->trans_id) !!}
                <div class="col-md-6">
                    <div class="form-group" id="meta_titleInput">
                        {!! Form::label('title', 'Meta Title',['class' => 'control-label']) !!} 
                        {!! Form::text('meta_title[]', $langs->meta_title, ['class' => 'form-control']) !!}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('title', 'Meta Description',['class' => 'control-label']) !!} 
                        {!! Form::textarea('meta_description[]', $langs->meta_description, ['class'=>'form-control', 'rows' => '3']) !!}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('title', 'Meta Keywords',['class' => 'control-label']) !!} 
                        {!! Form::textarea('meta_keywords[]', $langs->meta_keywords, ['class' => 'form-control', 'rows' => '3']) !!}
                    </div>
                </div>
            </div>
        @endforeach
        
    </div>
</div>