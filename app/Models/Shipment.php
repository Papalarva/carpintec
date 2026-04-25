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
 * @property string|null $address_id
 * @property string|null $carrier
 * @property string|null $tracking_number
 * @property string $status
 * @property string $cost
 * @property array<string, mixed>|null $api_response
 * @property Carbon|null $estimated_delivery_date
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Address|null $address
 * @property-read Collection<int, Order> $orders
 */
class Shipment extends Model
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
            'cost' => 'decimal:2',
            'api_response' => 'array',
            'estimated_delivery_date' => 'date',
        ];
    }

    // ==========================================
    // RELACIONES
    // ==========================================

    /**
     * Relación N:1 — Dirección de destino del envío.
     */
    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    /**
     * Relación 1:N — Pedidos que utilizan este envío.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
