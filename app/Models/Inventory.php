<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * Tabla con PK no-convencional: product_id (UUID) actúa como llave primaria.
 * No usa HasUuids porque el UUID proviene directamente del product_id relacionado.
 *
 * @property string $product_id
 * @property int $quantity
 * @property int $min_quantity
 * @property string|null $location
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Product $product
 */
class Inventory extends Model
{
    use HasFactory;

    /**
     * La llave primaria es product_id (UUID compartido con products).
     */
    protected $primaryKey = 'product_id';

    /**
     * La PK no es auto-incremental.
     */
    public $incrementing = false;

    /**
     * Tipo de la llave primaria.
     */
    protected $keyType = 'string';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'product_id',
        'quantity',
        'min_quantity',
        'location',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'min_quantity' => 'integer',
        ];
    }

    // ==========================================
    // RELACIONES
    // ==========================================

    /**
     * Relación N:1 — Producto al que pertenece este registro de inventario.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
