@if(!is_null($categorList) && count($categorList) > 0)
    @foreach($categorList as $node) 
        <li class="dd-item dd3-item" data-id="{{$node['id']}}">

            <div class="dd3-content">
                <img class="rounded-circle mr-1" src="{{$node['icon']['proxy_url'].'30/30'.$node['icon']['image_path']}}">{{$node["slug"]}}
                <span class="inner-div text-right">
                <?php 
                $status = 2; 
                $title = 'Delete'; $icon = 'mdi-delete';
                $askMessage = "return confirm('Are you sure? You want to delete category.')";
                
                /*if($node["status"] == 2){
                    $askMessage = "return confirm('Are you sure? You want to unblock category.')";
                    $status = 1; $icon = 'mdi-lock'; 
                    $title = 'Unblock';
                }*/
                ?>

                <a class="action-icon openCategoryModal" dataid="{{$node['id']}}" is_vendor="0" href="#"> <i class="mdi mdi-square-edit-outline"></i></a><a class="action-icon" dataid="{{$node['id']}}" title="'.$title.'" onclick="{{$askMessage}}" href="url('client/category/delete/'{{$node['id']}})"> <i class="mdi {{$icon}}"></i></a>
                  
              
                </span> 
            </div>
            @if(isset($node['children']) && count($node['children']) > 0)
                @include('backend.category.categoryTree', $node['children'])
                      
            @endif
        </li>
    @endforeach 
@endif