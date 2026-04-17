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
use App\Models\{User, UserVendor, Vendor, UserAddress, ClientPreference, Client, SubscriptionPlansVendor, SubscriptionFeaturesListVendor, SubscriptionInvoicesVendor, SubscriptionInvoiceFeaturesVendor, Payment, PaymentOption};

class VendorSubscriptionController extends BaseController
{
    use ApiResponser;
    /**
     * get Vendor subscriptions.
     *
     * @return \Illuminate\Http\Response
     */
    public function getSubscriptionPlans($id)
    {
        try{
            $sub_plans = SubscriptionPlansVendor::with('features.feature')->where('status', '1')->orderBy('sort_order', 'asc')->get();
            $featuresList = SubscriptionFeaturesListVendor::where('status', 1)->get();
            $active_subscription = SubscriptionInvoicesVendor::with(['plan', 'features.feature', 'status'])
                                ->where('vendor_id', $id)
                                ->where('status_id', '!=', 4)
                                ->orderBy('end_date', 'desc')
                                ->orderBy('id', 'desc')->first();

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
            return response()->json(["status"=>"Success", "data"=>['subscription_plans'=>$sub_plans, 'subscription'=>$active_subscription]]);
        }
        catch(\Exception $ex){
            return $this->errorResponse($ex->getMessage(), 400);
        }
    }
    
