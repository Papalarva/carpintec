<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Discount;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::with('discount')->latest('created_at')->paginate(15);
        return view('admin.coupons.index', compact('coupons'));
    }

    public function create()
    {
        $discounts = Discount::where('is_active', true)->pluck('name', 'id');
        return view('admin.coupons.create', compact('discounts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'discount_id' => 'required|exists:discounts,id',
            'code'        => 'nullable|string|unique:coupons,code',
            'max_uses'    => 'nullable|integer|min:1',
            'expires_at'  => 'nullable|date|after:today',
        ]);

        Coupon::create([
            'code'        => $validated['code'] ?: strtoupper(Str::random(10)),
            'discount_id' => $validated['discount_id'],
            'max_uses'    => $validated['max_uses'] ?? null,
            'expires_at'  => $validated['expires_at'] ?? null,
            'used_count'  => 0,
        ]);

        return redirect()->route('admin.coupons.index')->with('success', 'Cupón creado.');
    }

    public function edit(Coupon $coupon)
    {
        $discounts = Discount::where('is_active', true)->pluck('name', 'id');
        return view('admin.coupons.edit', compact('coupon', 'discounts'));
    }

    public function update(Request $request, Coupon $coupon)
    {
        $validated = $request->validate([
            'discount_id' => 'required|exists:discounts,id',
            'code'        => 'required|string|unique:coupons,code,' . $coupon->id,
            'max_uses'    => 'nullable|integer|min:1',
            'expires_at'  => 'nullable|date',
        ]);

        $coupon->update($validated);

        return redirect()->route('admin.coupons.index')->with('success', 'Cupón actualizado.');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return back()->with('success', 'Cupón eliminado.');
    }
}