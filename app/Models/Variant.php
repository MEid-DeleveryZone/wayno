<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Variant extends Model
{
  protected $fillable = ['title', 'type', 'position', 'status'];

  public function translation(){
    return $this->hasMany('App\Models\VariantTranslation')->join('languages', 'variant_translations.language_id', 'languages.id')->select('variant_translations.id', 'variant_translations.title', 'variant_translations.variant_id', 'variant_translations.language_id', 'languages.name');
  }

  public function translation_one(){
      $primary = ClientLanguage::orderBy('is_primary', 'desc')->first();
      if (isset($primary) && !empty($primary)) {
        $langset = $primary->language_id;
      } else {
        $langset = 1;
      }

      return $this->hasOne('App\Models\VariantTranslation')->select('variant_id', 'title')->where('language_id', $langset);
 }

  public function primary(){
    return $this->hasOne('App\Models\VariantTranslation')->join('client_languages as cl', 'cl.language_id', 'variant_translations.language_id')->where('cl.is_primary', 1); 
  }

  public function varcategory(){
    return $this->hasOne('App\Models\VariantCategory'); 
  }

  public function option(){
    return $this->hasMany('App\Models\VariantOption'); 
  }
  public function trans(){
       return $this->hasOne('App\Models\VariantTranslation')->select('title', 'variant_id'); 
  }
}
