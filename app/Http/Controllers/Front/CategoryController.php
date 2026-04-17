<?php

namespace App\Http\Controllers\Front;
use DB;
use Auth;
use Session;
use Carbon\CarbonPeriod;
use DateTime;
use DateInterval;
use DateTimeZone;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Front\FrontController;
use App\Models\{Currency, Banner, Category, Brand, Product, Celebrity, ClientLanguage, Vendor, VendorCategory, ClientCurrency, ProductVariantSet, ServiceArea, UserAddress,Country,Cart,CartProduct,SubscriptionInvoicesUser,ClientPreference,LoyaltyCard,Order};
use Redirect;
class CategoryController extends FrontController{
    private $field_status = 2;
    
    /** 
     * Display product and vendor list By Category id
     *
     * @return \Illuminate\Http\Response
     */
    public function categoryProduct(Request $request, $domain = '', $slug = 0)
    {   
        //dd($request->pickup_location);
        $preferences = Session::get('preferences');
        $langId = Session::get('customerLanguage');
        $curId = Session::get('customerCurrency');
        $category = Category::with(['tags', 'brands.translation' => function($q) use($langId){
            $q->where('brand_translations.language_id', $langId);
        },
        'type'  => function($q){
            $q->select('id', 'title as redirect_to');
        },
        'childs.translation'  => function($q) use($langId){
            $q->select('category_translations.name', 'category_translations.meta_title', 'category_translations.meta_description', 'category_translations.meta_keywords', 'category_translations.category_id')
            ->where('category_translations.language_id', $langId);
        },
        'translation' => function($q) use($langId){
            $q->select('category_translations.name', 'category_translations.meta_title', 'category_translations.meta_description', 'category_translations.meta_keywords', 'category_translations.category_id')
            ->where('category_translations.language_id', $langId);
        }, 
        'allParentsAccount'])
        ->select('id', 'icon', 'image', 'slug', 'type_id', 'can_add_products', 'parent_id')
        ->where('slug', $slug)->firstOrFail();
        $category->translation_name = ($category->translation->first()) ? $category->translation->first()->name : $category->slug;
        foreach($category->childs as $key => $child){
            $child->translation_name = ($child->translation->first()) ? $child->translation->first()->name : $child->slug;
        }
       
        if( (isset($preferences->is_hyperlocal)) && ($preferences->is_hyperlocal == 1) && (isset($category->type_id)) && ($category->type_id != 4) && ($category->type_id != 5) ){
            $latitude = Session::get('latitude');
            $longitude = Session::get('longitude');
            $vendorType = Session::get('vendorType');
            $serviceAreaVendors = Vendor::select('id');
            if($vendorType){
                $serviceAreaVendors = $serviceAreaVendors->where($vendorType, 1);
            }
            $serviceAreaVendors = $serviceAreaVendors->whereHas('serviceArea', function($query) use($latitude, $longitude){
                    $query->select('vendor_id')
                    ->whereRaw("ST_Contains(POLYGON, ST_GEOMFROMTEXT('POINT(".$latitude." ".$longitude.")'))");
                })
                ->where('status', 1)->get();

            if($serviceAreaVendors->isNotEmpty()){
                foreach($serviceAreaVendors as $value){
                    $vendors[] = $value->id;
                }
            }
            $redirect_to = $category->type->redirect_to; 
            $page = (strtolower($redirect_to) != '') ? strtolower($redirect_to) : 'product';  
            // if(Session::has('vendors')){
            if( (isset($vendors)) && (count($vendors) > 0) ){
                Session::put('vendors', $vendors);
                //remake child categories array
                if($category->childs->isNotEmpty()){
                    $childArray = array();
                    foreach($category->childs as $key => $child){
                        $child_ID = $child->id;
                        $category_vendors = VendorCategory::where('category_id', $child_ID)->where('status', 1)->first();
                        if($category_vendors){
                            $childArray[] = $child;
                        }
                    }
                    $category->childs = collect($childArray);
                }
                //Abort route if category from route does not exist as per hyperlocal vendors
                if($page != 'pickup/delivery'){
                    $category_vendors = VendorCategory::select('vendor_id')->where('category_id', $category->id)->where('status', 1)->get();
                    if($category_vendors->isNotEmpty()){
                        $index = 1;
                        foreach($category_vendors as $key => $value){
                            if(in_array($value->vendor_id, $vendors)){
                                break;
                            }
                            elseif(count($category_vendors) == $index){
                                abort(404);
                            }
                            $index++;
                        }
                    }
                    else{
                        abort(404);                    
                    }
                }
              
            }else{
                // abort(404);
            }
        }

        $navCategories = $this->categoryNav($langId);
        
        if(isset($vendors)){
            $vendorIds = $vendors;
        }else{
            $vendorIds = array();
            $vendorList = Vendor::select('id', 'name')->where('status', '!=', $this->field_status)->get();
            if(!empty($vendorList)){
                foreach ($vendorList as $key => $value) {
                    $vendorIds[] = $value->id;
                }
            }
        }

        $variantSets = ProductVariantSet::with(['options' => function($zx) use($langId){
                            $zx->join('variant_option_translations as vt','vt.variant_option_id','variant_options.id');
                            $zx->select('variant_options.*', 'vt.title');
                            $zx->where('vt.language_id', $langId);
                        }
                    ])->join('variants as vr', 'product_variant_sets.variant_type_id', 'vr.id')
                    ->join('variant_translations as vt','vt.variant_id','vr.id')
                    ->select('product_variant_sets.product_id', 'product_variant_sets.product_variant_id', 'product_variant_sets.variant_type_id', 'vr.type', 'vt.title')
                    ->where('vt.language_id', $langId)
                    ->whereIn('product_variant_sets.product_id', function($qry) use($category){ 
                        $qry->select('product_id')->from('product_categories')
                            ->where('category_id', $category->id);
                        })
                    ->groupBy('product_variant_sets.variant_type_id')->get();
        $redirect_to = $category->type->redirect_to;
        $listData = $this->listData($langId, $category->id, $redirect_to);
        $page = (strtolower($redirect_to) != '') ? strtolower($redirect_to) : 'product';
        $np = $this->productList($vendorIds, $langId, $curId, 'is_new');
        foreach($np as $new){
            $new->translation_title = (!empty($new->translation->first())) ? $new->translation->first()->title : $new->sku;
            $new->variant_multiplier = (!empty($new->variant->first())) ? $new->variant->first()->multiplier : 1;
            $new->variant_price = (!empty($new->variant->first())) ? $new->variant->first()->price : 0;
        }
        $newProducts = ($np->count() > 0) ? array_chunk($np->toArray(), ceil(count($np) / 2)) : $np;
        if($page == 'pickup/delivery'){
            if(!Auth::user()){
                return redirect()->route('customer.login');
            }else{

                $user_addresses = UserAddress::get();
                $clientCurrency = ClientCurrency::where('currency_id', $curId)->first();
                $wallet_balance = Auth::user()->balanceFloat * ($clientCurrency->doller_compare ?? 1);
               
                return view('frontend.booking.index')->with(['clientCurrency' => $clientCurrency ,'wallet_balance' => $wallet_balance, 'user_addresses' => $user_addresses, 'navCategories' => $navCategories,'category' => $category]);
            }
        }elseif($page == 'on demand service'){ 
            $cartDataGet = $this->getCartOnDemand($request);
            if($request->step == 2 && empty($request->addons) && empty($request->dataset)){
                $addos = 0;
                foreach($cartDataGet['cartData'] as $cp){
                    if(count($cp->product->addOn) > 0)
                    $addos = 1;
               }
               if($addos == 1){
                $name = \Request::route()->getName();
                $new_url = $request->path()."?step=1&addons=1";
                return redirect($new_url);
               }else{
                $name = \Request::route()->getName();
                $new_url = $request->path()."?step=2&dataset=1";
                return redirect($new_url);
               }
            }
            if($request->step == 2 && empty($request->addons))
            {
                if ($request->session()->has('skip_addons')) {
                    $clientCurrency = ClientCurrency::where('currency_id', Session::get('customerCurrency'))->first();
                    return view('frontend.ondemand.index')->with(['clientCurrency' => $clientCurrency,'time_slots' =>  $cartDataGet['time_slots'], 'period' =>  $cartDataGet['period'] ,'cartData' => $cartDataGet['cartData'], 'addresses' => $cartDataGet['addresses'], 'countries' => $cartDataGet['countries'], 'subscription_features' => $cartDataGet['subscription_features'], 'guest_user'=>$cartDataGet['guest_user'],'listData' => $listData, 'category' => $category,'navCategories' => $navCategories]);
                }
                $request->session()->put('skip_addons', '1');
                $new_url = $request->path()."?step=2";
                return redirect($new_url);
            }
            
            $clientCurrency = ClientCurrency::where('currency_id', $curId)->first();
            return view('frontend.ondemand.index')->with(['clientCurrency' => $clientCurrency,'time_slots' =>  $cartDataGet['time_slots'], 'period' =>  $cartDataGet['period'] ,'cartData' => $cartDataGet['cartData'], 'addresses' => $cartDataGet['addresses'], 'countries' => $cartDataGet['countries'], 'subscription_features' => $cartDataGet['subscription_features'], 'guest_user'=>$cartDataGet['guest_user'],'listData' => $listData, 'category' => $category,'navCategories' => $navCategories]);
        }else{
            if(view()->exists('frontend/cate-'.$page.'s')){
                return view('frontend/cate-'.$page.'s')->with(['listData' => $listData, 'category' => $category, 'navCategories' => $navCategories, 'newProducts' => $newProducts, 'variantSets' => $variantSets]);
            }else{
                abort(404);
            }
        }
    }    

