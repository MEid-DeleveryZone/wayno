<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProhibitedItem extends Model
{
    use HasFactory;
    
    public function getImageAttribute()
    {
        return asset($this->attributes['image']);
    }
}
