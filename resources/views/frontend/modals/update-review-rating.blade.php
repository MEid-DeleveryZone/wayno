<form id="review-upload-form" class="theme-form" action="javascript:void(0)" method="post" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="order_vendor_product_id" value="{{$order_vendor_product_id}}">
    <input type="hidden" name="file_set" id="files_set" value="0">
    <input type="hidden" name="only_set_radio" id="only_set_radio" value="0">
    <div id="remove_files">
    </div>
    
 
    <textarea class="form-control" maxlength="500" name="hidden_review" hidden>{{$rating_details->review??''}}</textarea>
    <div class="rating-form">
        <fieldset class="form-group">
            <legend class="form-legend">Rating:</legend> 
            <div class="form-item">

            <input id="rating-5" name="rating" type="radio" value="5" {{ $rating == 5 ? 'checked' : '' }}/>
                <label for="rating-5" data-value="5">
                    <span class="rating-star">
                        <i class="fa fa-star-o"></i>
                        <i class="fa fa-star"></i>
                    </span>
                    <span class="ir">5</span>
                </label>
                <input id="rating-4" name="rating" type="radio" value="4"  {{ $rating == 4 ? 'checked' : '' }}/>
                <label for="rating-4" data-value="4">
                    <span class="rating-star">
                        <i class="fa fa-star-o"></i>
                        <i class="fa fa-star"></i>
                    </span>
                    <span class="ir">4</span>
                </label>
                <input id="rating-3" name="rating" type="radio" value="3"  {{ $rating == 3 ? 'checked' : '' }}/>
                <label for="rating-3" data-value="3">
                    <span class="rating-star">
                        <i class="fa fa-star-o"></i>
                        <i class="fa fa-star"></i>
                    </span>
                    <span class="ir">3</span>
                </label>
                <input id="rating-2" name="rating" type="radio" value="2"  {{ $rating == 2 ? 'checked' : '' }}/>
                <label for="rating-2" data-value="2">
                    <span class="rating-star">
                        <i class="fa fa-star-o"></i>
                        <i class="fa fa-star"></i>
                    </span>
                    <span class="ir">2</span>
                </label>
                <input id="rating-1" name="rating" type="radio" value="1"  {{ $rating == 1 ? 'checked' : '' }}/>
                <label for="rating-1" data-value="1">
                    <span class="rating-star">
                        <i class="fa fa-star-o"></i>
                        <i class="fa fa-star"></i>
                    </span>
                    <span class="ir">1</span>
                </label>

                <div class="form-output">
                    ? / 5
                </div>

            </div>
        </fieldset>
    </div>

    <div class="row rating_files">
        <div class="col-12">
            <h4>Upload Images</h4>
        </div>
        <div class="col-6 col-md-3">
            <div class="file file--upload">
                <label for="input-file">
                    <span class="plus_icon"><i class="fa fa-plus" aria-hidden="true"></i></span>
                </label>
                <input id="input-file" type="file" name="images[]" accept="image/*"  multiple>
            </div>
        </div>

        <div class="col-9">
            <span class="row show-multiple-image-preview" id="thumb-output">
                @if(isset($rating_details->reviewFiles))
                @foreach ($rating_details->reviewFiles as $files)
                    <div class="col-6 col-md-4"><img class="update_pic" src="{{$files->file['proxy_url'].'300/300'.$files->file['image_path']}}">
                    <i class="fa fa-trash server-img-del" aria-hidden="true" data-id={{$files->id}}></i></div>
                @endforeach
                @endif
                
            </span>
        </div> 
        
    </div>

    <div class="form-row">
                                            
        <div class="col-md-12 mb-3">
            <label for="review">Review</label>
            <textarea class="form-control"
                placeholder="Wrire Your Testimonial Here"
                id="exampleFormControlTextarea1" rows="4"  name="review" maxlength="500">{{$rating_details->review??''}}</textarea>
        </div>
        <span class="text-danger" id="error-msg"></span>
        <span class="text-success" id="success-msg"></span>
        <div class="col-md-12">
            <button class="btn btn-solid buttonload" type="submit" id="review_form_button">Submit Your Review</button>
        </div>
        
    </div>
  </form>