    public function listData($langId, $category_id, $type = ''){

        $pagiNate = (Session::has('cus_paginate')) ? Session::get('cus_paginate') : 12;
        
        if(strtolower($type) == 'vendor'){
            $vendorData = Vendor::with('products')->select('vendors.id', 'name', 'banner', 'address', 'order_pre_time', 'order_min_amount', 'logo', 'slug', 'latitude', 'longitude', 'vendor_templete_id');
            $vendorData = $vendorData->join('vendor_categories as vct', 'vct.vendor_id', 'vendors.id')->where('vct.category_id', $category_id)->where('vct.status', 1);
            $preferences= Session::get('preferences');
            if( (isset($preferences->is_hyperlocal)) && ($preferences->is_hyperlocal == 1) ){
                $vendors= $this->getServiceAreaVendors();
                $vendorData= $vendorData->whereIn('vct.vendor_id', $vendors);
            }
            $vendorData = $vendorData->where('vendors.status', '!=', $this->field_status)->paginate($pagiNate);
            foreach ($vendorData as $key => $value) {
                $value->vendorRating = $this->vendorRating($value->products);
                if (($preferences) && ($preferences->is_hyperlocal == 1)) {
                    $latitude = Session::get('latitude') ?? '';
                    $longitude = Session::get('longitude') ?? '';
                    $value = $this->getVendorDistanceWithTime( $value, $preferences,$latitude, $longitude);
                }
                $vendorCategories = VendorCategory::with('category.translation_one')->where('vendor_id', $value->id)->where('status', 1)->get();
                $categoriesList = '';
                foreach ($vendorCategories as $key => $category) {
                    if ($category->category) {
                        $categoriesList = $categoriesList . $category->category->translation ? $category->category->translation->first()->name : $category->category->slug;
                        if ($key !=  $vendorCategories->count() - 1) {
                            $categoriesList = $categoriesList . ', ';
                        }
                    }
                }
                $value->categoriesList = $categoriesList;
            }
            return $vendorData;
        }
        elseif(strtolower($type) == 'brand'){
            $brands = Brand::with('bc')
                ->select('id', 'image')->where('status', '!=', $this->field_status)->orderBy('position', 'asc')->paginate($pagiNate);
            foreach ($brands as $brand) {
                $brand->redirect_url = route('brandDetail', $brand->id);
            }
            return $brands;
        }
        elseif(strtolower($type) == 'celebrity'){
            $celebs = Celebrity::orderBy('name', 'asc')->paginate($pagiNate);
            return $celebs;
        }else{
            $user = Auth::user();
            if ($user) {
                $column = 'user_id';
                $value = $user->id;
            } else {
                $column = 'unique_identifier';
                $value = session()->get('_token');
            }
      
            $clientCurrency = ClientCurrency::where('currency_id', Session::get('customerCurrency'))->first();
            $vendors = array();
            if(Session::has('vendors')){
                $vendors = Session::get('vendors');
            }
            $products = Product::with(['vendor', 'media.image', 'category',
                        'translation' => function($q) use($langId){
                        $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description')->where('language_id', $langId);
                        },
                        'variant' => function($q) use($langId,$column,$value){
                            $q->select('sku', 'product_id', 'quantity', 'price', 'barcode','id');
                            $q->groupBy('product_id');
                        },'variant.checkIfInCart'])->select('products.id', 'products.sku', 'products.url_slug', 'products.weight_unit', 'products.weight', 'products.vendor_id', 'products.has_variant', 'products.has_inventory', 'products.sell_when_out_of_stock', 'products.requires_shipping', 'products.Requires_last_mile', 'products.averageRating', 'products.inquiry_only')->where('products.is_live', 1)->where('category_id', $category_id);
            if(count($vendors) > 0){
                $products = $products->whereIn('products.vendor_id', $vendors);
            }
            $products = $products->paginate($pagiNate);
            if(!empty($products)){
                foreach ($products as $key => $value) {
                    $value->translation_title = (!empty($value->translation->first())) ? $value->translation->first()->title : $value->sku;
                    $value->translation_description = (!empty($value->translation->first())) ? html_entity_decode(strip_tags($value->translation->first()->body_html)) : $value->sku;
                    $value->variant_multiplier = $clientCurrency ? $clientCurrency->doller_compare : 1;
                    $value->variant_price = (!empty($value->variant->first())) ? $value->variant->first()->price : 0;
                    // foreach ($value->variant as $k => $v) {
                    //     $value->variant[$k]->multiplier = $clientCurrency ? $clientCurrency->doller_compare : 1;
                    // }
                }
            }
            $listData = $products;
            return $listData;
        }
    }
    
