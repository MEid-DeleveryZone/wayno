<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VariantOptionTranslation extends Model
{
    protected $fillable = ['title','variant_option_id','language_id'];
}
