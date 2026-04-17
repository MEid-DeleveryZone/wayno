<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Celebrity extends Model
{
  protected $fillable = ['name', 'email', 'avatar', 'phone_number', 'address', 'status', 'country_id', 'description'];

    public function getAvatarAttribute($value)
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

    public function brands()
    {
        return $this->belongsToMany(Brand::class, 'celebrity_brands');
    }

    public function products(){
      return $this->hasMany('App\Models\ProductCelebrity','celebrity_id', 'id'); 
    }

    public function country()
    {
        return $this->belongsTo(Country::class)->select('id', 'code', 'name', 'nicename');
    }
}