    /** 
     * Display category->vendor->products list
     *
     * @return \Illuminate\Http\Response
     */
    public function categoryVendorProducts(Request $request, $domain = '', $slug1 = 0, $slug2 = 0)
    {
        // slug1 => category slug
        // slug2 => vendor slug
        $pagiNate = (Session::has('cus_paginate')) ? Session::get('cus_paginate') : 12;
        $preferences = Session::get('preferences');
        $langId = Session::get('customerLanguage');
        $curId = Session::get('customerCurrency');
        $navCategories = $this->categoryNav($langId);
        $clientCurrency = ClientCurrency::where('currency_id', $curId)->first();
        $category = Category::with(['tags', 'brands.translation' => function($q) use($langId){
            $q->where('brand_translations.language_id', $langId);
        },
        'type'  => function($q){
            $q->select('id', 'title as redirect_to');
        },
        'childs.translation'  => function($q) use($langId){
            $q->select('category_translations.name', 'category_translations.meta_title', 'category_translations.meta_description', 'category_translations.meta_keywords', 'category_translations.category_id')
            ->where('category_translations.language_id', $langId);
        },
        'translation' => function($q) use($langId){
            $q->select('category_translations.name', 'category_translations.meta_title', 'category_translations.meta_description', 'category_translations.meta_keywords', 'category_translations.category_id')
            ->where('category_translations.language_id', $langId);
        }, 
        'allParentsAccount'])
        ->select('id', 'icon', 'image', 'slug', 'type_id', 'can_add_products', 'parent_id')
        ->where('slug', $slug1)->firstOrFail();
        $category->translation_name = ($category->translation->first()) ? $category->translation->first()->name : $category->slug;
        foreach($category->childs as $key => $child){
            $child->translation_name = ($child->translation->first()) ? $child->translation->first()->name : $child->slug;
        }
        $vendor = Vendor::select('id', 'name')->where('slug', $slug2)->where('status', 1)->firstOrFail();

        $variantSets = ProductVariantSet::with(['options' => function($zx) use($langId){
            $zx->join('variant_option_translations as vt','vt.variant_option_id','variant_options.id');
            $zx->select('variant_options.*', 'vt.title');
            $zx->where('vt.language_id', $langId);
        }
        ])->join('variants as vr', 'product_variant_sets.variant_type_id', 'vr.id')
        ->join('variant_translations as vt','vt.variant_id','vr.id')
        ->select('product_variant_sets.product_id', 'product_variant_sets.product_variant_id', 'product_variant_sets.variant_type_id', 'vr.type', 'vt.title')
        ->where('vt.language_id', $langId)
        ->whereIn('product_variant_sets.product_id', function($qry) use($category){ 
            $qry->select('product_id')->from('product_categories')
                ->where('category_id', $category->id);
        })
        ->groupBy('product_variant_sets.variant_type_id')->get();
        $redirect_to = $category->type->redirect_to;
        $np = $this->productList([$vendor->id], $langId, $curId, 'is_new');
        foreach($np as $new){
            $new->translation_title = (!empty($new->translation->first())) ? $new->translation->first()->title : $new->sku;
            $new->variant_multiplier = (!empty($new->variant->first())) ? $new->variant->first()->multiplier : 1;
            $new->variant_price = (!empty($new->variant->first())) ? $new->variant->first()->price : 0;
        }
        $newProducts = ($np->count() > 0) ? array_chunk($np->toArray(), ceil(count($np) / 2)) : $np;

        $products = Product::with(['media.image',
            'translation' => function($q) use($langId){
                $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description')->where('language_id', $langId);
            },
            'variant' => function($q) use($langId){
                $q->select('sku', 'product_id', 'quantity', 'price', 'barcode');
                $q->groupBy('product_id');
            },
        ])
        ->select('id', 'sku', 'description', 'requires_shipping', 'sell_when_out_of_stock', 'url_slug', 'weight_unit', 'weight', 'vendor_id', 'has_variant', 'has_inventory', 'Requires_last_mile', 'averageRating', 'inquiry_only')
        ->where('is_live', 1)->where('category_id', $category->id)->where('vendor_id', $vendor->id)->paginate($pagiNate);
        if(!empty($products)){
            foreach ($products as $key => $value) {
                $value->translation_title = ($value->translation->isNotEmpty()) ? $value->translation->first()->title : $value->sku;
                $value->translation_description = ($value->translation->isNotEmpty()) ? html_entity_decode(strip_tags($value->translation->first()->body_html)) : '';
                $value->variant_multiplier = $clientCurrency ? $clientCurrency->doller_compare : 1;
                $value->variant_price = ($value->variant->isNotEmpty()) ? $value->variant->first()->price : 0;
            }
        }
        $listData = $products;
        return view('frontend/cate-products')->with(['listData' => $listData, 'category' => $category, 'navCategories' => $navCategories, 'newProducts' => $newProducts, 'variantSets' => $variantSets]);
    }

