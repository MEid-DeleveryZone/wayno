<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPlanFeaturesUser extends Model
{
    use HasFactory;
    protected $table = "subscription_plan_features_user";

    public function feature(){
        return $this->belongsTo('App\Models\SubscriptionFeaturesListUser'); 
    }
}