<script type="text/javascript">
$(document).ready(function (e) {
    $('body').delegate('.local-img-del','click',function() {
        var img_id = $(this).data('id');
        $(this).prev().remove();
        $(this).remove();
        $("#"+img_id).remove();
    });


  $('input[type=radio][name=rating]').on('change', function() {
    $('#only_set_radio').val(1);
    $('.rating_files').show(); 
    $('.form-row').show();    
    $(this).closest("form").submit();
    });


    $('#review_form_button').on('click',function(e){
        $('#only_set_radio').val(0);
        $(this).closest("form").submit();
    });


$.ajaxSetup({
headers: {
'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
}
});
$(function() {
// Multiple images preview with JavaScript
var ShowMultipleImagePreview = function(input, imgPreviewPlaceholder) {
if (input.files) {
var filesAmount = input.files.length;
for (i = 0; i < filesAmount; i++) {
var reader = new FileReader();
reader.onload = function(event) {
var file = event.target;
$("#thumb-output").append("<div class='col-6 col-md-4'> <img class=\"update_pic\" src=\"" + event.target.result + "\" title=\"" + file.name + "\"/>" +
            "<i class='fa fa-trash local-img-del' aria-hidden='true' data-id='"+ i +"'></i></div>");
//$($.parseHTML('<img>')).addClass('col-6 col-md-3 col-lg-2 update_pic').attr('src', event.target.result).appendTo(imgPreviewPlaceholder);
}
reader.readAsDataURL(input.files[i]);
}
}
};

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
$('#review-upload-form').submit(function(e) {
e.preventDefault();

var formData = new FormData(this);
let TotalImages = $('#input-file')[0].files.length; //Total Images
let review = $('#exampleFormControlTextarea1').val();
if(TotalImages > 0)
{
   console.log(1); 
let images = $('#input-file')[0];
for (let i = 0; i < TotalImages; i++) {
formData.append('images' + i, images.files[i]);
}
formData.append('TotalImages', TotalImages);
formData.append('folder', '/review');

$.ajax({
type:'POST',
url: "{{ route('uploadfile')}}",
data: formData,
cache:false,
contentType: false,
processData: false,
beforeSend: function () {
    if(TotalImages > 0)
        $("#review_form_button").html('<i class="fa fa-spinner fa-spin fa-custom"></i> Loading').prop('disabled', true);
    },
success: (data) => {
if(data.status == 'Success')
    {   
        $("#input-file").val('');
        for(var i = 0; i < data.data.length; i++) {
            console.log(data.data[i]['name']);
            $("#remove_files").append("<input type='hidden' name='add_files[]' id='"+ data.data[i]['ids'] +"'  value='"+ data.data[i]['name'] +"'>");
            $("#thumb-output").append("<div class='col-6 col-md-4'> <img class=\"update_pic\" src=\"" + data.data[i]['img_path'] + "\" />" +
            "<i class='fa fa-trash local-img-del' aria-hidden='true' data-id='"+ data.data[i]['ids'] +"'></i></div>");
        }

        $("#review_form_button").html('Submit Your Review').prop('disabled', false);
    }else{
        $('#error-msg').text(data.message);
        $("#review_form_button").html('Submit Your Review').prop('disabled', false);
    }
},
error: function(data){
    $('#error-msg').text(data.message);
    $("#review_form_button").html('Submit Your Review').prop('disabled', false);
}
});
}
else
{
  
$.ajax({
type:'POST',
url: "{{ route('update.order.rating')}}",
data: formData,
cache:false,
contentType: false,
processData: false,
beforeSend: function () {
    if(TotalImages > 0 && review.length > 0)
        $("#review_form_button").html('<i class="fa fa-spinner fa-spin fa-custom"></i> Loading').prop('disabled', true);
    },
success: (data) => {
if(data.status == 'Success')
    {
        if(TotalImages == 0  && review.length == 0){
            $("#review_form_button").html('Submit Your Review').prop('disabled', false);
        }
        else
        {   
           $("#review_form_button").html('Submit Your Review');
           var set_rating = $('#only_set_radio').val();
           if(set_rating == 1){

           }else{
            var url = "{{route('user.orders',['pageType' => 'pastOrders'])}}";
            $(location).prop('href', url);
           }
        }
        $('#success-msg').text(data.message);
    }else{
        $('#error-msg').text(data.message);
        $("#review_form_button").html('Submit Your Review').prop('disabled', false);
    }
},
error: function(data){
    $('#error-msg').text(data.message);
    $("#review_form_button").html('Submit Your Review').prop('disabled', false);
}
});
}



});

});
</script>
