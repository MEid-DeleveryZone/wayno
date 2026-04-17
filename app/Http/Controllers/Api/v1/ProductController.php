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

class ProductController extends BaseController
{
    private $field_status = 2;
    use ApiResponser;
    /**
     * Get Company ShortCode
     *
     */
    public function productById_old(Request $request, $pid){
        $pvIds = array();
        $userid = Auth::user()->id;
        $langId = Auth::user()->language;
        $proVariants = ProductVariant::select('id', 'product_id')->where('product_id', $pid)->get();
        if($proVariants){
            foreach ($proVariants as $key => $value) {
                $pvIds[] = $value->id;
            }
        }
        $products = Product::with(['inwishlist' => function($qry) use($userid){
                        $qry->where('user_id', $userid);
                    },
                    'variant' => function($v){
                        $v->select('id', 'sku', 'product_id', 'title', 'quantity','price','barcode','tax_category_id');
                    },
                    'variant.vimage.pimage.image', 'vendor', 'media.image', 'related', 'upSell', 'crossSell',
                    'addOn' => function($q1) use($langId){
                        $q1->join('addon_sets as set', 'set.id', 'product_addons.addon_id');
                        $q1->join('addon_set_translations as ast', 'ast.addon_id', 'set.id');
                        $q1->select('product_addons.product_id', 'set.min_select', 'set.max_select', 'ast.title', 'product_addons.addon_id');
                        $q1->where('set.status', 1)->where('ast.language_id', $langId);
                    },
                    'variantSet' => function($z) use($langId){
                        $z->join('variants as vr', 'product_variant_sets.variant_type_id', 'vr.id');
                        $z->join('variant_translations as vt','vt.variant_id','vr.id');
                        $z->select('product_variant_sets.product_id', 'product_variant_sets.product_variant_id', 'product_variant_sets.variant_type_id', 'vr.type', 'vt.title');
                        $z->where('vt.language_id', $langId);
                    },
                    'variantSet.options' => function($zx) use($langId, $pvIds){
                        $zx->join('variant_option_translations as vt','vt.variant_option_id','variant_options.id')
                        ->select('variant_options.*', 'vt.title', 'pvs.product_variant_id', 'pvs.variant_type_id')
                        ->whereIn('pvs.product_variant_id', $pvIds)
                        ->where('vt.language_id', $langId);
                    },
                    'translation' => function($q) use($langId){
                        $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description');
                        $q->where('language_id', $langId);
                    },
                    'addOn.setoptions' => function($q2) use($langId){
                        $q2->join('addon_option_translations as apt', 'apt.addon_opt_id', 'addon_options.id');
                        $q2->select('addon_options.id', 'addon_options.title', 'addon_options.price', 'apt.title', 'addon_options.addon_id');
                        $q2->where('apt.language_id', $langId);
                    },
                    ])->select('id', 'sku', 'url_slug', 'weight', 'weight_unit', 'vendor_id', 'is_new', 'is_featured', 'is_physical', 'has_inventory', 'has_variant', 'sell_when_out_of_stock', 'requires_shipping', 'Requires_last_mile', 'averageRating')
                    ->where('id', $pid)
                    ->first();
        if(!$products){
            return response()->json(['error' => 'No record found.'], 404);
        }

        $clientCurrency = ClientCurrency::where('currency_id', Auth::user()->currency)->first();
        foreach ($products->variant as $key => $value) {
            $products->variant[$key]->multiplier = $clientCurrency->doller_compare;
        }

        foreach ($products->addOn as $key => $value) {
            foreach ($value->setoptions as $k => $v) {
                $v->multiplier = $clientCurrency->doller_compare;
            }
        }
        foreach ($products->variant as $key => $value) {
            if($products->sell_when_out_of_stock == 1){
                $value->stock_check = '1';
            }elseif($value->quantity > 0){
                $value->stock_check = '1';
            }else{
                $value->stock_check = 0;
            }
        }

        $response['products'] = $products;
        $response['relatedProducts'] = $this->metaProduct($langId, $clientCurrency->doller_compare, 'relate', $products->related);
        $response['upSellProducts'] = $this->metaProduct($langId, $clientCurrency->doller_compare, 'upSell', $products->upSell);
        $response['crossProducts'] = $this->metaProduct($langId, $clientCurrency->doller_compare, 'cross', $products->crossSell);
        unset($products->related);
        unset($products->upSell);
        unset($products->crossSell);
        $response['products'] = $products;
        return response()->json([
            'data' => $response,
        ]);
    }

