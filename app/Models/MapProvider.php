<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MapProvider extends Model
{
    protected $fillable = ['provider', 'keyword', 'status'];
}
