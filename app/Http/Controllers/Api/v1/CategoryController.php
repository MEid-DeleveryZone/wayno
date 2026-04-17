<?php

namespace App\Http\Controllers\Api\v1;

use DB;
use Validation;
use Carbon\Carbon;
use Exception;
// use Client;
use Illuminate\Http\Request;
use App\Http\Traits\ApiResponser;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\v1\BaseController;
use App\Models\{User, Product, Category, ProductVariantSet, ProductVariant, ProductAddon, ProductRelated, ProductUpSell, ProductCrossSell, ClientCurrency, Vendor, Brand, VendorCategory, ProductCategory, Client, ClientPreference};

class CategoryController extends BaseController
{
    private $field_status = 2;
    use ApiResponser;
    /**     * Get Company ShortCode     *     */
    public function categoryData(Request $request, $cid = 0)
    {
        try {
            $paginate = $request->has('limit') ? $request->limit : 12;
            $product_list = $request->has('product_list') ? $request->product_list : 'false';
            $mod_type = $request->has('type') ? $request->type : 'delivery';
            if ($cid == 0) {
                return response()->json(['error' => 'No record found.'], 404);
            }
            $userid = Auth::user()->id;
            $langId = Auth::user()->language;
            $category = Category::with([
                'tags', 'type'  => function ($q) {
                    $q->select('id', 'title as redirect_to');
                },
                'childs.translation'  => function ($q) use ($langId) {
                    $q->select('category_translations.name', 'category_translations.meta_title', 'category_translations.meta_description', 'category_translations.meta_keywords', 'category_translations.category_id')
                        ->where('category_translations.language_id', $langId);
                },
                'translation' => function ($q) use ($langId) {
                    $q->select('category_translations.name', 'category_translations.meta_title', 'category_translations.meta_description', 'category_translations.meta_keywords', 'category_translations.category_id')
                        ->where('category_translations.language_id', $langId);
                }
            ])
                ->select('id', 'icon', 'image', 'slug', 'type_id', 'can_add_products')
                ->where('id', $cid)
                ->where('status', 1) // Filter by active status
                ->first();
            $mode_of_service = "";
            if (!empty($category)) {
                if (!empty($category->products) && count($category->products) > 0) {
                    $mode_of_service = $category->products->first()->mode_of_service;
                }
            }
            $variantSets = ProductVariantSet::with(['options' => function ($zx) use ($langId) {
                $zx->join('variant_option_translations as vt', 'vt.variant_option_id', 'variant_options.id');
                $zx->select('variant_options.*', 'vt.title');
                $zx->where('vt.language_id', $langId);
            }])->join('variants as vr', 'product_variant_sets.variant_type_id', 'vr.id')
                ->join('variant_translations as vt', 'vt.variant_id', 'vr.id')
                ->select('product_variant_sets.product_id', 'product_variant_sets.product_variant_id', 'product_variant_sets.variant_type_id', 'vr.type', 'vt.title')
                ->where('vt.language_id', $langId)
                ->whereIn('product_variant_sets.product_id', function ($qry) use ($cid) {
                    $qry->select('product_id')->from('product_categories')->where('category_id', $cid);
                })->groupBy('product_variant_sets.variant_type_id')->get();
            if (!$category) {
                return response()->json(['error' => 'Category not found or inactive.'], 404);
            }
            $code = $request->header('code');
            $client = Client::where('code', $code)->first();
            $category->share_link = "https://" . $client->sub_domain . env('SUBMAINDOMAIN') . "/category/" . $category->slug;
            $response['category'] = $category;
            $response['filterData'] = $variantSets;
            $response['listData'] = $this->listData($langId, $cid, strtolower($category->type->redirect_to), $paginate, $userid, $product_list, $mod_type, $mode_of_service);
            return $this->successResponse($response);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function listData($langId, $category_id, $type = '', $limit = 12, $userid, $product_list, $mod_type, $mode_of_service = null)
    {
        if ($type == 'vendor' && $product_list == 'false') {
            $user = Auth::user();
            $preferences = ClientPreference::select('distance_to_time_multiplier', 'distance_unit_for_time', 'is_hyperlocal', 'Default_location_name', 'Default_latitude', 'Default_longitude')->first();
            $vendor_ids = [];
            $vendor_categories = VendorCategory::where('category_id', $category_id)->where('status', 1)->get();
            foreach ($vendor_categories as $vendor_category) {
                if (!in_array($vendor_category->vendor_id, $vendor_ids)) {
                    $vendor_ids[] = $vendor_category->vendor_id;
                }
            }
            $vendorData = Vendor::select('id', 'slug', 'name','logo','banner', 'show_slot', 'order_pre_time', 'order_min_amount', 'vendor_templete_id', 'latitude', 'longitude');
            if (($preferences) && ($preferences->is_hyperlocal == 1)) {
                $latitude = $user->latitude;
                $longitude = $user->longitude;
                if ((empty($latitude)) && (empty($longitude))) {
                    $latitude = (!empty($preferences->Default_latitude)) ? floatval($preferences->Default_latitude) : 0;
                    $longitude = (!empty($preferences->Default_latitude)) ? floatval($preferences->Default_longitude) : 0;
                }
                $vendorData = $vendorData->whereHas('serviceArea', function ($query) use ($latitude, $longitude) {
                    $query->select('vendor_id')
                        ->whereRaw("ST_Contains(polygon, ST_GeomFromText('POINT(" . $latitude . " " . $longitude . ")'))");
                });
            }
            $vendorData = $vendorData->where($mod_type, 1)->where('status', 1)->whereIn('id', $vendor_ids)->with('slot')->withAvg('product', 'averageRating')->paginate($limit);
            foreach ($vendorData as $vendor) {
                unset($vendor->products);
                $vendor->is_show_category = ($vendor->vendor_templete_id == 2 || $vendor->vendor_templete_id == 4 ) ? 1 : 0;
                $vendor->is_show_products_with_category = ($vendor->vendor_templete_id == 5) ? 1 : 0;
                $vendorCategories = VendorCategory::with('category.translation_one')->where('vendor_id', $vendor->id)->where('status', 1)->get();
                $categoriesList = '';
                foreach ($vendorCategories as $key => $category) {
                    if ($category->category) {
                        $categoriesList = $categoriesList . $category->category->translation_one->name ?? '';
                        if ($key !=  $vendorCategories->count() - 1) {
                            $categoriesList = $categoriesList . ', ';
                        }
                    }
                }
                $vendor->categoriesList = $categoriesList;
                
                if (($preferences) && ($preferences->is_hyperlocal == 1) && ($user->latitude) && ($user->longitude)) {
                    $vendor = $this->getVendorDistanceWithTime( $vendor, $preferences,$user->latitude, $user->longitude);
                }
                $vendor->is_vendor_closed = 0;
                if ($vendor->show_slot == 0) {
                    if (($vendor->slotDate->isEmpty()) && ($vendor->slot->isEmpty())) {
                        $vendor->is_vendor_closed = 1;
                    } else {
                        $vendor->is_vendor_closed = 0;
                        if ($vendor->slotDate->isNotEmpty()) {
                            $vendor->opening_time = Carbon::parse($vendor->slotDate->first()->start_time)->format('g:i A');
                            $vendor->closing_time = Carbon::parse($vendor->slotDate->first()->end_time)->format('g:i A');
                        } elseif ($vendor->slot->isNotEmpty()) {
                            $vendor->opening_time = Carbon::parse($vendor->slot->first()->start_time)->format('g:i A');
                            $vendor->closing_time = Carbon::parse($vendor->slot->first()->end_time)->format('g:i A');
                        }
                    }
                }
            }
            return $vendorData;
        } elseif ($type == 'vendor' && $product_list == 'true') {
            $vendor_ids = Vendor::where('status', 1)->pluck('id')->toArray();
            $clientCurrency = ClientCurrency::where('currency_id', Auth::user()->currency)->first();
            $products = Product::has('vendor')->with([
                'category.categoryDetail', 'category.categoryDetail.translation' => function ($q) use ($langId) {
                    $q->select('category_translations.name', 'category_translations.meta_title', 'category_translations.meta_description', 'category_translations.meta_keywords', 'category_translations.category_id')
                        ->where('category_translations.language_id', $langId);
                }, 'inwishlist' => function ($qry) use ($userid) {
                    $qry->where('user_id', $userid);
                },
                'media.image', 'translation' => function ($q) use ($langId) {
                    $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description')->where('language_id', $langId);
                },
                'variant' => function ($q) use ($langId) {
                    $q->select('sku', 'product_id', 'quantity', 'price', 'barcode');
                    // $q->groupBy('product_id');
                }, 'variant.checkIfInCartApp',
            ])->select('products.category_id', 'products.id', 'mode_of_service', 'products.sku', 'products.url_slug', 'products.weight_unit', 'products.weight', 'products.vendor_id', 'products.has_variant', 'products.has_inventory', 'products.sell_when_out_of_stock', 'products.requires_shipping', 'products.Requires_last_mile', 'products.averageRating')
                ->where('products.category_id', $category_id)->where('products.is_live', 1)->where('mode_of_service', $mode_of_service)->whereIn('products.vendor_id', $vendor_ids)->paginate($limit);
            if (!empty($products)) {
                foreach ($products as $key => $product) {
                    $product->is_wishlist = $product->category->categoryDetail->show_wishlist;
                    if ($product->variant->count() > 0) {
                        foreach ($product->variant as $k => $v) {
                            $product->variant[$k]->multiplier = $clientCurrency->doller_compare;
                        }
                    } else {
                        $product->variant =  $product;
                    }

                    $product->vendor->is_vendor_closed = 0;
                    if ($product->vendor->show_slot == 0) {
                        if (($product->vendor->slotDate->isEmpty()) && ($product->vendor->slot->isEmpty())) {
                            $product->vendor->is_vendor_closed = 1;
                        } else {
                            $product->vendor->is_vendor_closed = 0;
                            if ($product->vendor->slotDate->isNotEmpty()) {
                                $product->vendor->opening_time = Carbon::parse($product->vendor->slotDate->first()->start_time)->format('g:i A');
                                $product->vendor->closing_time = Carbon::parse($product->vendor->slotDate->first()->end_time)->format('g:i A');
                            } elseif ($product->vendor->slot->isNotEmpty()) {
                                $product->vendor->opening_time = Carbon::parse($product->vendor->slot->first()->start_time)->format('g:i A');
                                $product->vendor->closing_time = Carbon::parse($product->vendor->slot->first()->end_time)->format('g:i A');
                            }
                        }
                    }
                }
            }
            return $products;
        } elseif ($type == 'pickup/delivery' || $type == 'recovery') {
            $vendor_ids = [];
            $vendor_categories = VendorCategory::where('category_id', $category_id)->where('status', 1)->get();
            foreach ($vendor_categories as $vendor_category) {
                if (!in_array($vendor_category->vendor_id, $vendor_ids)) {
                    $vendor_ids[] = $vendor_category->vendor_id;
                }
            }
            $vendorData = Vendor::select('id', 'name', 'banner', 'show_slot', 'order_pre_time', 'order_min_amount', 'vendor_templete_id')->where('status', '!=', $this->field_status)->whereIn('id', $vendor_ids)->with('slot', 'products')->paginate($limit);
            // $avgRating = $vendorData->products->avg('averageRating');
            // $vendorData->avgRating = "fmwjkenf";
            foreach ($vendorData as $vendor) {
                unset($vendor->products);
                $vendor->is_show_category = ($vendor->vendor_templete_id == 1) ? 0 : 1;
            }
            return $vendorData;
        } elseif (strtolower($type) == 'subcategory') {
            $category_details = [];
            $category_list = Category::with([
                'tags', 'type'  => function ($q) {
                    $q->select('id', 'title as redirect_to');
                },
                'childs.translation'  => function ($q) use ($langId) {
                    $q->select('category_translations.name', 'category_translations.meta_title', 'category_translations.meta_description', 'category_translations.meta_keywords', 'category_translations.category_id')
                        ->where('category_translations.language_id', $langId);
                },
                'translation' => function ($q) use ($langId) {
                    $q->select('category_translations.name', 'category_translations.meta_title', 'category_translations.meta_description', 'category_translations.meta_keywords', 'category_translations.category_id')
                        ->where('category_translations.language_id', $langId);
                }
            ])->where('parent_id', $category_id)->get();
            foreach ($category_list as $category) {
                $category_details[] = array(
                    'id' => $category->id,
                    'name' => $category->translation ? $category->translation->first()->name : $category->slug,
                    'icon' => $category->icon,
                    'image' => $category->image
                );
            }
            return $category_details;
        } elseif ($type == 'product' || $type == 'Product' || $type == 'on demand service') {
            $vendor_ids = Vendor::where('status', 1)->pluck('id')->toArray();
            $clientCurrency = ClientCurrency::where('currency_id', Auth::user()->currency)->first();
            $products = Product::has('vendor')->with([
                'category.categoryDetail', 'category.categoryDetail.translation' => function ($q) use ($langId) {
                    $q->select('category_translations.name', 'category_translations.meta_title', 'category_translations.meta_description', 'category_translations.meta_keywords', 'category_translations.category_id')
                        ->where('category_translations.language_id', $langId);
                }, 'inwishlist' => function ($qry) use ($userid) {
                    $qry->where('user_id', $userid);
                },
                'addOn' => function ($q1) use ($langId) {
                    $q1->join('addon_sets as set', 'set.id', 'product_addons.addon_id');
                    $q1->join('addon_set_translations as ast', 'ast.addon_id', 'set.id');
                    $q1->select('product_addons.product_id', 'set.min_select', 'set.max_select', 'ast.title', 'product_addons.addon_id');
                    $q1->where('ast.language_id', $langId);
                },
                'addOn.setoptions' => function ($q2) use ($langId) {
                    $q2->join('addon_option_translations as apt', 'apt.addon_opt_id', 'addon_options.id');
                    $q2->select('addon_options.id', 'addon_options.title', 'addon_options.price', 'apt.title', 'addon_options.addon_id');
                    $q2->where('apt.language_id', $langId);
                },
                'media.image', 'translation' => function ($q) use ($langId) {
                    $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description')->where('language_id', $langId);
                },
                'variant' => function ($q) use ($langId) {
                    $q->select('id', 'sku', 'product_id', 'title', 'quantity', 'price', 'barcode');
                    // $q->groupBy('product_id');
                }, 'variant.checkIfInCartApp',
            ])->select('products.category_id', 'mode_of_service', 'products.id', 'products.sku', 'products.url_slug', 'products.weight_unit', 'products.weight', 'products.vendor_id', 'products.has_variant', 'products.has_inventory', 'products.sell_when_out_of_stock', 'products.requires_shipping', 'products.Requires_last_mile', 'products.averageRating')
                ->where('products.category_id', $category_id)->where('products.is_live', 1)->where('mode_of_service', $mode_of_service)->whereIn('products.vendor_id', $vendor_ids)->paginate($limit);
            if (!empty($products)) {
                foreach ($products as $key => $product) {
                    foreach ($product->addOn as $key => $value) {
                        foreach ($value->setoptions as $k => $v) {
                            if ($v->price == 0) {
                                $v->is_free = true;
                            } else {
                                $v->is_free = false;
                            }
                            $v->multiplier = $clientCurrency->doller_compare;
                        }
                    }

                    $product->vendor->is_vendor_closed = 0;
                    if ($product->vendor->show_slot == 0) {
                        if (($product->vendor->slotDate->isEmpty()) && ($product->vendor->slot->isEmpty())) {
                            $product->vendor->is_vendor_closed = 1;
                        } else {
                            $product->vendor->is_vendor_closed = 0;
                            if ($product->vendor->slotDate->isNotEmpty()) {
                                $product->vendor->opening_time = Carbon::parse($product->vendor->slotDate->first()->start_time)->format('g:i A');
                                $product->vendor->closing_time = Carbon::parse($product->vendor->slotDate->first()->end_time)->format('g:i A');
                            } elseif ($product->vendor->slot->isNotEmpty()) {
                                $product->vendor->opening_time = Carbon::parse($product->vendor->slot->first()->start_time)->format('g:i A');
                                $product->vendor->closing_time = Carbon::parse($product->vendor->slot->first()->end_time)->format('g:i A');
                            }
                        }
                    }

                    $p_id = $product->id;
                    $variantData = $product->with(['variantSet' => function ($z) use ($langId, $p_id) {
                        $z->join('variants as vr', 'product_variant_sets.variant_type_id', 'vr.id');
                        $z->join('variant_translations as vt', 'vt.variant_id', 'vr.id');
                        $z->select('product_variant_sets.product_id', 'product_variant_sets.product_variant_id', 'product_variant_sets.variant_type_id', 'vr.type', 'vt.title');
                        $z->where('vt.language_id', $langId);
                        $z->where('product_variant_sets.product_id', $p_id)->orderBy('product_variant_sets.variant_type_id', 'asc');
                    }, 'variantSet.options' => function ($zx) use ($langId, $p_id) {
                        $zx->join('variant_option_translations as vt', 'vt.variant_option_id', 'variant_options.id')
                            ->select('variant_options.*', 'vt.title', 'pvs.product_variant_id', 'pvs.variant_type_id')
                            ->where('pvs.product_id', $p_id)
                            ->where('vt.language_id', $langId);
                    }])->where('id', $p_id)->first();
                    $product->variantSet = $variantData->variantSet;
                    $product->is_wishlist = $product->category->categoryDetail->show_wishlist;
                    if ($product->variant->count() > 0) {
                        foreach ($product->variant as $k => $v) {
                            $product->variant[$k]->multiplier = $clientCurrency->doller_compare;
                        }
                    } else {
                        $product->variant =  $product;
                    }
                }
            }
            $listData = $products;
            return $listData;
        } else {
            $arr = array();
            return $arr;
        }
    }

    /**
     * Get category, vendor, and product details for a given category id
     */
    public function categoryVendorProductDetails(Request $request, $categoryId)
    {
        try {
            $langId = Auth::user() ? Auth::user()->language : 1;
            
            // Get category with active status
            $category = Category::with([
                'translation' => function ($q) use ($langId) {
                    $q->where('category_translations.language_id', $langId);
                }
            ])->where('status', 1) // Filter by active status
              ->find($categoryId);

            if (!$category) {
                return $this->errorResponse('Category not found or inactive.', 404);
            }

            // Get active vendors for this category
            $vendorIds = VendorCategory::where('category_id', $categoryId)
                ->where('status', 1) // Filter by active vendor-category relationship
                ->pluck('vendor_id');
                
            $vendors = Vendor::whereIn('id', $vendorIds)
                ->where('status', 1) // Filter by active vendor status
                //->select('id', 'name', 'slug', 'logo', 'banner', 'desc', 'address', 'phone_no', 'email', 'latitude', 'longitude', 'order_min_amount', 'order_pre_time', 'show_slot', 'vendor_templete_id')
                ->get();

            // Get active products for this category
            $products = Product::with([
                'media.image',
                'translation' => function ($q) use ($langId) {
                    $q->where('language_id', $langId);
                },
                'vendor' => function ($q) {
                    $q->where('status', 1); // Only include active vendors
                },
                'variant' => function ($q) {
                    $q->where('status', 1); // Only include active variants
                }
            ])->where('category_id', $categoryId)
              ->where('is_live', 1) // Filter by active product status
              ->whereHas('vendor', function ($q) {
                  $q->where('status', 1); // Ensure vendor is active
              })
              //->select('id', 'sku', 'url_slug', 'description', 'vendor_id', 'category_id', 'has_variant', 'has_inventory', 'sell_when_out_of_stock', 'requires_shipping', 'Requires_last_mile', 'averageRating', 'is_live', 'inquiry_only', 'mode_of_service')
              ->get();

            // Get translation data
            $translation = $category->translation->first();
            
            // Format the response
            $response = [
                'category' => $category, // This will include all fields and loaded relationships
                'vendors' => $vendors,   // All fields for each vendor
                'products' => $products, // All fields for each product, including loaded relationships
                'summary' => [
                    'total_vendors' => $vendors->count(),
                    'total_products' => $products->count(),
                    'category_status' => 'active'
                ]
            ];
            return $this->successResponse($response, 'Category details retrieved successfully');
            
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Product filters on category Page
     * @return \Illuminate\Http\Response
     */
    public function categoryFilters(Request $request, $cid = 0)
    {
        try {
            if ($cid == 0 || $cid < 0) {
                return response()->json(['error' => 'No record found.'], 404);
            }
            $langId = Auth::user()->language;
            $curId = Auth::user()->currency;
            $setArray = $optionArray = array();
            $clientCurrency = ClientCurrency::where('currency_id', $curId)->first();
            if ($request->has('variants') && !empty($request->variants)) {
                $setArray = array_unique($request->variants);
            }
            $startRange = 0;
            $endRange = 20000;
            if ($request->has('range') && !empty($request->range)) {
                $range = explode(';', $request->range);
                $clientCurrency->doller_compare;
                $startRange = $range[0] * $clientCurrency->doller_compare;
                $endRange = $range[1] * $clientCurrency->doller_compare;
            }
            $multiArray = array();
            if ($request->has('options') && !empty($request->options)) {
                foreach ($request->options as $key => $value) {
                    $multiArray[$request->variants[$key]][] = $value;
                }
            }
            $variantIds = $productIds = array();
            if (!empty($multiArray)) {
                foreach ($multiArray as $key => $value) {
                    $new_pIds = $new_vIds = array();
                    $vResult = ProductVariantSet::join('product_categories as pc', 'product_variant_sets.product_id', 'pc.product_id')->select('product_variant_sets.product_variant_id', 'product_variant_sets.product_id')
                        ->where('product_variant_sets.variant_type_id', $key)
                        ->whereIn('product_variant_sets.variant_option_id', $value);

                    if (!empty($variantIds)) {
                        $vResult  = $vResult->whereIn('product_variant_sets.product_variant_id', $variantIds);
                    }
                    $vResult  = $vResult->groupBy('product_variant_sets.product_variant_id')->get();

                    if ($vResult) {
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
            $products = Product::with([
                'category.categoryDetail', 'media.image',
                'translation' => function ($q) use ($langId) {
                    $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description')->where('language_id', $langId);
                },
                'variant' => function ($q) use ($langId, $variantIds) {
                    $q->select('sku', 'product_id', 'quantity', 'price', 'barcode');
                    if (!empty($variantIds)) {
                        $q->whereIn('id', $variantIds);
                    }
                    if (!empty($order_type) && $order_type == 'low_to_high') {
                        $q->orderBy('price', 'asc');
                    }
                    if (!empty($order_type) && $order_type == 'high_to_low') {
                        $q->orderBy('price', 'desc');
                    }
                    $q->groupBy('product_id');
                },
            ])->select('products.id', 'products.sku', 'products.url_slug', 'products.weight_unit', 'products.weight', 'products.vendor_id', 'products.has_variant', 'products.has_inventory', 'products.sell_when_out_of_stock', 'products.requires_shipping', 'products.Requires_last_mile', 'products.averageRating')
                ->where('category_id', $cid)
                ->where('products.is_live', 1)
                ->whereIn('id', function ($qr) use ($startRange, $endRange) {
                    $qr->select('product_id')->from('product_variants')
                        ->where('price',  '>=', $startRange)
                        ->where('price',  '<=', $endRange);
                });

            if (!empty($productIds)) {
                $products = $products->whereIn('id', $productIds);
            }

            if ($request->has('brands') && !empty($request->brands)) {
                $products = $products->whereIn('products.brand_id', $request->brands);
            }
            if (!empty($order_type) && $request->order_type == 'rating') {
                $products = $products->orderBy('averageRating', 'desc');
            }
            $paginate = $request->has('limit') ? $request->limit : 12;

            $products = $products->paginate($paginate);

            if (!empty($products)) {
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
