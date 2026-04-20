<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DistanceSlaGroup extends Model
{
    protected $fillable = [
        'name',
        'is_default',
        'is_active',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function rules(): HasMany
    {
        return $this->hasMany(DistanceSlaRule::class);
    }

    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function (DistanceSlaGroup $group) {
            if ($group->is_default) {
                static::where('id', '!=', $group->id ?? 0)->update(['is_default' => false]);
            }
        });
    }
}