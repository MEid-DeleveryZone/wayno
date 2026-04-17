<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
//use Laravel\Scout\Searchable;

class Category_translation extends Model
{
    protected $table='category_translations';
   	protected $fillable = ['language_id', 'name', 'category_id', 'order_detail_placeholder', 'vendor_heading', 'product_heading'];


       
}
