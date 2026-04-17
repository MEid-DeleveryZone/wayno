<?php

namespace App\Http\Controllers\Api\v1;

use DB;
use Log;
use Auth;
use Session;
use Redirect;
use Carbon\Carbon;
use Omnipay\Omnipay;
use Illuminate\Http\Request;
use App\Http\Traits\ApiResponser;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\v1\{BaseController, OrderController, WalletController};
use App\Models\Client as CP;
use App\Models\{PaymentOption, Client, ClientPreference, Order, OrderProduct, EmailTemplate, Cart, CartAddon, OrderProductPrescription, CartProduct, User, Product, OrderProductAddon, Payment, ClientCurrency, OrderVendor, UserAddress, Vendor, CartCoupon, CartProductPrescription, LoyaltyCard, NotificationTemplate, VendorOrderStatus,OrderTax, SubscriptionInvoicesUser, UserDevice, UserVendor};

class PayfastGatewayController extends BaseController
{
    use ApiResponser;
    public $gateway;

    public function __construct()
    {
        $payfast_creds = PaymentOption::select('credentials', 'test_mode')->where('code', 'payfast')->where('status', 1)->first();
        $creds_arr = json_decode($payfast_creds->credentials);
        $merchant_id = (isset($creds_arr->merchant_id)) ? $creds_arr->merchant_id : '';
        $merchant_key = (isset($creds_arr->merchant_key)) ? $creds_arr->merchant_key : '';
        $passphrase = (isset($creds_arr->passphrase)) ? $creds_arr->passphrase : '';
        $testmode = (isset($payfast_creds->test_mode) && ($payfast_creds->test_mode == '1')) ? true : false;
        $this->gateway = Omnipay::create('PayFast');
        $this->gateway->setMerchantId($merchant_id);
        $this->gateway->setMerchantKey($merchant_key);
        $this->gateway->setPassphrase($passphrase);
        $this->gateway->setTestMode($testmode); //set it to 'false' when go live
        // dd($this->gateway);
    }

    function generateSignature($data, $passPhrase = null) {
        // Create parameter string
        $pfOutput = '';
        foreach( $data as $key => $val ) {
            if($val !== '') {
                $pfOutput .= $key .'='. urlencode( trim( $val ) ) .'&';
            }
        }
        // Remove last ampersand
        $getString = substr( $pfOutput, 0, -1 );
        if( ($passPhrase !== null) || ($passPhrase !== '') ) {
            $getString .= '&passphrase='. urlencode( trim( $passPhrase ) );
        }
        // return $getString;
        return md5( $getString );
    }

    public function payfastPurchase(Request $request, $domain = ''){
        try{
            $user = Auth::user();
            $cart = Cart::select('id')->where('status', '0')->where('user_id', $user->id)->first();
            $amount = $this->getDollarCompareAmount($request->amount);
            $returnUrlParams = '?amount='.$amount;
            $address_id = 0;
            $tip = 0;
            if($request->has('tip')){
                $tip = $request->tip;
                $returnUrlParams = $returnUrlParams.'&tip='.$tip;
            }
            if( ($request->has('address_id')) && ($request->address_id > 0) ){
                $address_id = $request->address_id;
                $returnUrlParams = $returnUrlParams.'&address_id='.$address_id;
            }
            $returnUrlParams = $returnUrlParams.'&gateway=payfast';

            // $returnUrl = route('order.return.success');
            if($request->action == 'wallet'){
                // $returnUrl = route('user.wallet');
            }

            $request_arr = array(
                'merchant_id' => $this->gateway->getMerchantId(),
                'merchant_key' => $this->gateway->getMerchantKey(),
                'return_url' => url($request->serverUrl . 'payment/gateway/returnResponse?status=200&gateway=payfast&order='.$request->order_number),
                'cancel_url' => url($request->serverUrl . 'payment/gateway/returnResponse?status=0&gateway=payfast&order='.$request->order_number),
                'notify_url' => url($request->serverUrl . 'payment/payfast/notify/app'),
                'amount' => $amount,
                'item_name' => 'test item',
                'custom_int1' => $user->id, // user id
                'custom_int2' => $cart->id, // cart id
                'custom_int3' => 6, //payment option id
                'custom_str1' => $tip, // tip amount
                'custom_str2' => $request->action,
                'custom_str3' => $request->order_number,
                'currency' => 'ZAR',
                'description' => 'This is a test purchase transaction',
                // 'metadata' => ['user_id' => $user->id],
            );

            $response = $this->gateway->purchase($request_arr)->send();
            unset($request_arr['description']);
            $passphrase = $this->gateway->getPassphrase();
            $signature = $this->generateSignature($request_arr, $passphrase);
            $request_arr['signature'] = $signature;

            if ($response->isSuccessful()) {
                return $this->successResponse($response->getData());
            }
            elseif ($response->isRedirect()) {
                $data['formData'] = $request_arr;
                $data['redirectUrl'] = $response->getRedirectUrl();
                $this->failMail();
                return $this->successResponse($data);
            }
            else {
                $this->failMail();
                return $this->errorResponse($response->getMessage(), 400);
            }
        }
        catch(\Exception $ex){
            $this->failMail();
            return $this->errorResponse($ex->getMessage(), 400);
        }
    }

