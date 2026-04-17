<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DispatcherStatusOption extends Model
{
    use HasFactory;


    public function vendorOrderDispatcherStatus(){
	    return $this->hasOne('App\Models\VendorOrderDispatcherStatus' , 'dispatcher_status_option_id', 'id'); 
	}
}
