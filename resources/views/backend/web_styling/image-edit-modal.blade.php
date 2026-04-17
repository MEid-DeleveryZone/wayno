@php
    if(isset($banner->image['proxy_url']) && !empty($banner->image['proxy_url']))
    $img = $banner->image['proxy_url'].'1900/500'.$banner->image['image_path'];
    else
    $img = "{{asset('images/CabBANNER.jpg')}}";
@endphp


<div class="row"> 
    <div class="col-md-12">
        <div class="row">
            <div class="col-12" id="imageInput">
                <input type="hidden" name="id" value="{{$banner->id}}">
                @if(isset($banner->id))
                    <label>{{ __("Upload Background image") }}</label>
                    <input type="file" accept="image/*" data-plugins="dropify" name="image" class="dropify" data-default-file="{{$img}}" />
                    @else
                    <label>{{ __("Upload Background image") }}</label>
                    <input data-default-file="" type="file" data-plugins="dropify" name="image" accept="image/*" class="dropify"/>
                   

                    @endif
                <label class="logo-size text-right w-100">{{ __("Background Image Size") }} 1920x550</label>

                <span class="invalid-feedback" role="alert">
                    <strong></strong>
                </span>
            </div>
        </div>
        
    </div>
</div>