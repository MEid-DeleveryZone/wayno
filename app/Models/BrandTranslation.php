<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
//use Laravel\Scout\Searchable;

class BrandTranslation extends Model
{
	protected $fillable = ['title','brand_id','language_id'];
	//use Searchable;
	// public function toSearchableArray()
	// {
	//   $array = $this->toArray();
	     
	//   return array('id' => $array['id'], 'brand_id' => $array['brand_id'], 'title' => $array['title']);
	// }

	// public function searchableAs()
	// {
	//     return 'brand_translations_index';
	// }
    //
}
