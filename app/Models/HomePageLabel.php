<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomePageLabel extends Model
{
    use HasFactory;

    protected $fillable = ['title','slug','order_by','is_active'];


    public function translations(){
        $langData = $this->hasMany('App\Models\HomePageLabelTranslation');
        return $langData;
    }
}
