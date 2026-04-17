@extends('layouts.vertical', ['title' => 'Catalog'])
@section('css')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
<link href="{{asset('assets/libs/nestable2/nestable2.min.css')}}" rel="stylesheet" type="text/css" />
<style>
    .image-removed-warning {
        background-color: #fff3cd;
        border: 1px solid #ffeaa7;
        color: #856404;
        padding: 10px 15px;
        border-radius: 5px;
        margin-top: 10px;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .image-removed-warning i {
        color: #f39c12;
        font-size: 16px;
    }
    
    .image-removed-warning.alert-warning {
        animation: fadeIn 0.3s ease-in;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .text-muted.note-text {
        font-style: italic;
        color: #6c757d !important;
        margin-top: 5px;
        display: block;
    }
    
    .text-muted.optional-text {
        color: #28a745 !important;
        font-weight: 500;
        font-size: 12px;
    }
    
    .form-group label small.optional-text {
        margin-left: 8px;
        padding: 2px 8px;
        background-color: #d4edda;
        border-radius: 12px;
        border: 1px solid #c3e6cb;
    }
</style>
@endsection
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">{{ __('Catalog') }}</h4>
            </div>
        </div>
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
            </div>
        </div>
    </div>
    <div class="row catalog_box">
        <div class="col-xl-4 col-lg-6 mb-4 order-list-view">
            <div class="card-box h-100">
                <div class="row mb-2">
                    <div class="col-sm-8">
                        <h4 class="page-title">{{ __('Category') }}</h4>
                        <p class="sub-header">
                            {{ __('Drag & drop Categories to make child parent relation') }}
                        </p>
                    </div>
                    <div class="col-sm-4 text-right">
                        <button class="btn btn-info waves-effect waves-light text-sm-right openCategoryModal" dataid="0" is_vendor="0"><i class="mdi mdi-plus-circle mr-1"></i> {{ __('Add') }}
                        </button>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-12">
                        <form name="category_order" id="category_order" action="{{route('category.order')}}" method="post">
                            @csrf
                            <input type="hidden" name="orderDta" id="orderDta" value="" />
                        </form>
                        <div class="custom-dd-empty dd" id="nestable_list_3">
                            <?php print_r($html); ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 text-right btn_bottom">
                        <button class="btn btn-info waves-effect waves-light text-sm-right saveList">{{ __('Save Order') }}</button>
                    </div>
                </div>
            </div>
        </div>

        @if($client_preference_detail->business_type != 'taxi')
        <div class="col-xl-4 col-lg-6 mb-4">
            <div class="card-box h-100">
                <div class="row mb-2">
                    <div class="col-sm-8">
                        <h4 class="page-title">{{ __("Variant") }}</h4>
                        <p class="sub-header">
                            {{ __("Drag & drop Variant to change the position") }}
                        </p>
                    </div>
                    <div class="col-sm-4 text-right">
                        <button class="btn btn-info waves-effect waves-light text-sm-right addVariantbtn" dataid="0"><i class="mdi mdi-plus-circle mr-1"></i> {{ __('Add') }}
                        </button>
                    </div>
                    <div class="col-sm-8">
                        <h4 class="page-title"></h4>
                    </div>
                </div>
                <div class="row variant-row">
                    <div class="col-md-12">
                        <form name="variant_order" id="variant_order" action="{{route('variant.order')}}" method="post">
                            @csrf
                            <input type="hidden" name="orderData" id="orderVariantData" value="" />
                        </form>
                        <div class="table-responsive">
                            <table class="table table-centered table-nowrap table-striped" id="varient-datatable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Options') }}</th>
                                        <th>{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($variants as $key => $variant)
                                    @if(!empty($variant->translation_one))
                                    <tr class="variantList" data-row-id="{{$variant->id}}">
                                        <td><span class="dragula-handle"></span></td>
                                        <td><a class="editVariantBtn" dataid="{{$variant->id}}" href="javascript:void(0);">{{$variant->title}}</a> <br> <b>{{isset($variant->varcategory->cate->primary->name) ? $variant->varcategory->cate->primary->name : ''}}</b></td>
                                        <td>
                                            @foreach($variant->option as $key => $value)
                                            <label style="margin-bottom: 3px;">
                                                @if(isset($variant) && !empty($variant->type) && $variant->type == 2)
                                                <span style="padding:8px; float: left; border: 1px dotted #ccc; background:{{$value->hexacode}};"> </span>
                                                @endif
                                                &nbsp;&nbsp; {{$value->title}}</label> <br />
                                            @endforeach
                                        </td>
                                        <td>
                                            <a class="action-icon editVariantBtn" dataid="{{$variant->id}}" href="javascript:void(0);">
                                                <i class="mdi mdi-square-edit-outline"></i>
                                            </a>
                                            <a class="action-icon deleteVariant" dataid="{{$variant->id}}" href="javascript:void(0);">
                                                <i class="mdi mdi-delete"></i>
                                            </a>
                                            <form action="{{route('variant.destroy', $variant->id)}}" method="POST" style="display: none;" id="varDeleteForm{{$variant->id}}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="action-icon btn btn-primary-outline" dataid="{{$variant->id}}" onclick="return confirm('Are you sure? You want to delete the variant.')"> <i class="mdi mdi-delete"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-sm-12 text-right btn_bottom">
                        <button class="btn btn-info waves-effect waves-light text-sm-right saveVariantOrder">{{ __('Save Order') }}</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-lg-6 mb-4">
            <div class="card-box h-100">
                <div class="row mb-2">
                    <div class="col-sm-8">
                        <h4 class="page-title">{{ __('Brand') }}</h4>
                        <p class="sub-header"></p>
                    </div>
                    <div class="col-sm-4 text-right">
                        <button class="btn btn-info waves-effect waves-light text-sm-right addBrandbtn" dataid="0">
                            <i class="mdi mdi-plus-circle mr-1"></i> {{ __('Add') }}
                        </button>
                    </div>
                </div>
                <div class="row brand-row">
                    <div class="col-md-12">
                        <form name="brand_order" id="brand_order" action="{{route('brand.order')}}" method="post">
                            @csrf
                            <input type="hidden" name="orderData" id="orderBrandData" value="" />
                        </form>
                        <div class="table-responsive">
                            <table class="table table-centered table-nowrap table-striped" id="brand-datatable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{ __('Icon') }}</th>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($brands as $key => $brand)
                                    @if(!empty($brand->translation_one))
                                    <tr class="brandList" data-row-id="{{$brand->id}}">
                                        <td><span class="dragula-handle"></span></td>
                                        <td><img class="rounded-circle" src="{{$brand->image['proxy_url'].'30/30'.$brand->image['image_path']}}"></td>
                                        <td><a class="editBrandBtn" dataid="{{$brand->id}}" href="javascript:void(0);">{{$brand->title}}</a> <br> <b>
                                                @foreach($brand->bc as $cat)
                                                @foreach($categories as $cate)
                                                @if($cat->category_id == $cate->id && $cat->brand_id==$brand->id)
                                                {{$cate->translation_one['name']??''}}
                                                @endif
                                                @endforeach
                                                @endforeach
                                            </b></td>
                                        <td>
                                            <a class="action-icon editBrandBtn" dataid="{{$brand->id}}" href="javascript:void(0);">
                                                <i class="mdi mdi-square-edit-outline"></i>
                                            </a>
                                            <a class="action-icon deleteBrand" dataid="{{$brand->id}}" href="javascript:void(0);"> <i class="mdi mdi-delete"></i> </a>
                                            <form action="{{route('brand.destroy', $brand->id)}}" method="POST" style="display: none;" id="brandDeleteForm{{$brand->id}}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="action-icon btn btn-primary-outline" dataid="{{$brand->id}}"> <i class="mdi mdi-delete"></i> </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-sm-12 text-right btn_bottom">
                        <button class="btn btn-info waves-effect waves-light text-sm-right saveBrandOrder">{{ __('Save Order') }}</button>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@include('backend.common.category-modals')
@include('backend.catalog.modals')
@endsection
@section('script')
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
<script src="{{asset('assets/libs/nestable2/nestable2.min.js')}}"></script>
<script src="{{asset('assets/js/pages/my-nestable.init.js')}}"></script>
<script src="{{asset('assets/libs/dragula/dragula.min.js')}}"></script>
<script src="{{asset('assets/js/pages/dragula.init.js')}}"></script>
<script src="{{asset('assets/js/jscolor.js')}}"></script>
<script src="{{ asset('assets/js/jquery.tagsinput-revisited.js') }}"></script>
<link rel="stylesheet" href="{{ asset('assets/css/jquery.tagsinput-revisited.css') }}" />
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>


@include('backend.common.category-script')
@include('backend.catalog.pagescript')
<script type="text/javascript">
    var tagList = "";
    tagList = tagList.split(',');

    function makeTag(tagList = '') {
        $('.myTag1').tagsInput({
            'autocomplete': {
                source: tagList
            }
        });
    }
    $('.saveList').on('click', function(e) {
        e.preventDefault();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token-z"]').attr('content')
            }
        });
        var data = $('.dd').nestable('serialize');
        document.getElementById('orderDta').value = JSON.stringify(data);
        $('#category_order').submit();
    });
</script>

@endsection