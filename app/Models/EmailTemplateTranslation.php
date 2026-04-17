<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailTemplateTranslation extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'email_template_id',
        'language_id',
        'subject',
        'content'
    ];
    
    public function emailTemplate()
    {
        return $this->belongsTo(EmailTemplate::class);
    }
    
    public function language()
    {
        return $this->belongsTo(Language::class);
    }
}

