@php
$translation_name = ((!empty($product->translation->first()))?$product->translation->first()->title:$product->sku);
$breadcrumb = '<li class="breadcrumb-item align-items-center active" aria-current="page">'.(strlen($translation_name) > 26 ? substr($translation_name,0,22)."..." : $translation_name).'</li>';
$translation_name = ($category->translation->first()) ? $category->translation->first()->name : $category->slug;
$breadcrumb = '<li class="breadcrumb-item align-items-center"><a href="'.route("categoryDetail",$category->slug).'">'.$translation_name.'</a></li>'.$breadcrumb;
$subParent = $category->allParentsAccount;
do{
    if(strtolower($subParent->slug) != "root"){
        $translation_name = ($subParent->translation->first()) ? $subParent->translation->first()->name : $subParent->slug;
        $breadcrumb = '<li class="breadcrumb-item align-items-center"><a href="'.route("categoryDetail",$subParent->slug).'">'.$translation_name.'</a></li>'.$breadcrumb;
    }
    $subParent = $subParent->allParentsAccount;
} while(!empty($subParent));
$breadcrumb = '<li class="breadcrumb-item align-items-center"><a href="'.route("userHome").'">'. __("Home") .'</a></li>'.$breadcrumb;
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