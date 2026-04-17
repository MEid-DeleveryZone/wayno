<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SlotDay extends Model
{
    protected $fillable = ['slot_id','day'];

    public function vendor_slot(){
	    return $this->belongsTo('App\Models\VendorSlot','slot_id','id'); 
	}
}
