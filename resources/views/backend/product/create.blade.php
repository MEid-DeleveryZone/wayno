@extends('layouts.vertical', ['demo' => 'creative', 'title' => 'Add Product'])

@section('css')
<link href="{{asset('assets/libs/dropzone/dropzone.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/dropify/dropify.min.css')}}" rel="stylesheet" type="text/css" />
<style type="text/css">
    .image-upload>input {
        display: none;
    }
</style>
@endsection

@section('content')

<!-- Start Content-->
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-8">
            <div class="page-title-box">
                <h4 class="page-title">{{ __("Add Product") }}</h4>
            </div>
        </div>
        <div class="col-4 text-right" style="margin: auto;">
            <button type="button" class="btn btn-info waves-effect waves-light text-sm-right saveProduct"> {{ __("Submit") }}</button>
        </div>
    </div>
    <div class="row mb-2">
        <div class="col-sm-12">
            <div class="text-sm-left">
                @if (\Session::has('success'))
                <div class="alert alert-success">
                    <span>{!! \Session::get('success') !!}</span>
                </div>
                @endif
                @if (\Session::has('error_delete'))
                <div class="alert alert-danger">
                    <span>{!! \Session::get('error_delete') !!}</span>
                </div>
                @endif
                @if ( ($errors) && (count($errors) > 0) )
                    <div class="alert alert-danger">
                        <button type="button" class="close p-0" data-dismiss="alert">x</button>
                        <ul class="m-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <form action="{{route('product.store')}}" enctype="multipart/form-data" method="post" class="product_form">
        <div class="row">
            {!! Form::hidden('vendor_id', $vendor_id) !!}
            <div class="col-lg-7">
                @csrf
                <div class="card-box">
                    <h5 class="text-uppercase bg-light p-2 mt-0 mb-3">{{ __("General") }}</h5>
                    <div class="row mb-2">
                        <div class="col-6 mb-2">
                            {!! Form::label('title', __('Product Type'),['class' => 'control-label']) !!}
                            <select class="form-control selectizeInput" id="typeSelectBox" name="type_id">
                                @foreach($typeArray as $type)
                                    <option value="{{$type->id}}">{{$type->title}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-6 mb-2">
                            {!! Form::label('title', __('SKU (Allowed Keys -> a-z,A-Z,0-9,-,_)'),['class' => 'control-label']) !!}
                            <span class="text-danger">*</span>
                            {!! Form::text('sku', null, ['class'=>'form-control','id' => 'sku', 'onkeypress' => 'return alplaNumeric(event)', 'placeholder' => 'Apple-iMac']) !!}

                            @if($errors->has('sku'))
                                <span class="text-danger" role="alert">
                                    <strong>{{ $errors->first('sku') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="col-6" style="cursor: not-allowed;">
                            {!! Form::label('title', __('URL Slug'),['class' => 'control-label']) !!}
                            {!! Form::text('product_url', null, ['class'=>'form-control', 'id' => 'product_url', 'placeholder' => 'Apple iMac', 'style' => 'pointer-events:none;']) !!}
                        </div>

                        <div class="col-6">
                            {!! Form::label('title', __('Category'),['class' => 'control-label']) !!}
                            <select class="form-control selectizeInput" id="category_list" name="category[]">
                                <option value="">{{ __("Select Category") }}...</option>
                                @foreach($categories as $cate)
                                    <option value="{{$cate->id}}">{{$cate->english->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="card-box ">
                    <div class="row mb-2 bg-light">
                        <div class="col-8" style="margin:auto; padding: 8px !important;">
                            <h5 class="text-uppercase  mt-0 mb-0">{{ __("Product Information") }}</h5>
                        </div>
                        <div class="col-4 p-2 mt-0" style="margin:auto; padding: 8px !important;">
                            <select class="selectize-select form-control" id="language_id" name="language_id">
                                @foreach($languages as $lang)
                                <option value="{{$lang->langId}}">{{$lang->langName}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-12 mb-2">
                            {!! Form::label('title', __('Product Name'),['class' => 'control-label']) !!}
                            {!! Form::text('product_name', null, ['class'=>'form-control', 'id' => 'product_name', 'placeholder' => 'Apple iMac', 'required' => 'required']) !!}
                        </div>

                        <div class="col-12 mb-2">
                            {!! Form::label('title', __('Product Desription'),['class' => 'control-label']) !!}
                            {!! Form::textarea('body_html', null, ['class'=>'form-control', 'id' => 'body_html', 'placeholder' => 'Description', 'rows' => '3']) !!}
                        </div>

                        <div class="col-12 mb-2">
                            {!! Form::label('title', __('Meta Title'),['class' => 'control-label']) !!}
                            {!! Form::text('meta_title', null, ['class'=>'form-control', 'id' => 'meta_title', 'placeholder' => 'Meta Title']) !!}
                        </div>

                        <div class="col-12 mb-2">
                            {!! Form::label('title', __('Meta Keyword'),['class' => 'control-label']) !!}
                            {!! Form::textarea('meta_keyword', null, ['class'=>'form-control', 'id' => 'meta_keyword', 'placeholder' => 'Meta Keyword', 'rows' => '3']) !!}
                        </div>

                        <div class="col-12 mb-2">
                            {!! Form::label('title', __('Meta Desription'),['class' => 'control-label']) !!}
                            {!! Form::textarea('meta_description', null, ['class'=>'form-control', 'id' => 'meta_description', 'placeholder' => 'Meta Desription', 'rows' => '3']) !!}
                        </div>
                    </div>
                </div>

                <div class="card-box">
                    <h5 class="text-uppercase mt-0 mb-3 bg-light p-2">{{ __("Pricing Information") }}</h5>
                    <div class="row mb-2">
                        <div class="col-6 mb-2">
                            {!! Form::label('title', __('Price'), ['class' => 'control-label']) !!}
                            {!! Form::text('price', null, ['class'=>'form-control', 'id' => 'price', 'placeholder' => '200', 'onkeypress' => 'return isNumberKey(event)']) !!}
                        </div>
                        <div class="col-6 mb-2">
                            {!! Form::label('title', __('Compare at price'), ['class' => 'control-label']) !!}
                            {!! Form::text('compare_at_price', null, ['class'=>'form-control', 'id' => 'compare_at_price', 'placeholder' => '200', 'onkeypress' => 'return isNumberKey(event)']) !!}
                        </div>
                        <div class="col-6 mb-2">
                            {!! Form::label('title', __('Cost Price'), ['class' => 'control-label']) !!}
                            {!! Form::text('cost_price', null, ['class'=>'form-control', 'id' => 'cost_price', 'placeholder' => '200', 'onkeypress' => 'return isNumberKey(event)']) !!}
                        </div>
                        
                    </div>
                    <div class="row mb-2">
                        {!! Form::label('title', __('Track Inventory'),['class' => 'control-label col-sm-4']) !!}
                        <div class="col-sm-4">
                            <input type="checkbox" bid="" id="has_inventory" data-plugin="switchery" name="has_inventory" class="chk_box" data-color="#43bee1" checked>
                        </div>
                    </div>
                    <div class="row mb-2 check_inventory">
                        <div class="col-sm-6">
                            {!! Form::label('title', __('Quantity'),['class' => 'control-label']) !!}
                            {!! Form::number('quantity', 0, ['class'=>'form-control', 'id' => 'quantity', 'placeholder' => '0', 'min' => '0', 'onkeypress' => 'return isNumberKey(event)']) !!}
                        </div>
                        <div class="col-sm-2"></div>
                        <div class="col-sm-4">
                            {!! Form::label('title', __('Sell When Out Of Stock'),['class' => 'control-label']) !!} <br/>
                            <input type="checkbox" bid="" id="sell_stock_out" data-plugin="switchery" name="sell_stock_out" class="chk_box" data-color="#43bee1">
                        </div>

                    </div>
                </div>

                <div class="card-box">
                    
                    <div class="row mb-2 bg-light">
                        <div class="col-8" style="margin:auto;">
                            <h5 class="text-uppercase mt-0 bg-light p-2">{{ __("Variant Information") }}</h5>
                        </div>
                        <div class="col-4 p-2 mt-0 text-right" style="margin:auto; ">
                            <button type="button" class="btn btn-info makeVariantRow"> {{ __("Create Variants") }}</button>
                        </div>
                    </div>
                    <p>{{ __("Select or change category to get variants") }}</p>
                    <div class="row" style="width:100%; overflow-x: scroll;">
                        <div id="variantAjaxDiv" class="col-12 mb-2" ></div>

                        <div id="variantRowDiv" class="col-12"></div>
                        
                    </div>
                </div>                

            </div> <!-- end col -->

            <div class="col-lg-5">

                <div class="card-box">
                    <h5 class="text-uppercase mt-0 mb-3 bg-light p-2">{{ __("Other Information") }}</h5>

                    <div class="row mb-2">
                        {!! Form::label('title', __('New'),['class' => 'control-label col-sm-2']) !!}
                        <div class="col-sm-4">
                            <input type="checkbox" bid="" id="is_new" data-plugin="switchery" name="is_new" class="chk_box" data-color="#43bee1">
                        </div>
                        {!! Form::label('title', __('Featured'),['class' => 'control-label col-sm-2']) !!}
                        <div class="col-sm-4">
                            <input type="checkbox" bid="" id="is_featured" data-plugin="switchery" name="is_featured" class="chk_box" data-color="#43bee1">
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-sm-6">
                            {!! Form::label('title', __('Live'),['class' => 'control-label']) !!}
                            <select class="selectize-select form-control" id="is_live" name="is_live">
                                <option value="0">{{ __("Draft") }}</option>
                                <option value="1">{{ __("Published") }}</option>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            {!! Form::label('title', __('Tax Category'),['class' => 'control-label']) !!}
                            <select class="form-control selectizeInput" id="typeSelectBox" name="tax_category">
                                @foreach($taxCate as $cate)
                                    <option value="{{$cate->id}}">{{$cate->title}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mb-2">
                        {!! Form::label('title', __('Physical'),['class' => 'control-label col-sm-2']) !!}
                        <div class="col-sm-4">
                            <input type="checkbox" bid="" id="is_physical" data-plugin="switchery" name="is_physical" class="chk_box" data-color="#43bee1">
                        </div>

                        {!! Form::label('title', __('Required Last Mile'),['class' => 'control-label col-sm-2']) !!}
                        <div class="col-sm-4">
                            <input type="checkbox" id="last_mile" data-plugin="switchery" name="last_mile" class="chk_box" data-color="#43bee1">
                        </div>
                    </div>
                    <div class="row mb-2 physicalDiv" style="display: none;">
                        <div class="col-sm-6">
                            {!! Form::label('title', __('Weight'),['class' => 'control-label']) !!}
                            {!! Form::text('weight', null,['class' => 'form-control', 'onkeypress' => 'return isNumberKey(event)']) !!}
                        </div>
                        <div class="col-sm-6">
                            {!! Form::label('title', __('Weight Unit'),['class' => 'control-label']) !!}
                            {!! Form::text('weight_unit', null,['class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="row mb-2 physicalDiv" style="display: none;">
                        {!! Form::label('title', 'Required Shipping',['class' => 'control-label col-sm-2 mb-2']) !!}
                        <div class="col-sm-4 mb-2">
                            <input type="checkbox" id="requiredShipping" data-plugin="switchery" name="require_ship" class="chk_box" data-color="#43bee1">
                        </div>
                        <div class="col-sm-6"></div>
                        <div class="col-sm-6 shippingDiv" style="display: none;">
                            {!! Form::label('title', __('Country Origin'),['class' => 'control-label']) !!}
                            <select class="form-control selectizeInput" id="country_origin_id" name="country_origin_id">
                                @foreach($countries as $coun)
                                    <option value="{{$coun->id}}">{{$coun->name}}</option>
                                @endforeach
                            </select>

                        </div>
                    </div>
                </div>

                <div class="card-box">
                    <h5 class="text-uppercase mt-0 mb-3 bg-light p-2">{{ __("Product Images") }}</h5>
                    <div class="dropzone dropzone-previews" id="my-awesome-dropzone"></div>

                    <div class="imageDivHidden" ></div>

                </div>

                <div class="card-box">
                    <h5 class="text-uppercase mt-0 mb-3 bg-light p-2">{{ __("Relate with other products") }}</h5>
                    <div class="row mb-2">
                        <div class="col-12">
                            {!! Form::label('title', __('Select Addon Set'),['class' => 'control-label']) !!}
                            <select class="form-control selectizeInput" name="addon_sets[]" multiple placeholder="Select gear...">
                                <option value="">{{ __("Select gear") }}...</option>
                                @foreach($addons as $set)
                                <option value="{{$set->id}}">{{$set->title}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div> 
                    <div class="row mb-2">
                        <div class="col-12">
                            {!! Form::label('title', __('Up Sell Products'),['class' => 'control-label']) !!}
                            <select class="form-control selectizeInput" name="up_cell[]" multiple placeholder="Select gear...">
                                <option value="">{{ __("Select gear") }}...</option>
                                <optgroup label="Climbing">
                                    <option value="pitons">{{ __("Pitons") }}</option>
                                    <option value="cams">{{ __("Cams") }}</option>
                                    <option value="nuts">{{ __("Nuts") }}</option>
                                    <option value="bolts">{{ __("Bolts") }}</option>
                                    <option value="stoppers">{{ __("Stoppers") }}</option>
                                    <option value="sling">{{ __("Sling") }}</option>
                                </optgroup>
                                <optgroup label="Skiing">
                                    <option value="skis">{{ __("Skis") }}</option>
                                    <option value="skins">{{ __("Skins") }}</option>
                                    <option value="poles">{{ __("Poles") }}</option>
                                </optgroup>
                            </select>
                        </div>

                        <div class="col-12">
                            {!! Form::label('title', __('Cross Sell Products'),['class' => 'control-label']) !!}
                            <select class="form-control selectizeInput" name="cross_cell[]" multiple placeholder="Select gear...">
                                <option value="">{{ __('Select gear') }}...</option>
                                <optgroup label="Climbing">
                                    <option value="pitons">{{ __('Pitons') }}</option>
                                    <option value="cams">{{ __('Cams') }}</option>
                                    <option value="nuts">{{ __('Nuts') }}</option>
                                    <option value="bolts">{{ __('Bolts') }}</option>
                                    <option value="stoppers">{{ __('Stoppers') }}</option>
                                    <option value="sling">{{ __('Sling') }}</option>
                                </optgroup>
                                <optgroup label="Skiing">
                                    <option value="skis">{{ __('Skis') }}</option>
                                    <option value="skins">{{ __('Skins') }}</option>
                                    <option value="poles">{{ __('Poles') }}</option>
                                </optgroup>
                            </select>
                        </div>
                        <div class="col-12">
                            {!! Form::label('title', __('Related Products'),['class' => 'control-label']) !!}
                            <select class="form-control selectizeInput" name="releted_product[]" multiple placeholder="Select gear...">
                                <option value="">{{ __('Select gear') }}...</option>
                                <optgroup label="Climbing">
                                    <option value="pitons">{{ __('Pitons') }}</option>
                                    <option value="cams">{{ __('Cams') }}</option>
                                    <option value="nuts">{{ __('Nuts') }}</option>
                                    <option value="bolts">{{ __('Bolts') }}</option>
                                    <option value="stoppers">{{ __('Stoppers') }}</option>
                                    <option value="sling">{{ __('Sling') }}</option>
                                </optgroup>
                                <optgroup label="Skiing">
                                    <option value="skis">{{ __('Skis') }}</option>
                                    <option value="skins">{{ __('Skins') }}</option>
                                    <option value="poles">{{ __('Poles') }}</option>
                                </optgroup>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@endsection

@section('script')

<link href="{{asset('assets/css/dropzone.css')}}" rel="stylesheet" />
<script src="{{asset('assets/js/dropzone.js')}}"></script>

<script type="text/javascript">
    $('#requiredShipping').change(function(){
        var val = $(this).prop('checked');
        if(val == true){
            $('.shippingDiv').show();
        }else{
            $('.shippingDiv').hide();
        }
    });

    $('#is_physical').change(function(){
        var val = $(this).prop('checked');
        if(val == true){
            $('.physicalDiv').show();
        }else{
            $('.physicalDiv').hide();
        }
    });

    $('#has_inventory').change(function(){
        var val = $(this).prop('checked');
        if(val == true){
            $('.check_inventory').show();
        }else{
            $('.check_inventory').hide();
        }
    });

    var regexp = /^[a-zA-Z0-9-_]+$/;

    function alplaNumeric(evt){
        var charCode = String.fromCharCode(event.which || event.keyCode);

        if (!regexp.test(charCode)){
            return false;
        }
        var n1 = document.getElementById('sku');
        var n2 = document.getElementById('product_url');
        n2.value = n1.value+charCode;
        return true;
    }

    $('.saveProduct').click(function(){
        $('.product_form').submit();
    });

    var uploadedDocumentMap = {};

    Dropzone.autoDiscover = false;
    jQuery(document).ready(function() {

        $("div#my-awesome-dropzone").dropzone({
            addRemoveLinks: true,
            url: "{{route('product.images')}}",
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            success: function (file, res) {

                $('.imageDivHidden').append('<input type="hidden" name="fileIds[]" value="' + res.imageId + '">')
                uploadedDocumentMap[file.name] = res.imageId;

            },
            removedfile: function (file) {
                file.previewElement.remove();
                console.log(file);
                var name = ''
                if (typeof file.file_name !== 'undefined') {
                    name = file.file_name
                } else {
                    name = uploadedDocumentMap[file.name]
                }
                $('form').find('input[name="fileIds[]"][value="' + name +  '"]').remove();
            },
        });

    });

    $('#category_list').change(function(){

        var cid = $(this).val();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });

        var uri = "{{url('client/variant/cate')}}" + '/' + cid;

        $.ajax({
            type: "get",
            url: uri,
            data: '',
            dataType: 'json',
            success: function (data) {
                $('#variantAjaxDiv').html(data.resp);
            },
            error: function (data) {
                console.log('data2');
            }
        });
    });

    $('.makeVariantRow').click(function(){
        var product_sku = $('#sku').val();
        if(product_sku.trim() == ''){
            alert('Enter Product sku.');
            return false;
        }
        var var_ids = [];
        var opt_ids = [];
        count = 0;
        $("#variantAjaxDiv .intpCheck").each(function(){
            var $this = $(this);
            if($this.is(":checked") ){
                opt_ids.push($this.attr('opt'));
                var_ids.push($this.attr('varid'));
            }
        });
        
        $.ajax({
            type: "post",
            url: "{{route('product.makeRows')}}",
            data: {"_token": "{{ csrf_token() }}", 'variantIds' : var_ids, 'optionIds' : opt_ids, 'sku': product_sku},
            dataType: 'json',
            success: function (resp) {
                if(resp.success == 'false'){
                    alert(resp.msg);
                }else{
                    $('#variantRowDiv').html(resp.html);
                }
            },
            error: function (resp) {
                console.log('data2');
            }
        });
    });

    $(document).on('click', '.deleteCurRow', function () {
        $(this).closest('tr').remove();
    });

    $(document).on('change', '.vimage', function () {
        var forv = $(this).attr('for');
        readURL(this, forv);
    });

    function readURL(input, forv) {
        
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('.vimg_'+forv).attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]); // convert to base64 string
        }
    }

</script>

@endsection