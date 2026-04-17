<?php

namespace App\Http\Controllers\Front;
use Session;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Traits\ApiResponser;
use Illuminate\Support\Facades\Redis;
use App\Http\Controllers\Front\FrontController;
use App\Models\{Currency, Banner, Category, Brand, Product, ClientLanguage, Vendor, ClientCurrency, Category_translation, ProductTranslation};

class SearchController extends FrontController{
    use ApiResponser;
    public function postAutocompleteSearch(Request $request){
        $response = [];
        $keyword = $request->input('keyword');
        $language_id = Session::get('customerLanguage');
        $preferences = Session::get('preferences');
        $allowed_vendors = [];
        if(Session::has('vendors')){
            $allowed_vendors = Session::get('vendors');
        }
        $vendors = Vendor::select('id', 'name', 'logo','slug');
        if($preferences){
            if( (empty($latitude)) && (empty($longitude)) && (empty($selectedAddress)) ){
                $selectedAddress = $preferences->Default_location_name;
                $latitude = $preferences->Default_latitude;
                $longitude = $preferences->Default_longitude;
                Session::put('latitude', $latitude);
                Session::put('longitude', $longitude);
                Session::put('selectedAddress', $selectedAddress);
            }
            if(($preferences->is_hyperlocal == 1) && ($latitude) && ($longitude) ){
                $vendors = $vendors->whereHas('serviceArea', function($query) use($latitude, $longitude){
                    $query->select('vendor_id')
                    ->whereRaw("ST_Contains(POLYGON, ST_GEOMFROMTEXT('POINT(".$latitude." ".$longitude.")'))");
                });
            }
        }
        $vendors = $vendors->where(function ($q) use ($keyword) {
                        $q->where('name', 'LIKE', "%$keyword%");
                    })->where('status', '!=', 2)->get();
        foreach ($vendors as $vendor) {
            $vendor->redirect_url = route('vendorDetail', $vendor->slug);
            $vendor->image_url = $vendor->logo['proxy_url'].'80/80'.$vendor->logo['image_path'];
            $response[] = $vendor;
        }
        $brands = Brand::join('brand_translations as bt', 'bt.brand_id', 'brands.id')
                ->select('brands.id', 'bt.title as name', 'image')
                ->where('bt.title', 'LIKE', '%' . $keyword . '%')
                ->where('brands.status', '!=', '2')
                ->where('bt.language_id', $language_id)
                ->orderBy('brands.position', 'asc')->get();
        foreach ($brands as $brand) {
            $brand->redirect_url = route('brandDetail', $brand->id);
            $brand->image_url = $brand->image['proxy_url'].'80/80'.$brand->image['image_path'];
            $response[] = $brand;
        }
        $categories = Category::join('category_translations as cts', 'categories.id', 'cts.category_id')
                        ->leftjoin('types', 'types.id', 'categories.type_id')
                        ->select('categories.id', 'categories.icon', 'categories.image', 'categories.slug', 'categories.parent_id', 'cts.name', 'categories.warning_page_id', 'categories.template_type_id', 'types.title as redirect_to')
                        ->where('categories.id', '>', '1')
                        ->where('categories.is_visible', 1)
                        ->where('categories.status', '!=', 2)
                        ->where('categories.is_core', 1)
                        ->where('cts.language_id', $language_id)
                        ->where(function ($q) use ($keyword) {
                            $q->where('cts.name', ' LIKE', '%' . $keyword . '%')
                            ->orWhere('categories.slug', 'LIKE', '%' . $keyword . '%')
                            ->orWhere('cts.trans-slug', 'LIKE', '%' . $keyword . '%');
                        })->orderBy('categories.parent_id', 'asc')
                        ->orderBy('categories.position', 'asc')->get();
        foreach ($categories as $category) {
            $redirect_url = route('categoryDetail', $category->slug);
            $image_url = $category->image['proxy_url'].'80/80'.$category->image['image_path'];
            $response[] = ['id' => $category->id, 'name' => $category->name, 'image_url' => $image_url, 'redirect_url' => $redirect_url];
        }
        $products = Product::with('media')->join('product_translations as pt', 'pt.product_id', 'products.id')
        ->select('products.id', 'products.sku', 'products.url_slug', 'pt.title  as dataname', 'pt.body_html', 'pt.meta_title', 'pt.meta_keyword', 'pt.meta_description')
        ->where('pt.language_id', $language_id)
            ->where(function ($q) use ($keyword) {
                $q->where('products.sku', ' LIKE', '%' . $keyword . '%')->orWhere('products.url_slug', 'LIKE', '%' . $keyword . '%')->orWhere('pt.title', 'LIKE', '%' . $keyword . '%');
            })->where('products.is_live', 1);
        if( (isset($preferences->is_hyperlocal)) && ($preferences->is_hyperlocal == 1) ){
            $products = $products->whereIn('vendor_id', $allowed_vendors);
        }
        $products = $products->whereNull('deleted_at')->groupBy('products.id')->get();
        foreach ($products as $product) {
            $redirect_url = route('productDetail', $product->url_slug);
            $image_url = $product->media->first() ? $product->media->first()->image->path['proxy_url'].'80/80'.$product->media->first()->image->path['image_path'] : '';
            $response[] = ['id' => $product->id, 'name' => $product->dataname, 'image_url' => $image_url, 'redirect_url' => $redirect_url];
        }
        return $this->successResponse($response);
    }

