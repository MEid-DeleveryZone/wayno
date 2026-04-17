<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCrossSell extends Model
{
    public function detail(){
       return $this->belongsTo('App\Models\Product', 'cross_product_id', 'id')->select('id', 'sku');
    }

    public function translation(){
       return $this->hasMany('App\Models\ProductTranslation', 'product_id', 'cross_product_id')->select('product_id', 'title', 'body_html');
    }

    public function variant(){
      return $this->hasMany('App\Models\ProductVariant', 'product_id', 'cross_product_id')->select('id', 'product_id', 'quantity', 'price')->limit(1); 
    }
}
