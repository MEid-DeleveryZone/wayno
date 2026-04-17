<?php

namespace App\Http\Controllers\Api\v1;

use DB;
use Auth;
use Carbon\Carbon;
use GuzzleHttp\Client as GCLIENT;
use Omnipay\Omnipay;
use Illuminate\Http\Request;
use App\Models\PaymentOption;
use Omnipay\Common\CreditCard;
use App\Http\Traits\ApiResponser;
use App\Http\Controllers\Api\v1\BaseController;
use App\Http\Controllers\Api\v1\MobbexGatewayController;
use App\Http\Controllers\Api\v1\YocoGatewayController;
use App\Http\Requests\OrderStoreRequest;
use Illuminate\Support\Facades\Validator;
use App\Models\{Order, OrderProduct, Cart, CartAddon, CartProduct, Product, OrderProductAddon, Client, ClientPreference, ClientCurrency, OrderVendor, UserAddress, CartCoupon, ClientLanguage, DeliveryCart, VendorOrderStatus, OrderStatusOption, Vendor, LoyaltyCard, LuxuryOption, User, Payment, Transaction};
use Illuminate\Support\Facades\Log;
use Stripe;

use function GuzzleHttp\Promise\all;

class PaymentOptionController extends BaseController
{
    use ApiResponser;
    public $gateway;

    public function getPaymentOptions(Request $request, $page = '')
    {
        if ($page == 'wallet') {
            $code = array('paypal', 'stripe');
        } else {
            $code = array('cod', 'paypal', 'payfast', 'stripe'/*, 'mobbex','yoco'*/);
        }
        $payment_options = PaymentOption::whereIn('code', $code)->where('status', 1)->get(['id', 'title', 'off_site']);
        return $this->successResponse($payment_options, '', 201);
    }

    public function postPayment(Request $request, $gateway = '')
    {
        if (!empty($gateway)) {
            $code = $request->header('code');
            $client = Client::where('code', $code)->first();
            $server_url = "https://" . $client->sub_domain . env('SUBMAINDOMAIN') . "/";
            $request->serverUrl = $server_url;
            $request->currencyId = $request->header('currency');
            $function = 'postPaymentVia_' . $gateway;
            if (method_exists($this, $function)) {
                if (!empty($request->action)) {
                    $response = $this->$function($request); // call related gateway for payment processing
                    $responseData = $response->getData(); //getOriginalContent();
                    if ($responseData->status == 'Success') {
                        // if( ($gateway != 'paypal') && ($gateway != 'mobbex') ){
                        if ($gateway == 'stripe') {
                            $request->transaction_id = $responseData->data;
                            if ($request->action == 'cart') {
                                $orderResponse = $this->postPlaceOrder($request);
                                return $orderResponse;
                            } else if ($request->action == 'wallet') {
                                $walletResponse = $this->creditMyWallet($request);
                            }
                        }
                    }
                    return $response;
                }
            } else {
                return $this->errorResponse("Invalid Gateway Request", 400);
            }
        } else {
            return $this->errorResponse("Invalid Gateway Request", 400);
        }
    }

    public function postPaymentVia_payfast(Request $request)
    {
        $gateway = new PayfastGatewayController();
        return $gateway->payfastPurchase($request);
    }

    public function postPaymentVia_mobbex(Request $request)
    {
        $gateway = new MobbexGatewayController();
        return $gateway->mobbexPurchase($request);
    }

    public function postPaymentVia_yoco(Request $request)
    {
        $gateway = new YocoGatewayController();
        return $gateway->yocoPurchase($request);
    }

