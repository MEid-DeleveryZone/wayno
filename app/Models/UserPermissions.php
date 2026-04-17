<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPermissions extends Model
{
    protected $fillable = ['user_id','permission_id'];


    public function permission(){
        return $this->belongsTo('App\Models\Permissions', 'permission_id', 'id');
    }
}
