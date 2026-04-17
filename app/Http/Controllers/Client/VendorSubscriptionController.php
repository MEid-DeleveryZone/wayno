<?php

namespace App\Http\Controllers\Client;

use DB;
use Auth;
use Session;
use Redirect;
use Timezonelist;
use DataTables;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Traits\ApiResponser;
use App\Http\Traits\ToasterResponser;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Client\BaseController;
use App\Models\{User, UserVendor, Vendor, UserAddress, ClientPreference, Client, SubscriptionPlansVendor, SubscriptionFeaturesListVendor, SubscriptionInvoicesVendor, SubscriptionInvoiceFeaturesVendor, Payment, PaymentOption};

class VendorSubscriptionController extends BaseController
{
    use ToasterResponser;
    use ApiResponser;
    /**
     * get Vendor subscriptions.
     *
     * @return \Illuminate\Http\Response
     */
    public function getSubscriptionPlans($domain = '', $id)
    {
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
        return view('backend.vendor.vendorSubscriptions')->with(['subscription_plans'=>$sub_plans, 'subscription'=>$active_subscription]);
    }
    
    /**
     * select vendor subscription.
     *
     * @return \Illuminate\Http\Response
     */
    public function selectSubscriptionPlan($domain = '', $slug = '')
    {
        $code = array('stripe');
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
            return response()->json(["status"=>"Error", "message" => "Subscription plan not active"]);
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

    /**
     * check if vendor has any active subscription.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkActiveSubscription($domain = '', $id, $slug = '')
    {
        $vendorActiveSubscription = SubscriptionInvoicesVendor::with(['plan'])
                                ->whereNull('cancelled_at')
                                ->where('vendor_id', $id)
                                ->where('status_id', '!=', 4)
                                ->orderBy('end_date', 'desc')->first();
        if( ($vendorActiveSubscription) && ($vendorActiveSubscription->plan->slug != $slug) ){
            return $this->errorResponse('You cannot buy two subscriptions at the same time', 402);
        }
        return $this->successResponse('', 'Processing...');
    }

    /**
     * buy vendor subscription.
     *
     * @return \Illuminate\Http\Response
     */
    public function purchaseSubscriptionPlan(Request $request, $domain = '', $id, $slug = '')
    {
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
                Session::put('success', $message);
                return $this->successResponse('', $message);
            }
            else{
                return $this->errorResponse('Error in purchasing subscription.', 402);
            }
        }
        else{
            return $this->errorResponse('Invalid Data', 402);
        }
    }

    /**
     * cancel vendor subscription.
     *
     * @return \Illuminate\Http\Response
     */
    public function cancelSubscriptionPlan($domain = '', $id, $slug = '')
    {
        $active_subscription = SubscriptionInvoicesVendor::with('plan')
                            ->where('slug', $slug)
                            ->where('vendor_id', $id)
                            ->where('status_id', 2)
                            ->orderBy('end_date', 'desc')->first();
        if($active_subscription){
            $active_subscription->cancelled_at = $active_subscription->end_date;
            $active_subscription->updated_at = Carbon::now()->toDateTimeString();
            $active_subscription->save();
            return redirect()->back()->with('success', 'Your '.$active_subscription->plan->title.' subscription has been cancelled successfully');
        }
        else{
            return redirect()->back()->with('error', 'Unable to cancel subscription');
        }
    }

    /**
     * update vendor subscription status.
     *
     * @return \Illuminate\Http\Response
     */
    public function updateSubscriptionStatus(Request $request, $domain = '', $slug = '')
    {
        $message = '';
        $subscription_invoice = SubscriptionInvoicesVendor::with('plan')->where('slug', $slug)->firstOrFail();
        if(!empty($request->subscription_status)){
            DB::beginTransaction();
            try {
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
                    return redirect()->back()->with('error', 'Invalid request');
                }
                $subscription_invoice->updated_at = Carbon::now()->toDateTimeString();
                $subscription_invoice->save();
                DB::commit();
                return redirect()->back()->with('success', 'Subscription has been '.$message.' successfully');
            } 
            catch (Exception $e) {
                DB::rollback();
                return redirect()->back()->with('error', $e->getMessage());
            }
        }else{
            return redirect()->back()->with('error', 'Invalid request');
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getSubscriptionsFilterData(Request $request)
    {
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
