<?php

namespace App\Http\Controllers\Front;

use Auth;
use Omnipay\Omnipay;
use App\Models\Payment;
use App\Models\PaymentOption;
use Illuminate\Http\Request;
use App\Models\{Order, User, Cart, ClientCurrency, CartProduct};
use App\Http\Traits\ApiResponser;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Front\FrontController;


class PaymentController extends FrontController{

    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $user = Auth::user();
        $vendor_min_amount_errors = [];
        $cart = Cart::where('user_id', $user->id)->first();
        if($cart){
            $currency_id = Session::get('customerCurrency');
            $currency_symbol = Session::get('currencySymbol');
            $clientCurrency = ClientCurrency::where('currency_id', $currency_id)->first();
            $cart_products = CartProduct::with('vendor','product.pimage', 'product.variants', 'product.taxCategory.taxRate','coupon', 'product.addon')->where('cart_id', $cart->id)->where('status', [0,1])->where('cart_id', $cart->id)->orderBy('created_at', 'asc')->get();
            $dollar_compare = $clientCurrency ? $clientCurrency->doller_compare : 1;
            foreach ($cart_products->groupBy('vendor_id') as $vendor_id => $vendor_cart_products) {
                $vendor_detail = [];
                $vendor_payable_amount = 0;
                $vendor_discount_amount = 0;
                foreach ($vendor_cart_products as $vendor_cart_product) {
                    $vendor_detail = $vendor_cart_product->vendor;
                    $variant = $vendor_cart_product->product->variants->where('id', $vendor_cart_product->variant_id)->first();
                    $quantity_price = 0;
                    $divider = $vendor_cart_product->doller_compare ? $vendor_cart_product->doller_compare : 1;
                    $price_in_currency = $variant->price / $divider;
                    $price_in_dollar_compare = $price_in_currency * $dollar_compare;
                    $quantity_price = $price_in_dollar_compare * $vendor_cart_product->quantity;
                    $vendor_payable_amount = $vendor_payable_amount + $quantity_price;
                    $product_taxable_amount = 0;
                    $product_payable_amount = 0;
                    if(!empty($vendor_cart_product->addon)){
                        foreach ($vendor_cart_product->addon as $ck => $addon) {
                            $opt_quantity_price = 0;
                            $opt_price_in_currency = $addon->option->price;
                            $opt_price_in_doller_compare = $opt_price_in_currency * $clientCurrency->doller_compare;
                            $opt_quantity_price = $opt_price_in_doller_compare * $vendor_cart_product->quantity;
                            $vendor_payable_amount = $vendor_payable_amount + $opt_quantity_price;
                        }
                    }
                }
                // if($vendor_detail){
                //     if($vendor_detail->order_min_amount > 0){
                //         if($vendor_payable_amount < $vendor_detail->order_min_amount){
                //             $vendor_min_amount_errors[]= array(
                //                 'vendor_id' => $vendor_detail->id,
                //                 'message' => "Minimum order should be more than  $currency_symbol $vendor_detail->order_min_amount",
                //             );
                //         }
                //     }
                // }
            }
            // if(count($vendor_min_amount_errors) > 0){
            //     return $this->errorResponse($vendor_min_amount_errors, 402);
            // }
        }
        $ex_codes = ['cod'];
        $payment_options = PaymentOption::select('id', 'code', 'title', 'credentials')->where('status', 1)->get();
        foreach ($payment_options as $k => $payment_option) {
            if( (in_array($payment_option->code, $ex_codes)) || (!empty($payment_option->credentials)) ){
                $payment_option->slug = strtolower(str_replace(' ', '_', $payment_option->title));
                unset($payment_option->credentials);
            }
            else{
                unset($payment_options[$k]);
            }
        }
        return $this->successResponse($payment_options);
    }

    public function paypalCompleteCheckout(Request $request, $domain = '', $token = '', $action = '', $address_id ='')
    {
        return view('frontend.account.complete-checkout')->with(['auth_token' => $token, 'action' => $action, 'address_id' => $address_id]);
    }

    public function getCheckoutSuccess(Request $request, $domain = '', $id = '')
    {
        return view('frontend.account.checkout-success');
    }

    public function getGatewayReturnResponse(Request $request)
    {
        return view('frontend.account.gatewayReturnResponse');
    }
}
