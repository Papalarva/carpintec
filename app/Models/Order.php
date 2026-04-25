<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property string $id
 * @property string $customer_id
 * @property string $shipping_address_id
 * @property string|null $shipment_id
 * @property string|null $quotation_id
 * @property string|null $coupon_id
 * @property int $status_id
 * @property string $subtotal
 * @property string $discount_total
 * @property string $shipping_cost
 * @property string $total
 * @property string|null $notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class Order extends Model
{
    use HasFactory, HasUuids;

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

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
     * Relación 1:N — Ítems (líneas) del pedido.
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Relación N:1 — Dirección de envío usada por el pedido.
     */
    public function shippingAddress(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'shipping_address_id');
    }
}
