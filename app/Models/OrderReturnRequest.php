<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderReturnRequest extends Model
{
    protected $table = 'order_return_requests';

    protected $fillable = [
      'order_vendor_product_id', 'order_id', 'return_by', 'reason', 'coments', 'status','reason_by_vendor','return_ref_id'
    ];


    public function returnFiles(){
        return $this->hasMany(OrderReturnRequestFile::class, 'order_return_request_id', 'id');
    }

    public function product(){
	    return $this->belongsTo(OrderProduct::class, 'order_vendor_product_id', 'id'); 
	  }
    public function order(){
	    return $this->belongsTo(Order::class, 'order_id', 'id'); 
	  }
    public function returnBy(){
	    return $this->belongsTo(User::class, 'return_by', 'id'); 
	  }
}
