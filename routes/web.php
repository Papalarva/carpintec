<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CatalogController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/catalogo', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/producto/{slug}', [CatalogController::class, 'show'])->name('catalog.show');
Route::post('/carrito/agregar/{product}', function () {
    return redirect()->back()->with('info', 'El carrito estará disponible próximamente.');
})->name('cart.add');

Route::get('/cotizar/{product}', function () {
    return redirect()->back()->with('info', 'Las cotizaciones estarán disponibles próximamente.');
})->name('quotation.request');

require __DIR__.'/auth.php';
