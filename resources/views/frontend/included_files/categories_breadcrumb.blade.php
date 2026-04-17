@php
$subParent = $category->allParentsAccount;
$breadcrumb = '<li class="breadcrumb-item align-items-center active" aria-current="page">'.$category->translation_name.'</li>';
do{
    if(strtolower($subParent->slug) == "root"){
        $breadcrumb = '<li class="breadcrumb-item align-items-center"><a href="'.route("userHome").'">Home</a></li>'.$breadcrumb;
    } else {
        $translation_name = ($subParent->translation->first()) ? $subParent->translation->first()->name : $subParent->slug;
        $breadcrumb = '<li class="breadcrumb-item align-items-center"><a href="'.route("categoryDetail",$subParent->slug).'">'.$translation_name.'</a></li>'.$breadcrumb;
    }
    $subParent = $subParent->allParentsAccount;
} while(!empty($subParent));
@endphp
<div class="breadcrumb-section bg-transparent pt-0">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <nav aria-label="breadcrumb" class="theme-breadcrumb">
                    <ol class="breadcrumb p-0">
                        {!! $breadcrumb !!}
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>