<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromocodeTranslation extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'promocode_id',
        'language_id',
        'title',
        'short_desc',
        'image'
    ];
    
    public function promocode()
    {
        return $this->belongsTo(Promocode::class);
    }
    
    public function language()
    {
        return $this->belongsTo(Language::class);
    }
    
    public function getImageAttribute($value){
        $img = 'default/default_image.png';
        $values = array();
        if(!empty($value)){
            $img = $value;
        }
        $values['proxy_url'] = \Config::get('app.IMG_URL1');
        $values['image_path'] = \Config::get('app.IMG_URL2').'/'.\Storage::disk('s3')->url($img);
        $values['image_fit'] = \Config::get('app.FIT_URl');
        return $values;
    }
}