    public function postPaymentVia_paypal(Request $request)
    {
        try {
            $paypal_creds = PaymentOption::select('credentials')->where('code', 'paypal')->where('status', 1)->first();
            $creds_arr = json_decode($paypal_creds->credentials);
            $username = (isset($creds_arr->username)) ? $creds_arr->username : '';
            $password = (isset($creds_arr->password)) ? $creds_arr->password : '';
            $signature = (isset($creds_arr->signature)) ? $creds_arr->signature : '';
            $this->gateway = Omnipay::create('PayPal_Express');
            $this->gateway->setUsername($username);
            $this->gateway->setPassword($password);
            $this->gateway->setSignature($signature);
            $this->gateway->setTestMode(true); //set it to 'false' when go live
            $response = $this->gateway->purchase([
                'currency' => 'USD',
                'amount' => $request->amount,
                'cancelUrl' => url($request->serverUrl . $request->cancelUrl),
                'returnUrl' => url($request->serverUrl . $request->returnUrl . '?amount=' . $request->amount),
            ])->send();
            if ($response->isSuccessful()) {
                return $this->successResponse($response->getData());
            } elseif ($response->isRedirect()) {
                return $this->successResponse($response->getRedirectUrl());
            } else {
                return $this->errorResponse($response->getMessage(), 400);
            }
        } catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage(), 400);
        }
    }

    public function postPaymentVia_stripe(Request $request)
    {
        try {
            $stripe_creds = PaymentOption::select('credentials')->where('code', 'stripe')->where('status', 1)->first();
            $creds_arr = json_decode($stripe_creds->credentials);
            $api_key = (isset($creds_arr->api_key)) ? $creds_arr->api_key : '';
            $this->gateway = Omnipay::create('Stripe');
            $this->gateway->setApiKey($api_key);
            $this->gateway->setTestMode(true); //set it to 'false' when go live
            $token = $request->stripe_token;
            $response = $this->gateway->purchase([
                'currency' => 'INR',
                'token' => $token,
                'amount' => $request->amount,
                'metadata' => ['order_id' => '11'],
                'description' => 'Transaction type purchase',
            ])->send();
            if ($response->isSuccessful()) {
                return $this->successResponse($response->getTransactionReference());
            } else {
                return $this->errorResponse($response->getMessage(), 400);
            }
        } catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage(), 400);
        }
    }

    public function creditMyWallet(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            $credit_amount = $request->amount;
            $wallet = $user->wallet;
            if ($credit_amount > 0) {
                $wallet->depositFloat($credit_amount, ['Wallet has been <b>Credited</b> by transaction reference <b>' . $request->transaction_id . '</b>']);
                $transactions = Transaction::where('payable_id', $user->id)->get();
                $response['wallet_balance'] = $wallet->balanceFloat;
                $response['transactions'] = $transactions;
                $message = 'Wallet has been credited successfully';
                return $this->successResponse($response, $message, 201);
            } else {
                return $this->errorResponse('Amount is not sufficient', 402);
            }
        } else {
            return $this->errorResponse('Invalid User', 402);
        }
    }

    public function getDeliveryFeeDispatcher($vendor_id, $user_id)
    {
        try {
            $dispatch_domain = $this->checkIfLastMileOn();
            if ($dispatch_domain && $dispatch_domain != false) {
                $customer = User::find($user_id);
                $cus_address = UserAddress::where('user_id', $user_id)->orderBy('is_primary', 'desc')->first();
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
                    $postdata =  ['locations' => $location, 'type' => 'ecommerce'];
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
    public function postPlaceOrder(Request $request)
    {
        try {
            $total_amount = 0;
            $total_discount = 0;
            $taxable_amount = 0;
            $payable_amount = 0;
            $user = Auth::user();
            if ($user) {
                DB::beginTransaction();
                $loyalty_amount_saved = 0;
                $redeem_points_per_primary_currency = '';
                $loyalty_card = LoyaltyCard::where('status', '0')->first();
                if ($loyalty_card) {
                    $redeem_points_per_primary_currency = $loyalty_card->redeem_points_per_primary_currency;
                }
                $client_preference = ClientPreference::first();
                if ($client_preference->verify_email == 1) {
                    if ($user->is_email_verified == 0) {
                        return response()->json(['error' => 'Your account is not verified.'], 404);
                    }
                }
                if ($client_preference->verify_phone == 1) {
                    if ($user->is_phone_verified == 0) {
                        return response()->json(['error' => 'Your phone is not verified.'], 404);
                    }
                }
                $user_address = UserAddress::where('id', $request->address_id)->first();
                if (!$user_address) {
                    return response()->json(['error' => 'Invalid address id.'], 404);
                }
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
                    $order = new Order;
                    $order->user_id = $user->id;
                    $order->order_number = generateOrderNo();
                    $order->address_id = $request->address_id;
                    $order->payment_option_id = $request->payment_option_id ?? 4;
                    $order->payment_method = $request->payment_option_id;
                    $order->special_instruction =   $cart->specific_instructions;
                    $order->save();
                    $clientCurrency = ClientCurrency::where('currency_id', $request->currencyId)->first();
                    $cart_products = CartProduct::with('product.pimages', 'product.variants', 'product.taxCategory.taxRate', 'coupon', 'product.addon')->where('cart_id', $cart->id)->where('status', [0, 1])->where('cart_id', $cart->id)->orderBy('created_at', 'asc')->get();
                    $total_delivery_fee = 0;
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
                            if ($action == 'delivery') {
                                if ((!empty($vendor_cart_product->product->Requires_last_mile)) && ($vendor_cart_product->product->Requires_last_mile == 1)) {
                                    $delivery_fee = $this->getDeliveryFeeDispatcher($vendor_cart_product->vendor_id, $user->id);
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
                                CartAddon::where('cart_product_id', $vendor_cart_product->id)->delete();
                            }
                        }
                        $coupon_id = null;
                        $coupon_name = null;
                        $actual_amount = $vendor_payable_amount;
                        if ($vendor_cart_product->coupon) {
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
                        $order_vendor->delivery_fee = $total_delivery_fee;
                        $order_vendor->coupon_id = $coupon_id;
                        $order_vendor->coupon_code = $coupon_name;
                        $order_vendor->order_status_option_id = 1;
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
                    }
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
                    if ($cart->tip_amount > 0) {
                        $tip_amount = $cart->tip_amount;
                        $tip_amount = ($tip_amount / $clientCurrency->doller_compare) * $clientCurrency->doller_compare;
                        $order->tip_amount = number_format($tip_amount, 2);
                    }
                    $order->total_delivery_fee = $total_delivery_fee;
                    $order->loyalty_points_used = $loyalty_points_used;
                    $order->loyalty_amount_saved = $loyalty_amount_saved;
                    $order->payable_amount = $delivery_fee + $payable_amount + $tip_amount - $total_discount - $loyalty_amount_saved;
                    $order->loyalty_points_earned = $loyalty_points_earned['per_order_points'];
                    $order->loyalty_membership_id = $loyalty_points_earned['loyalty_card_id'];

                    if ($request->transaction_id && $request->transaction_id != '') {
                        $order->payment_status = 1;
                    }
                    $specific_instructions = null;
                    if ($cart->specific_instructions != null) {
                        $specific_instructions = $cart->specific_instructions;
                    }
                    $order->special_instruction = $specific_instructions;
                    $order->save();
                    $orderMain = new OrderController();
                    $orderMain->sendSuccessSMS($request, $order);
                    $orderMain->sendSuccessEmail($request, $order);
                    CartCoupon::where('cart_id', $cart->id)->delete();
                    CartProduct::where('cart_id', $cart->id)->delete();
                    $cart->use_wallet = 0;
                    $cart->tip_amount = 0;
                    $cart->paid = 0;
                    $cart->save();

                    if (($request->payment_option_id != 1) && ($request->payment_option_id != 2)) {
                        Payment::insert([
                            'date' => date('Y-m-d'),
                            'order_id' => $order->id,
                            'transaction_id' => $request->transaction_id,
                            'balance_transaction' => $order->payable_amount,
                        ]);
                    }
                    DB::commit();
                    return $this->successResponse($order, __('Order placed successfully.'), 201);
                }
            } else {
                return $this->errorResponse(['error' => __('Empty cart.')], 404);
            }
        } catch (Exception $e) {
            DB::rollback();
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function StripePaymentIntent(Request $request)
    {
        try {
            $user = Auth::user();

            $client_preference = ClientPreference::first();
            if ($client_preference->verify_email == 1) {
                if ($user->is_email_verified == 0) {
                    return $this->errorResponse('Your account is not verified', 403);
                }
            }
            if ($client_preference->verify_phone == 1) {
                if ($user->is_phone_verified == 0) {
                    return $this->errorResponse('Your phone is not verified.', 403);
                }
            }
            $user_address = UserAddress::where('id', $request->address_id)->first();
            if (!$user_address) {
                return $this->errorResponse('You have provided invalid address.', 403);
            }

            $cart = Cart::where('user_id', $user->id)->first();
            $cartController = new CartController();
            $cartDetails = $cartController->getCart($cart);
            $stripe_creds = PaymentOption::select('credentials')->where('code', 'stripe')->where('status', 1)->first();
            $creds_arr = json_decode($stripe_creds->credentials);
            $api_key = (isset($creds_arr->api_key)) ? $creds_arr->api_key : '';
            $publishable_key = (isset($creds_arr->publishable_key)) ? $creds_arr->publishable_key : '';
            $stripe = Stripe::setApiKey($api_key);

            if ($user->strip_customer_id) {
                $customer = $stripe->customers()->find($user->strip_customer_id);
            } else {
                $customer = $stripe->customers()->create();
                User::where('id', $user->id)->update(['strip_customer_id' => $customer['id']]);
            }
            $ephemeralKey = $stripe->EphemeralKey()->create($customer['id']);

            $tip_amount = 0;
            if ($request->tip != '' && $request->tip > 0) {
                $tip_amount += $request->tip;
            }
            $paymentIntent = $stripe->paymentIntents()->create([
                'amount' => $cartDetails->total_payable_amount + $tip_amount,
                'currency' => 'aed',
                'customer' => $customer['id'],
                'automatic_payment_methods' => [
                    'enabled' => 'true',
                ],
                'metadata' => [
                    'type' => 'order',
                    'user' => $user->id,
                    'cart_id' => $cart->id,
                    'address_id' => $request->address_id,
                    'payment_option_id' => $request->payment_option_id,
                    'order_type' => $request->type, //not needed
                    'tip_amount' => $request->tip,
                    'source' => url('/')
                ],
            ]);

            return response([
                'status' => 'success',
                'paymentIntent' => $paymentIntent['client_secret'],
                'ephemeralKey' => $ephemeralKey['secret'],
                'customer' => $customer['id'],
                'publishableKey' => $publishable_key
            ], 200);
        } catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage(), 400);
        }
    }

    public function StripeWebhook(Request $request)
    {
        $stripe_creds   = PaymentOption::select('credentials')->where('code', 'stripe')->where('status', 1)->first();
        $creds_arr      = json_decode($stripe_creds->credentials);
        $api_key        = (isset($creds_arr->api_key)) ? $creds_arr->api_key : '';
        \Stripe\Stripe::setApiKey($api_key);
        $endpointSecret = env('STRIPE_WEBHOOK_SECRET'); //take this whsec.. token from the stripe webhook request(secret)
        $payload        = file_get_contents('php://input');
        $event          = null;
        $preference     = ClientPreference::first();
        try {
            // Construct the Stripe event from the payload
            $event = \Stripe\Event::constructFrom(
                json_decode($payload, true)
            );
        } catch (\UnexpectedValueException $e) {
            Log::info('Webhook error while parsing basic request.');
            http_response_code(400);
            exit();
        }

        // Verify the signature if endpoint secret is provided
        if ($endpointSecret) {
            $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
            try {
                $event = \Stripe\Webhook::constructEvent(
                    $payload,
                    $sig_header,
                    $endpointSecret
                );
            } catch (Cartalyst\Stripe\Exception\SignatureVerificationException $e) {
                // Invalid signature
                Log::info('Webhook error while validating signature.');
                http_response_code(400);
                exit();
            }
        }

        // Handle different event types
        switch ($event->type) {
            case 'payment_intent.succeeded':
                $paymentIntent = $event->data->object;
                if ($paymentIntent->metadata->source != url('/')) {
                    return response()->json(['message' => 'Wrong Domain!'], 400);
                }

                try {
                    $user = User::find($paymentIntent->metadata->user);
                    $languages = ClientLanguage::where('is_primary', 1)->first();
                    $primary_cur = ClientCurrency::where('is_primary', 1)->first();
                    $user->language = $languages->language_id;
                    $user->currency = $primary_cur->currency_id;
                    Auth::login($user);

                    if ($paymentIntent->metadata->type == 'order') {
                        // Handle order processing
                        $orderRequest = new OrderStoreRequest();
                        $orderRequest->replace([
                            'address_id'        => $paymentIntent->metadata->address_id,
                            'payment_option_id' => $paymentIntent->metadata->payment_option_id ?? 4,
                            'currencyId'        => $preference->primary->currency_id,
                            'transaction_id'    => $paymentIntent->id,
                            'tip'               => $paymentIntent->metadata->tip_amount
                        ]);
                        $orderMain      = new OrderController();
                        $orderResponse  = $orderMain->postPlaceOrder($orderRequest);
                        Log::info('Order from Stripe Hook Success. ID: ' . json_encode($orderResponse));
                        // Update cart status
                        Cart::where('id', $paymentIntent->metadata->cart_id)->update(['paid' => 2]);
                    } elseif ($paymentIntent->metadata->type == 'delivery_order') {
                        // Handle delivery order processing
                        // DeliveryCart::where('id', $paymentIntent->metadata->delivery_cart_id)->update(['payment_option_id' => 4]);
                        $deliveryCart = DeliveryCart::with('tasks')
                            ->where('id', $paymentIntent->metadata->delivery_cart_id)
                            ->first();
                        $deliveryRequest = new Request();
                        $deliveryRequest->replace($deliveryCart->toArray());
                        $deliveryRequest['transaction_id'] = $paymentIntent->id;
                        $deliveryRequest['payment_option_id'] = $deliveryCart->payment_option_id;

                        $pickUpDelivery = new PickupDeliveryController();
                        $response = $pickUpDelivery->createOrder($deliveryRequest);

                        DeliveryCart::where('id', $paymentIntent->metadata->delivery_cart_id)->update(['status' => 'completed']);
                        return response($response);
                    }
                } catch (\Exception $e) {
                    Log::error('Stripe Webhook failed!');
                    Log::error($e->getMessage());
                }
                break;

            case 'payment_method.attached':
                $paymentMethod = $event->data->object;
                Log::info('Received payment_method.attached event: ' . json_encode($event));
                break;

            default:
                error_log('Received unknown event type: ' . json_encode($event));
        }

        return response('OK', 200);
    }


    public static function StripeRefund($transaction_id = '', $amount = 0)
    {
        $stripe_creds = PaymentOption::select('credentials')->where('code', 'stripe')->where('status', 1)->first();
        $creds_arr = json_decode($stripe_creds->credentials);
        $api_key = (isset($creds_arr->api_key)) ? $creds_arr->api_key : '';

        $stripe = new \Stripe\StripeClient($api_key);
        $refund_array['payment_intent'] = $transaction_id;

        if ($transaction_id != '') {
            // $response = $stripe->refunds->create(['charge' => $transaction_id]); // for a full refund
            if ($amount > 0) {
                // Partial refund → convert AED → fils
                $refundAmount = intval($amount * 100);

                $response = $stripe->refunds->create([
                    'payment_intent' => $transaction_id,
                    'amount' => $refundAmount,
                ]);
            } else {
                // Full refund
                $response = $stripe->refunds->create([
                    'payment_intent' => $transaction_id,
                ]);
            }
            // $response = $stripe->refunds->create([
            //     'charge' => $chargeId, 
            //     'amount' => 5000, // Amount to refund (in cents) in case of partial refund. 
            // ]);
            Log::info('Refund Response: ' . $response);
            if ($response->status === 'succeeded') {
                Log::info('succeeded');
                return [
                    'status' => 'success',
                    'message' => 'Refund Initiated',
                    'data' => $response
                ];
            } else {
                Log::info('failed');
                return [
                    'status' => 'error',
                    'message' => 'Refund failed',
                    'data' => $response
                ];
            }
            // Log::info('Refund Response: ' . $response);
            //return response()->json(['message' => 'refund Initiated']);
        } else {
            Log::info('Refund Response: Invalid transaction ID');
            return response()->json(['message' => 'Refund Failed. No Transaction ID']);
        }
    }

    public static function StripePaymentIntentForCourier(Request $request)
    {
        try {
            $user = Auth::user();
            $stripe_creds = PaymentOption::select('credentials')->where('code', 'stripe')->where('status', 1)->first();
            $creds_arr = json_decode($stripe_creds->credentials);
            $api_key = (isset($creds_arr->api_key)) ? $creds_arr->api_key : '';
            $publishable_key = (isset($creds_arr->publishable_key)) ? $creds_arr->publishable_key : '';
            $stripe = Stripe::setApiKey($api_key);
            if ($user->strip_customer_id) {
                $customer = $stripe->customers()->find($user->strip_customer_id);
            } else {
                $customer = $stripe->customers()->create([
                    'name' => $user->name,
                    'email' => $user->email,
                ]);
                User::where('id', $user->id)->update(['strip_customer_id' => $customer['id']]);
            }
            $ephemeralKey = $stripe->EphemeralKey()->create($customer['id']);

            $paymentIntent = $stripe->paymentIntents()->create([
                'amount' => $request->amount,
                'currency' => 'aed',
                'customer' => $customer['id'],
                // 'automatic_payment_methods' => [
                //     'enabled' => 'true',
                // ],
                'payment_method_options' => [
                    'card' => [
                        'request_three_d_secure' => 'any', // This forces 3D Secure (OTP) if it's available
                    ],
                ],
                'confirmation_method' => 'automatic',
                'metadata' => [
                    'type' => 'delivery_order',
                    'user' => $user->id,
                    'delivery_cart_id' => $request->delivery_cart_id,
                    'source' => url('/')
                ],
            ]);

            return ([
                'paymentIntent' => $paymentIntent['client_secret'],
                'ephemeralKey' => $ephemeralKey['secret'],
                'customer' => $customer['id'],
                'publishableKey' => $publishable_key
            ]);
        } catch (\Exception $ex) {
            return response()->json([
                'status' => 'Error',
                'message' => $ex->getMessage()
            ], 400);
        }
    }
}
