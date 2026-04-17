<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryTranslation extends Model
{
    use HasFactory;
    protected $fillable = ['name','trans-slug','meta_title','meta_description','meta_keywords','category_id','language_id'];
}
