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
use App\Models\{Client, ClientPreference, SmsProvider, Currency, Language, Country, User, SubscriptionPlansUser, SubscriptionPlanFeaturesUser,  SubscriptionFeaturesListUser, SubscriptionInvoicesUser};
use Carbon\Carbon;

class SubscriptionPlansUserController extends BaseController
{
    use ApiResponser;
    private $folderName = '/subscriptions/image';
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        //
    }

    /**
     * Get user subscriptions
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getSubscriptionPlans(Request $request, $domain = '')
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
        return view('backend/subscriptions/subscriptionPlansUser')->with(['features'=>$featuresList, 'subscription_plans'=>$sub_plans, 'subscribed_users_count'=>$subscribed_users_count, 'subscribed_users_percentage'=>$subscribed_users_percentage]);
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
            $plan = SubscriptionPlansUser::where('slug', $slug)->firstOrFail();
            $rules['title'] = $rules['title'].',id,'.$plan->id;
            $message = 'updated';
        }

        $validation  = Validator::make($request->all(), $rules);
        if ($validation->fails()) {
            return redirect()->back()->withInput()->withErrors($validation);
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
        $plan = SubscriptionPlansUser::where('slug', $slug)->firstOrFail();
        $planFeatures = SubscriptionPlanFeaturesUser::select('feature_id')->where('subscription_plan_id', $plan->id)->get();
        $featuresList = SubscriptionFeaturesListUser::where('status', 1)->get();
        $subPlanFeatures = array();
        foreach($planFeatures as $feature){
            $subPlanFeatures[] = $feature->feature_id;
        }
        $returnHTML = view('backend.subscriptions.edit-subscriptionPlanUser')->with(['features'=>$featuresList, 'plan' => $plan, 'subPlanFeatures'=>$subPlanFeatures])->render();
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
        $subscription = SubscriptionPlansUser::where('slug', $slug)->firstOrFail();
        $subscription->status = $request->status;
        $subscription->save();
        return response()->json(array('success' => true, 'message'=>'Subscription status has been updated.'));
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
            $subscription = SubscriptionPlansUser::where('slug', $slug)->firstOrFail();
            $subscription->delete();
            return redirect()->back()->with('success', 'Subscription has been deleted successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Subscription cannot be deleted.');
        }
    }
}
