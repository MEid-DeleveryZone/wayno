<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Brand extends Model
{
    protected $fillable = [
        'title', 'image','image_banner', 'position', 'status',
    ];
  	public function translation(){
  		return $this->hasMany('App\Models\BrandTranslation')->join('languages', 'brand_translations.language_id', 'languages.id');
  	}

    public function translation_one()
    {
        $primary = ClientLanguage::orderBy('is_primary', 'desc')->first();
        if (isset($primary) && !empty($primary)) {
          $langset = $primary->language_id;
        } else {
          $langset = 1;
        }

        return $this->hasOne('App\Models\BrandTranslation')->select('brand_id', 'title')->where('language_id', $langset);
    }
   

  	public function english(){
  		return $this->hasMany('App\Models\BrandTranslation')->where('language_id', 1); 
  	}

  	public function bc(){
  		return $this->hasMany('App\Models\BrandCategory');
  	}

    public function getImageAttribute($value)
    {
     
      $values = array();
      $img = 'default/default_image.png';
      if(!empty($value)){
        $img = $value;
      }
      $values['proxy_url'] = \Config::get('app.IMG_URL1');
      $values['image_path'] = \Config::get('app.IMG_URL2').'/'.\Storage::disk('s3')->url($img);
      $values['image_fit'] = \Config::get('app.FIT_URl');

      //$values['small'] = url('showImage/small/' . $img);
      return $values;
    }

    public function getImageBannerAttribute($value)
    {
      $values = array();
      $img = 'default/default_image.png';
      if(!empty($value)){
        $img = $value;
      }
      $values['proxy_url'] = \Config::get('app.IMG_URL1');
      $values['image_path'] = \Config::get('app.IMG_URL2').'/'.\Storage::disk('s3')->url($img);
      $values['image_fit'] = \Config::get('app.FIT_URl');

      //$values['small'] = url('showImage/small/' . $img);
      return $values;
    }

    public function products(){
       return $this->hasMany('App\Models\Product', 'brand_id', 'id'); 
    }
}
