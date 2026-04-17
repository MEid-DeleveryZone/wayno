<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemCategory extends Model
{
    use HasFactory;

    // Specify the table name (optional if the model name matches the table)
    protected $table = 'item_categories';

    // Specify the attributes that are mass assignable
    protected $fillable = [
        'name',
        'vendor_id',
        'selected_image',
        'unselected_image',
        'is_deleted',
    ];
    protected $appends = ['selected_image_url', 'unselected_image_url'];

    public function getSelectedImageUrlAttribute()
    {
        return asset($this->attributes['selected_image']);
    }

    public function getUnselectedImageUrlAttribute()
    {
        return asset($this->attributes['unselected_image']);
    }
}