    public function showSearchResults($domain="", $keyword){

        $response = [];
        $keyword = $keyword;
        $language_id = Session::get('customerLanguage');
        $preferences = Session::get('preferences');
        $vendors = Vendor::select('id', 'name', 'logo','slug');
        if($preferences){
            if( (empty($latitude)) && (empty($longitude)) && (empty($selectedAddress)) ){
                $selectedAddress = $preferences->Default_location_name;
                $latitude = $preferences->Default_latitude;
                $longitude = $preferences->Default_longitude;
                Session::put('latitude', $latitude);
                Session::put('longitude', $longitude);
                Session::put('selectedAddress', $selectedAddress);
            }
            if(($preferences->is_hyperlocal == 1) && ($latitude) && ($longitude) ){
                $vendors = $vendors->whereHas('serviceArea', function($query) use($latitude, $longitude){
                    $query->select('vendor_id')
                    ->whereRaw("ST_Contains(POLYGON, ST_GEOMFROMTEXT('POINT(".$latitude." ".$longitude.")'))");
                });
            }
        }
        $vendors = $vendors->where(function ($q) use ($keyword) {
                        $q->where('name', 'LIKE', "%$keyword%");
                    })->where('status', '!=', 2)->get();
        foreach ($vendors as $vendor) {
            $vendor->redirect_url = route('vendorDetail', $vendor->slug);
            $vendor->image_url = $vendor->logo['proxy_url'].'300/300'.$vendor->logo['image_path'];
            $response[] = $vendor;
        }
        $brands = Brand::join('brand_translations as bt', 'bt.brand_id', 'brands.id')
                ->select('brands.id', 'bt.title as name', 'image')
                ->where('bt.title', 'LIKE', '%' . $keyword . '%')
                ->where('brands.status', '!=', '2')
                ->where('bt.language_id', $language_id)
                ->orderBy('brands.position', 'asc')->get();
        foreach ($brands as $brand) {
            $brand->redirect_url = route('brandDetail', $brand->id);
            $brand->image_url = $brand->image['proxy_url'].'300/300'.$brand->image['image_path'];
            $response[] = $brand;
        }
        $categories = Category::join('category_translations as cts', 'categories.id', 'cts.category_id')
                        ->leftjoin('types', 'types.id', 'categories.type_id')
                        ->select('categories.id', 'categories.icon', 'categories.image', 'categories.slug', 'categories.parent_id', 'cts.name', 'categories.warning_page_id', 'categories.template_type_id', 'types.title as redirect_to')
                        ->where('categories.id', '>', '1')
                        ->where('categories.is_visible', 1)
                        ->where('categories.status', '!=', 2)
                        ->where('categories.is_core', 1)
                        ->where('cts.language_id', $language_id)
                        ->where(function ($q) use ($keyword) {
                            $q->where('cts.name', ' LIKE', '%' . $keyword . '%')
                            ->orWhere('categories.slug', 'LIKE', '%' . $keyword . '%')
                            ->orWhere('cts.trans-slug', 'LIKE', '%' . $keyword . '%');
                        })->orderBy('categories.parent_id', 'asc')
                        ->orderBy('categories.position', 'asc')->get();
        foreach ($categories as $category) {
            $redirect_url = route('categoryDetail', $category->slug);
            $image_url = $category->image['proxy_url'].'300/300'.$category->image['image_path'];
            $response[] = ['id' => $category->id, 'name' => $category->name, 'image_url' => $image_url, 'redirect_url' => $redirect_url];
        }
        $products = Product::with('media')->join('product_translations as pt', 'pt.product_id', 'products.id')
                    ->select('products.id', 'products.sku', 'pt.title  as dataname', 'pt.body_html', 'pt.meta_title', 'pt.meta_keyword', 'pt.meta_description')
                    ->where('pt.language_id', $language_id)
                    ->where(function ($q) use ($keyword) {
                        $q->where('products.sku', ' LIKE', '%' . $keyword . '%')->orWhere('products.url_slug', 'LIKE', '%' . $keyword . '%')->orWhere('pt.title', 'LIKE', '%' . $keyword . '%');
                    })->where('products.is_live', 1)->whereNull('deleted_at')->groupBy('products.id')->get();
        foreach ($products as $product) {
            $redirect_url = route('productDetail', $product->sku);
            $image_url = $product->media->first() ? $product->media->first()->image->path['proxy_url'].'300/300'.$product->media->first()->image->path['image_path'] : '';
            $response[] = ['id' => $product->id, 'name' => $product->dataname, 'image_url' => $image_url, 'redirect_url' => $redirect_url];
        }

        $language_id = Session::get('customerLanguage');
        $navCategories = $this->categoryNav($language_id);
        return view('frontend.searchResults')->with(['listData'=>$response, 'navCategories'=>$navCategories, 'keyword'=>$keyword]);
    }
}
