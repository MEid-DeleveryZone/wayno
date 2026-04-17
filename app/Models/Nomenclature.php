<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nomenclature extends Model
{
    use HasFactory;
    protected $fillable = ['label', 'value'];
    public function primary(){
      $langData = $this->hasOne('App\Models\NomenclatureTranslation')->join('client_languages as cl', 'cl.language_id', 'nomenclatures_translations.language_id')->where('cl.is_primary', session()->get('customerLanguage'));
      return $langData;
    }
    public function translations(){
      $langData = $this->hasMany('App\Models\NomenclatureTranslation');
      return $langData;
    }
}
