<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorDocs extends Model{

    use HasFactory;
    public function getfileNameAttribute($value){
      $values = array();
      if(!empty($value)){
        $img = $value;
      }
      $values['proxy_url'] = \Config::get('app.IMG_URL1');
      $values['image_path'] = \Config::get('app.IMG_URL2').'/'.\Storage::disk('s3')->url($img);
      $values['image_fit'] = \Config::get('app.FIT_URl');
      $values['storage_url'] = \Storage::disk('s3')->url($img);
      return $values;
    }
    public function vendor_registration_document(){
        return $this->hasOne('App\Models\VendorRegistrationDocument', 'id', 'vendor_registration_document_id'); 
    }
}
