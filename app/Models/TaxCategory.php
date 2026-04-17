<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaxCategory extends Model
{
    public function taxRate(){
       return $this->hasMany('App\Models\TaxRateCategory', 'tax_cate_id', 'id')
       			->join('tax_rates as tr', 'tr.id', 'tax_rate_categories.tax_rate_id')
		       ->select('tr.id', 'tr.identifier', 'tr.is_zip', 'tr.zip_code', 'tr.zip_from', 'tr.zip_to', 'tr.state', 'tr.country', 'tr.tax_rate', 'tax_rate_categories.tax_cate_id');
    }
}