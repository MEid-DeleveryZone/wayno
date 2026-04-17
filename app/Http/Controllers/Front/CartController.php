<?php

namespace App\Http\Controllers\Front;

use DB;
use Auth;
use Session;
use Carbon\Carbon;
use Illuminate\Http\Request;
use GuzzleHttp\Client as GCLIENT;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Front\FrontController;
use App\Models\{AddonSet, Cart, CartAddon, CartProduct, User, Product, ClientCurrency, CartProductPrescription, ProductVariantSet, Country, UserAddress, ClientPreference, Vendor, Order, OrderProduct, OrderProductAddon, OrderProductPrescription, VendorOrderStatus, OrderVendor,PaymentOption, OrderTax, CartCoupon, LuxuryOption, UserWishlist, SubscriptionInvoicesUser, LoyaltyCard, VendorDineinCategory, VendorDineinTable, VendorDineinCategoryTranslation, VendorDineinTableTranslation};

class CartController extends FrontController
{
    private function randomString()
    {
        $random_string = substr(md5(microtime()), 0, 32);
        while (User::where('system_id', $random_string)->exists()) {
            $random_string = substr(md5(microtime()), 0, 32);
        }
        return $random_string;
    }
    public function showCart(Request $request, $domain = '')
    {
        if(($request->has('gateway')) && (($request->gateway == 'mobbex')||($request->gateway == 'yoco')||($request->gateway == 'paypal'))){
            if($request->has('order')){
                $order = Order::where('order_number', $request->order)->first();
                if($order){
                    if($request->status == 0){
                        $order_products = OrderProduct::select('id')->where('order_id', $order->id)->get();
                        foreach($order_products as $order_prod){
                            OrderProductAddon::where('order_product_id', $order_prod->id)->delete();
                        }
                        OrderProduct::where('order_id', $order->id)->delete();
                        OrderProductPrescription::where('order_id', $order->id)->delete();
                        VendorOrderStatus::where('order_id', $order->id)->delete();
                        OrderVendor::where('order_id', $order->id)->delete();
                        OrderTax::where('order_id', $order->id)->delete();
                        $order->delete();
                        return redirect()->route('showCart')->with('error', 'Your order has been cancelled');
                    }
                    elseif($request->status == 200){
                        return redirect()->route('order.success', $order->id);
                    }
                }
            }
            return redirect()->route('showCart');
        }

        $cartData = [];
        $user = Auth::user();
        $countries = Country::get();
        $langId = Session::get('customerLanguage');
        $guest_user = true;
        if ($user) {
            $cart = Cart::select('id', 'is_gift', 'item_count')->with('coupon.promo')->where('status', '0')->where('user_id', $user->id)->first();
            $addresses = UserAddress::where('user_id', $user->id)->get();
            $guest_user = false;
        } else {
            $cart = Cart::select('id', 'is_gift', 'item_count')->with('coupon.promo')->where('status', '0')->where('unique_identifier', session()->get('_token'))->first();
            $addresses = collect();
        }
        if ($cart) {
            $cartData = CartProduct::where('status', [0, 1])->where('cart_id', $cart->id)->groupBy('vendor_id')->orderBy('created_at', 'asc')->get();
        }
        $navCategories = $this->categoryNav($langId);
        $subscription_features = array();
        if ($user) {
            $now = Carbon::now()->toDateTimeString();
            $user_subscription = SubscriptionInvoicesUser::with('features')
                ->select('id', 'user_id', 'subscription_id')
                ->where('user_id', $user->id)
                ->where('end_date', '>', $now)
                ->orderBy('end_date', 'desc')->first();
            if ($user_subscription) {
                foreach ($user_subscription->features as $feature) {
                    $subscription_features[] = $feature->feature_id;
                }
            }
        }
        $action = (Session::has('vendorType')) ? Session::get('vendorType') : 'delivery';
        $data = array(
            'navCategories' => $navCategories,
            'cartData' => $cartData,
            'addresses' => $addresses,
            'countries' => $countries,
            'subscription_features' => $subscription_features,
            'guest_user'=>$guest_user,
            'action' => $action
        );
        $client_preference_detail = ClientPreference::first();
        $public_key_yoco=PaymentOption::where('code','yoco')->first();
        if($public_key_yoco){

            $public_key_yoco= $public_key_yoco->credentials??'';
            $public_key_yoco= json_decode($public_key_yoco);
            $public_key_yoco= $public_key_yoco->public_key??'';
        }


        return view('frontend.cartnew',compact('public_key_yoco'))->with($data,$client_preference_detail);
        // return view('frontend.cartnew')->with(['navCategories' => $navCategories, 'cartData' => $cartData, 'addresses' => $addresses, 'countries' => $countries, 'subscription_features' => $subscription_features, 'guest_user'=>$guest_user]);
    }

