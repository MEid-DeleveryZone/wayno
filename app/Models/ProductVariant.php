<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;
use Session;

class ProductVariant extends Model
{
    protected $fillable = ['sku', 'product_id', 'title', 'quantity', 'price', 'position', 'compare_at_price', 'cost_price', 'barcode', 'currency_id', 'tax_category_id', 'inventory_policy', 'fulfillment_service', 'inventory_management', 'status'];

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
    public function set()
    {
        return $this->hasMany('App\Models\ProductVariantSet')
            ->join('variant_options as opt', 'opt.id', 'product_variant_sets.variant_option_id')
            ->join('variants as vari', 'vari.id', 'opt.variant_id')
            ->select('product_variant_sets.product_variant_id', 'product_variant_sets.variant_option_id', 'opt.title', 'opt.hexacode', 'vari.type', 'vari.id');
    }

    public function vimageall()
    {
        return $this->hasOne('App\Models\ProductVariantImage', 'product_variant_id', 'id')
            ->select('product_variant_id', 'product_image_id')->groupBy('product_variant_id');
    }

    public function vimage()
    {
        return $this->hasOne('App\Models\ProductVariantImage', 'product_variant_id', 'id')
            ->select('product_variant_id', 'product_image_id')->groupBy('product_variant_id');
    }

    public function media()
    {
        return $this->hasMany('App\Models\ProductVariantImage', 'product_variant_id', 'id')->select('product_variant_id', 'product_image_id');
    }

    public function vset()
    {
        return $this->hasMany('App\Models\ProductVariantSet')->select('product_variant_id', 'variant_option_id', 'product_id', 'variant_type_id');
    }

    public function translation($langId = 0)
    {
        return $this->hasMany('App\Models\ProductTranslation', 'product_id', 'product_id');
    }
    public function translation_one($langId = 0)
    {
        return $this->hasOne('App\Models\ProductTranslation', 'product_id', 'product_id');
    }
    public function optionData()
    {
        return $this->belongsTo('App\Models\VariantOption', 'variant_option_id', 'id');
    }
    public function tax()
    {
        return $this->belongsTo('App\Models\TaxCategory', 'tax_category_id', 'id')->select('id', 'title', 'code');
    }
    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'product_id', 'id')->select('id', 'sku', 'title', 'averageRating', 'inquiry_only', 'vendor_id');
    }
    public function wishlist()
    {
        return $this->hasOne('App\Models\UserWishlist', 'product_id', 'product_id')->select('product_id', 'user_id');
    }

    public function checkIfInCart()
    {
        $user = Auth::user();
        if ($user) {
            $column = 'user_id';
            $value = $user->id;
        } else {
            $column = 'unique_identifier';
            $value = session()->get('_token');
        }

        return $this->hasMany('App\Models\CartProduct', 'variant_id', 'id')->whereHas('cart', function ($qset) use ($column, $value) {
            $qset->where($column, $value);
        });;
    }

    public function checkIfInCartApp()
    {
        $user = Auth::user();
        if ($user->id && $user->id > 0) {
            $column = 'user_id';
            $value = $user->id;
        } else {
            $column = 'unique_identifier';
            $value = $user->system_user;
        }

        return $this->hasMany('App\Models\CartProduct', 'variant_id', 'id')->whereHas('cart', function ($qset) use ($column, $value) {
            $qset->where($column, $value);
            $qset->where('paid', '!=', 1); // To hide the quantity box in case payment hook error
        });
    }
}
