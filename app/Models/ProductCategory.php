<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
	protected $fillable = ['category_id','product_id'];

	public function product(){
	    return $this->hasOne('App\Models\Product', 'id', 'product_id'); 
	}
    public function cat(){
	    return $this->hasOne('App\Models\CategoryTranslation', 'category_id', 'category_id')->select('id', 'name', 'category_id'); 
	}
	public function categoryDetail(){
	    return $this->belongsTo('App\Models\Category', 'category_id', 'id'); 
	}
}
