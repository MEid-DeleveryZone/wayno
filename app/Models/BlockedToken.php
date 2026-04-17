<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlockedToken extends Model
{
    protected $fillable = [
        'token', 'expired'
    ];
}