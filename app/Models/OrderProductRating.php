<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Carbon\Carbon;
class OrderProductRating extends Model
{
    protected $table = 'order_product_ratings';

    protected $fillable = [
       'order_vendor_product_id','order_id','product_id','user_id','rating','review','file',
    ];


    public function reviewFiles(){
        return $this->hasMany(OrderProductRatingFile::class, 'order_product_rating_id', 'id');
    }

    public function user(){
      return $this->belongsTo(User::class,'user_id','id');
    }


    public function getTimeZoneCreatedAtAttribute($value)
    { 
      $timezone = Auth::user()->timezone??'UTC';
      $date = Carbon::parse($value, 'UTC');
      $date->setTimezone($timezone);
      return $date;
    }
}
