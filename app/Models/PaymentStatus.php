<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Catálogo de estados de pago. PK es un SMALLINT, sin timestamps.
 *
 * @property int $id
 * @property string $name
 * @property string|null $label
 * @property string|null $color
 * @property-read Collection<int, Payment> $payments
 */
class PaymentStatus extends Model
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
     * Relación 1:N — Pagos que se encuentran en este estado.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'status_id');
    }
}
