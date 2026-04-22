<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Catálogo de estados de pedido. PK es un SMALLINT, sin timestamps.
 *
 * @property int $id
 * @property string $name
 * @property string|null $label
 * @property string|null $color
 * @property-read Collection<int, Order> $orders
 * @property-read Collection<int, OrderStatusHistory> $histories
 */
class OrderStatus extends Model
{
    use HasFactory;

    /**
     * La PK es un SMALLINT definido en la BD; no usamos UUIDs ni auto-incremento gestionado por Eloquent.
     */
    public $incrementing = false;

    /**
     * Tipo de la llave primaria.
     */
    protected $keyType = 'integer';

    /**
     * Sin columnas created_at / updated_at en esta tabla de catálogo.
     */
    public $timestamps = false;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'id',
        'name',
        'label',
        'color',
    ];

    // ==========================================
    // RELACIONES
    // ==========================================

    /**
     * Relación 1:N — Pedidos que se encuentran en este estado.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'status_id');
    }

    /**
     * Relación 1:N — Entradas de historial que registran este estado.
     */
    public function histories(): HasMany
    {
        return $this->hasMany(OrderStatusHistory::class, 'status_id');
    }
}
