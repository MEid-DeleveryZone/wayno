<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionInvoiceFeaturesUser extends Model
{
    use HasFactory;
    protected $table = "subscription_invoice_features_user";

    public function feature(){
        return $this->belongsTo('App\Models\SubscriptionFeaturesListUser', 'feature_id', 'id');
    }
}
