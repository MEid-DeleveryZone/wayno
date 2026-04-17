<?php

namespace App\Http\Controllers\Front;

use DB;
use Config;
use Session;
use Validation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Api\v1\BaseController;
use App\Http\Requests\OrderProductRatingRequest;
use App\Models\{Category,ClientPreference,ClientCurrency,Vendor,ProductVariantSet,Product,LoyaltyCard,UserAddress,Order,OrderVendor,OrderProduct,VendorOrderStatus,Client,Promocode,PromoCodeDetail, VendorCategory,VendorOrderDispatcherStatus};
use App\Http\Traits\ApiResponser;
use GuzzleHttp\Client as GCLIENT;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Log,DateTime,DateTimeZone;
class PickupDeliveryController extends FrontController{
	
    use ApiResponser;
    public function getOrderTrackingDetails(Request $request, $domain = ''){

        $order = OrderVendor::where('order_id',$request->order_id)->select('*','dispatcher_status_option_id as dispatcher_status')->first()->toArray();
        $response = Http::get($request->new_dispatch_traking_url);
        if($response->status() == 200){
           $response = $response->json();
           $response['order_details'] = $order;
           return $this->successResponse($response); 
        }
    }
    
    public function postVendorListByCategoryId(Request $request, $domain = '',$category_id = 0){
        $vendor_ids = [];
        $vendor_categories = VendorCategory::where('category_id', $category_id)->where('status', 1)->get();
        foreach ($vendor_categories as $vendor_category) {
           if(!in_array($vendor_category->vendor_id, $vendor_ids)){
                $vendor_ids[] = $vendor_category->vendor_id;
           }
        }
        $vendors = Vendor::select('id', 'name', 'banner', 'show_slot', 'order_pre_time', 'order_min_amount', 'vendor_templete_id')->where('delivery', 1)->where('status', '!=', 2)->whereIn('id', $vendor_ids)->with('slot')->withAvg('product', 'averageRating')->get();
        foreach ($vendors as $vendor) {
            $vendor->is_show_category = ($vendor->vendor_templete_id == 1) ? 0 : 1;
            unset($vendor->products);
        }
        return $this->successResponse($vendors);
    }
    public function postCabProductById(Request $request, $domain = '',$product_id = 0){
        $user = Auth::user();
        $language_id = Session::get('customerLanguage');
        $product = Product::with(['category.categoryDetail','media.image', 'translation' => function($q) use($language_id){
                            $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description')->where('language_id', $language_id);
                        },'variant' => function($q) use($language_id){
                            $q->select('id','sku', 'product_id', 'quantity', 'price', 'barcode');
                            $q->groupBy('product_id');
                        }])->select('products.id', 'products.sku', 'products.requires_shipping', 'products.sell_when_out_of_stock', 'products.url_slug', 'products.weight_unit', 'products.weight', 'products.vendor_id', 'products.has_variant', 'products.has_inventory', 'products.Requires_last_mile', 'products.averageRating', 'products.category_id','products.tags')->where('products.id', $product_id)->where('products.is_live', 1)->firstOrFail(); 
        $image_url = $product->media->first() ? $product->media->first()->image->path['image_fit'].'360/360'.$product->media->first()->image->path['image_path'] : '';
        $product->image_url = $image_url;
        $tags_price = $this->getDeliveryFeeDispatcher($request, $product);
        $product->original_tags_price = $tags_price;
        $product->tags_price = number_format($tags_price);
        $product->name = $product->translation->first() ? $product->translation->first()->title :'';
        $product->description = $product->translation->first() ? $product->translation->first()->body_html :'';
        $product->is_wishlist = $product->category->categoryDetail->show_wishlist;
        foreach ($product->variant as $k => $v) {
            $product->variant[$k]->price = $product->tags_price;
            $product->variant[$k]->multiplier = 1;
        }
        $loyalty_amount_saved = 0;
        $redeem_points_per_primary_currency = '';
        $loyalty_card = LoyaltyCard::where('status', '0')->first();
        if ($loyalty_card) {
            $redeem_points_per_primary_currency = $loyalty_card->redeem_points_per_primary_currency;
        }
        $loyalty_points_used;
        $order_loyalty_points_earned_detail = Order::where('user_id', $user->id)->select(DB::raw('sum(loyalty_points_earned) AS sum_of_loyalty_points_earned'), DB::raw('sum(loyalty_points_used) AS sum_of_loyalty_points_used'))->first();
        if ($order_loyalty_points_earned_detail) {
            $loyalty_points_used = $order_loyalty_points_earned_detail->sum_of_loyalty_points_earned - $order_loyalty_points_earned_detail->sum_of_loyalty_points_used;
            if ($loyalty_points_used > 0 && $redeem_points_per_primary_currency > 0) {
                $loyalty_amount_saved = $loyalty_points_used / $redeem_points_per_primary_currency;
            }
        }
        $product->loyalty_amount_saved = number_format((float)$loyalty_amount_saved, 2, '.', '') ??0.00;

        if($product->loyalty_amount_saved > $product->tags_price)
        $product->loyalty_amount_saved = $product->tags_price;


        return $this->successResponse($product);
    }
    # get all vehicles category by vendor

