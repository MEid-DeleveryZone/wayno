<div id="add-category-form" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h4 class="modal-title">{{ __("Add Category") }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form id="addCategoryForm" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-body" id="AddCategoryBox">
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info waves-effect waves-light addCategorySubmit">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="edit-category-form" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h4 class="modal-title">{{ __("Edit Category") }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>

            <form id="editCategoryForm" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body" id="editCategoryBox">
                    
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-info waves-effect waves-light editCategorySubmit">{{ __("Submit") }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!--   Variant      modals   -->
<div id="addVariantmodal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h4 class="modal-title">{{ __("Add Variant") }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form id="addVariantForm" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-body" id="AddVariantBox">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {!! Form::label('title', __('Select Category'),['class' => 'control-label']) !!}
                                        <select class="form-control" id="cateSelectBox" name="parent_cate">
                                            <option value="">{{ __("Select") }}</option>
                                            @foreach($categories as $cate)
                                                <option value="{{$cate->id}}">{{$cate->slug}}</option>
                                            @endforeach
                                        </select>
                                        <span class="invalid-feedback" role="alert">
                                            <strong></strong>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {!! Form::label('title', 'Select List',['class' => 'control-label']) !!}
                                        <select class="form-control" name="type">
                                            <option value="1">{{ __("DropDown") }}</option>
                                            <option value="2">{{ __("Color") }}</option>
                                        </select>
                                        <span class="invalid-feedback" role="alert">
                                            <strong></strong>
                                        </span>
                                    </div>
                                </div>
                            </div>                            
                            <div class="row rowYK">
                                <div class="col-md-12">
                                    <h5>Variant Title</h5>
                                </div>
                                <div class="col-md-12" style="overflow-x: auto;">
                                    <table class="table table-borderless mb-0" id="banner-datatable" >
                                        <tr>
                                            @foreach($languages as $langs)
                                                <td>{{$langs->langName}}</td>
                                            @endforeach
                                        </tr>
                                        <tr>
                                           @foreach($languages as $langs)
                                            <td>
                                                {!! Form::hidden('language_id[]', $langs->langId) !!}
                                                {!! Form::text('title[]', null, ['class' => 'form-control']) !!}
                                            </td>
                                            @endforeach 
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <div class="row rowYK">
                                <div class="col-md-12">
                                    <h5>{{ __("Variant Options") }}</h5>
                                </div>
                                <div class="col-md-12" style="overflow-x: auto;">
                                    <table class="table table-borderless mb-0" id="banner-datatable" >
                                        <tr>
                                            <td>{{ __("Color Code") }}</td>
                                            @foreach($languages as $langs)
                                                <td>{{$langs->langName}}</td>
                                            @endforeach
                                        </tr>
                                        <tr class="trForClone">
                                            <td style="min-width: 100px;">
                                                {!! Form::text('hexacode[]', null, ['class' => 'form-control', 'placeholder' => '#cccccc']) !!}
                                            </td>
                                           @foreach($languages as $langs)
                                            <td>
                                                <input type="text" name="opt_color_{{$langs->langId}}" class="form-control">
                                            </td>
                                            @endforeach 
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info waves-effect waves-light addVariantSubmit">{{ __("Submit") }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="editVariantmodal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h4 class="modal-title">{{ __("Edit Variant") }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form id="editVariantForm" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-body" id="editVariantBox">
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info waves-effect waves-light editVariantSubmit">{{ __("Submit") }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!--   Brand      modals   -->
<div id="addBrandmodal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h4 class="modal-title">{{ __("Add Brand") }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form id="addBrandForm" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-body" id="AddBrandBox">
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info waves-effect waves-light addBrandSubmit">{{ __("Submit") }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="editBrandmodal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h4 class="modal-title">{{ __("Edit Brand") }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form id="editBrandForm" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-body" id="editBrandBox">
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info waves-effect waves-light editBrandSubmit">{{ __("Submit") }}</button>
                </div>
            </form>
        </div>
    </div>
</div>