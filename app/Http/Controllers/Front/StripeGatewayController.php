<?php

namespace App\Http\Controllers\Front;

use Auth;
use Session;
use Omnipay\Omnipay;
use Illuminate\Http\Request;
use Omnipay\Common\CreditCard;
use App\Models\{PaymentOption, Client, ClientPreference, ClientCurrency, SubscriptionPlansUser, UserAddress};
use App\Http\Traits\ApiResponser;
use App\Http\Controllers\Front\FrontController;
use Illuminate\Support\Facades\Validator;

class StripeGatewayController extends FrontController
{

    use ApiResponser;
    public $gateway;
    public $currency;

    public function __construct()
    {
        $stripe_creds = PaymentOption::select('credentials', 'test_mode')->where('code', 'stripe')->where('status', 1)->first();
        $creds_arr = json_decode($stripe_creds->credentials);
        $api_key = (isset($creds_arr->api_key)) ? $creds_arr->api_key : '';
        $testmode = (isset($stripe_creds->test_mode) && ($stripe_creds->test_mode == '1')) ? true : false;
        $this->gateway = Omnipay::create('Stripe');
        $this->gateway->setApiKey($api_key);
        $this->gateway->setTestMode($testmode); //set it to 'false' when go live

        $primaryCurrency = ClientCurrency::where('is_primary', '=', 1)->first();
        $this->currency = (isset($primaryCurrency->currency->iso_code)) ? $primaryCurrency->currency->iso_code : 'USD';
    }

    public function postPaymentViaStripe(request $request)
    {
        try {
            $user = Auth::user();
            $address = UserAddress::where('user_id', $user->id);
            $amount = $this->getDollarCompareAmount($request->amount);
            $token = $request->input('stripe_token');
            $response = $this->gateway->purchase([
                'currency' => $this->currency,
                'token' => $token,
                'amount' => $amount,
                'metadata' => ['cart_id' => ($request->cart_id) ? $request->cart_id : 0],
                'description' => 'This is a test purchase transaction.',
            //     'name'=>Auth::user()->name,
            //     'address' => [
            //        'line1'       => '510 Townsend St',
            //        'postal_code' => '98140',
            //        'city'        => 'San Francisco',
            //        'state'       => 'CA',
            //        'country'     => 'US',
            //    ],
                // 'name' => Auth::user()->name,
                // 'address' => $address->address . ', ' . $address->state . ', ' . $address->country . ', ' . $address->pincode,
            ])->send();
            if ($response->isSuccessful()) {
               // $this->successMail();
                return $this->successResponse($response->getData());
            }
            // elseif ($response->isRedirect()) {
            //     return $this->errorResponse($response->getRedirectUrl(), 400);
            // } 
            else {
                $this->failMail();
                return $this->errorResponse($response->getMessage(), 400);
            }
        } catch (\Exception $ex) {
            $this->failMail();
            return $this->errorResponse($ex->getMessage(), 400);
        }
    }

    public function subscriptionPaymentViaStripe(request $request)
    {
        try {
            $user = Auth::user();
            $address = UserAddress::where('user_id', $user->id);
            $token = $request->stripe_token;
            $plan = SubscriptionPlansUser::where('slug', $request->subscription_id)->firstOrFail();
            $saved_payment_method = $this->getSavedUserPaymentMethod($request);
            if (!$saved_payment_method) {
                $customerResponse = $this->gateway->createCustomer(array(
                    'description' => 'Creating Customer for subscription',
                    'email' => $request->email,
                    'source' => $token
                ))->send();
                // Find the card ID
                $customer_id = $customerResponse->getCustomerReference();
                if ($customer_id) {
                    $request->request->set('customerReference', $customer_id);
                    $save_payment_method_response = $this->saveUserPaymentMethod($request);
                }
            } else {
                $customer_id = $saved_payment_method->customerReference;
            }

            // $subscriptionResponse = $this->gateway->createSubscription(array(
            //     "customerReference" => $customer_id,
            //     'plan' => 'Basic Plan',
            // ))->send();

            $amount = $this->getDollarCompareAmount($request->amount);
            $authorizeResponse = $this->gateway->authorize([
                'amount' => $amount,
                'currency' => $this->currency,
                'description' => 'This is a subscription purchase transaction.',
                'customerReference' => $customer_id
            ])->send();
            if ($authorizeResponse->isSuccessful()) {
                $purchaseResponse = $this->gateway->purchase([
                    'currency' => $this->currency,
                    'amount' => $amount,
                    'metadata' => ['user_id' => $user->id, 'plan_id' => $plan->id],
                    'description' => 'This is a subscription purchase transaction.',
                    'customerReference' => $customer_id
                ])->send();
                if ($purchaseResponse->isSuccessful()) {
                  //  $this->successMail();
                    return $this->successResponse($purchaseResponse->getData());
                } else {
                    $this->failMail();
                    return $this->errorResponse($purchaseResponse->getMessage(), 400);
                }
            } else {
                $this->failMail();
                return $this->errorResponse($authorizeResponse->getMessage(), 400);
            }
        } catch (\Exception $ex) {
            $this->failMail();
            return $this->errorResponse($ex->getMessage(), 400);
        }
    }
}
