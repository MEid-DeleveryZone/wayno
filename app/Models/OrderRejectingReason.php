<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderRejectingReason extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'status'
    ];

    public function primary()
    {
        return $this->hasOne(OrderRejectingReasonTranslation::class)
            ->join('client_languages as cl', 'cl.language_id', '=', 'order_rejecting_reason_translations.language_id')
            ->where('cl.is_primary', 1);
    }

    public function translation()
    {
        return $this->hasOne(OrderRejectingReasonTranslation::class);
    }

    public function translations()
    {
        return $this->hasMany(OrderRejectingReasonTranslation::class);
    }
}