    public function productById(Request $request, $pid)
    {
        try{
            $pvIds = array();
            $user = Auth::user();
            $langId = $user->language;
            $userid = $user->id;
            $product = Product::with(['inwishlist' => function($qry) use($userid){
                            $qry->where('user_id', $userid);
                        },
                        'category.categoryDetail', 'category.categoryDetail.translation' => function($q) use($langId){
                            $q->select('category_translations.name', 'category_translations.meta_title', 'category_translations.meta_description', 'category_translations.meta_keywords', 'category_translations.category_id')
                            ->where('category_translations.language_id', $langId);
                        },
                        'variant' => function($v){
                            $v->select('id', 'sku', 'product_id', 'title', 'quantity','price','barcode','tax_category_id')
                            ->groupBy('product_id'); // return first variant
                        },
                        'variant.media.pimage.image', 'vendor', 'media.image', 'related', 'upSell', 'crossSell',
                        'addOn' => function($q1) use($langId){
                            $q1->join('addon_sets as set', 'set.id', 'product_addons.addon_id');
                            $q1->join('addon_set_translations as ast', 'ast.addon_id', 'set.id');
                            $q1->select('product_addons.product_id', 'set.min_select', 'set.max_select', 'ast.title', 'product_addons.addon_id');
                            $q1->where('set.status', 1)->where('ast.language_id', $langId);
                        },
                        'variantSet' => function($z) use($langId){
                            $z->join('variants as vr', 'product_variant_sets.variant_type_id', 'vr.id');
                            $z->join('variant_translations as vt','vt.variant_id','vr.id');
                            $z->select('product_variant_sets.product_id', 'product_variant_sets.product_variant_id', 'product_variant_sets.variant_type_id', 'vr.type', 'vt.title');
                            $z->where('vt.language_id', $langId);
                        },
                        'variantSet.options' => function($zx) use($langId, $pvIds, $pid){
                            $zx->join('variant_option_translations as vt','vt.variant_option_id','variant_options.id')
                            ->select('variant_options.*', 'vt.title', 'pvs.product_variant_id', 'pvs.variant_type_id')
                            ->where('pvs.product_id', $pid)
                            ->where('vt.language_id', $langId);
                        },
                        'translation' => function($q) use($langId){
                            $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description');
                            $q->where('language_id', $langId);
                        },
                        'addOn.setoptions' => function($q2) use($langId){
                            $q2->join('addon_option_translations as apt', 'apt.addon_opt_id', 'addon_options.id');
                            $q2->select('addon_options.id', 'addon_options.title', 'addon_options.price', 'apt.title', 'addon_options.addon_id');
                            $q2->where('apt.language_id', $langId);
                        },
                        ])->select('id', 'sku', 'url_slug', 'weight', 'weight_unit', 'vendor_id', 'is_new', 'is_featured', 'is_physical', 'has_inventory', 'has_variant', 'sell_when_out_of_stock', 'requires_shipping', 'Requires_last_mile', 'averageRating')
                        ->where('id', $pid)
                        ->first();

            if(!$product){
                return response()->json(['error' => 'No record found.'], 404);
            }
            $product->vendor->is_vendor_closed = 0;
            if($product->vendor->show_slot == 0){
                if( ($product->vendor->slotDate->isEmpty()) && ($product->vendor->slot->isEmpty()) ){
                    $product->vendor->is_vendor_closed = 1;
                }else{
                    $product->vendor->is_vendor_closed = 0;
                    if($product->vendor->slotDate->isNotEmpty()){
                        $product->vendor->opening_time = Carbon::parse($product->vendor->slotDate->first()->start_time)->format('g:i A');
                        $product->vendor->closing_time = Carbon::parse($product->vendor->slotDate->first()->end_time)->format('g:i A');
                    }elseif($product->vendor->slot->isNotEmpty()){
                        $product->vendor->opening_time = Carbon::parse($product->vendor->slot->first()->start_time)->format('g:i A');
                        $product->vendor->closing_time = Carbon::parse($product->vendor->slot->first()->end_time)->format('g:i A');
                    }
                }
            }

            $product->is_wishlist = $product->category->categoryDetail->show_wishlist;
            $clientCurrency = ClientCurrency::where('currency_id', $user->currency)->first();
            // foreach ($product->variant as $key => $value) {

            //     $product->variant[$key]->multiplier = $clientCurrency->doller_compare;
            // }
            $addonList = array();
            foreach ($product->addOn as $key => $value) {
                foreach ($value->setoptions as $k => $v) {
                    if($v->price == 0){
                        $v->is_free = true;
                    }else{
                        $v->is_free = false;
                    }
                    $v->multiplier = $clientCurrency->doller_compare;
                }
            }
            $data_image = array();
            /*  if variant has image return variant images else product images  */
            $variant_id = 0;

            foreach ($product->variant as $key => $value) {
                $product->variant[$key]->multiplier = $clientCurrency->doller_compare;
                $variant_id = $value->id;
                $value->less_quantity = 0;
                if($product->sell_when_out_of_stock == 1){
                    $value->stock_check = '1';
                }elseif($value->quantity > 0){
                    $value->stock_check = '1';
                }else{
                    $value->stock_check = 0;
                }
                if($value->media && count($value->media) > 0){
                    foreach ($value->media as $media_key => $media_value) {
                        $data_image[$media_key]['product_variant_id'] = $media_value->product_variant_id;
                        $data_image[$media_key]['media_id'] = $media_value->product_image_id;
                        $data_image[$media_key]['is_default'] = 0;
                        $data_image[$media_key]['image'] = $media_value->pimage->image;
                    }
                }else{
                    foreach ($product->media as $media_key => $media_value) {
                        $data_image[$media_key]['product_id'] = $media_value->product_id;
                        $data_image[$media_key]['media_id'] = $media_value->media_id;
                        $data_image[$media_key]['is_default'] = $media_value->is_default;
                        $data_image[$media_key]['image'] = $media_value->image;
                    }
                }
                if($product->has_inventory ==1 && ($value->quantity <= 5 && $value->quantity > 0) ){
                    $value->less_quantity = 1;
                }

            }
            if($product->variantSet){
                foreach ($product->variantSet as $set_key => $set_value) {
                    foreach ($set_value->options as $opt_key => $opt_value) {
                        $opt_value->value = $opt_value->product_variant_id == $variant_id ? true : false;
                    }
                }
            }
            $product->product_media = $data_image;
            $response['products'] = $product;
            $response['relatedProducts'] = $this->metaProduct($langId, $clientCurrency->doller_compare, 'relate', $product->related);
            $response['upSellProducts'] = $this->metaProduct($langId, $clientCurrency->doller_compare, 'upSell', $product->upSell);
            $response['crossProducts'] = $this->metaProduct($langId, $clientCurrency->doller_compare, 'cross', $product->crossSell);
            /* group by in query return data only for key - 0 so using 0 */
            if(isset($product->variant[0]->media) && !empty($product->variant[0]->media)){
                unset($product->variant[0]->media);
            }
            unset($product->related);
            unset($product->media);
            unset($product->upSell);
            unset($product->crossSell);
            $response['products'] = $product;
            return response()->json([
                'data' => $response,
            ]);

        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }


    }

