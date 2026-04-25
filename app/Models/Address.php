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
 * @property string $street
 * @property string|null $street2
 * @property string $city
 * @property string $state
 * @property string $zip_code
 * @property string $country
 * @property bool $is_primary
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Customer $customer
 * @property-read Collection<int, Order> $orders
 * @property-read Collection<int, Shipment> $shipments
 */
class Address extends Model
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
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_primary' => 'boolean',
        ];
    }

    // ==========================================
    // RELACIONES
    // ==========================================

    /**
     * Relación N:1 — Cliente propietario de esta dirección.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Relación 1:N — Pedidos que usan esta dirección como destino de envío.
     * La FK en orders no sigue la convención address_id, se especifica explícitamente.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'shipping_address_id');
    }

    /**
     * Relación 1:N — Envíos que parten hacia esta dirección.
     */
    public function shipments(): HasMany
    {
        return $this->hasMany(Shipment::class);
    }
}
