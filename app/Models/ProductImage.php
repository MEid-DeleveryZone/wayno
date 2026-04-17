<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $fillable = ['product_id','media_id','is_default'];

    public function variantImage(){
       return $this->hasMany('App\Models\ProductVariantImage')->select('product_variant_id', 'product_image_id'); 
    }

    public function image(){
	    return $this->belongsTo('App\Models\VendorMedia','media_id','id')->select('id' ,'media_type', 'path'); 
	}
}
