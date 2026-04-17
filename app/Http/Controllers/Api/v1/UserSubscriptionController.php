<?php

namespace App\Http\Controllers\Api\v1;

use DB;
use Validation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Traits\ApiResponser;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\v1\BaseController;
use App\Models\{User, UserAddress, ClientPreference, Client, ClientCurrency, SubscriptionPlansUser, SubscriptionFeaturesListUser, SubscriptionInvoicesUser, SubscriptionInvoiceFeaturesUser, Payment, PaymentOption};

class UserSubscriptionController extends BaseController
{
    use ApiResponser;

    /**
     * get user subscriptions.
     *
     * @return \Illuminate\Http\Response
     */
    public function getSubscriptionPlans(Request $request)
    {
        $user = Auth::user();
        $currency_id = $user->currency;
        $clientCurrency = ClientCurrency::where('currency_id', $currency_id)->first();
        $sub_plans = SubscriptionPlansUser::with('features.feature')->where('status', '1')->orderBy('id', 'asc')->get();
        $featuresList = SubscriptionFeaturesListUser::where('status', 1)->get();
        $active_subscription = SubscriptionInvoicesUser::with(['plan', 'features.feature'])
                            ->where('user_id', $user->id)
                            ->orderBy('end_date', 'desc')->first();
        if($sub_plans){
            foreach($sub_plans as $sub){
                $subFeaturesList = array();
                if($sub->features->isNotEmpty()){
                    foreach($sub->features as $feature){
                        $subFeaturesList[] = $feature->feature->title;
                    }
                    unset($sub->features);
                }
                $sub->features = $subFeaturesList;
                $sub->price = $sub->price * $clientCurrency->doller_compare;
            }
        }
        return response()->json(["status"=>"Success", "data"=>['all_plans'=>$sub_plans, 'subscription'=>$active_subscription, "clientCurrency"=>$clientCurrency]]);
    }
    
    /**
     * select user subscription.
     * Required Params-
     *  slug
     * 
     * @return \Illuminate\Http\Response
     */
    public function selectSubscriptionPlan(Request $request, $slug = '')
    {
        try{
            $user = Auth::user();
            $currency_id = $user->currency;
            $clientCurrency = ClientCurrency::where('currency_id', $currency_id)->first();
            $previousSubscriptionActive = $this->checkActiveSubscriptionPlan($slug)->getOriginalContent();
            if( $previousSubscriptionActive['status'] == 'Error' ){
                return $this->errorResponse($previousSubscriptionActive['message'], 400);
            }
            $sub_plan = SubscriptionPlansUser::with('features.feature')->where('slug', $slug)->first();
            if($sub_plan){
                if($sub_plan->status == '1'){
                    $subFeaturesList = array();
                    if($sub_plan->features->isNotEmpty()){
                        foreach($sub_plan->features as $feature){
                            $subFeaturesList[] = $feature->feature->title;
                        }
                        unset($sub_plan->features);
                    }
                    $sub_plan->features = $subFeaturesList;
                    $sub_plan->price = $sub_plan->price * $clientCurrency->doller_compare;
                }
                else{
                    return response()->json(["status"=>"Error", "message" => "Subscription plan not active"]);
                }
            }
            else{
                return response()->json(["status"=>"Error", "message" => "Invalid Data"]);
            }
            $code = array('stripe');
            $ex_codes = array('cod');
            $payment_options = PaymentOption::select('id', 'code', 'title', 'credentials')->whereIn('code', $code)->where('status', 1)->get();
            foreach ($payment_options as $k => $payment_option) {
                if( (in_array($payment_option->code, $ex_codes)) || (!empty($payment_option->credentials)) ){
                    $payment_option->slug = strtolower(str_replace(' ', '_', $payment_option->title));
                    unset($payment_option->credentials);
                }
                else{
                    unset($payment_options[$k]);
                }
            }
            return response()->json(["status"=>"Success", "data"=>["sub_plan" => $sub_plan, "payment_options" => $payment_options]]);
        }
        catch(\Exception $ex){
            return $this->errorResponse($ex->getMessage(), 400);
        }
    }

    /**
     * check if user has any active subscription.
     * Required Params-
     *  slug
     * 
     * @return \Illuminate\Http\Response
     */
    public function checkActiveSubscriptionPlan($slug = '')
    {
        try{
            $user = Auth::user();
            $now = Carbon::now()->toDateString();
            $userActiveSubscription = SubscriptionInvoicesUser::with(['plan'])
                                ->whereNull('cancelled_at')
                                ->where('user_id', $user->id)
                                ->where('end_date', '>=', $now )
                                ->orderBy('end_date', 'desc')->first();
            if( ($userActiveSubscription) && ($userActiveSubscription->plan->slug != $slug) ){
                return $this->errorResponse('You cannot buy two subscriptions at the same time', 400);
            }
            return $this->successResponse('', 'Processing...');
        }
        catch(\Exception $ex){
            return $this->errorResponse($ex->getMessage(), 400);
        }
    }

