<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
  use HasFactory;

  protected $fillable = ['unique_identifier', 'status', 'is_gift', 'item_count', 'currency_id', 'user_id', 'created_by', 'schedule_type', 'scheduled_date_time', 'paid', 'use_wallet'];

  public function cartProducts()
  {
    return $this->hasMany('App\Models\CartProduct')->leftjoin('client_currencies as cc', 'cc.currency_id', 'cart_products.currency_id')->select('cart_products.id', 'cart_products.cart_id', 'cart_products.product_id', 'cart_products.quantity', 'cart_products.variant_id', 'cart_products.is_tax_applied', 'cart_products.tax_rate_id', 'cart_products.currency_id', 'cc.doller_compare', 'cart_products.vendor_id')->orderBy('cart_products.created_at', 'asc')->orderBy('cart_products.vendor_id', 'asc');
  }

  public function coupon()
  {
    return $this->hasOne('App\Models\CartCoupon')->select("cart_id", "coupon_id", "vendor_id");
  }

  public function product()
  {
    return $this->belongsTo('App\Models\Product');
  }

  public function variant()
  {
    return $this->belongsTo('App\Models\ProductVariant');
  }

  public function cartvendor()
  {
    return $this->hasMany('App\Models\CartProduct')->select('cart_id', 'vendor_id');
  }
}
