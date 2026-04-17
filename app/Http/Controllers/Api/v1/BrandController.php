<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Api\v1\BaseController;
use Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\{User, Product, Category, ProductVariantSet, ProductVariant, ProductAddon, ProductRelated, ProductUpSell, ProductCrossSell, ClientCurrency, Vendor, Brand};
use Validation;
use DB;
use App\Http\Traits\ApiResponser;

class BrandController extends BaseController
{
    use ApiResponser;
    private $field_status = 2;
    /**
     * Get Company ShortCode
     *
     */
    public function productsByBrand(Request $request, $brandId = 0){
        try {
            if($brandId == 0 || $brandId < 0){
                return response()->json(['error' => 'No record found.'], 404);
            }
            $userid = Auth::user()->id;
            $langId = Auth::user()->language;
            $paginate = $request->has('limit') ? $request->limit : 12;
            $brand = Brand::with(['translation' => function($q) use($langId){
                            $q->select('title', 'brand_id');
                            $q->where('language_id', $langId);
                        }])->select('id', 'image')
                        ->where('status', '!=', 2)
                        ->where('id', $brandId)->first();
            if(!$brand){
                return response()->json(['error' => 'No record found.'], 200);
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
                        ->groupBy('product_variant_sets.variant_type_id')->get();
            $products = Product::with(['category.categoryDetail', 'inwishlist' => function($qry) use($userid){
                            $qry->where('user_id', $userid);
                        },
                        'media.image', 'translation' => function($q) use($langId){
                        $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description')->where('language_id', $langId);
                        },
                        'variant' => function($q) use($langId){
                            $q->select('sku', 'product_id', 'quantity', 'price', 'barcode');
                            $q->groupBy('product_id');
                        },
                    ])
                    ->select('id', 'sku', 'requires_shipping', 'sell_when_out_of_stock', 'url_slug', 'weight_unit', 'weight', 'brand_id', 'has_variant', 'has_inventory', 'Requires_last_mile', 'averageRating')
                    ->where('brand_id', $brandId)
                    ->where('is_live', 1)->paginate($paginate);
            
            $clientCurrency = ClientCurrency::where('currency_id', Auth::user()->currency)->first();
            if(!empty($products)){
                foreach ($products as $product) {
                    $product->is_wishlist = $product->category->categoryDetail->show_wishlist;
                    foreach ($product->variant as $k => $v) {
                        $product->variant[$k]->multiplier = $clientCurrency->doller_compare;
                    }
                }
            }
            $response['brand'] = $brand;
            $response['products'] = $products;
            $response['filterVariant'] = $variantSets;
            return $this->successResponse($response);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Product filters on category Page
     * @return \Illuminate\Http\Response
     */
    public function brandFilters(Request $request, $brandId = 0){
        try{
            if($brandId == 0 || $brandId < 0){
            return response()->json(['error' => 'No record found.'], 404);
        }
        $userid = Auth::user()->id;
        $langId = Auth::user()->language;
        $curId = Auth::user()->currency;
        $paginate = $request->has('limit') ? $request->limit : 12;
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
        $order_type = $request->has('order_type') ? $request->order_type : '';
        $products = Product::with(['category.categoryDetail','media.image', 'translation' => function($q) use($langId){
                        $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description')->where('language_id', $langId);
                    },'variant' => function($q) use($langId, $variantIds, $order_type){
                            $q->select('sku', 'product_id', 'quantity', 'price', 'barcode');
                            if(!empty($variantIds)){
                                $q->whereIn('id', $variantIds);
                            }
                            $q->groupBy('product_id');
                            if(!empty($order_type) && $order_type == 'low_to_high'){
                                $q->orderBy('price', 'asc');
                            }
                            if(!empty($order_type) && $order_type == 'high_to_low'){
                                $q->orderBy('price', 'desc');
                            }
                    }])->select('id', 'sku', 'url_slug', 'weight_unit', 'weight', 'vendor_id', 'has_variant', 'has_inventory', 'sell_when_out_of_stock', 'requires_shipping', 'Requires_last_mile', 'averageRating')
                    ->where('brand_id', $brandId)
                    ->where('is_live', 1)
                    ->whereIn('id', function($qr) use($startRange, $endRange){ 
                        $qr->select('product_id')->from('product_variants')->where('price',  '>=', $startRange)->where('price',  '<=', $endRange);
                    });
        if(!empty($productIds)){
            $products = $products->whereIn('id', $productIds);
        }
        if(!empty($order_type) && $request->order_type == 'rating'){
            $products = $products->orderBy('averageRating', 'desc');
        }
        $products = $products->paginate($paginate);
        if(!empty($products)){
            foreach ($products as $product) {
                $product->is_wishlist = $product->category->categoryDetail->show_wishlist;
                foreach ($product->variant as $k => $v) {
                    $product->variant[$k]->multiplier = $clientCurrency->doller_compare;
                }
            }
        }
        return $this->successResponse($products);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }
}
