<?php
// app/Services/DiscountService.php
namespace App\Services;

use App\Models\Coupon;
use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class DiscountService
{
    public function applyCoupon(string $code, Collection $cartItems, ?Customer $customer): array
    {
        $coupon = Coupon::where('code', $code)->first();

        if (!$coupon) {
            throw new \Exception('Cupón no válido.');
        }

        if ($coupon->expires_at && Carbon::parse($coupon->expires_at)->isPast()) {
            throw new \Exception('El cupón ha expirado.');
        }

        if ($coupon->max_uses !== null && $coupon->used_count >= $coupon->max_uses) {
            throw new \Exception('El cupón ha alcanzado su límite de usos.');
        }

        $discount = $coupon->discount;

        if (!$discount || !$discount->is_active) {
            throw new \Exception('El descuento asociado no está activo.');
        }

        $now = Carbon::now();
        if ($discount->starts_at && Carbon::parse($discount->starts_at)->gt($now)) {
            throw new \Exception('El descuento aún no está vigente.');
        }
        if ($discount->ends_at && Carbon::parse($discount->ends_at)->lt($now)) {
            throw new \Exception('El descuento ya no está vigente.');
        }

        $discountAmount = 0;
        $subtotal = $cartItems->sum(fn($item) => ($item->product->price ?? 0) * ($item->quantity ?? 0));
        $scope = strtolower(trim($discount->applies_to));

        switch ($scope) {
            case 'general':
            case 'all': // Soporte por si se guardó como 'all'
                $discountAmount = $this->calc($discount, $subtotal);
                break;
                
            case 'product':
            case 'products': // Soporte para plurales accidentales
                $validItems = $cartItems->filter(fn($item) =>
                    $discount->products->contains('id', $item->product_id ?? $item->product?->id)
                );
                $validSubtotal = $validItems->sum(fn($item) => ($item->product->price ?? 0) * ($item->quantity ?? 0));
                $discountAmount = $this->calc($discount, $validSubtotal);
                break;
                
            case 'category':
            case 'categories':
                $validItems = $cartItems->filter(fn($item) =>
                    $discount->categories->contains('id', $item->product->category_id ?? $item->product?->category_id)
                );
                $validSubtotal = $validItems->sum(fn($item) => ($item->product->price ?? 0) * ($item->quantity ?? 0));
                $discountAmount = $this->calc($discount, $validSubtotal);
                break;
                
            case 'customer':
            case 'customers':
                if (!$customer || !$discount->customers->contains('id', $customer->id)) {
                    throw new \Exception('Este cupón no es válido para tu cuenta.');
                }
                $discountAmount = $this->calc($discount, $subtotal);
                break;
                
            default:
                // Regla de Oro: En modo de depuración, siempre escupe el valor real que está rompiendo el flujo
                throw new \Exception("Ámbito de descuento no soportado: [{$discount->applies_to}]");
        }

        if ($discountAmount <= 0) {
            throw new \Exception('El cupón es válido, pero no aplica para los productos actuales de tu carrito.');
        }

        return [
            'amount' => round($discountAmount, 2),
            'coupon' => $coupon,
        ];
    }

    private function calc($discount, float $base): float
    {
        // SOLUCIÓN: Usar comparaciones estrictas de Enum.
        // Laravel ya nos garantiza que $discount->type es una instancia de App\Enums\DiscountType
        
        if ($discount->type === \App\Enums\DiscountType::PERCENTAGE) {
            return $base * ($discount->value / 100);
        }

        if ($discount->type === \App\Enums\DiscountType::FIXED_AMOUNT) {
            return min($discount->value, $base);
        }

        // Regla de Oro: Si por alguna razón el tipo de descuento no cuadra matemáticamente, fallamos explícitamente.
        throw new \Exception("Tipo de descuento no válido para cálculos.");
    }
}