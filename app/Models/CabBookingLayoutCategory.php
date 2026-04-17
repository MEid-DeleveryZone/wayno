<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CabBookingLayoutCategory extends Model
{
    use HasFactory;
    protected $table = 'cab_booking_layout_categories';

    public function categoryDetail(){
	    return $this->belongsTo('App\Models\Category', 'category_id', 'id'); 
	}
}
