<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductInquiry extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'email', 'phone_number','company_name', 'message', 'product_id','vendor_id', 'product_variant_id'];

    public function product(){
        return $this->belongsTo('App\Models\Product')->select('id', 'sku');
      }
}
