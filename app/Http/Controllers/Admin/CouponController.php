<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Discount;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CouponController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $sort = $request->query('sort');
        $direction = $request->query('direction', 'desc');

        $query = Coupon::query()->with('discount');

        // 1. Filtro de Búsqueda
        if ($search) {
            $query->where('code', 'ilike', "%{$search}%")
                  ->orWhereHas('discount', function($q) use ($search) {
                      $q->where('name', 'ilike', "%{$search}%");
                  });
        }

        // 2. Ordenamiento Dinámico
        $allowedSorts = ['code', 'used_count', 'max_uses', 'expires_at', 'created_at'];
        
        if ($sort && in_array($sort, $allowedSorts)) {
            $direction = strtolower($direction) === 'asc' ? 'asc' : 'desc';
            $query->orderBy($sort, $direction);
        } else {
            $query->latest('created_at');
        }

        $coupons = $query->paginate(15)->withQueryString();

        return view('admin.coupons.index', compact('coupons', 'search'));
    }

    public function create()
    {
        // Traemos la colección completa para mostrar el nombre y el valor en la vista
        $discounts = Discount::where('is_active', true)->orderBy('name')->get();
        return view('admin.coupons.create', compact('discounts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'discount_id' => 'required|exists:discounts,id',
            'code'        => 'nullable|string|unique:coupons,code|max:50',
            'max_uses'    => 'nullable|integer|min:1',
            'expires_at'  => 'nullable|date|after:today',
        ]);

        Coupon::create([
            'code'        => $validated['code'] ? strtoupper($validated['code']) : strtoupper(Str::random(10)),
            'discount_id' => $validated['discount_id'],
            'max_uses'    => $validated['max_uses'] ?? null,
            'expires_at'  => $validated['expires_at'] ?? null,
            'used_count'  => 0,
        ]);

        return redirect()->route('admin.coupons.index')->with('success', 'Cupón promocional creado exitosamente.');
    }

    public function edit(Coupon $coupon)
    {
        $discounts = Discount::where('is_active', true)->orderBy('name')->get();
        return view('admin.coupons.edit', compact('coupon', 'discounts'));
    }

    public function update(Request $request, Coupon $coupon)
    {
        $validated = $request->validate([
            'discount_id' => 'required|exists:discounts,id',
            'code'        => 'required|string|unique:coupons,code,' . $coupon->id . '|max:50',
            'max_uses'    => 'nullable|integer|min:1',
            'expires_at'  => 'nullable|date',
        ]);

        // Aseguramos que el código siempre se guarde en mayúsculas
        $validated['code'] = strtoupper($validated['code']);

        $coupon->update($validated);

        return redirect()->route('admin.coupons.index')->with('success', 'Configuración del cupón actualizada.');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return back()->with('success', 'Cupón eliminado del sistema.');
    }
}