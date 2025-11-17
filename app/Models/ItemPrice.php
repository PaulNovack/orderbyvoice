<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemPrice extends Model
{
    protected $fillable = [
        'item_id',
        'size_id',
        'base_price',
    ];

    protected function casts(): array
    {
        return [
            'base_price' => 'decimal:2',
        ];
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class, 'item_id');
    }

    public function size(): BelongsTo
    {
        return $this->belongsTo(MenuSize::class, 'size_id');
    }
}
