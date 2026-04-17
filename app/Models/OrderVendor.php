<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderVendor extends Model{
    use HasFactory;
    
	protected $fillable = ['web_hook_code','payment_option_id'];
	public function orderDetail(){
	    return $this->hasOne('App\Models\Order' , 'id', 'order_id'); 
	}
    public function vendor(){
	    return $this->hasOne('App\Models\Vendor' , 'id', 'vendor_id'); 
	}
	public function user(){
	    return $this->hasOne('App\Models\User' , 'id', 'user_id'); 
	}
    public function products(){
	    return $this->hasMany('App\Models\OrderProduct' , 'order_vendor_id', 'id'); 
	}
	public function payment(){
	    return $this->hasOne('App\Models\Payment' , 'order_id', 'order_id'); 
	}
	public function coupon(){
	    return $this->hasOne('App\Models\Promocode' , 'id', 'coupon_id'); 
	}
	public function status(){
	    return $this->hasOne('App\Models\VendorOrderStatus','order_vendor_id','id')->orderBy('id', "DESC"); 
	}
	public function orderstatus(){
	    return $this->hasOne('App\Models\VendorOrderStatus' , 'vendor_id', 'vendor_id', 'order_id', 'order_id')->orderBy('id', 'DESC')->latest(); 
	}
	public function scopeBetween($query, $from, $to){
        $query->whereBetween('created_at', [$from, $to]);
    }

	# get dispatcher status title 
	public function getDispatcherStatusAttribute($value)
    {
		$title = DispatcherStatusOption::where('id',$value)->value('title');
        return ucfirst($title);
    }

	public function allStatus(){
	    return $this->hasMany('App\Models\VendorOrderStatus','order_vendor_id','id'); 
	}
	public function rejectingReason(){
	    return $this->hasOne('App\Models\OrderRejectingReason','id','reject_reason'); 
	}

	public function dineInTable(){
	    return $this->belongsTo('App\Models\VendorDineinTable' , 'vendor_dinein_table_id', 'id'); 
	}
}
