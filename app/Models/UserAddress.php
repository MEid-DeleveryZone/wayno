<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'address',
        'house_number',
        'street',
        'city',
        'state',
        'latitude',
        'longitude',
        'pincode',
        'is_primary',
        'type',
        'category_id',
        'phonecode',
        'country_code',
        'country',
        'tag',
        'building_villa_flat_no',
        'name',
        'phone_number_type',
        'phone_number'
    ];

    public function country()
    {
        return $this->belongsTo('App\Models\Country');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }
}
