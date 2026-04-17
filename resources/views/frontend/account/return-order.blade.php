@extends('layouts.store', ['title' => 'Return Orders'])
@section('css')
@endsection
@section('content')
<header>
    <div class="mobile-fix-option"></div>
    @if(isset($set_template)  && $set_template->template_id == 1)
        @include('layouts.store/left-sidebar-template-one')
        @elseif(isset($set_template)  && $set_template->template_id == 2)
        @include('layouts.store/left-sidebar')
        @else
        @include('layouts.store/left-sidebar-template-one')
        @endif
</header>
<section class="section-b-space order-page">
    <div class="container">
        <div class="row">
            <div class="col-lg-3">
                <div class="account-sidebar"><a class="popup-btn">my account</a></div>
                <div class="dashboard-left">
                    <div class="collection-mobile-back"><span class="filter-back"><i class="fa fa-angle-left" aria-hidden="true"></i> back</span></div>
                    @include('layouts.store/profile-sidebar')
                </div>
            </div>
            <div class="col-lg-9">
                <div class="dashboard-right">
                    <div class="dashboard">
                        <div class="page-title">
                            <h2>Return Order</h2>
                        </div>
                        <div class="welcome-msg">
                            <h5>Here are your for return product !</h5>
                        </div>
                        <div class="row">
                            <div class="container">
                                @foreach($order->vendors as $key => $vendor)    
                                @foreach($vendor->products as  $key => $product)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <input id="item_one{{$key}}" type="hidden" name="return_ids" value="{{ $product->id }}" required>
                                            <label class="order-items d-flex" for="item_one{{$key}}">
                                                <div class="item-img mx-1">
                                                    <img src="{{ $product->image_url }}" alt="">
                                                </div>
                                                <div class="items-name ml-2">
                                                    <h4 class="mt-0 mb-1"><b>{{ $product->product_name }}</b></h4>
                                                    <label><b>Quantity</b>: {{ $product->quantity }}</label>
                                                </div>
                                            </label>
                                        </div>
                                    </td>
                                   
                                    
                                </tr>
                                @endforeach  
                            @endforeach  
                              
                                <form id="return-upload-form" class="theme-form" action="javascript:void(0)" method="post" enctype="multipart/form-data">
                                        @csrf
                                    <input type="hidden" name="order_vendor_product_id" value="{{app('request')->input('return_ids')}}">
                                    <input type="hidden" name="file_set" id="files_set" value="0">
                                    <div id="remove_files">
                                    </div>
                                    <div class="row rating_files">
                                        <div class="col-12">
                                        <label>{{__('Upload Images')}}</label>
                                        </div>
                                        <div class="col-6 col-md-3 col-lg-2">
                                            <div class="file file--upload">
                                                <label for="input-file">
                                                    <span class="plus_icon"><i class="fa fa-plus" aria-hidden="true"></i></span>
                                                </label>
                                                <input id="input-file" type="file" name="images[]" accept="image/*"  multiple>
                                                
                                            </div>
                                        </div>
                                        <div class="col-10">
                                            <span class="row show-multiple-image-preview" id="thumb-output">
                                            </span>
                                        </div>
                                        
                                    </div>
                    
                                    
                                    <div class="row form-group">
                                        <div class="col-md-6">
                                            <label>{{__('Reason for return product')}}</label>
                                            <select class="form-control" name="reason" id="reason">
                                                @foreach ($reasons as $reason)
                                                    <option value="{{$reason->title}}">{{$reason->title}}</option>
                                                @endforeach
                                            </select>
                                        </div>    
                                    </div>
                                    <div class="form-group">
                                        <label>{{__('Comments (Optional)')}}:</label>
                                        <textarea class="form-control" name="coments" id="comments" cols="20" rows="4"></textarea>
                                    </div>
                                    <span class="text-danger" id="error-msg"></span>
                                    <span class="text-success" id="success-msg"></span>
                                    <button class="btn btn-solid mt-3" id="return_form_button">{{__('Request')}}</button>
                                </form>
                            </div>
                        </div>


                       
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>



