<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MenuCategory extends Model
{
    protected $fillable = [
        'company_id',
        'name',
        'slug',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'category_id');
    }

    public function addonGroups(): HasMany
    {
        return $this->hasMany(AddonGroup::class, 'applies_to_category_id');
    }
}
