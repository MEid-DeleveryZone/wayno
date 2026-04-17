<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AddonSetTranslation extends Model
{
    protected $fillable = ['title','addon_id','language_id'];
}
