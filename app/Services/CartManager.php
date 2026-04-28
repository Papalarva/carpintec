<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class CartManager
{
    /**
     * Obtiene los ítems del carrito actual (sesión o BD).
     *
     * @return Collection
     */
    public function getItems(): Collection
    {
        if (Auth::check() && Auth::user()->customer) {
            return $this->getDatabaseItems();
        }

        return $this->getSessionItems();
    }

    /**
     * Agrega un producto al carrito. Si ya existe, incrementa la cantidad.
     */
    public function addItem(Product $product, int $quantity = 1): void
    {
        if ($quantity < 1) {
            return;
        }

        if (Auth::check() && Auth::user()->customer) {
            $this->addToDatabase($product, $quantity);
        } else {
            $this->addToSession($product, $quantity);
        }
    }

    /**
     * Actualiza la cantidad de un producto en el carrito.
     */
    public function updateQuantity(string $productId, int $quantity): void
    {
        if ($quantity < 1) {
            $this->removeItem($productId);
            return;
        }

        if (Auth::check() && Auth::user()->customer) {
            $this->updateDatabaseQuantity($productId, $quantity);
        } else {
            $this->updateSessionQuantity($productId, $quantity);
        }
    }

    /**
     * Elimina un producto del carrito.
     */
    public function removeItem(string $productId): void
    {
        if (Auth::check() && Auth::user()->customer) {
            $this->removeFromDatabase($productId);
        } else {
            $this->removeFromSession($productId);
        }
    }

    /**
     * Calcula el subtotal del carrito (suma de precio * cantidad).
     */
    public function getSubtotal(): float
    {
        $items = $this->getItems();
        $subtotal = 0;

        foreach ($items as $item) {
            // Ambos casos (BD y sesión) tienen producto con precio
            $product = $item->product ?? Product::find($item['product_id'] ?? $item->product_id);
            $price = $product?->price ?? 0;
            $qty = $item->quantity ?? $item['quantity'];
            $subtotal += $price * $qty;
        }

        return round($subtotal, 2);
    }

    /**
     * Devuelve la cantidad total de productos en el carrito.
     */
    public function getTotalCount(): int
    {
        $items = $this->getItems();
        $count = 0;

        foreach ($items as $item) {
            $count += $item->quantity ?? $item['quantity'];
        }

        return $count;
    }

    /**
     * Migra el carrito de sesión a la base de datos cuando un invitado inicia sesión o se registra.
     */
    public function migrateSessionToDatabase(Customer $customer): void
    {
        $sessionItems = session()->get('cart', []);

        if (empty($sessionItems)) {
            return;
        }

        $cart = $customer->cart()->firstOrCreate([]);

        foreach ($sessionItems as $productId => $quantity) {
            $product = Product::find($productId);
            if (!$product) {
                continue;
            }

            // Si ya existe el producto en el carrito del cliente, sumamos cantidades
            $existingItem = $cart->items()->where('product_id', $productId)->first();
            if ($existingItem) {
                $existingItem->update(['quantity' => $existingItem->quantity + $quantity]);
            } else {
                $cart->items()->create([
                    'product_id' => $productId,
                    'quantity' => $quantity,
                ]);
            }
        }

        $this->clearSession();
    }

    /**
     * Vacía el carrito de sesión.
     */
    public function clearSession(): void
    {
        session()->forget('cart');
    }

    // ─── Métodos privados para base de datos ────────────────────────────

    private function getDatabaseItems(): Collection
    {
        $customer = Auth::user()->customer;
        $cart = $customer->cart;

        if (!$cart) {
            return collect();
        }

        return $cart->items()->with('product')->get();
    }

    private function addToDatabase(Product $product, int $quantity): void
    {
        $customer = Auth::user()->customer;
        $cart = $customer->cart()->firstOrCreate([]);

        $existingItem = $cart->items()->where('product_id', $product->id)->first();

        if ($existingItem) {
            $existingItem->update(['quantity' => $existingItem->quantity + $quantity]);
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity' => $quantity,
            ]);
        }
    }

    private function updateDatabaseQuantity(string $productId, int $quantity): void
    {
        $customer = Auth::user()->customer;
        $cart = $customer->cart;
        if (!$cart) return;

        $cart->items()->where('product_id', $productId)->update(['quantity' => $quantity]);
    }

    private function removeFromDatabase(string $productId): void
    {
        $customer = Auth::user()->customer;
        $cart = $customer->cart;
        if (!$cart) return;

        $cart->items()->where('product_id', $productId)->delete();
    }

    // ─── Métodos privados para sesión ───────────────────────────────────

    private function getSessionItems(): Collection
    {
        $cart = session()->get('cart', []);

        return collect($cart)->map(function ($quantity, $productId) {
            $product = Product::find($productId);
            return (object)[
                'product_id' => $productId,
                'quantity' => $quantity,
                'product' => $product,
            ];
        })->values();
    }

    private function addToSession(Product $product, int $quantity): void
    {
        $cart = session()->get('cart', []);
        $productId = $product->id;

        if (isset($cart[$productId])) {
            $cart[$productId] += $quantity;
        } else {
            $cart[$productId] = $quantity;
        }

        session()->put('cart', $cart);
    }

    private function updateSessionQuantity(string $productId, int $quantity): void
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$productId])) {
            $cart[$productId] = $quantity;
            session()->put('cart', $cart);
        }
    }

    private function removeFromSession(string $productId): void
    {
        $cart = session()->get('cart', []);
        unset($cart[$productId]);
        session()->put('cart', $cart);
    }
}