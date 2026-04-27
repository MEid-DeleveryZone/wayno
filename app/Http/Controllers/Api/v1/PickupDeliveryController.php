<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use App\Http\Controllers\Api\v1\BaseController;
use App\Http\Requests\OrderProductRatingRequest;
use App\Models\{VendorCategory, Category, ClientPreference, ClientCurrency, Vendor, ProductVariantSet, Product, LoyaltyCard, UserAddress, Order, OrderVendor, OrderProduct, VendorOrderStatus, Client, ClientLanguage, DeliveryCart, DeliveryCartImage, DeliveryCartTasks, Promocode, PromoCodeDetail, VendorOrderDispatcherStatus, Payment, serviceArea, PaymentOption, ItemCategory, OrderRejectingReason, DistanceSlaRule, DistanceSlaGroup};
use App\Http\Traits\ApiResponser;
use Exception;
use GuzzleHttp\Client as GCLIENT;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Client\OrderController as ClientOrderController;

class PickupDeliveryController extends BaseController
{

    use ApiResponser;

    #get vendors list based on location

    public function getVendorIdsByCategoryAndLocation(Request $request)
    {
        // Validate input
        if (!isset($request->categoryId, $request->locations) || empty($request->categoryId) || empty($request->locations)) {
            return response()->json([
                'status'  => 'Success',
                'message' => '',
                'data'    => []
            ], 200);
        }

        $categoryId = $request->categoryId;
        $locations  = (array) $request->locations;

        // Base vendor IDs filtered by category and active status
        $baseVendorIds = Vendor::query()
            ->select('vendors.id')
            ->leftJoin('vendor_categories', function ($join) {
                $join->on('vendor_categories.vendor_id', '=', 'vendors.id')
                    ->where('vendor_categories.status', 1);
            })
            ->leftJoin('categories', function ($join) {
                $join->on('categories.id', '=', 'vendor_categories.category_id')
                    ->where('categories.status', 1);
            })
            ->where('vendors.status', 1)
            ->when($categoryId, fn($q) => $q->where('categories.id', $categoryId))
            ->pluck('vendors.id')
            ->toArray();

        if (empty($baseVendorIds)) {
            return response()->json([
                'status'  => 'Success',
                'message' => '',
                'data'    => []
            ], 200);
        }

        // Load client preferences for fallback coordinates
        $preferences = ClientPreference::first();

        // Validate and prepare all locations first
        $validLocations = [];
        foreach ($locations as $loc) {
            $latitude  = $loc['latitude']  ?? ($preferences->Default_latitude ?? null);
            $longitude = $loc['longitude'] ?? ($preferences->Default_longitude ?? null);

            // Skip if coordinates are missing
            if ($latitude === null || $longitude === null) {
                continue;
            }

            $validLocations[] = [
                'latitude' => $latitude,
                'longitude' => $longitude
            ];
        }

        // If no valid locations, return empty result
        if (empty($validLocations)) {
            return response()->json([
                'status'  => 'Success',
                'message' => 'No valid locations provided',
                'data'    => []
            ], 200);
        }

        // Find vendors that serve ALL locations (both pickup and dropoff)
        $validVendors = collect();

        foreach ($baseVendorIds as $vendorId) {
            $vendor = Vendor::find($vendorId);
            if (!$vendor) continue;

            $serveAllLocations = true;

            // Check if vendor's service area contains ALL locations
            foreach ($validLocations as $location) {
                $latitude = $location['latitude'];
                $longitude = $location['longitude'];

                $pointWkt = "POINT($latitude $longitude)";

                $hasServiceArea = $vendor->serviceArea()
                    ->whereRaw("
                        ST_Contains(
                            ST_GeomFromText(ST_AsText(polygon), 4326),
                            ST_GeomFromText(?, 4326)
                        )
                    ", [$pointWkt])
                    ->exists();

                if (!$hasServiceArea) {
                    $serveAllLocations = false;
                    break; // If any location is not served, exclude this vendor
                }
            }

            // Only include vendor if it serves ALL locations
            if ($serveAllLocations) {
                $validVendors->push($vendor);
            }
        }

        return response()->json([
            'status'  => 'Success',
            'message' => $validVendors->isEmpty() ? 'Out of service area' : '',
            'data'    => $validVendors->values()
        ], 200);
    }





    # get all vehicles category by vendor

    public function productsByVendorInPickupDelivery(Request $request, $vid = 0)
    {
        try {
            if ($vid == 0) {
                return response()->json(['error' => __('No record found.')], 404);
            }
            $userid = Auth::user()->id;
            $paginate = $request->has('limit') ? $request->limit : 12;
            $clientCurrency = ClientCurrency::where('currency_id', Auth::user()->currency)->first();
            $preferences = ClientPreference::select('distance_to_time_multiplier', 'distance_unit_for_time', 'is_hyperlocal', 'Default_location_name', 'Default_latitude', 'Default_longitude')->first();
            $langId = Auth::user()->language;
            $vendor = Vendor::select(
                'id',
                'name',
                'desc',
                'logo',
                'banner',
                'address',
                'latitude',
                'longitude',
                'order_min_amount',
                'order_pre_time',
                'auto_reject_time',
                'dine_in',
                'takeaway',
                'delivery'
            )
                ->where('id', $vid)->whereNotNull('payment_options')->first();
            if (!$vendor) {
                return response()->json(['error' => __('No record found.')], 200);
            }
            if (($preferences) && ($preferences->is_hyperlocal == 1)) {
                if (!empty($request->locations)) {
                    foreach ($request->locations as $key => $value) {
                        $latitude = $value['latitude'];
                        $longitude = $value['longitude'];
                        $vendorData = Vendor::select('id', 'slug', 'name', 'desc', 'banner', 'order_pre_time', 'order_min_amount', 'vendor_templete_id', 'show_slot', 'latitude', 'longitude')->where('id', $vid);
                        if ((empty($latitude)) && (empty($longitude))) {
                            $address = $preferences->Default_location_name;
                            $latitude = (!empty($preferences->Default_latitude)) ? floatval($preferences->Default_latitude) : 0;
                            $longitude = (!empty($preferences->Default_latitude)) ? floatval($preferences->Default_longitude) : 0;
                            $request->request->add(['latitude' => $latitude, 'longitude' => $longitude, 'address' => $address]);
                        }
                        // $vendorData = $vendorData->whereHas('serviceArea', function ($query) use ($latitude, $longitude) {
                        //     $query->select('vendor_id')
                        //         ->whereRaw("ST_Contains(polygon, ST_GeomFromText('POINT(" . $latitude . " " . $longitude . ")'))");
                        // });
                        $vendorData = $vendorData->whereHas('serviceArea', function ($query) use ($latitude, $longitude) {
                            $query->select('vendor_id')
                                ->whereRaw(" ST_Contains(ST_GeomFromText(ST_AsText(polygon), 4326),ST_GeomFromText('POINT(" . $latitude . " " . $longitude . ")', 4326)) ");
                        });

                        $vendorData = $vendorData->with('slot', 'slotDate')->where('status', 1)->get();
                        if (!count($vendorData)) {
                            return response()->json(['error' => __('Out of service area')], 200);
                        }
                    }
                }
            }
            $productsQuery  = Product::with([
                'taxCategory.taxRate',
                'category.categoryDetail',
                'inwishlist' => function ($qry) use ($userid) {
                    $qry->where('user_id', $userid);
                },
                'media.image',
                'translation' => function ($q) use ($langId) {
                    $q->select('product_id', 'title', 'body_html', 'dimensions', 'weight_description', 'meta_title', 'meta_keyword', 'meta_description')->where('language_id', $langId);
                },
                'variant' => function ($q) use ($langId) {
                    $q->select('id', 'sku', 'product_id', 'quantity', 'price', 'barcode');
                    $q->groupBy('product_id');
                },
            ])->join('product_categories as pc', 'pc.product_id', 'products.id')
                ->whereNotIn('pc.category_id', function ($qr) use ($vid) {
                    $qr->select('category_id')->from('vendor_categories')
                        ->where('vendor_id', $vid)->where('status', 0);
                })
                ->select('products.id', 'products.sku', 'products.requires_shipping', 'products.sell_when_out_of_stock', 'products.url_slug', 'products.weight_unit', 'products.weight', 'products.vendor_id', 'products.has_variant', 'products.has_inventory', 'products.Requires_last_mile', 'products.averageRating', 'pc.category_id', 'products.tags', 'products.dimensions', 'products.sla_diff_emirates', 'products.sla_same_emirates', 'products.weight_description', 'products.tax_category_id')
                ->where('products.vendor_id', $vid)
                ->where('products.is_live', 1);
            $paginatedProducts = $productsQuery->paginate($paginate);
            $products = $paginatedProducts->items();
            if (!empty($products)) {
                $filteredProducts = [];
                $dispatcherResponse = $this->getDeliveryFeeDispatcher($request, $products);
                if (!empty($dispatcherResponse) && $dispatcherResponse['status'] === true) {
                    foreach ($products as $key => $product) {
                        if (isset($product->tags) && !empty($product->tags)) {
                            $total_price = $dispatcherResponse['productData'][$product->tags]['total'];
                            $is_sameEmirate = $dispatcherResponse['is_sameEmirate'];
                            $product->tax_amount = 0;
                            if (!empty($product->taxCategory->taxRate) && count($product->taxCategory->taxRate) > 0) {
                                foreach ($product->taxCategory->taxRate as $tckey => $tax_value) {
                                    $tax_rate   = round($tax_value->tax_rate);
                                    $tax_amount = $total_price * ($tax_rate / 100);
                                    $service_fee = $total_price - $tax_amount;
                                    $product->tax_amount = $tax_amount;
                                    $product->service_fee = $service_fee;
                                }
                            } else {
                                $product->service_fee = $total_price;
                            }
                            $product->tags_price = $total_price;
                            $product->is_sameEmirate = $is_sameEmirate;
                            $product->sla = $product['sla_diff_emirates'];
                            if ($is_sameEmirate == '1') {
                                $product->sla = $product['sla_same_emirates'];
                            }
                            $product->is_wishlist = $product->category->categoryDetail->show_wishlist;
                            // Override dimensions and weight_description with translated values if available
                            if ($product->translation && count($product->translation) > 0) {
                                $translation = $product->translation[0];
                                if (!empty($translation->dimensions)) {
                                    $product->dimensions = $translation->dimensions;
                                }
                                if (!empty($translation->weight_description)) {
                                    $product->weight_description = $translation->weight_description;
                                }
                            }
                            foreach ($product->variant as $k => $v) {
                                $product->variant[$k]->price = $product->tags_price;
                                $product->variant[$k]->multiplier = $clientCurrency->doller_compare;
                            }
                            if ($total_price > 0) {
                                $filteredProducts[] = $product;
                            }
                            $products = $filteredProducts;
                        }
                    }
                } else {
                    return response()->json(['error' => __('Service Not Available')], 200);
                }
            }
            $productsCollection = collect($products);

            $paginatedProducts->setCollection(collect($products));

            $loyalty_amount_saved = 0;
            $redeem_points_per_primary_currency = '';
            $loyalty_card = LoyaltyCard::where('status', '0')->first();
            if ($loyalty_card) {
                $redeem_points_per_primary_currency = $loyalty_card->redeem_points_per_primary_currency;
            }
            $order_loyalty_points_earned_detail = Order::where('user_id', $userid)->select(DB::raw('sum(loyalty_points_earned) AS sum_of_loyalty_points_earned'), DB::raw('sum(loyalty_points_used) AS sum_of_loyalty_points_used'))->first();
            if ($order_loyalty_points_earned_detail) {
                $loyalty_points_used = $order_loyalty_points_earned_detail->sum_of_loyalty_points_earned - $order_loyalty_points_earned_detail->sum_of_loyalty_points_used;
                if ($loyalty_points_used > 0 && $redeem_points_per_primary_currency > 0) {
                    $loyalty_amount_saved = $loyalty_points_used / $redeem_points_per_primary_currency;
                }
            }

            $response['vendor'] = $vendor;
            $response['products'] = $paginatedProducts;
            $response['loyalty_amount_saved'] = $loyalty_amount_saved ?? 0.00;
            return response()->json(['status', 'data' => $response]);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage() . '', 404);
        }
    }
    /**
     * list of vehicles details
     */
    /**     * Get Company ShortCode     *     */
    public function getListOfVehicles(Request $request, $cid = 0)
    {
        try {

            if ($cid == 0) {
                return response()->json(['error' => __('No record found.')], 404);
            }
            $userid = Auth::user()->id;
            $langId = Auth::user()->language;
            $category = Category::with([
                'tags',
                'type'  => function ($q) {
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
                ->where('id', $cid)->first();


            if (!$category) {
                return response()->json(['error' => 'No record found.'], 200);
            }
            $response['category'] = $category;
            $response['listData'] = $this->listData($langId, $cid, $category->type->redirect_to, $userid, $request);
            return $this->successResponse($response);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function listData($langId, $category_id, $type = '', $userid, $request)
    {
        if ($type == 'Pickup/Delivery') {
            $category_details = [];
            $deliver_charge = $this->getDeliveryFeeDispatcher($request);
            $deliver_charge = $deliver_charge ?? 0.00;
            $category_list = Category::where('parent_id', $category_id)->get();
            foreach ($category_list as $category) {
                $category_details[] = array(
                    'id' => $category->id,
                    'name' => $category->slug,
                    'icon' => $category->icon,
                    'image' => $category->image,
                    'price' => $deliver_charge
                );
            }
            return $category_details;
        } else {
            $arr = array();
            return $arr;
        }
    }


    # get delivery fee from dispatcher 
    public function getDeliveryFeeDispatcher($request, $products = [])
    {
        try {
            $dispatch_domain = $this->checkIfPickupDeliveryOn();
            if ($dispatch_domain && $dispatch_domain != false) {
                $agent_tags = [];
                foreach ($products as $key => $product) {
                    $agent_tags[] = $product->tags;
                }
                $postdata =  ['locations' => $request->locations, 'agent_tag' => $agent_tags ?? '', 'team_tag' => $dispatch_domain->client_code . '_' . $product->vendor_id ?? ''];
                $client = new GCLIENT([
                    'headers' => [
                        'personaltoken' => $dispatch_domain->pickup_delivery_service_key,
                        'shortcode' => $dispatch_domain->pickup_delivery_service_key_code,
                        'content-type' => 'application/json'
                    ]
                ]);
                $url = $dispatch_domain->pickup_delivery_service_key_url;
                $res = $client->post(
                    $url . '/api/get-delivery-fee',
                    ['form_params' => ($postdata)]
                );
                $response = json_decode($res->getBody(), true);
                //$response = [ 'message' => 'success','status' => true, 'total' => 22, 'is_sameEmirate' => 1 ];
                if ($response && $response['message'] == 'success') {
                    return $response;
                }
            }
        } catch (\Exception $e) {
        }
    }
    # check if last mile delivery on 
    public function checkIfPickupDeliveryOn()
    {
        $preference = ClientPreference::first();
        if ($preference->need_dispacher_ride == 1 && !empty($preference->pickup_delivery_service_key) && !empty($preference->pickup_delivery_service_key_code) && !empty($preference->pickup_delivery_service_key_url))
            return $preference;
        else
            return false;
    }

    /**
     * Get rider waiting time (ETA for pickup executive to reach pickup location) from dispatcher panel.
     * Dispatcher resolves zone from lat/long, finds most available rider in that zone, returns time to reach pickup.
     *
     * @param float $latitude Pickup latitude
     * @param float $longitude Pickup longitude
     * @param int|null $vendorId Vendor id for team_tag (optional)
     * @return array|null ['waiting_time_minutes' => int, ...] or null on failure
     */
    private function getRiderWaitingTimeFromDispatcher($latitude, $longitude, $vendorId = null)
    {
        try {
            $dispatch_domain = $this->checkIfPickupDeliveryOn();
            if (!$dispatch_domain || $dispatch_domain == false) {
                return null;
            }
            $team_tag = $dispatch_domain->client_code . '_' . ($vendorId ?? '0');
            $postdata = [
                'latitude'  => (float) $latitude,
                'longitude' => (float) $longitude,
                'team_tag'  => $team_tag,
            ];
            $client = new GCLIENT([
                'headers' => [
                    'personaltoken' => $dispatch_domain->pickup_delivery_service_key,
                    'shortcode'     => $dispatch_domain->pickup_delivery_service_key_code,
                    'content-type'  => 'application/json',
                ],
            ]);
            $url = rtrim($dispatch_domain->pickup_delivery_service_key_url, '/');
            $res = $client->post($url . '/api/pickup-eta', [
                'json' => $postdata,
            ]);
            $response = json_decode($res->getBody(), true);
            if ($response && $response['message'] === 'success') {
                // Normalize eta_minutes to waiting_time_minutes for consistency
                // while preserving all dispatcher response fields (zone_name, rider_name, etc.)
                if (isset($response['eta_minutes'])) {
                    $response['waiting_time_minutes'] = (int) $response['eta_minutes'];
                } elseif (isset($response['waiting_time_minutes'])) {
                    // Already has waiting_time_minutes, keep it
                } elseif (isset($response['rider_eta_minutes'])) {
                    $response['waiting_time_minutes'] = (int) $response['rider_eta_minutes'];
                } else {
                    // No time field found, return null
                    return null;
                }
                return $response;
            }
            return null;
        } catch (\Exception $e) {
            Log::warning('Dispatcher pickup-eta failed: ' . $e->getMessage());
            return null;
        }
    }




    /**
     * create order for booking
     */
    public function createOrder(Request $request)
    {

        DB::beginTransaction();
        try {
            $order_place = $this->orderPlaceForPickupDelivery($request);
            if ($order_place && $order_place['status'] == 200) {
                $data = [];
                $order = $order_place['data'];
                $request_to_dispatch = $this->placeRequestToDispatch($request, $order, $request->vendor_id);
                if ($request_to_dispatch && isset($request_to_dispatch['task_id']) && $request_to_dispatch['task_id'] > 0) {
                    DB::commit();
                    $order_place['data']['dispatch_traking_url'] = $request_to_dispatch['dispatch_traking_url'];
                    return  $order_place;
                } else {
                    DB::rollback();
                    return $request_to_dispatch;
                }
            } else {
                DB::rollback();
                return $order_place;
            }
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }


    // order place for pickup delivery 

    public function orderPlaceForPickupDelivery($request)
    {
        $total_amount = 0;
        $total_discount = 0;
        $taxable_amount = 0;
        $payable_amount = 0;
        $user = Auth::user();
        $request->address_id = $request->address_id ?? null;
        $request->payment_option_id = $request->payment_option_id ?? 1;
        if ($user) {
            $loyalty_amount_saved = 0;
            $redeem_points_per_primary_currency = '';
            $loyalty_card = LoyaltyCard::where('status', '0')->first();
            if ($loyalty_card) {
                $redeem_points_per_primary_currency = $loyalty_card->redeem_points_per_primary_currency;
            }
            $client_preference = ClientPreference::first();
            //Remove Email Verification - WAYN-289
            // if ($client_preference->verify_email == 1) {
            //     if ($user->is_email_verified == 0) {
            //         $data = [];
            //         $data['status'] = 404;
            //         $data['message'] =  'Your account is not verified.';
            //         return $data;
            //     }
            // }
            if ($client_preference->verify_phone == 1) {
                if ($user->is_phone_verified == 0) {
                    $data = [];
                    $data['status'] = 404;
                    $data['message'] =  'Your phone is not verified.';
                    return $data;
                }
            }
            //removed the validation from order creation API for special instruction since it is moved to create cart API 
            // if (!empty($request->client_comment) && strlen($request->client_comment) > 200) {
            //     $data = [
            //         'status' => 400,
            //         'message' => 'The special instructions should not exceed 200 characters.',
            //     ];
            //     return $data;
            // }
            $cart = Product::where('id', $request->product_id)->first();
            if ($cart) {
                $order_loyalty_points_earned_detail = Order::where('user_id', $user->id)->select(DB::raw('sum(loyalty_points_earned) AS sum_of_loyalty_points_earned'), DB::raw('sum(loyalty_points_used) AS sum_of_loyalty_points_used'))->first();
                if ($order_loyalty_points_earned_detail) {
                    $loyalty_points_used = $order_loyalty_points_earned_detail->sum_of_loyalty_points_earned - $order_loyalty_points_earned_detail->sum_of_loyalty_points_used;
                    if ($loyalty_points_used > 0 && $redeem_points_per_primary_currency > 0) {
                        $loyalty_amount_saved = $loyalty_points_used / $redeem_points_per_primary_currency;
                    }
                }
                $order = new Order;
                $order->user_id = $user->id;
                $order->order_number = generateOrderNo();
                $order->address_id = $request->address_id;
                $order->payment_option_id = $request->payment_option_id;
                $order->special_instruction = $request->client_comment ?? '';
                $order->vehicle_number = $request->vehicle_number ?? '';
                $order->delivery_cart_id = $request->id;
                $order->save();
                $clientCurrency = ClientCurrency::where('currency_id', $user->currency)->first();
                $vendor = Vendor::whereHas('product', function ($q) use ($request) {
                    $q->where('id', $request->product_id);
                })->select('*', 'id as vendor_id')->orderBy('created_at', 'asc')->first();
                $vendor_id = $vendor->id;
                $product = Product::where('id', $request->product_id)->with('pimage', 'variants', 'taxCategory.taxRate', 'addon')->first();
                $total_delivery_fee = 0;
                $delivery_fee = 0;
                $vendor_payable_amount = 0;
                $vendor_discount_amount = 0;
                $order_vendor = new OrderVendor;
                $order_vendor->status = 0;
                $order_vendor->user_id = $user->id;
                $order_vendor->order_id = $order->id;
                $order_vendor->vendor_id = $vendor->id;
                $order_vendor->save();
                $variant = $product->variants->where('product_id', $request->product_id)->first();
                $variant->price = $request->amount;
                $quantity_price = 0;
                $divider = (empty($clientCurrency->doller_compare) || $clientCurrency->doller_compare < 0) ? 1 : $clientCurrency->doller_compare;
                $divider = isset($divider) ? $divider : 1;
                $price_in_currency = $request->amount / $divider;
                $price_in_dollar_compare = $price_in_currency * $divider;
                $quantity_price = $price_in_dollar_compare * 1;
                $payable_amount = $payable_amount + $quantity_price;
                $vendor_payable_amount = $vendor_payable_amount + $quantity_price;
                $total_amount += $variant->price;
                $order_product = new OrderProduct;
                $order_product->order_vendor_id = $order_vendor->id;
                $order_product->order_id = $order->id;
                $order_product->price = $variant->price;
                $order_product->quantity = 1;
                $order_product->vendor_id = $vendor->id;
                $order_product->product_id = $product->id;
                //$order_product->sla = $request->is_same_emirates == 1 ? $product->sla_same_emirates : $product->sla_diff_emirates;
                $order_product->sla = $request->estimated_time;
                $order_product->dimensions = $product->dimensions;
                $order_product->weight = $product->weight_description;
                $order_product->created_by = null;
                $order_product->variant_id = $variant->id;
                $order_product->product_name = $product->title ?? $product->sku;
                if ($product->pimage) {
                    $order_product->image = $product->pimage->first() ? $product->pimage->first()->path : '';
                }
                $order_product->save();
                $coupon_id = null;
                $coupon_name = null;
                $actual_amount = $vendor_payable_amount;
                if ($request->coupon_id) {
                    $coupon = Promocode::find($request->coupon_id);
                    $coupon_id = $coupon->id;
                    $coupon_name = $coupon->name;
                    if ($coupon->promo_type_id == 2) {
                        $coupon_discount_amount = $coupon->amount;
                        $total_discount += $coupon_discount_amount;
                        $vendor_payable_amount -= $coupon_discount_amount;
                        $vendor_discount_amount += $coupon_discount_amount;
                    } else {
                        $coupon_discount_amount = ($quantity_price * $coupon->amount / 100);
                        $final_coupon_discount_amount = $coupon_discount_amount * $clientCurrency->doller_compare;
                        $total_discount += $final_coupon_discount_amount;
                        $vendor_payable_amount -= $final_coupon_discount_amount;
                        $vendor_discount_amount += $final_coupon_discount_amount;
                    }
                }
                $product_taxable_amount = 0;
                $product_payable_amount = 0;
                $vendor_taxable_amount = 0;
                if ($product->tax_category_id > 0) {
                    foreach ($product->taxCategory->taxRate as $tax_rate_detail) {
                        $rate = round($tax_rate_detail->tax_rate);
                        //$tax_amount = ($price_in_dollar_compare * $rate) / 100;
                        //$product_tax = $quantity_price * $rate / 100;
                        $product_tax = $vendor_payable_amount * $rate / 100;
                        $taxable_amount = $taxable_amount + $product_tax;
                        $payable_amount = $vendor_payable_amount + $product_tax;
                        $vendor_payable_amount = $payable_amount;
                    }
                }
                $vendor_taxable_amount += $taxable_amount;
                $order_vendor->coupon_id = $coupon_id;
                $order_vendor->coupon_code = $coupon_name;
                $order_vendor->order_status_option_id = 1;
                $order_vendor->subtotal_amount = $actual_amount;
                $order_vendor->payable_amount = $vendor_payable_amount;
                $order_vendor->taxable_amount = $vendor_taxable_amount;
                $order_vendor->discount_amount = $vendor_discount_amount;
                $order_vendor->payment_option_id = $request->payment_option_id;
                $vendor_info = Vendor::where('id', $vendor_id)->first();
                if ($vendor_info) {
                    if (($vendor_info->commission_percent) != null && $vendor_payable_amount > 0) {
                        $order_vendor->admin_commission_percentage_amount = round($vendor_info->commission_percent * ($vendor_payable_amount / 100), 2);
                    }
                    if (($vendor_info->commission_fixed_per_order) != null && $vendor_payable_amount > 0) {
                        $order_vendor->admin_commission_fixed_amount = $vendor_info->commission_fixed_per_order;
                    }
                }
                $order_vendor->save();
                $order_status = new VendorOrderStatus();
                $order_status->order_id = $order->id;
                $order_status->vendor_id = $vendor_id;
                $order_status->order_status_option_id = 1;
                $order_status->order_vendor_id = $order_vendor->id;
                $order_status->save();

                $loyalty_points_earned = LoyaltyCard::getLoyaltyPoint($loyalty_points_used, $payable_amount);
                $order->total_amount = $total_amount;
                $order->total_discount = $total_discount;
                $order->taxable_amount = $taxable_amount;
                if ($loyalty_amount_saved > 0) {
                    if ($payable_amount < $loyalty_amount_saved) {
                        $loyalty_amount_saved =  $payable_amount;
                        $loyalty_points_used = $payable_amount * $redeem_points_per_primary_currency;
                    }
                }
                $wallet_amount_used = 0;
                if ($user->balanceFloat > 0 && $request->use_wallet > 0) {
                    $wallet = $user->wallet;
                    $wallet_amount_used = $user->balanceFloat;
                    if ($wallet_amount_used > $payable_amount) {
                        $wallet_amount_used = $payable_amount;
                    }
                    $order->wallet_amount_used = $wallet_amount_used;
                    if ($wallet_amount_used > 0) {
                        $wallet->withdrawFloat($order->wallet_amount_used, ['Wallet has been <b>debited</b> for order number <b>' . $order->order_number . '</b>']);
                    }
                }

                $payable_amount = $payable_amount - $wallet_amount_used;
                $order->scheduled_date_time = $request->schedule_time ?? null;
                $order->total_delivery_fee = $total_delivery_fee;
                $order->loyalty_points_used = $loyalty_points_used;
                $order->loyalty_amount_saved = $loyalty_amount_saved;
                $order->payable_amount = $delivery_fee + $vendor_payable_amount - $loyalty_amount_saved;
                $order->loyalty_points_earned = $loyalty_points_earned['per_order_points'];
                $order->loyalty_membership_id = $loyalty_points_earned['loyalty_card_id'];
                $order->type = Category::where('id', $request->category_id)->value('type_id');

                if ($request->transaction_id && $request->transaction_id != '') {
                    $order->payment_status = 1;
                }
                $order->save();
                $orderMain = new OrderController();
                $orderMain->sendSuccessSMS($request, $order);
                $orderMain->sendSuccessEmail($request, $order);

                //sendAdminPanelPusherNotification();

                // Send push notification for order creation with 5 seconds delay using queue
                dispatch(new \App\Jobs\SendPushNotificationJob([$user->id], $order->id, $order->order_number, 1, $vendor_id))
                    ->delay(now()->addSeconds(5))->onConnection('database');

                if (($request->payment_option_id != 1) && ($request->payment_option_id != 2)) {
                    Payment::insert([
                        'date' => date('Y-m-d'),
                        'order_id' => $order->id,
                        'transaction_id' => $request->transaction_id,
                        'balance_transaction' => $order->payable_amount,
                    ]);
                }
            }

            $data = [];
            $data['status'] = 200;
            $data['message'] =  __('Order Placed');
            $data['data'] = $order;
            return $data;
        }
    }

    // place Request To Dispatch
    public function placeRequestToDispatch($request, $order, $vendor)
    {
        try {
            $dispatch_domain = $this->checkIfPickupDeliveryOn();
            $customer        = Auth::user();
            $wallet          = $customer->wallet;
            if ($dispatch_domain && $dispatch_domain != false) {
                $tasks = array();
                if ($request->payment_option_id == 1) {
                    $cash_to_be_collected     = 'Yes';
                    $payable_amount           = $request->amount;
                    $sub_vendor_payment_type  = 'COD';
                } else {
                    $cash_to_be_collected     = 'No';
                    $payable_amount           =  $request->amount;
                    $sub_vendor_payment_type  = 'PREPAID';
                }
                $dynamic                = uniqid($order->id . $vendor);
                $unique                 = Auth::user()->code;
                $client_do              = Client::where('code', $unique)->first();
                $call_back_url          = "https://" . $client_do->sub_domain . env('SUBMAINDOMAIN') . "/dispatch-pickup-delivery/" . $dynamic;
                $callback_url_update    = "https://" . $client_do->sub_domain . env('SUBMAINDOMAIN') . "/update-dispatch-order/";
                $tasks                  = array();
                $meta_data              = '';
                $code                   = $dispatch_domain->client_code . $vendor;
                $vendor_username        =   $code . "_royodispatch@dispatch.com";
                $team_tag               = $dispatch_domain->client_code . "_" . $vendor;
                $product                = Product::find($request->product_id);
                $item_category          = ItemCategory::find($request->item_cat_id);
                $order_agent_tag        = $product->tags ?? '';

                // Get delivery cart images if order has a delivery cart
                $delivery_cart_images = [];
                if ($order->delivery_cart_id) {
                    $delivery_cart = DeliveryCart::with('images')->find($order->delivery_cart_id);
                    if ($delivery_cart && $delivery_cart->images) {
                        $delivery_cart_images = $delivery_cart->images->map(function ($image) {
                            return $image->image_path;
                        })->toArray();
                    }
                }

                $postdata =  [
                    'customer_id'               => $customer->id ?? '0',
                    'customer_name'             => !empty($customer->name) ? $customer->name : 'Wayno Customer',
                    'customer_phone_number'     => $customer->phone_number ?? rand(111111, 11111),
                    'customer_email'            => $customer->email ?? '',
                    'recipient_phone'           => $request->phone_number ?? $customer->phone_number,
                    'recipient_email'           => $request->email ?? $customer->email,
                    'task_description'          => "Pickup & Delivery From order",
                    'allocation_type'           => 'u',
                    'task_type'                 => $request->task_type,
                    'schedule_time'             => $request->schedule_time ?? null,
                    'cash_to_be_collected'      => $payable_amount ?? 0.00, //delivery fee
                    'order_cost'                => $payable_amount ?? 0.00, //total amount
                    'barcode'                   => '',
                    'call_back_url'             => $call_back_url ?? null,
                    'callback_url_update'       => $callback_url_update ?? null,
                    'webhook_token'             => $dynamic ?? null,
                    'order_team_tag'            => $team_tag,
                    'order_agent_tag'           => $order_agent_tag,
                    'product_name'              => $product->title,
                    //'sla'                       => $request->is_same_emirates == 1 ? $product->sla_same_emirates : $product->sla_diff_emirates,
                    'due_date'                  => $request->estimated_time,
                    'sla'                       => __(':time on :date', [
                        'time' => date('g:i A', strtotime($request->estimated_time)),
                        'date' => date('d F Y', strtotime($request->estimated_time)),
                    ]),
                    'dimensions'                => $product->dimensions,
                    'weight'                    => $product->weight_description,
                    'task'                      => $request->tasks,
                    'order_time_zone'           => $request->order_time_zone ?? null,
                    'runrun_order_number'       => $order->order_number,
                    'special_instruction'       => $order->special_instruction,
                    'sub_vendor_payment_type'   => $sub_vendor_payment_type,
                    'sub_vendor_payment_amount' => $order->payable_amount,
                    'type'                      => $order->type,
                    'vehicle_number'            => $order->vehicle_number ?? null,
                    'item_category'             => $item_category->name ?? null,
                    'delivery_cart_images'      => $delivery_cart_images
                ];


                $client = new GClient([
                    'headers' => [
                        'personaltoken' => $dispatch_domain->pickup_delivery_service_key,
                        'shortcode'     => $dispatch_domain->pickup_delivery_service_key_code,
                        'content-type'  => 'application/json'
                    ]
                ]);

                $url        = $dispatch_domain->pickup_delivery_service_key_url;
                $res        = $client->post($url . '/api/task/create', ['form_params' => ($postdata)]);
                $response   = json_decode($res->getBody(), true);
                if ($response && isset($response['task_id']) && $response['task_id'] > 0) {
                    $dispatch_traking_url   = $response['dispatch_traking_url'] ?? '';
                    $up_web_hook_code       = OrderVendor::where(['order_id' => $order->id, 'vendor_id' => $vendor])
                        ->update(['web_hook_code' => $dynamic, 'dispatch_traking_url' => $dispatch_traking_url]);
                    $response['dispatch_traking_url'] = $dispatch_traking_url;
                    $or_ids         = OrderVendor::where(['order_id' => $order->id, 'vendor_id' => $vendor])->first();
                    $update_vendor  = VendorOrderStatus::updateOrCreate([
                        'order_id'               =>  $order->id,
                        'order_status_option_id' => 1,
                        'vendor_id'              =>  $vendor,
                        'order_vendor_id'        =>  $or_ids->id
                    ]);

                    OrderVendor::where('vendor_id', $vendor)->where('order_id', $order->id)->update(['order_status_option_id' => 1, 'dispatcher_status_option_id' => 1]);
                    $update = VendorOrderDispatcherStatus::updateOrCreate([
                        'dispatcher_id' => null,
                        'order_id' =>  $order->id,
                        'dispatcher_status_option_id' =>  1,
                        'vendor_id' =>  $vendor
                    ]);

                    if ($request->payment_option_id == 2) {
                        $wal =   $wallet->forceWithdrawFloat($order->payable_amount, ['Wallet has been <b>debited</b> for order number <b>' . $order->order_number . '</b>']);
                    }
                }
                return $response;
            }
        } catch (\Exception $e) {
            $data            = [];
            $data['status']  = 400;
            $data['message'] =  $e->getMessage();
            return $data;
        }
    }




    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function postPromoCodeList(Request $request)
    {
        try {
            $promo_codes = new \Illuminate\Database\Eloquent\Collection;
            $vendor_id = $request->vendor_id;
            $langId = $this->resolveLanguageId($request);
            $validator = $this->validatePromoCodeList();
            if ($validator->fails()) {
                foreach ($validator->errors()->toArray() as $error_key => $error_value) {
                    $errors['error'] = __($error_value[0]);
                    return $this->errorResponse($errors, 422);
                }
            }
            $vendor = Vendor::where('id', $request->vendor_id)->first();
            if (!$vendor) {
                return response()->json(['error' => 'Invalid vendor id.'], 404);
            }
            $now = Carbon::now()->toDateTimeString();
            $product_ids = Product::where('vendor_id', $request->vendor_id)->where('id', $request->product_id)->pluck("id");
            $cart_products = Product::with(['variant' => function ($q) {
                $q->select('sku', 'product_id', 'quantity', 'price', 'barcode');
            }])->where('vendor_id', $request->vendor_id)->where('id', $request->product_id)->get();
            //$total_minimum_spend = 0;
            // foreach ($cart_products as $cart_product) {
            //     $total_minimum_spend += $cart_product->variant->first() ? $cart_product->variant->first()->price * 1 : 0;
            // }
            $total_minimum_spend = $request->amount ?? 0;
            if ($product_ids) {
                $promo_code_details = PromoCodeDetail::whereIn('refrence_id', $product_ids->toArray())->pluck('promocode_id');
                if ($promo_code_details->count() > 0) {
                    $result1 = Promocode::whereIn('id', $promo_code_details->toArray())->whereDate('expiry_date', '>=', $now)->where('minimum_spend', '<=', $total_minimum_spend)->where('maximum_spend', '>=', $total_minimum_spend)->where('restriction_on', 0)->where('restriction_type', 0)->where('is_deleted', 0)->get();
                    $promo_codes = $promo_codes->merge($result1);
                }

                $vendor_promo_code_details = PromoCodeDetail::whereHas('promocode')->where('refrence_id', $vendor_id)->pluck('promocode_id');
                $result2 = Promocode::whereIn('id', $vendor_promo_code_details->toArray())->where('restriction_on', 1)->whereHas('details', function ($q) use ($vendor_id) {
                    $q->where('refrence_id', $vendor_id);
                })->where('restriction_on', 1)->where('is_deleted', 0)->where('minimum_spend', '<=', $total_minimum_spend)->where('maximum_spend', '>=', $total_minimum_spend)->whereDate('expiry_date', '>=', $now)->get();
                $promo_codes = $promo_codes->merge($result2);
            }
            if ($promo_codes->isNotEmpty()) {
                $promo_codes->load([
                    'translations' => function ($query) use ($langId) {
                        if (!empty($langId)) {
                            $query->where('language_id', $langId);
                        }
                    },
                    'primary' => function ($query) {
                        $query->select('promocode_translations.*');
                    }
                ]);

                $promo_codes = $promo_codes->map(function ($promocode) use ($langId) {
                    $translation = $promocode->relationLoaded('translations') ? $promocode->translations->first() : null;

                    if (!$translation && $promocode->relationLoaded('primary')) {
                        $translation = $promocode->primary;
                    }

                    $promocode->setAttribute('title', $translation->title ?? $promocode->name);
                    $promocode->setAttribute('short_desc', $translation->short_desc ?? ($promocode->short_desc ?? ''));
                    $promocode->setAttribute('translated_image', $translation ? $translation->image : $promocode->image);
                    $promocode->setAttribute('translation_language_id', $translation->language_id ?? $langId);

                    if ($promocode->relationLoaded('translations')) {
                        $promocode->unsetRelation('translations');
                    }

                    if ($promocode->relationLoaded('primary')) {
                        $promocode->unsetRelation('primary');
                    }

                    return $promocode;
                })->unique(function ($item) {
                    return $item->id;
                })->values();
            }
            return $this->successResponse($promo_codes, '', 200);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    protected function resolveLanguageId(Request $request): int
    {
        $fallbackLangId = ClientLanguage::where('is_primary', 1)->value('language_id');
        if (empty($fallbackLangId)) {
            $fallbackLangId = ClientLanguage::value('language_id') ?? 1;
        }

        if ($request->hasHeader('language')) {
            $langValue = $request->header('language');

            if (!empty($langValue)) {
                if (is_numeric($langValue)) {
                    $language = ClientLanguage::where('language_id', $langValue)->first();
                    if ($language) {
                        return (int) $language->language_id;
                    }
                } else {
                    $language = ClientLanguage::whereHas('language', function ($query) use ($langValue) {
                        $query->whereRaw('LOWER(sort_code) = ?', [strtolower($langValue)]);
                    })->first();

                    if ($language) {
                        return (int) $language->language_id;
                    }
                }
            }
        }

        if (Auth::check() && !empty(Auth::user()->language)) {
            return (int) Auth::user()->language;
        }

        return (int) $fallbackLangId;
    }

    protected function resolveLocaleCode(Request $request): string
    {
        $langId = $this->resolveLanguageId($request);
        $clientLanguage = ClientLanguage::with('language:id,sort_code')
            ->where('language_id', $langId)
            ->first();

        if ($clientLanguage && $clientLanguage->language) {
            return $clientLanguage->language->sort_code;
        }

        // Fallback to primary language
        $primaryLanguage = ClientLanguage::with('language:id,sort_code')
            ->where('is_primary', 1)
            ->first();

        if ($primaryLanguage && $primaryLanguage->language) {
            return $primaryLanguage->language->sort_code;
        }

        // Final fallback
        return 'en';
    }

    public function postVerifyPromoCode(Request $request)
    {
        // Set locale for multi-language support
        $localeCode = $this->resolveLocaleCode($request);
        app()->setLocale($localeCode);

        $request->validate([
            'cart_id' => 'required',
            'coupon_id' => 'required',
        ]);
        try {
            $promoCode = Promocode::where('id', $request->coupon_id)->first();
            if (!$promoCode) {
                return $this->errorResponse(__('Invalid Promocode Id'), 422);
            }
            $now = Carbon::now()->toDateTimeString();
            if ($promoCode->expiry_date < $now) {
                return $this->errorResponse(__('Promocode code is expired'), 422);
            }
            $deliveryCart = DeliveryCart::find($request->cart_id);
            if (!$deliveryCart) {
                return $this->errorResponse(__('Invalid Order!'), 422);
            }
            if ($deliveryCart->amount < $promoCode->minimum_spend) {
                return $this->errorResponse(__('Minimum amount AED :amount needed!', ['amount' => $promoCode->minimum_spend]), 422);
            }
            if ($deliveryCart->amount > $promoCode->maximum_spend) {
                return $this->errorResponse(__('Promocode applicable only upto AED :amount!', ['amount' => $promoCode->maximum_spend]), 422);
            }
            $user = Auth::user();
            if ($promoCode->first_order_only > 0) {
                $orderCount = Order::where('user_id', $user->id)->count();
                if ($orderCount > 0) {
                    return $this->errorResponse(__('Promocode applicable only to the First Order!'), 422);
                }
            }
            if ($promoCode->limit_per_user > 0) {
                $couponCount = OrderVendor::where('user_id', $user->id)->where('coupon_id', $request->coupon_id)->count();
                if ($couponCount > $promoCode->limit_per_user) {
                    return $this->errorResponse(__('You have used the PromoCode to the maximum Limit!'), 422);
                }
            }
            if ($promoCode->limit_total > 0) {
                $couponCount = OrderVendor::where('coupon_id', $request->coupon_id)->count();
                if ($couponCount > $promoCode->limit_total) {
                    return $this->errorResponse(__('PromoCode reached maximum Limit!'), 422);
                }
            }
            if ($promoCode->restriction_on == 1) {
                $vendorArr = array_column($promoCode->details->toArray(), 'refrence_id');
                if (($promoCode->restriction_type == 0 &&  !in_array($deliveryCart->vendor_id, $vendorArr)) || ($promoCode->restriction_type == 1 && !in_array($deliveryCart->vendor_id, $vendorArr))) {
                    return $this->errorResponse(__('PromoCode Not Applicable to this Vendor!'), 422);
                }
            }
            if ($promoCode->restriction_on == 0) {
                $productArr = array_column($promoCode->details->toArray(), 'refrence_id');
                if (($promoCode->restriction_type == 0 && !in_array($deliveryCart->product_id, $productArr)) || ($promoCode->restriction_type == 1 && in_array($deliveryCart->product_id, $productArr))) {
                    return $this->errorResponse(__('PromoCode Not Applicable to this Product!'), 422);
                }
            }

            DeliveryCart::where('id', $request->cart_id)
                ->update(['coupon_id' => $request->coupon_id]);

            $deliveryCartDetails = $this->getDeliveryCart($request->cart_id);
            $deliveryCartDetails['name'] = $promoCode->name;
            return response()->json([
                'message' => __('Coupon Applied Successfully!'),
                'data' => $deliveryCartDetails
            ]);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }
    public function postRemovePromoCode(Request $request)
    {
        // Set locale for multi-language support
        $localeCode = $this->resolveLocaleCode($request);
        app()->setLocale($localeCode);

        $request->validate([
            'cart_id' => 'required',
        ]);

        try {
            DeliveryCart::where('id', $request->cart_id)
                ->update(['coupon_id' => NULL]);
            $deliveryCartDetails = $this->getDeliveryCart($request->cart_id);
            return response()->json([
                'message' => __('Coupon Removed Successfully!'),
                'data' => $deliveryCartDetails
            ]);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function validatePromoCodeList()
    {
        return Validator::make(request()->all(), [
            'vendor_id' => 'required',
        ]);
    }

    public function validatePromoCode()
    {
        return Validator::make(request()->all(), [
            'vendor_id' => 'required',
            'coupon_id' => 'required',
            'amount' => 'required'
        ]);
    }


    public function getOrderTrackingDetails(Request $request)
    {

        $order = OrderVendor::where('order_id', $request->order_id)->with('products')->select('*', 'dispatcher_status_option_id as dispatcher_status')->first()->toArray();
        $response = Http::get($request->new_dispatch_traking_url);
        if ($response->status() == 200) {
            $response = $response->json();
            $response['order_details'] = $order;
            return $this->successResponse($response);
        }
    }

    public function toggleWalletUse(Request $request)
    {
        $request->validate([
            'cart_id' => 'required',
            'type' => 'required',
        ]);
        $user = Auth::user();
        $deliveryCart = DeliveryCart::where('user_id', $user->id)->first();
        if ($user->wallet->balance > 0) {
            try {
                $deliveryCart->use_wallet = $request->type ? 1 : 0;
                $deliveryCart->save();
                $message = 'Wallet is ' . ($request->type ? 'enabled.' : 'disabled');
            } catch (\Exception $e) {
                return response()->json(['message' => $e->getMessage()]);
            }
        } else {
            $deliveryCart->use_wallet = 0;
            $deliveryCart->save();
            $message = 'Wallet is empty!';
        }
        $deliveryCartDetails = $this->getDeliveryCart($deliveryCart->id);
        return response()->json([
            'message' => $message,
            'data' => $deliveryCartDetails
        ]);
    }

    private function computeLegacySameEmirateSla(int $scheduleTime, int $sla, string $frequency): ?string
    {
        if ($frequency == 'days') {
            $estimatedTime = strtotime("+{$sla} days", $scheduleTime);
            // Set time to 8 PM only if the frequency is 'days'
            $finalEstimatedTime = date('Y-m-d 20:00:00', $estimatedTime);
            return strtotime($finalEstimatedTime); // Recalculate as timestamp
        }

        if ($frequency == 'hours') {
            $estimatedTime = strtotime("+{$sla} hours", $scheduleTime);
        }

        return null;
    }

    private function resolveDistanceSlaGroupId($productDistanceSlaGroupId, bool $clientUsesDistanceBasedSla): ?int
    {
        if (!empty($productDistanceSlaGroupId)) {
            return (int) $productDistanceSlaGroupId;
        }

        if (!$clientUsesDistanceBasedSla) {
            return null;
        }

        $defaultGroup = DistanceSlaGroup::query()
            ->default()
            ->where('is_active', true)
            ->first()
            ?? DistanceSlaGroup::query()->default()->first();

        return $defaultGroup ? (int) $defaultGroup->id : null;
    }

    private function computeDistanceBasedSla(array $tasks, int $groupId, int $scheduleTime, ?array $riderWaitingTime): ?int
    {
        $riderWaitingTime ??= ['waiting_time_minutes' => 0];

        $pickupTask = null;
        $dropoffTask = null;

        foreach ($tasks as $task) {
            $taskTypeId = (int)($task['task_type_id'] ?? 0);
            if ($taskTypeId === 1 && $pickupTask === null) {
                $pickupTask = $task;
            } elseif ($taskTypeId === 2 && $dropoffTask === null) {
                $dropoffTask = $task;
            }
        }

        if (
            empty($pickupTask['latitude']) || empty($pickupTask['longitude']) ||
            empty($dropoffTask['latitude']) || empty($dropoffTask['longitude'])
        ) {
            return null;
        }

        $distanceKm = $this->haversineDistance(
            (float)$pickupTask['latitude'],
            (float)$pickupTask['longitude'],
            (float)$dropoffTask['latitude'],
            (float)$dropoffTask['longitude']
        );

        $rule = ($distanceKm > 0)
            ? DistanceSlaRule::where('distance_sla_group_id', $groupId)
            ->where('distance_from', '<=', $distanceKm)
            ->where('distance_to', '>=', $distanceKm)
            ->first()
            : null;

        if (!$rule) {
            $totalMinutes = (int)($riderWaitingTime['waiting_time_minutes'] ?? 0);
            return strtotime("+{$totalMinutes} minutes", $scheduleTime);
        }

        $hasAssignedRider = !empty($riderWaitingTime['rider_name']);
        $slaMinutes = $hasAssignedRider ? (int)$rule->time_with_rider : (int)$rule->time_without_rider;

        $totalMinutes = $slaMinutes + ($riderWaitingTime['waiting_time_minutes'] ?? 0);

        return strtotime("+{$totalMinutes} minutes", $scheduleTime);
    }

    private function haversineDistance(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $earthRadius = 6371;
        $latDelta = deg2rad($lat2 - $lat1);
        $lngDelta = deg2rad($lng2 - $lng1);

        $a = sin($latDelta / 2) * sin($latDelta / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($lngDelta / 2) * sin($lngDelta / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }


    public function insertDeliveryCart(Request $request)
    {
        $request->validate([
            'amount'            => 'required',
            'payment_option_id' => 'required',
            'vendor_id'         => 'required',
            'product_id'        => 'required',
            'currency_id'       => 'required',
            'is_same_emirate'   => 'required',
            'tasks'             => 'required',
            'category_id'       => 'required',
            'client_comment'    => 'nullable|string',
        ]);
        $paymentOptionDetails = [];
        $user              = Auth::user();
        $client_preference = ClientPreference::first();

        //Remove Email Verification - WAYN-289
        // if ($client_preference->verify_email == 1) {
        //     if ($user->is_email_verified == 0) {
        //         return response()->json(['error' => 'Your account is not verified.'], 404);
        //     }
        // }
        if ($client_preference->verify_phone == 1) {
            if ($user->is_phone_verified == 0) {
                return response()->json(['error' => 'Your phone is not verified.'], 422);
            }
        }
        try {
            $deliveryCart = DeliveryCart::where('user_id', $user->id)->first();
            if ($deliveryCart && $deliveryCart->status == 'paid') {
                throw new Exception('Unfinished Courier Order Exists', 500);
                exit();
            }
            //calculare ETA
            $estimated_time = '';
            $resolvedDistanceSlaGroupId = null;

            // Get rider waiting time (ETA to reach pickup) from dispatcher using pickup location
            $riderWaitingTime = null;
            if (!empty($request->tasks[0]['latitude']) && !empty($request->tasks[0]['longitude'])) {
                $riderWaitingTime = $this->getRiderWaitingTimeFromDispatcher(
                    $request->tasks[0]['latitude'],
                    $request->tasks[0]['longitude'],
                    $request->vendor_id
                );
            }

            $ProductData = Product::select('sla_same_emirates', 'same_emirate_frequency', 'sla_diff_emirates', 'diff_emirate_frequency', 'distance_sla_group_id')
                ->find($request->product_id);

            if (!empty($ProductData)) {
                // Convert the schedule time to a timestamp (order creation time)
                $scheduleTime = strtotime($request->schedule_time);

                if ($scheduleTime === false) {
                    $estimated_time = null;
                } else {
                    // Check if the order is for same emirates
                    if ($request->is_same_emirate) {
                        $estimated_time = null;

                        // here we are getting the distance sla group id 
                        // if its being configured in the settings and not set for the product we took the default group id 
                        $resolvedDistanceSlaGroupId = $this->resolveDistanceSlaGroupId(
                            $ProductData->distance_sla_group_id,
                            (bool) ($client_preference->use_distance_based_sla ?? false)
                        );

                        if ($resolvedDistanceSlaGroupId !== null) {
                            $estimated_time = $this->computeDistanceBasedSla(
                                $request->tasks ?? [],
                                $resolvedDistanceSlaGroupId,
                                (int) $scheduleTime,
                                $riderWaitingTime
                            );
                        } else {
                            $estimated_time = $this->computeLegacySameEmirateSla(
                                (int) $scheduleTime,
                                (int) $ProductData->sla_same_emirates,
                                (string) ($ProductData->same_emirate_frequency ?? '')
                            );
                        }
                    } else { // If the order is for different emirates

                        // If the frequency is in days, add SLA days to the schedule time
                        if ($ProductData->diff_emirate_frequency == 'days') {
                            $estimated_time = strtotime("+{$ProductData->sla_diff_emirates} days", $scheduleTime);
                            // Set time to 8 PM only if the frequency is 'days'
                            $final_estimated_time = date('Y-m-d 20:00:00', $estimated_time);
                            $estimated_time = strtotime($final_estimated_time); // Recalculate as timestamp
                        }
                        // If the frequency is in hours, add SLA hours to the schedule time
                        else if ($ProductData->diff_emirate_frequency == 'hours') {
                            $estimated_time = strtotime("+{$ProductData->sla_diff_emirates} hours", $scheduleTime);
                        }
                    }
                }

                if (is_int($estimated_time)) {
                    $estimated_time = date('Y-m-d H:i:s', $estimated_time); // Format as datetime
                } else {
                    $estimated_time = null; // Handle as needed if calculation failed
                }
            }
            $distanceSLAGroupId = (!empty($ProductData) && $resolvedDistanceSlaGroupId !== null)
                ? $resolvedDistanceSlaGroupId
                : null;

            $deliveryCart = DeliveryCart::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'status'  => 'pending',
                ],
                [
                    'vendor_id'             => $request->vendor_id,
                    'product_id'            => $request->product_id,
                    'currency_id'           => $request->currency_id,
                    'is_same_emirate'       => $request->is_same_emirate,
                    'payment_option_id'     => $request->payment_option_id,
                    'amount'                => $request->amount, //servicefee + tax
                    'recipient_phone'       => $request->recipient_phone,
                    'recipient_email'       => $request->recipient_email,
                    'schedule_time'         => date('Y-m-d H:i:s', strtotime($request->schedule_time)),
                    'task_type'             => $request->task_type,
                    'category_id'           => $request->category_id,
                    'client_comment'        => $request->client_comment,
                    'vehicle_number'        => $request->vehicle_number,
                    'coupon_id'             => $request->coupon_id ?? NULL,
                    'distance_sla_group_id' => $distanceSLAGroupId,
                    'estimated_time'        => $estimated_time,
                    'item_cat_id'           => $request->item_cat_id ?? 0,
                ]
            );

            //remove the coupon ,only if coupon id is null in request ,for applying coupon we have another function use that only
            if ($request->coupon_id === null) {
                DeliveryCart::where('id', $deliveryCart->id)->update(['coupon_id' => NULL]);
            }
            foreach ($request->tasks as $task) {
                $deliveryCartDetails = DeliveryCartTasks::updateOrCreate(
                    [
                        'delivery_cart_id' => $deliveryCart->id,
                        'task_type_id'     => $task['task_type_id'],

                    ],
                    [
                        'short_name'             => @$task['short_name'],
                        'address'                => $task['address'],
                        'latitude'               => (string) $task['latitude'],
                        'longitude'              => (string) $task['longitude'],
                        'building_villa_flat_no' => $task['building_villa_flat_no'] ?? '',
                        'street'                 => $task['street'] ?? '',
                        'city'                   => $task['city'] ?? '',
                        'area'                   => $task['area'] ?? '',
                        'name'                   => $task['name'] ?? '',
                        'phone_number_type'      => $task['phone_number_type'] ?? '',
                        'phone_number'           => $task['phone_number'] ?? '',
                        'email'                  => $task['email'] ?? '',
                    ]
                );
            }

            $deliveryCart = DeliveryCart::with(['tasks', 'coupon', 'product.taxCategory.taxRate'])
                ->where('id', $deliveryCart->id)
                ->first();

            $couponDiscount = 0;
            if ($deliveryCart->coupon_id != NULL) {
                $promoCode = Promocode::where('id', $deliveryCart->coupon_id)->first();
                if (!$promoCode) {
                    return $this->errorResponse(__('Invalid Promocode Id'), 422);
                }
                if ($promoCode->promo_type_id == 2) {
                    $couponDiscount = $promoCode->amount;
                }
                if ($promoCode->promo_type_id == 1) {
                    $couponDiscount = ($deliveryCart->amount * ($promoCode->amount / 100));
                }
            }

            $newAmount = $deliveryCart->amount - $couponDiscount;
            if ($deliveryCart->use_wallet == 1) {
                if ($user->balanceFloat > 0) {
                    $newAmount =  $newAmount -  $user->balanceFloat;
                }
            }
            $tax = 0;
            if ($deliveryCart->product->taxCategory != null) {
                $taxRate = optional($deliveryCart->product->taxCategory)->taxRate?->first();
                $rate = $taxRate ? round($taxRate->tax_rate) : 0;
                $tax  = ($newAmount * $rate) / 100;
            }

            $grandTotal = $newAmount + $tax;

            if (!empty($deliveryCart->estimated_time)) {
                $estimatedAt = Carbon::parse($deliveryCart->estimated_time)->locale(app()->getLocale());
                $timeFormatted = $estimatedAt->translatedFormat('g:i A');
                $dateFormatted = $estimatedAt->translatedFormat('d F Y');
                $sla_message = __(':time on :date', [
                    'time' => $timeFormatted,
                    'date' => $dateFormatted,
                ]);
            } else {
                // Handle case where estimated_time is not set
                $sla_message = __('Estimated completion time is unavailable.');
            }
            $deliveryCart['sla_message']    = $sla_message;
            $deliveryCart['service_fee']    = $deliveryCart->amount; //service fee
            $deliveryCart['couponDiscount'] = $couponDiscount;
            $deliveryCart['tax']            = round(max($tax, 0), 2);
            $deliveryCart['grandTotal']     = round(max($grandTotal, 0), 2);

            // Add rider ETA details to response
            $deliveryCart['rider_waiting_time_minutes'] = $riderWaitingTime['waiting_time_minutes'] ?? null;
            $deliveryCart['zone_name'] = $riderWaitingTime['zone_name'] ?? null;
            $deliveryCart['rider_name'] = $riderWaitingTime['rider_name'] ?? null;
            if ($riderWaitingTime !== null) {
                $deliveryCart['rider_eta'] = $riderWaitingTime; // Full dispatcher response object
            }

            $vendor = Vendor::select('payment_options')
                ->where('id', $request->vendor_id)
                ->first();
            $paymentOptions = json_decode($vendor->payment_options, true);
            if (!empty($paymentOptions)) {
                $paymentOptionDetails = PaymentOption::select('id', 'title')
                    ->whereIn('id', $paymentOptions)
                    ->get();
            }
            $deliveryCart['paymentOptions']  = $paymentOptionDetails;
            return response()->json([
                'message' => 'Cart Created Successfully',
                'data' => $deliveryCart
            ]);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }
    public function uploadCartImages(Request $request)
    {
        $request->validate([
            'delivery_cart_id'  => 'required',
            'images'            => 'required|array|max:5',
            'images.*'          => 'nullable|image|mimes:jpg,jpeg,png',
        ]);
        try {
            // Get existing images before deletion to remove files from S3
            $existingImages = DeliveryCartImage::where('delivery_cart_id', $request->delivery_cart_id)->get();

            // Delete all existing images for this cart ID
            DeliveryCartImage::where('delivery_cart_id', $request->delivery_cart_id)->delete();

            // Delete orphaned image files from S3
            foreach ($existingImages as $existingImage) {
                try {
                    // Extract the path from the URL to delete from S3
                    $imageUrl = $existingImage->image_path;
                    $pathParts = parse_url($imageUrl);
                    $s3Path = '';

                    // Extract the path after the domain
                    if (isset($pathParts['path'])) {
                        // Remove leading slash and get the path relative to S3 bucket
                        $s3Path = ltrim($pathParts['path'], '/');
                        // Remove the bucket name if it's in the path
                        if (strpos($s3Path, 'delivery_cart_images/') === 0) {
                            Storage::disk('s3')->delete($s3Path);
                        }
                    }
                } catch (\Exception $e) {
                    // Log error but continue with other operations
                    Log::warning('Failed to delete S3 image: ' . $e->getMessage());
                }
            }

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $filename = uniqid('cart_') . '.' . $image->getClientOriginalExtension();
                    $path = '';

                    // Check if image size is greater than 2MB (2 * 1024 * 1024 bytes)
                    if ($image->getSize() > 2 * 1024 * 1024) {
                        // Compress and resize large images
                        $filename = uniqid('cart_') . '.jpg'; // Convert to JPG for better compression
                        $compressedImage = Image::make($image)
                            ->resize(800, 600, function ($constraint) {
                                $constraint->aspectRatio();
                                $constraint->upsize();
                            })
                            ->encode('jpg', 80); // 80% quality for good compression

                        // Save compressed image to S3
                        $path = 'delivery_cart_images/' . $filename;
                        Storage::disk('s3')->put($path, $compressedImage->stream());
                    } else {
                        // Save original image without compression for smaller files
                        $path = $image->storeAs('delivery_cart_images', $filename, 's3');
                    }

                    // Make the file publicly accessible
                    Storage::disk('s3')->setVisibility($path, 'public');

                    // Get full URL to store in DB
                    $url = Storage::disk('s3')->url($path);

                    DeliveryCartImage::create([
                        'delivery_cart_id' => $request->delivery_cart_id,
                        'image_path' => $url
                    ]);
                }
            }
            return $this->successResponse([], 'Cart Images uploaded successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function updateDeliveryCartPaid(Request $request)
    {
        if ($request->has('delivery_cart_id')) {
            $update = DeliveryCart::where('id', $request->delivery_cart_id)->where('status', 'pending')->update(['status' => 'paid']);
            if ($update) {
                return response(['message' => 'Cart updated Paid']);
            } else {
                return response(['message' => 'Cart Paid status not updated'], 500);
            }
        }
    }

    public function getDeliveryCart($cartId)
    {
        $deliveryCart = DeliveryCart::with(['product.taxCategory.taxRate'])
            ->where('id', $cartId)
            ->first();
        $user = Auth::user();
        $couponDiscount = 0;
        if ($deliveryCart->coupon_id != NULL) {
            $promoCode = Promocode::where('id', $deliveryCart->coupon_id)->first();
            if (!$promoCode) {
                return $this->errorResponse(__('Invalid Promocode Id'), 422);
            }
            if ($promoCode->promo_type_id == 2) {
                $couponDiscount = $promoCode->amount;
            }
            if ($promoCode->promo_type_id == 1) {
                $couponDiscount = ($deliveryCart->amount * ($promoCode->amount / 100));
            }
        }
        $newAmount = $deliveryCart->amount - $couponDiscount;
        $tax = 0;
        if ($deliveryCart->product->taxCategory != null) {
            $rate = round($deliveryCart->product->taxCategory->taxRate[0]->tax_rate);
            $tax = ($newAmount * $rate) / 100;
        }
        $newAmount = $newAmount + $tax;
        if ($deliveryCart->use_wallet == 1) {
            if ($user->balanceFloat > 0) {
                $newAmount =  $newAmount -  $user->balanceFloat;
            }
        }
        return ([
            'delivery_cart_id' => $cartId,
            'newAmount' => round(max($newAmount, 0), 2),
            'use_wallet' => $deliveryCart->use_wallet,
            'coupon_id' => $deliveryCart->coupon_id
        ]);
    }

    public function paymentIntent(Request $request)
    {
        $request->validate([
            'cart_id' => 'required'
        ]);
        try {
            $deliveryCartAmount = $this->getDeliveryCart($request->cart_id);
            $intentRequest = new Request();
            $intentRequest->replace([
                'amount' => $deliveryCartAmount['newAmount'],
                'delivery_cart_id' => $request->cart_id,
            ]);
            $intent = PaymentOptionController::StripePaymentIntentForCourier($intentRequest);
            return response()->json([
                'message' => 'Payment Intent Created Successfully',
                'data' => $intent
            ]);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    // This function is for orders using COD method
    public function placeOrder(Request $request)
    {
        $request->validate([
            'cart_id' => 'required'
        ]);
        $deliveryCart = DeliveryCart::with('tasks')
            ->where('id', $request->cart_id)
            ->get();
        $deliveryRequest = new Request();
        $deliveryRequest->replace($deliveryCart->toArray()[0]);
        $pickUpDelivery = new PickupDeliveryController();
        $response = $pickUpDelivery->createOrder($deliveryRequest);
        DeliveryCart::where('id', $request->cart_id)->update(['status' => 'completed']);
        return response($response);
    }

    public function getItemCategories(Request $request)
    {
        $request->validate([
            'vendor_id' => 'required|exists:vendors,id'
        ]);

        try {
            $data = ItemCategory::select('id', 'name', 'description', 'selected_image', 'unselected_image')
                ->where([
                    'is_deleted' => 0,
                    'vendor_id' => $request->vendor_id
                ])->get()->toArray();

            $data = array_map(function ($item) {
                // Translate the name field using JSON translation file
                $item['name'] = __($item['name']);
                $item['selected_image'] = asset($item['selected_image']);
                $item['unselected_image'] = asset($item['unselected_image']);
                return $item;
            }, $data);

            return response()->json([
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function getCancelReason(Request $request)
    {
        $languageId = $this->resolveLanguageId($request);

        $rejecting_reasons = OrderRejectingReason::with([
            'translations' => function ($query) use ($languageId) {
                $query->where('language_id', $languageId);
            },
            'primary'
        ])
            ->where(['type' => 1, 'status' => 1])
            ->get(['id', 'name']);

        $rejecting_reasons = $rejecting_reasons->map(function ($reason) {
            $translation = $reason->relationLoaded('translations') ? $reason->translations->first() : null;

            if (!$translation && $reason->relationLoaded('primary')) {
                $translation = $reason->primary;
            }

            return [
                'id' => $reason->id,
                'name' => $translation->reason ?? $reason->name,
            ];
        });

        if ($rejecting_reasons->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No rejecting reasons found.',
                'data' => []
            ], 200);
        }
        return response()->json([
            'success' => true,
            'data' => $rejecting_reasons
        ]);
    }
    public function cancelOrder(Request $request)
    {
        $orderId = $request->orderId;
        $comment = $request->comment;
        if (!$orderId) {
            return response()->json(['error' => 'Order ID is required'], 400);
        }
        $order = Order::find($orderId);
        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }
        $orderController = new OrderController();
        $dispatch_domain = $orderController->getDispatchDomain();
        if ($dispatch_domain && $dispatch_domain != false) {
            $order = Order::select('payments.transaction_id', 'orders.payment_option_id', 'orders.order_number', 'orders.user_id', 'order_vendors.vendor_id', 'order_vendors.id as order_vendor_id')
                ->leftJoin('order_vendors', 'order_vendors.order_id', '=', 'orders.id')
                ->leftJoin('payments', 'payments.order_id', '=', 'orders.id')
                ->where('orders.id', $orderId)
                ->first();
            $cancel_reason = OrderRejectingReason::where('id', $request->cancel_reason_id)->value('name');
            $postdata =  [
                'order_number'  => $order->order_number,
                'cancel_reason' => $cancel_reason,
                'comments'      =>  $comment,
                'cancelled_by'  => 'customer'
            ];
            $client = new GCLIENT([
                'headers' => [
                    'personaltoken' => $dispatch_domain->delivery_service_key,
                    'shortcode'     => $dispatch_domain->delivery_service_key_code,
                    'content-type'  => 'application/json'
                ]
            ]);
            try {
                $url = $dispatch_domain->delivery_service_key_url;
                $res = $client->post(
                    $url . '/api/reject-order',
                    ['form_params' => ($postdata)]
                );
                $response = json_decode($res->getBody(), true);

                if ($res->getStatusCode() == 200) {
                    $vendor_order_status                         = new VendorOrderStatus();
                    $vendor_order_status->order_id               = $orderId;
                    $vendor_order_status->vendor_id              = $order->vendor_id;
                    $vendor_order_status->order_vendor_id        = $order->order_vendor_id;
                    $vendor_order_status->order_status_option_id = 3;
                    $vendor_order_status->save();

                    OrderVendor::where('order_id', $orderId)->update(['order_status_option_id' => 3, 'reject_reason' => $request->cancel_reason_id, 'comment' => $comment, 'cancelled_by' => 2]);

                    // if ($request->status_option_id == 3) {
                    //     $clientDetail = CP::on('mysql')->where(['code' => $client_preferences->client_code])->first();
                    //     AutoRejectOrderCron::on('mysql')->where(['database_name' => $clientDetail->database_name, 'order_vendor_id' => $currentOrderStatus->id])->delete();
                    // }

                    if ($order->paymentOption->code == 'stripe' || $order->paymentOption->code == 'applepay') {
                        $payment = Payment::where('order_id', $orderId)->first();
                        PaymentOptionController::StripeRefund($payment->transaction_id);
                        $refundMsg = 'Payment Refund initiated.';
                    }
                    $clientOrderController = new ClientOrderController();
                    $clientOrderController->sendRejectOrderMail($orderId, 'cancelled');

                    // Send push notification to customer
                    if ($order && $order->user_id) {
                        //sendStatusChangePushNotificationCustomer([$order->user_id], $order, 3, $order->vendor_id);

                        // Send push notification for order cancellation with 5 seconds delay using queue
                        dispatch(new \App\Jobs\SendPushNotificationJob([$order->user_id], $order->id, $order->order_number, 3, $order->vendor_id))
                            ->delay(now()->addSeconds(5))->onConnection('database');
                    }

                    return response()->json(['message' => __('Order cancelled successfully.')], 200);
                } elseif ($res->getStatusCode() == 400) {

                    return response()->json(['message' =>  $response['message'] ?? 'Failed to reject order.'], 400);
                }
            } catch (\GuzzleHttp\Exception\ClientException $e) {
                // Catch client errors like 400, 404, etc.
                $responseBody = $e->getResponse()->getBody()->getContents();
                $errorResponse = json_decode($responseBody, true);
                Log::error('Client Exception: ' . $responseBody);
                return response()->json(['message' => $errorResponse['message'] ?? 'Failed to reject order.'], 400);
            } catch (\Exception $e) {

                Log::info('Refund Response: ' . $e->getMessage());
                return response()->json(['message' => 'Something went wrong,Please try again later!'], 500);
            }
        }
    }
}