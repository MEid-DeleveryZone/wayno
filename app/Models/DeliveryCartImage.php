<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryCartImage extends Model
{
    use HasFactory;
    protected $fillable = ['delivery_cart_id', 'image_path'];

    public function cart()
    {
        return $this->belongsTo(DeliveryCart::class, 'delivery_cart_id');
    }
}
