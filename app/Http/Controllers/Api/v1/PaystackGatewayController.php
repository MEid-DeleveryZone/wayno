<?php

namespace App\Http\Controllers\Api\v1;

use Auth;
use Omnipay\Omnipay;
use Illuminate\Http\Request;
use Omnipay\Common\CreditCard;
use App\Http\Traits\ApiResponser;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\v1\BaseController;
use App\Models\{PaymentOption, Client, ClientPreference, ClientCurrency};

class PaystackGatewayController extends BaseController
{
    use ApiResponser;
    public $gateway;

    public function __construct()
    {
        $paystack_creds = PaymentOption::select('credentials', 'test_mode')->where('code', 'paystack')->where('status', 1)->first();
        $creds_arr = json_decode($paystack_creds->credentials);
        $secret_key = (isset($creds_arr->secret_key)) ? $creds_arr->secret_key : '';
        $public_key = (isset($creds_arr->public_key)) ? $creds_arr->public_key : '';
        $testmode = (isset($paystack_creds->test_mode) && ($paystack_creds->test_mode == '1')) ? true : false;
        $this->gateway = Omnipay::create('Paystack');
        $this->gateway->setSecretKey($secret_key);
        $this->gateway->setPublicKey($public_key);
        $this->gateway->setTestMode($testmode); //set it to 'false' when go live
        // dd($this->gateway);
    }

    public function paystackPurchase(Request $request){
        try{
            $user = Auth::user();
            $amount = $this->getDollarCompareAmount($request->amount);
            $returnUrlParams = '?amount='.$amount;
            if($request->has('tip')){
                $returnUrlParams = $returnUrlParams.'&tip='.$request->tip.'&gateway=paystack';
            }
            $returnUrlParams = $returnUrlParams.'&gateway=paystack';
            $response = $this->gateway->purchase([
                'amount' => $amount,
                'currency' => 'ZAR',
                'email' => $user->email,
                'returnUrl' => url($request->returnUrl . $returnUrlParams),
                'cancelUrl' => url($request->cancelUrl),
                'metadata' => ['user_id' => $user->id],
                'description' => 'This is a test purchase transaction.',
            ])->send();
            if ($response->isSuccessful()) {
                return $this->successResponse($response->getData());
            }
            elseif ($response->isRedirect()) {
                $this->failMail();
                return $this->successResponse($response->getRedirectUrl());
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

    public function paystackCompletePurchase(Request $request)
    {
        // Once the transaction has been approved, we need to complete it.
        if($request->has(['reference'])){
            $amount = $this->getDollarCompareAmount($request->amount);
            $transaction = $this->gateway->completePurchase(array(
                'amount'                => $amount,
                'transactionReference'  => $request->reference
            ));
            $response = $transaction->send();
            if ($response->isSuccessful()){
                $this->successMail();
                return $this->successResponse($response->getTransactionReference());
            } else {
                $this->failMail();
                return $this->errorResponse($response->getMessage(), 400);
            }
        } else {
            $this->failMail();
            return $this->errorResponse('Transaction has been declined', 400);
        }
    }
}
