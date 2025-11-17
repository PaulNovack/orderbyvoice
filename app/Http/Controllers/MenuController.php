<?php

namespace App\Http\Controllers;

use App\Models\MenuCategory;
use App\Models\MenuItem;
use Inertia\Inertia;
use Inertia\Response;

class MenuController extends Controller
{
    public function index(): Response
    {
        $companyId = auth()->user()->company_id;

        $categories = MenuCategory::query()
            ->where('company_id', $companyId)
            ->with(['items' => function ($query) {
                $query->where('is_active', true)
                    ->with(['prices.size']);
            }])
            ->get();

        return Inertia::render('Menu/Index', [
            'categories' => $categories,
        ]);
    }

    public function show(MenuItem $menuItem): Response
    {
        abort_if($menuItem->company_id !== auth()->user()->company_id, 403);

        $menuItem->load([
            'prices.size',
            'category.addonGroups.addons.prices.size',
            'defaultAddons',
        ]);

        return Inertia::render('Menu/Show', [
            'item' => $menuItem,
        ]);
    }
}
