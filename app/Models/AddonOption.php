<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AddonOption extends Model
{
    protected $fillable = ['title','addon_id','position','price'];

    public function translation(){
       return $this->hasMany('App\Models\AddonOptionTranslation', 'addon_opt_id', 'id')->join('languages', 'addon_option_translations.language_id', 'languages.id')->select('addon_option_translations.id', 'addon_option_translations.title', 'addon_option_translations.addon_opt_id', 'addon_option_translations.language_id', 'languages.sort_code', 'languages.name'); 
    }

    public function translation_one()
    {
        return $this->hasOne('App\Models\AddonOptionTranslation', 'addon_opt_id', 'id');
    }
}