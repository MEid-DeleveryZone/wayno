<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromoType extends Model
{
    use HasFactory;
    protected $table = "promo_types";



    public function promocode()
    {
        return $this->hasOne(Promocode::class);
    }
}
