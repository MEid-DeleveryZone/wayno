<?php

namespace App\Http\Controllers\Api\v1;

use DB;
use Validation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Traits\ApiResponser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\v1\BaseController;
use App\Models\{Client, ClientCurrency, ClientPreference, LoyaltyCard, Order};
use Dotenv\Loader\Loader;

class LoyaltyController extends BaseController
{
    use ApiResponser;
    /**
     * Display a loyalty points to user.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            $user = Auth::user();
            $loyalty_points_earned = $loyalty_points_used = 0;
            $order_loyalty_points_earned_detail = Order::where('user_id', $user->id)->select(DB::raw('sum(loyalty_points_earned) AS sum_of_loyalty_points_earned'), DB::raw('sum(loyalty_points_used) AS sum_of_loyalty_points_used'))->first();
            if ($order_loyalty_points_earned_detail) {
                $loyalty_points_earned = (!empty($order_loyalty_points_earned_detail->sum_of_loyalty_points_earned)) ? $order_loyalty_points_earned_detail->sum_of_loyalty_points_earned : 0;
                $loyalty_points_used = (!empty($order_loyalty_points_earned_detail->sum_of_loyalty_points_used)) ? $order_loyalty_points_earned_detail->sum_of_loyalty_points_used : 0;
            }
            $current_loyalty = LoyaltyCard::select('name', 'image')->where('minimum_points', '<=', $loyalty_points_earned)->orderBy('minimum_points', 'desc')->first();
            $upcoming_loyalty = LoyaltyCard::select('name', 'image', 'minimum_points')->where('minimum_points', '>', $loyalty_points_earned)->get();
            if($upcoming_loyalty){
                foreach($upcoming_loyalty as $loyalty){
                    $loyalty->points_to_reach = number_format(($loyalty->minimum_points - $loyalty_points_earned), 2, '.', '');
                }
            }
            return response()->json(["status"=>"Success", "data"=>['current_loyalty'=> $current_loyalty, 'loyalty_points_earned'=>$loyalty_points_earned, 'loyalty_points_used'=>$loyalty_points_used, 'upcoming_loyalty'=>$upcoming_loyalty]]);
        }
        catch(\Exception $ex){
            return $this->errorResponse($ex->getMessage(), 400);
        }
    }
}
