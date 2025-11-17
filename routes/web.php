<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return Inertia::render('welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');

    // Menu management (admin)
    Route::resource('menu-categories', App\Http\Controllers\MenuCategoryController::class)->except('show');
    Route::resource('menu-items', App\Http\Controllers\MenuItemController::class)->except('show');
    Route::resource('menu-sizes', App\Http\Controllers\MenuSizeController::class)->except('show');

    // Menu display/ordering (customer-facing)
    Route::get('menu', [App\Http\Controllers\MenuController::class, 'index'])->name('menu.index');
    Route::get('menu/{menuItem}', [App\Http\Controllers\MenuController::class, 'show'])->name('menu.show');
});

require __DIR__.'/settings.php';
