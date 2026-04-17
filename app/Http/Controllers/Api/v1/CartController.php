<?php

namespace App\Http\Controllers\Api\v1;

use DB;
use Illuminate\Support\Facades\Session;
use Validation;
use Carbon\Carbon;
use App\Models\Order;
use App\Models\Country;
use Client;
use Illuminate\Http\Request;
use App\Http\Traits\ApiResponser;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\v1\BaseController;
use App\Services\APIAuthService;
use App\Models\{User, Product, Cart, ProductVariantSet, ProductVariant, CartProduct, CartCoupon, ClientCurrency, Brand, CartAddon, UserDevice, AddonSet, UserAddress, ClientPreference, LuxuryOption, Vendor, LoyaltyCard, SubscriptionInvoicesUser, VendorDineinCategory, VendorDineinTable, VendorDineinCategoryTranslation, VendorDineinTableTranslation, OrderVendor, OrderProductAddon, OrderTax, OrderProduct, OrderProductPrescription, VendorOrderStatus, PaymentOption};
use GuzzleHttp\Client as GCLIENT;
use Illuminate\Support\Facades\Validator;
use App\Http\Traits\OrderTrait;

class CartController extends BaseController
{
    use ApiResponser;
    use APIAuthService;
    use OrderTrait;

    private $field_status = 2;

    public function __construct()
    {
        $this->middleware('checkPaidCart')->except('index');
    }

