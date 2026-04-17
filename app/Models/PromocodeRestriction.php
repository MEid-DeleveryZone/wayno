<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromocodeRestriction extends Model
{
    use HasFactory;
    protected $table = 'promocode_restrictions';

    public function promocode()
    {
        return $this->belongsTo(Promocode::class);
    }
}

