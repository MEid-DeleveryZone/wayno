<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderReturnRequestFile extends Model
{
    protected $table = 'order_return_request_files';

    protected $fillable = [
       'order_return_request_id','file'
    ];



    public function getFileAttribute($value)
    {
      $values = array();
      $img = 'default/default_image.png';
      if(!empty($value)){
        $img = $value;
      }
      $values['proxy_url'] = \Config::get('app.IMG_URL1');
      $values['image_path'] = \Config::get('app.IMG_URL2').'/'.\Storage::disk('s3')->url($img);
      $values['image_fit'] = \Config::get('app.FIT_URl');
      $values['original'] = $value;

      return $values;
    }
}
