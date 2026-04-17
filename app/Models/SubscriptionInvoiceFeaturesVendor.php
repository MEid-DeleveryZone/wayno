<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionInvoiceFeaturesVendor extends Model
{
    use HasFactory;
    protected $table = "subscription_invoice_features_vendor";

    public function feature(){
        return $this->belongsTo('App\Models\SubscriptionFeaturesListVendor', 'feature_id', 'id');
    }
}
