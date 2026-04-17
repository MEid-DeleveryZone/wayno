<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorDineinCategory extends Model
{
    use HasFactory;
    
    public function translations(){
        $langData = $this->hasMany('App\Models\VendorDineinCategoryTranslation', 'category_id', 'id');
        return $langData;
    }
}
