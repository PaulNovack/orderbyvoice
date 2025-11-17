<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMenuCategoryRequest;
use App\Http\Requests\UpdateMenuCategoryRequest;
use App\Models\MenuCategory;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class MenuCategoryController extends Controller
{
    public function index(): Response
    {
        $companyId = auth()->user()->company_id;

        $categories = MenuCategory::query()
            ->where('company_id', $companyId)
            ->withCount('items')
            ->latest()
            ->get();

        return Inertia::render('MenuCategories/Index', [
            'categories' => $categories,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('MenuCategories/Create');
    }

    public function store(StoreMenuCategoryRequest $request): RedirectResponse
    {
        MenuCategory::create(array_merge(
            $request->validated(),
            ['company_id' => auth()->user()->company_id]
        ));

        return redirect()->route('menu-categories.index')
            ->with('success', 'Category created successfully.');
    }

    public function edit(MenuCategory $menuCategory): Response
    {
        abort_if($menuCategory->company_id !== auth()->user()->company_id, 403);

        return Inertia::render('MenuCategories/Edit', [
            'category' => $menuCategory,
        ]);
    }

    public function update(UpdateMenuCategoryRequest $request, MenuCategory $menuCategory): RedirectResponse
    {
        abort_if($menuCategory->company_id !== auth()->user()->company_id, 403);

        $menuCategory->update($request->validated());

        return redirect()->route('menu-categories.index')
            ->with('success', 'Category updated successfully.');
    }

    public function destroy(MenuCategory $menuCategory): RedirectResponse
    {
        abort_if($menuCategory->company_id !== auth()->user()->company_id, 403);

        $menuCategory->delete();

        return redirect()->route('menu-categories.index')
            ->with('success', 'Category deleted successfully.');
    }
}
