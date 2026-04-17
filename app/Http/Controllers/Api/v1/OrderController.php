<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Traits\ApiResponser;
use GuzzleHttp\Client as GCLIENT;
use App\Http\Controllers\Api\v1\BaseController;
use App\Http\Controllers\Front\OrderController as FrontOrderController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\OrderStoreRequest;
use Illuminate\Support\Facades\Log;
use App\Models\{Order, OrderProduct, Cart, CartAddon, CartProduct, CartProductPrescription, Product, OrderProductAddon, ClientPreference, ClientCurrency, OrderVendor, UserAddress, CartCoupon, VendorOrderStatus, VendorOrderDispatcherStatus, OrderStatusOption, Vendor, LoyaltyCard, NotificationTemplate, User, Payment, SubscriptionInvoicesUser, UserDevice, Client, DeliveryCart, DeliveryCartTasks, DispatcherStatusOption, EmailTemplate, ItemCategory, UserVendor, LuxuryOption, ClientLanguage, Language};
use App\Models\AutoRejectOrderCron;
use App\Http\Traits\OrderTrait;
use Barryvdh\DomPDF\Facade\Pdf;
// use ArPhp\I18N\Arabic;
include_once(base_path() . '/vendor/khaled.alshamaa/ar-php/src/Arabic.php');

use Illuminate\Support\Facades\Crypt;

