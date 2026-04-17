<?php

namespace App\Http\Controllers\Front;

use Auth;
use Session;
use Timezonelist;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Traits\ApiResponser;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail; 
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Front\FrontController;
use App\Models\{User, UserAddress, ClientPreference, Client, ClientCurrency, SubscriptionPlansUser, SubscriptionFeaturesListUser, SubscriptionInvoicesUser, SubscriptionInvoiceFeaturesUser, Payment, PaymentOption};

class UserSubscriptionController extends FrontController
{
    use ApiResponser;

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __construct(request $request)
    {
        $preferences = ClientPreference::where(['id' => 1])->first();
        if((isset($preferences->subscription_mode)) && ($preferences->subscription_mode == 0)){
            abort(404);
        }
    }

    /**
     * get user subscriptions.
     *
     * @return \Illuminate\Http\Response
     */
    public function getSubscriptionPlans(Request $request, $domain = '')
    {
        $langId = Session::get('customerLanguage');
        $navCategories = $this->categoryNav($langId);
        $currency_id = Session::get('customerCurrency');
        $clientCurrency = ClientCurrency::where('currency_id', $currency_id)->first();
        $sub_plans = SubscriptionPlansUser::with('features.feature')->where('status', '1')->orderBy('id', 'asc')->get();
        $featuresList = SubscriptionFeaturesListUser::where('status', 1)->get();
        $active_subscription = SubscriptionInvoicesUser::with(['plan', 'features.feature'])
                            ->where('user_id', Auth::user()->id)
                            ->orderBy('end_date', 'desc')->first();
        // $active_subscription_plan_ids = array();
        // foreach($active_subscription as $subscription){
        //     $active_subscription_plan_ids[] = $active_subscription->subscription_id;
        // }

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
            }
        }
        return view('frontend.account.userSubscriptions')->with(['navCategories'=>$navCategories, 'subscription_plans'=>$sub_plans, 'subscription'=>$active_subscription, 'clientCurrency'=>$clientCurrency]);
    }
    
    /**
     * select user subscription.
     *
     * @return \Illuminate\Http\Response
     */
    public function selectSubscriptionPlan(Request $request, $domain = '', $slug = '')
    {
        $langId = Session::get('customerLanguage');
        $navCategories = $this->categoryNav($langId);
        $currency_id = Session::get('customerCurrency');
        $currencySymbol = Session::get('currencySymbol');
        $clientCurrency = ClientCurrency::where('currency_id', $currency_id)->first();
        $sub_plan = SubscriptionPlansUser::with('features.feature')->where('slug', $slug)->where('status', '1')->first();
        if($sub_plan){
            $subFeaturesList = '<ul>';
            if($sub_plan->features->isNotEmpty()){
                foreach($sub_plan->features as $feature){
                    $subFeaturesList = $subFeaturesList.'<li><i class="fa fa-check"></i><span class="ml-1">'.$feature->feature->title.'</span></li>';
                }
                unset($sub_plan->features);
            }
            $subFeaturesList = $subFeaturesList.'<ul>';
            $sub_plan->features = $subFeaturesList;
            $sub_plan->price = $sub_plan->price * $clientCurrency->doller_compare;
        }
        else{
            return response()->json(["status"=>"Error", "message" => __("Subscription plan not active")]);
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
        return response()->json(["status"=>"Success", "sub_plan" => $sub_plan, "payment_options" => $payment_options, "currencySymbol"=>$currencySymbol]);
    }

    /**
     * check if user has any active subscription.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkActiveSubscription(Request $request, $domain = '', $slug = '')
    {
        $now = Carbon::now()->toDateString();
        $userActiveSubscription = SubscriptionInvoicesUser::with(['plan'])
                                ->whereNull('cancelled_at')
                                ->where('user_id', Auth::user()->id)
                                ->where('end_date', '>=', $now )
                                ->orderBy('end_date', 'desc')->first();
        if( ($userActiveSubscription) && ($userActiveSubscription->plan->slug != $slug) ){
            return $this->errorResponse(__('You cannot buy two subscriptions at the same time'), 402);
        }
        return $this->successResponse('', 'Processing...');
    }

    /**
     * buy user subscription.
     *
     * @return \Illuminate\Http\Response
     */
    public function purchaseSubscriptionPlan(Request $request, $domain = '', $slug = '')
    {
        $user = Auth::user();
        $subscription_plan = SubscriptionPlansUser::with('features.feature')->where('slug', $slug)->where('status', '1')->first();
        $last_subscription = SubscriptionInvoicesUser::with(['plan', 'features.feature'])
            ->where('user_id', Auth::user()->id)
            ->where('subscription_id', $subscription_plan->id)
            ->orderBy('end_date', 'desc')->first();
        if( ($user) && ($subscription_plan) ){
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
            $subscription_invoice->subscription_amount = $request->amount;
            $subscription_invoice->save();
            $subscription_invoice_id = $subscription_invoice->id;
            if($subscription_invoice_id){
                $payment = new Payment;
                $payment->balance_transaction = $request->amount;
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
                $message = __('Your subscription has been activated successfully.');
                Session::put('success', $message);
                return $this->successResponse('', $message);
            }
            else{
                return $this->errorResponse(__('Error in purchasing subscription.'), 402);
            }
        }
        else{
            return $this->errorResponse(__('Invalid Data'), 402);
        }
    }

    /**
     * cancel user subscription.
     *
     * @return \Illuminate\Http\Response
     */
    public function cancelSubscriptionPlan(Request $request, $domain = '', $slug = '')
    {
        $active_subscription = SubscriptionInvoicesUser::with('plan')
                            ->where('slug', $slug)
                            ->where('user_id', Auth::user()->id)
                            ->orderBy('end_date', 'desc')->first();
        if($active_subscription){
            $active_subscription->cancelled_at = $active_subscription->end_date;
            $active_subscription->updated_at = Carbon::now()->toDateTimeString();
            $active_subscription->save();
            return redirect()->back()->with('success', 'Your '.$active_subscription->plan->title.' subscription has been cancelled successfully');
        }
        else{
            return redirect()->back()->with('error', __('Unable to cancel subscription'));
        }
    }
}
