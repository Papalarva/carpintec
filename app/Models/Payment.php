<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property string $id
 * @property string $order_id
 * @property int $status_id
 * @property string $amount
 * @property string|null $method
 * @property string|null $mp_preference_id
 * @property string|null $mp_payment_id
 * @property array<string, mixed>|null $mp_data
 * @property Carbon|null $paid_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Order $order
 * @property-read PaymentStatus $status
 */
class Payment extends Model
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
            'status_id' => 'integer',
            'amount' => 'decimal:2',
            'mp_data' => 'array',
            'paid_at' => 'datetime',
        ];
    }

    // ==========================================
    // RELACIONES
    // ==========================================

    /**
     * Relación N:1 — Pedido al que corresponde este pago.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Relación N:1 — Estado del pago (catálogo PaymentStatus).
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(PaymentStatus::class, 'status_id');
    }
}
