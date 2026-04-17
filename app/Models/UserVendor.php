<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserVendor extends Model
{
    protected $fillable = ['user_id','vendor_id'];



    public function user(){
        return $this->belongsTo('App\Models\User', 'user_id', 'id')->select('id','email','name','image','phone_number');
     }

}
