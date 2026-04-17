<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorOrderDispatcherStatus extends Model
{
    protected $table = 'vendor_order_dispatcher_statuses';

    protected $fillable = [
        'dispatcher_id', 'order_id', 'dispatcher_status_option_id', 'vendor_id'
    ];
}
