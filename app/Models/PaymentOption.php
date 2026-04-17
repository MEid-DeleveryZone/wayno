<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentOption extends Model
{
    protected $fillable = ['code','path','title','credentials','status'];

    use HasFactory;

    protected $appends = ['title_lng'];

    public function getTitleLngAttribute(){
        return __($this->title);
    }
}
