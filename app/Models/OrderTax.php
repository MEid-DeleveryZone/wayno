<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderTax extends Model
{
    use HasFactory;

    public function category()
    {
        return $this->hasOne('App\Models\TaxCategory' , 'id', 'tax_category_id'); 
    }

    public function order(){
	    return $this->hasOne('App\Models\Order' , 'id', 'order_id'); 
	}
}