    /**
     * select vendor subscription.
     * Required Params-
     *  slug (Subscription plan)
     */
    public function selectSubscriptionPlan($slug = '')
    {
        try{
            $previousSubscriptionActive = $this->checkActiveSubscriptionPlan($slug)->getOriginalContent();
            if( $previousSubscriptionActive['status'] == 'Error' ){
                return $this->errorResponse($previousSubscriptionActive['message'], 400);
            }
            $sub_plan = SubscriptionPlansVendor::with('features.feature')->where('slug', $slug)->where('status', '1')->first();
            if($sub_plan){
                $subFeaturesList = '<ul class="pl-1" style="list-style:none">';
                if($sub_plan->features->isNotEmpty()){
                    foreach($sub_plan->features as $feature){
                        $subFeaturesList = $subFeaturesList.'<li><i class="fa fa-check"> '.$feature->feature->title.'</li>';
                    }
                    unset($sub_plan->features);
                }
                $subFeaturesList = $subFeaturesList.'<ul>';
                $sub_plan->features = $subFeaturesList;
            }
            else{
                return $this->errorResponse('Subscription plan not active', 400);
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
            return response()->json(["status"=>"Success", "sub_plan" => $sub_plan, "payment_options" => $payment_options]);
        }
        catch(\Exception $ex){
            return $this->errorResponse($ex->getMessage(), 400);
        }
    }

    /**
     * check if vendor has any active subscription.
     * Required Params-
     *  id
     *  slug (Subscription plan)
     */
    public function checkActiveSubscriptionPlan($id, $slug = '')
    {
        try{
            $now = Carbon::now()->toDateString();
            $vendorActiveSubscription = SubscriptionInvoicesVendor::with(['plan'])
                                    ->whereNull('cancelled_at')
                                    ->where('vendor_id', $id)
                                    ->where('status_id', '!=', 4)
                                    ->where('end_date', '>=', $now )
                                    ->orderBy('end_date', 'desc')->first();
            if( ($vendorActiveSubscription) && ($vendorActiveSubscription->plan->slug != $slug) ){
                return $this->errorResponse('You cannot buy two subscriptions at the same time', 400);
            }
            return $this->successResponse('', 'Processing...');
        }
        catch(\Exception $ex){
            return $this->errorResponse($ex->getMessage(), 400);
        }
    }

    /**
     * buy vendor subscription.
     * Required Params-
     *  id
     *  slug (Subscription plan)
     *  payment_option_id
     *  transaction_id
     *  amount
     */
    public function purchaseSubscriptionPlan(Request $request, $id, $slug = '')
    {
        try{
            $validator = Validator::make($request->all(), [
                'amount'            => 'required|not_in:0',
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
            $vendor = Vendor::where('id', $id)->first();
            $subscription_plan = SubscriptionPlansVendor::with('features.feature')->where('slug', $slug)->where('status', '1')->first();
            $last_subscription = SubscriptionInvoicesVendor::with(['plan', 'features.feature'])
                ->where('user_id', $user->id)
                ->where('vendor_id', $id)
                ->where('subscription_id', $subscription_plan->id)
                ->where('status_id', 2)
                ->orderBy('end_date', 'desc')->first();
            if( ($vendor) && ($subscription_plan) ){
                $subscription_invoice = new SubscriptionInvoicesVendor;
                $subscription_invoice->vendor_id = $vendor->id;
                $subscription_invoice->user_id = $user->id;
                $subscription_invoice->subscription_id = $subscription_plan->id;
                $subscription_invoice->slug = strtotime(Carbon::now()).'_'.$slug;
                $subscription_invoice->payment_option_id = $request->payment_option_id;
                $subscription_invoice->status_id = ($subscription_plan->on_request == 1) ? (($last_subscription) ? 2 : 1) : 2;
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
                    $payment->vendor_subscription_invoice_id = $subscription_invoice_id;
                    $payment->date = Carbon::now()->format('Y-m-d');
                    $payment->save();

                    $subscription_invoice_features = array();
                    foreach($subscription_plan->features as $feature){
                        $subscription_invoice_features[] = array(
                            'vendor_id' => $vendor->id,
                            'subscription_id' => $subscription_plan->id,
                            'subscription_invoice_id' => $subscription_invoice_id,
                            'feature_id' => $feature->feature_id,
                            'feature_title' => $feature->feature->title
                        );
                    }
                    if(!empty($subscription_invoice_features)){
                        SubscriptionInvoiceFeaturesVendor::insert($subscription_invoice_features);
                    }
                    $message = 'Your subscription has been activated successfully.';
                    if($subscription_plan->on_request == 1){
                        if(empty($last_subscription)){
                            $message = 'You have successfully purchased a subscription. Your request is under processing.';
                        }
                    }
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
     * cancel vendor subscription.
     * Required Params-
     *  id
     *  slug (vendor invoice)
     */
    public function cancelSubscriptionPlan($id, $slug = '')
    {
        try{
            DB::beginTransaction();
            $active_subscription = SubscriptionInvoicesVendor::with('plan')
                                ->where('slug', $slug)
                                ->where('vendor_id', $id)
                                ->where('status_id', 2)
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

    /**
     * update vendor subscription status.
     * Required Params-
     *  slug (vendor invoice)
     *  subscription_status (approve, reject)
     */
    public function updateSubscriptionStatus(Request $request, $slug = '')
    {
        try{
            DB::beginTransaction();
            $validator = Validator::make($request->all(), [
                'subscription_status' => 'required'
            ]);
            if($validator->fails()){
                foreach($validator->errors()->toArray() as $error_key => $error_value){
                    return $this->errorResponse($error_value[0], 400);
                }
            }
            $message = '';
            $subscription_invoice = SubscriptionInvoicesVendor::with('plan')->where('slug', $slug)->first();
            if(!empty($request->subscription_status)){
                $status = $request->subscription_status;
                if($status == 'approve'){
                    $subscription_invoice->status_id = 2;
                    $subscription_invoice->approved_by = Auth::user()->id;
                    $message = 'approved';
                }
                elseif(($status == 'reject') && ($subscription_invoice->status_id != 4)){
                    $subscription_invoice->status_id = 4;
                    $subscription_invoice->rejected_by = Auth::user()->id;
                    if($subscription_invoice->subscription_amount > 0) {
                        $credit_amount = $subscription_invoice->subscription_amount;
                        $user = User::findOrFail($subscription_invoice->user_id);
                        $wallet = $user->wallet;
                        $wallet->depositFloat($credit_amount, ['Wallet has been <b>Credited</b> for rejected '.$subscription_invoice->plan->title]);
                        $message = 'rejected and refunded';
                    }
                }
                else{
                    return $this->errorResponse('Invalid request', 400);
                }
                $subscription_invoice->updated_at = Carbon::now()->toDateTimeString();
                $subscription_invoice->save();
                DB::commit();
                return $this->successResponse('', 'Subscription has been '.$message.' successfully');
            }else{
                return $this->errorResponse('Invalid request', 400);
            }
        }
        catch(\Exception $ex){
            DB::rollback();
            return $this->errorResponse($ex->getMessage(), 400);
        }
    }

    /**
     * Display a listing of the resource.
     * Required Params-
     *  status (invoice status)
     */
    public function getSubscriptionsFilterData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required'
        ]);
        if($validator->fails()){
            foreach($validator->errors()->toArray() as $error_key => $error_value){
                return $this->errorResponse($error_value[0], 400);
            }
        }
        $vendor_subscriptions = SubscriptionInvoicesVendor::with(['plan', 'vendor', 'features.feature', 'status'])->where('status_id', $request->status)->get();
        if($vendor_subscriptions){
            foreach($vendor_subscriptions as $sub){
                $features = '';
                if($sub->features->isNotEmpty()){
                    $subFeaturesList = array();
                    foreach($sub->features as $feature){
                        $subFeaturesList[] = $feature->feature->title;
                    }
                    $features = implode(', ', $subFeaturesList);
                }
                $sub->vendor_name = $sub->vendor->name;
                $sub->vendor_url = route('vendor.catalogs', $sub->vendor_id);
                $sub->sub_features = $features;
                $sub->frequency = ucfirst($sub->frequency);
                $sub->plan_title = $sub->plan->title;
                $sub->plan_url = route('subscription.plans.vendor');
                $sub->sub_status = $sub->status->title;
                if($sub->sub_status == 'Pending'){
                    $sub->sub_status_class = 'info';
                }elseif($sub->sub_status == 'Rejected'){
                    $sub->sub_status_class = 'danger';
                }elseif($sub->sub_status == 'Active'){
                    $sub->sub_status_class = 'success';
                }
            }
        }

        return Datatables::of($vendor_subscriptions)
        ->addIndexColumn()
        ->filter(function ($instance) use ($request) {
            // if (!empty($request->get('search'))) {
            //     $instance->collection = $instance->collection->filter(function ($row) use ($request){
            //         if (Str::contains(Str::lower($row['name']), Str::lower($request->get('search')))){
            //             return true;
            //         }
            //         return false;
            //     });
            // }
        })->make(true);
    }
}
