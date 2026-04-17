<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriverRegistrationDocument extends Model
{
    use HasFactory;
    public function primary(){
        $langData = $this->hasOne('App\Models\DriverRegistrationDocumentTranslation')->join('client_languages as cl', 'cl.language_id', 'driver_registration_document_translations.language_id')->where('cl.is_primary', 1);
        return $langData;
      }
      public function translations(){
        $langData = $this->hasMany('App\Models\DriverRegistrationDocumentTranslation');
        return $langData;
      }
}
