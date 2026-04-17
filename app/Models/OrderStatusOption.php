<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderStatusOption extends Model
{
  use HasFactory;

  public static function findNext($id)
  {
    return static::where('id', '>', $id)->first();
  }
  public function getPreference()
  {
    return $this->hasOne('App\Models\ClientPreference', 'client_code', 'code');
  }

  public function getImageAttribute()
  {
    return asset($this->attributes['image']);
  }
}
