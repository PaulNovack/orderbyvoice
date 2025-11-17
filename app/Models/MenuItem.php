<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MenuItem extends Model
{
    protected $fillable = [
        'company_id',
        'category_id',
        'name',
        'description',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
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

    public function prices(): HasMany
    {
        return $this->hasMany(ItemPrice::class, 'item_id');
    }

    public function addonGroups(): HasMany
    {
        return $this->hasMany(AddonGroup::class, 'applies_to_item_id');
    }

    public function defaultAddons(): BelongsToMany
    {
        return $this->belongsToMany(Addon::class, 'menu_item_addon');
    }
}
