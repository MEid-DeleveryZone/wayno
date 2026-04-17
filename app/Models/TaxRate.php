<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaxRate extends Model
{
    protected $fillables = ['identifier','is_zip', 'zip_code', 'zip_from', 'zip_to', 'state','country', 'tax_rate', 'tax_amount'];

    public function category(){
    return $this->hasMany(TaxRateCategory::class, 'tax_rate_id', 'id')->join('tax_categories', 'tax_rate_categories.tax_cate_id', 'tax_categories.id'); 
  }
}
