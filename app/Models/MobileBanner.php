<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MobileBanner extends Model
{
    protected $fillable = ['name', 'language_id', 'link', 'image', 'validity_on', 'sorting', 'status', 'start_date_time', 'end_date_time', 'redirect_category_id', 'redirect_vendor_id' ];

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

      return $values;
    }


    public function category(){
      return $this->hasOne('App\Models\Category', 'id', 'redirect_category_id'); 
    }
    public function vendor(){
      return $this->hasOne('App\Models\Vendor', 'id', 'redirect_vendor_id'); 
    }

    public function language(){
      return $this->belongsTo('App\Models\Language', 'language_id', 'id')->select('id', 'name', 'sort_code', 'nativeName', 'is_rtl');
    }
}