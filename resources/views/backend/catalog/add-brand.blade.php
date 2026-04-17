<div class="row">
    <div class="col-md-12 pb-0 mb-0">
        <div class="row mb-2">
            <div class="col-md-12">
                <label>{{ __("Upload Logo") }}</label>
                <input type="file" accept="image/*" data-plugins="dropify" name="image1" class="dropify" data-default-file="" />
                <label class="logo-size d-block text-right mt-1">{{ __("Image Size") }} 350x200</label>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-md-12">
                <label>{{ __("Upload Banner") }}</label>
                <input type="file" accept="image/*" data-plugins="dropify" name="image2" class="dropify" data-default-file="" />
                <label class="logo-size d-block text-right mt-1">{{ __("Image Size") }} 1920x1080</label>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    {!! Form::label('title', __('Select Category'),['class' => 'control-label']) !!}
                    <select class="form-control selectize-select" data-toggle="select2" id="cateSelectBox" name="cate_id[]" multiple="multiple" data-placeholder="Choose ...">
                        @foreach($categories as $cate)
                        <option value="{{$cate->id}}">{{$cate->slug}}</option>
                        @endforeach
                    </select>
                    <span class="invalid-feedback" role="alert">
                        <strong></strong>
                    </span>
                </div>
            </div>

        </div>
        <div class="row rowYK">
            <div class="col-md-12">
                <h5>{{ __("Brand Title") }}</h5>
            </div>
            <div class="col-md-12" style="overflow-x: auto;">
                <table class="table table-borderless mb-0" id="banner-datatable">
                    <tr>
                        @foreach($languages as $langs)
                        <th>{{$langs->language->name}}</th>
                        @endforeach
                    </tr>
                    <tr>
                        @foreach($languages as $langs)
                        @if($langs->is_primary == 1)
                        <td>
                            {!! Form::hidden('language_id[]', $langs->language->id) !!}
                            {!! Form::text('title[]', null, ['class' => 'form-control', 'required' => 'required']) !!}
                        </td>

                        @else
                        <td>
                            {!! Form::hidden('language_id[]', $langs->language->id) !!}
                            {!! Form::text('title[]', null, ['class' => 'form-control']) !!}
                        </td>
                        @endif
                        @endforeach
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>