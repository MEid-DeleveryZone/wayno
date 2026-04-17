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
use App\Models\{Client, ClientPreference, SmsProvider, Currency, Language, Country, User, SubscriptionPlansUser, SubscriptionPlanFeaturesUser,  SubscriptionFeaturesListUser, SubscriptionInvoicesUser};

class SubscriptionPlansUserController extends BaseController
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
    public function getSubscriptionPlans(Request $request)
    {
        $sub_plans = SubscriptionPlansUser::with(['features.feature'])->orderBy('id', 'asc')->get();
        $featuresList = SubscriptionFeaturesListUser::where('status', 1)->get();
        $user_subscriptions = SubscriptionInvoicesUser::groupBy('user_id')->get();
        $subscribed_users_count = $user_subscriptions->count();
        $active_users = User::where('status', 1)->count();
        $subscribed_users_percentage = ($subscribed_users_count / $active_users) * 100;
        $subscribed_users_percentage = number_format($subscribed_users_percentage, 2);
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
        return response()->json(["status"=>"Success", "data"=>['features'=>$featuresList, 'all_plans'=>$sub_plans, 'subscribed_users_count'=>$subscribed_users_count, 'subscribed_users_percentage'=>$subscribed_users_percentage]]);
    }

    /**
     * save user subscription
     * Required Params-
     *  image
     *  slug (Subscription plan)
     *  title
     *  features
     *  price
     *  frequency
     *  status
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
                $plan = SubscriptionPlansUser::where('slug', $slug)->first();
                if(empty($plan)){
                    return $this->errorResponse('Invalid Data', 400);
                }
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
                $subFeatures = SubscriptionPlanFeaturesUser::where('subscription_plan_id', $plan->id)->whereNotIn('feature_id', $request->features)->delete();
            }else{
                $plan = new SubscriptionPlansUser;
                $plan->slug = uniqid();
            }
            $plan->title = $request->title;
            $plan->price = $request->price;
            // $plan->period = $request->period;
            $plan->frequency = $request->frequency;
            // $plan->sort_order = $request->sort_order;
            $plan->status = ($request->has('status') && $request->status == 'on') ? '1' : '0';
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
                        $subFeature = SubscriptionPlanFeaturesUser::where('subscription_plan_id', $planId)->where('feature_id', $val)->first();
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
                    SubscriptionPlanFeaturesUser::insert($feature);
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
            $plan = SubscriptionPlansUser::where('slug', $slug)->first();
            if($plan){
                $planFeatures = SubscriptionPlanFeaturesUser::select('feature_id')->where('subscription_plan_id', $plan->id)->get();
                $featuresList = SubscriptionFeaturesListUser::where('status', 1)->get();
                $subPlanFeatures = array();
                foreach($planFeatures as $feature){
                    $subPlanFeatures[] = $feature->feature_id;
                }
                // $returnHTML = view('backend.subscriptions.edit-subscriptionPlanUser')->with(['features'=>$featuresList, 'plan' => $plan, 'subPlanFeatures'=>$subPlanFeatures])->render();
                // return response()->json(array('success' => true, 'html'=>$returnHTML));
                return response()->json(["status"=>"Success", "data"=>['features'=>$featuresList, 'plan' => $plan, 'subPlanFeatures'=>$subPlanFeatures]]);
            }
            else{
                return $this->errorResponse('Invalid Data', 400);
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
            $validator = Validator::make($request->all(), [
                'status' => 'required'
            ]);
            if($validator->fails()){
                foreach($validator->errors()->toArray() as $error_key => $error_value){
                    return $this->errorResponse($error_value[0], 400);
                }
            }
            DB::beginTransaction();
            $subscription = SubscriptionPlansUser::where('slug', $slug)->first();
            if($subscription){
                $subscription->status = $request->status;
                $subscription->save();
                DB::commit();
                return $this->successResponse('', 'Subscription status has been updated.');
            }else{
                return $this->errorResponse('Invalid data', 400);    
            }
        }
        catch(\Exception $ex){
            DB::rollback();
            return $this->errorResponse($ex->getMessage(), 400);
        }
    }

    /**
     * update user subscription
     * Required Params-
     *  slug (Subscription plan)
     */
    public function deleteSubscriptionPlan(Request $request, $slug='')
    {
        try {
            DB::beginTransaction();
            $subscription = SubscriptionPlansUser::where('slug', $slug)->first();
            if($subscription){
                $subscription->delete();
                DB::commit();
                return $this->successResponse('', 'Subscription has been deleted successfully.');
            }else{
                return $this->errorResponse('Invalid data', 400);    
            }
        } catch (\Exception $e) {
            DB::rollback();
            return $this->errorResponse('Subscription cannot be deleted.', 400);
        }
    }
}