    /*public function payfastNotify(Request $request, $domain = '')
    {
        // Notify PayFast that information has been received
        header( 'HTTP/1.0 200 OK' );
        flush();

        // Posted variables from ITN
        $pfData = $request;
        $pfData->payment_status = 'COMPLETE';
        //update db
        switch( $pfData->payment_status )
        {
        case 'COMPLETE':
            // If complete, update your application, email the buyer and process the transaction as paid
            $pfData->request->add([
                'user_id' => $pfData->custom_int1,
                'payment_option_id' => $pfData->custom_int3,
                'transaction_id' => $pfData->pf_payment_id
            ]);
            if($pfData->custom_str2 == 'cart'){
                $transactionId = $pfData->pf_payment_id;
                $order_number = $pfData->custom_str3;
                $order = Order::with(['paymentOption', 'user_vendor', 'vendors:id,order_id,vendor_id'])->where('order_number', $order_number)->first();
                if($order){
                    // if($payment_details['status']['code'] == 200){
                        $order->payment_status = 1;
                        $order->save();
                        $payment_exists = Payment::where('transaction_id', $transactionId)->first();
                        if(!$payment_exists){
                            Payment::insert([
                                'date' => date('Y-m-d'),
                                'order_id' => $order->id,
                                'transaction_id' => $transactionId,
                                'balance_transaction' => $pfData->amount_gross,
                            ]);

                            // Auto accept order
                            $orderController = new OrderController();
                            $orderController->autoAcceptOrderIfOn($order->id);

                            // Remove cart
                            $user_id = $pfData->custom_int1;
                            $cart_id = $pfData->custom_int2;
                            Cart::where('id', $cart_id)->update(['schedule_type' => NULL, 'scheduled_date_time' => NULL]);
                            CartAddon::where('cart_id', $cart_id)->delete();
                            CartCoupon::where('cart_id', $cart_id)->delete();
                            CartProduct::where('cart_id', $cart_id)->delete();
                            CartProductPrescription::where('cart_id', $cart_id)->delete();

                            // Send Notification
                            if (!empty($order->vendors)) {
                                foreach ($order->vendors as $vendor_value) {
                                    $vendor_order_detail = $orderController->minimize_orderDetails_for_notification($order->id, $vendor_value->vendor_id);
                                    $user_vendors = UserVendor::where(['vendor_id' => $vendor_value->vendor_id])->pluck('user_id');
                                    $orderController->sendOrderPushNotificationVendors($user_vendors, $vendor_order_detail);
                                }
                            }
                            $vendor_order_detail = $orderController->minimize_orderDetails_for_notification($order->id);
                            $super_admin = User::where('is_superadmin', 1)->pluck('id');
                            $orderController->sendOrderPushNotificationVendors($super_admin, $vendor_order_detail);

                            // Send Email
                       //     $this->successMail();
                    //     }
                    // }else{
                    }
                }
            }
            elseif($pfData->custom_str2 == 'wallet'){
                $pfData->request->add([
                    'wallet_amount' => $pfData->amount_gross
                ]);
                $wallet = new WalletController();
                $creditWallet = $wallet->creditWallet($pfData);
                $response = $creditWallet->getData();
            }

            if($response->status == 'Success'){
            //    $this->successMail();
                return $this->successResponse($response->data, 'Payment completed successfully.', 200);
            }else{
                $this->failMail();
                return $this->errorResponse($response->message, 400);
            }
        break;
        case 'FAILED':

            if($pfData->custom_str2 == 'cart'){
                $order_number = $pfData->custom_str3;
                $order = Order::with(['paymentOption', 'user_vendor', 'vendors:id,order_id,vendor_id'])->where('order_number', $order_number)->first();
                $order_products = OrderProduct::select('id')->where('order_id', $order->id)->get();
                foreach($order_products as $order_prod){
                    OrderProductAddon::where('order_product_id', $order_prod->id)->delete();
                }
                OrderProduct::where('order_id', $order->id)->delete();
                OrderProductPrescription::where('order_id', $order->id)->delete();
                VendorOrderStatus::where('order_id', $order->id)->delete();
                OrderVendor::where('order_id', $order->id)->delete();
                OrderTax::where('order_id', $order->id)->delete();
                Order::where('id', $order->id)->delete();
            }

            $this->failMail();
            // There was an error, update your application
            return $this->errorResponse('Payment failed', 400);
        break;
        default:
        $this->failMail();
            // If unknown status, do nothing (safest course of action)
            // return $this->errorResponse($response->getMessage(), 400);
        break;
        }
    }*/

}
