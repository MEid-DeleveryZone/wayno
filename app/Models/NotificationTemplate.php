<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationTemplate extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'slug',
        'tags',
        'label'
    ];
    
    public function primary()
    {
        $langData = $this->hasOne('App\Models\NotificationTemplateTranslation')
            ->join('client_languages as cl', 'cl.language_id', 'notification_template_translations.language_id')
            ->where('cl.is_primary', 1);
        return $langData;
    }
    
    public function translation()
    {
        return $this->hasOne(NotificationTemplateTranslation::class, 'notification_template_id', 'id');
    }
    
    public function translations()
    {
        return $this->hasMany(NotificationTemplateTranslation::class);
    }
}
