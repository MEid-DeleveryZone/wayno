<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductAddon extends Model
{
   protected $fillable = ['product_id','addon_id'];

    public function addOn(){
       return $this->belongsTo('App\Models\AddonSet', 'id', 'addon_id')->select('id', 'title', 'min_select', 'max_select', 'position'); 
    }

    public function setoptions(){
       return $this->hasMany('App\Models\AddonOption', 'addon_id', 'addon_id')->select('id', 'addon_id', 'title', 'price', 'position')->orderBy('id', 'asc'); 
    }


    public function addOnName(){
      return $this->belongsTo('App\Models\AddonSet', 'addon_id', 'id')->select('id', 'title', 'min_select', 'max_select', 'position'); 
   }
    

}