<?php

namespace App\Http\Controllers\Client;

use DB;
use Session;
use \DateTimeZone;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Traits\ApiResponser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Client\BaseController;
use App\Models\{Client, ClientPreference, SmsProvider, Currency, Language, Country, User, Vendor, SubscriptionPlansVendor, SubscriptionPlanFeaturesVendor, SubscriptionFeaturesListVendor, SubscriptionInvoicesVendor, SubscriptionInvoiceFeaturesVendor};
use Carbon\Carbon;

class SubscriptionPlansVendorController extends BaseController
{
    use ApiResponser;
    private $folderName = '/subscriptions/image';
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
     * Get user subscriptions
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getSubscriptionPlans(Request $request, $domain = '')
    {
        $sub_plans = SubscriptionPlansVendor::with(['features.feature'])->orderBy('id', 'asc')->get();
        $featuresList = SubscriptionFeaturesListVendor::where('status', 1)->get();
        $vendor_subscriptions = SubscriptionInvoicesVendor::where('status_id', 2)->groupBy('vendor_id')->get();
        $awaiting_approval_subscriptions_count = SubscriptionInvoicesVendor::where('status_id', 1)->count();
        $approved_subscriptions_count = SubscriptionInvoicesVendor::where('status_id', 2)->count();
        $rejected_subscriptions_count = SubscriptionInvoicesVendor::where('status_id', 4)->count();
        $subscribed_vendors_count = $vendor_subscriptions->count();
        $active_vendors = Vendor::where('status', 1)->count();
        $subscribed_vendors_percentage = ($subscribed_vendors_count / $active_vendors) * 100;
        $subscribed_vendors_percentage = number_format($subscribed_vendors_percentage, 2);
        if($sub_plans){
            foreach($sub_plans as $plan){
                $features = '';
                if($plan->features->isNotEmpty()){
                    $planFeaturesList = array();
                    foreach($plan->features as $feature){
                        $planFeaturesList[] = $feature->feature->title;
                    }
                    unset($plan->features);
                    $features = implode(', ', $planFeaturesList);
                }
                $plan->features = $features;
            }
        }
        return view('backend/subscriptions/subscriptionPlansVendor')->with(['features'=>$featuresList, 'subscription_plans'=>$sub_plans, 'subscribed_vendors_count'=>$subscribed_vendors_count, 'subscribed_vendors_percentage'=>$subscribed_vendors_percentage, 'awaiting_approval_subscriptions_count'=>$awaiting_approval_subscriptions_count, 'rejected_subscriptions_count'=>$rejected_subscriptions_count, 'approved_subscriptions_count'=>$approved_subscriptions_count]);
    }

    /**
     * save user subscription
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function saveSubscriptionPlan(Request $request, $domain = '', $slug='')
    {
        $message = 'added';
        $rules = array(
            'title' => 'required|string|max:50',
            'features' => 'required',
            'price' => 'required',
            // 'period' => 'required',
            // 'sort_order' => 'required'
        );
        if(!empty($slug)){
            $plan = SubscriptionPlansVendor::where('slug', $slug)->firstOrFail();
            $rules['title'] = $rules['title'].',id,'.$plan->id;
            $message = 'updated';
        }

        $validation  = Validator::make($request->all(), $rules);
        if ($validation->fails()) {
            return redirect()->back()->withInput()->withErrors($validation);
        }
        if(!empty($slug)){
            $subFeatures = SubscriptionPlanFeaturesVendor::where('subscription_plan_id', $plan->id)->whereNotIn('feature_id', $request->features)->delete();
        }else{
            $plan = new SubscriptionPlansVendor;
            $plan->slug = uniqid();
        }
        $plan->title = $request->title;
        $plan->price = $request->price;
        // $plan->period = $request->period;
        $plan->frequency = $request->frequency;
        // $plan->sort_order = $request->sort_order;
        $plan->status = ($request->has('status') && $request->status == 'on') ? '1' : '0';
        $plan->on_request = ($request->has('on_request') && $request->on_request == 'on') ? 1 : 0;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $plan->image = Storage::disk('s3')->put($this->folderName, $file,'public');
        }
        if( ($request->has('description')) && (!empty($request->description)) ){
            $plan->description = $request->description;
        }
        $plan->save();
        $planId = $plan->id;
        if( ($request->has('features')) && (!empty($request->features)) ){
            foreach($request->features as $key => $val){
                if(!empty($slug)){
                    $subFeature = SubscriptionPlanFeaturesVendor::where('subscription_plan_id', $planId)->where('feature_id', $val)->first();
                    if($subFeature){
                        continue;
                    }
                }
                $feature = array(
                    'subscription_plan_id' => $planId,
                    'feature_id' => $val,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                );
                SubscriptionPlanFeaturesVendor::insert($feature);
            }
        }
        return redirect()->back()->with('success', 'Subscription has been '.$message.' successfully.');
    }

    /**
     * edit user subscription
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function editSubscriptionPlan(Request $request, $domain = '', $slug='')
    {
        $plan = SubscriptionPlansVendor::where('slug', $slug)->firstOrFail();
        $planFeatures = SubscriptionPlanFeaturesVendor::select('feature_id')->where('subscription_plan_id', $plan->id)->get();
        $featuresList = SubscriptionFeaturesListVendor::where('status', 1)->get();
        $subPlanFeatures = array();
        foreach($planFeatures as $feature){
            $subPlanFeatures[] = $feature->feature_id;
        }
        $returnHTML = view('backend.subscriptions.edit-subscriptionPlanVendor')->with(['features'=>$featuresList, 'plan' => $plan, 'subPlanFeatures'=>$subPlanFeatures])->render();
        return response()->json(array('success' => true, 'html'=>$returnHTML));
    }

    /**
     * update user subscription status
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateSubscriptionPlanStatus(Request $request, $domain = '', $slug='')
    {
        $subscription = SubscriptionPlansVendor::where('slug', $slug)->firstOrFail();
        $subscription->status = $request->status;
        $subscription->save();
        return response()->json(array('success' => true, 'message'=>'Subscription status has been updated.'));
    }

    /**
     * update vendor subscription on request
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateSubscriptionPlanOnRequest(Request $request, $domain = '', $slug='')
    {
        $subscription = SubscriptionPlansVendor::where('slug', $slug)->firstOrFail();
        $subscription->on_request = $request->on_request;
        $subscription->save();
        return response()->json(array('success' => true, 'message'=>'Subscription on request status has been updated.'));
    }

    /**
     * update user subscription
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteSubscriptionPlan(Request $request, $domain = '', $slug='')
    {
        try {
            $subscription = SubscriptionPlansVendor::where('slug', $slug)->firstOrFail();
            $subscription->delete();
            return redirect()->back()->with('success', 'Subscription has been deleted successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Subscription cannot be deleted.');
        }
    }

}
