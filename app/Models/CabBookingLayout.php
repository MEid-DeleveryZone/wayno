<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CabBookingLayout extends Model
{
    use HasFactory;

    protected $fillable = ['title','slug','order_by','is_active','image'];


    public function translations(){
        $langData = $this->hasMany('App\Models\CabBookingLayoutTranslation');
        return $langData;
    }


    public function pickupCategories(){
        return $this->hasMany('App\Models\CabBookingLayoutCategory')->whereHas('categoryDetail',function($q){$q->where('deleted_at',null);});
       
    }

    public function getImageAttribute($value)
    {
      $values = array();
      $img = 'images/CabBANNER.jpg';
      if(!empty($value)){
        $img = $value;
      }else{
        return $value;
      }
      $values['proxy_url'] = \Config::get('app.IMG_URL1');
      $values['image_path'] = \Config::get('app.IMG_URL2').'/'.\Storage::disk('s3')->url($img);
      $values['image_fit'] = \Config::get('app.FIT_URl');

      return $values;
    }
}
