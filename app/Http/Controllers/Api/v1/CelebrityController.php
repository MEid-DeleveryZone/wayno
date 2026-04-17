<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Api\v1\BaseController;
use Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\{User, Product, Category, ProductVariantSet, ProductVariant, ProductAddon, ProductRelated, ProductUpSell, ProductCrossSell, ClientCurrency, Vendor, Celebrity, ProductCelebrity};
use Validation;
use DB;
use App\Http\Traits\ApiResponser;

class CelebrityController extends BaseController
{
    private $field_status = 2;
    use ApiResponser;

    /**     *       Get Celebrity     *       */
    public function celebrityList($keyword = 'all')
    {
        try {
            if(empty($keyword) || strtolower($keyword) == 'all'){
                $celebrity = Celebrity::with('country')->whereNotIn('status', [2,3])
                            ->select('id', 'name', 'avatar', 'description', 'country_id')->get();
                return $this->successResponse($celebrity);
            }
            $chars = str_split($keyword);
            $celebrity = Celebrity::with('country')->select('id', 'name', 'avatar', 'description', 'country_id')
                            ->whereNotIn('status', [2,3])
                            ->where(function ($q) use ($chars) {
                                foreach ($chars as $key => $value) {
                                    if($key == 0){
                                        $q->where('name', 'LIKE', $value . '%');
                                    }else{
                                        $q->orWhere('name', 'LIKE', $value . '%');
                                    }
                                }
                            })->orderBy('name', 'asc')->get();
            return $this->successResponse($celebrity);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /***       Get Celebrity Products    **/
    public function celebrityProducts(Request $request, $cid = 0){
        try {
            $userid = Auth::user()->id;
            $paginate = $request->has('limit') ? $request->limit : 12;
            $clientCurrency = ClientCurrency::where('currency_id', Auth::user()->currency)->first();
            $langId = Auth::user()->language;
            $celebrity = Celebrity::whereNotIn('status', [2,3])
                            ->select('id', 'name', 'avatar', 'description', 'country_id')->where('id', $cid)->get();
            if(!$celebrity){
                return $this->errorResponse('Celebrity not found.', 404);
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
                        ->whereIn('product_variant_sets.product_id', function($qry) use($cid){ 
                            $qry->select('product_id')->from('product_celebrities')
                                ->where('celebrity_id', $cid);
                            })
                        ->groupBy('product_variant_sets.variant_type_id')->get();

            $products = Product::join('product_celebrities as pc', 'pc.product_id', 'products.id')
                        ->with(['category.categoryDetail','inwishlist' => function($qry) use($userid){
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
                ->select('products.id', 'products.sku', 'products.requires_shipping', 'products.sell_when_out_of_stock', 'products.url_slug', 'products.weight_unit', 'products.weight', 'products.vendor_id', 'products.has_variant', 'products.has_inventory', 'products.Requires_last_mile', 'products.averageRating', 'pc.celebrity_id')
                ->where('pc.celebrity_id', $cid)
                ->paginate($paginate);
            if(!empty($products)){
                foreach ($products as $key => $product) {
                    $product->is_wishlist = $product->category->categoryDetail->show_wishlist;
                    foreach ($product->variant as $k => $v) {
                        $product->variant[$k]->multiplier = $clientCurrency->doller_compare;
                    }
                }
            }
            $response['celebrity'] = $celebrity;
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
    public function celebrityFilters(Request $request, $cid = 0)
    {
        try{
            $user = Auth::user();
            $langId = $user->language;
            $setArray = $optionArray = array();
            $clientCurrency = ClientCurrency::where('currency_id', $user->currency)->first();

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
            $paginate = $request->has('limit') ? $request->limit : 12;

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
            $products = Product::join('product_celebrities as pc', 'pc.product_id', 'products.id')
                    ->with(['category.categoryDetail','variant.vimage.pimage.image', 'media.image', 'translation' => function($q) use($langId){
                        $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description')->where('language_id', $langId);
                        },
                        'variant' => function($q) use($langId, $variantIds, $order_type){
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
                        },
                    ])->select('products.id', 'products.sku', 'products.url_slug', 'products.weight_unit', 'products.weight', 'products.vendor_id', 'products.has_variant', 'products.has_inventory', 'products.sell_when_out_of_stock', 'products.requires_shipping', 'products.Requires_last_mile', 'products.averageRating')
                    ->where('pc.celebrity_id', $cid)
                    ->where('products.is_live', 1)
                    ->whereIn('id', function($qr) use($startRange, $endRange){ 
                        $qr->select('product_id')->from('product_variants')
                            ->where('price',  '>=', $startRange)
                            ->where('price',  '<=', $endRange);
                        });

            if(!empty($productIds)){
                $products = $products->whereIn('products.id', $productIds);
            }
            if(!empty($order_type) && $request->order_type == 'rating'){
                $products = $products->orderBy('products.averageRating', 'desc');
            }
            $products = $products->paginate($paginate);
            if(!empty($products)){
                foreach ($products as $key => $product) {
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