    public function index(Request $request)
    {
        try {

            if (($request->has('gateway')) && ($request->gateway != '')) {
                if ($request->has('order')) {
                    $order = Order::where('order_number', $request->order)->first();
                    if ($order) {
                        if ($request->status == 0) {
                            $order_products = OrderProduct::select('id')->where('order_id', $order->id)->get();
                            foreach ($order_products as $order_prod) {
                                OrderProductAddon::where('order_product_id', $order_prod->id)->delete();
                            }
                            OrderProduct::where('order_id', $order->id)->delete();
                            OrderProductPrescription::where('order_id', $order->id)->delete();
                            VendorOrderStatus::where('order_id', $order->id)->delete();
                            OrderVendor::where('order_id', $order->id)->delete();
                            OrderTax::where('order_id', $order->id)->delete();
                            $order->delete();
                        }
                    }
                }
            }

            $user = Auth::user();

            //To fix the paid status error after payment in fast devices(iOS)
            $paidCart = Cart::where('user_id', $user->id)->where('paid', 1)->first();
            if ($paidCart) {
                return response()->json([]);
                abort(400);
            }
            //

            if (!$user->id) {
                $cart = Cart::where('unique_identifier', $user->system_user);
            } else {
                $cart = Cart::where('user_id', $user->id);
            }
            $cart = $cart->first();
            if ($cart) {
                $cartData = $this->getCart($cart, $user->language, $user->currency, $request->type, $request->lat, $request->long);
                return $this->successResponse($cartData);
            }
            return $this->successResponse($cart);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /**   check auth and system user to add product in cart    */
    public function userCheck()
    {
        $user = Auth::user();
        if ($user->id && $user->id > 0) {
            $user_id = $user->id;
        } else {
            if (empty($user->system_user)) {
                return response()->json(['error' => 'System id should not be empty.'], 404);
            }
            $user = User::where('system_id', Auth::user()->system_user)->first();
            $val = Auth::user()->system_user;
            if (!$user) {
                $user = new User;
                $user->name = "System User";
                $user->email = $val . "@email.com";
                $user->password = Hash::make($val);
                $user->system_id = $val;
                $user->save();
            }
            $user_id = $user->id;
        }
        return $user_id;
    }

    /**     * Add product In Cart    *           */
    public function add(Request $request)
    {
        try {
            $preference = ClientPreference::first();
            $luxury_option = LuxuryOption::where('title', $request->type)->first();
            $user = Auth::user();
            $langId = $user->language;
            $user_id = $user->id;
            $unique_identifier = '';
            if (!$user_id) {
                if (empty($user->system_user)) {
                    return $this->errorResponse(__('System id should not be empty.'), 404);
                }
                $unique_identifier = $user->system_user;
            }

            $product = Product::where('sku', $request->sku)->first();
            if (!$product) {
                return $this->errorResponse(__('Invalid product.'), 404);
            }
            $productVariant = ProductVariant::where('product_id', $product->id)->where('id', $request->product_variant_id)->first();
            if (!$productVariant) {
                return $this->errorResponse(__('Invalid product variant.'), 404);
            }

            if ($product->category->categoryDetail->type_id == 8) {
            } else {
                if (($product->sell_when_out_of_stock == 0 && $productVariant->quantity < $request->quantity)) {
                    return $this->errorResponse('You Can not order more than ' . $productVariant->quantity . ' quantity.', 404);
                }
            }

            $addonSets = $addon_ids = $addon_options = array();
            if ($request->has('addon_ids')) {
                $addon_ids = $request->addon_ids;
            }
            if ($request->has('addon_options')) {
                $addon_options = $request->addon_options;
            }
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
                    return $this->errorResponse(__('Invalid addon or delete by admin. Try again with remove some.'), 404);
                }
                if ($addon->min_select > count($value)) {
                    return response()->json([
                        "status" => "Error",
                        'message' => 'Select minimum ' . $addon->min_select . ' options of ' . $addon->title,
                        'data' => $addon
                    ], 404);
                }
                if ($addon->max_select < count($value)) {
                    return response()->json([
                        "status" => "Error",
                        'message' => 'You can select maximum ' . $addon->min_select . ' options of ' . $addon->title,
                        'data' => $addon
                    ], 404);
                }
            }
            $client_currency = ClientCurrency::where('is_primary', '=', 1)->first();
            $cart_detail = [
                'is_gift' => 0,
                'status' => '0',
                'item_count' => 0,
                'user_id' => $user->id,
                'created_by' => $user->id,
                'unique_identifier' => $unique_identifier,
                'currency_id' => $client_currency->currency_id,
                'paid' => 0,
            ];
            if (!empty($user_id)) {
                $cart_detail = Cart::updateOrCreate(['user_id' => $user->id], $cart_detail);
            } else {
                $cart_detail = Cart::updateOrCreate(['unique_identifier' => $unique_identifier], $cart_detail);
            }

            $checkVendorId = CartProduct::where('cart_id', $cart_detail->id)->where('vendor_id', '!=', $product->vendor_id)->first();

            $cartExist = CartProduct::where('cart_id', $cart_detail->id)->first();

            if (!$cartExist) {
                Cart::where('id', $cart_detail->id)->update(['check_location' => 1]);
            }

            if ($luxury_option) {
                $checkCartLuxuryOption = CartProduct::where('luxury_option_id', '!=', $luxury_option->id)->where('cart_id', $cart_detail->id)->first();
                if ($checkCartLuxuryOption) {
                    return $this->errorResponse(['error' => __('You are adding products in different mods'), 'alert' => '1'], 404);
                }
                if ($luxury_option->id == 2 || $luxury_option->id == 3) {
                    if ($checkVendorId) {
                        return $this->errorResponse(['error' => __('Your cart has existing items from another vendor'), 'alert' => '1'], 404);
                    }
                }
            }

            if ((isset($preference->isolate_single_vendor_order)) && ($preference->isolate_single_vendor_order == 1)) {
                if ($checkVendorId) {
                    CartProduct::where('cart_id', $cart_detail->id)->delete();
                }
            }

            if ($cart_detail->id > 0) {
                $oldquantity = $isnew = 0;
                $cart_product_detail = [
                    'status'  => '0',
                    'is_tax_applied'  => '1',
                    'created_by'  => $user_id,
                    'product_id' => $product->id,
                    'cart_id'  => $cart_detail->id,
                    'quantity'  => $request->quantity,
                    'vendor_id'  => $product->vendor_id,
                    'variant_id'  => $request->product_variant_id,
                    'currency_id' => $client_currency->currency_id,
                    'luxury_option_id' => $luxury_option ? $luxury_option->id : 1,
                ];
                $cartProduct = CartProduct::where('cart_id', $cart_detail->id)
                    ->where('product_id', $product->id)
                    ->where('variant_id', $productVariant->id)->first();

                if (!$cartProduct) {
                    $isnew = 1;
                } else {
                    $checkaddonCount = CartAddon::where('cart_product_id', $cartProduct->id)->count();
                    if (count($addon_ids) != $checkaddonCount) {
                        $isnew = 1;
                    } else {
                        foreach ($addon_options as $key => $opts) {
                            $cart_addon = CartAddon::where('cart_product_id', $cartProduct->id)
                                ->where('addon_id', $addon_ids[$key])
                                ->where('option_id', $opts)->first();
                            if (!$cart_addon) {
                                $isnew = 1;
                            }
                        }
                    }
                }
                if ($isnew == 1) {
                    $cartProduct = CartProduct::create($cart_product_detail);
                    if (!empty($addon_ids) && !empty($addon_options)) {
                        $saveAddons = array();
                        foreach ($addon_options as $key => $opts) {
                            $saveAddons[] = [
                                'option_id' => $opts,
                                'cart_id' => $cart_detail->id,
                                'addon_id' => $addon_ids[$key],
                                'cart_product_id' => $cartProduct->id,
                            ];
                        }
                        if (!empty($saveAddons)) {
                            CartAddon::insert($saveAddons);
                        }
                    }
                } else {
                    if ($request->basket == true) {
                        $cartProduct->quantity = $request->quantity;
                    } else {
                        $cartProduct->quantity = $cartProduct->quantity + $request->quantity;
                    }
                    $cartProduct->save();
                }
            }
            $cartData = $this->getCart($cart_detail, $user->language, $user->currency, $request->type);
            if ($cartData && !empty($cartData)) {
                $cartData->cart_product_id = $cartProduct->id;
                return $this->successResponse($cartData);
            } else {
                return $this->successResponse($cartData);
            }
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /**        
     *    update quantity in cart       
     **/
    public function updateQuantity(Request $request)
    {
        $user = Auth::user();
        if ($request->quantity < 1) {
            return response()->json(['error' => __('Quantity should not be less than 1')], 422);
        }
        $cart = Cart::where('user_id', $user->id)->where('id', $request->cart_id)->first();
        if (!$cart) {
            return response()->json(['error' => __('User cart not exist.')], 404);
        }
        $cartProduct = CartProduct::where('cart_id', $cart->id)->where('id', $request->cart_product_id)->first();
        if (!$cartProduct) {
            return response()->json(['error' => __('Product not exist in cart.')], 404);
        }
        $cartProduct->quantity = $request->quantity;
        $cartProduct->save();
        $totalProducts = CartProduct::where('cart_id', $cart->id)->sum('quantity');
        $cart->item_count = $totalProducts;
        $cart->save();
        $cartData = $this->getCart($cart, $user->language, $user->currency, $request->type);
        return response()->json([
            'data' => $cartData,
        ]);
    }

    public function getItemCount(Request $request)
    {
        $cart = Cart::where('user_id', Auth::user()->id)->where('id', $request->cart_id)->first();
        if (!$cart) {
            return response()->json(['error' => __('User cart not exist.')], 404);
        }
        $totalProducts = CartProduct::where('cart_id', $cart->id)->sum('quantity');
        $cart->item_count = $totalProducts;
        $cart->save();
        return response()->json([
            'total_item' => $cart->item_count,
        ]);
    }

    public function removeItem(Request $request)
    {
        $user = Auth::user();
        $user_id = $user->id;
        $cart = Cart::where('id', $request->cart_id);
        if (!$user_id || $user_id < 1) {
            if (empty($user->system_user)) {
                return $this->errorResponse('System id should not be empty.', 404);
            }
            $cart = $cart->where('unique_identifier', $user->system_user);
        } else {
            $cart = $cart->where('user_id', $user->id);
        }
        $cart = $cart->first();
        if (!$cart) {
            return response()->json(['error' => __('Cart not exist')], 404);
        }
        $cartProduct = CartProduct::where('cart_id', $cart->id)->where('id', $request->cart_product_id)->first();
        if (!$cartProduct) {
            return response()->json(['error' => __('Product not exist in cart.')], 404);
        }
        $cartProduct->delete();
        $totalProducts = CartProduct::where('cart_id', $cart->id)->sum('quantity');
        if (!$totalProducts || $totalProducts < 1) {
            $cart->delete();
            return response()->json([
                "message" => __("Product removed from basket successfully."),
                'data' => array(),
            ]);
        }
        $cart->item_count = $totalProducts;
        $cart->save();
        $cartData = $this->getCart($cart, $user->language, $user->currency, $request->type);
        return response()->json([
            "message" => __("Product removed from basket successfully."),
            'data' => $cartData,
        ]);
    }

    /**         *       Empty cart       *          */
    public function emptyCart($cartId = 0)
    {
        $user = Auth::user();
        $user_id = $user->id;
        $cart = Cart::where('id', '>', 0);
        if (!$user_id || $user_id < 1) {
            if (empty($user->system_user)) {
                return $this->errorResponse('System id should not be empty.', 404);
            }
            $cart = $cart->where('unique_identifier', $user->system_user);
        } else {
            $cart = $cart->where('user_id', $user->id);
        }
        $cart->delete();
        return response()->json(['message' => __('Empty basket successfully.')]);
    }

    /**         *       Empty cart       *          */
    public function getCart($cart, $langId = '1', $currency = '1', $type = 'delivery', $lat = '',  $long = '')
    {
        $preferences = ClientPreference::first();
        $clientCurrency = ClientCurrency::where('currency_id', $currency)->first();
        if (!$cart) {
            return false;
        }
        $address = [];
        $latitude = '';
        $longitude = '';
        $address_id = 0;
        $delivery_status = 1;
        $cartID = $cart->id;
        $upSell_products = collect();
        $crossSell_products = collect();
        $cartData = CartProduct::with([
            'vendor', 'coupon' => function ($qry) use ($cartID) {
                $qry->where('cart_id', $cartID);
            }, 'coupon.promo.details', 'vendorProducts.pvariant.media.image', 'vendorProducts.product.media.image',
            'vendorProducts.pvariant.vset.variantDetail.trans' => function ($qry) use ($langId) {
                $qry->where('language_id', $langId);
            },
            'vendorProducts.pvariant.vset.optionData.trans' => function ($qry) use ($langId) {
                $qry->where('language_id', $langId);
            },
            'vendorProducts.product.translation' => function ($q) use ($langId) {
                $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description');
                $q->where('language_id', $langId);
            },
            'vendorProducts' => function ($qry) use ($cartID) {
                $qry->where('cart_id', $cartID);
            },
            'vendorProducts.addon.set' => function ($qry) use ($langId) {
                $qry->where('language_id', $langId);
            },
            'vendorProducts.addon.option' => function ($qry) use ($langId) {
                $qry->where('language_id', $langId);
            }, 'vendorProducts.product.taxCategory.taxRate',
        ])->select('vendor_id', 'vendor_dinein_table_id')->where('cart_id', $cartID)->groupBy('vendor_id')->orderBy('created_at', 'asc')->get();
        $loyalty_amount_saved = 0;
        $subscription_features = array();
        if ($cart->user_id) {
            $now = Carbon::now()->toDateTimeString();
            $user_subscription = SubscriptionInvoicesUser::with('features')
                ->select('id', 'user_id', 'subscription_id')
                ->where('user_id', $cart->user_id)
                ->where('end_date', '>', $now)
                ->orderBy('end_date', 'desc')->first();
            if ($user_subscription) {
                foreach ($user_subscription->features as $feature) {
                    $subscription_features[] = $feature->feature_id;
                }
            }
            $user = User::find($cart->user_id);
            $cart->scheduled_date_time = !empty($cart->scheduled_date_time) ? convertDateTimeInTimeZone($cart->scheduled_date_time, $user->timezone, 'Y-m-d\TH:i') : NULL;
            if ($lat != '' && $long != '' && $cart->check_location) {
                $address = $this->getNearestAddress($lat, $long, $cart->user_id);
            } else {
                $address = UserAddress::where('user_id', $cart->user_id)->where('is_primary', 1)->first();
            }
            $address_id = ($address) ? $address->id : 0;
        }
        $latitude = ($address) ? $address->latitude : '';
        $longitude = ($address) ? $address->longitude : '';
        $total_payable_amount = $total_subscription_discount = $total_discount_amount = $total_discount_percent = $total_taxable_amount = 0.00;
        $total_tax = $total_paying = $total_disc_amount = 0.00;
        $item_count = 0;
        $total_delivery_amount = 0;
        $product_total = 0;
        $deliver_charge = 0;
        if ($cartData) {
            $cart_dinein_table_id = NULL;
            $action = $type;
            $vendor_details = [];
            $tax_details = [];
            $is_vendor_closed = 0;
            $vendor_id = 0;
            foreach ($cartData as $ven_key => $vendorData) {
                if ($ven_key == 0)
                    $vendor_id = $vendorData->vendor_id;
                $codeApplied = $is_percent = $proSum = $proSumDis = $taxable_amount = $subscription_discount = $discount_amount = $discount_percent = $deliver_charge = $delivery_fee_charges = 0.00;
                $delivery_count = 0;

                // if(Session::has('vendorTable')){
                //     if((Session::has('vendorTableVendorId')) && (Session::get('vendorTableVendorId') == $vendorData->vendor_id)){
                //         $cart_dinein_table_id = Session::get('vendorTable');
                //     }
                //     Session::forget(['vendorTable', 'vendorTableVendorId']);
                // }else{
                $cart_dinein_table_id = $vendorData->vendor_dinein_table_id;
                // }

                if ($action != 'delivery') {
                    $vendor_details['vendor_address'] = $vendorData->vendor->select('id', 'latitude', 'longitude', 'address')->where('id', $vendorData->vendor_id)->first();
                    if ($action == 'dine_in') {
                        $vendor_tables = VendorDineinTable::where('vendor_id', $vendorData->vendor_id)->with('category')->get();
                        foreach ($vendor_tables as $vendor_table) {
                            $vendor_table->qr_url = url('/vendor/' . $vendorData->vendor->slug . '/?id=' . $vendorData->vendor_id . '&name=' . $vendorData->vendor->name . '&table=' . $vendor_table->id);
                        }
                        $vendor_details['vendor_tables'] = $vendor_tables;
                    }
                } else {
                    if ((isset($preferences->is_hyperlocal)) && ($preferences->is_hyperlocal == 1)) {
                        if ($address_id > 0) {
                            $serviceArea = $vendorData->vendor->whereHas('serviceArea', function ($query) use ($latitude, $longitude) {
                                $query->select('vendor_id')
                                    ->whereRaw("ST_Contains(POLYGON, ST_GEOMFROMTEXT('POINT(" . $latitude . " " . $longitude . ")'))");
                            })->where('id', $vendorData->vendor_id)->get();
                        }
                    }
                }

                $ttAddon = $payable_amount = $is_coupon_applied = $coupon_removed = 0;
                $coupon_removed_msg = '';
                $deliver_charge = 0;
                $delivery_fee_charges = 0.00;
                $couponData = $couponProducts = array();
                if (!empty($vendorData->coupon->promo) && ($vendorData->coupon->vendor_id == $vendorData->vendor_id)) {
                    $now = Carbon::now()->toDateTimeString();
                    $minimum_spend = 0;
                    if (isset($vendorData->coupon->promo->minimum_spend)) {
                        $minimum_spend = $vendorData->coupon->promo->minimum_spend * $clientCurrency->doller_compare;
                    }
                    if ($vendorData->coupon->promo->expiry_date < $now) {
                        $coupon_removed = 1;
                        $coupon_removed_msg = 'Coupon code is expired.';
                    } else {
                        $couponData['coupon_id'] =  $vendorData->coupon->promo->id;
                        $couponData['name'] =  $vendorData->coupon->promo->name;
                        $couponData['disc_type'] = ($vendorData->coupon->promo->promo_type_id == 1) ? 'Percent' : 'Amount';
                        $couponData['expiry_date'] =  $vendorData->coupon->promo->expiry_date;
                        $couponData['allow_free_delivery'] =  $vendorData->coupon->promo->allow_free_delivery;
                        $couponData['minimum_spend'] =  $vendorData->coupon->promo->minimum_spend;
                        $couponData['first_order_only'] = $vendorData->coupon->promo->first_order_only;
                        $couponData['restriction_on'] = ($vendorData->coupon->promo->restriction_on == 1) ? 'Vendor' : 'Product';

                        $is_coupon_applied = 1;
                        if ($vendorData->coupon->promo->promo_type_id == 1) {
                            $is_percent = 1;
                            $discount_percent = round($vendorData->coupon->promo->amount);
                        } else {
                            $discount_amount = $vendorData->coupon->promo->amount * $clientCurrency->doller_compare;
                        }
                        if ($vendorData->coupon->promo->restriction_on == 0) {
                            foreach ($vendorData->coupon->promo->details as $key => $value) {
                                $couponProducts[] = $value->refrence_id;
                            }
                        }
                    }
                }
                foreach ($vendorData->vendorProducts as $pkey => $prod) {
                    $price_in_currency = $price_in_doller_compare = $pro_disc = $quantity_price = 0;
                    $variantsData = $taxData = $vendorAddons = array();
                    $divider = (empty($prod->doller_compare) || $prod->doller_compare < 0) ? 1 : $prod->doller_compare;
                    $price_in_currency = $prod->pvariant ? $prod->pvariant->price : 0;
                    $price_in_doller_compare = $price_in_currency * $clientCurrency->doller_compare;
                    $quantity_price = $price_in_doller_compare * $prod->quantity;
                    $item_count = $item_count + $prod->quantity;
                    $proSum = $proSum + $quantity_price;
                    if (isset($prod->pvariant->image->imagedata) && !empty($prod->pvariant->image->imagedata)) {
                        $prod->cartImg = $prod->pvariant->image->imagedata;
                    } else {
                        $prod->cartImg = (isset($prod->product->media[0]) && !empty($prod->product->media[0])) ? $prod->product->media[0]->image : '';
                    }
                    if ($prod->pvariant) {
                        $variantsData['price']              = $price_in_currency;
                        $variantsData['id']                 = $prod->pvariant->id;
                        $variantsData['sku']                = ucfirst($prod->pvariant->sku);
                        $variantsData['title']              = $prod->pvariant->title;
                        $variantsData['barcode']            = $prod->pvariant->barcode;
                        $variantsData['product_id']         = $prod->pvariant->product_id;
                        $variantsData['multiplier']         = $clientCurrency->doller_compare;
                        $variantsData['gross_qty_price']    = $price_in_doller_compare * $prod->quantity;
                        if (!empty($vendorData->coupon->promo) && ($vendorData->coupon->promo->restriction_on == 0) && in_array($prod->product_id, $couponProducts)) {
                            $pro_disc = $discount_amount;
                            if ($minimum_spend <= $quantity_price) {
                                if ($is_percent == 1) {
                                    $pro_disc = ($quantity_price * $discount_percent) / 100;
                                }
                                $quantity_price = $quantity_price - $pro_disc;
                                $proSumDis = $proSumDis + $pro_disc;
                                if ($quantity_price < 0) {
                                    $quantity_price = 0;
                                }
                                $codeApplied = 1;
                            } else {
                                $variantsData['coupon_msg'] = "Spend minimun " . $minimum_spend . " to apply this coupon";
                                $variantsData['coupon_not_appiled'] = 1;
                            }
                        }
                        $variantsData['discount_amount'] = $pro_disc;
                        $variantsData['coupon_applied'] = $codeApplied;
                        $variantsData['quantity_price'] = $quantity_price;
                        $payable_amount = $payable_amount + $quantity_price;
                        if (!empty($prod->product->taxCategory) && count($prod->product->taxCategory->taxRate) > 0) {
                            foreach ($prod->product->taxCategory->taxRate as $tckey => $tax_value) {
                                $rate = round($tax_value->tax_rate);
                                $tax_amount = ($price_in_doller_compare * $rate) / 100;
                                $product_tax = $quantity_price * $rate / 100;
                                $taxData[$tckey]['rate'] = $rate;
                                $taxData[$tckey]['tax_amount'] = number_format($tax_amount, 2, '.', '');
                                $taxData[$tckey]['product_tax'] = number_format($product_tax, 2, '.', '');
                                $taxable_amount = $taxable_amount + $product_tax;
                                $taxData[$tckey]['sku'] = ucfirst($prod->pvariant->sku);
                                $taxData[$tckey]['identifier'] = $tax_value->identifier;
                                $tax_details[] = array(
                                    'rate' => $rate,
                                    'tax_amount' => $tax_amount,
                                    'identifier' => $tax_value->identifier,
                                    'sku' => ucfirst($prod->pvariant->sku),
                                );
                            }
                        }
                        $prod->taxdata = $taxData;
                        if ($action == 'delivery') {
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
                        if (!empty($prod->addon)) {
                            foreach ($prod->addon as $ck => $addons) {
                                $opt_quantity_price = 0;
                                $opt_price_in_currency = $addons->option ? $addons->option->price : 0;
                                $opt_price_in_doller_compare = $opt_price_in_currency * $clientCurrency->doller_compare;
                                $opt_quantity_price = $opt_price_in_doller_compare * $prod->quantity;
                                $vendorAddons[$ck]['quantity'] = $prod->quantity;
                                $vendorAddons[$ck]['addon_id'] = $addons->addon_id;
                                $vendorAddons[$ck]['option_id'] = $addons->option_id;
                                $vendorAddons[$ck]['price'] = $opt_price_in_currency;
                                $vendorAddons[$ck]['addon_title'] = $addons->set->title;
                                $vendorAddons[$ck]['quantity_price'] = $opt_quantity_price;
                                $vendorAddons[$ck]['option_title'] = $addons->option ? $addons->option->title : 0;
                                $vendorAddons[$ck]['price_in_cart'] = $addons->option->price;
                                $vendorAddons[$ck]['cart_product_id'] = $addons->cart_product_id;
                                $vendorAddons[$ck]['multiplier'] = $clientCurrency->doller_compare;
                                $ttAddon = $ttAddon + $opt_quantity_price;
                                $payable_amount = $payable_amount + $opt_quantity_price;
                            }
                        }
                        unset($prod->addon);
                        unset($prod->pvariant);
                    }
                    $variant_options = [];
                    if ($prod->pvariant) {
                        foreach ($prod->pvariant->vset as $variant_set_option) {
                            $variant_options[] = array(
                                'option' => $variant_set_option->optionData->trans->title,
                                'title' => $variant_set_option->variantDetail->trans->title,
                            );
                        }
                    }
                    $prod->variants = $variantsData;
                    $prod->variant_options = $variant_options;
                    $payable_amount = $payable_amount;
                    $prod->product_addons = $vendorAddons;

                    $product = Product::with([
                        'variant' => function ($sel) {
                            $sel->groupBy('product_id');
                        },
                        'variant.media.pimage.image', 'upSell', 'crossSell', 'vendor', 'media.image', 'translation' => function ($q) use ($langId) {
                            $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description');
                            $q->where('language_id', $langId);
                        }
                    ])->select('id', 'sku', 'inquiry_only', 'url_slug', 'weight', 'weight_unit', 'vendor_id', 'has_variant', 'has_inventory', 'averageRating')
                        ->where('url_slug', $prod->product->url_slug)
                        ->first();
                    $doller_compare = ($clientCurrency) ? $clientCurrency->doller_compare : 1;
                    $up_prods = $this->metaProduct($langId, $doller_compare, 'upSell', $product->upSell);
                    if ($up_prods) {
                        $upSell_products->push($up_prods);
                    }
                    $cross_prods = $this->metaProduct($langId, $doller_compare, 'crossSell', $product->crossSell);
                    if ($cross_prods) {
                        $crossSell_products->push($cross_prods);
                    }
                }
                $couponApplied = 0;
                if (!empty($vendorData->coupon->promo) && ($vendorData->coupon->promo->restriction_on == 1)) {
                    $minimum_spend = $vendorData->coupon->promo->minimum_spend * $clientCurrency->doller_compare;
                    if ($minimum_spend < $proSum) {
                        if ($is_percent == 1) {
                            $discount_amount = ($proSum * $discount_percent) / 100;
                        }
                        $couponApplied = 1;
                    } else {
                        $vendorData->coupon_msg = "To apply coupon minimum spend should be greater than " . $minimum_spend . '.';
                        $vendorData->coupon_not_appiled = 1;
                    }
                }
                $deliver_charge = $deliver_charge * $clientCurrency->doller_compare;
                $vendorData->proSum = $proSum;
                $product_total += $proSum;
                $vendorData->addonSum = $ttAddon;
                $vendorData->deliver_charge = $deliver_charge;
                $total_delivery_amount += $deliver_charge;
                $vendorData->coupon_apply_on_vendor = $couponApplied;
                $vendorData->is_coupon_applied = $is_coupon_applied;
                $vendorData->is_coupon_applied = $is_coupon_applied;
                if (empty($couponData)) {
                    $vendorData->couponData = NULL;
                } else {
                    $vendorData->couponData = $couponData;
                }
                $vendorData->vendor_gross_total = $payable_amount;
                $vendorData->discount_amount = $discount_amount;
                $vendorData->discount_percent = $discount_percent;
                $vendorData->taxable_amount = $taxable_amount;
                $vendorData->payable_amount = $payable_amount - $discount_amount;
                $vendorData->isDeliverable = 1;
                $total_paying = $total_paying + $payable_amount;
                $total_tax = $total_tax + $taxable_amount;
                $total_disc_amount = $total_disc_amount + $discount_amount;
                $total_discount_percent = $total_discount_percent + $discount_percent;
                $vendorData->vendor->is_vendor_closed = $is_vendor_closed;
                if (!empty($vendorData->coupon->promo)) {
                    unset($vendorData->coupon->promo);
                }

                if (in_array(1, $subscription_features)) {
                    $subscription_discount = $subscription_discount + $deliver_charge;
                }
                $total_subscription_discount = $total_subscription_discount + $subscription_discount;
                if (isset($serviceArea)) {
                    if ($serviceArea->isEmpty()) {
                        $vendorData->isDeliverable = 0;
                        $delivery_status = 0;
                    }
                }
                if ($vendorData->vendor->show_slot == 0) {
                    if (($vendorData->vendor->slotDate->isEmpty()) && ($vendorData->vendor->slot->isEmpty())) {
                        $vendorData->vendor->is_vendor_closed = 1;
                        if ($delivery_status != 0) {
                            $delivery_status = 0;
                        }
                    } else {
                        $vendorData->vendor->is_vendor_closed = 0;
                    }
                }
            }
        }
        $cart_product_luxury_id = CartProduct::where('cart_id', $cartID)->select('luxury_option_id', 'vendor_id')->first();
        if ($cart_product_luxury_id) {
            if ($cart_product_luxury_id->luxury_option_id == 2 || $cart_product_luxury_id->luxury_option_id == 3) {
                $vendor_address = Vendor::where('id', $cart_product_luxury_id->vendor_id)->select('address')->first();
                $cart->address = $vendor_address->address;
            }
        }
        if (!empty($subscription_features)) {
            $total_disc_amount = $total_disc_amount + $total_subscription_discount;
            $cart->total_subscription_discount = $total_subscription_discount * $clientCurrency->doller_compare;
        }
        $cart->delivery_charge = $deliver_charge * $clientCurrency->doller_compare;
        $cart->total_tax = number_format($total_tax, 2, '.', '');
        $cart->tax_details = $tax_details;
        $cart->gross_paybale_amount = $total_paying;
        $cart->total_discount_amount = $total_disc_amount * $clientCurrency->doller_compare;
        $cart->products = $cartData;
        $cart->item_count = $item_count;
        $temp_total_paying = $total_paying  + $total_tax - $total_disc_amount;
        if ($cart->user_id > 0) {
            $loyalty_amount_saved = $this->getLoyaltyPoints($cart->user_id, $clientCurrency->doller_compare);
            // if($total_paying > $cart->loyalty_amount){
            //    $cart->loyalty_amount = 0.00; 
            // }
            // $cart->wallet = $this->getWallet($cart->user_id, $clientCurrency->doller_compare, $currency);
        }
        if ($loyalty_amount_saved  >= $temp_total_paying) {
            $loyalty_amount_saved = $temp_total_paying;
            $cart->total_payable_amount = 0.00;
        } else {
            $cart->total_payable_amount = $total_paying  + $total_tax + $cart->tip_amount - $total_disc_amount - $loyalty_amount_saved;
        }

        $wallet_amount_used = 0;
        if (isset($user) && $cart->use_wallet > 0) {
            if ($user->balanceFloat > 0) {
                $wallet_amount_used = $user->balanceFloat;
                if ($clientCurrency) {
                    $wallet_amount_used = $user->balanceFloat * $clientCurrency->doller_compare;
                }
                if ($wallet_amount_used > $cart->total_payable_amount) {
                    $wallet_amount_used = $cart->total_payable_amount;
                }
                $cart->total_payable_amount = $cart->total_payable_amount - $wallet_amount_used;
                $cart->wallet_amount_used = $wallet_amount_used;
            }
        }
        $cart->deliver_status = $delivery_status;
        $cart->loyalty_amount = $loyalty_amount_saved;
        $cart->tip = array(
            ['label' => '3', 'value' => 3],
            ['label' => '5', 'value' => 5],
            ['label' => '7', 'value' => 7],
            ['label' => '20', 'value' => 20]
        );
        $temp_index = array_search($cart->tip_amount, array_column($cart->tip, 'value'));
        $cart->tip_index = $temp_index !== false ? $temp_index : -1;
        $cart->vendor_details = $vendor_details;
        $cart->total_payable_amount = number_format($cart->total_payable_amount, 2, '.', '');
        $cart->cart_dinein_table_id = $cart_dinein_table_id;
        $cart->upSell_products = ($upSell_products) ? $upSell_products->first() : collect();
        $cart->crossSell_products = ($crossSell_products) ? $crossSell_products->first() : collect();
        $cart->address = $address;
        $cart->product_total = $product_total;
        $cart->sub_total = $product_total - $cart->total_discount_amount;
        return $cart;
    }
    public function getVendorPayOptions(Request $request, $vendorId = 0)
    {
        $paymentOptionDetails = [];
        if ($vendorId) {
            $vendor = Vendor::select('payment_options')
                ->where('id', $vendorId)
                ->first();
            if ($vendor) {
                $paymentOptions = json_decode($vendor->payment_options, true);
                if (!empty($paymentOptions)) {
                    $paymentOptionDetails = PaymentOption::select('id', 'title')
                        ->whereIn('id', $paymentOptions)
                        ->get();
                }
            }
            return response()->json([
                'message' => __("Payment options retrieved successfully."),
                'data' => $paymentOptionDetails,
            ]);
        }
        return response()->json([
            'message' => __("Invalid vendor ID."),
            'data' => $paymentOptionDetails,
        ], 400);
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

    public function addVendorTableToCart(Request $request, $domain = '')
    {
        DB::beginTransaction();
        try {
            $user = Auth::user();
            if ($user) {
                $cart = Cart::select('id')->where('status', '0')->where('user_id', $user->id)->firstOrFail();
                $cartData = CartProduct::where('cart_id', $cart->id)->where('vendor_id', $request->vendor_id)->update(['vendor_dinein_table_id' => $request->table]);
                DB::commit();
                return response()->json(['status' => 'Success', 'message' => 'Table has been selected']);
            } else {
                return response()->json(['status' => 'Error', 'message' => 'Invalid user']);
            }
        } catch (\Exception $ex) {
            DB::rollback();
            return response()->json(['status' => 'Error', 'message' => $ex->getMessage()]);
        }
    }

    public function updateSchedule(Request $request, $domain = '')
    {
        DB::beginTransaction();
        try {
            $user = Auth::user();
            if ($user) {
                if ($request->task_type == 'now') {
                    $request->schedule_dt = Carbon::now()->format('Y-m-d H:i:s');
                } else {
                    $request->schedule_dt = Carbon::parse($request->schedule_dt, $user->timezone)->setTimezone('UTC')->format('Y-m-d H:i:s');
                }
                Cart::where('status', '0')->where('user_id', $user->id)->update(['specific_instructions' => $request->specific_instructions ?? null, 'schedule_type' => $request->task_type, 'scheduled_date_time' => $request->schedule_dt]);
                DB::commit();
                return response()->json(['status' => 'Success', 'message' => 'Cart has been scheduled']);
            } else {
                return response()->json(['status' => 'Error', 'message' => 'Invalid user']);
            }
        } catch (\Exception $ex) {
            DB::rollback();
            return response()->json(['status' => 'Error', 'message' => $ex->getMessage()]);
        }
    }

    public function checkIsolateSingleVendor(Request $request, $domain = '')
    {
        try {
            $preference = ClientPreference::first();
            $user = Auth::user();
            if ($user->id && $user->id > 0) {
                $cart_detail = Cart::where('user_id', $user->id)->first();
            } else {
                $cart_detail = Cart::where('unique_identifier', $user->system_user)->first();
            }
            if ((isset($preference->isolate_single_vendor_order)) && ($preference->isolate_single_vendor_order == 1) && (!empty($cart_detail))) {
                $checkVendorId = CartProduct::where('vendor_id', '!=', $request->vendor_id)->where('cart_id', $cart_detail->id)->first();
                return response()->json(['status' => 'Success', 'otherVendorExists' => ($checkVendorId ? 1 : 0), 'isSingleVendorEnabled' => 1]);
            } else {
                return response()->json(['status' => 'Success', 'otherVendorExists' => 0, 'isSingleVendorEnabled' => 0]);
            }
        } catch (\Exception $ex) {
            return response()->json(['status' => 'Error', 'message' => $ex->getMessage()]);
        }
    }


    public function updateProductSchedule(Request $request, $domain = '')
    {
        DB::beginTransaction();
        try {
            $user = Auth::user();
            if ($user) {
                if ($request->task_type == 'now') {
                    $request->schedule_dt = Carbon::now()->format('Y-m-d H:i:s');
                } else {
                    $request->schedule_dt = Carbon::parse($request->schedule_dt, $user->timezone)->setTimezone('UTC')->format('Y-m-d H:i:s');
                }
                CartProduct::where('id', $request->cart_product_id)->update(['schedule_type' => $request->task_type, 'scheduled_date_time' => $request->schedule_dt]);
                DB::commit();
                return response()->json(['status' => 'Success', 'message' => 'Cart has been scheduled']);
            } else {
                return response()->json(['status' => 'Error', 'message' => 'Invalid user']);
            }
        } catch (\Exception $ex) {
            DB::rollback();
            return response()->json(['status' => 'Error', 'message' => $ex->getMessage()]);
        }
    }

    public function getNearestAddress($lat, $long, $user_id)
    {
        // $address = UserAddress::where('user_id', $user_id)->where('is_primary', 1)->first();
        $address = UserAddress::where('user_id', $user_id)->get();
        if (count($address) < 1) {
            return null;
        }
        $distance = 0;
        foreach ($address as $row) {
            $newDistance = $this->calulateDistanceLineOfSight($lat, $long, $row->latitude, $row->longitude, 'kilometer');
            if ($distance == 0) {
                $distance = $newDistance;
                $nearAddress = $row;
            } elseif ($newDistance < $distance) {
                $distance = $newDistance;
                $nearAddress = $row;
            }
        }
        $user = Auth::user();
        UserAddress::where('user_id', $user->id)->update(['is_primary' => 0]);
        UserAddress::where('id', $nearAddress->id)->update(['is_primary' => 1]);
        return $nearAddress;
    }

    public function updateCartPaid(Request $request)
    {
        if ($request->has('cart_id')) {
            $update = Cart::where('id', $request->cart_id)->where('paid', 0)->update(['paid' => 1]);
            if ($update) {
                return response(['message' => 'Cart updated Paid']);
            } else {
                return response(['message' => 'Cart Paid status not updated'], 500);
            }
        }
    }

    public function toggleWalletUse(Request $request)
    {
        if (!$this->isLoggedIn($request)) {
            return response()->json([
                'message' => "Please login to enable Wallet!"
            ], 400);
        }

        $request->validate([
            'cart_id' => 'required',
            'use_wallet' => 'required'
        ]);
        $user = Auth::user();
        if ($user->wallet->balance > 0) {
            try {
                Cart::where('id', $request->cart_id)->update(['use_wallet' => $request->use_wallet]);
                return response()->json([
                    'message' => 'Wallet is ' . ($request->use_wallet ? 'enabled.' : 'disabled'),
                    'active_wallet' => true
                ]);
            } catch (\Exception $e) {
                return response()->json(['message' => $e->getMessage()]);
            }
        } else {
            return response()->json([
                'message' => 'Wallet is empty!',
                'active_wallet' => false
            ]);
        }
    }

    public function updateCartTipAmount(Request $request)
    {
        $request->validate([
            'cart_id' => 'required',
            'tip_amount' => 'required'
        ]);
        if ($request->tip_amount > 0) {
            try {
                $cart = Cart::find($request->cart_id);
                $cart->tip_amount = $cart->tip_amount == $request->tip_amount ? 0 : $request->tip_amount;
                $cart->save();
                return response()->json([
                    'message' => 'Tip Amount updated',
                    'tip_index' => $request->tip_index ?? -1,
                    'update_status' => true
                ]);
            } catch (\Exception $e) {
                return response()->json(['message' => $e->getMessage(), 'update_status' => false]);
            }
        }
    }


    public function updateCartSpecialInstruction(Request $request)
    {
        // Custom validation messages
        $messages = [
            'cart_id.required' => 'Cart ID is required.',
            'specific_instructions.required' => 'Special instructions are required.',
            'specific_instructions.string' => 'Special instructions must be a string.',
            'specific_instructions.max' => 'Special instructions should not exceed 200 characters.',
        ];

        $validator = Validator::make($request->all(), [
            'cart_id' => 'required',
        ], $messages);

        if ($validator->fails()) {
            $error = $validator->errors()->first('cart_id');

            return response()->json([
                'status' => 400,
                'message' => $error,
            ], Response::HTTP_BAD_REQUEST);
        }

        // Validate specific_instructions only if cart_id validation passes
        $validator = Validator::make($request->all(), [
            'specific_instructions' => 'required|string|max:200',
        ], $messages);

        if ($validator->fails()) {
            $error = $validator->errors()->first('specific_instructions');

            return response()->json([
                'status' => 400,
                'message' => $error,
            ], Response::HTTP_BAD_REQUEST);
        }

        if ($request->specific_instructions) {
            try {
                $cart = Cart::find($request->cart_id);
                if (!$cart) {
                    return response()->json([
                        'status' => 404,
                        'message' => 'Cart not found.',
                        'data' => ['update_status' => false]
                    ]);
                }

                $cart->specific_instructions = $request->specific_instructions;
                $cart->save();

                return response()->json([
                    'status' => 200,
                    'message' => 'Special Instructions updated',
                    'data' => [
                        'specific_instructions' => $request->specific_instructions ?? -1,
                        'update_status' => true
                    ]
                ]);
            } catch (\Exception $e) {
                return response()->json(['status' => 400, 'message' => $e->getMessage(), 'data' => ['update_status' => false]]);
            }
        }
    }
}