    public function postAddToCart(Request $request, $domain = '')
    {
        $preference = ClientPreference::first();
        $luxury_option = LuxuryOption::where('title', Session::get('vendorType'))->first();
        try {
            $cart_detail = [];
            $user = Auth::user();
            // $addon_ids = $request->addonID;
            // $addon_options_ids = $request->addonoptID;
            $langId = Session::get('customerLanguage');
            $new_session_token = session()->get('_token');
            $client_currency = ClientCurrency::where('is_primary', '=', 1)->first();
            $user_id = $user ? $user->id : '';
            $variant_id = $request->variant_id;
            if ($user) {
                $cart_detail['user_id'] = $user_id;
                $cart_detail['created_by'] = $user_id;
            }
            $cart_detail = [
                'is_gift' => 1,
                'status' => '0',
                'item_count' => 0,
                'currency_id' => $client_currency->currency_id,
                'unique_identifier' => !$user ? $new_session_token : '',
            ];
            if ($user) {
                $cart_detail = Cart::updateOrCreate(['user_id' => $user->id], $cart_detail);
                $already_added_product_in_cart = CartProduct::where(["product_id" => $request->product_id, 'cart_id' => $cart_detail->id])->first();
            } else {
                $cart_detail = Cart::updateOrCreate(['unique_identifier' => $new_session_token], $cart_detail);
                $already_added_product_in_cart = CartProduct::where(["product_id" => $request->product_id, 'cart_id' => $cart_detail->id])->first();
            }
            $productDetail = Product::with([
                'variant' => function ($sel) use($variant_id) {
                    $sel->where('id', $variant_id);
                    $sel->groupBy('product_id');
                }
            ])->find($request->product_id);

            # if product type is not equal to on demand
            if($productDetail->category->categoryDetail->type_id != 8){
                if(!empty($already_added_product_in_cart)){
                    if($productDetail->variant[0]->quantity <= $already_added_product_in_cart->quantity){
                        return response()->json(['status' => 'error', 'message' => __('Maximum quantity already added in your cart')]);
                    }
                    if($productDetail->variant[0]->quantity <= ($already_added_product_in_cart->quantity + $request->quantity)){
                        $request->quantity = $productDetail->variant[0]->quantity - $already_added_product_in_cart->quantity;
                    }
                }
                if($productDetail->variant[0]->quantity < $request->quantity){
                    $request->quantity = $productDetail->variant[0]->quantity;
                }
            }


            $addonSets = $addon_ids = $addon_options = array();
            if($request->has('addonID')){
                $addon_ids = $request->addonID;
            }
            if($request->has('addonoptID')){
                $addon_options = $request->addonoptID;
            }
            foreach($addon_options as $key => $opt){
                $addonSets[$addon_ids[$key]][] = $opt;
            }
            foreach($addonSets as $key => $value){
                $addon = AddonSet::join('addon_set_translations as ast', 'ast.addon_id', 'addon_sets.id')
                            ->select('addon_sets.id', 'addon_sets.min_select', 'addon_sets.max_select', 'ast.title')
                            ->where('ast.language_id', $langId)
                            ->where('addon_sets.status', '!=', '2')
                            ->where('addon_sets.id', $key)->first();
                if(!$addon){
                    return response()->json(["status" => "error", 'message' => 'Invalid addon or delete by admin. Try again with remove some.'], 404);
                }
                if($addon->min_select > count($value)){
                    return response()->json([
                        "status" => "error",
                        'message' => 'Select minimum ' . $addon->min_select .' options of ' .$addon->title,
                        'data' => $addon
                    ], 400);
                }
                if($addon->max_select < count($value)){
                    return response()->json([
                        "status" => "error",
                        'message' => 'You can select maximum ' . $addon->min_select .' options of ' .$addon->title,
                        'data' => $addon
                    ], 400);
                }
            }
            $oldquantity = $isnew = 0;
            $cart_product_detail = [
                'status'  => '0',
                'is_tax_applied'  => '1',
                'created_by'  => $user_id,
                'cart_id'  => $cart_detail->id,
                'quantity'  => $request->quantity,
                'vendor_id'  => $request->vendor_id,
                'product_id' => $request->product_id,
                'variant_id'  => $request->variant_id,
                'currency_id' => $client_currency->currency_id,
                'luxury_option_id' => ($luxury_option) ? $luxury_option->id : 0
            ];

            $checkVendorId = CartProduct::where('cart_id', $cart_detail->id)->where('vendor_id', '!=', $request->vendor_id)->first();

            if ($luxury_option) {
                $checkCartLuxuryOption = CartProduct::where('luxury_option_id', '!=', $luxury_option->id)->where('cart_id', $cart_detail->id)->first();
                if ($checkCartLuxuryOption) {
                    CartProduct::where('cart_id', $cart_detail->id)->delete();
                }
                if ($luxury_option->id == 2 || $luxury_option->id == 3) {
                    if ($checkVendorId) {
                        CartProduct::where('cart_id', $cart_detail->id)->delete();
                    }else{
                        $checkVendorTableAdded = CartProduct::where('cart_id', $cart_detail->id)->where('vendor_id', $request->vendor_id)->whereNotNull('vendor_dinein_table_id')->first();
                        $cart_product_detail['vendor_dinein_table_id'] = ($checkVendorTableAdded) ? $checkVendorTableAdded->vendor_dinein_table_id : NULL;
                    }
                }
            }
            if ( (isset($preference->isolate_single_vendor_order)) && ($preference->isolate_single_vendor_order == 1) ) {
                if ($checkVendorId) {
                    CartProduct::where('cart_id', $cart_detail->id)->delete();
                }
            }

            $cartProduct = CartProduct::where('product_id', $request->product_id)->where('variant_id', $request->variant_id)->where('cart_id', $cart_detail->id)->first();
            if(!$cartProduct){
                $isnew = 1;
            }else{
                $checkaddonCount = CartAddon::where('cart_product_id', $cartProduct->id)->count();
                if(count($addon_ids) != $checkaddonCount){
                    $isnew = 1;
                }else{
                    foreach ($addon_options as $key => $opts) {
                        $cart_addon = CartAddon::where('cart_product_id', $cartProduct->id)
                                    ->where('addon_id', $addon_ids[$key])
                                    ->where('option_id', $opts)->first();

                        if(!$cart_addon){
                            $isnew = 1;
                        }
                    }
                }
            }

            if($isnew == 1){
                $cartProduct = CartProduct::create($cart_product_detail);
                if(!empty($addon_ids) && !empty($addon_options)){
                    $saveAddons = array();
                    foreach ($addon_options as $key => $opts) {
                        $saveAddons[] = [
                            'option_id' => $opts,
                            'cart_id' => $cart_detail->id,
                            'addon_id' => $addon_ids[$key],
                            'cart_product_id' => $cartProduct->id,
                        ];
                    }
                    if(!empty($saveAddons)){
                        CartAddon::insert($saveAddons);
                    }
                }
            }else{
                $cartProduct->quantity = $cartProduct->quantity + $request->quantity;
                $cartProduct->save();
            }

            // if ($checkIfExist) {
            //     $checkIfExist->quantity = (int)$checkIfExist->quantity + $request->quantity;
            //     $cart_detail->cartProducts()->save($checkIfExist);
            // } else {
                // $productForVendor = Product::where('id', $request->product_id)->first();

                // $cart_product = CartProduct::updateOrCreate(['cart_id' =>  $cart_detail->id, 'product_id' => $request->product_id], $cart_product_detail);
                // $create_cart_addons = [];
                // if ($addon_options_ids) {
                //     foreach ($addon_options_ids as $k => $addon_options_id) {
                //         $create_cart_addons[] = [
                //             'addon_id' => $addon_ids[$k],
                //             'cart_id' => $cart_detail->id,
                //             'option_id' => $addon_options_id,
                //             'cart_product_id' => $cart_product->id,
                //         ];
                //     }
                // }
                // CartAddon::insert($create_cart_addons);
            // }
            return response()->json(['status' => 'success', 'message' => 'Product Added Successfully!','cart_product_id' => $cartProduct->id]);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
    /**
     * add product to cart
     *
     * @return \Illuminate\Http\Response
     */
    public function addToCart(Request $request, $domain = '')
    {
        $langId = Session::get('customerLanguage');
        if ($request->has('addonID') && $request->has('addonoptID')) {
            $addon_ids = $request->addonID;
            $addon_options = $request->addonoptID;
            $addonSets = array();
            foreach ($addon_options as $key => $opt) {
                $addonSets[$addon_ids[$key]][] = $opt;
            }
            foreach ($addonSets as $key => $value) {
                $addon = AddonSet::join('addon_set_translations as ast', 'ast.addon_id', 'addon_sets.id')
                    ->select('addon_sets.id', 'addon_sets.min_select', 'addon_sets.max_select', 'ast.title')
                    ->where('ast.language_id', $langId)
                    ->where('addon_sets.status', '!=', '2')
                    ->where('addon_sets.id', $key)->first();
                if (!$addon) {
                    return response()->json(['error' => 'Invalid addon or delete by admin. Try again with remove some.'], 404);
                }
                if ($addon->min_select > count($value)) {
                    return response()->json([
                        'error' => 'Select minimum ' . $addon->min_select . ' options of ' . $addon->title,
                        'data' => $addon
                    ], 404);
                }
                if ($addon->max_select < count($value)) {
                    return response()->json([
                        'error' => 'You can select maximum ' . $addon->min_select . ' options of ' . $addon->title,
                        'data' => $addon
                    ], 404);
                }
            }
        }
        $user_id = ' ';
        $cartInfo = ' ';
        $user = Auth::user();
        $currency = ClientCurrency::where('is_primary', '=', 1)->first();
        if ($user) {
            $user_id = $user->id;
            $userFind = Cart::where('user_id', $user_id)->first();
            if (!$userFind) {
                $cart = new Cart;
                $cart->status = '0';
                $cart->is_gift = '1';
                $cart->item_count = '1';
                $cart->user_id = $user_id;
                $cart->created_by = $user_id;
                $cart->currency_id = $currency->currency->id;
                $cart->unique_identifier = $user->system_id;
                $cart->save();
                $cartInfo = $cart;
            } else {
                $cartInfo = $userFind;
            }
            $checkIfExist = CartProduct::where('product_id', $request->product_id)->where('variant_id', $request->variant_id)->where('cart_id', $cartInfo->id)->first();
            if ($checkIfExist) {
                $checkIfExist->quantity = (int)$checkIfExist->quantity + 1;
                $cartInfo->cartProducts()->save($checkIfExist);
                return response()->json(['status' => 'success', 'message' => 'Product Added Successfully!']);
            } else {
            }
        } else {
            $cart_detail = Cart::where('unique_identifier', session()->get('_token'))->first();
            if (!$cart_detail) {
                $cart = new Cart;
                $cart->status = '0';
                $cart->is_gift = '1';
                $cart->item_count = '1';
                $cart->currency_id = $currency->currency->id;
                $cart->unique_identifier = session()->get('_token');
                $cart->save();
            }
            $productForVendor = Product::where('id', $request->product_id)->first();
            $cartProduct = new CartProduct;
            $cartProduct->status  = '0';
            $cartProduct->is_tax_applied  = '1';
            $cartProduct->created_by  = $user_id;
            $cartProduct->cart_id  = $cart_detail->id;
            $cartProduct->quantity  = $request->quantity;
            $cartProduct->product_id = $request->product_id;
            $cartProduct->variant_id  = $request->variant_id;
            $cartProduct->currency_id = $cart_detail->currency_id;
            $cartProduct->vendor_id  = $productForVendor->vendor_id;
            $cartProduct->save();
            if ($request->has('addonID') && $request->has('addonID')) {
                foreach ($addon_ids as $key => $value) {
                    $aa = $addon_ids[$key];
                    $bb = $addon_options[$key];
                    $cartAddOn = new CartAddon;
                    $cartAddOn->addon_id = $aa;
                    $cartAddOn->option_id = $bb;
                    $cartAddOn->cart_id = $cart_detail->id;
                    $cartAddOn->cart_product_id = $cartProduct->id;
                    $cartAddOn->save();
                }
            }
            return response()->json(['status' => 'success', 'message' => 'Product Added Successfully!','cart_product_id' => $cartProduct->id]);
        }
    }

    /**
     * add wishlist products to cart
     *
     * @return \Illuminate\Http\Response
     */
    public function addWishlistToCart(Request $request, $domain = '')
    {
        try {
            $cart_detail = [];
            $user = Auth::user();
            $new_session_token = session()->get('_token');
            $client_currency = ClientCurrency::where('is_primary', '=', 1)->first();
            $user_id = $user ? $user->id : '';
            if ($user) {
                $cart_detail['user_id'] = $user_id;
                $cart_detail['created_by'] = $user_id;
                $cart_detail = [
                    'is_gift' => 1,
                    'status' => '0',
                    'item_count' => 0,
                    'currency_id' => $client_currency->currency_id,
                    'unique_identifier' => !$user ? $new_session_token : '',
                ];
                $cart_detail = Cart::updateOrCreate(['user_id' => $user->id], $cart_detail);
                foreach ($request->wishlistProducts as $product) {
                    $checkIfExist = CartProduct::where('product_id', $product['product_id'])->where('variant_id', $product['variant_id'])->where('cart_id', $cart_detail->id)->first();
                    if ($checkIfExist) {
                        $checkIfExist->quantity = (int)$checkIfExist->quantity + 1;
                        $cart_detail->cartProducts()->save($checkIfExist);
                    } else {
                        $productVendor = Product::where('id', $product['product_id'])->first();
                        $cart_product_detail = [
                            'status'  => '0',
                            'is_tax_applied'  => '1',
                            'created_by'  => $user_id,
                            'cart_id'  => $cart_detail->id,
                            'quantity'  => 1,
                            'vendor_id'  => $productVendor->vendor_id,
                            'product_id' => $product['product_id'],
                            'variant_id'  => $product['variant_id'],
                            'currency_id' => $client_currency->currency_id,
                        ];
                        $cart_product = CartProduct::updateOrCreate(['cart_id' =>  $cart_detail->id, 'product_id' => $product['product_id']], $cart_product_detail);
                    }
                    $exist = UserWishlist::where('user_id', Auth::user()->id)->where('product_id', $product['product_id'])->where('product_variant_id', $product['variant_id'])->first();
                    if ($exist) {
                        $exist->delete();
                    }
                }
            }
            return response()->json(['status' => 'success', 'message' => 'Products Has Been Added to Cart Successfully!']);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->message()]);
        }
    }

