<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPlanFeaturesVendor extends Model
{
    use HasFactory;
    protected $table = "subscription_plan_features_vendor";

    public function feature(){
        return $this->belongsTo('App\Models\SubscriptionFeaturesListVendor'); 
    }
}