    /**
     * buy user subscription.
     * Required Params-
     *  slug
     *  payment_option_id
     *  transaction_id
     *  amount
     * 
     * @return \Illuminate\Http\Response
     */
    public function purchaseSubscriptionPlan(Request $request, $slug = '')
    {
        try{
            $validator = Validator::make($request->all(), [
                // 'amount'            => 'required|not_in:0',
                'transaction_id'    => 'required',
                'payment_option_id' => 'required',
            ]);
            if($validator->fails()){
                foreach($validator->errors()->toArray() as $error_key => $error_value){
                    return $this->errorResponse($error_value[0], 400);
                }
            }
            DB::beginTransaction();
            $user = Auth::user();
            $subscription_plan = SubscriptionPlansUser::with('features.feature')->where('slug', $slug)->where('status', '1')->first();
            if( ($user) && ($subscription_plan) ){
                $last_subscription = SubscriptionInvoicesUser::with(['plan', 'features.feature'])
                    ->where('user_id', $user->id)
                    ->where('subscription_id', $subscription_plan->id)
                    ->orderBy('end_date', 'desc')->first();
                $subscription_invoice = new SubscriptionInvoicesUser;
                $subscription_invoice->user_id = $user->id;
                $subscription_invoice->subscription_id = $subscription_plan->id;
                $subscription_invoice->slug = strtotime(Carbon::now()).'_'.$slug;
                $subscription_invoice->payment_option_id = $request->payment_option_id;
                // $subscription_invoice->status_id = 2;
                $subscription_invoice->frequency = $subscription_plan->frequency;
                $subscription_invoice->payment_option_id = $request->payment_option_id;
                $subscription_invoice->transaction_reference = $request->transaction_id;
                $now = Carbon::now();
                $current_date = $now->toDateString();
                $start_date = $current_date;
                $next_date = NULL;
                $end_date = NULL;

                if($last_subscription){
                    if($last_subscription->end_date >= $current_date){
                        $start_date = Carbon::parse($last_subscription->end_date)->addDays(1)->toDateString();
                    }
                }
                if($subscription_plan->frequency == 'weekly'){
                    $end_date = Carbon::parse($start_date)->addDays(6)->toDateString();
                }elseif($subscription_plan->frequency == 'monthly'){
                    $end_date = Carbon::parse($start_date)->addMonths(1)->subDays(1)->toDateString();
                }elseif($subscription_plan->frequency == 'yearly'){
                    $end_date = Carbon::parse($start_date)->addYears(1)->subDays(1)->toDateString();
                }
                $next_date = Carbon::parse($end_date)->addDays(1)->toDateString();
                $subscription_invoice->start_date = $start_date;
                $subscription_invoice->next_date = $next_date;
                $subscription_invoice->end_date = $end_date;
                $subscription_invoice->subscription_amount = $subscription_plan->price;
                $subscription_invoice->save();
                $subscription_invoice_id = $subscription_invoice->id;
                if($subscription_invoice_id){
                    $payment = new Payment;
                    $payment->balance_transaction = $subscription_plan->price;
                    $payment->transaction_id = $request->transaction_id;
                    $payment->user_subscription_invoice_id = $subscription_invoice_id;
                    $payment->date = Carbon::now()->format('Y-m-d');
                    $payment->save();

                    $subscription_invoice_features = array();
                    foreach($subscription_plan->features as $feature){
                        $subscription_invoice_features[] = array(
                            'user_id' => $user->id,
                            'subscription_id' => $subscription_plan->id,
                            'subscription_invoice_id' => $subscription_invoice_id,
                            'feature_id' => $feature->feature_id,
                            'feature_title' => $feature->feature->title
                        );
                    }
                    if(!empty($subscription_invoice_features)){
                        SubscriptionInvoiceFeaturesUser::insert($subscription_invoice_features);
                    }
                    $message = 'Your subscription has been activated successfully.';
                    DB::commit();
                    return $this->successResponse('', $message);
                }
                else{
                    DB::rollback();
                    return $this->errorResponse('Error in purchasing subscription.', 400);
                }
            }
            else{
                return $this->errorResponse('Invalid Data', 400);
            }
        }
        catch(\Exception $ex){
            DB::rollback();
            return $this->errorResponse($ex->getMessage(), 400);
        }
    }

    /**
     * cancel user subscription.
     * Required Params-
     *  slug
     * 
     * @return \Illuminate\Http\Response
     */
    public function cancelSubscriptionPlan($slug = '')
    {
        try{
            DB::beginTransaction();
            $active_subscription = SubscriptionInvoicesUser::with('plan')
                                ->where('slug', $slug)
                                ->where('user_id', Auth::user()->id)
                                ->orderBy('end_date', 'desc')->first();
            if($active_subscription){
                $active_subscription->cancelled_at = $active_subscription->end_date;
                $active_subscription->updated_at = Carbon::now()->toDateTimeString();
                $active_subscription->save();
                DB::commit();
                return $this->successResponse('', 'Your '.$active_subscription->plan->title.' subscription has been cancelled successfully');
            }
            else{
                return $this->errorResponse('Unable to cancel subscription', 400);
            }
        }
        catch(\Exception $ex){
            DB::rollback();
            return $this->errorResponse($ex->getMessage(), 400);
        }
    }
}
