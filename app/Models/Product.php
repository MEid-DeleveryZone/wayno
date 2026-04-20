<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Product extends Model
{
   use SoftDeletes;

   protected $fillable = ['sku', 'title', 'url_slug', 'description', 'body_html', 'vendor_id', 'category_id', 'type_id', 'country_origin_id', 'is_new', 'is_featured', 'is_live', 'is_physical', 'weight', 'weight_unit', 'has_inventory', 'sell_when_out_of_stock', 'requires_shipping', 'Requires_last_mile', 'publish_at', 'inquiry_only', 'has_variant', 'averageRating', 'tags', 'pharmacy_check', 'deleted_at', 'celebrity_id', 'brand_id', 'tax_category_id', 'need_price_from_dispatcher', 'mode_of_service', 'returnable', 'same_emirate_frequency', 'diff_emirate_frequency', 'distance_sla_group_id'];

   protected $appends = ['vendor_name', 'isReturnable'];

   public function addOn()
   {
      return $this->hasMany('App\Models\ProductAddon')->select('product_id', 'addon_id');
   }

   public function sets()
   {
      return $this->hasMany('App\Models\ProductAddon')->join('addon_set_translations as ast', 'ast.addon_id', 'product_addons.addon_id')->select('product_addons.product_id', 'ast.title', 'product_addons.addon_id');
   }

   public function brand()
   {
      return $this->belongsTo('App\Models\Brand')->select('id', 'title', 'image');
   }

   public function vendor()
   {
      return $this->belongsTo('App\Models\Vendor')->select('id', 'slug', 'name', 'desc', 'logo', 'show_slot', 'status');
   }

   public function related()
   {
      return $this->hasMany('App\Models\ProductRelated')->select('product_id', 'related_product_id');
   }

   public function celebrities()
   {
      return $this->hasMany('App\Models\ProductCelebrity')->select('product_id', 'celebrity_id');
   }

   public function upSell()
   {
      return $this->hasMany('App\Models\ProductUpSell')->select('product_id', 'upsell_product_id');
   }

   public function crossSell()
   {
      return $this->hasMany('App\Models\ProductCrossSell')->select('product_id', 'cross_product_id');
   }

   public function variant()
   {
      return $this->hasMany('App\Models\ProductVariant')->select('id', 'sku', 'product_id', 'title', 'quantity', 'price', 'position', 'compare_at_price', 'barcode', 'cost_price', 'currency_id', 'tax_category_id')->where('status', 1);
   }

   public function translation($langId = 0)
   {
      if ($langId > 0) {
         return $this->hasMany('App\Models\ProductTranslation')->where('language_id', $langId);
      } else {
         return $this->hasMany('App\Models\ProductTranslation');
      }
   }
   public function translation_one($langId = 0)
   {
      return $this->hasOne('App\Models\ProductTranslation');
   }
   public function primary()
   {

      $langData = $this->hasOne('App\Models\ProductTranslation')->join('client_languages as cl', 'cl.language_id', 'product_translations.language_id')->select('product_translations.product_id', 'product_translations.title', 'product_translations.language_id', 'product_translations.body_html', 'product_translations.dimensions', 'product_translations.weight_description', 'product_translations.meta_title', 'product_translations.meta_keyword', 'product_translations.meta_description')->where('cl.is_primary', 1);

      return $langData;
   }

   public function category()
   {
      return $this->hasOne('App\Models\ProductCategory')->select('product_id', 'category_id');
   }

   public function variantSet()
   {
      return $this->hasMany('App\Models\ProductVariantSet')->select('product_id', 'product_variant_id', 'variant_type_id', 'variant_option_id')->groupBy('variant_type_id')->orderBy('product_variant_id');
   }

   public function vatoptions()
   {
      return $this->hasMany('App\Models\ProductVariantSet')->select('product_id', 'product_variant_id', 'variant_option_id')->groupBy('variant_option_id');
   }

   public function variantSets()
   {
      return $this->hasMany('App\Models\ProductVariantSet');
   }

   public function media()
   {
      return $this->hasMany('App\Models\ProductImage')->select('product_id', 'media_id', 'is_default');
   }

   public function pimage()
   {
      return $this->hasMany('App\Models\ProductImage')->select('product_images.product_id', 'product_images.media_id', 'product_images.is_default', 'vendor_media.media_type', 'vendor_media.path')->join('vendor_media', 'vendor_media.id', 'product_images.media_id')->limit(1);
   }

   public function baseprice()
   {
      return $this->hasMany('App\Models\ProductVariant')->select('id', 'product_id', 'price')->groupBy('product_id');
   }

   /* for app */

   public function variants()
   {
      return $this->hasMany('App\Models\ProductVariant')->select('id', 'sku', 'product_id', 'quantity', 'price', 'barcode');
   }

   public function variant_list()
   {
      return $this->hasMany('App\Models\ProductVariantSet')
         ->join('variants as pv', 'pv.id', 'product_variant_sets.variant_type_id')
         ->select('product_id', 'title', 'type', 'position', 'status')
         ->groupBy('product_variant_sets.variant_type_id')
         ->orderBy('pv.position', 'asc');
   }

   public function variant1()
   {
      return $this->hasMany('App\Models\ProductVariant', 'product_id', 'pro_id')->select('id', 'sku', 'product_id');
   }

   public function inwishlist()
   {
      return $this->hasOne('App\Models\UserWishlist')->select('product_id');
   }

   public function taxCategory()
   {
      return $this->belongsTo('App\Models\TaxCategory', 'tax_category_id', 'id')->select('id', 'title', 'code');
   }

   public function distanceSlaGroup()
   {
      return $this->belongsTo(DistanceSlaGroup::class, 'distance_sla_group_id')
         ->withDefault(function () {
            return DistanceSlaGroup::where('is_default', true)->first() ?? new DistanceSlaGroup();
         });
   }

   public function getVendorNameAttribute()
   {
      return $this->vendor->name ?? '';
   }

   public function getIsReturnableAttribute()
   {
      return $this->returnable;
   }

   public function pimages()
   {
      return $this->hasMany('App\Models\ProductImage')->select('product_images.product_id', 'product_images.media_id', 'product_images.is_default', 'vendor_media.media_type', 'vendor_media.path')->join('vendor_media', 'vendor_media.id', 'product_images.media_id');
   }
}
