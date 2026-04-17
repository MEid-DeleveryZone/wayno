@extends('layouts.vertical', ['title' => 'Category'])

@section('css')
    <!-- Plugins css -->
    <link href="{{asset('assets/libs/nestable2/nestable2.min.css')}}" rel="stylesheet" type="text/css" />

    <style type="text/css">
        #add-category-form .modal-lg, #edit-category-form .modal-lg {
            max-width: 70%;
        }
        span.inner-div{
            float: right;
            display: block;
            position: absolute;
            top: -5px;
            right: 16px;
        }
        .table-borderless th, .table-borderless td {
            padding: 8px 10px 8px 0px;
        }
    </style>
@endsection

@section('content')

<!-- Start Content-->
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                
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

    <div class="row">
        <div class="col-sm-4">
            <div class="card-box">
                <div class="row mb-2">
                    <div class="col-sm-8">
                        <h4 class="page-title">Category</h4>
                        <p class="sub-header">
                            Drag & drop Categories to make child parent relation
                        </p>
                    </div>
                    <div class="col-sm-4 text-right">
                        <button class="btn btn-info waves-effect waves-light text-sm-right openCategoryModal"
                         dataid="0"><i class="mdi mdi-plus-circle mr-1"></i> Add
                        </button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">                        
                        <form name="category_order" id="category_order" action="{{route('category.order')}}" method="post">
                            @csrf
                            <input type="hidden" name="orderDta" id="orderDta" value="" />
                        </form>
                        <div class="custom-dd-empty dd" id="nestable_list_3">
                            
                            <?php print_r($html); ?>

                            <!--<ol class="dd-list">
                                <li class="dd-item dd3-item" data-id="13">
                                    <div class="dd-handle dd3-handle"></div>
                                    <div class="dd3-content">
                                        Item 13
                                    </div>
                                </li>
                                <li class="dd-item dd3-item" data-id="14">
                                    <div class="dd-handle dd3-handle"></div>
                                    <div class="dd3-content">
                                        Item 14
                                    </div>
                                </li>
                                <li class="dd-item dd3-item" data-id="15">
                                    <div class="dd-handle dd3-handle"></div>
                                    <div class="dd3-content">
                                        Item 15
                                    </div>
                                    <ol class="dd-list">
                                        <li class="dd-item dd3-item" data-id="16">
                                            <div class="dd-handle dd3-handle"></div>
                                            <div class="dd3-content">
                                                Item 16
                                            </div>
                                        </li>
                                        <li class="dd-item dd3-item" data-id="17">
                                            <div class="dd-handle dd3-handle"></div>
                                            <div class="dd3-content">
                                                Item 17
                                            </div>
                                        </li>
                                        <li class="dd-item dd3-item" data-id="18">
                                            <div class="dd-handle dd3-handle"></div>
                                            <div class="dd3-content">
                                                Item 18
                                            </div>
                                        </li>
                                    </ol>
                                </li>
                            </ol> -->
                            
                        </div>
                    </div>
                    <div class="col-sm-12 text-right">
                        <button class="btn btn-info waves-effect waves-light text-sm-right saveList">Save</button>
                    </div>

                </div> <!-- end row -->
            </div> <!-- end card-box -->
        </div> <!-- end col -->

        <div class="col-sm-4">
            <div class="card-box">
                <div class="row mb-2">
                    <div class="col-sm-8">
                        <h4 class="page-title">Variant</h4>
                        <p class="sub-header">
                            Drag & drop Variant to make child parent relation
                        </p>
                    </div>
                    <div class="col-sm-4 text-right">
                        <button class="btn btn-info waves-effect waves-light text-sm-right addVariantbtn"
                         dataid="0"><i class="mdi mdi-plus-circle mr-1"></i> Add
                        </button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        
                        <form name="variant_order" id="variant_order" action="{{route('variant.order')}}" method="post">
                            @csrf
                            <input type="hidden" name="variantData" id="variantData" value="" />
                        </form>
                        <div class="custom-dd-empty dd" id="nestable_list_3">
                            
                        <?php //print_r($html); ?>
                        </div>
                    </div>
                    <div class="col-sm-12 text-right">
                        <button class="btn btn-info waves-effect waves-light text-sm-right saveList">Save</button>
                    </div>

                </div> <!-- end row -->
            </div> <!-- end card-box -->
        </div>

        <div class="col-sm-4">
            <div class="card-box">
                <div class="row mb-2">
                    <div class="col-sm-8">
                        <h4 class="page-title">Brand</h4>
                        <p class="sub-header">
                            Drag & drop Brand to make child parent relation
                        </p>
                    </div>
                    <div class="col-sm-4 text-right">
                        <button class="btn btn-info waves-effect waves-light text-sm-right openCategoryModal"
                         dataid="0"><i class="mdi mdi-plus-circle mr-1"></i> Add
                        </button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <form name="variant_order" id="variant_order" action="{{route('variant.order')}}" method="post">
                            @csrf
                            <input type="hidden" name="variantData" id="variantData" value="" />
                        </form>
                        <div class="custom-dd-empty dd" id="nestable_list_3">
                            
                        <?php //print_r($html); ?>
                        </div>
                    </div>
                    <div class="col-sm-12 text-right">
                        <button class="btn btn-info waves-effect waves-light text-sm-right saveList">Save</button>
                    </div>

                </div> <!-- end row -->
            </div> <!-- end card-box -->
        </div>
    </div>
</div>
@include('backend.catalog.modals')
@endsection

@section('script')
    <script src="{{asset('assets/libs/nestable2/nestable2.min.js')}}"></script>
    <script src="{{asset('assets/js/pages/my-nestable.init.js')}}"></script>
    @include('backend.catalog.pagescript')

<script type="text/javascript">

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
        
        /*var jsonString = ;
         console.log(jsonString);
        $.ajax({
            type: "post",
            headers: {
                Accept: "application/json"
            },
            url: "{{route('category.order')}}",
            data: {data : jsonString}, 
            dataType : 'json',
            success: function(response) {
                console.log(response);
                location.reload();
            }
        });*/

    });
    
</script>
    
@endsection