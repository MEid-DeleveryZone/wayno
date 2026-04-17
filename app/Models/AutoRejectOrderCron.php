<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AutoRejectOrderCron extends Model
{
    protected $table = 'auto_reject_orders_cron';

    protected $fillable = ['database_host','database_name','database_username','database_password','order_vendor_id','auto_reject_time'];
}
