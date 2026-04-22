<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

/**
 * OrderController
 *
 * Responsabilidad única: manejar el ciclo de vida HTTP de los pedidos (Orders).
 * Seguimos el patrón Thin Controller: este controlador NO contiene lógica de
 * negocio. Solo orquesta la Request → Model → Response.
 *
 * Si la lógica de creación crece (p. ej. aplicar cupones, recalcular totales,
 * notificar por correo), extrae esa lógica a un Service/Action separado y
 * llámalo desde aquí.
 */
class OrderController extends Controller
{
    /**
     * Almacena un nuevo pedido en la base de datos.
     *
     * El flujo completo es:
     *   1. Laravel valida la petición con StoreOrderRequest (si falla, retorna
     *      422 automáticamente antes de que este método se ejecute).
     *   2. Abrimos una transacción de base de datos para que, si cualquier
     *      paso intermedio falla, todos los cambios se reviertan (rollback)
     *      automáticamente → integridad garantizada.
     *   3. Creamos el registro principal en `orders`.
     *   4. Insertamos los ítems del pedido en `order_items` usando una relación
     *      Eloquent, aprovechando el bloqueo pesimista (lockForUpdate) para
     *      evitar condiciones de carrera en inventario concurrente.
     *   5. Retornamos una respuesta 201 Created con el pedido completo.
     *
     * @param  StoreOrderRequest  $request  Petición ya validada por el FormRequest.
     */
    public function store(StoreOrderRequest $request): JsonResponse
    {
        // ── Paso 1: Obtener datos validados ──────────────────────────────────
        // `validated()` retorna únicamente los campos que pasaron las reglas
        // definidas en StoreOrderRequest. Nunca usar $request->all() aquí.
        $validated = $request->validated();

        // ── Paso 2: Envolver toda la operación en una transacción ────────────
        // DB::transaction() ejecuta el closure y hace COMMIT automático si no
        // hay excepciones, o ROLLBACK si se lanza cualquier Throwable.
        $order = DB::transaction(function () use ($validated) {

            // ── Paso 3: Crear el pedido principal ────────────────────────────
            // Eloquent asigna el UUID automáticamente gracias al trait HasUuids
            // declarado en el modelo Order. Solo pasamos los campos del modelo
            // (excluimos el array `items` que se maneja por separado).
            /** @var Order $order */
            $order = Order::create([
                'customer_id' => $validated['customer_id'],
                'shipping_address_id' => $validated['shipping_address_id'],
                'shipment_id' => $validated['shipment_id'] ?? null,
                'coupon_id' => $validated['coupon_id'] ?? null,
                'status_id' => $validated['status_id'],
                'subtotal' => $validated['subtotal'],
                'discount_total' => $validated['discount_total'],
                'shipping_cost' => $validated['shipping_cost'],
                'total' => $validated['total'],
                'notes' => $validated['notes'] ?? null,
            ]);

            // ── Paso 4: Insertar los ítems del pedido ────────────────────────
            // Iteramos sobre cada ítem enviado por el cliente.
            foreach ($validated['items'] as $item) {

                // Bloqueo pesimista (SELECT … FOR UPDATE):
                // Bloqueamos el registro del producto para que ninguna otra
                // transacción concurrente pueda modificar su stock hasta que
                // esta transacción haga COMMIT o ROLLBACK. Esto es crítico en
                // sistemas con múltiples usuarios comprando al mismo tiempo.
                //
                // Nota: Si tu tabla de inventario es separada (stock/inventory),
                // aplica el lockForUpdate() sobre esa tabla, no sobre products.
                $product = Product::lockForUpdate()->findOrFail($item['product_id']);

                // Aquí podrías verificar stock disponible, por ejemplo:
                // if ($product->stock < $item['quantity']) {
                //     throw new \RuntimeException("Stock insuficiente para {$product->name}");
                // }
                // Y luego decrementar: $product->decrement('stock', $item['quantity']);

                // Creamos el ítem asociado al pedido mediante la relación
                // Eloquent `items()` definida en el modelo Order (hasMany).
                // Esto asigna automáticamente `order_id` al UUID del pedido recién creado.
                $order->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'discount_amount' => $item['discount_amount'] ?? 0,
                ]);
            }

            // Retornamos el pedido para que esté disponible fuera del closure.
            return $order;
        });

        // ── Paso 5: Retornar la respuesta ────────────────────────────────────
        // Cargamos las relaciones eager para que la respuesta JSON incluya los
        // datos del cliente, dirección y los ítems creados, evitando N+1 queries.
        $order->load(['customer', 'shippingAddress', 'items', 'status']);

        // HTTP 201 Created es el código semánticamente correcto para un recurso
        // recién creado en una API REST.
        return response()->json([
            'message' => 'Pedido creado exitosamente.',
            'data' => $order,
        ], 201);
    }
}
