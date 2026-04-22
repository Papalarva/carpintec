<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * Historial de cambios de estado de un pedido.
 * Usa `changed_at` como timestamp de creación y no tiene updated_at.
 *
 * @property string $id
 * @property string $order_id
 * @property int $status_id
 * @property string|null $user_id
 * @property string|null $notes
 * @property Carbon $changed_at
 * @property-read Order $order
 * @property-read OrderStatus $status
 * @property-read User|null $user
 */
class OrderStatusHistory extends Model
{
    use HasFactory, HasUuids;

    /**
     * Columna que Eloquent usará como "created_at".
     */
    const CREATED_AT = 'changed_at';

    /**
     * Sin columna updated_at en esta tabla de historial inmutable.
     */
    const UPDATED_AT = null;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'order_id',
        'status_id',
        'user_id',
        'notes',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status_id' => 'integer',
            'changed_at' => 'datetime',
        ];
    }

    // ==========================================
    // RELACIONES
    // ==========================================

    /**
     * Relación N:1 — Pedido cuyo estado fue modificado.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Relación N:1 — Estado al que se transitó.
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(OrderStatus::class, 'status_id');
    }

    /**
     * Relación N:1 — Usuario que realizó el cambio de estado (puede ser null para cambios automáticos).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