    /**
     * get products from cart
     *
     * @return \Illuminate\Http\Response
     */
    public function getCartProducts($domain = '')
    {
        $cart_details = [];
        $user = Auth::user();
        $curId = Session::get('customerCurrency');
        $langId = Session::get('customerLanguage');
        if ($user) {
            $cart = Cart::select('id', 'is_gift', 'item_count', 'schedule_type', 'scheduled_date_time')->with('coupon.promo')->where('status', '0')->where('user_id', $user->id)->first();
        } else {
            $cart = Cart::select('id', 'is_gift', 'item_count', 'schedule_type', 'scheduled_date_time')->with('coupon.promo')->where('status', '0')->where('unique_identifier', session()->get('_token'))->first();
        }
        if ($cart) {
            $cart_details = $this->getCart($cart);
        }
        if ($cart_details && !empty($cart_details)) {
            return response()->json([
                'data' => $cart_details,
            ]);
        }
        return response()->json([
            'message' => "No product found in cart",
            'data' => $cart_details,
        ]);
    }

    /**
     * Get Cart Items
     *
     */
    public function getCart($cart, $address_id=0)
    {
        $address = [];
        $cart_id = $cart->id;
        $user = Auth::user();
        $langId = Session::get('customerLanguage');
        $curId = Session::get('customerCurrency');
        $preferences = ClientPreference::with(['client_detail:id,code,country_id'])->first();
        $countries = Country::get();
        $cart->pharmacy_check = $preferences->pharmacy_check;
        $customerCurrency = ClientCurrency::where('currency_id', $curId)->first();
        $latitude = '';
        $longitude = '';
        $user_allAddresses = collect();
        $upSell_products = collect();
        $crossSell_products = collect();
        if($user){
            $user_allAddresses = UserAddress::where('user_id', $user->id)->get();
            if($address_id > 0){
                $address = UserAddress::where('user_id', $user->id)->where('id', $address_id)->first();
            }else{
                $address = UserAddress::where('user_id', $user->id)->where('is_primary', 1)->first();
                $address_id = ($address) ? $address->id : 0;
            }
        }
        $latitude = ($address) ? $address->latitude : '';
        $longitude = ($address) ? $address->longitude : '';

        $delifproductnotexist = CartProduct::where('cart_id', $cart_id)->doesntHave('product')->delete();

        $cartData = CartProduct::with([
            'vendor', 'vendor.slot.day', 'vendor.slotDate', 'coupon' => function ($qry) use ($cart_id) {
                $qry->where('cart_id', $cart_id);
            }, 'vendorProducts.pvariant.media.pimage.image', 'vendorProducts.product.media.image',
            'vendorProducts.pvariant.vset.variantDetail.trans' => function ($qry) use ($langId) {
                $qry->where('language_id', $langId);
            },
            'vendorProducts.pvariant.vset.optionData.trans' => function ($qry) use ($langId) {
                $qry->where('language_id', $langId);
            },
            'vendorProducts.product.translation_one' => function ($q) use ($langId) {
                $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description');
                $q->where('language_id', $langId);
            },
            'vendorProducts' => function ($qry) use ($cart_id) {
                $qry->where('cart_id', $cart_id);
            },
            'vendorProducts.addon.set' => function ($qry) use ($langId) {
                $qry->where('language_id', $langId);
            },
            'vendorProducts.addon.option' => function ($qry) use ($langId) {
                $qry->where('language_id', $langId);
            }, 'vendorProducts.product.taxCategory.taxRate',
        ])->select('vendor_id', 'luxury_option_id', 'vendor_dinein_table_id')->where('status', [0, 1])->where('cart_id', $cart_id)->groupBy('vendor_id')->orderBy('created_at', 'asc')->get();
        $loyalty_amount_saved = 0;
        $redeem_points_per_primary_currency = '';
        $loyalty_card = LoyaltyCard::where('status', '0')->first();
        if ($loyalty_card) {
            $redeem_points_per_primary_currency = $loyalty_card->redeem_points_per_primary_currency;
        }
        $subscription_features = array();
        if($user){
            $order_loyalty_points_earned_detail = Order::where('user_id', $user->id)->select(DB::raw('sum(loyalty_points_earned) AS sum_of_loyalty_points_earned'), DB::raw('sum(loyalty_points_used) AS sum_of_loyalty_points_used'))->first();
            if ($order_loyalty_points_earned_detail) {
                $loyalty_points_used = $order_loyalty_points_earned_detail->sum_of_loyalty_points_earned - $order_loyalty_points_earned_detail->sum_of_loyalty_points_used;
                if ($loyalty_points_used > 0 && $redeem_points_per_primary_currency > 0) {
                    $loyalty_amount_saved = $loyalty_points_used / $redeem_points_per_primary_currency;
                    if( ($customerCurrency) && ($customerCurrency->is_primary != 1) ){
                        $loyalty_amount_saved = $loyalty_amount_saved * $customerCurrency->doller_compare;
                    }
                }
            }
            $now = Carbon::now()->toDateTimeString();
            $user_subscription = SubscriptionInvoicesUser::with('features')
                ->select('id', 'user_id', 'subscription_id')
                ->where('user_id', $user->id)
                ->where('end_date', '>', $now)
                ->orderBy('end_date', 'desc')->first();
            if ($user_subscription) {
                foreach ($user_subscription->features as $feature) {
                    $subscription_features[] = $feature->feature_id;
                }
            }

            $cart->scheduled_date_time = convertDateTimeInTimeZone($cart->scheduled_date_time, $user->timezone, 'Y-m-d\TH:i');
        }
        $total_payable_amount = $total_subscription_discount = $total_discount_amount = $total_discount_percent = $total_taxable_amount = 0.00;
        if ($cartData) {
            $cart_dinein_table_id = NULL;
            $action = (Session::has('vendorType')) ? Session::get('vendorType') : 'delivery';
            $vendor_details = [];
            $delivery_status = 1;
            $is_vendor_closed = 0;
            foreach ($cartData as $ven_key => $vendorData) {
                $payable_amount = $taxable_amount = $subscription_discount = $discount_amount = $discount_percent = $deliver_charge = $delivery_fee_charges = 0.00;
                $delivery_count = 0;
                $coupon_amount_used = 0;
                if(Session::has('vendorTable')){
                    if((Session::has('vendorTableVendorId')) && (Session::get('vendorTableVendorId') == $vendorData->vendor_id)){
                        $cart_dinein_table_id = Session::get('vendorTable');
                    }
                    Session::forget(['vendorTable', 'vendorTableVendorId']);
                }else{
                    $cart_dinein_table_id = $vendorData->vendor_dinein_table_id;
                }

                if($action != 'delivery'){
                    $vendor_details['vendor_address'] = $vendorData->vendor->select('id','latitude','longitude','address')->where('id', $vendorData->vendor_id)->first();
                    if($action == 'dine_in'){
                        $vendor_tables = VendorDineinTable::where('vendor_id', $vendorData->vendor_id)->with('category')->get();
                        foreach ($vendor_tables as $vendor_table) {
                            $vendor_table->qr_url = url('/vendor/'.$vendorData->vendor->slug.'/?id='.$vendorData->vendor_id.'&name='.$vendorData->vendor->name.'&table='.$vendor_table->id);
                        }
                        $vendor_details['vendor_tables'] = $vendor_tables;
                    }
                }
                else{
                    if( (isset($preferences->is_hyperlocal)) && ($preferences->is_hyperlocal == 1) ){
                        if($address_id > 0){
                            $serviceArea = $vendorData->vendor->whereHas('serviceArea', function($query) use($latitude, $longitude){
                                $query->select('vendor_id')
                                ->whereRaw("ST_Contains(POLYGON, ST_GEOMFROMTEXT('POINT(".$latitude." ".$longitude.")'))");
                            })->where('id', $vendorData->vendor_id)->get();
                        }
                    }
                }

                foreach ($vendorData->vendorProducts as $ven_key => $prod) {
                    if($cart_dinein_table_id > 0){
                        $prod->update(['vendor_dinein_table_id' => $cart_dinein_table_id]);
                    }
                    $quantity_price = 0;
                    $divider = (empty($prod->doller_compare) || $prod->doller_compare < 0) ? 1 : $prod->doller_compare;
                    $price_in_currency = $prod->pvariant->price;
                    $price_in_doller_compare = $prod->pvariant->price;
                    if($customerCurrency){
                        $price_in_currency = $prod->pvariant->price / $divider;
                        $price_in_doller_compare = $price_in_currency * $customerCurrency->doller_compare;
                    }
                    $quantity_price = $price_in_doller_compare * $prod->quantity;
                    $prod->pvariant->price_in_cart = $prod->pvariant->price;
                    $prod->pvariant->price = number_format($price_in_currency, 2, '.', '');
                    $prod->pvariant->media_one = isset($prod->pvariant->media) ? $prod->pvariant->media->first() : [];
                    $prod->pvariant->media_second = isset($prod->product->media) ? $prod->product->media->first() : [];
                    $prod->pvariant->multiplier = ($customerCurrency) ? $customerCurrency->doller_compare : 1;
                    $prod->pvariant->quantity_price = number_format($quantity_price, 2, '.', '');
                    $payable_amount = $payable_amount + $quantity_price;
                    $taxData = array();
                    if (!empty($prod->product->taxCategory) && count($prod->product->taxCategory->taxRate) > 0) {
                        foreach ($prod->product->taxCategory->taxRate as $tckey => $tax_value) {
                            $rate = round($tax_value->tax_rate);
                            $tax_amount = ($price_in_doller_compare * $rate) / 100;
                            $product_tax = $quantity_price * $rate / 100;
                            $taxData[$tckey]['identifier'] = $tax_value->identifier;
                            $taxData[$tckey]['rate'] = $rate;
                            $taxData[$tckey]['tax_amount'] = number_format($tax_amount, 2, '.', '');
                            $taxData[$tckey]['product_tax'] = number_format($product_tax, 2, '.', '');
                            $taxable_amount = $taxable_amount + $product_tax;
                            $payable_amount = $payable_amount + $product_tax;
                        }
                        unset($prod->product->taxCategory);
                    }
                    $prod->taxdata = $taxData;
                    foreach ($prod->addon as $ck => $addons) {
                        $opt_price_in_currency = $addons->option->price;
                        $opt_price_in_doller_compare = $addons->option->price;
                        if($customerCurrency){
                            $opt_price_in_currency = $addons->option->price / $divider;
                            $opt_price_in_doller_compare = $opt_price_in_currency * $customerCurrency->doller_compare;
                        }
                        $opt_quantity_price = number_format($opt_price_in_doller_compare * $prod->quantity, 2, '.', '');
                        $addons->option->price_in_cart = $addons->option->price;
                        $addons->option->price = number_format($opt_price_in_currency, 2, '.', '');
                        $addons->option->multiplier = ($customerCurrency) ? $customerCurrency->doller_compare : 1;
                        $addons->option->quantity_price = $opt_quantity_price;
                        $payable_amount = $payable_amount + $opt_quantity_price;
                    }
                    if (isset($prod->pvariant->image->imagedata) && !empty($prod->pvariant->image->imagedata)) {
                        $prod->cartImg = $prod->pvariant->image->imagedata;
                    } else {
                        $prod->cartImg = (isset($prod->product->media[0]) && !empty($prod->product->media[0])) ? $prod->product->media[0]->image : '';
                    }
                    if($action == 'delivery'){
                        if (!empty($prod->product->Requires_last_mile) && ($prod->product->Requires_last_mile == 1)) {
                            $deliver_charge = $this->getDeliveryFeeDispatcher($vendorData->vendor_id);
                            if (!empty($deliver_charge) && $delivery_count == 0) {
                                $delivery_count = 1;
                                $prod->deliver_charge = number_format($deliver_charge, 2, '.', '');
                                $payable_amount = $payable_amount + $deliver_charge;
                                $delivery_fee_charges = $deliver_charge;
                            }
                        }
                    }

                    $product = Product::with([
                        'variant' => function ($sel) {
                            $sel->groupBy('product_id');
                        },
                        'variant.media.pimage.image', 'upSell', 'crossSell', 'vendor', 'media.image', 'translation' => function ($q) use ($langId) {
                            $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description');
                            $q->where('language_id', $langId);
                        }])->select('id', 'sku', 'inquiry_only', 'url_slug', 'weight', 'weight_unit', 'vendor_id', 'has_variant', 'has_inventory', 'averageRating')
                        ->where('url_slug', $prod->product->url_slug)
                        ->first();
                    $doller_compare = ($customerCurrency) ? $customerCurrency->doller_compare : 1;
                    $up_prods = $this->metaProduct($langId, $doller_compare, 'upSell', $product->upSell);
                    if($up_prods){
                        $upSell_products->push($up_prods);
                    }
                    $cross_prods = $this->metaProduct($langId, $doller_compare, 'crossSell', $product->crossSell);
                    if($cross_prods){
                        $crossSell_products->push($cross_prods);
                    }
                }
                if ($vendorData->coupon) {
                    if (isset($vendorData->coupon->promo)) {
                        if ($vendorData->coupon->promo->promo_type_id == 2) {
                            $total_discount_percent = $vendorData->coupon->promo->amount;
                            $payable_amount -= $total_discount_percent;
                            $coupon_amount_used = $total_discount_percent;
                        } else {
                            $gross_amount = number_format(($payable_amount - $taxable_amount), 2, '.', '');
                            $percentage_amount = ($gross_amount * $vendorData->coupon->promo->amount / 100);
                            $payable_amount -= $percentage_amount;
                            $coupon_amount_used = $percentage_amount;
                        }
                    }
                }
                if (in_array(1, $subscription_features)) {
                    $subscription_discount = $subscription_discount + $delivery_fee_charges;
                }
                $vendorData->coupon_amount_used = number_format($coupon_amount_used, 2, '.', '');
                $vendorData->delivery_fee_charges = number_format($delivery_fee_charges, 2, '.', '');
                $vendorData->payable_amount = number_format($payable_amount, 2, '.', '');
                $vendorData->discount_amount = number_format($discount_amount, 2, '.', '');
                $vendorData->discount_percent = number_format($discount_percent, 2, '.', '');
                $vendorData->taxable_amount = number_format($taxable_amount, 2, '.', '');
                $vendorData->product_total_amount = number_format(($payable_amount - $taxable_amount), 2, '.', '');
                $vendorData->isDeliverable = 1;
                $vendorData->is_vendor_closed = $is_vendor_closed;
                if (!empty($subscription_features)) {
                    $vendorData->product_total_amount = number_format(($payable_amount - $taxable_amount - $subscription_discount), 2, '.', '');
                }
                if(isset($serviceArea)){
                    if($serviceArea->isEmpty()){
                        $vendorData->isDeliverable = 0;
                        $delivery_status = 0;
                    }
                }
                if($vendorData->vendor->show_slot == 0){
                    if( ($vendorData->vendor->slotDate->isEmpty()) && ($vendorData->vendor->slot->isEmpty()) ){
                        $vendorData->is_vendor_closed = 1;
                        if($delivery_status != 0){
                            $delivery_status = 0;
                        }
                    }else{
                        $vendorData->is_vendor_closed = 0;
                    }
                }
                $total_payable_amount = $total_payable_amount + $payable_amount;
                $total_taxable_amount = $total_taxable_amount + $taxable_amount;
                $total_discount_amount = $total_discount_amount + $discount_amount;
                $total_discount_percent = $total_discount_percent + $discount_percent;
                $total_subscription_discount = $total_subscription_discount + $subscription_discount;
            }
            $is_percent = 0;
            $amount_value = 0;
            if ($cart->coupon) {
                foreach ($cart->coupon as $ck => $coupon) {
                    if (isset($coupon->promo)) {
                        if ($coupon->promo->promo_type_id == 1) {
                            $is_percent = 1;
                            $total_discount_percent = $total_discount_percent + round($coupon->promo->amount);
                        }
                    }
                }
            }
            if ($is_percent == 1) {
                $total_discount_percent = ($total_discount_percent > 100) ? 100 : $total_discount_percent;
                $total_discount_amount = $total_discount_amount + ($total_payable_amount * $total_discount_percent) / 100;
            }
            if ($amount_value > 0) {
                if($customerCurrency){
                    $amount_value = $amount_value * $customerCurrency->doller_compare;
                }
                $total_discount_amount = $total_discount_amount + $amount_value;
            }
            if (!empty($subscription_features)) {
                $total_discount_amount = $total_discount_amount + $total_subscription_discount;
                $cart->total_subscription_discount = number_format($total_subscription_discount, 2, '.', '');
            }
            $total_payable_amount = $total_payable_amount - $total_discount_amount;
            if ($loyalty_amount_saved > 0) {
                if ($loyalty_amount_saved > $total_payable_amount) {
                    $loyalty_amount_saved =  $total_payable_amount;
                }
                $total_payable_amount = $total_payable_amount - $loyalty_amount_saved;
            }
            $wallet_amount_used = 0;
            if($user){
                if($user->balanceFloat > 0){
                    $wallet_amount_used = $user->balanceFloat;
                    if($customerCurrency){
                        $wallet_amount_used = $user->balanceFloat * $customerCurrency->doller_compare;
                    }
                    if($wallet_amount_used > $total_payable_amount){
                        $wallet_amount_used = $total_payable_amount;
                    }
                    $total_payable_amount = $total_payable_amount - $wallet_amount_used;
                    $cart->wallet_amount_used = number_format($wallet_amount_used, 2, '.', '');
                }
            }
            $cart->loyalty_amount = number_format($loyalty_amount_saved, 2, '.', '');

            $cart->gross_amount = number_format(($total_payable_amount + $total_discount_amount + $loyalty_amount_saved + $wallet_amount_used - $total_taxable_amount), 2, '.', '');
            $cart->new_gross_amount = number_format(($total_payable_amount + $total_discount_amount), 2, '.', '');
            $cart->total_payable_amount = number_format($total_payable_amount, 2, '.', '');
            $cart->total_discount_amount = number_format($total_discount_amount, 2, '.', '');
            $cart->total_taxable_amount = number_format($total_taxable_amount, 2, '.', '');
            $cart->tip_5_percent = number_format((0.05 * $total_payable_amount), 2, '.', '');
            $cart->tip_10_percent = number_format((0.1 * $total_payable_amount), 2, '.', '');
            $cart->tip_15_percent = number_format((0.15 * $total_payable_amount), 2, '.', '');
            $cart->deliver_status = $delivery_status;
            $cart->action = $action;
            $cart->left_section = view('frontend.cartnew-left')->with(['action' => $action,  'vendor_details' => $vendor_details, 'addresses'=> $user_allAddresses, 'countries'=> $countries, 'cart_dinein_table_id'=> $cart_dinein_table_id, 'preferences' => $preferences])->render();
            $cart->upSell_products = ($upSell_products) ? $upSell_products->first() : collect();
            $cart->crossSell_products = ($crossSell_products) ? $crossSell_products->first() : collect();

            // dd($cart->toArray());
            $cart->products = $cartData->toArray();
        }
        return $cart;
    }
    /**
     * Show Main Cart
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * Update Quantityt
     *
     * @return \Illuminate\Http\Response
     */
    public function updateQuantity($domain = '', Request $request)
    {
        $cartProduct = CartProduct::find($request->cartproduct_id);
        $variant_id = $cartProduct->variant_id;
        $productDetail = Product::with([
            'variant' => function ($sel) use($variant_id) {
                $sel->where('id', $variant_id);
                $sel->groupBy('product_id');
            }
        ])->find($cartProduct->product_id);

        if($productDetail->category->categoryDetail->type_id != 8){
            if($productDetail->variant[0]->quantity < $request->quantity){
                return response()->json(['status' => 'error', 'message' => __('Maximum quantity already added in your cart')]);
            }

        }

         $cartProduct->quantity = $request->quantity;
        $cartProduct->save();

        return response()->json("Successfully Updated");
    }

