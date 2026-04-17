<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionInvoicesUser extends Model
{
    use HasFactory;
    protected $table = "subscription_invoices_user";

    public function plan(){
        return $this->belongsTo('App\Models\SubscriptionPlansUser', 'subscription_id', 'id')->withTrashed(); 
    }

    public function user(){
        return $this->belongsTo('App\Models\User', 'user_id', 'id'); 
    }

    public function features(){
        return $this->hasMany('App\Models\SubscriptionInvoiceFeaturesUser', 'subscription_invoice_id', 'id'); 
    }

    public function payment(){
        return $this->hasOne('App\Models\Payment', 'user_subscription_invoice_id', 'id'); 
    }
}