class OrderController extends BaseController
{
    use ApiResponser;
    use OrderTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    # check if last mile delivery on
    public function checkIfLastMileOn()
    {
        $preference = ClientPreference::first();
        if ($preference->need_delivery_service == 1 && !empty($preference->delivery_service_key) && !empty($preference->delivery_service_key_code) && !empty($preference->delivery_service_key_url))
            return $preference;
        else
            return false;
    }
    public function postPlaceOrder(OrderStoreRequest $request)
    {
        try {
            $total_amount = 0;
            $total_discount = 0;
            $taxable_amount = 0;
            $payable_amount = 0;
            $vendor_id = 0;
            $user = Auth::user();
            if ($user) {
                DB::beginTransaction();
                $subscription_features = array();
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
                $loyalty_amount_saved = 0;
                $redeem_points_per_primary_currency = '';
                $loyalty_card = LoyaltyCard::where('status', '0')->first();
                if ($loyalty_card) {
                    $redeem_points_per_primary_currency = $loyalty_card->redeem_points_per_primary_currency;
                }
                $client_preference = ClientPreference::first();
                // if ($client_preference->verify_email == 1) {
                //     if ($user->is_email_verified == 0) {
                //         return response()->json(['error' => 'Your account is not verified.'], 404);
                //     }
                // }
                if ($client_preference->verify_phone == 1) {
                    if ($user->is_phone_verified == 0) {
                        return response()->json(['error' => 'Your phone is not verified.'], 404);
                    }
                }
                $user_address = UserAddress::where('id', $request->address_id)->first();
                if (!$user_address) {
                    return response()->json(['error' => 'Invalid address id.'], 404);
                }
                //if we are doing delivery for this vendor,get the delivery price from dispatcher,
                //here by default we are passing 'delivery' as we are doing delivery for all the grocery/pharmacy vendors
                $action = ($request->has('type')) ? $request->type : 'delivery';
                $luxury_option = LuxuryOption::where('title', $action)->first();
                $cart = Cart::where('user_id', $user->id)->first();
                if ($cart) {
                    $loyalty_points_used = 0;
                    $order_loyalty_points_earned_detail = Order::where('user_id', $user->id)->select(DB::raw('sum(loyalty_points_earned) AS sum_of_loyalty_points_earned'), DB::raw('sum(loyalty_points_used) AS sum_of_loyalty_points_used'))->first();
                    if ($order_loyalty_points_earned_detail) {
                        $loyalty_points_used = $order_loyalty_points_earned_detail->sum_of_loyalty_points_earned - $order_loyalty_points_earned_detail->sum_of_loyalty_points_used;
                        if ($loyalty_points_used > 0 && $redeem_points_per_primary_currency > 0) {
                            $loyalty_amount_saved = $loyalty_points_used / $redeem_points_per_primary_currency;
                        }
                    }
                    $order                      = new Order;
                    $order->user_id             = $user->id;
                    $order->order_number        = generateOrderNo();
                    $order->address_id          = $request->address_id;
                    $address                    = UserAddress::find($request->address_id);
                    if ($address) {
                        $addressJson             = $address->toJson();
                        $order->delivery_address = $addressJson;
                    }
                    $order->payment_option_id     = $request->payment_option_id;
                    $order->payment_method        = $request->payment_option_id;
                    //NOTE:by default $order->type value will be saved as 'product'(for grocery & paharmacy the type value is product)
                    $order->special_instruction = isset($cart->specific_instructions) ? $cart->specific_instructions : '';
                    $order->save();
                    $customerCurrency = ClientCurrency::where('currency_id', $user->currency)->first();
                    $clientCurrency = ClientCurrency::where('is_primary', '=', 1)->first();
                    // $cart_products = CartProduct::with('product.pimages', 'product.variants', 'product.taxCategory.taxRate', 'coupon', 'product.addon')->where('cart_id', $cart->id)->where('status', [0, 1])->where('cart_id', $cart->id)->orderBy('created_at', 'asc')->get();
                    $cart_products = CartProduct::with([
                        'product.pimages',
                        'product.variants',
                        'product.taxCategory.taxRate',
                        'getAppliedCoupon',
                        'product.addon'
                    ])
                        ->where('cart_id', $cart->id)
                        ->whereIn('status', [0, 1])
                        ->orderBy('created_at', 'asc')
                        ->get();

                    // Manually map getAppliedCoupon to coupon
                    $cart_products->each(function ($cart_product) {
                        $cart_product->coupon = $cart_product->getAppliedCoupon;
                        unset($cart_product->getAppliedCoupon); // Unset the getAppliedCoupon attribute if not needed
                    });
                    $total_subscription_discount = $total_delivery_fee = 0;
                    foreach ($cart_products->groupBy('vendor_id') as $vendor_id => $vendor_cart_products) {
                        $delivery_fee = 0;
                        $deliver_charge = $delivery_fee_charges = 0.00;
                        $delivery_count = 0;
                        $vendor_payable_amount = 0;
                        $vendor_discount_amount = 0;
                        $order_vendor = new OrderVendor;
                        $order_vendor->status = 0;
                        $order_vendor->user_id = $user->id;
                        $order_vendor->order_id = $order->id;
                        $order_vendor->vendor_id = $vendor_id;
                        $order_vendor->save();
                        foreach ($vendor_cart_products as $vendor_cart_product) {
                            $variant = $vendor_cart_product->product->variants->where('id', $vendor_cart_product->variant_id)->first();
                            $quantity_price = 0;
                            $divider = (empty($vendor_cart_product->doller_compare) || $vendor_cart_product->doller_compare < 0) ? 1 : $vendor_cart_product->doller_compare;
                            $price_in_currency = $variant->price / $divider;
                            $price_in_dollar_compare = $price_in_currency * $clientCurrency->doller_compare;
                            $quantity_price = $price_in_dollar_compare * $vendor_cart_product->quantity;
                            $payable_amount = $payable_amount + $quantity_price;
                            $vendor_payable_amount = $vendor_payable_amount + $quantity_price;
                            $product_taxable_amount = 0;
                            $product_payable_amount = 0;
                            $vendor_taxable_amount = 0;
                            if ($vendor_cart_product->product['taxCategory']) {
                                foreach ($vendor_cart_product->product['taxCategory']['taxRate'] as $tax_rate_detail) {
                                    $rate = round($tax_rate_detail->tax_rate);
                                    $tax_amount = ($price_in_dollar_compare * $rate) / 100;
                                    $product_tax = $quantity_price * $rate / 100;
                                    $taxable_amount = $taxable_amount + $product_tax;
                                    $payable_amount = $payable_amount + $product_tax;
                                    $vendor_payable_amount = $vendor_payable_amount;
                                }
                            }
                            //if we are doing delivery for this vendor,get the delivery price from dispatcher,
                            //here by default we are passing delivery as we are doing delivery for all the grocery/pharmacy vendors
                            if ($action == 'delivery') {
                                //check for delivery enabled against vendor or not
                                if ((!empty($vendor_cart_product->product->Requires_last_mile)) && ($vendor_cart_product->product->Requires_last_mile == 1)) {
                                    $delivery_fee = $this->getDeliveryFeeDispatcher($vendor_cart_product->vendor_id);
                                    if (!empty($delivery_fee) && $delivery_count == 0) {
                                        $delivery_count = 1;
                                        $vendor_cart_product->delivery_fee = number_format($delivery_fee, 2, '.', '');
                                        // $payable_amount = $payable_amount + $delivery_fee;
                                        $delivery_fee_charges = $delivery_fee;
                                        $latitude = $request->header('latitude');
                                        $longitude = $request->header('longitude');
                                        $vendor_cart_product->vendor = $this->getVendorDistanceWithTime($vendor_cart_product->vendor, $client_preference, $latitude, $longitude);
                                        $order_vendor->order_pre_time = ($vendor_cart_product->vendor->order_pre_time > 0) ? $vendor_cart_product->vendor->order_pre_time : 0;
                                        if ($vendor_cart_product->vendor->timeofLineOfSightDistance > 0) {
                                            $order_vendor->user_to_vendor_time = $vendor_cart_product->vendor->timeofLineOfSightDistance - $order_vendor->order_pre_time;
                                        }
                                    }
                                }
                            }
                            $vendor_taxable_amount += $taxable_amount;
                            $total_amount += $vendor_cart_product->quantity * $variant->price;
                            $order_product = new OrderProduct;
                            $order_product->order_vendor_id = $order_vendor->id;
                            $order_product->order_id = $order->id;
                            $order_product->price = $variant->price;
                            $order_product->prorated_price = $variant->price; //need to correct to actual prorated price
                            $order_product->quantity = $vendor_cart_product->quantity;
                            $order_product->vendor_id = $vendor_cart_product->vendor_id;
                            $order_product->product_id = $vendor_cart_product->product_id;
                            $order_product->created_by = $vendor_cart_product->created_by;
                            $order_product->variant_id = $vendor_cart_product->variant_id;
                            $order_product->product_name = $vendor_cart_product->product->title ?? $vendor_cart_product->product->sku;
                            $order_product->product_dispatcher_tag = $vendor_cart_product->product->tags;
                            if ($vendor_cart_product->product->pimage) {
                                $order_product->image = $vendor_cart_product->product->pimage->first() ? $vendor_cart_product->product->pimage->first()->path : '';
                            }
                            $order_product->save();
                            if (!empty($vendor_cart_product->addon)) {
                                foreach ($vendor_cart_product->addon as $ck => $addon) {
                                    $opt_quantity_price = 0;
                                    $opt_price_in_currency = $addon->option->price;
                                    $opt_price_in_doller_compare = $opt_price_in_currency * $clientCurrency->doller_compare;
                                    $opt_quantity_price = $opt_price_in_doller_compare * $order_product->quantity;
                                    $total_amount = $total_amount + $opt_quantity_price;
                                    $payable_amount = $payable_amount + $opt_quantity_price;
                                    $vendor_payable_amount = $vendor_payable_amount + $opt_quantity_price;
                                }
                            }
                            $cart_addons = CartAddon::where('cart_product_id', $vendor_cart_product->id)->get();
                            if ($cart_addons) {
                                foreach ($cart_addons as $cart_addon) {
                                    $orderAddon = new OrderProductAddon;
                                    $orderAddon->addon_id = $cart_addon->addon_id;
                                    $orderAddon->option_id = $cart_addon->option_id;
                                    $orderAddon->order_product_id = $order_product->id;
                                    $orderAddon->save();
                                }
                                if (($request->payment_option_id != 7) && ($request->payment_option_id != 6) && ($request->payment_option_id != 8) && ($request->payment_option_id != 9)) { // if not mobbex, payfast
                                    CartAddon::where('cart_product_id', $vendor_cart_product->id)->delete();
                                }
                            }
                        }
                        $coupon_id = null;
                        $coupon_name = null;
                        $actual_amount = $vendor_payable_amount;
                        if ($vendor_cart_product->coupon && !empty($vendor_cart_product->coupon->promo)) {
                            $coupon_id = $vendor_cart_product->coupon->promo->id;
                            $coupon_name = $vendor_cart_product->coupon->promo->name;
                            if ($vendor_cart_product->coupon->promo->promo_type_id == 2) {
                                $coupon_discount_amount = $vendor_cart_product->coupon->promo->amount;
                                $total_discount += $coupon_discount_amount;
                                $vendor_payable_amount -= $coupon_discount_amount;
                                $vendor_discount_amount += $coupon_discount_amount;
                            } else {
                                $coupon_discount_amount = ($quantity_price * $vendor_cart_product->coupon->promo->amount / 100);
                                $final_coupon_discount_amount = $coupon_discount_amount * $clientCurrency->doller_compare;
                                $total_discount += $final_coupon_discount_amount;
                                $vendor_payable_amount -= $final_coupon_discount_amount;
                                $vendor_discount_amount += $final_coupon_discount_amount;
                            }
                        }
                        $total_delivery_fee += $delivery_fee;
                        $order_vendor->coupon_id = $coupon_id;
                        $order_vendor->coupon_code = $coupon_name;
                        $order_vendor->order_status_option_id = 1;
                        $order_vendor->delivery_fee = $total_delivery_fee;
                        $order_vendor->subtotal_amount = $actual_amount;
                        $order_vendor->payable_amount = $vendor_payable_amount + $total_delivery_fee;
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
                        $vendor_id = $vendor_id;
                    }
                    $loyalty_points_earned = LoyaltyCard::getLoyaltyPoint($loyalty_points_used, $payable_amount);
                    if (in_array(1, $subscription_features)) {
                        $total_subscription_discount = $total_subscription_discount + $total_delivery_fee;
                    }
                    $total_discount = $total_discount + $total_subscription_discount;
                    $order->total_amount = $total_amount;
                    $order->total_discount = $total_discount;
                    $order->taxable_amount = $taxable_amount;
                    $payable_amount = $payable_amount + $total_delivery_fee - $total_discount;
                    if ($loyalty_amount_saved > 0) {
                        if ($loyalty_amount_saved > $payable_amount) {
                            $loyalty_amount_saved = $payable_amount;
                            $loyalty_points_used = $payable_amount * $redeem_points_per_primary_currency;
                        }
                    }
                    $payable_amount = $payable_amount - $loyalty_amount_saved;
                    $wallet_amount_used = 0;
                    if ($user->balanceFloat > 0 && $cart->use_wallet > 0) {
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
                    $tip_amount = 0;
                    if ((isset($request->tip)) && ($request->tip != '') && ($request->tip > 0)) {
                        $tip_amount = $request->tip;
                        $tip_amount = ($tip_amount / $customerCurrency->doller_compare) * $clientCurrency->doller_compare;
                        $order->tip_amount = number_format($tip_amount, 2);
                    }
                    $payable_amount = $payable_amount + $tip_amount;
                    $order->total_delivery_fee = $total_delivery_fee;
                    $order->loyalty_points_used = $loyalty_points_used;
                    $order->loyalty_amount_saved = $loyalty_amount_saved;
                    $order->loyalty_points_earned = $loyalty_points_earned['per_order_points'];
                    $order->loyalty_membership_id = $loyalty_points_earned['loyalty_card_id'];
                    $order->scheduled_date_time = $cart->schedule_type == 'schedule' ? $cart->scheduled_date_time : null;
                    $order->subscription_discount = $total_subscription_discount;
                    $order->luxury_option_id = (isset($luxury_option->id) && !empty($luxury_option->id)) ?? 0;
                    $order->payable_amount = $payable_amount;
                    if (($payable_amount == 0) || (($request->has('transaction_id')) && (!empty($request->transaction_id)))) {
                        $order->payment_status = 1;
                    }
                    $order->save();
                    $this->sendSuccessEmail($request, $order);
                    $this->sendSuccessSMS($request, $order);
                    $cart->use_wallet = 0;
                    $cart->tip_amount = 0;
                    $cart->paid = 0;
                    $cart->save();
                    if (($request->payment_option_id != 7) && ($request->payment_option_id != 6) && ($request->payment_option_id != 8) && ($request->payment_option_id != 9)) { // if not mobbex, payfast
                        Cart::where('id', $cart->id)->update(['schedule_type' => NULL, 'scheduled_date_time' => NULL]);
                        CartCoupon::where('cart_id', $cart->id)->delete();
                        CartProduct::where('cart_id', $cart->id)->delete();
                        CartProductPrescription::where('cart_id', $cart->id)->delete();
                    }
                    if (($request->payment_option_id != 1) && ($request->payment_option_id != 2) && ($request->has('transaction_id')) && (!empty($request->transaction_id))) {
                        Payment::insert([
                            'date' => date('Y-m-d'),
                            'order_id' => $order->id,
                            'transaction_id' => $request->transaction_id,
                            'balance_transaction' => $order->payable_amount,
                        ]);
                    }
                    $order = $order->with(['vendors:id,order_id,vendor_id', 'user_vendor', 'vendors.vendor'])->where('order_number', $order->order_number)->first();
                    if (($request->payment_option_id != 7) && ($request->payment_option_id != 6) && ($request->payment_option_id != 8) && ($request->payment_option_id != 9)) { // if not mobbex, payfast
                        $code = $request->header('code');
                        if (!empty($order->vendors)) {
                            foreach ($order->vendors as $vendor_value) {
                                $vendor_order_detail = $this->minimize_orderDetails_for_notification($order->id, $vendor_value->vendor_id);
                                $user_vendors = UserVendor::where(['vendor_id' => $vendor_value->vendor_id])->pluck('user_id');
                                $vendorDetail = $vendor_value->vendor;
                                if ($vendorDetail->auto_accept_order == 0 && $vendorDetail->auto_reject_time > 0) {
                                    $clientDetail = Client::on('mysql')->where(['code' => $client_preference->client_code])->first();
                                    AutoRejectOrderCron::on('mysql')->create(['database_host' => $clientDetail->database_path, 'database_name' => $clientDetail->database_name, 'database_username' => $clientDetail->database_username, 'database_password' => $clientDetail->database_password, 'order_vendor_id' => $vendor_value->id, 'auto_reject_time' => Carbon::now()->addMinute($vendorDetail->auto_reject_time)]);
                                }
                                $this->sendOrderPushNotificationVendors($user_vendors, $vendor_order_detail, $code);
                            }
                        }
                        $vendor_order_detail = $this->minimize_orderDetails_for_notification($order->id);
                        $super_admin = User::where('is_superadmin', 1)->pluck('id');
                        $this->sendOrderPushNotificationVendors($super_admin, $vendor_order_detail, $code);
                        // $user_admins = User::where(function ($query) {
                        //     $query->where(['is_superadmin' => 1]);
                        // })->pluck('id')->toArray();
                        // $user_vendors = [];
                        // if (!empty($order->user_vendor) && count($order->user_vendor) > 0) {
                        //     $user_vendors = $order->user_vendor->pluck('user_id')->toArray();
                        // }
                        // $order->admins = array_unique(array_merge($user_admins, $user_vendors));

                        // // $this->sendOrderNotification($user->id);
                        // $this->sendOrderPushNotificationVendors($order->admins, ['id' => $order->id], $code);
                    }

                    # if payment type cash on delivery or payment status is 'Paid'
                    if (($order->payment_option_id == 1) || (($order->payment_option_id != 1) && ($order->payment_status == 1))) {
                        # if vendor selected auto accept
                        $autoaccept = $this->autoAcceptOrderIfOn($order->id);
                    }

                    DB::commit();

                    return $this->successResponse($order, __('Order placed successfully.'), 201);
                }
            } else {
                return $this->errorResponse(['error' => __('Empty cart.')], 404);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    # if vendor selected auto accepted order
    public function autoAcceptOrderIfOn($order_id)
    {
        $order_vendors = OrderVendor::where('order_id', $order_id)->whereHas('vendor', function ($q) {
            $q->where('auto_accept_order', 1);
        })->get();
        Log::info($order_vendors);
        foreach ($order_vendors as $ov) {
            Log::info($ov);
            Log::info($ov->order_id);
            $request = $ov;

            DB::beginTransaction();
            //try {

            $request->order_id = $ov->order_id;
            Log::info($ov->order_id);
            Log::info($request->order_id);
            $request->vendor_id = $ov->vendor_id;
            $request->order_vendor_id = $ov->id;
            $request->status_option_id = 2;
            $timezone = Auth::user()->timezone;
            Log::info($request);
            $vendor_order_status_check = VendorOrderStatus::where('order_id', $request->order_id)->where('vendor_id', $request->vendor_id)->where('order_status_option_id', $request->status_option_id)->first();
            Log::info($vendor_order_status_check);
            if (!$vendor_order_status_check) {
                $vendor_order_status = new VendorOrderStatus();
                $vendor_order_status->order_id = $request->order_id;
                $vendor_order_status->vendor_id = $request->vendor_id;
                $vendor_order_status->order_vendor_id = $request->order_vendor_id;
                $vendor_order_status->order_status_option_id = $request->status_option_id;
                $vendor_order_status->save();
                if ($request->status_option_id == 2) {
                    Log::info($request->status_option_id);
                    $order_dispatch = $this->checkIfanyProductLastMileon($request);
                    if ($order_dispatch && $order_dispatch == 1)
                        $stats = $this->insertInVendorOrderDispatchStatus($request);
                }
                OrderVendor::where('vendor_id', $request->vendor_id)->where('order_id', $request->order_id)->update(['order_status_option_id' => $request->status_option_id]);
                $this->ProductVariantStoke($order_id);
                DB::commit();
                // $this->sendSuccessNotification(Auth::user()->id, $request->vendor_id);
            }
            // } catch(\Exception $e){
            // DB::rollback();
            // Log::info($e->getMessage());
            // }
        }
    }

    /// ******************  check If any Product Last Mile on   ************************ ///////////////
    public function checkIfanyProductLastMileon($request)
    {
        $order_dispatchs = 2;
        $checkdeliveryFeeAdded = OrderVendor::where(['order_id' => $request->order_id, 'vendor_id' => $request->vendor_id])->first();
        $dispatch_domain = $this->getDispatchDomain();
        if ($dispatch_domain && $dispatch_domain != false) {
            if ($checkdeliveryFeeAdded && $checkdeliveryFeeAdded->delivery_fee > 0.00)
                $order_dispatchs = $this->placeRequestToDispatch($request->order_id, $request->vendor_id, $dispatch_domain);


            if ($order_dispatchs && $order_dispatchs == 1)
                return 1;
        }


        $dispatch_domain_ondemand = $this->getDispatchOnDemandDomain();
        if ($dispatch_domain_ondemand && $dispatch_domain_ondemand != false) {
            $ondemand = 0;
            Log::info($dispatch_domain_ondemand);
            foreach ($checkdeliveryFeeAdded->products as $key => $prod) {
                if (isset($prod->product_dispatcher_tag) && !empty($prod->product_dispatcher_tag) && $prod->product->category->categoryDetail->type_id == 8) {
                    $dispatch_domain_ondemand = $this->getDispatchOnDemandDomain();
                    if ($dispatch_domain_ondemand && $dispatch_domain_ondemand != false && $ondemand == 0  && $checkdeliveryFeeAdded->delivery_fee <= 0.00) {
                        $order_dispatchs = $this->placeRequestToDispatchOnDemand($request->order_id, $request->vendor_id, $dispatch_domain_ondemand);
                        if ($order_dispatchs && $order_dispatchs == 1) {
                            $ondemand = 1;
                            return 1;
                        }
                    }
                }
            }
        }

        return 2;
    }


    // place Request To Dispatch
    public function placeRequestToDispatch($order, $vendor, $dispatch_domain)
    {
        try {

            $order = Order::find($order);
            $customer = User::find($order->user_id);
            $cus_address = UserAddress::find($order->address_id);
            $tasks = array();
            if ($order->payment_method == 1) {
                $cash_to_be_collected = 'Yes';
                $payable_amount = $order->payable_amount;
            } else {
                $cash_to_be_collected = 'No';
                $payable_amount = 0.00;
            }
            $dynamic = uniqid($order->id . $vendor);
            $call_back_url = route('dispatch-order-update', $dynamic);
            $vendor_details = Vendor::where('id', $vendor)->select('id', 'name', 'latitude', 'longitude', 'address')->first();
            $tasks = array();
            $meta_data = '';

            $team_tag = null;
            if (!empty($dispatch_domain->last_mile_team))
                $team_tag = $dispatch_domain->last_mile_team;


            $tasks[] = array(
                'task_type_id' => 1,
                'latitude' => $vendor_details->latitude ?? '',
                'longitude' => $vendor_details->longitude ?? '',
                'short_name' => '',
                'address' => $vendor_details->address ?? '',
                'post_code' => '',
                'barcode' => '',
            );

            $tasks[] = array(
                'task_type_id' => 2,
                'latitude' => $cus_address->latitude ?? '',
                'longitude' => $cus_address->longitude ?? '',
                'short_name' => '',
                'address' => $cus_address->address ?? '',
                'post_code' => $cus_address->pincode ?? '',
                'barcode' => '',
            );

            $postdata =  [
                'customer_name' => $customer->name ?? 'Dummy Customer',
                'customer_phone_number' => $customer->phone_number ?? rand(111111, 11111),
                'customer_email' => $customer->email ?? null,
                'recipient_phone' => $customer->phone_number ?? rand(111111, 11111),
                'recipient_email' => $customer->email ?? null,
                'task_description' => "Order From :" . $vendor_details->name,
                'allocation_type' => 'a',
                'task_type' => 'now',
                'cash_to_be_collected' => $payable_amount ?? 0.00,
                'barcode' => '',
                'order_team_tag' => $team_tag,
                'call_back_url' => $call_back_url ?? null,
                'task' => $tasks,
                'runrun_order_number' => $order->order_number,
                'special_instruction' => $order->special_instruction
            ];


            $client = new Client([
                'headers' => [
                    'personaltoken' => $dispatch_domain->delivery_service_key,
                    'shortcode' => $dispatch_domain->delivery_service_key_code,
                    'content-type' => 'application/json'
                ]
            ]);

            $url = $dispatch_domain->delivery_service_key_url;
            $res = $client->post(
                $url . '/api/task/create',
                ['form_params' => ($postdata)]
            );
            $response = json_decode($res->getBody(), true);
            if ($response && $response['task_id'] > 0) {
                $up_web_hook_code = OrderVendor::where(['order_id' => $order->id, 'vendor_id' => $vendor])
                    ->update(['web_hook_code' => $dynamic]);
                return 1;
            }
            return 2;
        } catch (\Exception $e) {
            return 2;
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }



    // place Request To Dispatch for On Demand
    public function placeRequestToDispatchOnDemand($order, $vendor, $dispatch_domain)
    {
        try {
            Log::info($order);
            Log::info($vendor);
            Log::info($dispatch_domain);
            $order = Order::find($order);
            $customer = User::find($order->user_id);
            $cus_address = UserAddress::find($order->address_id);
            $tasks = array();
            if ($order->payment_method == 1) {
                $cash_to_be_collected = 'Yes';
                $payable_amount = $order->payable_amount;
            } else {
                $cash_to_be_collected = 'No';
                $payable_amount = 0.00;
            }
            $dynamic = uniqid($order->id . $vendor);
            $call_back_url = route('dispatch-order-update', $dynamic);
            $vendor_details = Vendor::where('id', $vendor)->select('id', 'name', 'latitude', 'longitude', 'address')->first();
            $tasks = array();
            $meta_data = '';

            $unique = Auth::user()->code;
            $team_tag = $unique . "_" . $vendor;


            $tasks[] = array(
                'task_type_id' => 1,
                'latitude' => $vendor_details->latitude ?? '',
                'longitude' => $vendor_details->longitude ?? '',
                'short_name' => '',
                'address' => $vendor_details->address ?? '',
                'post_code' => '',
                'barcode' => '',
            );

            $tasks[] = array(
                'task_type_id' => 2,
                'latitude' => $cus_address->latitude ?? '',
                'longitude' => $cus_address->longitude ?? '',
                'short_name' => '',
                'address' => $cus_address->address ?? '',
                'post_code' => $cus_address->pincode ?? '',
                'barcode' => '',
            );

            $postdata =  [
                'customer_name' => $customer->name ?? 'Dummy Customer',
                'customer_phone_number' => $customer->phone_number ?? rand(111111, 11111),
                'customer_email' => $customer->email ?? null,
                'recipient_phone' => $customer->phone_number ?? rand(111111, 11111),
                'recipient_email' => $customer->email ?? null,
                'task_description' => "Order From :" . $vendor_details->name,
                'allocation_type' => 'a',
                'task_type' => 'now',
                'cash_to_be_collected' => $payable_amount ?? 0.00,
                'barcode' => '',
                'order_team_tag' => $team_tag,
                'call_back_url' => $call_back_url ?? null,
                'task' => $tasks,
                'runrun_order_number' => $order->order_number
            ];


            $client = new Client([
                'headers' => [
                    'personaltoken' => $dispatch_domain->dispacher_home_other_service_key,
                    'shortcode' => $dispatch_domain->dispacher_home_other_service_key_code,
                    'content-type' => 'application/json'
                ]
            ]);

            $url = $dispatch_domain->dispacher_home_other_service_key_url;
            $res = $client->post(
                $url . '/api/task/create',
                ['form_params' => ($postdata)]
            );
            $response = json_decode($res->getBody(), true);
            if ($response && $response['task_id'] > 0) {
                $up_web_hook_code = OrderVendor::where(['order_id' => $order->id, 'vendor_id' => $vendor])
                    ->update(['web_hook_code' => $dynamic]);
                return 1;
            }
            return 2;
        } catch (\Exception $e) {
            return 2;
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }


    # get prefereance if last mile on or off and all details updated in config
    public function getDispatchDomain()
    {
        $preference = ClientPreference::first();
        if ($preference->need_delivery_service == 1 && !empty($preference->delivery_service_key) && !empty($preference->delivery_service_key_code) && !empty($preference->delivery_service_key_url))
            return $preference;
        else
            return false;
    }


    # get prefereance if on demand on in config
    public function getDispatchOnDemandDomain()
    {
        $preference = ClientPreference::first();
        if ($preference->need_dispacher_home_other_service == 1 && !empty($preference->dispacher_home_other_service_key) && !empty($preference->dispacher_home_other_service_key_code) && !empty($preference->dispacher_home_other_service_key_url))
            return $preference;
        else
            return false;
    }

    /// ******************   insert In Vendor Order Dispatch Status   ************************ ///////////////
    public function insertInVendorOrderDispatchStatus($request)
    {
        $update = VendorOrderDispatcherStatus::updateOrCreate([
            'dispatcher_id' => null,
            'order_id' =>  $request->order_id,
            'dispatcher_status_option_id' => 1,
            'vendor_id' =>  $request->vendor_id
        ]);
    }

    public function sendSuccessSMS($request, $order, $vendor_id = '')
    {
        try {
            $prefer = ClientPreference::select('sms_provider', 'sms_key', 'sms_secret', 'sms_from')->first();

            $user = Auth::user();
            if ($user) {
                $customerCurrency = ClientCurrency::join('currencies as cu', 'cu.id', 'client_currencies.currency_id')->where('client_currencies.currency_id', $user->currency)->first();
                $currSymbol = $customerCurrency->symbol;
                if ($user->dial_code == "971") {
                    $to = '+' . $user->dial_code . "0" . $user->phone_number;
                } else {
                    $to = '+' . $user->dial_code . $user->phone_number;
                }
                $provider = $prefer->sms_provider;
                $body = "Hi " . $user->name . ", your order " . $order->order_number . " for " . $currSymbol . $order->payable_amount . " is placed successfully!";
                if (!empty($prefer->sms_key) && !empty($prefer->sms_secret)) {
                    if (!empty($data->sms_from)) {
                        $send = $this->sendSms($provider, $prefer->sms_key, $prefer->sms_secret, $prefer->sms_from, $to, $body);
                    }
                }
            }
        } catch (\Exception $ex) {
        }
    }

    public function sendSuccessEmail($request, $order)
    {
        if ((isset($request->user_id)) && (!empty($request->user_id))) {
            $user = User::find($request->user_id);
        } elseif ((isset($request->auth_token)) && (!empty($request->auth_token))) {
            $user = User::where('auth_token', $request->auth_token)->first();
        } else {
            $user = Auth::user();
        }
        // Prepare product HTML based on order type
        $currSymbol = 'AED';
        $returnHTML = '';
        
        try {
            if ($order->type == 7 || $order->type == 9 || $order->type == 10) {
                $DeliveryCart = DeliveryCart::with('product', 'vendor', 'category')->where('id', $request->id)->first();
                $location = DeliveryCartTasks::where('delivery_cart_id', $DeliveryCart->id)->get();
                
                $pick_up = '';
                $drop_off = '';
                
                // Check if pickup is enabled for this category
                if ($DeliveryCart->category && $DeliveryCart->category->is_pickup_enabled == 1 && isset($location[0])) {
                    $pick_up = $location[0]->building_villa_flat_no . " " . $location[0]->street . " " . $location[0]->area . " " . $location[0]->city . " " . $location[0]->address;
                }
                
                // Check if dropoff is enabled for this category
                if ($DeliveryCart->category && $DeliveryCart->category->is_dropoff_enabled == 1 && isset($location[1])) {
                    $drop_off = $location[1]->building_villa_flat_no . " " . $location[1]->street . " " . $location[1]->area . " " . $location[1]->city . " " . $location[1]->address;
                }
                
                $returnHTML = view('email.orderProducts')->with(['order' => $order, 'currencySymbol' => $currSymbol, 'DeliveryCart' => $DeliveryCart, 'pick_up' => $pick_up, 'drop_off' => $drop_off])->render();
            } else {
                if ($user) {
                    $cart = Cart::select('id', 'is_gift', 'item_count')->with('coupon.promo')->where('status', '0')->where('user_id', $user->id)->first();
                } else {
                    $cart = Cart::select('id', 'is_gift', 'item_count')->with('coupon.promo')->where('status', '0')->where('unique_identifier', session()->get('_token'))->first();
                }
                if ($cart) {
                    $orderController = new \App\Http\Controllers\Front\OrderController();
                    $cartData = $orderController->getCart($cart, 0, '1', '1');
                }
                $address = UserAddress::where('id', $request->address_id)->first();
                $addressStr = $address ? ($address->address . ', ' . $address->state . ', ' . $address->country . ', ' . $address->pincode) : '';
                $returnHTML = view('email.orderVendorProducts')->with(['cartData' => $cartData ?? [], 'currencySymbol' => $currSymbol, 'address' => $addressStr])->render();
            }
            
            // Use new EmailService for multi-language and RTL support
            $this->sendEmail(
                $user->email,
                5, // Order success email template ID
                [
                    'customer_name' => ucwords($user->name),
                    'order_id' => $order->order_number,
                    'products' => $returnHTML,
                    'order-placed-email-banner' => asset("images/email/order-placed-email-banner.jpg")
                ],
                [],
                $user->language_id ?? null,
                true,
                'verify_email'
            );
        } catch (\Exception $e) {
            Log::info("Error From Order Email: " . $e->getMessage());
        }
    }

    public function sendOrderNotification($id)
    {
        $token = UserDevice::whereNotNull('device_token')->pluck('device_token')->where('user_id', $id)->toArray();
        $from = env('FIREBASE_SERVER_KEY');

        $notification_content = NotificationTemplate::where('id', 1)->first();
        if ($notification_content) {
            $headers = [
                'Authorization: key=' . $from,
                'Content-Type: application/json',
            ];
            $data = [
                "registration_ids" => $token,
                "notification" => [
                    'title' => $notification_content->label,
                    'body'  => $notification_content->content,
                ]
            ];
            $dataString = $data;

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($dataString));
            $result = curl_exec($ch);
            curl_close($ch);
        }
    }
    public function getOrdersList(Request $request)
    {
        $user = Auth::user();
        $order_status_options = [];
        $paginate = $request->has('limit') ? $request->limit : 12;
        $type = $request->has('type') ? $request->type : 'active';
        $orders = OrderVendor::where('user_id', $user->id)->orderBy('id', 'DESC');
        switch ($type) {
            case 'active':
                $orders->whereNotIn('order_status_option_id', [6, 3]);
                break;
            case 'past':
                $orders->whereIn('order_status_option_id', [6, 3]);
                break;
            case 'schedule':
                $order_status_options = [10];
                $orders->whereHas('status', function ($query) use ($order_status_options) {
                    $query->whereIn('order_status_option_id', $order_status_options);
                });
                break;
        }
        $orders = $orders->with(['orderDetail', 'vendor:id,name,logo,banner'])
            ->whereHas('orderDetail', function ($q1) {
                $q1->where('orders.payment_status', 1)->whereNotIn('orders.payment_option_id', [1]);
                $q1->orWhere(function ($q2) {
                    $q2->where('orders.payment_option_id', 1);
                });
            })
            ->paginate($paginate);
        foreach ($orders as $order) {
            $order_item_count = 0;
            $order->user_name = $user->name;
            $order->user_image = $user->image;
            $order->date_time = convertDateTimeInTimeZone($order->orderDetail->created_at, $user->timezone);
            $order->payment_option_title = __($order->orderDetail->paymentOption->title ?? '');
            $order->order_number = $order->orderDetail->order_number;
            $product_details = [];
            $vendor_order_status = VendorOrderStatus::with('OrderStatusOption')->where('order_id', $order->orderDetail->id)->where('vendor_id', $order->vendor_id)->orderBy('id', 'DESC')->first();
            if ($vendor_order_status) {
                $order->order_status =  ['current_status' => ['id' => $vendor_order_status->OrderStatusOption->id, 'title' => __($vendor_order_status->OrderStatusOption->title), 'updated_date' => $vendor_order_status->updated_at]];
            } else {
                $order->current_status = null;
            }
            foreach ($order->products as $product) {
                $order_item_count += $product->quantity;
                $product_details[] = array(
                    'image_path' => $product->media->first() ? $product->media->first()->image->path : $product->image,
                    'price' => $product->price,
                    'qty' => $product->quantity,
                    'category_type' => $product->product->category->categoryDetail->type->title ?? '',
                    'product_id' => $product->product_id,
                    'title' => $product->product_name,
                );
            }
            if ($order->delivery_fee > 0) {
                $order_pre_time = ($order->order_pre_time > 0) ? $order->order_pre_time : 0;
                $user_to_vendor_time = ($order->user_to_vendor_time > 0) ? $order->user_to_vendor_time : 0;
                $ETA = $order_pre_time + $user_to_vendor_time;
                $order->ETA = ($ETA > 0) ? $this->formattedOrderETA($ETA, $order->created_at, $order->orderDetail->scheduled_date_time) : convertDateTimeInTimeZone($order->created_at, $user->timezone, 'h:i A');
            }
            if (!empty($order->orderDetail->scheduled_date_time)) {
                $order->scheduled_date_time = convertDateTimeInTimeZone($order->orderDetail->scheduled_date_time, $user->timezone, 'M d, Y h:i A');
            }
            $luxury_option_name = '';
            if ($order->orderDetail->luxury_option_id > 0) {
                $luxury_option = LuxuryOption::where('id', $order->orderDetail->luxury_option_id)->first();
                if ($luxury_option->title == 'takeaway') {
                    $luxury_option_name = $this->getNomenclatureName('Takeaway', $user->language, false);
                } elseif ($luxury_option->title == 'dine_in') {
                    $luxury_option_name = __('Dine-In');
                } else {
                    $luxury_option_name = __('Delivery');
                }
            }
            $order->luxury_option_name = $luxury_option_name;
            $order->product_details = $product_details;
            $order->item_count = $order_item_count;
            unset($order->user);
            unset($order->products);
            unset($order->paymentOption);
            unset($order->payment_option_id);
            // unset($order->orderDetail);
        }
        return $this->successResponse($orders, '', 201);
    }

    public function getActiveOrders(Request $request)
    {
        $user = Auth::user();
        $paginate = $request->has('limit') ? $request->limit : 12;
        
        // Get only active orders where rider has started pickup till completion, excluding customer cancellations
        $orders = OrderVendor::where('user_id', $user->id)
            ->whereNotIn('order_status_option_id', [6, 3]) // Exclude completed (6) and customer cancelled (3) orders
            //->where('dispatcher_status_option_id', '>=', 3) // Only orders where rider has started pickup (Started=3, Arrived=4, Completed=5)
            ->orderBy('id', 'DESC')
            ->with(['orderDetail', 'products.media.image', 'products.product.category.categoryDetail.type'])
            ->paginate($paginate);

        $formatted_orders = [];
        
        foreach ($orders as $order) {
            $product_details = [];
            
            // Get order status
            $vendor_order_status = VendorOrderStatus::with('OrderStatusOption')
                ->where('order_id', $order->orderDetail->id)
                ->where('vendor_id', $order->vendor_id)
                ->orderBy('id', 'DESC')
                ->first();
            
            // Build product details
            foreach ($order->products as $product) {
                $product_details[] = array(
                    'image_path' => $product->media->first() ? $product->media->first()->image->path : $product->image,
                    'price' => $product->price,
                    'qty' => $product->quantity,
                    'category_type' => $product->product->category->categoryDetail->type->title ?? '',
                    'product_id' => $product->product_id,
                    'title' => $product->product_name,
                );
            }
            
            // Get delivery agent information
            $delivery_agent = null;
            // if (($order->orderDetail->type == 7 || $order->orderDetail->type == 9 || $order->orderDetail->type == 10) && $order->dispatch_traking_url) {
            //     try {
            //         $url = str_replace("order", "order-details", $order->dispatch_traking_url);
            //         $response = Http::get($url);
            //         if ($response->status() == 200) {
            //             $response_data = json_decode($response->body());
                        
            //             // Provide rider details if rider assigned
            //             if ($response_data->order->driver_id != NULL && $response_data->tasks[0]->task_status > 1) {
            //                 $delivery_agent = [
            //                     'name' => $response_data->order->name ?? '',
            //                     'phone_number' => $response_data->order->phone_number ?? '',
            //                     'profile_picture' => $response_data->agent_image ?? '',
            //                     'vehicle_type_name' => $response_data->order->vehicle_type_name ?? '',
            //                     'vehicle_type_icon' => $response_data->vehicle_type_icon ?? '',
            //                     'agent_location' => $response_data->agent_location ?? []
            //                 ];
            //             }
            //         }
            //     } catch (\Exception $e) {
            //         // Log error if dispatcher API call fails
            //         Log::info("Dispatcher API call failed for order: " . $order->orderDetail->id . " - " . $e->getMessage());
            //     }
            // }
            
            // Format order data with only requested fields
            $formatted_order = [
                'order_id' => $order->orderDetail->id,
                'order_number' => $order->orderDetail->order_number,
                'vendor_id' => $order->vendor_id,
                'order_status' => $vendor_order_status ? [
                    'current_status' => [
                        'title' => __($vendor_order_status->OrderStatusOption->title),
                        'id' => $vendor_order_status->OrderStatusOption->id
                    ]
                ] : null,
                'dispatcher_status' => [
                    'id' => $order->dispatcher_status_option_id,
                    'title' => $this->getDispatcherStatusTitle($order->dispatcher_status_option_id)
                ],
                'product_details' => $product_details,
                'item' => [
                    'payable_amount' => $order->orderDetail->payable_amount ?? 0
                ],
                'delivery_agent' => $delivery_agent
            ];
            
            $formatted_orders[] = $formatted_order;
        }
        
        // Get all order statuses for common object
        $order_statuses = OrderStatusOption::where('type', 1) // Only order statuses (type 1), not dispatch statuses (type 2)
            ->where('status', 1) // Only active statuses
            ->orderBy('sort_order', 'ASC')
            ->get(['id', 'title','image','sort_order','description'])
            ->map(function ($status) {
                return [
                    'id' => $status->id,
                    'title' => __($status->title),
                    'image' => $status->image,
                    'sort_order' => $status->sort_order,
                    'description' => __($status->description),
                ];
            });
        
        // Create paginated response with formatted data and order_statuses
        $paginated_response = [
            'current_page' => $orders->currentPage(),
            'data' => $formatted_orders,
            'first_page_url' => $orders->url(1),
            'from' => $orders->firstItem(),
            'last_page' => $orders->lastPage(),
            'last_page_url' => $orders->url($orders->lastPage()),
            'next_page_url' => $orders->nextPageUrl(),
            'path' => $orders->path(),
            'per_page' => $orders->perPage(),
            'prev_page_url' => $orders->previousPageUrl(),
            'to' => $orders->lastItem(),
            'total' => $orders->total(),
            'order_statuses' => $order_statuses
        ];
        
        return $this->successResponse($paginated_response, 'Active orders retrieved successfully', 200);
    }

    public function postOrderDetail(Request $request)
    {
        try {
            $user = Auth::user();
            $order_item_count = 0;
            $language_id = $user->language;
            $order_id = $request->order_id;
            $vendor_id = $request->vendor_id;
            if ($vendor_id) {
                $order = Order::with([
                    'vendors' => function ($q) use ($vendor_id) {
                        $q->where('vendor_id', $vendor_id);
                    },
                    'vendors.vendor',
                    'vendors.products' => function ($q) use ($vendor_id) {
                        $q->where('vendor_id', $vendor_id);
                    },
                    'vendors.products.translation' => function ($q) use ($language_id) {
                        $q->select('id', 'product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description');
                        $q->where('language_id', $language_id);
                    },
                    'vendors.products.category.categoryDetail' => function ($q) {
                        $q->select('id', 'slug', 'order_details_image', 'is_pickup_enabled', 'is_dropoff_enabled');
                    },
                    'vendors.products.pvariant.vset.optionData.trans',
                    'vendors.products.addon',
                    'vendors.coupon',
                    'address',
                    'vendors.products.productRating',
                    'vendors.allStatus',
                    'vendors.rejectingReason'
                ])
                    ->where(function ($q1) {
                        $q1->where('payment_status', 1)->whereNotIn('payment_option_id', [1]);
                        $q1->orWhere(function ($q2) {
                            $q2->where('payment_option_id', 1);
                        });
                    })
                    ->where('id', $order_id)->first();
            } else {
                $order = Order::with(
                    [
                        'vendors.vendor',
                        'vendors.products.translation' => function ($q) use ($language_id) {
                            $q->select('id', 'product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description');
                            $q->where('language_id', $language_id);
                        },
                        'vendors.products.category.categoryDetail' => function ($q) {
                            $q->select('id', 'slug','order_details_image', 'is_pickup_enabled', 'is_dropoff_enabled');
                        },
                        'vendors.products.pvariant.vset.optionData.trans',
                        'vendors.products.addon',
                        'vendors.coupon',
                        'address',
                        'vendors.products.productRating',
                        'vendors.rejectingReason'
                    ]
                )
                    ->where(function ($q1) {
                        $q1->where('payment_status', 1)->whereNotIn('payment_option_id', [1]);
                        $q1->orWhere(function ($q2) {
                            $q2->where('payment_option_id', 1);
                        });
                    })
                    ->where('user_id', $user->id)->where('id', $order_id)->first();
            }
            if ($order) {
                $CartData = DeliveryCart::select('estimated_time', 'item_cat_id')->find($order->delivery_cart_id);
                if (!empty($CartData) && !empty($CartData->estimated_time)) {
                    $estimatedAt = Carbon::parse($CartData->estimated_time)->locale(app()->getLocale());
                    $timeFormatted = $estimatedAt->translatedFormat('g:i A');
                    $dateFormatted = $estimatedAt->translatedFormat('d F Y');
                    $order->sla_message = __(':time on :date', [
                        'time' => $timeFormatted,
                        'date' => $dateFormatted,
                    ]);
                } else {
                    // Handle case where estimated_time is not set
                    $order->sla_message = __('Estimated completion time is unavailable.');
                }
                $order->item_category  = ItemCategory::find($CartData->item_cat_id ?? null);
                $order->delivery_address = json_decode($order->delivery_address, true);
                $order->user_name = $order->user->name;
                $order->user_image = $order->user->image;
                $order->payment_option_title = __($order->paymentOption->title);
                $order->created_date = Carbon::parse($order->created_at)->setTimezone($user->timezone)->format('M d, Y h:i A');
                $order->category_slug = $order->vendors->first()?->products?->first()?->category?->categoryDetail?->slug;

                foreach ($order->vendors as $vendor) {
                    $vendor_order_status = VendorOrderStatus::with('OrderStatusOption')->where('order_id', $order_id)->where('vendor_id', $vendor->vendor->id)->orderBy('id', 'DESC')->first();
                    if ($vendor_order_status) {
                        $vendor->order_status =  ['current_status' => [
                            'id' => $vendor_order_status->OrderStatusOption->id,
                            'title' => $vendor_order_status->OrderStatusOption->title,
                            'image' => $vendor_order_status->OrderStatusOption->image
                        ]];
                        $order_statuses = OrderStatusOption::select('id', 'title', 'image', 'sort_order', 'description')->orderBy('sort_order')->get();
                        $curent_order_status = $vendor_order_status->OrderStatusOption->id;
                        $order_statuses->each(function ($item) use ($curent_order_status) {
                            $item['is_current'] = ($curent_order_status == $item['id']) ? true : false;
                            $item['title'] = __($item['title']);
                            $item['description'] = __($item['description']);
                            return $item;
                        });
                        $vendor->order_statuses = $order_statuses;
                    } else {
                        $vendor->current_status = null;
                    }
                    $vendor->reject_reason = $vendor->rejectingReason->name ?? '';
                    $product_addons = [];
                    $vendor->vendor_name = $vendor->vendor->name;
                    
                    // Append +971 to vendor phone number
                    if (!empty($vendor->vendor->phone_no)) {
                        $vendor->vendor->phone_no = '+971' . $vendor->vendor->phone_no;
                    }
                    
                    // Include customer care number
                    $vendor->customer_care_number = $vendor->vendor->customer_care_number ?? '';
                    if (!empty($vendor->customer_care_number) && !str_starts_with($vendor->customer_care_number, '+')) {
                        $vendor->customer_care_number = '+971' . $vendor->customer_care_number;
                    }
                    foreach ($vendor->products as  $product) {
                        $product_addons = [];
                        $variant_options = [];
                        $order_item_count += $product->quantity;
                        $product->image_path = $product->media->first() ? $product->media->first()->image->path : $product->image;
                        if ($product->pvariant) {
                            foreach ($product->pvariant->vset as $variant_set_option) {
                                $variant_options[] = array(
                                    'option' => $variant_set_option->optionData->trans->title,
                                    'title' => $variant_set_option->variantDetail->trans->title,
                                );
                            }
                        }
                        $product->variant_options = $variant_options;
                        if (!empty($product->addon)) {
                            foreach ($product->addon as $addon) {
                                $product_addons[] = array(
                                    'addon_id' =>  $addon->addon_id,
                                    'addon_title' =>  $addon->set->title,
                                    'option_title' =>  $addon->option->title,
                                );
                            }
                        }
                        $product->product_addons = $product_addons;
                    }
                    if ($vendor->delivery_fee > 0) {
                        $order_pre_time = ($vendor->order_pre_time > 0) ? $vendor->order_pre_time : 0;
                        $user_to_vendor_time = ($vendor->user_to_vendor_time > 0) ? $vendor->user_to_vendor_time : 0;
                        $ETA = $order_pre_time + $user_to_vendor_time;
                        $vendor->ETA = ($ETA > 0) ? $this->formattedOrderETA($ETA, $vendor->created_at, $order->scheduled_date_time) : convertDateTimeInTimeZone($vendor->created_at, $user->timezone, 'h:i A');
                    }
                }
                if (!empty($order->scheduled_date_time)) {
                    $order->scheduled_date_time = convertDateTimeInTimeZone($order->scheduled_date_time, $user->timezone, 'M d, Y h:i A');
                }
                $luxury_option_name = '';
                if ($order->luxury_option_id > 0) {
                    $luxury_option = LuxuryOption::where('id', $order->luxury_option_id)->first();
                    if ($luxury_option->title == 'takeaway') {
                        $luxury_option_name = $this->getNomenclatureName('Takeaway', $user->language, false);
                    } elseif ($luxury_option->title == 'dine_in') {
                        $luxury_option_name = 'Dine-In';
                    } else {
                        $luxury_option_name = 'Delivery';
                    }
                }
                $order->luxury_option_name = $luxury_option_name;
                $order->order_item_count = $order_item_count;
                //$order->service_fee = $order->total_amount - $order->taxable_amount;
                $order->service_fee = $order->total_amount;
                if (($order->type == 7 || $order->type == 9 || $order->type == 10) && $order->vendors->first()->dispatch_traking_url) {
                    $url = str_replace("order", "order-details", $order->vendors->first()->dispatch_traking_url);
                    $response = Http::get($url);
                    if ($response->status() == 200) {
                        $response = json_decode($response->body());

                        //provide rider details if rider assigned
                        if ($response->order->driver_id != NULL && $response->tasks[0]->task_status > 0) {
                            $agent['name'] = $response->order->name ?? '';
                            $agent['profile_picture'] = $response->agent_image ?? '';
                            $agent['phone_number'] = $response->order->phone_number ?? '';
                            $agent['vehicle_type_name'] = $response->order->vehicle_type_name ?? '';
                            $agent['vehicle_type_icon'] = $response->vehicle_type_icon ?? '';
                            $agent['agent_location'] = $response->agent_location ?? [];
                            $order->delivery_agent = $agent;
                            if ($response->tasks[1]->task_status == 4) { //drop off completed
                                $order->delivery_status = 3;
                            } elseif ($response->tasks[1]->task_status > 1) { //drop off in progrss
                                $order->delivery_status = 2;
                            } elseif ($response->tasks[0]->task_status == 4) { //pickup completed
                                $order->delivery_status = 1;
                            } else {
                                $order->delivery_status = 0;
                            }
                        } else {
                            $order->delivery_status = -1;
                        }
                        // Get the pickup task (task_type_id = 1)
                        $pickup_task = array_values(array_filter($response->tasks, function ($task) {
                            return $task->task_type_id == 1;
                        }))[0];

                        // Get the dropoff task (task_type_id = 2)
                        $dropoff_task = array_values(array_filter($response->tasks, function ($task) {
                            return $task->task_type_id == 2;
                        }))[0];
                        $pickupFlatNo  = $response->tasks[0]->flat_no ?? '';
                        $pickupShort   = $response->tasks[0]->short_name ?? '';
                        $dropFlatNo    = $response->tasks[1]->flat_no ?? '';
                        $dropShort     = $response->tasks[1]->short_name ?? '';

                        $order->delivery_pickup_address       = $response->tasks[0]->address;
                        $order->delivery_pickup_latitude      = $pickup_task->latitude ?? null;
                        $order->delivery_pickup_longitude     = $pickup_task->longitude ?? null;
                        $order->delivery_pickup_name          = $response->tasks[0]->contact_name;
                        $order->delivery_pickup_phone_number  = $response->tasks[0]->contact_number;
                        $order->delivery_pickup_flat_number   = trim($pickupFlatNo . ($pickupShort ? ', ' . $pickupShort : ''));

                        $order->delivery_drop_address         = $response->tasks[1]->address;
                        $order->delivery_drop_latitude        = $dropoff_task->latitude ?? null;
                        $order->delivery_drop_longitude       = $dropoff_task->longitude ?? null;
                        $order->delivery_drop_name            = $response->tasks[1]->contact_name;
                        $order->delivery_drop_phone_number    = $response->tasks[1]->contact_number;
                        $order->delivery_drop_flat_number     = trim($dropFlatNo . ($dropShort ? ', ' . $dropShort : ''));

                        $order->tracking_url = url('/order-tracking?order_id=' . Crypt::encryptString($order->id));

                    } else {
                        Log::info("GCLIENT CALL ERROR");
                    }
                }
            }
            return $this->successResponse($order, null, 201);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }
    public function postVendorOrderStatusUpdate(Request $request)
    {
        DB::beginTransaction();
        try {
            $order_id = $request->order_id;
            $vendor_id = $request->vendor_id;
            $reject_reason = $request->reject_reason;
            $order_vendor_id = $request->order_vendor_id;
            $order_status_option_id = $request->order_status_option_id;
            $client_preference = ClientPreference::first();
            if ($order_status_option_id == 7) {
                $order_status_option_id = 2;
            } else if ($order_status_option_id == 8) {
                $order_status_option_id = 3;
            }
            $vendor_order_status = VendorOrderStatus::where('order_id', $order_id)->where('vendor_id', $vendor_id)->first();
            $vendor_order_status_detail = VendorOrderStatus::where('order_id', $order_id)->where('vendor_id', $vendor_id)->where('order_status_option_id', $order_status_option_id)->first();
            if (!$vendor_order_status_detail) {
                $vendor_order_status = new VendorOrderStatus();
                $vendor_order_status->order_id = $order_id;
                $vendor_order_status->vendor_id = $vendor_id;
                $vendor_order_status->order_status_option_id = $order_status_option_id;
                $vendor_order_status->order_vendor_id = $vendor_order_status->order_vendor_id;
                $vendor_order_status->save();
                $currentOrderStatus = OrderVendor::where('vendor_id', $vendor_id)->where('order_id', $order_id)->first();
                OrderVendor::where('vendor_id', $vendor_id)->where('order_id', $order_id)->update(['order_status_option_id' => $order_status_option_id, 'reject_reason' => $reject_reason]);
                if ($order_status_option_id == 2 || $order_status_option_id == 3) {
                    $clientDetail = Client::on('mysql')->where(['code' => $client_preference->client_code])->first();
                    AutoRejectOrderCron::on('mysql')->where(['database_name' => $clientDetail->database_name, 'order_vendor_id' => $currentOrderStatus->id])->delete();
                }
                $current_status = OrderStatusOption::select('id', 'title')->find($order_status_option_id);
                if ($order_status_option_id == 2) {
                    $upcoming_status = OrderStatusOption::select('id', 'title')->where('id', '>', 3)->first();
                } elseif ($order_status_option_id == 3) {
                    $upcoming_status = null;
                } elseif ($order_status_option_id == 6) {
                    $upcoming_status = null;
                } else {
                    $upcoming_status = OrderStatusOption::select('id', 'title')->where('id', '>', $order_status_option_id)->first();
                }
                $order_status = [
                    'current_status' => $current_status,
                    'upcoming_status' => $upcoming_status,
                ];
                $orderData = Order::find($order_id);

                if (!empty($currentOrderStatus->dispatch_traking_url) && ($request->order_status_option_id == 3)) {
                    $dispatch_traking_url = str_replace('/order/', '/order-cancel/', $currentOrderStatus->dispatch_traking_url);
                    $response = Http::get($dispatch_traking_url);
                }
                DB::commit();
                // $this->sendSuccessNotification(Auth::user()->id, $request->vendor_id);
                $code = $request->header('code');
                $this->sendStatusChangePushNotificationCustomer([$orderData->user_id], $orderData, $order_status_option_id, $code);
                return response()->json([
                    'status' => 'success',
                    'order_status' => $order_status,
                    'message' => 'Order Status Updated Successfully.'
                ]);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function sendOrderPushNotificationVendors($user_ids, $orderData, $header_code)
    {
        $devices = UserDevice::whereNotNull('device_token')->whereIn('user_id', $user_ids)->pluck('device_token')->toArray();
        Log::info($devices);
        $client_preferences = ClientPreference::select('fcm_server_key', 'favicon')->first();
        if (!empty($devices) && !empty($client_preferences->fcm_server_key)) {
            $from = $client_preferences->fcm_server_key;
            $notification_content = NotificationTemplate::where('id', 4)->first();
            if ($notification_content) {
                $code = $header_code;
                $client = Client::where('code', $code)->first();
                $redirect_URL = "https://" . $client->sub_domain . env('SUBMAINDOMAIN') . "/client/order";
                $headers = [
                    'Authorization: key=' . $from,
                    'Content-Type: application/json',
                ];
                $data = [
                    "registration_ids" => $devices,
                    "notification" => [
                        'title' => $notification_content->subject,
                        'body'  => $notification_content->content,
                        'sound' => "notification.wav",
                        "icon" => (!empty($client_preferences->favicon)) ? $client_preferences->favicon['proxy_url'] . '200/200' . $client_preferences->favicon['image_path'] : '',
                        'click_action' => $redirect_URL,
                        "android_channel_id" => "sound-channel-id"
                    ],
                    "data" => [
                        'title' => $notification_content->subject,
                        'body'  => $notification_content->content,
                        'data' => $orderData,
                        'type' => "order_created"
                    ],
                    "priority" => "high"
                ];
                $dataString = $data;
                Log::info(json_encode($data));
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($dataString));
                $result = curl_exec($ch);
                Log::info($result);
                curl_close($ch);
            }
        }
    }

    public function sendStatusChangePushNotificationCustomer($user_ids, $orderData, $order_status_id, $header_code)
    {
        $devices = UserDevice::whereNotNull('device_token')->whereIn('user_id', $user_ids)->pluck('device_token')->toArray();
        Log::info($devices);
        $client_preferences = ClientPreference::select('fcm_server_key', 'favicon')->first();
        if (!empty($devices) && !empty($client_preferences->fcm_server_key)) {
            $from = $client_preferences->fcm_server_key;
            if ($order_status_id == 2 || $order_status_id == 7) {
                $notification_content = NotificationTemplate::where('id', 5)->first();
            } elseif ($order_status_id == 3 || $order_status_id == 8) {
                $notification_content = NotificationTemplate::where('id', 6)->first();
            } elseif ($order_status_id == 4) {
                $notification_content = NotificationTemplate::where('id', 7)->first();
            } elseif ($order_status_id == 5) {
                $notification_content = NotificationTemplate::where('id', 8)->first();
            } elseif ($order_status_id == 6) {
                $notification_content = NotificationTemplate::where('id', 9)->first();
            }
            if ($notification_content) {
                $code = $header_code;
                $client = Client::where('code', $code)->first();
                $redirect_URL = "https://" . $client->sub_domain . env('SUBMAINDOMAIN') . "/user/orders";
                $headers = [
                    'Authorization: key=' . $from,
                    'Content-Type: application/json',
                ];
                $body_content = str_ireplace("{order_id}", "#" . $orderData->order_number, $notification_content->content);
                $data = [
                    "registration_ids" => $devices,
                    "notification" => [
                        'title' => $notification_content->subject,
                        'body'  => $body_content,
                        'sound' => "default",
                        "icon" => (!empty($client_preferences->favicon)) ? $client_preferences->favicon['proxy_url'] . '200/200' . $client_preferences->favicon['image_path'] : '',
                        'click_action' => $redirect_URL,
                        "android_channel_id" => "default-channel-id"
                    ],
                    "data" => [
                        'title' => $notification_content->subject,
                        'body'  => $body_content,
                        "type" => "order_status_change"
                    ],
                    "priority" => "high"
                ];
                $dataString = $data;
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($dataString));
                $result = curl_exec($ch);
                Log::info($result);
                curl_close($ch);
            }
        }
    }

    public function minimize_orderDetails_for_notification($order_id, $vendor_id = "")
    {
        $order = Order::with(['vendors.vendor:id,name,auto_accept_order'])->select('id', 'order_number', 'payable_amount', 'payment_option_id', 'user_id', 'address_id', 'loyalty_amount_saved', 'total_discount', 'total_delivery_fee', 'total_amount', 'taxable_amount', 'created_at');
        $order = $order->whereHas('vendors', function ($query) use ($vendor_id) {
            if (!empty($vendor_id)) {
                $query->where('vendor_id', $vendor_id);
            }
        })->with('vendors', function ($query) use ($vendor_id) {
            $query->select('id', 'order_id', 'vendor_id');
            if (!empty($vendor_id)) {
                $query->where('vendor_id', $vendor_id);
            }
        });
        $order = $order->find($order_id);
        return $order;
    }

    public function orderDetails_for_notification($order_id, $vendor_id = "")
    {
        $user = Auth::user();
        if ($user->is_superadmin != 1) {
            $userVendorPermissions = UserVendor::where(['user_id' => $user->id])->pluck('vendor_id')->toArray();
            $vendor_id = OrderVendor::where(['order_id' => $order_id])->whereIn('vendor_id', $userVendorPermissions)->pluck('vendor_id')->first();
            if (!$vendor_id) {
                return response()->json(['error' => __('No order found')], 404);
            }
        }
        $language_id = (!empty($user->language)) ? $user->language : 1;
        $order = Order::with([
            'vendors.products:id,product_name,product_id,order_id,order_vendor_id,variant_id,quantity,price',
            'vendors.vendor:id,name,auto_accept_order,logo',
            'vendors.products.addon:id,order_product_id,addon_id,option_id',
            'vendors.products.pvariant:id,sku,product_id,title,quantity',
            'user:id,name,timezone,dial_code,phone_number',
            'address:id,user_id,address',
            'vendors.products.addon.option:addon_options.id,addon_options.title,addon_id,price',
            'vendors.products.addon.set:addon_sets.id,addon_sets.title',
            'vendors.products.translation' => function ($q) use ($language_id) {
                $q->select('id', 'product_id', 'title');
                $q->where('language_id', $language_id);
            },
            'vendors.products.addon.option.translation_one' => function ($q) use ($language_id) {
                $q->select('id', 'addon_opt_id', 'title');
                $q->where('language_id', $language_id);
            },
            'vendors.products.addon.set.translation_one' => function ($q) use ($language_id) {
                $q->select('id', 'addon_id', 'title');
                $q->where('language_id', $language_id);
            }
        ])->select('id', 'order_number', 'payable_amount', 'payment_option_id', 'user_id', 'address_id', 'loyalty_amount_saved', 'total_discount', 'total_delivery_fee', 'total_amount', 'taxable_amount', 'wallet_amount_used', 'created_at');
        $order = $order->whereHas('vendors', function ($query) use ($vendor_id) {
            if (!empty($vendor_id)) {
                $query->where('vendor_id', $vendor_id);
            }
        })->with('vendors', function ($query) use ($vendor_id) {
            $query->select('id', 'order_id', 'vendor_id');
            if (!empty($vendor_id)) {
                $query->where('vendor_id', $vendor_id);
            }
        });
        $order = $order->find($order_id);
        $order_item_count = 0;
        $order->payment_option_title = $order->paymentOption->title;
        $order->item_count = $order_item_count;
        foreach ($order->products as $product) {
            $order_item_count += $product->quantity;
        }
        $order->item_count = $order_item_count;
        unset($order->products);
        unset($order->paymentOption);
        return $this->successResponse($order, __('Order detail.'), 201);
    }

    public function generateInvoicePDF(Request $request, $order_id, $vendor_id)
    {
        $vendor_order_status_option_ids = [];
        $vendor_order_status_created_dates = [];
        $order = Order::with(array(
            'vendors' => function ($query) use ($vendor_id) {
                $query->where('vendor_id', $vendor_id);
            },
            'vendors.products.prescription' => function ($query) use ($vendor_id, $order_id) {
                $query->where('vendor_id', $vendor_id)->where('order_id', $order_id);
            },
            'vendors.products' => function ($query) use ($vendor_id) {
                $query->where('vendor_id', $vendor_id);
            },
            'address',
            'vendors.vendor',
        ))->findOrFail($order_id);
        // return $order;
        foreach ($order->vendors as $key => $vendor) {
            foreach ($vendor->products as $key => $product) {
                $product->image_path  = $product->media->first() ? $product->media->first()->image->path : '';
            }
        }
        $order_status_options = OrderStatusOption::where('type', 1)->get();
        $dispatcher_status_options = DispatcherStatusOption::with(['vendorOrderDispatcherStatus' => function ($q) use ($order_id, $vendor_id) {
            $q->where(['order_id' => $order_id, 'vendor_id' => $vendor_id]);
        }])->get();
        $vendor_order_statuses = VendorOrderStatus::where('order_id', $order_id)->where('vendor_id', $vendor_id)->get();
        foreach ($vendor_order_statuses as $vendor_order_status) {
            $vendor_order_status_created_dates[$vendor_order_status->order_status_option_id] = $vendor_order_status->created_at;
            $vendor_order_status_option_ids[] = $vendor_order_status->order_status_option_id;
        }
        $clientCurrency = ClientCurrency::where('is_primary', 1)->with('currency')->first();
        
        // Get language and RTL direction first to determine currency format
        $user = Auth::user();
        $language_id = $user->language ?? 1;
        $header = $request->headers->all();
        
        // Check if language is provided in header (highest priority)
        if(isset($header['language'][0]) && !empty($header['language'][0])){
            $langValue = $header['language'][0];
            
            // Check if the value is numeric (old way: language_id) or string (new way: language code like 'en', 'ar')
            if(is_numeric($langValue)){
                $checkLang = ClientLanguage::where('language_id', $langValue)->first();
                if($checkLang){
                    $language_id = $checkLang->language_id;
                }
            } else {
                // New way: support language codes like 'en', 'ar', 'ae'
                $checkLang = ClientLanguage::whereHas('language', function($q) use ($langValue) {
                    $q->where('sort_code', $langValue);
                })->first();
                if($checkLang){
                    $language_id = $checkLang->language_id;
                }
            }
        }
        
        // Get language details
        $clientLanguage = ClientLanguage::with('language')->where('language_id', $language_id)->first();
        $locale = 'en'; // default
        $is_rtl = 0; // default
        
        if($clientLanguage && $clientLanguage->language) {
            $locale = $clientLanguage->language->sort_code ?? 'en';
            $is_rtl = $clientLanguage->language->is_rtl ?? 0;
        } else {
            // Fallback: get language directly from Language model
            $language = Language::find($language_id);
            if($language) {
                $locale = $language->sort_code ?? 'en';
                $is_rtl = $language->is_rtl ?? 0;
            }
        }
        
        // Set currency based on language - use Arabic symbol (د.إ) for Arabic, AED for others
        if (isset($clientCurrency->currency) && $clientCurrency->currency->iso_code == "AED") {
            if ($locale == 'ar' || $is_rtl) {
                $currency = $clientCurrency->currency->symbol; // د.إ for Arabic
            } else {
                $currency = $clientCurrency->currency->iso_code; // AED for others
            }
        } else {
            $currency = $clientCurrency->currency->symbol;
        }
        
        $data['urlImg'] = url('/') . '/assets/images/users/user-1.jpg';
        $clientData = Client::select('id', 'logo')->where('id', '>', 0)->first();
        if ($clientData) {
            //$data['urlImg'] = $clientData->logo['image_fit'] . '200/80' . $clientData->logo['image_path'];
            $logoPath = $clientData->logo['image_fit'] . '200/80' . $clientData->logo['image_path'];
            $data['urlImg'] = base64_encode(file_get_contents($logoPath));
        }
        if ($order->type == 7 || $order->type == 9 || $order->type == 10) {
            $url = str_replace("order", "order-details", $order->vendors->first()->dispatch_traking_url);
            $response = Http::get($url);
            if ($response->status() == 200) {
                $response = json_decode($response->body());
                $order->pickup_address = $response->tasks[0]->flat_no . " " . $response->tasks[0]->address;
                $order->pickup_name = $response->tasks[0]->contact_name;
                $order->pickup_phone_number = $response->tasks[0]->contact_number;
                $order->drop_address = $response->tasks[1]->flat_no . " " . $response->tasks[1]->address;
                $order->drop_name = $response->tasks[1]->contact_name;
                $order->drop_phone_number = $response->tasks[1]->contact_number;
            } else {
                Log::info("GCLIENT CALL ERROR");
            }
            $order->service_fee = $order->total_amount - $order->taxable_amount;
        }
        $data['order'] = $order;
        $data['currency'] = $currency;
        
        // Set locale for translations
        app()->setLocale($locale);
        
        $data['locale'] = $locale;
        $data['is_rtl'] = $is_rtl;
        $data['direction'] = $is_rtl ? 'rtl' : 'ltr';

        // if($order->type == 'delivery' || $order->type == 'recovery'){
        //     $pdf = PDF::loadView('theme.pages.delivery_invoice', $data);
        // }else{
        //     $pdf = PDF::loadView('theme.pages.invoice', $data);
        // }
        if ($order->type == 7 || $order->type == 9 || $order->type == 10) {
            $reportHtml = view('theme.pages.delivery_invoice', $data)->render();
        } else {
            $reportHtml = view('theme.pages.invoice', $data)->render();
        }
        $arabic = new \ArPHP\I18N\Arabic();
        $p = $arabic->arIdentify($reportHtml);

        for ($i = count($p) - 1; $i >= 0; $i -= 2) {
            $utf8ar = $arabic->utf8Glyphs(substr($reportHtml, $p[$i - 1], $p[$i] - $p[$i - 1]));
            $reportHtml = substr_replace($reportHtml, $utf8ar, $p[$i - 1], $p[$i] - $p[$i - 1]);
        }

        // Use DejaVu Sans for Arabic/RTL languages, Poppins for others
        $defaultFont = ($is_rtl || $locale == 'ar') ? 'DejaVu Sans' : 'Poppins';
        $fontDir = ($is_rtl || $locale == 'ar') ? public_path('pdf_fonts') : public_path('pdf_fonts/Poppins');
        
        $pdf = PDF::loadHTML($reportHtml)->setOptions([
            'dpi' => 159,
            'defaultFont' => $defaultFont,
            'fontDir' => $fontDir,
            'fontCache'   => public_path('pdf_fonts/'),
            'isHtml5ParserEnabled' => true,
            'isPhpEnabled' => true,
            'isFontSubsettingEnabled' => true,
            'viewport' => 'Fit',

        ]);
        return response()->json([
            'invoice_pdf' => base64_encode($pdf->stream())
        ]);
    }

    /**
     * Get dispatcher status title by ID
     *
     * @param int $statusId
     * @return string
     */
    private function getDispatcherStatusTitle($statusId)
    {
        $statusTitles = [
            1 => 'Created',
            2 => 'Assigned', 
            3 => 'Started',
            4 => 'Arrived',
            5 => 'Completed'
        ];
        
        return $statusTitles[$statusId] ?? 'Unknown';
    }
}
