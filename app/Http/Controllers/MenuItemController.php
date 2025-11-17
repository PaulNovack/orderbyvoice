<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMenuItemRequest;
use App\Http\Requests\UpdateMenuItemRequest;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class MenuItemController extends Controller
{
    public function index(): Response
    {
        $companyId = auth()->user()->company_id;

        $items = MenuItem::query()
            ->where('company_id', $companyId)
            ->with('category')
            ->withCount('prices')
            ->latest()
            ->get();

        return Inertia::render('MenuItems/Index', [
            'items' => $items,
        ]);
    }

    public function create(): Response
    {
        $companyId = auth()->user()->company_id;
        $categories = MenuCategory::query()
            ->where('company_id', $companyId)
            ->select('id', 'name')
            ->get();

        // Get all sizes grouped by category for the frontend
        $allSizes = \App\Models\MenuSize::query()
            ->where('company_id', $companyId)
            ->orderBy('sort_order')
            ->get()
            ->groupBy('category_id');

        return Inertia::render('MenuItems/Create', [
            'categories' => $categories,
            'sizesByCategory' => $allSizes,
        ]);
    }

    public function store(StoreMenuItemRequest $request): RedirectResponse
    {
        $menuItem = MenuItem::create(array_merge(
            $request->validated(),
            ['company_id' => auth()->user()->company_id]
        ));

        // Handle prices
        if ($request->has('prices')) {
            foreach ($request->input('prices', []) as $sizeId => $price) {
                if ($price !== null && $price !== '') {
                    $menuItem->prices()->create([
                        'size_id' => $sizeId === 'no_size' ? null : $sizeId,
                        'base_price' => $price,
                    ]);
                }
            }
        }

        return redirect()->route('menu-items.index')
            ->with('success', 'Menu item created successfully.');
    }

    public function edit(MenuItem $menuItem): Response
    {
        abort_if($menuItem->company_id !== auth()->user()->company_id, 403);

        $companyId = auth()->user()->company_id;
        $categories = MenuCategory::query()
            ->where('company_id', $companyId)
            ->select('id', 'name')
            ->get();

        // Only load sizes for the item's category
        $sizes = \App\Models\MenuSize::query()
            ->where('company_id', $companyId)
            ->where('category_id', $menuItem->category_id)
            ->orderBy('sort_order')
            ->get();

        // Load addon groups for this category with their addons
        $addonGroups = \App\Models\AddonGroup::query()
            ->where('company_id', $companyId)
            ->where('applies_to_category_id', $menuItem->category_id)
            ->with('addons')
            ->get();

        return Inertia::render('MenuItems/Edit', [
            'item' => $menuItem->load(['category', 'prices', 'defaultAddons']),
            'categories' => $categories,
            'sizes' => $sizes,
            'addonGroups' => $addonGroups,
        ]);
    }

    public function update(UpdateMenuItemRequest $request, MenuItem $menuItem): RedirectResponse
    {
        abort_if($menuItem->company_id !== auth()->user()->company_id, 403);

        $menuItem->update($request->validated());

        // Handle prices - delete existing and recreate
        if ($request->has('prices')) {
            $menuItem->prices()->delete();

            foreach ($request->input('prices', []) as $sizeId => $price) {
                if ($price !== null && $price !== '') {
                    $menuItem->prices()->create([
                        'size_id' => $sizeId === 'no_size' ? null : $sizeId,
                        'base_price' => $price,
                    ]);
                }
            }
        }

        // Handle default addons (like toppings included with the item)
        if ($request->has('default_addons')) {
            $menuItem->defaultAddons()->sync($request->input('default_addons', []));
        }

        return redirect()->route('menu-items.index')
            ->with('success', 'Menu item updated successfully.');
    }

    public function destroy(MenuItem $menuItem): RedirectResponse
    {
        abort_if($menuItem->company_id !== auth()->user()->company_id, 403);

        $menuItem->delete();

        return redirect()->route('menu-items.index')
            ->with('success', 'Menu item deleted successfully.');
    }
}
