<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationTemplateTranslation extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'notification_template_id',
        'language_id',
        'subject',
        'content'
    ];
    
    public function notificationTemplate()
    {
        return $this->belongsTo(NotificationTemplate::class);
    }
    
    public function language()
    {
        return $this->belongsTo(Language::class);
    }
}
