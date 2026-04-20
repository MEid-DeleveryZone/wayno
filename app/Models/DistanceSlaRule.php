<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DistanceSlaRule extends Model
{
    protected $fillable = [
        'distance_sla_group_id',
        'distance_from',
        'distance_to',
        'time_with_rider',
        'time_without_rider',
    ];

    protected $casts = [
        'distance_from' => 'float',
        'distance_to' => 'float',
    ];

    public function group(): BelongsTo
    {
        return $this->belongsTo(DistanceSlaGroup::class, 'distance_sla_group_id');
    }
}
