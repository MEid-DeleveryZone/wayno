<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Front\FrontController;
use App\Models\{Currency, Category, Brand, Product, Celebrity, ClientLanguage, Vendor, VendorCategory, ClientCurrency, ProductVariantSet, ServiceArea};
use Illuminate\Http\Request;
use Session;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Hash;

class CelebrityController extends FrontController
{
    private $field_status = 2;

    /** 
     * Display product list By Celebrity slug
     *
     * @return \Illuminate\Http\Response
     */
    public function celebrityProducts(Request $request, $domain = '', $slug = 0)
    {
        $preferences = Session::get('preferences');
        $langId = Session::get('customerLanguage');
        $curId = Session::get('customerCurrency');
        $navCategories = $this->categoryNav($langId);
        $clientCurrency = ClientCurrency::where('currency_id', $curId)->first();
        $pagiNate = (Session::has('cus_paginate')) ? Session::get('cus_paginate') : 12;
        if( (isset($preferences->is_hyperlocal)) && ($preferences->is_hyperlocal == 1) ){
            if(Session::has('vendors')){
                $vendors = Session::get('vendors');
            }else{
                abort(404);
            }
        }
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
        $np = $this->productList($vendorIds, $langId, $curId, 'is_new');
        foreach($np as $new){
            $new->translation_title = (!empty($new->translation->first())) ? $new->translation->first()->title : $new->sku;
            $new->variant_multiplier = (!empty($new->variant->first())) ? $new->variant->first()->multiplier : 1;
            $new->variant_price = (!empty($new->variant->first())) ? $new->variant->first()->price : 0;
        }
        $newProducts = ($np->count() > 0) ? array_chunk($np->toArray(), ceil(count($np) / 2)) : $np;
        $celebrity = Celebrity::with(['products.product.variant', 'products.product' => function($query) use($vendorIds){
            $query->whereIn('products.vendor_id', $vendorIds)->paginate();
        }])        
        ->where('slug', $slug)->first();

        if( (isset($celebrity->products)) && (!empty($celebrity->products)) ){
            foreach ($celebrity->products as $key => $value) {
                if(!empty($value->product)){
                    $celebrity->products[$key] = $value->product;

                    $celebrity->products[$key]->translation_title = (!empty($value->product->translation->first())) ? $value->product->translation->first()->title : $value->product->sku;
                    $celebrity->products[$key]->variant_multiplier = $clientCurrency ? $clientCurrency->doller_compare : 1;
                    $celebrity->products[$key]->variant_price = (!empty($value->product->variant->first())) ? $value->product->variant->first()->price : 0;

                    // foreach ($value->product->variant as $k => $v) {
                    //     $value->product->variant[$k]->multiplier = $clientCurrency ? $clientCurrency->doller_compare : 1;
                    //     $celebrity->products[$key]->variant[$k] = $value->product->variant[$k];
                    // }
                }else{
                    unset($celebrity->products[$key]);
                }
            }
        }
        return view('frontend/celebrity-products')->with(['celebrity' => $celebrity, 'navCategories' => $navCategories, 'newProducts' => $newProducts]);
    }
}