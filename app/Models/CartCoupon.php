<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartCoupon extends Model
{
    use HasFactory;

    public function promo(){
      return $this->belongsTo('App\Models\Promocode', 'coupon_id', 'id')->select('id', 'name', 'amount', 'promo_type_id', 'expiry_date', 'allow_free_delivery', 'minimum_spend', 'maximum_spend', 'first_order_only', 'limit_total', 'restriction_on');
    }

    public static function getDetails($vendor_id, $user_id){
    	$cart_coupon_detail = CartCoupon::with('promo')->where('vendor_id', $vendor_id)->first();
    	if($cart_coupon_detail){
    		if($cart_coupon_detail->promo){
    			if($cart_coupon_detail->promo->first_order_only == 1){

    			}else{
    				
    			}
    		}
    	}
    }

}
