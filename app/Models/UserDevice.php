<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class UserDevice extends Model
{
    use Notifiable;
    protected $fillable = ['user_id','device_type','fcm_token','device_token','access_token'];

    public function routeNotificationForFcm()
    {
        return $this->device_token;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
