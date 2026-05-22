<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use Illuminate\Http\Request;

class CollectionController extends Controller
{
    public function index()
    {
        // Cargamos los productos y su relación oficial 'media' de Spatie
        $collections = Collection::where('is_active', true)
            ->with(['products' => function($query) {
                $query->where('is_active', true)
                      ->with('media'); // <-- CORRECCIÓN AQUÍ
            }])
            ->get();

        return view('collections.index', compact('collections'));
    }

    public function show(Collection $collection)
    {
        if (!$collection->is_active) {
            abort(404);
        }

        $products = $collection->products()
            ->where('is_active', true)
            ->with('media') // <-- CORRECCIÓN AQUÍ
            ->paginate(12);

        return view('collections.show', compact('collection', 'products'));
    }

    public function newest()
    {
        // Obtenemos las últimas 6 colecciones activas y precargamos sus productos
        $collections = Collection::where('is_active', true)
            ->with(['products' => function($query) {
                $query->where('is_active', true)
                      ->with('media'); // Cargamos las imágenes de los productos
            }])
            ->latest()
            ->take(6)
            ->get();

        return view('collections.newest', compact('collections'));
    }
}