    /**
     * Product filters on category Page
     * @return \Illuminate\Http\Response
     */
    public function categoryFilters(Request $request, $domain = '', $cid = 0)
    {
        $langId = Session::get('customerLanguage');
        $curId = Session::get('customerCurrency');
        $setArray = $optionArray = array();
        $clientCurrency = ClientCurrency::where('currency_id', $curId)->first();

        if($request->has('variants') && !empty($request->variants)){
            $setArray = array_unique($request->variants);
        }

        $startRange = 0; $endRange = 20000;
        if($request->has('range') && !empty($request->range)){
            $range = explode(';', $request->range);
            $clientCurrency->doller_compare;
            $startRange = $range[0] * $clientCurrency->doller_compare;
            $endRange = $range[1] * $clientCurrency->doller_compare;
        }

        $multiArray = array();
        if($request->has('options') && !empty($request->options)){
            foreach ($request->options as $key => $value) {
                $multiArray[$request->variants[$key]][] = $value;
            }
        }
        $variantIds = $productIds = array();

        if(!empty($multiArray)){
            foreach ($multiArray as $key => $value) {
                $new_pIds = $new_vIds = array();
                $vResult = ProductVariantSet::join('product_categories as pc', 'product_variant_sets.product_id', 'pc.product_id')->select('product_variant_sets.product_variant_id', 'product_variant_sets.product_id')
                    ->where('product_variant_sets.variant_type_id', $key)
                    ->whereIn('product_variant_sets.variant_option_id', $value);

                if(!empty($variantIds)){
                    $vResult  = $vResult->whereIn('product_variant_sets.product_variant_id', $variantIds);
                }
                $vResult  = $vResult->groupBy('product_variant_sets.product_variant_id')->get();

                if($vResult){
                    foreach ($vResult as $key => $value) {
                        $new_vIds[] = $value->product_variant_id;
                        $new_pIds[] = $value->product_id;
                    }
                }
                $variantIds = $new_vIds;
                $productIds = $new_pIds;
            }
        }

        /*if($request->has('options') && !empty($request->options)){
            $optionArray = $request->options;
        }
        $variantSetData = ProductVariantSet::join('product_categories as pc', 'product_variant_sets.product_id', 'pc.product_id' )->select('product_variant_sets.*');
        if(!empty($setArray)){
            $variantSetData = $variantSetData->whereIn('product_variant_sets.variant_type_id', $setArray);
        }
        if(!empty($optionArray)){
            $variantSetData = $variantSetData->whereIn('product_variant_sets.variant_option_id', $optionArray);
        }
        echo $variantSetData = $variantSetData->groupBy('product_variant_sets.product_id')->toSql();die;

        dd($variantSetData->toArray());
        
        foreach ($variantSetData as $key => $value) {
            $variantIds[] = $value->product_variant_id;
            $productIds[] = $value->product_id;
        }*/
       // print_r($variantIds);die;

        $products = Product::with(['media.image',
                        'translation' => function($q) use($langId){
                        $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description')->where('language_id', $langId);
                        },
                        'variant' => function($q) use($langId, $variantIds){
                            $q->select('sku', 'product_id', 'quantity', 'price', 'barcode','id');
                            if(!empty($variantIds)){
                                $q->whereIn('id', $variantIds);
                            }
                            $q->groupBy('product_id');
                        },
                    ])->select('products.id', 'products.sku', 'products.url_slug', 'products.weight_unit', 'products.weight', 'products.vendor_id', 'products.has_variant', 'products.has_inventory', 'products.sell_when_out_of_stock', 'products.requires_shipping', 'products.Requires_last_mile', 'products.averageRating')
                    ->where('category_id', $cid)
                    ->where('products.is_live', 1)
                    ->whereIn('id', function($qr) use($startRange, $endRange){ 
                        $qr->select('product_id')->from('product_variants')
                            ->where('price',  '>=', $startRange)
                            ->where('price',  '<=', $endRange);
                        });
        if(!empty($productIds)){
            $products = $products->whereIn('id', $productIds);
        }
        
        if($request->has('brands') && !empty($request->brands)){
            $products = $products->whereIn('products.brand_id', $request->brands);
        }
        $pagiNate = (Session::has('cus_paginate')) ? Session::get('cus_paginate') : 12;
        
        $products = $products->paginate($pagiNate);

        if(!empty($products)){
            foreach ($products as $key => $value) {
                $value->translation_title = (!empty($value->translation->first())) ? $value->translation->first()->title : $value->sku;
                $value->translation_description = (!empty($value->translation->first())) ? strip_tags($value->translation->first()->body_html) : $value->sku;
                $value->variant_multiplier = $clientCurrency ? $clientCurrency->doller_compare : 1;
                $value->variant_price = (!empty($value->variant->first())) ? $value->variant->first()->price : 0;
                // foreach ($value->variant as $k => $v) {
                //     $value->variant[$k]->multiplier = $clientCurrency->doller_compare;
                // }
            }
        }
        $listData = $products;

        $returnHTML = view('frontend.ajax.productList')->with(['listData' => $listData])->render();
        return response()->json(array('success' => true, 'html'=>$returnHTML));
    }

