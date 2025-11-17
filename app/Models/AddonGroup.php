<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AddonGroup extends Model
{
    protected $fillable = [
        'company_id',
        'name',
        'applies_to_category_id',
        'applies_to_item_id',
        'min_select',
        'max_select',
        'required',
    ];

    protected function casts(): array
    {
        return [
            'min_select' => 'integer',
            'max_select' => 'integer',
            'required' => 'boolean',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(MenuCategory::class, 'applies_to_category_id');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class, 'applies_to_item_id');
    }

    public function addons(): HasMany
    {
        return $this->hasMany(Addon::class, 'addon_group_id');
    }
}
