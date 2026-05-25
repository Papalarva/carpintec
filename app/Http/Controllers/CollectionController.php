<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use Illuminate\Http\Request;

class CollectionController extends Controller
{
    public function index()
    {
        $collections = Collection::where('is_active', true)
            ->with(['products' => function ($query) {
                $query->where('is_active', true)
                    ->with('media');
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
            ->with('media')
            ->paginate(12);

        return view('collections.show', compact('collection', 'products'));
    }

    public function newest()
    {
        $collections = Collection::where('is_active', true)
            ->with(['products' => function ($query) {
                $query->where('is_active', true)
                    ->with('media');
            }])
            ->latest()
            ->take(6)
            ->get();

        return view('collections.newest', compact('collections'));
    }
}
