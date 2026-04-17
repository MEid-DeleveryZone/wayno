<?php

namespace App\Http\Controllers\Client;

use Auth;
use Omnipay\Omnipay;
use Illuminate\Http\Request;
use Omnipay\Common\CreditCard;
use App\Models\{PaymentOption, Client, ClientCurrency, SubscriptionPlansVendor};
use App\Http\Traits\ApiResponser;
use App\Http\Controllers\Client\BaseController;
use Illuminate\Support\Facades\Validator;
class StripeGatewayController extends BaseController{

    use ApiResponser;
    public $gateway;

    public function __construct()
    {
        $stripe_creds = PaymentOption::select('credentials')->where('code', 'stripe')->where('status', 1)->first();
        $creds_arr = json_decode($stripe_creds->credentials);
        $api_key = (isset($creds_arr->api_key)) ? $creds_arr->api_key : '';
        $this->gateway = Omnipay::create('Stripe');
        $this->gateway->setApiKey($api_key);
        $this->gateway->setTestMode(true); //set it to 'false' when go live
    }

    public function subscriptionPaymentViaStripe(request $request)
    {
        try{
            $user = Auth::user();
            $token = $request->stripe_token;
            $plan = SubscriptionPlansVendor::where('slug',$request->subscription_id)->firstOrFail();
            $request->request->add(['user_id' => $user->id]); //add request
            $saved_payment_method = $this->getSavedVendorPaymentMethod($request);
            if(!$saved_payment_method){
                $customerResponse = $this->gateway->createCustomer(array(
                    'description' => 'Creating Customer for subscription',
                    'email' => $request->email,
                    'source' => $token
                ))->send();
                $customer_id = $customerResponse->getCustomerReference();
                if($customer_id){
                    $request->request->set('customerReference', $customer_id);
                    $save_payment_method_response = $this->saveVendorPaymentMethod($request);
                }
            }
            else{
                $customer_id = $saved_payment_method->customerReference;
            }
            
            // $subscriptionResponse = $this->gateway->createSubscription(array(
            //     "customerReference" => $customer_id,
            //     'plan' => 'Basic Plan',
            // ))->send();
            $authorizeResponse = $this->gateway->authorize([
                'amount' => $request->amount,
                'currency' => 'INR',
                'description' => 'This is a subscription purchase transaction.',
                'customerReference' => $customer_id
            ])->send();
            if ($authorizeResponse->isSuccessful()) {
                $purchaseResponse = $this->gateway->purchase([
                    'currency' => 'INR',
                    'amount' => $request->amount,
                    'metadata' => ['user_id'=>$user->id, 'vendor_id' => $request->vendor_id, 'plan_id' => $plan->id],
                    'description' => 'This is a subscription purchase transaction.',
                    'customerReference' => $customer_id
                ])->send();
                if ($purchaseResponse->isSuccessful()) {
                    return $this->successResponse($purchaseResponse->getData());
                }
                else {
                    return $this->errorResponse($purchaseResponse->getMessage(), 400);
                }
            }
            else {
                return $this->errorResponse($authorizeResponse->getMessage(), 400);
            }
        }catch(\Exception $ex){
            return $this->errorResponse($ex->getMessage(), 400);
        }
    }

}
