<?php

namespace App\Http\Controllers\Api\v1;

use DB;
use Validation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\v1\BaseController;
use App\Models\{User, Product, Cart, CartProduct, CartCoupon, Promocode, PromocodeRestriction};

class CouponController extends BaseController
{
    private $field_status = 2;

    public function list(Request $request, $cartId = 0){
        $user = User::where('status', '!=', '2');
        if (Auth::user() && Auth::user()->id > 0) {
            $user = $user->where('id', Auth::user()->id);
        }else{
            if(empty(Auth::user()->system_user)){
                return response()->json(['error' => 'System id should not be empty.'], 404);
            }
            $user = $user->where('system_id', Auth::user()->system_user);
        }
        $user = $user = $user->first();
        if(!$user){
            return response()->json(['error' => 'User not found'], 404);
        }
        $cart = Cart::with('cartvendor')->select('id', 'is_gift', 'item_count')
                    ->where('status', '0')
                    ->where('user_id', $user->id)->first();
        $promocode = Promocode::with('type', 'restriction')->where('is_deleted', '0')->whereDate('expiry_date', '>=', Carbon::now())->get();
    }

    public function apply(Request $request){
        
    }

    public function remove(Request $request){

    }
}