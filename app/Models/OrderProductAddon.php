<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderProductAddon extends Model
{
    use HasFactory;
    public function option(){
       return $this->belongsTo('App\Models\AddonOption', 'option_id', 'id')
       			->join('addon_option_translations as aot', 'addon_options.id', 'aot.addon_opt_id')
				->select('addon_options.id', 'addon_options.addon_id', 'addon_options.price', 'aot.title', 'aot.language_id')
				->orderBy('addon_options.position', 'asc'); 
    }

    public function set(){
       return $this->belongsTo('App\Models\AddonSet', 'addon_id', 'id')
       			->join('addon_set_translations as ast', 'addon_sets.id', 'ast.addon_id')
				->select('addon_sets.id', 'ast.title', 'ast.language_id')
				->orderBy('addon_sets.position', 'asc'); 
    }

}
