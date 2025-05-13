<?php

use Livewire\Volt\Volt;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomepageController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductCategoryController;
Use App\Http\Controllers\PostController;
use App\Http\Controllers\ProductsController;


//kode baru diubah menjadi seperti ini
Route::get('/', [HomepageController::class, 'index'])->name('home');
Route::get('products', [HomepageController::class, 'products'])->name('products');
Route::get('product/{slug}', [HomepageController::class, 'product']);
Route::get('categories',[HomepageController::class, 'categories']);
Route::get('category/{slug}', [HomepageController::class, 'category']);
Route::get('cart', [HomepageController::class, 'cart']);
Route::get('checkout', [HomepageController::class, 'checkout']);


    Route::middleware(['auth', 'verified'])->prefix('dashboard')->name('dashboard.')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('index');
    
        Route::resource('categories', ProductCategoryController::class);
        //Route::get('products', [DashboardController::class, 'products'])->name('products');
        //Route::resource('products', ProductsController::class)->names('products');
    
        Route::resource('posts', PostController::class)->names('posts');
        
    });
    
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::middleware(['auth', 'verified'])->prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('products', [DashboardController::class, 'products'])->name('products.index');
    Route::get('products/create', [DashboardController::class, 'createProduct'])->name('products.create');
    Route::post('products', [DashboardController::class, 'storeProduct'])->name('products.store');
    Route::get('products/{product}', [Products::class, 'showProduct'])->name('products.show');
    Route::get('products/{product}/edit', [DashboardController::class, 'editProduct'])->name('products.edit');
    Route::patch('products/{product}', [DashboardController::class, 'updateProduct'])->name('products.update');
    Route::delete('products/{product}', [DashboardController::class, 'destroyProduct'])->name('products.destroy');
});


Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__.'/auth.php';