    private function array_combinations($arrays)
    {
        $result = array();
        $arrays = array_values($arrays);
        $sizeIn = sizeof($arrays);
        $size = $sizeIn > 0 ? 1 : 0;
        foreach ($arrays as $array)
            $size = $size * sizeof($array);
        for ($i = 0; $i < $size; $i ++)
        {
            $result[$i] = array();
            for ($j = 0; $j < $sizeIn; $j ++)
                array_push($result[$i], current($arrays[$j]));
            for ($j = ($sizeIn -1); $j >= 0; $j --)
            {
                if (next($arrays[$j]))
                    break;
                elseif (isset ($arrays[$j]))
                    reset($arrays[$j]);
            }
        }
        return $result;
    }


    // ***********   getTimeSlotsForOndemand ************** /////////////////
    public function getTimeSlotsForOndemand(Request $request){

        $user = Auth::user();
        $timezone = $user->timezone ?? 'Asia/Kolkata';

     
        $dates = new DateTime("now", new DateTimeZone($timezone) );
        $today = $dates->format('Y-m-d');

        if($today < $request->cur_date){
            $curr_time = "00:00";
        }else{
            $daten = new DateTime("now", new DateTimeZone($timezone) );
            $curr_time = $daten->format('H:i');
    
        }
        

        $date = $request->cur_date;
       
        $start_time = $date." ".$curr_time;
        $end_time = $date." 23:59";
        $time_slots = $this->SplitTime($start_time, $end_time, "60");
        $cart_product_id = $request->cart_product_id??0;
        if ($request->ajax()) {
           return \Response::json(\View::make('frontend.ondemand.time-slots-for-date', array('time_slots' => $time_slots,'cart_product_id'=> $cart_product_id))->render());
        }
    }

}