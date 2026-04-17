<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = ['amount','transaction_id','balance_transaction','type','date','cart_id','order_id'];
}
