<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NomenclatureTranslation extends Model
{
    use HasFactory;
    protected $table = 'nomenclatures_translations';

    protected $fillable = ['language_id', 'nomenclature_id', 'name'];

    public static function getNameBylanguageId($language_id, $nomenclature_id){
        $name = '';
        $result = NomenclatureTranslation::where('nomenclature_id', $nomenclature_id)->where('language_id', $language_id)->first();
        if($result){
            $name = $result->name;
        }
        return $name;
    }
}
