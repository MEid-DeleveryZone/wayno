<?php

namespace App\Http\Controllers\Api\v1;

use Auth;

use Config;
use Session;
use Omnipay\Omnipay;
use Illuminate\Http\Request;
use Omnipay\Common\CreditCard;
use App\Models\{PaymentOption, Client, ClientPreference, ClientCurrency};
use App\Http\Traits\ApiResponser;
use App\Http\Controllers\Api\v1\BaseController;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use DB;

class PaypalGatewayController extends BaseController
{
    use ApiResponser;
    public $gateway;
    public $currency;

    public function __construct()
    {
        $paypal_creds = PaymentOption::select('credentials', 'test_mode')->where('code', 'paypal')->where('status', 1)->first();
        $creds_arr = json_decode($paypal_creds->credentials);
        $username = (isset($creds_arr->username)) ? $creds_arr->username : '';
        $password = (isset($creds_arr->password)) ? $creds_arr->password : '';
        $signature = (isset($creds_arr->signature)) ? $creds_arr->signature : '';
        $testmode = (isset($paypal_creds->test_mode) && ($paypal_creds->test_mode == '1')) ? true : false;
        $this->gateway = Omnipay::create('PayPal_Express');
        $this->gateway->setUsername($username);
        $this->gateway->setPassword($password);
        $this->gateway->setSignature($signature);
        $this->gateway->setTestMode($testmode); //set it to 'false' when go live

        $primaryCurrency = ClientCurrency::where('is_primary', '=', 1)->first();
        $this->currency = (isset($primaryCurrency->currency->iso_code)) ? $primaryCurrency->currency->iso_code : 'USD';
    }

    public function paypalPurchase(Request $request)
    {
        try {
            $amount = $this->getDollarCompareAmount($request->amount);
            $returnUrlParams = '?amount=' . $amount;
            if ($request->has('tip')) {
                $returnUrlParams = $returnUrlParams . '&tip=' . $request->tip;
            }
            $response = $this->gateway->purchase([
                'currency' => 'USD', //$this->currency,
                'amount' => $amount,
                'cancelUrl' => url($request->cancelUrl),
                'returnUrl' => url($request->returnUrl . $returnUrlParams),
            ])->send();
            if ($response->isSuccessful()) {
                return $this->successResponse($response->getData());
            } elseif ($response->isRedirect()) {
                $this->failMail();
                return $this->successResponse($response->getRedirectUrl());
            } else {
                $this->failMail();
                return $this->errorResponse($response->getMessage(), 400);
            }
        } catch (\Exception $ex) {
            $this->failMail();
            return $this->errorResponse($ex->getMessage(), 400);
        }
    }

    public function paypalCompletePurchase(Request $request)
    {
        // Once the transaction has been approved, we need to complete it.
        if ($request->has(['token', 'PayerID'])) {
            $amount = $this->getDollarCompareAmount($request->amount);
            $returnUrlParams = '?amount=' . $amount;
            if ($request->has('tip')) {
                $returnUrlParams = $returnUrlParams . '&tip=' . $request->tip;
            }
            $transaction = $this->gateway->completePurchase(array(
                'amount'                => $amount,
                'payer_id'              => $request->PayerID,
                'transactionReference'  => $request->token,
            //     'cancelUrl' =>  url($request->cancelUrl),
            //     'returnUrl' => url($request->returnUrl . $returnUrlParams),
             ));
            $response = $transaction->send();
            if ($response->isSuccessful()) {
              //  $this->successMail();
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
