<ol class="dd-list">
    @forelse($categories  as $first_child)
        @if(isset($first_child['translation_one']))
            <li class="dd-item dd3-item" data-id="{{$first_child['id']}}">
                <div class="dd3-content"> 
                    <img class="rounded-circle mr-1" src="{{$first_child['icon']['proxy_url']}}30/30{{$first_child['icon']['image_path']}}">
                    {{$first_child['translation_one']['name']}} 
                    <span class="inner-div text-right">
                        <a class="action-icon" data-id="6" href="javascript:void(0)">
                            @if(in_array($first_child['id'], $vendorcategory))
                                <input type="checkbox" data-category_id="{{ $first_child['id'] }}" data-color="#43bee1" class="form-control activeCategory" data-plugin="switchery" checked="" {{$vendor->status == 1 ? '' : 'disabled'}}>
                            @else
                                <input type="checkbox" data-category_id="{{ $first_child['id'] }}" data-color="#43bee1" class="form-control activeCategory" data-plugin="switchery" {{$vendor->status == 1 ? '' : 'disabled'}}>
                            @endif
                        </a>
                    </span>
                </div>
            </li>
            @if(isset($first_child['children']))
                <x-category :categories="$first_child['children']" :vendorcategory="$vendorcategory" :vendor="$vendor"/>
            @endif
        @endif
    @empty
    @endforelse
</ol>