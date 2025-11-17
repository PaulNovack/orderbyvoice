<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Addon extends Model
{
    protected $fillable = [
        'addon_group_id',
        'name',
        'type',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(AddonGroup::class, 'addon_group_id');
    }

    public function prices(): HasMany
    {
        return $this->hasMany(AddonPrice::class, 'addon_id');
    }
}