    /**
     * Delete Cart Product
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteCartProduct($domain = '', Request $request)
    {
        CartProduct::where('id', $request->cartproduct_id)->delete();
        CartCoupon::where('vendor_id', $request->vendor_id)->delete();
        CartAddon::where('cart_product_id', $request->cartproduct_id)->delete();
        return response()->json(['status' => 'success', 'message' => 'Product deleted successfully.']);
    }

    /**
     * Empty Cart
     *
     * @return \Illuminate\Http\Response
     */
    public function emptyCartData($domain = '', Request $request)
    {
        $cart_id = $request->cart_id;
        if (($cart_id != '') && ($cart_id > 0)) {
            // Cart::where('id', $cart_id)->delete();
            CartProduct::where('cart_id', $cart_id)->delete();
            CartCoupon::where('cart_id', $cart_id)->delete();
            CartAddon::where('cart_id', $cart_id)->delete();
            return response()->json(['status' => 'success', 'message' => 'Cart has been deleted successfully.']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Cart cannot be deleted.']);
        }
    }

    /**
     * Delete Cart Product
     *
     * @return \Illuminate\Http\Response
     */
    public function getCartData($domain = '', Request $request)
    {
        $cart_details = [];
        $user = Auth::user();
        $curId = Session::get('customerCurrency');
        $langId = Session::get('customerLanguage');
        $address_id = 0;
        if ($user) {
            $cart = Cart::select('id', 'is_gift', 'item_count', 'schedule_type', 'scheduled_date_time')->with('coupon.promo')->where('status', '0')->where('user_id', $user->id)->first();
        } else {
            $cart = Cart::select('id', 'is_gift', 'item_count', 'schedule_type', 'scheduled_date_time')->with('coupon.promo')->where('status', '0')->where('unique_identifier', session()->get('_token'))->first();
        }
        if (isset($request->address_id) && !empty($request->address_id)) {
            $address_id = $request->address_id;
            $address = UserAddress::where('user_id', $user->id)->update(['is_primary' => 0]);
            $address = UserAddress::where('user_id', $user->id)->where('id', $address_id)->update(['is_primary' => 1]);
        }
        if ($cart) {
            $cart_details = $this->getCart($cart, $address_id);
        }

        $client_preference_detail = ClientPreference::first();
        return response()->json(['status' => 'success', 'cart_details' => $cart_details, 'client_preference_detail' => $client_preference_detail]);
    }


    # get delivery fee from dispatcher
    public function getDeliveryFeeDispatcher($vendor_id)
    {
        try {
            $dispatch_domain = $this->checkIfLastMileOn();
            if ($dispatch_domain && $dispatch_domain != false) {
                $customer = User::find(Auth::id());
                $cus_address = UserAddress::where('user_id', Auth::id())->orderBy('is_primary', 'desc')->first();
                if ($cus_address) {
                    $tasks = array();
                    $vendor_details = Vendor::find($vendor_id);
                    $location[] = array(
                        'latitude' => $vendor_details->latitude ?? 30.71728880,
                        'longitude' => $vendor_details->longitude ?? 76.80350870
                    );
                    $location[] = array(
                        'latitude' => $cus_address->latitude ?? 30.717288800000,
                        'longitude' => $cus_address->longitude ?? 76.803508700000
                    );
                    $postdata =  ['locations' => $location];
                    $client = new GClient([
                        'headers' => [
                            'personaltoken' => $dispatch_domain->delivery_service_key,
                            'shortcode' => $dispatch_domain->delivery_service_key_code,
                            'content-type' => 'application/json'
                        ]
                    ]);
                    $url = $dispatch_domain->delivery_service_key_url;
                    $res = $client->post(
                        $url . '/api/get-delivery-fee',
                        ['form_params' => ($postdata)]
                    );
                    $response = json_decode($res->getBody(), true);
                    if ($response && $response['message'] == 'success') {
                        return $response['total'];
                    }
                }
            }
        } catch (\Exception $e) {
        }
    }
    # check if last mile delivery on
    public function checkIfLastMileOn()
    {
        $preference = ClientPreference::first();
        if ($preference->need_delivery_service == 1 && !empty($preference->delivery_service_key) && !empty($preference->delivery_service_key_code) && !empty($preference->delivery_service_key_url))
            return $preference;
        else
            return false;
    }

    public function uploadPrescription(Request $request, $domain = '')
    {
        $user = Auth::user();
        if ($user) {
            $cart = Cart::select('id')->where('status', '0')->where('user_id', $user->id)->first();
            foreach ($request->prescriptions as $prescription) {
                $cart_product_prescription = new CartProductPrescription();
                $cart_product_prescription->cart_id = $cart->id;
                $cart_product_prescription->vendor_id = $request->vendor_idd;
                $cart_product_prescription->product_id = $request->product_id;
                $cart_product_prescription->prescription = Storage::disk('s3')->put('prescription', $prescription, 'public');
                $cart_product_prescription->save();
            }
        }
        return response()->json(['status' => 'success', 'message' => "Uploaded Successfully"]);
    }

    public function addVendorTableToCart(Request $request, $domain = '')
    {
        DB::beginTransaction();
        try{
            $user = Auth::user();
            if ($user) {
                $cart = Cart::select('id')->where('status', '0')->where('user_id', $user->id)->firstOrFail();
                $cartData = CartProduct::where('cart_id', $cart->id)->where('vendor_id', $request->vendor)->update(['vendor_dinein_table_id' => $request->table]);
                DB::commit();
                return response()->json(['status'=>'Success', 'message'=>'Table has been selected']);
            }
            else{
                return response()->json(['status'=>'Error', 'message'=>'Invalid user']);
            }
        }
        catch(\Exception $ex){
            DB::rollback();
            return response()->json(['status'=>'Error', 'message'=>$ex->getMessage()]);
        }
    }

    public function updateSchedule(Request $request, $domain = '')
    {
        DB::beginTransaction();
        try{
            $user = Auth::user();
            if ($user) {
                if($request->task_type == 'now'){
                    $request->schedule_dt = Carbon::now()->format('Y-m-d H:i:s');
                }else{
                    $request->schedule_dt = Carbon::parse($request->schedule_dt, $user->timezone)->setTimezone('UTC')->format('Y-m-d H:i:s');
                }
                Cart::where('status', '0')->where('user_id', $user->id)->update(['specific_instructions' => $request->specific_instructions??null,'schedule_type' => $request->task_type, 'scheduled_date_time' => $request->schedule_dt]);
                DB::commit();
                return response()->json(['status'=>'Success', 'message'=>'Cart has been scheduled']);
            }
            else{
                return response()->json(['status'=>'Error', 'message'=>'Invalid user']);
            }
        }
        catch(\Exception $ex){
            DB::rollback();
            return response()->json(['status'=>'Error', 'message'=>$ex->getMessage()]);
        }
    }

    # update schedule for home services basis on services
    public function updateProductSchedule(Request $request, $domain = '')
    {
        DB::beginTransaction();
        try{
            $user = Auth::user();
            if ($user) {
                if($request->task_type == 'now'){
                    $request->schedule_dt = Carbon::now()->format('Y-m-d H:i:s');
                }else{
                    $request->schedule_dt = Carbon::parse($request->schedule_dt, $user->timezone)->setTimezone('UTC')->format('Y-m-d H:i:s');
                }
                CartProduct::where('id', $request->cart_product_id)->update(['schedule_type' => $request->task_type, 'scheduled_date_time' => $request->schedule_dt]);
                DB::commit();
                return response()->json(['status'=>'Success', 'message'=>'Cart has been scheduled']);
            }
            else{
                return response()->json(['status'=>'Error', 'message'=>'Invalid user']);
            }
        }
        catch(\Exception $ex){
            DB::rollback();
            return response()->json(['status'=>'Error', 'message'=>$ex->getMessage()]);
        }
    }

    // add ones add in cart for ondemand

    public function postAddToCartAddons(Request $request, $domain = '')
    {

        try {

            $user = Auth::user();
             $addon_ids = $request->addonID;
             $addon_options_ids = $request->addonoptID;
             $langId = Session::get('customerLanguage');

           $addonSets = $addon_ids = $addon_options = array();
            if($request->has('addonID')){
                $addon_ids = $request->addonID;
            }
            if($request->has('addonoptID')){
                $addon_options = $request->addonoptID;
            }
            foreach($addon_options as $key => $opt){
                $addonSets[$addon_ids[$key]][] = $opt;
            }

            if($request->has('addonoptID')){
                $addon = AddonSet::join('addon_set_translations as ast', 'ast.addon_id', 'addon_sets.id')
                ->select('addon_sets.id', 'addon_sets.min_select', 'addon_sets.max_select', 'ast.title')
                ->where('ast.language_id', $langId)
                ->where('addon_sets.status', '!=', '2')
                ->where('addon_sets.id', $request->addonID[0])->first();
             if (!$addon) {
                return response()->json(['error' => 'Invalid addon or delete by admin. Try again with remove some.'], 404);
            }
            if ($addon->min_select > count($request->addonID)) {
                return response()->json([
                    'error' => 'Select minimum ' . $addon->min_select . ' options of ' . $addon->title,
                    'data' => $addon
                ], 404);
            }
            if ($addon->max_select < count($request->addonID)) {
                return response()->json([
                    'error' => 'You can select maximum ' . $addon->max_select . ' options of ' . $addon->title,
                    'data' => $addon
                ], 404);
            }

            }



                    CartAddon::where('cart_id',$request->cart_id)->where('cart_product_id',$request->cart_product_id)->delete();
                    if (count($addon_options) > 0) {
                        $saveAddons = array();
                        foreach ($addon_options as $key => $opts) {
                            $saveAddons[] = [
                            'option_id' => $opts,
                            'cart_id' => $request->cart_id,
                            'addon_id' => $addon_ids[$key],
                            'cart_product_id' => $request->cart_product_id,
                        ];
                        }
                        CartAddon::insert($saveAddons);
                    }





            return response()->json(['status' => 'success', 'message' => 'Addons Added Successfully!']);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function checkIsolateSingleVendor(Request $request, $domain=''){
        $preference = ClientPreference::first();
        $user = Auth::user();
        $new_session_token = session()->get('_token');
        if ($user) {
            $cart_detail = Cart::where('user_id', $user->id)->first();
        } else {
            $cart_detail = Cart::where('unique_identifier', $new_session_token)->first();
        }
        if ( (isset($preference->isolate_single_vendor_order)) && ($preference->isolate_single_vendor_order == 1) && (!empty($cart_detail)) ) {
            $checkVendorId = CartProduct::where('vendor_id', '!=', $request->vendor_id)->where('cart_id', $cart_detail->id)->first();
            return response()->json(['status'=>'Success', 'otherVendorExists'=>($checkVendorId ? 1 : 0), 'isSingleVendorEnabled'=>1]);
        }else{
            return response()->json(['status'=>'Success', 'otherVendorExists'=>0 , 'isSingleVendorEnabled'=>0]);
        }
    }
}
