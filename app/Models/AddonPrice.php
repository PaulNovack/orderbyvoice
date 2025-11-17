<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AddonPrice extends Model
{
    protected $fillable = [
        'addon_id',
        'size_id',
        'price',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
        ];
    }

    public function addon(): BelongsTo
    {
        return $this->belongsTo(Addon::class, 'addon_id');
    }

    public function size(): BelongsTo
    {
        return $this->belongsTo(MenuSize::class, 'size_id');
    }
}
