<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryHistory extends Model
{
    protected $fillable = ['category_id','action','updater_role','update_id','client_code'];
}
