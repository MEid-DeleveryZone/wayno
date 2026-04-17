<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserWishlist extends Model{
    use HasFactory;
    public function product(){
      return $this->belongsTo('App\Models\Product', 'product_id', 'id')->select('id', 'sku', 'requires_shipping', 'sell_when_out_of_stock', 'url_slug', 'weight_unit', 'weight', 'brand_id', 'has_variant', 'has_inventory', 'Requires_last_mile', 'averageRating', 'deleted_at')->withTrashed()->where('is_live', 1); 
    }
}
