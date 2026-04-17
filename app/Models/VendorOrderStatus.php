<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorOrderStatus extends Model
{
    use HasFactory;

    protected $fillable = [
       'order_id','order_vendor_id', 'order_status_option_id', 'vendor_id'
    ];

    public function OrderStatusOption(){
       return $this->hasOne('App\Models\OrderStatusOption', 'id', 'order_status_option_id'); 
    }

    public function vendor(){
        return $this->belongsTo('App\Models\Vendor', 'vendor_id', 'id')->select('id', 'name', 'desc', 'logo', 'banner', 'order_pre_time', 'auto_reject_time', 'order_min_amount');
    }
}
