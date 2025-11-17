<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMenuSizeRequest;
use App\Http\Requests\UpdateMenuSizeRequest;
use App\Models\MenuSize;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class MenuSizeController extends Controller
{
    public function index(): Response
    {
        $companyId = auth()->user()->company_id;

        $sizes = MenuSize::query()
            ->where('company_id', $companyId)
            ->with('category')
            ->orderBy('sort_order')
            ->get();

        return Inertia::render('MenuSizes/Index', [
            'sizes' => $sizes,
        ]);
    }

    public function create(): Response
    {
        $companyId = auth()->user()->company_id;
        $categories = \App\Models\MenuCategory::query()
            ->where('company_id', $companyId)
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        return Inertia::render('MenuSizes/Create', [
            'categories' => $categories,
        ]);
    }

    public function store(StoreMenuSizeRequest $request): RedirectResponse
    {
        $data = $request->validated();

        // Convert "none" to null for category_id
        if (isset($data['category_id']) && $data['category_id'] === 'none') {
            $data['category_id'] = null;
        }

        MenuSize::create(array_merge(
            $data,
            ['company_id' => auth()->user()->company_id]
        ));

        return redirect()->route('menu-sizes.index')
            ->with('success', 'Size created successfully.');
    }

    public function edit(MenuSize $menuSize): Response
    {
        abort_if($menuSize->company_id !== auth()->user()->company_id, 403);

        $companyId = auth()->user()->company_id;
        $categories = \App\Models\MenuCategory::query()
            ->where('company_id', $companyId)
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        return Inertia::render('MenuSizes/Edit', [
            'size' => $menuSize,
            'categories' => $categories,
        ]);
    }

    public function update(UpdateMenuSizeRequest $request, MenuSize $menuSize): RedirectResponse
    {
        abort_if($menuSize->company_id !== auth()->user()->company_id, 403);

        $data = $request->validated();

        // Convert "none" to null for category_id
        if (isset($data['category_id']) && $data['category_id'] === 'none') {
            $data['category_id'] = null;
        }

        $menuSize->update($data);

        return redirect()->route('menu-sizes.index')
            ->with('success', 'Size updated successfully.');
    }

    public function destroy(MenuSize $menuSize): RedirectResponse
    {
        abort_if($menuSize->company_id !== auth()->user()->company_id, 403);

        $menuSize->delete();

        return redirect()->route('menu-sizes.index')
            ->with('success', 'Size deleted successfully.');
    }
}
