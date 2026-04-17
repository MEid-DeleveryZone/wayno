<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Page extends Model{
    use HasFactory;
    protected $fillable = ['title'];
    public function primary(){
      $langData = $this->hasOne('App\Models\PageTranslation')->join('client_languages as cl', 'cl.language_id', 'page_translations.language_id')->where('cl.is_primary', 1);
      return $langData;
    }
    public function translation()
    {
        return $this->hasOne(PageTranslation::class, 'page_id', 'id');
    }
    public function translations()
{
    return $this->hasMany(PageTranslation::class);
}
}
