<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ProductVariantSet extends Model
{
    protected $fillable = ['product_id','product_variant_id','variant_type_id','variant_option_id'];

	public function variantDetail() {
	    return $this->belongsTo('App\Models\Variant', 'variant_type_id', 'id');
	}
	
	public function options() {
	    return $this->hasMany('App\Models\VariantOption', 'variant_id', 'variant_type_id')
	    		->join('product_variant_sets as pvs', 'pvs.variant_option_id', 'variant_options.id')
	    		->groupBy('pvs.variant_option_id')->orderBy('pvs.product_variant_id');
	}

	public function option2() {
	    return $this->hasMany('App\Models\ProductVariantSet', 'variant_type_id', 'variant_type_id')
    		->join('variant_options as pvs', 'product_variant_sets.variant_option_id', 'pvs.id')
    		->join('variant_option_translations as vt','vt.variant_option_id','pvs.id')
    		->select('pvs.hexacode', 'vt.title', 'product_variant_sets.product_id', 'product_variant_sets.variant_type_id', 'product_variant_sets.variant_option_id', 'product_variant_sets.product_variant_id')
    		->groupBy('product_variant_sets.variant_option_id');
	}

	public function optionData() {
	    return $this->belongsTo('App\Models\VariantOption', 'variant_option_id', 'id');
	}
}
