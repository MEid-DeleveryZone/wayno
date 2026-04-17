<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;


class Wallet extends Model
{
    use HasFactory;
    protected $fillable = ['holder_type','holder_id','name','slug','description','meta','balance','decimal_places'];
    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
