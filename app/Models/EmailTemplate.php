<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'slug',
        'tags',
        'label'
    ];
    
    public function primary()
    {
        $langData = $this->hasOne('App\Models\EmailTemplateTranslation')
            ->join('client_languages as cl', 'cl.language_id', 'email_template_translations.language_id')
            ->where('cl.is_primary', 1);
        return $langData;
    }
    
    public function translation()
    {
        return $this->hasOne(EmailTemplateTranslation::class, 'email_template_id', 'id');
    }
    
    public function translations()
    {
        return $this->hasMany(EmailTemplateTranslation::class);
    }
}
