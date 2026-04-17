<?php

namespace App\Http\Controllers\Api\v1;


use Log;
use Auth;
//use WebhookCall;
use Omnipay\Omnipay;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Omnipay\Common\CreditCard;
use App\Http\Traits\ApiResponser;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\v1\BaseController;
use App\Http\Controllers\Api\v1\OrderController;
use App\Models\{User, UserVendor, Cart, CartAddon, CartCoupon, CartProduct, CartProductPrescription, Payment, PaymentOption, Client, ClientPreference, ClientCurrency, Order, OrderProduct, OrderProductAddon, OrderProductPrescription, VendorOrderStatus, OrderVendor, OrderTax};
use Illuminate\Support\Facades\Auth as FacadesAuth;

class PaylinkGatewayController extends BaseController
{
    use ApiResponser;
    public $API_KEY;
    public $API_SECRET_KEY;
    public $test_mode;




    public function __construct()
    {
        $paylink_creds = PaymentOption::select('credentials', 'test_mode')->where('code', 'paylink')->where('status', 1)->first();
        $creds_arr = json_decode($paylink_creds->credentials);
        $api_key = (isset($creds_arr->api_key)) ? $creds_arr->api_key : '';
        $api_secret_key = (isset($creds_arr->api_secret_key)) ? $creds_arr->api_secret_key : '';
        $this->test_mode = (isset($paylink_creds->test_mode) && ($paylink_creds->test_mode == '1')) ? true : false;

        $this->API_KEY = $api_key;
        $this->API_SECRET_KEY = $api_secret_key;
    }

    public function paylinkPurchase(Request $request)
    {
        try {
            $user = Auth::user();
            $cart = Cart::select('id')->where('status', '0')->where('user_id', $user->id)->first();
            $amount = $this->getDollarCompareAmount($request->amount);

            $returnUrlParams = '?gateway=paylink&order=' . $request->order_number;

            $returnUrl = route('order.return.success');
            if ($request->payment_form == 'wallet') {
                $returnUrl = route('user.wallet');
            }
            $uniqid = uniqid();

            $notifyUrlParams = '?gateway=paylink&amount=' . $request->amount . '&cart_id=' . $request->cart_id . '&order=' . $request->order_number;

            $data = array(
                'requestId' => 'CHK-' . $uniqid,
                'orderId' => 'CHK-100000214',
                'amount' => $amount,
                'currency' => 'AED',
                'description' => 'Order Checkout',

                'reference' => $request->order_number,


                'returnUrl' => url('payment/paylink/notify' . $notifyUrlParams),

                'redirect' => true,
                'test' => $this->test_mode, // True, testing, false, production

                'customer' => array(
                    'firstName' => $user->name,
                    'lastName' => '-',
                    'email' => $user->email,
                    'phone' => $user->phone_number,
                    // 'identification' => '12123123',
                    'cart_id' => $cart->id
                ),
                'billingAddress' => array(
                    'name' => $user->address->first()->address,
                    'address1' => $user->address->first()->address,
                    'address2' => $user->address->first()->address,
                    'street' =>  $user->address->first()->street,
                    // 'identification' => '12123123',
                    'city' => $user->address->first()->city,
                    'state' => $user->address->first()->state,
                    'zip' => $user->address->first()->pincode,
                    'country' => 'AED'
                ),
                'items' => array(
                    'name' => 'Dark grey sunglasses',
                    'sku' => '1116521',
                    'unitprice' => 50,
                    'quantity' => 2,
                    'linetotal' => 100
                )
            );


            $ch = curl_init('https://api.test.pointcheckout.com/mer/v2.0/checkout/web');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLINFO_HEADER_OUT, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt(
                $ch,
                CURLOPT_HTTPHEADER,
                array(
                    'Content-Type: application/json',
                    'X-PointCheckout-Api-Key:' . $this->API_KEY,
                    'X-PointCheckout-Api-Secret:' . $this->API_SECRET_KEY
                )
            );

            $result = curl_exec($ch);
            curl_close($ch);
            $result = json_decode($result);

            $result_from_url = '&status=' . $result->result->status;


            if ($result->success == true) {
                // $curl = curl_init();

                return $this->successResponse($result->result->redirectUrl, ['status' => $result->result->status]);
            } else {
                return $this->errorResponse($result->error, 400);
            }
        } catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage(), 400);
        }
    }



    public function paylinkNotify(Request $request, $domain = '')
    {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.test.pointcheckout.com/mer/v2.0/checkout/' . $request->checkout,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'X-PointCheckout-Api-Key:' . $this->API_KEY,
                'X-PointCheckout-Api-Secret:' . $this->API_SECRET_KEY,
            ),
        ));


        $response = curl_exec($curl);

        curl_close($curl);
        $response = json_decode($response);

        //  dd($response);
        $transactionId = $request->checkout;
        $order_number = $request->order;
        $order = Order::with(['paymentOption', 'user_vendor', 'vendors:id,order_id,vendor_id'])->where('order_number', $order_number)->first();


        if ($response->result->status == 'PAID') {

            // if ($request->succes == 'true') {

            if ($order) {
                $order->payment_status = 1;
                $order->save();
                $payment_exists = Payment::where('transaction_id', $transactionId)->first();
                if (!$payment_exists) {
                    Payment::insert([
                        'date' => date('Y-m-d'),
                        'order_id' => $order->id,
                        'transaction_id' => $transactionId,
                        'balance_transaction' => $request->amount,
                    ]);

                    // Auto accept order
                    $orderController = new OrderController();
                    $orderController->autoAcceptOrderIfOn($order->id);

                    // Remove cart

                    Cart::where('id', $request->cart_id)->update(['schedule_type' => null, 'scheduled_date_time' => null]);
                    CartAddon::where('cart_id', $request->cart_id)->delete();
                    CartCoupon::where('cart_id', $request->cart_id)->delete();
                    CartProduct::where('cart_id', $request->cart_id)->delete();
                    CartProductPrescription::where('cart_id', $request->cart_id)->delete();

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
                    $returnUrlParams = '?gateway=paylink&order=' . $order->id;
                    $returnUrl = route('order.return.success');
                    return Redirect::to(url($returnUrl . $returnUrlParams));
                }

                // Send Email
                //   $this->successMail();
            }
        } else {
            $order_products = OrderProduct::select('id')->where('order_id', $order->id)->get();
            foreach ($order_products as $order_prod) {
                OrderProductAddon::where('order_product_id', $order_prod->id)->delete();
            }
            OrderProduct::where('order_id', $order->id)->delete();
            OrderProductPrescription::where('order_id', $order->id)->delete();
            VendorOrderStatus::where('order_id', $order->id)->delete();
            OrderVendor::where('order_id', $order->id)->delete();
            OrderTax::where('order_id', $order->id)->delete();
            Order::where('id', $order->id)->delete();
            return Redirect::to(url('viewcart'));
        }
    }

    
}
