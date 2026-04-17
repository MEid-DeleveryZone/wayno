<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionInvoicesVendor extends Model
{
    use HasFactory;
    protected $table = "subscription_invoices_vendor";

    public function plan(){
        return $this->belongsTo('App\Models\SubscriptionPlansVendor', 'subscription_id', 'id')->withTrashed(); 
    }

    public function vendor(){
        return $this->belongsTo('App\Models\Vendor', 'vendor_id', 'id'); 
    }

    public function status(){
        return $this->belongsTo('App\Models\SubscriptionStatusOptions', 'status_id', 'id');
    }

    public function features(){
        return $this->hasMany('App\Models\SubscriptionInvoiceFeaturesVendor', 'subscription_invoice_id', 'id'); 
    }

    public function payment(){
        return $this->hasOne('App\Models\Payment', 'vendor_subscription_invoice_id', 'id'); 
    }
}
