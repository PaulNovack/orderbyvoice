<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MenuSize extends Model
{
    protected $fillable = [
        'company_id',
        'category_id',
        'name',
        'size_note',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(MenuCategory::class, 'category_id');
    }

    public function itemPrices(): HasMany
    {
        return $this->hasMany(ItemPrice::class, 'size_id');
    }

    public function addonPrices(): HasMany
    {
        return $this->hasMany(AddonPrice::class, 'size_id');
    }
}
