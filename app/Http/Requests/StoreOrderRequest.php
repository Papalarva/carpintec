<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * StoreOrderRequest
 *
 * Esta clase centraliza toda la lógica de validación para la creación de un
 * nuevo pedido. Al usar un FormRequest en lugar de validar directamente en el
 * controlador, mantenemos los controladores delgados (Thin Controllers) y
 * la validación queda desacoplada y reutilizable.
 */
class StoreOrderRequest extends FormRequest
{
    /**
     * Determina si el usuario autenticado está autorizado a ejecutar esta
     * petición.
     *
     * Retornamos `true` aquí de forma provisional. En producción deberías
     * implementar una Policy de Laravel (OrderPolicy@create) para verificar
     * que solo los usuarios con el rol correcto puedan crear pedidos.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Define las reglas de validación que se aplican a la petición entrante.
     *
     * Cada clave corresponde a un campo del payload JSON/Form que el cliente
     * debe enviar. Los campos opcionales de la BD llevan "nullable".
     *
     * @return array<string, list<string>>
     */
    public function rules(): array
    {
        return [
            // ── Relaciones obligatorias ──────────────────────────────────────

            /**
             * UUID del cliente que realiza el pedido.
             * Debe existir en la tabla `customers`, columna `id`.
             */
            'customer_id' => ['required', 'uuid', 'exists:customers,id'],

            /**
             * UUID de la dirección a la que se enviará el pedido.
             * Debe existir en la tabla `addresses`, columna `id`.
             */
            'shipping_address_id' => ['required', 'uuid', 'exists:addresses,id'],

            // ── Relaciones opcionales ────────────────────────────────────────

            /**
             * UUID del envío asociado. En el momento de la creación del pedido
             * normalmente es null; se asigna cuando el pedido se despacha.
             */
            'shipment_id' => ['nullable', 'uuid', 'exists:shipments,id'],

            /**
             * UUID del cupón de descuento aplicado (si el cliente usa uno).
             */
            'coupon_id' => ['nullable', 'uuid', 'exists:coupons,id'],

            // ── Estado ───────────────────────────────────────────────────────

            /**
             * Clave foránea al catálogo `order_statuses` (SMALLINT).
             * Por defecto el negocio debería asignarlo automáticamente, pero lo
             * incluimos por si el admin lo establece al crear.
             */
            'status_id' => ['required', 'integer', 'exists:order_statuses,id'],

            // ── Totales monetarios ────────────────────────────────────────────

            /**
             * Subtotal: suma de precios de los ítems sin descuentos ni envío.
             * min:0 para evitar valores negativos.
             */
            'subtotal' => ['required', 'numeric', 'min:0', 'max:9999999999.99'],

            /**
             * Total de descuentos aplicados al pedido.
             * Opcional (la BD tiene DEFAULT 0).
             */
            'discount_total' => ['nullable', 'numeric', 'min:0', 'max:9999999999.99'],

            /**
             * Costo de envío. Opcional (la BD tiene DEFAULT 0).
             */
            'shipping_cost' => ['nullable', 'numeric', 'min:0', 'max:9999999999.99'],

            /**
             * Total final que el cliente debe pagar.
             * La regla `gte:subtotal` garantiza coherencia básica entre totales.
             */
            'total' => ['required', 'numeric', 'min:0', 'max:9999999999.99', 'gte:subtotal'],

            // ── Información adicional ─────────────────────────────────────────

            /**
             * Notas del cliente o del administrador sobre el pedido.
             */
            'notes' => ['nullable', 'string', 'max:2000'],

            // ── Ítems del pedido ──────────────────────────────────────────────

            /**
             * El pedido debe contener al menos un ítem.
             * `items` es un array de objetos; cada elemento se valida por
             * separado con la notación de punto (dot notation) de Laravel.
             */
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'uuid', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0'],
            'items.*.discount_amount' => ['nullable', 'numeric', 'min:0'],
        ];
    }

    /**
     * Mensajes de error personalizados (en español).
     *
     * Laravel mostrará estos textos en lugar de los mensajes genéricos en
     * inglés cuando fallen las validaciones correspondientes.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'customer_id.required' => 'El cliente es obligatorio.',
            'customer_id.exists' => 'El cliente seleccionado no existe.',
            'shipping_address_id.required' => 'La dirección de envío es obligatoria.',
            'shipping_address_id.exists' => 'La dirección de envío no existe.',
            'status_id.required' => 'El estado del pedido es obligatorio.',
            'status_id.exists' => 'El estado seleccionado no es válido.',
            'subtotal.required' => 'El subtotal es obligatorio.',
            'subtotal.numeric' => 'El subtotal debe ser un valor numérico.',
            'subtotal.min' => 'El subtotal no puede ser negativo.',
            'total.required' => 'El total es obligatorio.',
            'total.gte' => 'El total no puede ser menor al subtotal.',
            'items.required' => 'El pedido debe contener al menos un producto.',
            'items.*.product_id.required' => 'El producto de cada ítem es obligatorio.',
            'items.*.product_id.exists' => 'Uno o más productos no existen en el catálogo.',
            'items.*.quantity.min' => 'La cantidad mínima por ítem es 1.',
            'items.*.unit_price.min' => 'El precio unitario no puede ser negativo.',
        ];
    }

    /**
     * Prepara los datos para la validación.
     *
     * Aplicamos valores por defecto para los campos opcionales de dinero que
     * la BD tiene con DEFAULT 0. De esta forma el modelo recibe valores
     * concretos y no nulls que podrían romper cálculos.
     */
    protected function prepareForValidation(): void
    {
        $this->mergeIfMissing([
            'discount_total' => 0,
            'shipping_cost' => 0,
        ]);
    }
}
