<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VariantCategory extends Model
{
	protected $fillable = ['variant_id', 'category_id'];

    public function cate(){
       return $this->belongsTo('App\Models\Category', 'category_id', 'id')->select('id', 'slug', 'type_id'); 
    }
}
