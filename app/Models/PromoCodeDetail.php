<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromoCodeDetail extends Model
{
    use HasFactory;
    protected $table = 'promocode_details';
    protected $fillable = ['promocode_id','refrence_id'];

    public function promocode(){
        return $this->hasOne('App\Models\Promocode' , 'id', 'promocode_id')->where('restriction_on', 1)->where('restriction_type', 0);
    }
}
