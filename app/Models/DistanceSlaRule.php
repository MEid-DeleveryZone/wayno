<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DistanceSlaRule extends Model
{
    protected $fillable = [
        'vendor_id',
        'scope',
        'min_distance',
        'max_distance',
        'time_with_rider',
        'time_without_rider',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function scopeForDistance($query, float $distance)
    {
        return $query->where('min_distance', '<=', $distance)
            ->where('max_distance', '>=', $distance);
    }

    public function scopeGlobal($query)
    {
        return $query->where('scope', 'global');
    }
}