    public function productsByVendorInPickupDelivery(Request $request, $domain = '',$vid = 0){
        try {
            if($vid == 0){
                return response()->json(['error' => 'No record found.'], 404);
            }
            $userid = Auth::user()->id;
            $paginate = $request->has('limit') ? $request->limit : 12;
            $clientCurrency = ClientCurrency::where('currency_id', Auth::user()->currency)->first();
            $language_id = Session::get('customerLanguage');
            $vendor = Vendor::select('id', 'name', 'desc', 'logo', 'banner', 'address', 'latitude', 'longitude', 
                        'order_min_amount', 'order_pre_time', 'auto_reject_time', 'dine_in', 'takeaway', 'delivery')
                        ->where('id', $vid)->first();
            if(!$vendor){
                return response()->json(['error' => 'No record found.'], 200);
            }
            $products = Product::with(['category.categoryDetail', 'inwishlist' => function($qry) use($userid){
                            $qry->where('user_id', $userid);
                        },'media.image', 'translation' => function($q) use($language_id){
                            $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description')->where('language_id', $language_id);
                        },'variant' => function($q) use($language_id){
                            $q->select('id','sku', 'product_id', 'quantity', 'price', 'barcode');
                            $q->groupBy('product_id');
                        },
                    ])->join('product_categories as pc', 'pc.product_id', 'products.id')
                    ->whereNotIn('pc.category_id', function($qr) use($vid){ 
                                $qr->select('category_id')->from('vendor_categories')
                                    ->where('vendor_id', $vid)->where('status', 0);
                    })
                    ->whereHas('category.categoryDetail' ,function($qryd) {
                        $qryd->where('type_id', 7);   # check only products get of pickup
                    })
                    ->select('products.id', 'products.sku', 'products.requires_shipping', 'products.sell_when_out_of_stock', 'products.url_slug', 'products.weight_unit', 'products.weight', 'products.vendor_id', 'products.has_variant', 'products.has_inventory', 'products.Requires_last_mile', 'products.averageRating', 'pc.category_id','products.tags')
                    ->where('products.vendor_id', $vid)
                    ->where('products.is_live', 1)->distinct()->get(); 

             if(!empty($products)){
                foreach ($products as $key => $product) {
                    $tags_price = $this->getDeliveryFeeDispatcher($request, $product);
                    $image_url = $product->media->first() ? $product->media->first()->image->path['image_fit'].'93/93'.$product->media->first()->image->path['image_path'] : '';
                    $product->image_url = $image_url;
                    $product->name = $product->translation->first() ? $product->translation->first()->title :'';
                    $product->description = $product->translation->first() ? $product->translation->first()->meta_description :'';
                    $product->original_tags_price = $tags_price;
                    $product->tags_price = number_format($tags_price);
                    $product->is_wishlist = $product->category->categoryDetail->show_wishlist;
                    foreach ($product->variant as $k => $v) {
                        $product->variant[$k]->price = $product->tags_price;
                        $product->variant[$k]->multiplier = 1;
                    }
                }
            }
            $loyalty_amount_saved = 0;
            $redeem_points_per_primary_currency = '';
            $loyalty_card = LoyaltyCard::where('status', '0')->first();
            if ($loyalty_card) {
                $redeem_points_per_primary_currency = $loyalty_card->redeem_points_per_primary_currency;
            }
            $loyalty_points_used;
            $order_loyalty_points_earned_detail = Order::where('user_id', $userid)->select(DB::raw('sum(loyalty_points_earned) AS sum_of_loyalty_points_earned'), DB::raw('sum(loyalty_points_used) AS sum_of_loyalty_points_used'))->first();
            if ($order_loyalty_points_earned_detail) {
                $loyalty_points_used = $order_loyalty_points_earned_detail->sum_of_loyalty_points_earned - $order_loyalty_points_earned_detail->sum_of_loyalty_points_used;
                if ($loyalty_points_used > 0 && $redeem_points_per_primary_currency > 0) {
                    $loyalty_amount_saved = $loyalty_points_used / $redeem_points_per_primary_currency;
                }
            }
           
            $response['vendor'] = $vendor;
            $response['products'] = $products;
            $response['loyalty_amount_saved'] = number_format((float)$loyalty_amount_saved, 2, '.', '') ??0.00;
            return $this->successResponse($response);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage().''.$e->getLineNo(), $e->getCode());
        }
    }
    /**
     * list of vehicles details
    */
     /**     * Get Company ShortCode     *     */
     public function getListOfVehicles(Request $request, $cid = 0){
        try{
            if($cid == 0){
                return response()->json(['error' => 'No record found.'], 404);
            }
            $userid = Auth::user()->id;
            $langId = Auth::user()->language;
            $category = Category::with(['tags','type'  => function($q){
                            $q->select('id', 'title as redirect_to');
                        },'childs.translation'  => function($q) use($langId){
                            $q->select('category_translations.name', 'category_translations.meta_title', 'category_translations.meta_description', 'category_translations.meta_keywords', 'category_translations.category_id')->where('category_translations.language_id', $langId);
                        },'translation' => function($q) use($langId){
                            $q->select('category_translations.name', 'category_translations.meta_title', 'category_translations.meta_description', 'category_translations.meta_keywords', 'category_translations.category_id')->where('category_translations.language_id', $langId);
                        }])->select('id', 'icon', 'image', 'slug', 'type_id', 'can_add_products')
                        ->where('id', $cid)->first();
            if(!$category){
                return response()->json(['error' => 'No record found.'], 200);
            }
            $response['category'] = $category;
            $response['listData'] = $this->listData($langId, $cid, $category->type->redirect_to, $userid,$request);
            return $this->successResponse($response);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
        
    }

    public function listData($langId, $category_id, $type = '', $userid,$request){
        if ($type == 'Pickup/Delivery') {
            $category_details = [];
            $deliver_charge = $this->getDeliveryFeeDispatcher($request);
            $deliver_charge = $deliver_charge??0.00;
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
        }else{
            $arr = array();
            return $arr;
        }
    }


     # get delivery fee from dispatcher 
     public function getDeliveryFeeDispatcher($request,$product=null){
        try {
            $dispatch_domain = $this->checkIfPickupDeliveryOn();
            if ($dispatch_domain && $dispatch_domain != false) {
                $all_location = array();
                $postdata =  ['locations' => $request->locations,'agent_tag' => $product->tags??''];
                $client = new GCLIENT(['headers' => ['personaltoken' => $dispatch_domain->pickup_delivery_service_key,'shortcode' => $dispatch_domain->pickup_delivery_service_key_code,'content-type' => 'application/json']]);
                $url = $dispatch_domain->pickup_delivery_service_key_url;                      
                $res = $client->post($url.'/api/get-delivery-fee',
                    ['form_params' => ($postdata)]
                );
                $response = json_decode($res->getBody(), true); 
                if($response && $response['message'] == 'success'){
                    return $response['total'];
                }
            }
        }catch(\Exception $e){
              
        }
    }
    # check if last mile delivery on 
    public function checkIfPickupDeliveryOn(){
        $preference = ClientPreference::first();
        if($preference->need_dispacher_ride == 1 && !empty($preference->pickup_delivery_service_key) && !empty($preference->pickup_delivery_service_key_code) && !empty($preference->pickup_delivery_service_key_url))
            return $preference;
        else
            return false;
    }
    /**
     * create order for booking
    */
     public function createOrder(Request $request){
        
        DB::beginTransaction();
        try {
            if(isset($request->schedule_datetime) && !empty($request->schedule_datetime))
            {   
                $timezone = $request->time_zone;
                $given = new DateTime($request->schedule_datetime, new DateTimeZone($timezone));
                $given->setTimezone(new DateTimeZone("UTC"));
                $request->schedule_time = $given->format("Y-m-d H:i:s");
            }
            
            $user = Auth::user();
            $order_place = $this->orderPlaceForPickupDelivery($request);
           
           if($order_place && $order_place['status'] == 200){
                $data = [];
                $order = $order_place['data'];
                $request_to_dispatch = $this->placeRequestToDispatch($request,$order,$request->vendor_id);
                if($request_to_dispatch && isset($request_to_dispatch['task_id']) && $request_to_dispatch['task_id'] > 0){
                    DB::commit();
                    $order_place['data']['dispatch_traking_url'] = $request_to_dispatch['dispatch_traking_url']; 
                    $order_place['data']['user_name'] = $user->email; 
                    $order_place['data']['phone_number'] = '+'.$user->dial_code.''.$user->phone_number; 
                    return  $order_place;
                }else{
                    DB::rollback();
                    return $request_to_dispatch;
                }
            }else{
                DB::rollback();
                return $order_place;
            }
        }catch(\Exception $e){
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
 

    // order place for pickup delivery 
    
    public function orderPlaceForPickupDelivery($request){
        $total_amount = 0;
        $total_discount = 0;
        $taxable_amount = 0;
        $payable_amount = 0;
        $user = Auth::user();
        $currency_id = Session::get('customerCurrency');
        $request->address_id = $request->address_id ??null;
        $request->payment_option_id = $request->payment_option_id ??1;
        if ($user) {
            $loyalty_amount_saved = 0;
            $redeem_points_per_primary_currency = '';
            $loyalty_card = LoyaltyCard::where('status', '0')->first();
            if ($loyalty_card) {
                $redeem_points_per_primary_currency = $loyalty_card->redeem_points_per_primary_currency;
            }
            $client_preference = ClientPreference::first();
            if ($client_preference->verify_email == 1) {
                if ($user->is_email_verified == 0) {
                    $data = [];
                    $data['status'] = 404;
                    $data['message'] =  'Your account is not verified.';
                    return $data;
                }
            }
            if ($client_preference->verify_phone == 1) {
                if ($user->is_phone_verified == 0) {
                    $data = [];
                    $data['status'] = 404;
                    $data['message'] =  'Your phone is not verified.';
                    return $data;
                }
            }
            $cart = Product::where('id', $request->product_id)->first();
            if ($cart) {
                $loyalty_points_used;
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
                $order->save();
                $clientCurrency = ClientCurrency::where('currency_id', $currency_id)->first();
                $vendor = Vendor::whereHas('product', function ($q) use ($request) {
                    $q->where('id', $request->product_id);
                })->select('*','id as vendor_id')->orderBy('created_at', 'asc')->first();
                $vendor_id = $vendor->id;
                $product = Product::where('id',$request->product_id)->with('pimage', 'variants', 'taxCategory.taxRate', 'addon')->first();
                $total_delivery_fee = 0;
                $delivery_fee = 0;
                $vendor_payable_amount = 0;
                $vendor_discount_amount = 0;
                $order_vendor = new OrderVendor;
                $order_vendor->status = 0;
                $order_vendor->user_id= $user->id;
                $order_vendor->order_id= $order->id;
                $order_vendor->vendor_id= $vendor->id;
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
                $product_taxable_amount = 0;
                $product_payable_amount = 0;
                $vendor_taxable_amount = 0;
                if ($product['tax_category']) {
                    foreach ($product['tax_category']['tax_rate'] as $tax_rate_detail) {
                        $rate = round($tax_rate_detail->tax_rate);
                        $tax_amount = ($price_in_dollar_compare * $rate) / 100;
                        $product_tax = $quantity_price * $rate / 100;
                        $taxable_amount = $taxable_amount + $product_tax;
                        $payable_amount = $payable_amount + $product_tax;
                        $vendor_payable_amount = $vendor_payable_amount;
                    }
                }
                $vendor_taxable_amount += $taxable_amount;
                $total_amount += $variant->price;
                $order_product = new OrderProduct;
                $order_product->order_vendor_id = $order_vendor->id;
                $order_product->order_id = $order->id;
                $order_product->price = $variant->price;
                $order_product->quantity = 1;
                $order_product->vendor_id = $vendor->id;
                $order_product->product_id = $product->id;
                $order_product->created_by = null;
                $order_product->variant_id = $variant->id;
                $order_product->product_name = $product->sku;
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
                        $vendor_discount_amount +=$coupon_discount_amount;
                    } else {
                        $coupon_discount_amount = ($quantity_price * $coupon->amount / 100);
                        $final_coupon_discount_amount = $coupon_discount_amount * $clientCurrency->doller_compare;
                        $total_discount += $final_coupon_discount_amount;
                        $vendor_payable_amount -=$final_coupon_discount_amount;
                        $vendor_discount_amount +=$final_coupon_discount_amount;
                    }
                }
                $order_vendor->coupon_id = $coupon_id;
                $order_vendor->coupon_code = $coupon_name;
                $order_vendor->order_status_option_id = 1;
                $order_vendor->subtotal_amount = $actual_amount;
                $order_vendor->payable_amount = $vendor_payable_amount;
                $order_vendor->taxable_amount = $vendor_taxable_amount;
                $order_vendor->discount_amount= $vendor_discount_amount;
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
                $order->total_delivery_fee = $total_delivery_fee;
                $order->loyalty_points_used = $loyalty_points_used;
                $order->loyalty_amount_saved = $loyalty_amount_saved;
                $order->payable_amount = $delivery_fee + $payable_amount - $total_discount - $loyalty_amount_saved;
                $order->loyalty_points_earned = $loyalty_points_earned['per_order_points'];
                $order->loyalty_membership_id = $loyalty_points_earned['loyalty_card_id'];
                $order->save();
            }
            $order['route'] = route('front.booking.details',$order->order_number);
            $data = [];
            $data['status'] = 200;
            $data['message'] =  'Order Placed';
            $data['data'] = $order;
            return $data;
        }
    }

     // place Request To Dispatch
    public function placeRequestToDispatch($request,$order,$vendor){
        try { 
            $meta_data = '';
            $tasks = array();
            $dispatch_domain = $this->checkIfPickupDeliveryOn();
            $customer = Auth::user();
            $wallet = $customer->wallet;
           
            if ($dispatch_domain && $dispatch_domain != false) {
                if ($request->payment_option_id == 1) {
                    $cash_to_be_collected = 'Yes';
                    $payable_amount = $order->payable_amount;
                } else {
                    $cash_to_be_collected = 'No';
                    $payable_amount = 0.00;
                  
                }
                Log::info($cash_to_be_collected);
                $unique = Auth::user()->code;
                $team_tag = $unique."_".$vendor;
                $dynamic = uniqid($order->id.$vendor);
                $order_agent_tag = $product->tags??'';
                $product = Product::find($request->product_id);
                $client_do = Client::where('code',$unique)->first();
                $call_back_url = "https://".$client_do->sub_domain.env('SUBMAINDOMAIN')."/dispatch-pickup-delivery/".$dynamic; 

                $postdata =  [
                    'barcode' => '',
                    'allocation_type' => 'a',
                    'task' => $request->tasks,
                    'order_team_tag' => $team_tag,
                    'task_type' => $request->task_type,
                    'order_agent_tag' => $order_agent_tag,
                    'call_back_url' => $call_back_url??null,
                    'customer_email' => $customer->email ?? '',
                    'cash_to_be_collected' => $payable_amount??0.00,
                    'schedule_time' => $request->schedule_time ?? null,
                    'task_description' => "Pickup & Delivery From order",
                    'order_time_zone' => $request->order_time_zone ??null,
                    'customer_name' => $customer->name ?? 'Dummy Customer',
                    'recipient_email' => $request->email ?? $customer->email,
                    'recipient_phone' => $request->phone_number ?? $customer->phone_number,
                    'customer_phone_number' => $customer->phone_number ?? rand(111111,11111),
                    'runrun_order_number' =>$order->order_number
                ];
                $client = new GClient(['headers' => ['personaltoken' => $dispatch_domain->pickup_delivery_service_key,'shortcode' => $dispatch_domain->pickup_delivery_service_key_code,'content-type' => 'application/json']]);
                $url = $dispatch_domain->pickup_delivery_service_key_url;
                $res = $client->post($url.'/api/task/create',['form_params' => ($postdata)]);
                $response = json_decode($res->getBody(), true);
                if ($response && isset($response['task_id']) && $response['task_id'] > 0) {
                    $dispatch_traking_url = $response['dispatch_traking_url']??'';
                    $up_web_hook_code = OrderVendor::where(['order_id' => $order->id,'vendor_id' => $vendor])
                                    ->update(['web_hook_code' => $dynamic,'dispatch_traking_url' => $dispatch_traking_url]);
                    $response['dispatch_traking_url'] = $dispatch_traking_url;


                    $or_ids = OrderVendor::where(['order_id' => $order->id,'vendor_id' => $vendor])->first();

                    $update_vendor = VendorOrderStatus::updateOrCreate([
                        'order_id' =>  $order->id,
                        'order_status_option_id' => 2,
                        'vendor_id' =>  $vendor,
                        'order_vendor_id' =>  $or_ids->id]);   

                    OrderVendor::where('vendor_id', $vendor)->where('order_id', $order->id)->update(['order_status_option_id' => 2,'dispatcher_status_option_id' => 1]);

                    $update = VendorOrderDispatcherStatus::updateOrCreate(['dispatcher_id' => null,
                    'order_id' =>  $order->id,
                    'dispatcher_status_option_id' =>  1,
                    'vendor_id' =>  $vendor]);

                    if ($request->payment_option_id == 2){
                        $wal =   $wallet->forceWithdrawFloat($order->payable_amount, ['Wallet has been <b>debited</b> for order number <b>' . $order->order_number . '</b>']);
                    }
                 return $response;
                }
                return $response;
            }
        }catch(\Exception $e){ 
                $data = [];
                $data['status'] = 400;
                $data['message'] =  $e->getMessage();
                return $data;
            }
    }




      /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function postPromoCodeList(Request $request){
        try {
            $promo_codes = new \Illuminate\Database\Eloquent\Collection;
            $vendor_id = $request->vendor_id;
            $validator = $this->validatePromoCodeList();
            if($validator->fails()){
                return $this->errorResponse($validator->messages(), 422);
            }
            $vendor = Vendor::where('id', $request->vendor_id)->first();
            if(!$vendor){
                return response()->json(['error' => 'Invalid vendor id.'], 404);
            }
            $now = Carbon::now()->toDateTimeString();
            $product_ids = Product::where('vendor_id', $request->vendor_id)->where('id', $request->product_id)->pluck("id");
            $cart_products = Product::with(['variant' => function($q){
                            $q->select('sku', 'product_id', 'quantity', 'price', 'barcode');
                        }])->where('vendor_id', $request->vendor_id)->where('id', $request->product_id)->get();
            //$total_minimum_spend = 0;
            // foreach ($cart_products as $cart_product) {
            //     $total_minimum_spend += $cart_product->variant->first() ? $cart_product->variant->first()->price * 1 : 0;
            // }
            $total_minimum_spend = $request->amount??0;
            if($product_ids){
                $promo_code_details = PromoCodeDetail::whereIn('refrence_id', $product_ids->toArray())->pluck('promocode_id');
                if($promo_code_details->count() > 0){
                    $result1 = Promocode::whereIn('id', $promo_code_details->toArray())->whereDate('expiry_date', '>=', $now)->where('minimum_spend','<=',$total_minimum_spend)->where('maximum_spend','>=',$total_minimum_spend)->where('restriction_on', 0)->where('restriction_type', 0)->where('is_deleted', 0)->get();
                    $promo_codes = $promo_codes->merge($result1);
                }
                
                $vendor_promo_code_details = PromoCodeDetail::whereHas('promocode')->where('refrence_id', $vendor_id)->pluck('promocode_id');
                $result2 = Promocode::whereIn('id', $vendor_promo_code_details->toArray())->where('restriction_on', 1)->whereHas('details', function($q) use($vendor_id){
                    $q->where('refrence_id', $vendor_id);
                })->where('restriction_on', 1)->where('is_deleted', 0)->where('minimum_spend','<=',$total_minimum_spend)->where('maximum_spend','>=',$total_minimum_spend)->whereDate('expiry_date', '>=', $now)->get();
                $promo_codes = $promo_codes->merge($result2);
               
               
            }
            return $this->successResponse($promo_codes, '', 200);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function postVerifyPromoCode(Request $request){
        try {
            $validator = $this->validatePromoCode();
            if($validator->fails()){
                return $this->errorResponse($validator->messages(), 422);
            }
            $vendor = Vendor::where('id', $request->vendor_id)->first();
            if(!$vendor){
                return response()->json(['error' => 'Invalid vendor id.'], 404);
            }
           
            $cart_detail = Promocode::where('id', $request->coupon_id)->first();
            if(!$cart_detail){
                return $this->errorResponse('Invalid Promocode Id', 422);
            }

            if($cart_detail->promo_type_id == 2){
                $cart_detail['new_amount'] = $cart_detail->amount;
                if($cart_detail['new_amount'] < 0)
                $cart_detail['new_amount'] = 0.00;
                $cart_detail['currency_symbol'] = Session::get('currencySymbol');
            }
            if($cart_detail->promo_type_id == 1){ 
                $cart_detail['new_amount'] = ($request->amount * ($cart_detail->amount/100));
                if($cart_detail['new_amount'] < 0)
                $cart_detail['new_amount'] = 0.00;
                $cart_detail['currency_symbol'] = Session::get('currencySymbol');
            }
            return $this->successResponse($cart_detail, 'Promotion Code Used Successfully.', 201);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }
    public function postRemovePromoCode(Request $request){
        try {
            $response = ['currency_symbol' => Session::get('currencySymbol')];
            return $this->successResponse($response, 'Promotion Code Removed Successfully.', 201);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function validatePromoCodeList(){
        return Validator::make(request()->all(), [
            'vendor_id' => 'required',
        ]);
    }
    
    public function validatePromoCode(){
        return Validator::make(request()->all(), [
            'vendor_id' => 'required',
            'coupon_id' => 'required',
            'amount' => 'required'
        ]);
    }

}
