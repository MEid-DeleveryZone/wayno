<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderRejectingReasonTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_rejecting_reason_id',
        'language_id',
        'reason',
    ];
}

