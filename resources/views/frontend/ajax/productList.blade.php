<div class="product-wrapper-grid">
    <div class="row margin-res">
      @if($listData->isNotEmpty())
        @foreach($listData as $key => $data)
            @if(($data->variant)->isNotEmpty())
                <?php $imagePath = $imagePath2 = '';
                $mediaCount = count($data->media);
                for ($i = 0; $i < $mediaCount && $i < 2; $i++) { 
                    if($i == 0){
                        $imagePath = $data->media[$i]->image->path['proxy_url'].'300/300'.$data->media[$i]->image->path['image_path'];
                    }
                    $imagePath2 = $data->media[$i]->image->path['proxy_url'].'300/300'.$data->media[$i]->image->path['image_path'];
                } ?>
                <div class="col-xl-3 col-md-4 col-6 col-grid-box mt-3">
                    <a href="{{route('productDetail', $data->url_slug)}}" class="product-box scale-effect mt-0">
                        <div class="product-image p-0">
                            <img class="img-fluid blur-up lazyload" src="{{$imagePath}}" alt="">
                        </div>
                        <div class="media-body align-self-center">
                            <div class="inner_spacing w-100">
                                <h3 class="d-flex align-items-center justify-content-between">
                                    <label class="mb-0">{{ $data->translation_title }}</label>
                                    @if($client_preference_detail)
                                        @if($client_preference_detail->rating_check == 1)  
                                            @if($data->averageRating > 0)
                                                <span class="rating">{{ number_format($data->averageRating, 1, '.', '') }} <i class="fa fa-star text-white p-0"></i></span>
                                            @endif
                                        @endif
                                    @endif
                                </h3>
                                <h6 class="mt-0"><b>{{$data->vendor->name}}</b></h6>
                                @if (strlen($data->translation_description) >= 65)
                                    <p title="{{$data->translation_description}}">{{ substr($data->translation_description, 0, 64)." ..." }}</p>
                                @else
                                    <p>{{ $data->translation_description }}</p>
                                @endif
                                @if($data['inquiry_only'] == 0)
                                    <h4 class="mt-1">{{Session::get('currencySymbol').' '.(number_format($data->variant_price * $data->variant_multiplier,2))}}</h4>
                                @endif
                            </div>
                        </div>
                    </a>
                </div>
            @endif
        @endforeach
	  @else
		<div class="col-xl-12 col-12 mt-4"><h5 class="text-center">No Product Found</h5></div>
	  @endif
    </div>
</div>

@if(count($listData))
<div class="pagination pagination-rounded justify-content-end mb-0">
    {{ $listData->links() }}
</div>
@endif
