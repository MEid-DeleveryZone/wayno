<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AddonOptionTranslation extends Model
{
    protected $fillable = ['title','addon_opt_id','language_id'];
}
