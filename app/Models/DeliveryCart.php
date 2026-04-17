<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryCart extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function tasks(){
        return $this->hasMany('App\Models\DeliveryCartTasks');
    }
    public function product(){
        return $this->hasOne(Product::class,'id','product_id');
    }
    public function vendor(){
        return $this->hasOne(Vendor::class,'id','vendor_id');
    }
    public function category(){
        return $this->hasOne(Category::class,'id','category_id');
    }
    public function coupon(){
        return $this->hasOne(Promocode::class,'id','coupon_id');
    }
    public function images()
    {
        return $this->hasMany(DeliveryCartImage::class, 'delivery_cart_id');
    }
}
