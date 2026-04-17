<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AddonSet extends Model
{
	protected $fillable = ['title','min_select','max_select','position','status','is_core','vendor_id'];
	
    public function translation(){
	    return $this->hasMany('App\Models\AddonSetTranslation' , 'addon_id', 'id')
	    ->join('client_languages', 'addon_set_translations.language_id', 'client_languages.language_id')
	    ->select('addon_set_translations.title', 'addon_set_translations.addon_id', 'addon_set_translations.language_id')->where('client_languages.is_active', 1); 
	}

	  public function primary(){
	    return $this->hasOne('App\Models\AddonSetTranslation' , 'addon_id', 'id')->select('title', 'addon_id', 'language_id')->join('client_languages', 'addon_set_translations.language_id', 'client_languages.language_id')->where('client_languages.is_primary', 1);
	  }

	  public function option(){
	    return $this->hasMany('App\Models\AddonOption', 'addon_id', 'id')->select('id', 'title', 'addon_id', 'position', 'price'); 
	  }

	public function translation_one()
	{
		return $this->hasOne('App\Models\AddonSetTranslation', 'addon_id', 'id');
	}
}
