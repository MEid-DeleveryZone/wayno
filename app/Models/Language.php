<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    //

    protected $fillable = [
        'name', 'sort_code','is_rtl'
    ];
}
