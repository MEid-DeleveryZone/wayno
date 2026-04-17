<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model{

    use HasFactory;
    protected $casts = ['total_amount' => 'float'];

    public function products(){
	    return $this->hasMany('App\Models\OrderProduct' , 'order_id', 'id'); 
	}
	public function vendors(){
	    return $this->hasMany('App\Models\OrderVendor' , 'order_id', 'id'); 
	}
	public function user(){
	    return $this->hasOne('App\Models\User' , 'id', 'user_id'); 
	}
	public function address(){
	    return $this->hasOne('App\Models\UserAddress' , 'id', 'address_id'); 
	}
	public function paymentOption(){
	    return $this->hasOne('App\Models\PaymentOption' , 'id', 'payment_option_id'); 
	}
	public function orderStatusVendor(){
        return $this->hasMany('App\Models\VendorOrderStatus', 'order_id', 'id');
    }
	public function scopeBetween($query, $from, $to){
        $query->whereBetween('created_at', [$from, $to]);
    }
	public function payment(){
	    return $this->hasOne('App\Models\Payment' , 'order_id', 'id'); 
	}
	public function loyaltyCard(){
	    return $this->hasOne('App\Models\LoyaltyCard' , 'id', 'loyalty_membership_id'); 
	}
	public function taxes(){
	    return $this->hasMany('App\Models\OrderTax' , 'order_id', 'id' )->latest();
	}
	public function prescription(){
	    return $this->hasMany('App\Models\OrderProductPrescription' , 'order_id', 'id'); 
	}
	public function user_vendor(){
		return $this->hasManyThrough(
            'App\Models\UserVendor',
            'App\Models\OrderVendor',
            'order_id', // Foreign key on the environments table...
            'vendor_id', // Foreign key on the deployments table...
            'id', // Local key on the projects table...
            'vendor_id' // Local key on the environments table...
        );
	}

	
	public function getTotalDiscountCalculateAttribute(){
		return $this->vendors()->sum('discount_amount');
	}
}
