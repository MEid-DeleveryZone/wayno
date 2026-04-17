<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartProduct extends Model{

    use HasFactory;

    protected $fillable = ['cart_id','product_id', 'vendor_id', 'vendor_dinein_table_id', 'quantity', 'status', 'variant_id', 'is_tax_applied', 'tax_rate_id', 'currency_id', 'tax_category_id', 'luxury_option_id','schedule_type','scheduled_date_time'];

    protected $touches = ['cart'];

    public function cart(){
        return $this->belongsTo('App\Models\Cart');
    }

    public function addon(){
       return $this->hasMany('App\Models\CartAddon', 'cart_product_id', 'id')->select('cart_product_id', 'addon_id', 'option_id'); 
    }


 	public function product(){
    	return $this->belongsTo('App\Models\Product')->select('id','title', 'sku', 'url_slug', 'is_live', 'weight', 'weight_unit', 'averageRating', 'brand_id', 'tax_category_id','Requires_last_mile','pharmacy_check','tags','mode_of_service');
    }

    public function vendor(){
        return $this->belongsTo('App\Models\Vendor', 'vendor_id', 'id')->select('id', 'name', 'desc', 'logo', 'banner', 'latitude', 'longitude', 'order_pre_time', 'auto_reject_time', 'order_min_amount', 'show_slot');
    }

    public function pvariant(){
    	return $this->belongsTo('App\Models\ProductVariant', 'variant_id', 'id')->select('id', 'sku', 'product_id', 'title', 'price', 'tax_category_id', 'barcode');
    }

    public function coupon(){
      return $this->hasOne('App\Models\CartCoupon', 'vendor_id', 'vendor_id')->select("cart_id", "coupon_id", 'vendor_id');
    }

    public function getAppliedCoupon(){
      return $this->hasOne('App\Models\CartCoupon', 'cart_id', 'cart_id')->select("cart_id", "coupon_id", 'vendor_id');
    }

    public function currency(){
      return $this->hasOne('App\Models\Currency', 'vendor_id', 'vendor_id');
    }
    
    public function vendorProducts(){
      return $this->hasMany(CartProduct::class, 'vendor_id', 'vendor_id')->leftjoin('client_currencies as cc', 'cc.currency_id', 'cart_products.currency_id')->select('cart_products.id', 'cart_products.cart_id', 'cart_products.product_id', 'cart_products.quantity', 'cart_products.variant_id', 'cart_products.is_tax_applied', 'cart_products.tax_category_id', 'cart_products.currency_id', 'cc.doller_compare', 'cart_products.vendor_id', 'cart_products.scheduled_date_time')->orderBy('cart_products.created_at', 'asc')->orderBy('cart_products.vendor_id', 'asc');
    }
}