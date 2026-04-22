<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property string $id
 * @property string $customer_id
 * @property string|null $shipping_address_id
 * @property string|null $shipment_id
 * @property string|null $coupon_id
 * @property int $status_id
 * @property string $subtotal
 * @property string $discount_total
 * @property string $shipping_cost
 * @property string $total
 * @property string|null $notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Customer $customer
 * @property-read Address|null $shippingAddress
 * @property-read Shipment|null $shipment
 * @property-read Coupon|null $coupon
 * @property-read OrderStatus $status
 * @property-read Collection<int, OrderItem> $items
 * @property-read Collection<int, Payment> $payments
 * @property-read Collection<int, OrderStatusHistory> $statusHistory
 */
class Order extends Model
{
    use HasFactory, HasUuids;

    /**
     * Campos permitidos para asignación masiva.
     *
     * @var list<string>
     */
    protected $fillable = [
        'customer_id',
        'shipping_address_id',
        'shipment_id',
        'coupon_id',
        'status_id',
        'subtotal',
        'discount_total',
        'shipping_cost',
        'total',
        'notes',
    ];

    /**
     * Conversión de atributos a tipos nativos de PHP.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'discount_total' => 'decimal:2',
            'shipping_cost' => 'decimal:2',
            'total' => 'decimal:2',
            'status_id' => 'integer',
        ];
    }

    // ==========================================
    // RELACIONES
    // ==========================================

    /**
     * Relación N:1 — Cliente que realizó el pedido.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Relación N:1 — Dirección de envío del pedido.
     * La FK no sigue la convención address_id, se especifica explícitamente.
     */
    public function shippingAddress(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'shipping_address_id');
    }

    /**
     * Relación N:1 — Envío asignado al pedido (puede ser null al crear).
     */
    public function shipment(): BelongsTo
    {
        return $this->belongsTo(Shipment::class);
    }

    /**
     * Relación N:1 — Cupón de descuento aplicado al pedido (opcional).
     */
    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    /**
     * Relación N:1 — Estado actual del pedido (catálogo OrderStatus).
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(OrderStatus::class, 'status_id');
    }

    /**
     * Relación 1:N — Líneas/ítems que componen este pedido.
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Relación 1:N — Pagos procesados para este pedido.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Relación 1:N — Historial de cambios de estado de este pedido.
     */
    public function statusHistory(): HasMany
    {
        return $this->hasMany(OrderStatusHistory::class);
    }
}