@endsection

@section('script')


<script type="text/javascript">
    $(document).ready(function (e) {
        $('body').delegate('.local-img-del','click',function() {
            var img_id = $(this).data('id');
            $(this).prev().remove();
            $(this).remove();
            $("#"+img_id).remove();
        });
     
    $.ajaxSetup({
    headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });
    $(function() {
   
    
    $('#input-file').on('change', function() {
        $('#files_set').val(1); 
       $(this).closest("form").submit();
    });
    
    $('.server-img-del').on('click',function(e){
        var img_id = $(this).data('id');
        $(this).prev().remove();
         $(this).remove();
         $("#remove_files").append("<input type='hidden' name='remove_files[]' value='"+ img_id +"'>");
    });
    
    
    
    
    
    });    
    $('#return-upload-form').submit(function(e) {
    e.preventDefault();
    
    var formData = new FormData(this);
    let TotalImages = $('#input-file')[0].files.length; //Total Images
    let comments = $('#comments').val();
    if(TotalImages > 0)
    {
        
    let images = $('#input-file')[0];
    for (let i = 0; i < TotalImages; i++) {
    formData.append('images' + i, images.files[i]);
    }
    formData.append('TotalImages', TotalImages);
    formData.append('folder', '/return');
    
    $.ajax({
    type:'POST',
    url: "{{ route('uploadfile')}}",
    data: formData,
    cache:false,
    contentType: false,
    processData: false,
    beforeSend: function () {
        if(TotalImages > 0)
            $("#return_form_button").html('<i class="fa fa-spinner fa-spin fa-custom"></i> Loading').prop('disabled', true);
        },
    success: (data) => {
    if(data.status == 'Success')
        {   
            $("#input-file").val('');
            for(var i = 0; i < data.data.length; i++) {
                $("#remove_files").append("<input type='hidden' name='add_files[]' id='"+ data.data[i]['ids'] +"' = value='"+ data.data[i]['name'] +"'>");
                $("#thumb-output").append("<div class='col-6 col-md-3 col-lg-2'> <img class=\"update_pic\" src=\"" + data.data[i]['img_path'] + "\" />" +
                "<i class='fa fa-trash local-img-del' aria-hidden='true' data-id='"+ data.data[i]['ids'] +"'></i></div>");
            }
    
            $("#return_form_button").html('Request').prop('disabled', false);
        }else{
            $('#error-msg').text(data.message);
            $("#return_form_button").html('Request').prop('disabled', false);
        }
    },
    error: function(data){
        $('#error-msg').text(data.message);
        $("#return_form_button").html('Request').prop('disabled', false);
    }
    });
    }
    else
    {
    $.ajax({
    type:'POST',
    url: "{{ route('update.order.return')}}",
    data: formData,
    cache:false,
    contentType: false,
    processData: false,
    beforeSend: function () {
        if(TotalImages > 0 && comments.length > 0)
            $("#return_form_button").html('<i class="fa fa-spinner fa-spin fa-custom"></i> Loading').prop('disabled', true);
        },
    success: (data) => {
    if(data.status == 'Success')
        {
            if(TotalImages == 0  && comments.length == 0){
                $("#return_form_button").html('Request').prop('disabled', false);
            }
            else
            {
                $("#return_form_button").html('Request');
                var url = "{{route('user.orders',['pageType' => 'returnOrders'])}}";
                $(location).prop('href', url); 
            }
        }else{
            $('#error-msg').text(data.message);
            $("#return_form_button").html('Request').prop('disabled', false);
        }
    },
    error: function(data){
        $('#error-msg').text(data.message);
        $("#review_form_button").html('Request').prop('disabled', false);
    }
    });
    }
    
    
    
    });
    
    });
    </script>
    




@endsection