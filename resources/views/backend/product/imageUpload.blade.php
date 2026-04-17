<div class="row">
    <div class="col-md-12">
    
        <div class="row">
            @foreach($images as $img)

            <div class="col-md-3 col-sm-4 col-12 mb-3">
                <div class="product-img-box">
                    <div class="form-group checkbox checkbox-success">
                        <input type="checkbox" id="image{{$img->id}}" class="imgChecks" imgId="{{$img->id}}"
                         @if(in_array($img->id, $variantImages)) checked @endif>
                        <label for="image{{$img->id}}">
                        <img src="{{$img->image->path['proxy_url'].'100/100'.$img->image->path['image_path']}}" alt="">
                        </label>
                    </div>
                </div>
            </div>
            @endforeach

            <div class="col-md-3 col-sm-4 col-12 mb-3 lastDiv">
                <input type="hidden" name="nothing" id="modalVariantId" value="{{$variant_id}}">
                <form method="post" name="imageUpload" id="modalImageForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="prodId" value="{{$productId}}">
                    <input type="hidden" name="variantId" value="{{$variant_id}}">
                    <div class="product-img-box">
                      <label class="file-input" for="uploadNew"><img src="{{asset('assets/images/default_image.png')}}" class="newImg"/> </label>
                      <input type="file" accept="image/*" accept="image/*" proId="" name="file[]" id="uploadNew" class="vimageNew" multiple="" style="display: none;">
                    </div>
                </form>
            </div>

            
            <!--<div class="col-md-3 col-sm-4 col-12 mb-3">
                <div class="product-img-box">
                    <div class="form-group checkbox checkbox-success">
                        <input type="checkbox" id="html2">
                        <label for="html2">
                        <img src="{{asset('assets/images/default_image.png')}}" alt="">
                        </label>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-4 col-12 mb-3">
            <div class="product-img-box">
                    <div class="form-group checkbox checkbox-success">
                        <input type="checkbox" id="html3">
                        <label for="html3">
                        <img src="{{asset('assets/images/default_image.png')}}" alt="">
                        </label>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-4 col-12 mb-3">
                 <div class="product-img-box">
                    <div class="form-group checkbox checkbox-success">
                        <input type="checkbox" id="html4">
                        <label for="html4">
                        <img src="{{asset('assets/images/default_image.png')}}" alt="">
                        </label>
                    </div>
                </div>
            </div> -->
        </div>

        <!--<div class="row">
            <div class="col-12 text-center">
                <div class="upload-btn-wrapper">
                    <button class="btn">Upload a file</button>
                    <input type="file" accept="image/*" accept="image/*" name="myfile" />
                </div>
            </div>
        </div> -->

    </div>
</div>