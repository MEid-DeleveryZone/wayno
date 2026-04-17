<?php

namespace App\Http\Controllers\Api\v1;

use DB;
use Validation;
use \DateTimeZone;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Traits\ApiResponser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\v1\BaseController;
use App\Models\{Client, ClientPreference, SmsProvider, Currency, Language, Country, User, Vendor, SubscriptionPlansVendor, SubscriptionPlanFeaturesVendor, SubscriptionFeaturesListVendor, SubscriptionInvoicesVendor, SubscriptionInvoiceFeaturesVendor};

class SubscriptionPlansVendorController extends BaseController
{
    use ApiResponser;
    private $folderName = '/subscriptions/image';
    /**
     * Handle the incoming request.
     */
    public function __construct(request $request)
    {
        $preferences = ClientPreference::where(['id' => 1])->first();
        if((isset($preferences->subscription_mode)) && ($preferences->subscription_mode == 0)){
            return $this->errorResponse('Subscription mode is not active', 400);
        }
    }

    /**
     * Get user subscriptions
     */
    public function getSubscriptionPlans()
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
        return response()->json(["status"=>"Success", "data"=>['features'=>$featuresList, 'all_plans'=>$sub_plans, 'subscribed_vendors_count'=>$subscribed_vendors_count, 'subscribed_vendors_percentage'=>$subscribed_vendors_percentage, 'awaiting_approval_subscriptions_count'=>$awaiting_approval_subscriptions_count, 'rejected_subscriptions_count'=>$rejected_subscriptions_count, 'approved_subscriptions_count'=>$approved_subscriptions_count]]);
    }

    /**
     * save user subscription
     * Required Params-
     *  slug (Subscription plan)
     *  image
     *  title
     *  features
     *  price
     *  frequency
     *  status
     *  on_request
     *  description
     */
    public function saveSubscriptionPlan(Request $request, $slug='')
    {
        try{
            DB::beginTransaction();
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
            $validator  = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                foreach($validator->errors()->toArray() as $error_key => $error_value){
                    return $this->errorResponse($error_value[0], 400);
                }
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
            DB::commit();
            return $this->successResponse('', 'Subscription has been '.$message.' successfully.');
        }
        catch(\Exception $ex){
            DB::rollback();
            return $this->errorResponse($ex->getMessage(), 400);
        }
    }

    /**
     * edit user subscription
     * Required Params-
     *  slug (Subscription plan)
     */
    public function editSubscriptionPlan($slug='')
    {
        try{
            $plan = SubscriptionPlansVendor::where('slug', $slug)->first();
            if($plan){
                $planFeatures = SubscriptionPlanFeaturesVendor::select('feature_id')->where('subscription_plan_id', $plan->id)->get();
                $featuresList = SubscriptionFeaturesListVendor::where('status', 1)->get();
                $subPlanFeatures = array();
                foreach($planFeatures as $feature){
                    $subPlanFeatures[] = $feature->feature_id;
                }
                return response()->json(["status"=>"Success", "data"=>['features'=>$featuresList, 'plan' => $plan, 'subPlanFeatures'=>$subPlanFeatures]]);
            }
            else{
                return $this->errorResponse('Invalid data', 400);
            }
        }
        catch(\Exception $ex){
            return $this->errorResponse($ex->getMessage(), 400);
        }
    }

    /**
     * update user subscription status
     * Required Params-
     *  slug (Subscription plan)
     *  status
     */
    public function updateSubscriptionPlanStatus(Request $request, $slug='')
    {
        try{
            DB::beginTransaction();
            $subscription = SubscriptionPlansVendor::where('slug', $slug)->first();
            if($subscription){
                $subscription->status = $request->status;
                $subscription->save();
                DB::commit();
                return response()->json(array("status"=>"Success", 'message'=>'Subscription status has been updated.'));
            }
            else{
                return $this->errorResponse('Invalid data', 400);
            }
        }
        catch(\Exception $ex){
            DB::rollback();
            return $this->errorResponse($ex->getMessage(), 400);
        }
    }

    /**
     * update vendor subscription on request
     * Required Params-
     *  slug (Subscription plan)
     *  on_request
     */
    public function updateSubscriptionPlanOnRequest(Request $request, $slug='')
    {
        try{
            DB::beginTransaction();
            $subscription = SubscriptionPlansVendor::where('slug', $slug)->first();
            if($subscription){
                $subscription->on_request = $request->on_request;
                $subscription->save();
                DB::commit();
                return response()->json(array("status"=>"Success", 'message'=>'Subscription on request status has been updated.'));
            }
            else{
                return $this->errorResponse('Invalid data', 400);
            }
        }
        catch(\Exception $ex){
            DB::rollback();
            return $this->errorResponse($ex->getMessage(), 400);
        }
    }

    /**
     * delete subscription plan
     * Required Params-
     *  slug (Subscription plan)
     */
    public function deleteSubscriptionPlan($slug='')
    {
        try {
            DB::beginTransaction();
            $subscription = SubscriptionPlansVendor::where('slug', $slug)->first();
            if($subscription){
                $subscription->delete();
                DB::commit();
                return response()->json(array("status"=>"Success", 'message'=>'Subscription has been deleted successfully.'));
            }
            else{
                return $this->errorResponse('Invalid data', 400);
            }
        } catch (Exception $e) {
            DB::rollback();
            return $this->errorResponse($ex->getMessage(), 400);
        }
    }

}