    public function metaProduct($langId, $multiplier, $for = 'relate', $productArray = [])
    {
        if(empty($productArray)){
            return $productArray;
        }

        $productIds = array();
        foreach ($productArray as $key => $value) {
            if($for == 'relate'){
                $productIds[] = $value->related_product_id;
            }
            if($for == 'upSell'){
                $productIds[] = $value->upsell_product_id;
            }
            if($for == 'cross'){
                $productIds[] = $value->cross_product_id;
            }
        }
        $products = Product::with(['media' => function($q){
                            $q->groupBy('product_id');
                        }, 'media.image',
                        'translation' => function($q) use($langId){
                        $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description')->where('language_id', $langId);
                        },
                        'variant' => function($q) use($langId){
                            $q->select('sku', 'product_id', 'quantity', 'price', 'barcode');
                            $q->groupBy('product_id');
                        },
                    ])->select('id', 'sku', 'averageRating')
                    ->whereIn('id', $productIds);

        $products = $products->get();
        if(!empty($products)){
            foreach ($products as $key => $value) {
                foreach ($value->variant as $k => $v) {
                    $value->variant[$k]->multiplier = $multiplier;
                }
            }
        }
        return $products;
    }

    /**
     * Display product variant data
     *
     * @return \Illuminate\Http\Response
     */
    public function getVariantData(Request $request, $sku)
    {
        try{
            if(!$request->has('variants')){
                return $this->errorResponse('Variants should not be empty.', 422);
            }
            if(!$request->has('options')){
                return $this->errorResponse('Options should not be empty.', 422);
            }
            $product = Product::with('category.categoryDetail')->where('sku', $sku)->first();
            if(!$product){
                return $this->errorResponse('No record found.', 404);
            }

            $langId = Auth::user()->language;
            $userid = Auth::user()->id;
            $clientCurrency = ClientCurrency::where('currency_id', Auth::user()->currency)->first();

            $pv_ids = array();

            foreach ($request->options as $key => $value) {
                $newIds = array();

                $product_variant = ProductVariantSet::where('variant_type_id', $request->variants[$key])
                    ->where('variant_option_id', $request->options[$key]);

                if (!empty($pv_ids)) {
                    $product_variant = $product_variant->whereIn('product_variant_id', $pv_ids);
                }
                $product_variant = $product_variant->where('product_id', $product->id)->get();

                if ($product_variant) {
                    foreach ($product_variant as $key => $value) {
                        $newIds[] = $value->product_variant_id;
                    }
                }
                $pv_ids = $newIds;
            }

            if(empty($pv_ids)){
                return $this->errorResponse('Invalid product sets or product has been removed.', 404, ['variant_empty'=>true]);
            }

            $variantData = ProductVariant::join('products as pro', 'product_variants.product_id', 'pro.id')
                        ->with(['wishlist', 'product.media.image', 'media.pimage.image', 'translation' => function($q) use($langId){
                            $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description');
                            $q->where('language_id', $langId);
                        },'wishlist' =>  function($q) use($userid){
                            $q->where('user_id', $userid);
                        }])->select('product_variants.id','product_variants.sku','product_variants.quantity', 'product_variants.price',  'product_variants.barcode', 'product_variants.product_id', 'pro.sku', 'pro.url_slug', 'pro.weight', 'pro.weight_unit', 'pro.vendor_id', 'pro.is_new', 'pro.is_featured', 'pro.is_physical', 'pro.has_inventory', 'pro.has_variant', 'pro.sell_when_out_of_stock', 'pro.requires_shipping', 'pro.Requires_last_mile', 'pro.averageRating')->where('product_variants.id', $pv_ids[0])->first();
            if($variantData->sell_when_out_of_stock == 1){
                $variantData->stock_check = '1';
            }elseif($variantData->quantity > 0){
                $variantData->stock_check = '1';
            }else{
                $variantData->stock_check = 0;
            }
            $variantData->less_quantity = 0;
            if($variantData->has_inventory ==1 && ($variantData->quantity <= 5 && $variantData->quantity > 0) ){
                $variantData->less_quantity = 1;
            }
            $data_image = array();
            $variantData->inwishlist = $variantData->wishlist;
            $variantData->is_wishlist = $product->category->categoryDetail->show_wishlist;
            if($variantData->media && count($variantData->media) > 0){
                foreach ($variantData->media as $media_key => $media_value) {
                    $data_image[$media_key]['product_variant_id'] = $media_value->product_variant_id;
                    $data_image[$media_key]['media_id'] = $media_value->product_image_id;
                    $data_image[$media_key]['image'] = $media_value->pimage->image;
                }
            }else{
                foreach ($variantData->product->media as $media_key => $media_value) {
                    $data_image[$media_key]['product_id'] = $media_value->product_id;
                    $data_image[$media_key]['media_id'] = $media_value->media_id;
                    $data_image[$media_key]['image'] = $media_value->image;
                }
            }
            $variantData->product_media = $data_image;

            if ($variantData) {
                $variantData->multiplier = $clientCurrency->doller_compare;
                $variantData->productPrice = $variantData->price * $clientCurrency->doller_compare;
            }
            unset($variantData->media);
            unset($variantData->product->media);
            return $this->successResponse($variantData);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }
}
