<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderProduct extends Model
{
  use HasFactory;

  protected $table = 'order_vendor_products';
  protected $casts = ['price' => 'double'];
  protected $appends = ['isReturnable'];
  public function vendor()
  {
    return $this->belongsTo('App\Models\Vendor', 'vendor_id', 'id')->select('id', 'name', 'desc', 'logo', 'banner', 'order_pre_time', 'auto_reject_time', 'order_min_amount');
  }
  public function coupon()
  {
    return $this->hasOne('App\Models\CartCoupon', 'vendor_id', 'vendor_id')->select("cart_id", "coupon_id", 'vendor_id');
  }
  public function addon()
  {
    return $this->hasMany('App\Models\OrderProductAddon', 'order_product_id', 'id');
  }
  public function product()
  {
    return $this->belongsTo('App\Models\Product')->select('id', 'sku', 'url_slug', 'is_live', 'weight', 'weight_unit', 'averageRating', 'brand_id', 'tax_category_id', 'category_id', 'returnable');
  }

  public function category()
  {
    return $this->hasOne('App\Models\ProductCategory', 'product_id', 'product_id');
  }
  public function variant()
  {
    return $this->hasMany('App\Models\ProductVariant', 'product_id', 'product_id')->select('id', 'sku', 'product_id', 'title', 'quantity', 'price', 'position', 'compare_at_price', 'barcode', 'cost_price', 'currency_id', 'tax_category_id')->where('status', 1);
  }
  public function pvariant()
  {
    return $this->belongsTo('App\Models\ProductVariant', 'variant_id', 'id')->select('id', 'sku', 'product_id', 'title', 'price', 'tax_category_id', 'barcode');
  }
  public function media()
  {
    return $this->hasMany('App\Models\ProductImage', 'product_id', 'product_id')->select('product_id', 'media_id', 'is_default');
  }
  public function pimage()
  {
    return $this->hasMany('App\Models\ProductImage', 'order_product_id', 'order_product_id')->select('product_images.product_id', 'product_images.media_id', 'product_images.is_default', 'vendor_media.media_type', 'vendor_media.path')->join('vendor_media', 'vendor_media.id', 'product_images.media_id')->limit(1);
  }
  public function vendorProducts()
  {
    return $this->hasMany(OrderProduct::class, 'vendor_id', 'vendor_id')->orderBy('order_vendor_products.created_at', 'asc')->orderBy('order_vendor_products.vendor_id', 'asc');
  }
  public function translation()
  {
    return $this->hasOne('App\Models\ProductTranslation', 'product_id', 'product_id');
  }
  public function prescription()
  {
    return $this->hasMany('App\Models\OrderProductPrescription', 'product_id', 'product_id');
  }
  public function productRating()
  {
    return $this->hasOne('App\Models\OrderProductRating', 'order_vendor_product_id', 'id');
  }
  public function order()
  {
    return $this->belongsTo('App\Models\Order', 'order_id', 'id')->select('id', 'order_number', 'user_id');
  }
  public function productReturn()
  {
    return $this->hasMany('App\Models\OrderReturnRequest', 'order_vendor_product_id', 'id');
  }
  public function getIsReturnableAttribute()
  {
    return $this->belongsTo('App\Models\Product', 'product_id', 'id')->pluck('returnable')->first();
  }

  public function getImageAttribute($value)
  {
    $values = array();
    $img = 'default/default_image.png';
    if (!empty($value)) {
      $img = $value;
    }
    $values['proxy_url'] = \Config::get('app.IMG_URL1');
    $values['image_path'] = \Config::get('app.IMG_URL2') . '/' . \Storage::disk('s3')->url($img);
    $values['image_fit'] = \Config::get('app.FIT_URl');
    return $values;
  }
}
